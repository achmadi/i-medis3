<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pembagian_Jasa_Detail_Model extends CI_Model {

	protected $table_def = "pembagian_jasa_detail";
	protected $table_def_tarif_pelayanan = "tarif_pelayanan";
    
	public function __construct() {
        parent::__construct();
    }
	
	public function getById($id) {
		$select = $this->table_def.".id, ";
		$select .= $this->table_def.".pembagian_jasa_id, ";
		$select .= $this->table_def.".tarif_pelayanan_id, ";
		$select .= $this->table_def.".pegawai_id, ";
		$select .= $this->table_def.".harga_satuan, ";
		$select .= $this->table_def.".quantity";
		$this->db->select($select);
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
		$select .= $this->table_def.".pembagian_jasa_id, ";
		$select .= $this->table_def.".tarif_pelayanan_id, ";
		$select .= $this->table_def_tarif_pelayanan.".nama AS nama_tarif_pelayanan, ";
		$select .= $this->table_def.".pegawai_id, ";
		$select .= $this->table_def.".harga_satuan, ";
		$select .= $this->table_def.".quantity";
		$this->db->select($select);
		$this->db->join($this->table_def_tarif_pelayanan, $this->table_def.".tarif_pelayanan_id = ".$this->table_def_tarif_pelayanan.".id", "left");
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
    
}

?>