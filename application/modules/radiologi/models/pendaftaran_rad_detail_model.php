<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pendaftaran_Rad_Detail_Model extends CI_Model {

	protected $table_def = "pendaftaran_rad_detail";
    
	public function __construct() {
        parent::__construct();
    }
	
	public function getBy($wheres = array()) {
		$select = $this->table_def_pendaftaran.'.id, ';
		$select .= $this->table_def_pendaftaran.'.tanggal, ';
		$select .= $this->table_def_pendaftaran.'.no_register, ';
		$select .= $this->table_def_pendaftaran.'.pasien_id, ';
		$select .= $this->table_def_pasien.'.no_medrec, ';
		$select .= $this->table_def_pasien.'.nama, ';
		$select .= $this->table_def_pasien.'.jenis_kelamin, ';
		$select .= $this->table_def_pasien.'.alamat_jalan, ';
		$select .= $this->table_def_pasien.'.alamat_rt, ';
		$select .= $this->table_def_pasien.'.alamat_rw, ';
		$select .= $this->table_def_pasien.'.provinsi_id, ';
		$select .= $this->table_def_pasien.'.kabupaten_id, ';
		$select .= $this->table_def_pasien.'.kecamatan_id, ';
		$select .= $this->table_def_pasien.'.kelurahan_id, ';
		$select .= $this->table_def_pasien.'.kodepos, ';
		$select .= $this->table_def_pasien.'.telepon, ';
		$select .= $this->table_def_pasien.'.tempat_lahir, ';
		$select .= $this->table_def_pasien.'.tanggal_lahir, ';
		$select .= $this->table_def_pendaftaran.'.umur_tahun, ';
		$select .= $this->table_def_pendaftaran.'.umur_bulan, ';
		$select .= $this->table_def_pendaftaran.'.umur_hari, ';
		$select .= $this->table_def_pasien.'.agama_id, ';
		$select .= $this->table_def_agama.'.nama AS agama, ';
		$select .= $this->table_def_pasien.'.pendidikan_id, ';
		$select .= $this->table_def_pendidikan.'.nama AS pendidikan, ';
		$select .= $this->table_def_pasien.'.pekerjaan_id, ';
		$select .= $this->table_def_pekerjaan.'.nama AS pekerjaan, ';
		$select .= $this->table_def_pendaftaran.'.rujukan_id, ';
		$select .= $this->table_def_rujukan.'.nama AS rujukan, ';
		$select .= $this->table_def_pendaftaran.'.cara_bayar_id, ';
		$select .= $this->table_def_cara_bayar.'.nama AS cara_bayar, ';
		$select .= $this->table_def_pendaftaran.'.poliklinik_id, ';
		$select .= $this->table_def_poliklinik.'.nama AS poliklinik, ';
		$select .= $this->table_def_pendaftaran.'.dokter_id, ';
		$select .= $this->table_def_pegawai.'.nama AS dokter, ';
		$select .= $this->table_def_pendaftaran.'.pj_nama, ';
		$select .= $this->table_def_pendaftaran.'.pj_hubungan, ';
		$select .= $this->table_def_pendaftaran.'.pj_pekerjaan_id, ';
		$select .= $this->table_def_pendaftaran.'.pj_alamat';
		$this->db->select($select);
		$this->db->join($this->table_def_pasien, $this->table_def_pendaftaran.'.pasien_id = '.$this->table_def_pasien.'.id', 'left');
		$this->db->join($this->table_def_rujukan, $this->table_def_pendaftaran.'.rujukan_id = '.$this->table_def_rujukan.'.id', 'left');
		$this->db->join($this->table_def_cara_bayar, $this->table_def_pendaftaran.'.cara_bayar_id = '.$this->table_def_cara_bayar.'.id', 'left');
		$this->db->join($this->table_def_poliklinik, $this->table_def_pendaftaran.'.poliklinik_id = '.$this->table_def_poliklinik.'.id', 'left');
		$this->db->join($this->table_def_pegawai, $this->table_def_pendaftaran.'.dokter_id = '.$this->table_def_pegawai.'.id', 'left');
		$this->db->join($this->table_def_agama, $this->table_def_pasien.'.agama_id = '.$this->table_def_agama.'.id', 'left');
		$this->db->join($this->table_def_pendidikan, $this->table_def_pasien.'.pendidikan_id = '.$this->table_def_pendidikan.'.id', 'left');
		$this->db->join($this->table_def_pekerjaan, $this->table_def_pasien.'.pekerjaan_id = '.$this->table_def_pekerjaan.'.id', 'left');
        if (count($wheres) > 0) {
			$this->db->where($wheres);
		}
        $query = $this->db->get($this->table_def_pendaftaran);
        if ($query->num_rows() > 0) {
			return $query->row();
        }
		else {
			return false;
		}
    }
	
	public function getAll($limit = 10, $offset = 0, $orders = array(), $wheres = array(), $likes = array()) {
		$data = array();
		$this->db->start_cache();
		$select = $this->table_def.'.id, ';
		$select .= $this->table_def.'.radiologi_id, ';
		$select .= $this->table_def.'.tarif_pelayanan_id, ';
		$select .= $this->table_def.'.harga';
		$this->db->select($select);
		if (count($wheres) > 0) {
			$this->db->where($wheres);
		}
		if (count($likes) > 0) {
			$this->db->or_like($likes);
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