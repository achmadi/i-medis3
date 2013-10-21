<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth_Role_Module_Model extends CI_Model
{

	protected $table_def = "auth_role_Module";
	protected $table_def_module = "auth_Module";
    
	public function __construct() {
        parent::__construct();
    }
	
	public function getBy($aWhere = array())  {
        if (count($aWhere) > 0) {
			$this->db->where($aWhere);
		}
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
		$this->db->select($this->table_def.".id");
		$this->db->select($this->table_def.".role_id");
		$this->db->select($this->table_def.".module_id");
		$this->db->select($this->table_def_module.".nama AS module");
		if (count($where) > 0) {
			$this->db->where($where);
		}
		if (count($like) > 0) {
			$this->db->like($like);
		}
		$this->db->join($this->table_def_module, $this->table_def.".module_id = ".$this->table_def_module.".id", "left");
		$this->db->stop_cache();
		
		$rows = $this->db->get($this->table_def)->result();
		$data['total_rows'] = count($rows);
		
		if (count($orders) > 0)
			foreach ($orders as $order => $direction)
				$this->db->order_by($order, $direction);
		
        if ($limit == 0) {
            $data['data'] = $this->db->get($this->table_def)->result();
        }
        else {
            $data['data'] = $this->db->get($this->table_def, $limit, $offset)->result();
        }
		$this->db->flush_cache();
		return $data;
	}

    public function create($role_module) {
		$data = $this->_toArray($role_module);
        $this->db->insert($this->table_def, $data);
        return $this->db->insert_id();
    }
    
    public function update($role_module) {
        $data = $this->_toArray($role_module);
        $this->db->where('id', $role_module->id);
        $this->db->update($this->table_def, $data);
    }
    
    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table_def); 
    }
	
	private function _toArray($role_module) {
		$data = array(
			'role_id'	=> $role_module->role_id,
			'module_id'	=> $role_module->module_id
		);
		return $data;
	}
    
}

?>