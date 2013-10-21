<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth_Module_Model extends CI_Model {

	protected $table_def = "auth_module";
	protected $table_child1_def = "roles_modules";
	protected $table_child2_def = "users_modules";
	
	const IDS_MASTER					= 'Master';
	const IDS_PENDAFTARAN_RAWAT_JALAN	= 'Pendaftaran Rawat Jalan';
	const IDS_PELAYANAN_RAWAT_JALAN		= 'Pelayanan Rawat Jalan';
	const IDS_PENDAFTARAN_IGD			= 'Pendaftaran IGD';
	const IDS_PELAYANAN_IGD				= 'Pelayanan IGD';
	
	const ID_MASTER						= 1;
	const ID_PENDAFTARAN_RAWAT_JALAN	= 2;
	const ID_PELAYANAN_RAWAT_JALAN		= 3;
	const ID_PENDAFTARAN_IGD			= 4;
	const ID_PELAYANAN_IGD				= 5;
	
	protected static $_jenis_module = array(
		self::ID_MASTER						=> self::IDS_MASTER, 
		self::ID_PENDAFTARAN_RAWAT_JALAN	=> self::IDS_PENDAFTARAN_RAWAT_JALAN, 
		self::ID_PELAYANAN_RAWAT_JALAN		=> self::IDS_PELAYANAN_RAWAT_JALAN,
		self::ID_PENDAFTARAN_IGD			=> self::IDS_PENDAFTARAN_IGD, 
		self::ID_PELAYANAN_IGD				=> self::IDS_PELAYANAN_IGD
	);
	
	const IDS_ACTIVE	= 'Active';
	const IDS_INACTIVE	= 'Inactive';
	
	const ID_ACTIVE		= 1;
	const ID_INACTIVE	= 2;
	
	protected static $_state = array(
		self::ID_ACTIVE		=> self::IDS_ACTIVE, 
		self::ID_INACTIVE	=> self::IDS_INACTIVE 
	);
    
	public function __construct() {
        parent::__construct();
    }
	
	public function getById($id) {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table_def);
        if ($query->num_rows() > 0) {
			return $query->row();
        }
		else {
			return false;
		}
    }
	
	public function getAll($limit = 10, $offset = 0, $orders = array(), $where = array(), $like = array()) {
		$data = array();
		
		$this->db->start_cache();
		if (count($where) > 0) {
			$this->db->where($where);
		}
		if (count($like) > 0) {
			$this->db->like($like);
		}
		$this->db->stop_cache();
		
		$data['total_rows'] = $this->db->count_all_results($this->table_def);
		
		if (count($orders) > 0)
			foreach ($orders as $order => $direction)
				$this->db->order_by($order, $direction);
		
        if ($limit == 0)
            $dt = $this->db->get($this->table_def)->result();
        else
            $dt = $this->db->get($this->table_def, $limit, $offset)->result();
        
		$this->db->flush_cache();
		foreach ($dt as $row)
			$row->child = $this->_countChild($row->id);
		
		$data['data'] = $dt;
		return $data;
	}
	
	private function _countChild($id) {
		$count = 0;
		/*
		$this->db->where('module_id', $id);
        $query = $this->db->get($this->table_child1_def);
		$count += $query->num_rows();
		
		$this->db->where('module_id', $id);
        $query = $this->db->get($this->table_child1_def);
		$count += $query->num_rows();
		*/
        return $count;
	}

    public function create($module) {
        $data = $this->_toArray($module);
		
		$timezone = "Asia/Pontianak";
		if (function_exists('date_default_timezone_set'))
			date_default_timezone_set($timezone);
		$current_date = date("Y-m-d H:i:s");
		
		$data['created'] = $current_date;
		$data['created_by'] = 0;
		
        $this->db->insert($this->table_def, $data);
        return $this->db->insert_id();
    }
    
    public function update($module) {
		$data = $this->_toArray($module);
		
		$timezone = "Asia/Pontianak";
		if (function_exists('date_default_timezone_set'))
			date_default_timezone_set($timezone);
		$current_date = date("Y-m-d H:i:s");
		
		$data['modified'] = $current_date;
		$data['modified_by'] = 0;
		$data['version'] = 0;
		
        $this->db->where('id', $module->id);
        $this->db->update($this->table_def, $data);
    }
    
    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table_def); 
    }
	
	public function getJenisModule() {
		return self::$_jenis_module;
	}
	
	public function getJenisModuleDescr($index) {
		return self::$_jenis_module[$index];
	}
	
	private function _toArray($module) {
		$data = array(
            'nama'	=> $module->nama,
			'jenis'	=> $module->jenis
        );
		return $data;
	}
    
}

?>