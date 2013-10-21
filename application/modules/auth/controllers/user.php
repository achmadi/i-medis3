<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * User management controller.
 * 
 * @package App
 * @category Controller
 * @author Ardi Soebrata
 */
class User extends Admin_Controller {

	/**
	 * User form definition.
	 * 
	 * @var array
	 */
	protected $form = array(
		'id' => array(
			'label'		=> 'ID',
			'rules'		=> 'trim|xss_clean'
		),
		'name' => array(
			'label'		=> 'Nama',
			'rules'		=> 'trim|required|max_length[100]|xss_clean'
		),
		'username' => array(
			'label'		=> 'Username',
			'rules'		=> 'trim|required|max_length[255]|callback_unique_username|xss_clean'
		),
		'email' => array(
			'label'		=> 'Email',
			'rules'		=> 'trim|required|max_length[255]|valid_email|callback_unique_email|xss_clean'
		),
		'password' => array(
			'label'		=> 'Password',
			'rules'		=> 'trim|matches[confirm_password]'
		),
		'confirm_password' => array(
			'label'		=> 'Komfirmasi Password',
			'rules'		=> 'trim'
		)
	);
	
	public function __construct() {
		parent::__construct();
		
		if ($this->input->post('cancel-button'))
			redirect ('auth/user/index');
		
		$this->load->language('auth');
		$this->load->model('master/Gedung_Model');
	}
	
	public function index() {
		//$this->data['users'] = $this->user_model->get_list(site_url('auth/user'));
		$this->data['sub_nav'] = "auth/user/sub_nav";
		$this->template->set_title('User')
					   ->set_css('alertify.core', array('id' => 'toggleCSS'))
					   ->set_js('jquery.dataTables')
					   ->set_js('alertify.min')
					   ->set_js('jquery.validate.min')
			           ->build('auth/user/browse');
	}
	
	public function load_data() {
		
		$aColumns = array('username');
		
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
		
		$users = $this->User_Model->getAll($iLimit, $iOffset, $aOrders, array(), $aLikes);
		
		$rResult = $users['data'];
		$iFilteredTotal = $users['total_rows'];
		$iTotal = $users['total_rows'];
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		foreach ($rResult as $user) {
			$row = array();
			$row[] = "<a href=\"#\" class=\"edit-row\" data-id=\"".$user->id."\" style=\"color: #0C9ABB\">".$user->name."</a>";
			$row[] = $user->username;
			$row[] = $user->email;
			$row[] = role_descr($user->role);
			$row[] = $user->registered;
			
			$action = "<a class=\"delete-row btn btn-danger btn-mini\" href=\"#\" data-id=\"".$user->id."\" data-original-title=\"Hapus\">Hapus</a>";
			
			$row[] = $action;
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	
	public function get_data() {
		$user_id = $this->input->get('user_id');
		
		$output = array();
		if ($user_id) {
			$user = $this->User_Model->getBy(array('auth_users.id' => $user_id));
			$output['data']	= $user;
		}
		else {
			$output['data']	= $this->_getEmptyDataObject();
		}
		
		$roles = get_role();
		$aRoles = array();
		foreach ($roles as $key => $value) {
			$aRoles[] = $value;
		}
		$output['role_list'] = $aRoles;
		
		$units = get_jenis_unit();
		$aUnits = array();
		foreach ($units as $key => $value) {
			$aUnits[] = $value;
		}
		$output['unit_list'] = $aUnits;
		
		$gedung = $this->Gedung_Model->getAll(0);
		$output['gedung_list'] = $gedung['data'];
		
		echo json_encode($output);
	}
	
	public function simpan() {
		$user = $this->_getDataObject();
		if (isset($user->id) && $user->id > 0) {
			$this->User_Model->update($user);
		}
		else {
			$this->User_Model->create($user);
		}
		echo "ok";
	}
	
	public function delete() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->User_Model->delete($id);
		}
    }
	
	public function profile() {
		$this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="span12 error" style="color: #CC0000;">', '</div>');
		$this->form_validation->set_message('required', 'Field %s diperlukan');
 		
		$user = $this->User_Model->getBy(array('auth_users.id' => $this->data['auth_user']['id']));
		
		$this->form['username']['rules']	= "trim|required|max_length[255]|callback_unique_username[{$user->id}]|xss_clean";
		$this->form['email']['rules']		= "trim|required|max_length[255]|valid_email|callback_unique_email[{$user->id}]|xss_clean";
		
		$this->form_validation->init($this->form);
		
		if ($this->input->post('batal')) {
            redirect(site_url(), 'refresh');
        }
		if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $user = $this->_getDataObject();
                if (isset($user->id) && $user->id > 0) {
                    $this->User_Model->update($user);
                }
                else {
                    $this->User_Model->create($user);
                }
                redirect(site_url(), 'refresh');
            }
        }
		
		$this->data['data'] = $user;
		
		$this->template->set_title('User')
					   ->set_css('bootstrap-editable')
			           ->build('auth/user/profile');
	}
	
	public function unique_username($value, $id = 0) {
		if ($this->User_Model->is_username_unique($value, $id))
			return TRUE;
		else
		{
			$this->form_validation->set_message('unique_username', 'Username tersebut sudah ada!');
			return FALSE;
		}
	}
	
	public function unique_email($value, $id = 0) {
		if ($this->User_Model->is_email_unique($value, $id))
			return TRUE;
		else {
			$this->form_validation->set_message('unique_email', 'Email tersebut sudah ada!');
			return FALSE;
		}
	}
	
	private function _getEmptyDataObject() {
		$user = new stdClass();
		$user->id				= 0;
        $user->name				= '';
		$user->username			= '';
		$user->email			= '';
		$user->password			= '';
		$user->confirm_password	= '';
		$user->role				= 0;
		$user->unit_kerja_id	= 0;
		$user->gedung_id		= 0;
        return $user;
    }
	
	private function _getDataObject() {
		$this->input->post(NULL, TRUE);

        $user = new stdClass();
		$user->id				= $this->input->post('id') ? $this->input->post('id') : 0;
        $user->name				= $this->input->post('name');
		$user->username			= $this->input->post('username');
		$user->email			= $this->input->post('email');
		$user->password			= $this->input->post('password');
		$user->confirm_password	= $this->input->post('confirm_password');
		$user->role				= $this->input->post('role');
		$user->unit_kerja_id	= $this->input->post('unit_kerja_id');
		$user->gedung_id		= $this->input->post('gedung_id');
        return $user;
    }
	
}

/* End of file user.php */
/* Location: ./application/modules/auth/controllers/user.php */