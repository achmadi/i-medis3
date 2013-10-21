<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users_Modules_Model extends CI_Model
{

	protected $table_def = "Users_Modules";
    
	public function __construct()
    {
        parent::__construct();
    }
	
	public function getById($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table_def);
        if ($query->num_rows() > 0) {
			return $query->row();
        }
		else
		{
			return false;
		}
    }
	
	public function getByUserId($user_id)
	{
		$this->db->where('user_id', $user_id);
        $query = $this->db->get($this->table_def);
        if ($query->num_rows() > 0) {
			return $query->result();
        }
		else
		{
			return false;
		}
	}

    public function create($user_module)
    {
        $data = array(
            'user_id'		=> $user_module->user_id,
			'module_id'		=> $user_module->module_id,
			'access_rights'	=> $user_module->access_rights
        );
        $this->db->insert($this->table_def, $data);
        return $this->db->insert_id();
    }
    
    public function update($user_module)
    {
        $data = array(
            'user_id'		=> $user_module->user_id,
			'module_id'		=> $user_module->module_id,
			'access_rights'	=> $user_module->access_rights
        );
        $this->db->where('id', $user_module->id);
        $this->db->update($this->table_def, $data);
    }
    
    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table_def); 
    }
    
}

?>