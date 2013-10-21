<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends ADMIN_Controller {

	protected $form = array(
		array(
			'field'		=> 'name',
			'label'		=> 'Nama',
			'rules'		=> 'xss_clean|required'
		)
	);

    public function __construct() {
		parent::__construct();
		$this->load->model('auth/User_Model');
	}

	public function index($id)	{
		$this->data['is_new'] = false;
		$this->_update($id);
	}
    
    private function _update($id = 0) {
		$this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error" style="color: #CC0000;">', '</div>');
		$this->form_validation->set_message('required', 'Field %s diperlukan');
        $this->form_validation->set_rules($this->form);
        
        if ($this->input->post('batal')) {
            redirect('welcome', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $user = $this->_getDataObject();
				$this->User_Model->update($user);
				$this->session->set_flashdata('notification', array('type' => 'success' , 'message' => 'Data Agama telah di update.'));
				redirect('welcome', 'refresh');
            }
        }
		
		$user = $this->User_Model->get_by_id($id);
		
		$this->data['data'] = $user;
		
		$this->data['sub_nav'] = "auth/profile/sub_nav";
		$this->template->set_title('User Profile')
			           ->build('auth/profile/edit');
    }
    
    private function _getDataObject() {
		$this->input->post(NULL, TRUE);

        $user = new stdClass();
		$user->id	= $this->input->post('id') && ($this->input->post('id') > 0) ? $this->input->post('id') : 0;
        $user->name	= $this->input->post('nama');
        return $user;
    }
    
}

/* End of file agama.php */
/* Location: ./application/controllers/agama.php */