<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Options_Model extends CI_Model {

	protected $table_def = "options";
    
	public function __construct() {
        parent::__construct();
    }
	
	public function get_options() {
		$options = array();
        $result = $this->db->get($this->table_def)->result();
		foreach ($result as $row) {
			$options[$row->property] = $row->value;
		}
		return $options;
    }
	
	private function create_table_options() {
		$table_options = array(
			'agama'	=> array(
				'nama'		=> true,
				'ordering'	=> true
			),
			'pendidikan' => array(
				'nama'		=> true,
				'ordering'	=> true
			),
			'pekerjaan'	=> array(
				'nama'		=> true,
				'ordering'	=> true
			)
		);
		return $table_options;
	}
	
}

?>