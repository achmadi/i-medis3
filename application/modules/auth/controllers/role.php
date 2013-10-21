<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Role extends MY_Controller {
	
	protected $form = array(
		array(
			'field'		=> 'nama',
			'label'		=> 'Nama',
			'rules'		=> 'xss_clean|required'
		)
	);
	
    public function __construct() {
		parent::__construct();
		$this->load->model('Auth_Role_Model');
		$this->load->model('Auth_Module_Model');
		$this->load->model('Auth_Role_Module_Model');
	}
	
	public function index()	{
		$this->template->set_title('Role')
					   ->set_css('alertify.core', array('id' => 'toggleCSS'))
					   ->set_js('jquery.dataTables')
					   ->set_js('alertify.min')
					   ->set_js('jquery.validate.min')
			           ->build('auth/role/browse');
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
		
		$roles = $this->Auth_Role_Model->getAll($iLimit, $iOffset, $aOrders, array(), $aLikes);
		
		$rResult = $roles['data'];
		$iFilteredTotal = $roles['total_rows'];
		$iTotal = $roles['total_rows'];
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		foreach ($rResult as $role) {
			$row = array();
			$row[] = "<a href=\"".site_url("auth/role/edit/".$role->id)."\" data-original-title=\"Edit Role\" style=\"color: #0C9ABB\">".$role->nama."</a>";

			$action = "<a class=\"delete-row btn btn-danger btn-mini\" href=\"#\" data-id=\"".$role->id."\" data-original-title=\"Hapus\">Hapus</a>";
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
            redirect('auth/role', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $role = $this->_getDataObject();
                if (isset($role->id) && $role->id > 0) {
                    $id = $role->id;
                    $this->Auth_Role_Model->update($role);
                }
                else {
                    $id = $this->Auth_Role_Model->create($role);
                }
                $data['data'] = $role;
                redirect('auth/role', 'refresh');
            }
        }
		
		if (!empty($id)) {
			$role = $this->Auth_Role_Model->getById($id);
		}
		else {
			$role = $this->_getEmptyDataObject();
		}
		
		$this->data['data'] = $role;
		$roleModules = $this->Auth_Role_Module_Model->getAll(0, 0, array('id' => 'ASC'), array('role_id' => $role->id));
        if (!$roleModules['data'])
            $roleModules['data'] = array();
        $this->data['role_modules'] = $roleModules['data'];
		$modules = $this->Auth_Module_Model->getAll(0);
		$this->data['module_list'] = $modules['data'];
		
		$this->data['jenis_list'] = array(1 => "Super Administrator", 2 => "Administrator", 3 => "User");
		
		$this->data['sub_nav'] = "auth/role/sub_nav";
		$this->template->set_title('Tambah/Edit Role')
					   ->set_js('jquery.dataTables')
			           ->build('auth/role/edit');
    }
	
	public function delete($id) {
		$this->Roles_Model->delete($id);
        redirect('roles', 'refresh');
    }
    
    private function _getEmptyDataObject() {
		$roles = new stdClass();
		$roles->id		= 0;
        $roles->nama	= '';
		$roles->jenis	= 0;
		$roles->modules = array();
        return $roles;
    }
    
    private function _getDataObject() {
        $this->input->post(NULL, TRUE);
        
        $roles = new stdClass();
		$roles->id		= $this->input->post('id') && ($this->input->post('id') > 0) ? $this->input->post('id') : 0;
        $roles->nama	= $this->input->post('nama');
		$roles->jenis	= $this->input->post('jenis');
		
		$modules = $this->Roles_Modules_Model->getAll(0, 0, array('id' => 'ASC'), array('id' => $roles->id));
        $roles->modules = $modules['data'];
		
		$aModules = array();
        foreach ($roles->modules as $module) {
            if ($module->role_id != $roles->id) {
                $module->role_id = $roles->id;
            }
            $aModules[$module->id] = $module;
            $aModules[$module->id]->status_edit = 3;  // 3: Delete
        }
		if ($this->input->post('module')) {
            for ($i = 0; $i < count($_POST['module_id']); $i++) {
                $module_id = $this->input->post('module');
				for ($j = 0; $j < count($_POST['role_module_id']); $j++) {
                    $s = explode('|', $_POST['role_module_id'][$j]);
                    if ($s[0] === $module_id) {
                        $role_module_id = $s[1];
                        break;
                    }
                }
                if (!array_key_exists(intval($module_id), $aModules)) {
                    $role_module = new StdClass();
                    $role_module->id = $role_module_id;
                    $role_module->role_id = $roles->id;
                    $role_module->module_id = $_POST['module_id'][$i];
                    $role_module->status_edit = 1;  // 1: Add
                    $aModules[$role_module_id] = $role_module;
                }
            }
        }
        $roles->modules = $aModules;
		
        return $roles;
    }
    
}

/* End of file role.php */
/* Location: ./application/controllers/role.php */