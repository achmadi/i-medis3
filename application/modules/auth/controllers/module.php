<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Module extends MY_Controller {

	protected $form = array(
		array(
			'field'		=> 'nama',
			'label'		=> 'Nama',
			'rules'		=> 'xss_clean|required'
		),
		array(
			'field'		=> 'jenis',
			'label'		=> 'Jenis Module',
			'rules'		=> 'xss_clean|required'
		)
	);
	
    public function __construct() {
		parent::__construct();
		$this->load->model('auth/Auth_Module_Model');
	}
	
	public function index()	{
		$this->template->set_title('Module')
					   ->set_css('alertify.core', array('id' => 'toggleCSS'))
					   ->set_js('jquery.dataTables')
					   ->set_js('alertify.min')
					   ->set_js('jquery.validate.min')
			           ->build('auth/module/browse');
	}
    
	public function load_data() {
		
		$aColumns = array('nama', 'jenis');
		
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
		
		$modules = $this->Auth_Module_Model->getAll($iLimit, $iOffset, $aOrders, array(), $aLikes);
		
		$rResult = $modules['data'];
		$iFilteredTotal = $modules['total_rows'];
		$iTotal = $modules['total_rows'];
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		foreach ($rResult as $module) {
			$row = array();
			$row[] = "<a href=\"".site_url("auth/module/edit/".$module->id)."\" data-original-title=\"Edit Module\" style=\"color: #0C9ABB\">".$module->nama."</a>";
			$row[] = $this->Auth_Module_Model->getJenisModuleDescr($module->jenis);

			$action = "<a class=\"delete-row btn btn-danger btn-mini\" href=\"#\" data-id=\"".$module->id."\" data-original-title=\"Hapus\">Hapus</a>";
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
        $this->form_validation->set_error_delimiters('<div class="error" style="color: #CC0000; margin-left: 150px;">', '</div>');
		$this->form_validation->set_rules($this->form);
        
        if ($this->input->post('batal')) {
            redirect('auth/module', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run()) {
                $modules = $this->_getDataObject();
                if (isset($modules->id) && $modules->id > 0) {
                    $this->Auth_Module_Model->update($modules);
                }
                else {
                    $this->Auth_Module_Model->create($modules);
                }
                redirect('auth/module', 'refresh');
            }
        }
		
		if (!empty($id)) {
			$modules = $this->Auth_Module_Model->getById($id);
		}
		else {
			$modules = $this->_getEmptyDataObject();
		}
		
		$this->data['data'] = $modules;
		$this->data['jenis_list'] = $this->Auth_Module_Model->getJenisModule();
		
		$this->data['sub_nav'] = "auth/module/sub_nav";
		$this->template->set_title('Tambah/Edit Module')
			           ->build('auth/module/edit');
    }
    
	public function delete($id) {
		$this->Auth_Module_Model->delete($id);
        redirect('auth/modules', 'refresh');
    }
    
    private function _getEmptyDataObject()  {
		$modules = new stdClass();
		$modules->id	= 0;
        $modules->nama	= '';
		$modules->jenis	= '';
        return $modules;
    }
    
    private function _getDataObject()  {
		$this->input->post(NULL, TRUE);
		
        $modules = new stdClass();
		$modules->id	= $this->input->post('id') && ($this->input->post('id') > 0) ? $this->input->post('id') : 0;
        $modules->nama	= $this->input->post('nama');
		$modules->jenis	= $this->input->post('jenis');
        return $modules;
    }
    
}

/* End of file modules.php */
/* Location: ./application/controllers/modules.php */