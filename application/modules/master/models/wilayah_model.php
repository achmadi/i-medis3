<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Wilayah_Model extends CI_Model {

	protected $table_def = "wilayah";
    
	public function __construct() {
        parent::__construct();
		$this->load->library('Nested_Set');
		$this->nested_set->setControlParams($this->table_def, 'id');
    }
	
	public function getBy($where = array()) {
        if (count($where) > 0) {
			$this->db->where($where);
		}
        $query = $this->db->get($this->table_def);
        if ($query->num_rows() > 0) {
			return $query->row();
        }
		else {
			return false;
		}
    }
	
	public function getAll($limit = 10, $offset = 0, $orders = array(), $where = array(), $like = array(), $root = false) {
		$data = array();

		$this->db->start_cache();
		if (!$root)
			$this->db->where('level >', 0);
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
	
	public function get_tree($id, $limit = 10, $offset = 0, $orders = array(), $where = array(), $like = array()) {
		$data = array();
		
		$this->db->start_cache();
		$this->db->select('n.*');
		$this->db->where('n.lft BETWEEN p.lft AND p.rgt', null, false);
		$this->db->where('p.id = '.(int) $id, null, false);
		if (count($where) > 0) {
			$this->db->where($where);
		}
		if (count($like) > 0) {
			$this->db->like($like);
		}
		$this->db->stop_cache();
		
		$data['total_rows'] = $this->db->count_all_results($this->table_def.' AS n, '.$this->table_def.' AS p');
		
		if (count($orders) > 0)
			foreach ($orders as $order => $direction)
				$this->db->order_by($order, $direction);
		
		if ($limit == 0) {
            $data['data'] = $this->db->get($this->table_def.' AS n, '.$this->table_def.' AS p')->result();
        }
        else {
            $data['data'] = $this->db->get($this->table_def.' AS n, '.$this->table_def.' AS p', $limit, $offset)->result();
        }

		$this->db->flush_cache();
		
		return $data;
	}
	
	public function save($wilayah) {
		if (!$id = $this->nested_set->getRootId())
			$id = $this->_create_root();
		
		if ($wilayah->parent_id == 0) {
			$wilayah->parent_id = $id;
		}
		
		if ($wilayah->old_parent_id != $wilayah->parent_id || $wilayah->id == 0) {
			$this->nested_set->setLocation($wilayah->parent_id, 'last-child');
		}
		
		$data = get_object_vars($wilayah);
		if ($wilayah->id == 0) {
			$data['created'] = get_current_date();
			$data['created_by'] = 0;
		}
		else {
			$data['modified'] = get_current_date();
			$data['modified_by'] = 0;
			$data['version'] = $wilayah->version + 1;
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
		
		return $id;
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
			'nama'			=> 'Indonesia',
			'jenis'			=> 0,
			'path'			=> '',
			'parent_id'		=> 0,
			'level'			=> 0,
			'lft'			=> 1,
			'rgt'			=> 2,
			'created'		=> get_current_date(),
			'created_by'	=> 0,
			'modified'		=> null,
			'modified_by'	=> null,
			'version'		=> 0
		);
		$this->db->insert($this->table_def, $data);
		return $this->db->insert_id();
	}
	
}

?>