<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bed_Model extends CI_Model {

	protected $table_def = "bed";
	protected $table_def_gedung = "gedung";
	protected $table_def_ruangan = "ruangan";
	protected $table_def_kelas = "kelas";
	protected $table_def_pelayanan_ri = "pelayanan_ri";
	protected $table_def_pasien = "pasien";
	protected $table_def_dokter = "pegawai";
    
	public function __construct() {
        parent::__construct();
    }
	
	public function getBy($where = array()) {
		$this->db->select($this->table_def.".id");
		$this->db->select($this->table_def.".nama");
		$this->db->select($this->table_def.".gedung_id");
		$this->db->select($this->table_def_gedung.".nama AS gedung");
		$this->db->select($this->table_def.".ruangan_id");
		$this->db->select($this->table_def_ruangan.".nama AS ruangan");
		$this->db->select($this->table_def.".kelas_id");
		$this->db->select($this->table_def_kelas.".nama AS kelas");
		$this->db->select($this->table_def.".status");
		$this->db->select($this->table_def.".sementara");
		$this->db->select($this->table_def.".bed_sementara_id");
		$this->db->select($this->table_def.".pelayanan_ri_id");
		$this->db->select($this->table_def_pelayanan_ri.".pasien_id");
		$this->db->select($this->table_def_pasien.".no_medrec");
		$this->db->select($this->table_def_pasien.".nama AS nama_pasien");
		$this->db->select($this->table_def_pasien.".jenis_kelamin");
		$this->db->select($this->table_def_pelayanan_ri.".umur_tahun");
		$this->db->select($this->table_def_pelayanan_ri.".umur_bulan");
		$this->db->select($this->table_def_pelayanan_ri.".umur_hari");
		$this->db->select($this->table_def_pelayanan_ri.".dokter_id");
		$this->db->select($this->table_def_dokter.".nama AS dokter");
		$this->db->join($this->table_def_gedung, $this->table_def.".gedung_id = ".$this->table_def_gedung.".id", "left");
		$this->db->join($this->table_def_ruangan, $this->table_def.".ruangan_id = ".$this->table_def_ruangan.".id", "left");
		$this->db->join($this->table_def_kelas, $this->table_def.".kelas_id = ".$this->table_def_kelas.".id", "left");
		$this->db->join($this->table_def_pelayanan_ri, $this->table_def.".pelayanan_ri_id = ".$this->table_def_pelayanan_ri.".id", "left");
		$this->db->join($this->table_def_pasien, $this->table_def_pelayanan_ri.".pasien_id = ".$this->table_def_pasien.".id", "left");
		$this->db->join($this->table_def_dokter, $this->table_def_pelayanan_ri.".dokter_id = ".$this->table_def_dokter.".id", "left");
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
		$this->db->select($this->table_def.".nama");
		$this->db->select($this->table_def.".gedung_id");
		$this->db->select($this->table_def_gedung.".nama AS gedung");
		$this->db->select($this->table_def.".ruangan_id");
		$this->db->select($this->table_def_ruangan.".nama AS ruangan");
		$this->db->select($this->table_def.".kelas_id");
		$this->db->select($this->table_def_kelas.".nama AS kelas");
		$this->db->select($this->table_def.".status");
		$this->db->select($this->table_def.".sementara");
		$this->db->select($this->table_def.".bed_sementara_id");
		$this->db->select($this->table_def.".pelayanan_ri_id");
		$this->db->select($this->table_def_pelayanan_ri.".pasien_id");
		$this->db->select($this->table_def_pasien.".no_medrec");
		$this->db->select($this->table_def_pasien.".nama AS nama_pasien");
		$this->db->select($this->table_def_pasien.".jenis_kelamin");
		$this->db->select($this->table_def_pelayanan_ri.".umur_tahun");
		$this->db->select($this->table_def_pelayanan_ri.".umur_bulan");
		$this->db->select($this->table_def_pelayanan_ri.".umur_hari");
		$this->db->select($this->table_def_pelayanan_ri.".dokter_id");
		$this->db->select($this->table_def_dokter.".nama AS dokter");
		$this->db->join($this->table_def_gedung, $this->table_def.".gedung_id = ".$this->table_def_gedung.".id", "left");
		$this->db->join($this->table_def_ruangan, $this->table_def.".ruangan_id = ".$this->table_def_ruangan.".id", "left");
		$this->db->join($this->table_def_kelas, $this->table_def.".kelas_id = ".$this->table_def_kelas.".id", "left");
		$this->db->join($this->table_def_pelayanan_ri, $this->table_def.".pelayanan_ri_id = ".$this->table_def_pelayanan_ri.".id", "left");
		$this->db->join($this->table_def_pasien, $this->table_def_pelayanan_ri.".pasien_id = ".$this->table_def_pasien.".id", "left");
		$this->db->join($this->table_def_dokter, $this->table_def_pelayanan_ri.".dokter_id = ".$this->table_def_dokter.".id", "left");
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

    public function create($bed) {
		$data = get_object_vars($bed);
		unset($data->id);
		$data['status'] = $this->config->item('ID_STATUS_BED_KOSONG');
		$data['sementara'] = false;
		$data['bed_sementara_id'] = 0;
		$data['pelayanan_ri_id'] = 0;
        $this->db->insert($this->table_def, $data);
        return $this->db->insert_id();
    }
    
    public function update($bed) {
		$data = get_object_vars($bed);
		unset($data->id);
        $this->db->where('id', $bed->id);
        $this->db->update($this->table_def, $data);
    }
    
    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table_def); 
    }

}

?>