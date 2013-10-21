<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tarif_Pelayanan extends Admin_Controller {

	protected $form = array(
		array(
			'field'		=> 'nama',
			'label'		=> 'Nama Pelayanan',
			'rules'		=> 'xss_clean|required'
		)
	);

    public function __construct() {
		parent::__construct();
		$this->load->model('master/Tarif_Pelayanan_Model');
		$this->load->model('master/Unit_Model');
	}

	public function index()	{
		if ($this->input->get('unit_id')) {
			$unit_id = $this->input->get('unit_id');
		}
		else {
			redirect('master/jenis_pelayanan', 'refresh');
		}
		
		$this->data['unit'] = $this->Unit_Model->getBy(array('id' => $unit_id));
		$this->data['sub_nav'] = "master/tarif_pelayanan/sub_nav";
		$this->template->set_title('Jenis Pelayanan')
					   ->set_css('alertify.core', array('id' => 'toggleCSS'))
					   ->set_js('jquery.dataTables')
					   ->set_js('alertify.min')
			           ->build('master/tarif_pelayanan/browse');
	}

	public function load_data() {
		
		$aColumns = array('nama', 'jasa_sarana', 'jasa_pelayanan', 'ordering');
		
		$unit_id = $this->input->get("unit_id");
		
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
					if ($aColumns[$i] == 'nama') {
						$aLikes['n.'.$aColumns[$i]] = mysql_real_escape_string($_GET['sSearch']);
					}
				}
			}
		}
		
		$row = $this->Tarif_Pelayanan_Model->getBy(array('unit_id' => $unit_id, 'jenis' => 'Root'));
		$tarif_pelayanans = $this->Tarif_Pelayanan_Model->get_tree($row->id, $iLimit, $iOffset, $aOrders, array(), $aLikes);
		
		$rResult = $tarif_pelayanans['data'];
		$iFilteredTotal = $tarif_pelayanans['total_rows'];
		$iTotal = $tarif_pelayanans['total_rows'];
		
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
			$orderkey = array_search($tarif->id, $ordering[$tarif->parent_id]);
			
			$row = array();
			
			$lvl = $tarif->level - 1;
			$indent = '';
			for ($i = 0; $i < $lvl; $i++) {
				$indent .= '<span class="gi">|&mdash;</span>';
			}
				
			$row[] = $indent.$tarif->nama;
			$row[] = number_format($tarif->jasa_sarana, 2, ',', '.');
			$row[] = number_format($tarif->jasa_pelayanan, 2, ',', '.');
			
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
				$action = "<a class=\"btn btn-info btn-mini\" href=\"".site_url("master/tarif_pelayanan/edit/".$tarif->id."?unit_id=".$unit_id)."\" data-original-title=\"Edit\">Edit</a>&nbsp;";
				$action .= "<a class=\"delete-row btn btn-danger btn-mini\" data-id=\"".$tarif->id."\" href=\"#\" data-original-title=\"Hapus\">Hapus</a>&nbsp;";
			}
			$row[] = $action;
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}

	public function add() {
		if ($this->input->get('unit_id')) {
			$unit_id = $this->input->get('unit_id');
		}
		else {
			redirect('master/jenis_pelayanan', 'refresh');
		}
		
		$this->data['unit_id'] = $unit_id;
		$this->data['is_new'] = true;
		$this->_update();
	}
	
	public function edit($id) {
		if ($this->input->get('unit_id')) {
			$unit_id = $this->input->get('unit_id');
		}
		else {
			redirect('master/jenis_pelayanan', 'refresh');
		}
		
		$this->data['unit_id'] = $unit_id;
		$this->data['is_new'] = false;
		$this->_update($id);
	}
    
    private function _update($id = 0) {
		$this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error" style="color: #CC0000;">', '</div>');
		$this->form_validation->set_message('required', 'Field %s diperlukan');
        $this->form_validation->set_rules($this->form);
        
        if ($this->input->post('batal')) {
            redirect('master/tarif_pelayanan/index?unit_id='.$this->data['unit_id'], 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $tarif = $this->_getDataObject();
				$this->Tarif_Pelayanan_Model->save($tarif);
                redirect('master/tarif_pelayanan/index?unit_id='.$this->data['unit_id'], 'refresh');
            }
			else {
				$this->session->set_flashdata('notification', array('type' => 'success' , 'message' => 'GAGAL.'));
			}
        }
		
		if ($id) {
			$tarif = $this->Tarif_Pelayanan_Model->getBy(array('id' => $id));
			$tarif->old_parent_id = $tarif->parent_id;
		}
		else {
			$tarif = $this->_getEmptyDataObject();
		}
		
		$this->data['data'] = $tarif;
		
		$root = $this->Tarif_Pelayanan_Model->getBy(array('unit_id' => $this->data['unit_id'], 'jenis' => 'Root'));
		$parent_list = $this->Tarif_Pelayanan_Model->get_tree($root->id, 0, 0, array('n.lft' => 'ASC'), array('n.jenis <>' => 'Rincian'));
		$this->data['parent_list'] = $parent_list['data'];
		
		$this->data['sub_nav'] = "master/tarif_pelayanan/sub_nav";
		$this->template->set_title('Tambah/Edit Jenis Pelayanan Rawat Inap')
					   ->set_js('autoNumeric')
			           ->build('master/tarif_pelayanan/edit');
    }
    
	public function delete() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Tarif_Pelayanan_Model->delete($id);
		}
    }
	
	public function order_up() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Tarif_Pelayanan_Model->order_up($id);
		}
	}
	
	public function order_down() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Tarif_Pelayanan_Model->order_down($id);
		}
	}
    
    private function _getEmptyDataObject() {
		$pelayanan = new stdClass();
		$pelayanan->id				= 0;
		$pelayanan->unit_id			= 0;
        $pelayanan->nama			= '';
		$pelayanan->jenis			= 'Kelompok';
		$pelayanan->jasa_sarana		= 0;
		$pelayanan->jasa_pelayanan	= 0;
		$pelayanan->parent_id		= 0;
		$pelayanan->old_parent_id	= 0;
		$pelayanan->version			= 0;
        return $pelayanan;
    }
    
    private function _getDataObject() {
		$this->input->post(NULL, TRUE);

        $pelayanan = new stdClass();
		$pelayanan->id				= $this->input->post('id') && ($this->input->post('id') > 0) ? $this->input->post('id') : 0;
        $pelayanan->unit_id			= $this->input->post('unit_id');
		$pelayanan->nama			= $this->input->post('nama');
		$pelayanan->jenis			= $this->input->post('jenis');
		$jasa_sarana				= $this->input->post('jasa_sarana');
		$pelayanan->jasa_sarana		= floatval(str_replace(',', '.', str_replace('.', '', $jasa_sarana)));
		$jasa_pelayanan				= $this->input->post('jasa_pelayanan');
		$pelayanan->jasa_pelayanan	= floatval(str_replace(',', '.', str_replace('.', '', $jasa_pelayanan)));
		$pelayanan->parent_id		= $this->input->post('parent_id');
		$pelayanan->old_parent_id	= $this->input->post('old_parent_id');
		$pelayanan->version			= $this->input->post('version');
        return $pelayanan;
    }
    
}

/* End of file tarif_pelayanan.php */
/* Location: ./application/controllers/tarif_pelayanan.php */