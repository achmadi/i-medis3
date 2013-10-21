<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth_Role_Model extends CI_Model {

	protected $table_def = "auth_role";
	protected $table_child_def = "roles_modules";
    
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
		//foreach ($dt as $row)
			//$row->child = $this->_countChild($row->id);
		
		$data['data'] = $dt;
		return $data;
	}
	/*
	private function _countChild($id) {
		$this->db->where('module_id', $id);
        $query = $this->db->get($this->table_child_def);
        return $query->num_rows();
	}
	*/
    public function create($role) {
		
		$this->db->trans_start();
		
        $data = $this->_toArray($role);
        $this->db->insert($this->table_def, $data);
        $id = $this->db->insert_id();
		
		foreach ($role->modules as $module) {
			$data = $this->_toArrayModule($module);
			$data['role_id'] = $id;
			$this->db->insert($this->table_child_def, $data);
		}
		
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === true) {
			return $id;
		}
		else {
			return false;
		}
    }
    
    public function update($module) {
        $data = array(
            'nama'	=> $module->nama
        );
        $this->db->where('id', $module->id);
        $this->db->update($this->table_def, $data);
    }
    
    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table_def); 
    }
	
	private function _toArray($role) {
		$data = array(
            'nama'	=> $role->nama
        );
		return $data;
	}
	
	private function _toArrayModule($module) {
		$data = array(
			'role_id'	=> $module->role_id,
			'module_id'	=> $module->module_id
		);
		return $data;
	}
    
}

?>