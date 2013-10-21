<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dashboard
 * 
 * @package App
 * @category Controller
 * @author Dadang Dana Suryana
 */
class Dashboard extends CI_Controller 
{
	
	public function __construct() {
		parent::__construct();
		$this->load->language('welcome');
	}

	public function index() {
		$this->data['sub_nav'] = "welcome/sub_nav";
		$this->template->set_title('Dashboard')
			           ->build('welcome/dashboard');
	}
	
}

/* End of file dashboard.php */
/* Location: ./application/modules/dashboard/dashboard.php */