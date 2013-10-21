<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Insentif_Pemda_Model extends CI_Model {

	protected $table_def = "insentif_pemda";
    
	public function __construct() {
        parent::__construct();
    }
	
	public function getBy($where = array()) {
		$select = $this->table_def.".id, ";
		$select .= $this->table_def.".tanggal, ";
		$select .= $this->table_def.".jumlah";
		$this->db->select($select);
		if (count($where) > 0) {
			$this->db->where($where);
		}
        $this->db->where($this->table_def.'.id', $id);
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
		$select = $this->table_def.".id, ";
		$select .= $this->table_def.".tanggal, ";
		$select .= $this->table_def.".jumlah";
		$this->db->select($select);
		if (count($where) > 0) {
			$this->db->where($where);
		}
		if (count($like) > 0) {
			$this->db->or_like($like);
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
	
	public function get_tahun() {
		$this->db->select('YEAR(tanggal) AS tahun', FALSE);
		$this->db->group_by("YEAR(tanggal)");
		$data = $this->db->get($this->table_def)->result();
		return $data;
	}
	
	public function get_jumlah($bulan, $tahun) {
		$this->db->select_sum('jumlah');
		$this->db->where('MONTH(tanggal)', $bulan, FALSE);
		$this->db->where('YEAR(tanggal)', $tahun, FALSE);
		$data = $this->db->get($this->table_def)->row();
		if ($data)
			return $data->jumlah;
		else
			return 0;
	}
    
}

?>