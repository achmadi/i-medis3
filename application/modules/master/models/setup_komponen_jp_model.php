<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Setup_Komponen_JP_Model extends CI_Model {

	protected $table_def = "setup_komponen_jp";
    
	public function __construct() {
        parent::__construct();
		$this->load->library('nested_set');
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
	
	public function getAll($limit = 10, $offset = 0, $orders = array(), $where = array(), $like = array()) {
		$data = array();

		$this->db->start_cache();
		$this->db->where('jenis <>', 'Root');
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
	
    public function save($tarif) {
		if ($tarif->old_parent_id != $tarif->parent_id || $tarif->id == 0) {
			$this->nested_set->setLocation($tarif->parent_id, 'last-child');
		}
		
		$data = get_object_vars($tarif);
		if ($tarif->id == 0) {
			$data['created'] = get_current_date();
			$data['created_by'] = 0;
		}
		else {
			$data['modified'] = get_current_date();
			$data['modified_by'] = 0;
			$data['version'] = $tarif->version + 1;
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

}

?>