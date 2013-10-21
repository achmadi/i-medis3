<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Tindakan_Model extends CI_Model {
	
	protected $table_def = "tindakan";
	protected $table_def_tarif_pelayanan = "tarif_pelayanan";
	protected $table_def_dokter = "pegawai";
	
	public function __construct() {
        parent::__construct();
    }

	public function getBy($aWhere = array()) {
		$this->db->select($this->table_def.".id");
		$this->db->select($this->table_def.".unit_id");
		$this->db->select($this->table_def.".pelayanan_ri_id");
		$this->db->select($this->table_def.".bed_id");
		$this->db->select($this->table_def.".tanggal");
		$this->db->select($this->table_def.".tindakan_id");
		$this->db->select($this->table_def_tarif_pelayanan.".nama AS tarif_pelayanan");
		$this->db->select($this->table_def_tarif_pelayanan.".jasa_pelayanan AS tarif");
		$this->db->select($this->table_def.".icd_10_id");
		$this->db->select($this->table_def.".dokter_id");
		$this->db->select($this->table_def_dokter.".nama AS dokter");
		$this->db->select($this->table_def.".keterangan");
		$this->db->join($this->table_def_tarif_pelayanan, $this->table_def.".tindakan_id = ".$this->table_def_tarif_pelayanan.".id", "left");
		$this->db->join($this->table_def_dokter, $this->table_def.".dokter_id = ".$this->table_def_dokter.".id", "left");
        if (count($aWhere) > 0) {
			$this->db->where($aWhere);
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
		$this->db->select($this->table_def.".unit_id");
		$this->db->select($this->table_def.".pelayanan_ri_id");
		$this->db->select($this->table_def.".bed_id");
		$this->db->select($this->table_def.".tanggal");
		$this->db->select($this->table_def.".tindakan_id");
		$this->db->select($this->table_def_tarif_pelayanan.".nama AS tarif_pelayanan");
		$this->db->select($this->table_def_tarif_pelayanan.".jasa_pelayanan AS tarif");
		$this->db->select($this->table_def.".icd_10_id");
		$this->db->select($this->table_def.".dokter_id");
		$this->db->select($this->table_def_dokter.".nama AS dokter");
		$this->db->select($this->table_def.".keterangan");
		$this->db->join($this->table_def_tarif_pelayanan, $this->table_def.".tindakan_id = ".$this->table_def_tarif_pelayanan.".id", "left");
		$this->db->join($this->table_def_dokter, $this->table_def.".dokter_id = ".$this->table_def_dokter.".id", "left");
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

    public function create($tindakan) {
		$this->db->trans_start();
		
		$data = get_object_vars($tindakan);
		unset($data['id']);
		$this->db->where('id', $tindakan->id);
		$this->db->insert($this->table_def, $data);
		$id = $this->db->insert_id();
		
		$this->db->trans_complete();
		if ($this->db->trans_status() === TRUE) {
			return $id;
		}
		else {
			return false;
		}
    }
    
    public function update($tindakan) {
		$this->db->trans_start();
		
		$data = get_object_vars($tindakan);
		unset($data['id']);
		$this->db->where('id', $tindakan->id);
        $this->db->update($this->table_def, $data);
		
		$this->db->trans_complete();
		if ($this->db->trans_status() === true) {
			return true;
		}
		else {
			return false;
		}
    }
	
    public function delete($id) {
        $this->db->trans_start();
		
		$this->db->where('id', $id);
        $this->db->delete($this->table_def);
		
		$this->db->trans_complete();
		if ($this->db->trans_status() === true) {
			return true;
		}
		else {
			return false;
		}
    }
    
}

?>