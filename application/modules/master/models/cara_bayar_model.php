<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cara_Bayar_Model extends CI_Model {

	protected $table_def = "cara_bayar";
    
	public function __construct() {
        parent::__construct();
		$this->load->library('Nested_Set');
		$this->nested_set->setControlParams($this->table_def, 'id');
    }
	
	public function getBy($aWhere = array(), $aOrWhere = array()) {
        if (count($aWhere) > 0) {
			$this->db->where($aWhere);
		}
		if (count($aOrWhere) > 0) {
			$this->db->where($aOrWhere);
		}
        $query = $this->db->get($this->table_def);
        if ($query->num_rows() > 0) {
			return $query->row();
        }
		else {
			return false;
		}
    }
	
	public function getAll($limit = 10, $offset = 0, $orders = array(), $where = array(), $or_where = array(), $like = array()) {
		$data = array();
		$this->db->start_cache();
		if (count($where) > 0) {
			$this->db->where($where);
		}
		if (count($or_where) > 0) {
			$this->db->where($or_where);
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
	
	public function save($cara_bayar) {
		if (!$id = $this->nested_set->getRootId())
			$id = $this->_create_root();
		
		if ($cara_bayar->parent_id == 0) {
			$cara_bayar->parent_id = $id;
		}
		
		if ($cara_bayar->old_parent_id != $cara_bayar->parent_id || $cara_bayar->id == 0) {
			$this->nested_set->setLocation($cara_bayar->parent_id, 'last-child');
		}
		
		$data = get_object_vars($cara_bayar);
		if ($cara_bayar->id == 0) {
			$data['created'] = get_current_date();
			$data['created_by'] = 0;
		}
		else {
			$data['modified'] = get_current_date();
			$data['modified_by'] = 0;
			$data['version'] = $cara_bayar->version + 1;
		}
		
		if (!$id = $this->nested_set->store($data)) {
			return false;
		}
		
		if (!$this->nested_set->rebuildPath($id)) {
			return false;
		}
		
		/*
		if (!$this->nested_set->rebuild($this->nested_set->getKey(), $this->nested_set->getLeft(), $this->nested_set->getLevel(), $this->nested_set->getPath($id))) {
			return false;
		}
		*/
		
		return true;

    }
    
    public function delete($id) {
		$this->nested_set->delete($id);
    }
	
	public function order_up($id) {
		$this->nested_set->orderUp($id);
	}
	
	public function order_down($id) {
		$this->nested_set->orderDown($id);
	}
	
	private function _create_root() {
		$data = array(
			'nama'				=> 'Cara Pembayaran',
			'jenis_cara_bayar'	=> 0,
			'jenis'				=> 'Root',
			'path'				=> '',
			'parent_id'			=> 0,
			'level'				=> 0,
			'lft'				=> 1,
			'rgt'				=> 2,
			'created'			=> get_current_date(),
			'created_by'		=> 0,
			'modified'			=> null,
			'modified_by'		=> null,
			'version'			=> 0
		);
		$this->db->insert($this->table_def, $data);
		return $this->db->insert_id();
	}
    
}

?>