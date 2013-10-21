<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wilayah extends Admin_Controller {

	protected $form = array(
		array(
			'field'		=> 'nama',
			'label'		=> 'Nama Pelayanan',
			'rules'		=> 'xss_clean|required'
		),
		array(
			'field'		=> 'jenis',
			'label'		=> 'Jenis',
			'rules'		=> 'xss_clean|required'
		)
	);

    public function __construct() {
		parent::__construct();
		$this->load->model('master/Wilayah_Model');
	}

	public function index()	{
		$this->data['sub_nav'] = "master/wilayah/sub_nav";
		$this->template->set_title('Wilayah')
					   ->set_css('alertify.core', array('id' => 'toggleCSS'))
					   ->set_js('jquery.dataTables')
					   ->set_js('alertify.min')
			           ->build('master/wilayah/browse');
	}

	public function load_data() {
		
		$aColumns = array('nama', 'jenis', 'ordering');
		
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
						$aLikes[$aColumns[$i]] = mysql_real_escape_string($_GET['sSearch']);
					}
				}
			}
		}
		
		$wilayahs = $this->Wilayah_Model->getAll($iLimit, $iOffset, array('lft' => 'ASC'), array(), $aLikes);
		
		$rResult = $wilayahs['data'];
		$iFilteredTotal = $wilayahs['total_rows'];
		$iTotal = $wilayahs['total_rows'];
		
		$ordering = array();
		foreach ($rResult as $wilayah) {
			$ordering[$wilayah->parent_id][] = $wilayah->id;
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
		
		foreach ($rResult as $index => $wilayah) {
			
			$orderkey = array_search($wilayah->id, $ordering[$wilayah->parent_id]);
			
			$row = array();
			
			$lvl = $wilayah->level - 1;
			$indent = '';
			for ($i = 0; $i < $lvl; $i++) {
				$indent .= '<span class="gi">|&mdash;</span>';
			}
			
			//$action = "<a   >Edit</a>&nbsp;";
			$row[] = $indent."<a href=\"".site_url("master/wilayah/edit/".$wilayah->id)."\" data-original-title=\"Edit Wilayah\" style=\"color: #0C9ABB\">".$wilayah->nama."</a>";
			$row[] = jenis_wilayah_descr($wilayah->jenis);
			
			$order = "";
			$order .= "<div class=\"btn-group\" data-toggle=\"buttons-radio\" style=\"margin-right: 2px;\">";
			if (($index > 0 || ($index + $iOffset > 0)) && isset($ordering[$wilayah->parent_id][$orderkey - 1])) {
				$order .= "	<a class=\"order_up btn btn-info btn-mini\" type=\"button\" data-id=\"".$wilayah->id."\" >";
				$order .= "		<i class=\"icon-white icon-arrow-up\"></i>";
				$order .= "	</a>";
			}
			else {
				$order .= "<span style=\"display: inline-block; width: 28px;\"></span>";
			}
			if (($index < $iTotal - 1 || $index + $iLimit < $iTotal - 1) && isset($ordering[$wilayah->parent_id][$orderkey + 1])) {
				$order .= "	<a class=\"order_down btn btn-info btn-mini\" type=\"button\" data-id=\"".$wilayah->id."\">";
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
			if ($wilayah->jenis != "Root") {
				$action .= "<a class=\"delete-row btn btn-danger btn-mini\" data-id=\"".$wilayah->id."\" href=\"#\" data-original-title=\"Hapus\">Hapus</a>&nbsp;";
			}
			$row[] = $action;
			//$row[] = '';
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}

	public function add() {
		$this->data['is_new'] = true;
		$this->_update();
	}
	
	public function edit($id) {
		$this->data['is_new'] = false;
		$this->_update($id);
	}
    
    private function _update($id = 0) {
		$this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error" style="color: #CC0000;">', '</div>');
		$this->form_validation->set_message('required', 'Field %s diperlukan');
        $this->form_validation->set_rules($this->form);
        
        if ($this->input->post('batal')) {
            redirect('master/wilayah', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $wilayah = $this->_getDataObject();
				$this->Wilayah_Model->save($wilayah);
                redirect('master/wilayah', 'refresh');
            }
			else {
				$this->session->set_flashdata('notification', array('type' => 'success' , 'message' => 'GAGAL.'));
			}
        }
		
		if ($id) {
			$wilayah = $this->Wilayah_Model->getBy(array('id' => $id));
			$wilayah->old_parent_id = $wilayah->parent_id;
		}
		else {
			$wilayah = $this->_getEmptyDataObject();
		}
		
		$this->data['data'] = $wilayah;
		$this->data['jenis_list'] = jenis_wilayah();
		
		$parent_list = $this->Wilayah_Model->getAll(0, 0, array('lft' => 'ASC'), array('jenis <=' => 4));
		$this->data['parent_list'] = $parent_list['data'];
		
		$this->data['sub_nav'] = "master/wilayah/sub_nav";
		$this->template->set_title('Tambah/Edit Wilayah')
					   ->set_js('autoNumeric')
			           ->build('master/wilayah/edit');
    }
    
	public function delete() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Wilayah_Model->delete($id);
		}
    }
	
	public function order_up() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Wilayah_Model->order_up($id);
		}
	}
	
	public function order_down() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Wilayah_Model->order_down($id);
		}
	}
    
    private function _getEmptyDataObject() {
		$wilayah = new stdClass();
		$wilayah->id			= 0;
        $wilayah->nama			= '';
		$wilayah->jenis			= 0;
		$wilayah->parent_id		= 0;
		$wilayah->old_parent_id	= 0;
		$wilayah->version		= 0;
        return $wilayah;
    }
    
    private function _getDataObject() {
		$this->input->post(NULL, TRUE);

        $wilayah = new stdClass();
		$wilayah->id			= $this->input->post('id') && ($this->input->post('id') > 0) ? $this->input->post('id') : 0;
		$wilayah->nama			= $this->input->post('nama');
		$wilayah->jenis			= $this->input->post('jenis');
		$wilayah->parent_id		= $this->input->post('parent_id');
		$wilayah->old_parent_id	= $this->input->post('old_parent_id');
		$wilayah->version		= $this->input->post('version');
        return $wilayah;
    }
    
}

/* End of file wilayah.php */
/* Location: ./application/controllers/wilayah.php */