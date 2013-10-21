<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_Komponen_JP extends Admin_Controller {

	protected $form = array(
		array(
			'field'		=> 'kelompok_id',
			'label'		=> 'Nama Kelompok',
			'rules'		=> 'xss_clean|required'
		)
	);

    public function __construct() {
		parent::__construct();
		$this->load->model('master/Unit_Model');
		$this->load->model('master/Unit_Detail_Model');
		$this->load->model('master/Kelompok_Pegawai_Model');
		$this->load->model('master/Pegawai_Model');
	}

	public function index()	{
		if ($this->input->get('unit_id')) {
			$unit_id = $this->input->get('unit_id');
		}
		else {
			redirect('master/jenis_pelayanan/setup_jp', 'refresh');
		}
		
		$this->data['unit'] = $this->Unit_Model->getBy(array('id' => $unit_id));
		$this->data['sub_nav'] = "master/setup_komponen_jp/sub_nav";
		$this->template->set_title('Setup Komponen Jenis Pelayanan')
					   ->set_css('alertify.core', array('id' => 'toggleCSS'))
					   ->set_js('jquery.dataTables')
					   ->set_js('alertify.min')
			           ->build('master/setup_komponen_jp/browse');
	}

	public function load_data() {
		
		$aColumns = array('nama', 'ordering');
		
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
					$aLikes[$aColumns[$i]] = mysql_real_escape_string($_GET['sSearch']);
				}
			}
		}
		
		$root = $this->Unit_Detail_Model->getBy(array('unit_id' => $unit_id, 'unit_detail.jenis' => 'Root'));
		$details = $this->Unit_Detail_Model->get_tree($root->id, $iLimit, $iOffset, $aOrders, array(), $aLikes);
		
		$rResult = $details['data'];
		$iFilteredTotal = $details['total_rows'];
		$iTotal = $details['total_rows'];
		
		$ordering = array();
		foreach ($rResult as $detail) {
			$ordering[$detail->parent_id][] = $detail->id;
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
		
		foreach ($rResult as $index => $detail) {
			if ($detail->jenis != 'Root') {
				$orderkey = array_search($detail->id, $ordering[$detail->parent_id]);

				$row = array();

				$lvl = $detail->level - 2;
				$indent = '';
				for ($i = 0; $i < $lvl; $i++) {
					$indent .= '<span class="gi">|&mdash;</span>';
				}

				$row[] = $indent.$detail->kelompok;

				$order = "";
				$order .= "<div class=\"btn-group\" data-toggle=\"buttons-radio\" style=\"margin-right: 2px;\">";
				if (($index > 0 || ($index + $iOffset > 0)) && isset($ordering[$detail->parent_id][$orderkey - 1])) {
					$order .= "	<a class=\"order_up btn btn-info btn-mini\" type=\"button\" data-id=\"".$detail->id."\" >";
					$order .= "		<i class=\"icon-white icon-arrow-up\"></i>";
					$order .= "	</a>";
				}
				else {
					$order .= "<span style=\"display: inline-block; width: 28px;\"></span>";
				}
				if (($index < $iTotal - 1 || $index + $iLimit < $iTotal - 1) && isset($ordering[$detail->parent_id][$orderkey + 1])) {
					$order .= "	<a class=\"order_down btn btn-info btn-mini\" type=\"button\" data-id=\"".$detail->id."\" >";
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
				if ($detail->jenis != "Root") {
					$action = "<a class=\"btn btn-info btn-mini\" href=\"".site_url("master/setup_komponen_jp/edit/".$detail->id."?unit_id=".$unit_id)."\" data-original-title=\"Edit\">Edit</a>&nbsp;";
					$action .= "<a class=\"delete-row btn btn-danger btn-mini\" data-id=\"".$detail->id."\" href=\"#\" data-original-title=\"Hapus\">Hapus</a>&nbsp;";
				}
				$row[] = $action;

				$output['aaData'][] = $row;
			}
		}
		
		echo json_encode($output);
	}

	public function add() {
		if ($this->input->get('unit_id')) {
			$unit_id = $this->input->get('unit_id');
		}
		else {
			redirect('master/setup_komponen_jp', 'refresh');
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
			redirect('master/setup_komponen_jp', 'refresh');
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
            redirect('master/setup_komponen_jp/index?unit_id='.$this->data['unit_id'], 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $komponen = $this->_getDataObject();
				$this->Unit_Detail_Model->save($komponen);
                redirect('master/setup_komponen_jp/index?unit_id='.$this->data['unit_id'], 'refresh');
            }
			else {
				$this->session->set_flashdata('notification', array('type' => 'success' , 'message' => 'GAGAL.'));
			}
        }
		
		if ($id) {
			$komponen = $this->Unit_Detail_Model->getBy(array('unit_detail.id' => $id));
			$komponen->old_parent_id = $komponen->parent_id;
		}
		else {
			$komponen = $this->_getEmptyDataObject();
		}
		
		$this->data['data'] = $komponen;
		
		$kelompoks = $this->Kelompok_Pegawai_Model->getAll(0);
		$this->data['kelompok_list'] = $kelompoks['data'];
		
		$this->data['jenis_kelompok_list'] = get_jenis_kelompok_pegawai();
		
		$root = $this->Unit_Detail_Model->getBy(array('unit_id' => $this->data['unit_id'], 'unit_detail.jenis' => 'Root'));
		$parent_list = $this->Unit_Detail_Model->get_tree($root->id, 0, 0, array('n.lft' => 'ASC'));
		$this->data['parent_list'] = $parent_list['data'];
		
		$this->data['sub_nav'] = "master/setup_komponen_jp/sub_nav";
		$this->template->set_title('Tambah/Edit Setup Komponen Jasa Pelayanan')
					   ->set_js('autoNumeric')
			           ->build('master/setup_komponen_jp/edit');
    }
    
	public function delete() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Unit_Detail_Model->delete($id);
		}
    }
	
	public function order_up() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Unit_Detail_Model->order_up($id);
		}
	}
	
	public function order_down() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Unit_Detail_Model->order_down($id);
		}
	}
    
    private function _getEmptyDataObject() {
		$komponen = new stdClass();
		$komponen->id				= 0;
		$komponen->unit_id			= 0;
        $komponen->kelompok_id		= 0;
		$komponen->jenis			= 'Rincian';
		$komponen->parent_id		= 0;
		$komponen->old_parent_id	= 0;
		$komponen->version			= 0;
        return $komponen;
    }
    
    private function _getDataObject() {
		$this->input->post(NULL, TRUE);

        $komponen = new stdClass();
		$komponen->id				= $this->input->post('id') && ($this->input->post('id') > 0) ? $this->input->post('id') : 0;
        $komponen->unit_id			= $this->input->post('unit_id');
		$komponen->kelompok_id		= $this->input->post('kelompok_id');
		$komponen->jenis			= $this->input->post('jenis');
		$komponen->parent_id		= $this->input->post('parent_id');
		$komponen->old_parent_id	= $this->input->post('old_parent_id');
		$komponen->version			= $this->input->post('version');
        return $komponen;
    }
    
}

/* End of file setup_komponen_jp.php */
/* Location: ./application/controllers/setup_komponen_jp.php */