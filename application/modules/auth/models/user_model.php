<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * User Model
 * 
 * @package App
 * @category Model
 * @author Ardi Soebrata
 */
class User_model extends MY_Model 
{
	protected $table_def = 'auth_users';
	protected $role_table = 'acl_roles';
	private $ci;
	
	function __construct()
	{
		parent::__construct();
		$this->ci =& get_instance();
		$this->ci->load->library('PasswordHash', array('iteration_count_log2' => 8, 'portable_hashes' => FALSE));
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
	
	public function getBy($aWhere = array(), $aOrWhere = array()) {
        if (count($aWhere) > 0) {
			$this->db->where($aWhere);
		}
		if (count($aOrWhere) > 0) {
			$this->db->or_where($aOrWhere);
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

        if ($limit == 0) {
            $data['data'] = $this->db->get($this->table_def)->result();
        }
        else {
            $data['data'] = $this->db->get($this->table_def, $limit, $offset)->result();
        }

		$this->db->flush_cache();

		return $data;
	}
	
	public function create($user) {
		$data = get_object_vars($user);
		unset($data['id']);
		unset($data['confirm_password']);
		$data['lang'] = "id";
		$data['registered'] = get_current_date();
		
		if (strlen(trim($user->password)) > 0) {
			$data['password'] = $this->ci->passwordhash->HashPassword($user->password);
		}
		else {
			unset($data['password']);
		}
		
        $this->db->insert($this->table_def, $data);
		$id = $this->db->insert_id();
        return $id;
    }
	
	public function update($user) {
		$data = get_object_vars($user);
		unset($data['id']);
		unset($data['confirm_password']);
		
		if (strlen(trim($user->password)) > 0) {
			$data['password'] = $this->ci->passwordhash->HashPassword($user->password);
		}
		else {
			unset($data['password']);
		}
		
        $this->db->where('id', $user->id);
        $this->db->update($this->table_def, $data);
    }
	
	public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table_def); 
    }
    
    /**
     * Compare user input password to stored hash
     * 
	 * @param string $userpass
     * @param string $password
	 * @return boolean
     */
    public function check_password($password, $userpass) {
        // check password
        return $this->ci->passwordhash->CheckPassword($password, $userpass);
    }
	
	/**
	 * Check if username is available
	 * 
	 * @param string $username
	 * @param int $id
	 * @return boolean
	 */
	function is_username_unique($username, $id = 0)
	{
		$this->db->where('username', $username);
		if ($id > 0)
			$this->db->where('id <>', $id);
		$query = $this->db->get($this->table_def);
		return ($query->num_rows() == 0);
	}
	
	/**
	 * Check if email is available
	 * 
	 * @param string $email
	 * @param int $id
	 * @return boolean
	 */
	function is_email_unique($email, $id = 0)
	{
		$this->db->where('email', $email);
		if ($id > 0)
			$this->db->where('id <>', $id);
		$query = $this->db->get($this->table_def);
		return ($query->num_rows() == 0);
	}
	
}