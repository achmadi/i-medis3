<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Penerima_JP_Model extends CI_Model {

	protected $table_def = "penerima_jp";
    
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

    public function create($penerima_jp) {
		$data = get_object_vars($penerima_jp);
		unset($data['id']);
        $this->db->insert($this->table_def, $data);
		$id = $this->db->insert_id();
        return $id;
    }
    
    public function update($penerima_jp) {
		$data = get_object_vars($penerima_jp);
		unset($data['id']);
        $this->db->where('id', $penerima_jp->id);
        $this->db->update($this->table_def, $data);
    }
    
    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table_def); 
    }

}

?>