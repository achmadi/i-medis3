<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Komponen_Tarif_Askes extends Admin_Controller {

	protected $form = array(
		array(
			'field'		=> 'nama',
			'label'		=> 'Nama Pelayanan',
			'rules'		=> 'xss_clean|required'
		)
	);
	
    public function __construct() {
		parent::__construct();
		$this->load->model('master/Kelompok_Pelayanan_Askes_Model');
		$this->load->model('master/Komponen_Tarif_Askes_Model');
	}

	public function index()	{
		if ($this->input->get('kelompok_pelayanan_askes_id')) {
			$kelompok_pelayanan_askes_id = $this->input->get('kelompok_pelayanan_askes_id');
		}
		else {
			redirect('master/kelompok_pelayanan_askes', 'refresh');
		}
		
		$this->data['kelompok_pelayanan_askes'] = $this->Kelompok_Pelayanan_Askes_Model->getBy(array('id' => $kelompok_pelayanan_askes_id));
		$this->data['sub_nav'] = "master/komponen_tarif_askes/sub_nav";
		$this->template->set_title('Komponen Tarif Askes')
					   ->set_css('alertify.core', array('id' => 'toggleCSS'))
					   ->set_js('jquery.dataTables')
					   ->set_js('alertify.min')
			           ->build('master/komponen_tarif_askes/browse');
	}
	
	public function load_data() {
		
		$aColumns = array('nama', 'tarif', 'bmhp', 'yan', 'medik', 'anest');
		
		/* 
		 * Paging
		 */
		if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' ) {
			$iLimit = intval( $_GET['iDisplayLength'] );
			$iOffset = intval( $_GET['iDisplayStart'] );
		}
		
		/*
		 * Ordering
		 */
		$aOrders = array();
		if (isset($_GET['iSortCol_0'])) {
			for ($i = 0; $i <intval($_GET['iSortingCols']); $i++) {
				if ($_GET['bSortable_'.intval($_GET['iSortCol_'.$i])] == "true") {
					if ($aColumns[intval($_GET['iSortCol_'.$i])] == 'ordering')
						$aOrders['n.lft'] = $_GET['sSortDir_'.$i] === 'asc' ? 'asc' : 'desc';
					else
						$aOrders[$aColumns[intval($_GET['iSortCol_'.$i])]] = $_GET['sSortDir_'.$i] === 'asc' ? 'asc' : 'desc';
				}
			}
		}
		
		/*
		 * Like
		 */
		$aLikes = array();
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
			for ($i = 0; $i < count($aColumns); $i++) {
				if (isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true") {
					$aLikes[$aColumns[$i]] = mysql_real_escape_string($_GET['sSearch']);
				}
			}
		}
		
		$kelompok_pelayanan_askes_id = $this->input->get('kelompok_pelayanan_askes_id');
		$root = $this->Komponen_Tarif_Askes_Model->getBy(array('kelompok_pelayanan_askes_id' => $kelompok_pelayanan_askes_id, 'jenis' => 'Root'));
		$komponens = $this->Komponen_Tarif_Askes_Model->get_tree($root->id, $iLimit, $iOffset, $aOrders, array(), $aLikes);
		
		$rResult = $komponens['data'];
		$iFilteredTotal = $komponens['total_rows'];
		$iTotal = $komponens['total_rows'];
		if ($iTotal > 0)
			$iTotal -= 1;
		
		$ordering = array();
		foreach ($rResult as $tarif) {
			$ordering[$tarif->parent_id][] = $tarif->id;
		}
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		foreach ($rResult as $index => $tarif) {
			
			if ($tarif->jenis != 'Root') {
				$orderkey = array_search($tarif->id, $ordering[$tarif->parent_id]);

				$row = array();

				$lvl = $tarif->level - 1;
				$indent = '';
				for ($i = 0; $i < $lvl; $i++) {
					$indent .= '<span class="gi">|&mdash;</span>';
				}

				$row[] = $indent.$tarif->nama;
				$row[] = number_format($tarif->tarif, 2, ',', '.');
				$row[] = number_format($tarif->bmhp, 2, ',', '.');
				$row[] = number_format($tarif->sarana, 2, ',', '.');
				$row[] = number_format($tarif->yan, 2, ',', '.');
				$row[] = number_format($tarif->medik, 2, ',', '.');
				$row[] = number_format($tarif->anest, 2, ',', '.');
				$row[] = number_format(0, 2, ',', '.');

				$order = "";
				$order .= "<div class=\"btn-group\" data-toggle=\"buttons-radio\" style=\"margin-right: 2px;\">";
				if (($index > 0 || ($index + $iOffset > 0)) && isset($ordering[$tarif->parent_id][$orderkey - 1])) {
					$order .= "	<a class=\"order_up btn btn-info btn-mini\" type=\"button\" data-id=\"".$tarif->id."\" >";
					$order .= "		<i class=\"icon-white icon-arrow-up\"></i>";
					$order .= "	</a>";
				}
				else {
					$order .= "<span style=\"display: inline-block; width: 28px;\"></span>";
				}
				if (($index < $iTotal - 1 || $index + $iLimit < $iTotal - 1) && isset($ordering[$tarif->parent_id][$orderkey + 1])) {
					$order .= "	<a class=\"order_down btn btn-info btn-mini\" type=\"button\" data-id=\"".$tarif->id."\" >";
					$order .= "		<i class=\" icon-white icon-arrow-down\"></i>";
					$order .= "	</a>";
				}
				else {
					$order .= "<span style=\"display: inline-block; width: 28px;\"></span>";
				}
				$order .= "</div>";
				$order .= "<span class=\"label\">&nbsp;".strval($orderkey + 1)."&nbsp;</span>";
				$row[] = $order;

				$action = "";
				if ($tarif->jenis != "Root") {
					$action = "<a class=\"btn btn-info btn-mini\" href=\"".site_url("master/komponen_tarif_askes/edit/".$tarif->id)."\" data-original-title=\"Edit\">Edit</a>&nbsp;";
					$action .= "<a class=\"delete-row btn btn-danger btn-mini\" data-id=\"".$tarif->id."\" href=\"#\" data-original-title=\"Hapus\">Hapus</a>&nbsp;";
				}
				$row[] = $action;

				$output['aaData'][] = $row;
			}
		}
		
		echo json_encode($output);
	}

	public function add() {
		if ($this->input->get('kelompok_pelayanan_askes_id')) {
			$kelompok_pelayanan_askes_id = $this->input->get('kelompok_pelayanan_askes_id');
		}
		else {
			redirect('master/kelompok_pelayanan_askes', 'refresh');
		}
		
		$this->data['kelompok_pelayanan_askes_id'] = $kelompok_pelayanan_askes_id;
		$this->data['is_new'] = true;
		$this->_update();
	}
	
	public function edit($id) {
		if ($this->input->get('kelompok_pelayanan_askes_id')) {
			$kelompok_pelayanan_askes_id = $this->input->get('kelompok_pelayanan_askes_id');
		}
		else {
			redirect('master/kelompok_pelayanan_askes', 'refresh');
		}
		
		$this->data['kelompok_pelayanan_askes_id'] = $kelompok_pelayanan_askes_id;
		$this->data['is_new'] = false;
		$this->_update($id);
	}
    
    private function _update($id = 0) {
		$this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error" style="color: #CC0000;">', '</div>');
		$this->form_validation->set_message('required', 'Field %s diperlukan');
        $this->form_validation->set_rules($this->form);
        
        if ($this->input->post('batal')) {
            redirect('master/komponen_tarif_askes/index?kelompok_pelayanan_askes_id='.$this->data['kelompok_pelayanan_askes_id'], 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $tarif = $this->_getDataObject();
				$this->Komponen_Tarif_Askes_Model->save($tarif);
                redirect('master/komponen_tarif_askes/index?kelompok_pelayanan_askes_id='.$this->data['kelompok_pelayanan_askes_id'], 'refresh');
            }
			else {
				$this->session->set_flashdata('notification', array('type' => 'success' , 'message' => 'GAGAL.'));
			}
        }
		
		if ($id) {
			$tarif = $this->Komponen_Tarif_Askes_Model->getBy(array('id' => $id));
			$tarif->old_parent_id = $tarif->parent_id;
		}
		else {
			$tarif = $this->_getEmptyDataObject();
		}
		
		$this->data['data'] = $tarif;
		
		$parent_list = $this->Komponen_Tarif_Askes_Model->getAll(0, 0, array('lft' => 'ASC'));
		$this->data['parent_list'] = $parent_list['data'];
		
		$this->data['sub_nav'] = "master/komponen_tarif_askes/sub_nav";
		$this->template->set_title('Tambah/Edit Jenis Pelayanan Rawat Inap')
					   ->set_js('autoNumeric')
			           ->build('master/komponen_tarif_askes/edit');
    }
    
	public function delete() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Komponen_Tarif_Askes_Model->delete($id);
		}
    }
	
	public function order_up() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Komponen_Tarif_Askes_Model->order_up($id);
		}
	}
	
	public function order_down() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Komponen_Tarif_Askes_Model->order_down($id);
		}
	}
    
    private function _getEmptyDataObject() {
		$komponen = new stdClass();
		$komponen->id				= 0;
        $komponen->nama				= '';
		$komponen->jenis			= 'Kelompok';
		$komponen->paket			= false;
		$komponen->tarif			= 0;
		$komponen->bmhp				= 0;
		$komponen->sarana			= 0;
		$komponen->yan				= 0;
		$komponen->medik			= 0;
		$komponen->anest			= 0;
		$komponen->parent_id		= 0;
		$komponen->old_parent_id	= 0;
		$komponen->version			= 0;
        return $komponen;
    }
    
    private function _getDataObject() {
		$this->input->post(NULL, TRUE);

        $komponen = new stdClass();
		$komponen->id				= $this->input->post('id') && ($this->input->post('id') > 0) ? $this->input->post('id') : 0;
		$komponen->nama				= $this->input->post('nama');
		$komponen->jenis			= $this->input->post('jenis');
		$komponen->paket			= $this->input->post('paket');
		$tarif = $this->input->post('tarif');
		$komponen->tarif			= floatval(str_replace(',', '.', str_replace('.', '', $tarif)));
		$bmhp = $this->input->post('bmhp');
		$komponen->bmhp				= floatval(str_replace(',', '.', str_replace('.', '', $bmhp)));
		$sarana = $this->input->post('sarana');
		$komponen->sarana			= floatval(str_replace(',', '.', str_replace('.', '', $sarana)));
		$yan = $this->input->post('yan');
		$komponen->yan				= floatval(str_replace(',', '.', str_replace('.', '', $yan)));
		$medik = $this->input->post('medik');
		$komponen->medik			= floatval(str_replace(',', '.', str_replace('.', '', $medik)));
		$anest = $this->input->post('anest');
		$komponen->anest			= floatval(str_replace(',', '.', str_replace('.', '', $anest)));
		$komponen->parent_id		= $this->input->post('parent_id');
		$komponen->old_parent_id	= $this->input->post('old_parent_id');
		$komponen->version			= $this->input->post('version');
        return $komponen;
    }
    
}

/* End of file tarif_pelayanan.php */
/* Location: ./application/controllers/tarif_pelayanan.php */