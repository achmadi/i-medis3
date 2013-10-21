<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pegawai_Model extends CI_Model {

	protected $table_def = "pegawai";
	protected $table_def_golongan = "golongan_pegawai";
	protected $table_def_kelompok = "kelompok_pegawai";
	protected $table_def_unit = "unit";
	protected $table_def_gedung = "gedung";
    
	public function __construct() {
        parent::__construct();
    }
	
	public function getBy($where = array()) {
		$this->db->select($this->table_def.".id");
		$this->db->select($this->table_def.".nip");
		$this->db->select($this->table_def.".nama");
		$this->db->select($this->table_def.".no_rekening");
		$this->db->select($this->table_def.".npwp");
		$this->db->select($this->table_def.".jabatan");
		$this->db->select($this->table_def.".golongan_id");
		$this->db->select($this->table_def_golongan.".nama AS golongan");
		$this->db->select($this->table_def.".kelompok_id");
		$this->db->select($this->table_def_kelompok.".nama AS kelompok");
		$this->db->select($this->table_def.".unit_id");
		$this->db->select($this->table_def_unit.".nama AS unit");
		$this->db->select($this->table_def.".gedung_id");
		$this->db->select($this->table_def_gedung.".nama AS gedung");
		$this->db->select('IFNULL('.$this->table_def_gedung.'.nama, IFNULL('.$this->table_def_unit.'.nama, '.$this->table_def_kelompok.'.nama)) AS ruang', false);
		$this->db->select($this->table_def.".indeks_langsung");
		$this->db->select($this->table_def.".indeks_basic");
		$this->db->select($this->table_def.".indeks_posisi");
		$this->db->select($this->table_def.".indeks_emergency");
		$this->db->select($this->table_def.".indeks_resiko");
		$this->db->select($this->table_def.".indeks_pendidikan");
		$this->db->select($this->table_def.".indeks_masa_kerja");
		$this->db->join($this->table_def_golongan, $this->table_def.".golongan_id = ".$this->table_def_golongan.".id", "left");
		$this->db->join($this->table_def_kelompok, $this->table_def.".kelompok_id = ".$this->table_def_kelompok.".id", "left");
		$this->db->join($this->table_def_unit, $this->table_def.".unit_id = ".$this->table_def_unit.".id", "left");
		$this->db->join($this->table_def_gedung, $this->table_def.".gedung_id = ".$this->table_def_gedung.".id", "left");
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
		$this->db->select($this->table_def.".nip");
		$this->db->select($this->table_def.".nama");
		$this->db->select($this->table_def.".no_rekening");
		$this->db->select($this->table_def.".npwp");
		$this->db->select($this->table_def.".jabatan");
		$this->db->select($this->table_def.".golongan_id");
		$this->db->select($this->table_def_golongan.".nama AS golongan");
		$this->db->select($this->table_def.".kelompok_id");
		$this->db->select($this->table_def_kelompok.".nama AS kelompok");
		$this->db->select($this->table_def_kelompok.".jenis");
		$this->db->select($this->table_def.".unit_id");
		$this->db->select($this->table_def_unit.".nama AS unit");
		$this->db->select($this->table_def.".gedung_id");
		$this->db->select($this->table_def_gedung.".nama AS gedung");
		$this->db->select('IFNULL('.$this->table_def_gedung.'.nama, IFNULL('.$this->table_def_unit.'.nama, '.$this->table_def_kelompok.'.nama)) AS ruang', false);
		$this->db->select($this->table_def.".indeks_langsung");
		$this->db->select($this->table_def.".indeks_basic");
		$this->db->select($this->table_def.".indeks_posisi");
		$this->db->select($this->table_def.".indeks_emergency");
		$this->db->select($this->table_def.".indeks_resiko");
		$this->db->select($this->table_def.".indeks_pendidikan");
		$this->db->select($this->table_def.".indeks_masa_kerja");
		$this->db->join($this->table_def_golongan, $this->table_def.".golongan_id = ".$this->table_def_golongan.".id", "left");
		$this->db->join($this->table_def_kelompok, $this->table_def.".kelompok_id = ".$this->table_def_kelompok.".id", "left");
		$this->db->join($this->table_def_unit, $this->table_def.".unit_id = ".$this->table_def_unit.".id", "left");
		$this->db->join($this->table_def_gedung, $this->table_def.".gedung_id = ".$this->table_def_gedung.".id", "left");
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
	
	public function getPerKelompok($ruang) {
		$this->db->select($this->table_def.".id");
		$this->db->select($this->table_def.".nip");
		$this->db->select($this->table_def.".nama");
		$this->db->select($this->table_def.".no_rekening");
		$this->db->select($this->table_def.".npwp");
		$this->db->select($this->table_def.".jabatan");
		$this->db->select($this->table_def.".golongan_id");
		$this->db->select($this->table_def_golongan.".nama AS golongan");
		$this->db->select($this->table_def.".kelompok_id");
		$this->db->select($this->table_def_kelompok.".nama AS kelompok");
		$this->db->select($this->table_def_kelompok.".jenis");
		$this->db->select($this->table_def.".unit_id");
		$this->db->select($this->table_def_unit.".nama AS unit");
		$this->db->select($this->table_def.".gedung_id");
		$this->db->select($this->table_def_gedung.".nama AS gedung");
		$this->db->select('IFNULL('.$this->table_def_gedung.'.nama, IFNULL('.$this->table_def_unit.'.nama, '.$this->table_def_kelompok.'.nama)) AS ruang', false);
		$this->db->select($this->table_def.".indeks_langsung");
		$this->db->select($this->table_def.".indeks_basic");
		$this->db->select($this->table_def.".indeks_posisi");
		$this->db->select($this->table_def.".indeks_emergency");
		$this->db->select($this->table_def.".indeks_resiko");
		$this->db->select($this->table_def.".indeks_pendidikan");
		$this->db->select($this->table_def.".indeks_masa_kerja");
		$this->db->join($this->table_def_golongan, $this->table_def.".golongan_id = ".$this->table_def_golongan.".id", "left");
		$this->db->join($this->table_def_kelompok, $this->table_def.".kelompok_id = ".$this->table_def_kelompok.".id", "left");
		$this->db->join($this->table_def_unit, $this->table_def.".unit_id = ".$this->table_def_unit.".id", "left");
		$this->db->join($this->table_def_gedung, $this->table_def.".gedung_id = ".$this->table_def_gedung.".id", "left");
		$this->db->where('IFNULL('.$this->table_def_gedung.'.nama, IFNULL('.$this->table_def_unit.'.nama, '.$this->table_def_kelompok.'.nama)) =', $ruang);
		$data = $this->db->get($this->table_def)->result();
		return $data;
	}
	
	public function getAllKelompok() {
		$this->db->select('IFNULL('.$this->table_def_gedung.'.nama, IFNULL('.$this->table_def_unit.'.nama, '.$this->table_def_kelompok.'.nama)) AS ruang', false);
		$this->db->join($this->table_def_kelompok, $this->table_def.".kelompok_id = ".$this->table_def_kelompok.".id", "left");
		$this->db->join($this->table_def_unit, $this->table_def.".unit_id = ".$this->table_def_unit.".id", "left");
		$this->db->join($this->table_def_gedung, $this->table_def.".gedung_id = ".$this->table_def_gedung.".id", "left");
		$this->db->order_by('ruang', 'ASC');
		$this->db->group_by('ruang');
		$this->db->where('IFNULL('.$this->table_def_gedung.'.nama, IFNULL('.$this->table_def_unit.'.nama, '.$this->table_def_kelompok.'.nama)) IS NOT NULL', null, false);
		$data = $this->db->get($this->table_def)->result();
		return $data;
	}

    public function create($pegawai) {
        $data = get_object_vars($pegawai);
		unset($data['id']);
        $this->db->insert($this->table_def, $data);
        return $this->db->insert_id();
    }
    
    public function update($pegawai) {
       $data = get_object_vars($pegawai);
		unset($data['id']);
        $this->db->where('id', $pegawai->id);
        $this->db->update($this->table_def, $data);
		return true;
    }
    
    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table_def);
		return true;
    }
	
	public function get_jumlah_skor_langsung($ruang) {
		$this->db->select_sum($this->table_def.".indeks_langsung", 'skor');
		$this->db->join($this->table_def_kelompok, $this->table_def.".kelompok_id = ".$this->table_def_kelompok.".id", "left");
		$this->db->join($this->table_def_unit, $this->table_def_pegawai.".unit_id = ".$this->table_def_unit.".id", "left");
		$this->db->join($this->table_def_gedung, $this->table_def_pegawai.".gedung_id = ".$this->table_def_gedung.".id", "left");
		$this->db->where($this->table_def_kelompok.".jenis", 4);
		$this->db->where('IFNULL('.$this->table_def_gedung.'.nama, IFNULL('.$this->table_def_unit.'.nama, '.$this->table_def_kelompok.'.nama)) =', $ruang);
		$data = $this->db->get($this->table_def)->row();
		if ($data)
			return $data->skor;
		else
			return 0;
	}
	
	public function get_jumlah_skor_tidak_langsung() {
		$sum = $this->table_def.".indeks_basic + ";
		$sum .= $this->table_def.".indeks_posisi + ";
		$sum .= $this->table_def.".indeks_emergency + ";
		$sum .= $this->table_def.".indeks_resiko + ";
		$sum .= $this->table_def.".indeks_pendidikan + ";
		$sum .= $this->table_def.".indeks_masa_kerja";
		$this->db->select_sum($sum, 'skor');
		$this->db->join($this->table_def_kelompok, $this->table_def.".kelompok_id = ".$this->table_def_kelompok.".id", "left");
		$this->db->where($this->table_def_kelompok.".jenis", 4);
		$data = $this->db->get($this->table_def)->row();
		if ($data)
			return $data->skor;
		else
			return 0;
	}
    
}

?>