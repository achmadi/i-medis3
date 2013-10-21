<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Gedung_Kelas_Model extends CI_Model {

	protected $table_def = "gedung_kelas";
	protected $table_def_kelas = "kelas";
    
	public function __construct() {
        parent::__construct();
    }
	
	public function getBy($where = array()) {
        $this->db->select($this->table_def.".id");
		$this->db->select($this->table_def.".gedung_id");
		$this->db->select($this->table_def.".kelas_id");
		$this->db->select($this->table_def_kelas.".nama AS kelas");
		$this->db->join($this->table_def_kelas, $this->table_def.".kelas_id = ".$this->table_def_kelas.".id", "left");
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
	
	public function getAll($limit = 10, $offset = 0, $orders = array(), $where = array(), $like = array()) {
		$data = array();

		$this->db->start_cache();
		$this->db->select($this->table_def.".id");
		$this->db->select($this->table_def.".gedung_id");
		$this->db->select($this->table_def.".kelas_id");
		$this->db->select($this->table_def_kelas.".nama AS kelas");
		$this->db->join($this->table_def_kelas, $this->table_def.".kelas_id = ".$this->table_def_kelas.".id", "left");
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

}

?>