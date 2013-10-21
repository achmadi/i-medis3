<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kelas_Model extends CI_Model {

	protected $table_def = "kelas";
    
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

        if ($limit == 0) {
            $data['data'] = $this->db->get($this->table_def)->result();
        }
        else {
            $data['data'] = $this->db->get($this->table_def, $limit, $offset)->result();
        }

		$this->db->flush_cache();

		return $data;
	}

    public function create($kelas) {
		$data = get_object_vars($kelas);
		unset($data['id']);
		$data['ordering'] = $this->getNextOrder();
        $this->db->insert($this->table_def, $data);
		$id = $this->db->insert_id();
		$this->reorder();
        return $id;
    }
    
    public function update($kelas) {
		$data = get_object_vars($kelas);
		unset($data['id']);
        $this->db->where('id', $kelas->id);
        $this->db->update($this->table_def, $data);
    }
    
    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table_def);
		$this->reorder();
    }
	
	public function getNextOrder($where = '') {
		$this->db->select_max('ordering');;
		if ($where) {
			$this->db->where($where);
		}
		$query = $this->db->get($this->table_def);
		$row = $query->row();
		
		return (int) $row->ordering + 1;
	}
	
	public function reorder($where = '') {

		$this->db->select('id, ordering');
		$this->db->where('ordering >=', 0);
		if ($where) {
			$this->db->where($where);
		}
		$this->db->order_by('ordering', 'ASC');
		$query = $this->db->get($this->table_def);
		
		if ($query->num_rows() > 0) {
			$rows = $query->result();
			foreach ($rows as $i => $row) {
				if ($row->ordering >= 0) {
					if ($row->ordering != $i + 1) {
						// Update the row ordering field.
						$this->db->set('ordering', $i + 1);
						$this->db->where('id', $row->id);
						$this->db->update($this->table_def);
					}
				}
			}
        }
		return true;
	}
	
	public function move($delta, $id, $ordering, $where = '') {
		
		if (empty($delta)) {
			return true;
		}
		
		$this->db->select('id, ordering');
		
		if ($delta < 0) {
			$this->db->where('ordering < '.(int) $ordering);
			$this->db->order_by('ordering DESC');
		}
		elseif ($delta > 0) {
			$this->db->where('ordering > '.(int) $ordering);
			$this->db->order_by('ordering ASC');
		}
		
		if ($where)	{
			$this->db->where($where);
		}
		
		$row = $this->db->get($this->table_def)->first_row();

		if (!empty($row)) {
			$this->db->set('ordering', (int) $row->ordering);
			$this->db->where('id', $id);
			$this->db->update($this->table_def);
			
			$this->db->set('ordering', (int) $ordering);
			$this->db->where('id', $row->id);
			$this->db->update($this->table_def);
		}
		else {
			$this->db->set('ordering', (int) $ordering);
			$this->db->where('id', $id);
			$this->db->update($this->table_def);
		}
		
		return true;
	}

}

?>