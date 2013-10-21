<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Unit extends CI_Controller {
	
	protected $form = array(
		array(
			'field'		=> 'nama',
			'label'		=> 'Nama',
			'rules'		=> 'xss_clean|required'
		),
		array(
			'field'		=> 'jenis',
			'label'		=> 'Jenis',
			'rules'		=> 'xss_clean|greater_than[0]'
		)
	);
	
    public function __construct() {
		parent::__construct();
		$this->load->model('master/Unit_Model');
	}
	
	public function index()	{
		$this->data['sub_nav'] = "master/unit/sub_nav";
		$this->template->set_title('Unit')
					   ->set_css('alertify.core', array('id' => 'toggleCSS'))
					   ->set_js('jquery.dataTables')
					   ->set_js('alertify.min')
			           ->build('master/unit/browse');
	}
	
	public function load_data() {
		
		$aColumns = array('nama');
		
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
					if ($aColumns[$i] != 'ordering') {
						$aOrders[$aColumns[intval($_GET['iSortCol_'.$i])]] = $_GET['sSortDir_'.$i] === 'asc' ? 'asc' : 'desc';
					}
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
		
		$units = $this->Unit_Model->getAll($iLimit, $iOffset, $aOrders, array(), array(), $aLikes);
		
		$rResult = $units['data'];
		$iFilteredTotal = $units['total_rows'];
		$iTotal = $units['total_rows'];
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		foreach ($rResult as $unit) {
			$row = array();
			$row[] = "<a href=\"".site_url("master/unit/edit/".$unit->id)."\" style=\"color: #0C9ABB\">".$unit->nama."</a>";
			$row[] = get_jenis_unit_descr($unit->jenis);
			
			$action = "<a class=\"delete-row btn btn-danger btn-mini\" href=\"#\" data-id=\"".$unit->id."\" data-original-title=\"Hapus\">Hapus</a>";
			$row[] = $action;
			
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
        $this->form_validation->set_error_delimiters('<div class="span12 error" style="color: #CC0000;">', '</div>');
		$this->form_validation->set_message('required', 'Field %s diperlukan');
		$this->form_validation->set_message('greater_than', 'Field %s diperlukan');
		$this->form_validation->set_message('less_then', 'Field %s diperlukan');
        $this->form_validation->set_rules($this->form);
        
        if ($this->input->post('batal')) {
            redirect('master/unit', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run()) {
                $unit = $this->_getDataObject();
                if (isset($unit->id) && $unit->id > 0) {
                    $this->Unit_Model->update($unit);
                }
                else {
                    $this->Unit_Model->create($unit);
                }
                redirect('master/unit', 'refresh');
            }
        }
		
		if (!empty($id)) {
			$unit = $this->Unit_Model->getBy(array('id' => $id));
		}
		else {
			$unit = $this->_getEmptyDataObject();
		}
		
		$this->data['data'] = $unit;
		$this->data['jenis_unit_list'] = get_jenis_unit();
		
		$this->data['sub_nav'] = "master/unit/sub_nav";
		$this->template->set_title('Tambah/Edit Unit')
					   ->set_js('jquery.dataTables')
			           ->build('master/unit/edit');
    }
	
	public function delete() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Unit_Model->delete($id);
		}
    }
    
    private function _getEmptyDataObject() {
		$unit = new stdClass();
		$unit->id		= 0;
        $unit->nama		= '';
		$unit->jenis	= 0;
        return $unit;
    }
    
    private function _getDataObject() {
		$this->input->post(NULL, TRUE);
		
        $unit = new stdClass();
		$unit->id		= $this->input->post('id') && ($this->input->post('id') > 0) ? $this->input->post('id') : 0;
        $unit->nama		= $this->input->post('nama');
		$unit->jenis	= $this->input->post('jenis');
        return $unit;
    }
    
}

/* End of file unit.php */
/* Location: ./application/modules/master/controllers/unit.php */