<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pelayanan_RI_Model extends CI_Model {

	protected $table_def = "pelayanan_ri";
	protected $table_def_pasien = "pasien";
	protected $table_def_agama = "agama";
	protected $table_def_pendidikan = "pendidikan";
	protected $table_def_pekerjaan = "pekerjaan";
	protected $table_def_pendaftaran = "pendaftaran_ri";
	protected $table_def_dokter = "pegawai";
	protected $table_def_bed = "bed";
	protected $table_def_gedung = "gedung";
	protected $table_def_kelas = "kelas";
	
	public function __construct() {
        parent::__construct();
    }
	
	public function getBy($where = array()) {
		$this->db->select($this->table_def.".id");
		$this->db->select($this->table_def.".tanggal");
		$this->db->select($this->table_def.".pendaftaran_id");
		$this->db->select($this->table_def_pendaftaran.".no_register");
		$this->db->select($this->table_def.".pasien_id");
		$this->db->select($this->table_def_pasien.".no_medrec");
		$this->db->select($this->table_def_pasien.".nama");
		$this->db->select($this->table_def_pasien.".jenis_kelamin");
		$this->db->select($this->table_def_pasien.".alamat_jalan");
		$this->db->select($this->table_def_pasien.".tanggal_lahir");
		$this->db->select($this->table_def.".umur_tahun");
		$this->db->select($this->table_def.".umur_bulan");
		$this->db->select($this->table_def.".umur_hari");
		$this->db->select($this->table_def_pasien.".agama_id");
		$this->db->select($this->table_def_agama.".nama AS agama");
		$this->db->select($this->table_def_pasien.".pendidikan_id");
		$this->db->select($this->table_def_pendidikan.".nama AS pendidikan");
		$this->db->select($this->table_def_pasien.".pekerjaan_id");
		$this->db->select($this->table_def_pekerjaan.".nama AS pekerjaan");
		$this->db->select($this->table_def_pasien.".status_kawin");
		$this->db->select($this->table_def.".cara_masuk");
		$this->db->select($this->table_def.".bed_id");
		$this->db->select($this->table_def_bed.".nama AS bed");
		$this->db->select($this->table_def_bed.".kelas_id");
		$this->db->select($this->table_def_kelas.".nama AS kelas");
		$this->db->select($this->table_def_bed.".gedung_id");
		$this->db->select($this->table_def_bed.".ruangan_id");
		$this->db->select($this->table_def.".cara_masuk");
		$this->db->select($this->table_def.".pindah_dari_tanggal");
		$this->db->select($this->table_def.".pindah_dari_bed_id");
		$this->db->select($this->table_def.".pindah_ke_tanggal");
		$this->db->select($this->table_def.".pindah_ke_bed_id");
		$this->db->select($this->table_def_gedung.".nama AS gedung");
		$this->db->select($this->table_def_gedung.".bagian");
		$this->db->select($this->table_def.".tanggal_keluar");
		$this->db->select($this->table_def.".keadaan_pasien_keluar");
		$this->db->select($this->table_def.".cara_pasien_keluar");
		$this->db->select($this->table_def.".cara_bayar_id");
		$this->db->select($this->table_def.".diagnosa_utama");
		$this->db->select($this->table_def.".komplikasi");
		$this->db->select($this->table_def.".sebab_luar_kecelakaan");
		$this->db->select($this->table_def.".icd_x_id");
		$this->db->select($this->table_def.".operasi_tindakan");
		$this->db->select($this->table_def.".tanggal_tindakan_operasi");
		$this->db->select($this->table_def.".dokter_id");
		$this->db->select($this->table_def_dokter.".nama AS dokter");
		$this->db->select($this->table_def.".status_masuk");
		$this->db->select($this->table_def.".status_keluar");
		$this->db->join($this->table_def_pendaftaran, $this->table_def.".pendaftaran_id = ".$this->table_def_pendaftaran.".id", "left");
		$this->db->join($this->table_def_pasien, $this->table_def.".pasien_id = ".$this->table_def_pasien.".id", "left");
		$this->db->join($this->table_def_agama, $this->table_def_pasien.".agama_id = ".$this->table_def_agama.".id", "left");
		$this->db->join($this->table_def_pendidikan, $this->table_def_pasien.".pendidikan_id = ".$this->table_def_pendidikan.".id", "left");
		$this->db->join($this->table_def_pekerjaan, $this->table_def_pasien.".pekerjaan_id = ".$this->table_def_pekerjaan.".id", "left");
		$this->db->join($this->table_def_dokter, $this->table_def.".dokter_id = ".$this->table_def_dokter.".id", "left");
		$this->db->join($this->table_def_bed, $this->table_def.".bed_id = ".$this->table_def_bed.".id", "left");
		$this->db->join($this->table_def_gedung, $this->table_def_bed.".gedung_id = ".$this->table_def_gedung.".id", "left");
		$this->db->join($this->table_def_kelas, $this->table_def_bed.".kelas_id = ".$this->table_def_kelas.".id", "left");
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
		$this->db->select($this->table_def.".tanggal");
		$this->db->select($this->table_def.".pendaftaran_id");
		$this->db->select($this->table_def_pendaftaran.".no_register");
		$this->db->select($this->table_def.".pasien_id");
		$this->db->select($this->table_def_pasien.".no_medrec");
		$this->db->select($this->table_def_pasien.".nama");
		$this->db->select($this->table_def_pasien.".jenis_kelamin");
		$this->db->select($this->table_def_pasien.".alamat_jalan");
		$this->db->select($this->table_def_pasien.".tanggal_lahir");
		$this->db->select($this->table_def.".umur_tahun");
		$this->db->select($this->table_def.".umur_bulan");
		$this->db->select($this->table_def.".umur_hari");
		$this->db->select($this->table_def_pasien.".agama_id");
		$this->db->select($this->table_def_agama.".nama AS agama");
		$this->db->select($this->table_def_pasien.".pendidikan_id");
		$this->db->select($this->table_def_pendidikan.".nama AS pendidikan");
		$this->db->select($this->table_def_pasien.".pekerjaan_id");
		$this->db->select($this->table_def_pekerjaan.".nama AS pekerjaan");
		$this->db->select($this->table_def_pasien.".status_kawin");
		$this->db->select($this->table_def.".cara_masuk");
		$this->db->select($this->table_def.".bed_id");
		$this->db->select($this->table_def_bed.".nama AS bed");
		$this->db->select($this->table_def_bed.".kelas_id");
		$this->db->select($this->table_def_kelas.".nama AS kelas");
		$this->db->select($this->table_def_bed.".gedung_id");
		$this->db->select($this->table_def_bed.".ruangan_id");
		$this->db->select($this->table_def.".cara_masuk");
		$this->db->select($this->table_def.".pindah_dari_tanggal");
		$this->db->select($this->table_def.".pindah_dari_bed_id");
		$this->db->select($this->table_def.".pindah_ke_tanggal");
		$this->db->select($this->table_def.".pindah_ke_bed_id");
		$this->db->select($this->table_def_gedung.".nama AS gedung");
		$this->db->select($this->table_def_gedung.".bagian");
		$this->db->select($this->table_def.".tanggal_keluar");
		$this->db->select($this->table_def.".keadaan_pasien_keluar");
		$this->db->select($this->table_def.".cara_pasien_keluar");
		$this->db->select($this->table_def.".cara_bayar_id");
		$this->db->select($this->table_def.".diagnosa_utama");
		$this->db->select($this->table_def.".komplikasi");
		$this->db->select($this->table_def.".sebab_luar_kecelakaan");
		$this->db->select($this->table_def.".icd_x_id");
		$this->db->select($this->table_def.".operasi_tindakan");
		$this->db->select($this->table_def.".tanggal_tindakan_operasi");
		$this->db->select($this->table_def.".dokter_id");
		$this->db->select($this->table_def_dokter.".nama AS dokter");
		$this->db->select($this->table_def.".status_masuk");
		$this->db->select($this->table_def.".status_keluar");
		$this->db->join($this->table_def_pendaftaran, $this->table_def.".pendaftaran_id = ".$this->table_def_pendaftaran.".id", "left");
		$this->db->join($this->table_def_pasien, $this->table_def.".pasien_id = ".$this->table_def_pasien.".id", "left");
		$this->db->join($this->table_def_agama, $this->table_def_pasien.".agama_id = ".$this->table_def_agama.".id", "left");
		$this->db->join($this->table_def_pendidikan, $this->table_def_pasien.".pendidikan_id = ".$this->table_def_pendidikan.".id", "left");
		$this->db->join($this->table_def_pekerjaan, $this->table_def_pasien.".pekerjaan_id = ".$this->table_def_pekerjaan.".id", "left");
		$this->db->join($this->table_def_dokter, $this->table_def.".dokter_id = ".$this->table_def_dokter.".id", "left");
		$this->db->join($this->table_def_bed, $this->table_def.".bed_id = ".$this->table_def_bed.".id", "left");
		$this->db->join($this->table_def_gedung, $this->table_def_bed.".gedung_id = ".$this->table_def_gedung.".id", "left");
		$this->db->join($this->table_def_kelas, $this->table_def_bed.".kelas_id = ".$this->table_def_kelas.".id", "left");
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

    public function create($pelayanan) {
		$this->db->trans_start();
		
		$data = get_object_vars($pelayanan);
		unset($data['id']);
		$this->db->insert($this->table_def, $data);
        $id = $this->db->insert_id();
		
		$this->db->set('konfirmasi', true);
		$this->db->where('id', $pelayanan->pendaftaran_id);
		$this->db->update($this->table_def_pendaftaran);
        
		$this->db->set('status', $this->config->item('ID_STATUS_BED_ISI'));
		$this->db->set('pelayanan_ri_id', $id);
		$this->db->where('id', $pelayanan->bed_id);
		$this->db->update($this->table_def_bed);
		
		$this->db->trans_complete();
		if ($this->db->trans_status() === TRUE) {
			return $id;
		}
		else {
			return false;
		}
    }
    
    public function update($pelayanan) {
		$data = get_object_vars($pelayanan);
		unset($data['id']);
        $this->db->where('id', $pelayanan->id);
        $this->db->update($this->table_def, $data);
    }
	
    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table_def); 
    }
	
	public function checkout($pelayanan) {
		$this->db->trans_start();
		
		$data = array();
		$data['tanggal_keluar']			= $pelayanan->tanggal_keluar;
		$data['keadaan_pasien_keluar']	= $pelayanan->keadaan_pasien_keluar;
		$data['cara_pasien_keluar']		= $pelayanan->cara_pasien_keluar;
		$data['cara_pasien_keluar']		= $pelayanan->status_keluar;
        $this->db->where('id', $pelayanan->id);
        $this->db->update($this->table_def, $data);
		
		$this->db->set('status', $this->config->item('ID_STATUS_BED_KOSONG'));
		$this->db->set('pelayanan_ri_id', 0);
		$this->db->where('id', $pelayanan->bed_id);
		$this->db->update($this->table_def_bed);
		
		$this->db->trans_complete();
		if ($this->db->trans_status() === TRUE) {
			return true;
		}
		else {
			return false;
		}
    }
	
	public function pindah_bed($pelayanan) {
		$this->db->trans_start();
		
		$this->db->set('status', $this->config->item('ID_STATUS_BED_KOSONG'));
		$this->db->set('pelayanan_ri_id', 0);
		$this->db->where('id', $pelayanan->bed_lama_id);
		$this->db->update($this->table_def_bed);
		
		$this->db->set('status', $this->config->item('ID_STATUS_BED_ISI'));
		$this->db->set('pelayanan_ri_id', $pelayanan->pelayanan_ri_id);
		$this->db->where('id', $pelayanan->bed_baru_id);
		$this->db->update($this->table_def_bed);
		
		$this->db->trans_complete();
		if ($this->db->trans_status() === TRUE) {
			return true;
		}
		else {
			return false;
		}
    }
    
}

?>