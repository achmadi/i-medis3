<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Login controller.
 * 
 * @package App
 * @category Controller
 * @author Dadang Dana Suryana
 */
class Login extends MY_Controller {

	public function index() {
		// user is already logged in
        if ($this->auth->loggedin()) {
            redirect($this->config->item('dashboard_uri'));
        }
		
		$this->load->language('auth');
		
        // form submitted
        if ($this->input->post('username') && $this->input->post('password')) {
            $remember = $this->input->post('remember') ? TRUE : FALSE;
            
            // get user from database
			$user = $this->User_Model->getBy(array('auth_users.username' => $this->input->post('username')));
			if ($user && $this->User_Model->check_password($this->input->post('password'), $user->password))
			{
				// mark user as logged in
				$this->auth->login($user->id, $remember);
				
				// Add session data
				$this->session->set_userdata(array(
					'lang'		=> $user->lang,
					'role'	=> $user->role
					//'role_name'	=> $user->role_name
				));
				
				redirect($this->config->item('dashboard_uri'));
			}
			else
				$this->template->add_message ('error', lang('login_attempt_failed'));
        }
		
		if ($this->input->post('username'))
			$this->data['username'] = $this->input->post('username');
		if ($this->input->post('remember'))
			$this->data['remember'] = $this->input->post('remember');
        
        // show login form
        $this->load->helper('form');
		$this->template->set_title('Login')
		               ->build('login');
	}
}

/* End of file login.php */
/* Location: ./application/modules/auth/controllers/login.php */