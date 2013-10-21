<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pendaftaran_RI_Model extends CI_Model {

	protected $table_def = "pendaftaran_ri";
	protected $table_def_pelayanan = "pelayanan_ri";
	protected $table_def_pasien = "pasien";
	protected $table_def_wilayah = "wilayah";
	protected $table_def_agama = "agama";
	protected $table_def_pendidikan = "pendidikan";
	protected $table_def_pekerjaan = "pekerjaan";
	protected $table_def_rujukan = "rujukan";
	protected $table_def_cara_bayar = "cara_bayar";
	protected $table_def_pegawai = "pegawai";
	protected $table_def_gedung = "gedung";
	protected $table_def_ruangan = "ruangan";
	protected $table_def_bed = "bed";
	
	protected $table_def_pendaftaran_irj = "pendaftaran_irj";
	protected $table_def_pendaftaran_igd = "pendaftaran_igd";
	
	public function __construct() {
        parent::__construct();
    }
	
	public function getBy($wheres = array()) {
		$this->db->select($this->table_def.'.id');
		$this->db->select($this->table_def.'.tanggal');
		$this->db->select($this->table_def.'.no_register');
		$this->db->select($this->table_def.'.pendaftaran_id');
		$this->db->select($this->table_def.'.pasien_id');
		$this->db->select($this->table_def_pasien.'.no_medrec');
		$this->db->select($this->table_def_pasien.'.nama');
		$this->db->select($this->table_def_pasien.'.jenis_kelamin');
		$this->db->select($this->table_def_pasien.'.alamat_jalan');
		$this->db->select($this->table_def_pasien.'.provinsi_id');
		$this->db->select('provinsi.nama AS provinsi');
		$this->db->select($this->table_def_pasien.'.kabupaten_id');
		$this->db->select('kabupaten.nama AS kabupaten');
		$this->db->select($this->table_def_pasien.'.kecamatan_id');
		$this->db->select('kecamatan.nama AS kecamatan');
		$this->db->select($this->table_def_pasien.'.kelurahan_id');
		$this->db->select('kelurahan.nama AS kelurahan');
		$this->db->select($this->table_def_pasien.'.kodepos');
		$this->db->select($this->table_def_pasien.'.telepon');
		$this->db->select($this->table_def_pasien.'.tempat_lahir');
		$this->db->select($this->table_def_pasien.'.tanggal_lahir');
		$this->db->select($this->table_def_pasien.'.golongan_darah');
		$this->db->select($this->table_def_pasien.'.agama_id');
		$this->db->select($this->table_def_agama.'.nama as agama');
		$this->db->select($this->table_def_pasien.'.pendidikan_id');
		$this->db->select($this->table_def_pendidikan.'.nama as pendidikan');
		$this->db->select($this->table_def_pasien.'.pekerjaan_id');
		$this->db->select($this->table_def_pekerjaan.'.nama as pekerjaan');
		$this->db->select($this->table_def.'.umur_tahun');
		$this->db->select($this->table_def.'.umur_bulan');
		$this->db->select($this->table_def.'.umur_hari');
		$this->db->select($this->table_def.'.rujukan_id');
		$this->db->select($this->table_def_rujukan.'.nama AS rujukan');
		$this->db->select($this->table_def.'.cara_bayar_id');
		$this->db->select($this->table_def_cara_bayar.'.nama AS cara_bayar');
		$this->db->select($this->table_def.'.dokter_id');
		$this->db->select($this->table_def_pegawai.'.nama AS dokter');
		$this->db->select($this->table_def.'.cara_masuk');
		$this->db->select($this->table_def.'.gedung_id');
		$this->db->select($this->table_def_gedung.'.nama AS gedung');
		$this->db->select($this->table_def.'.ruangan_id');
		$this->db->select($this->table_def_ruangan.'.nama AS ruangan');
		$this->db->select($this->table_def.'.bed_id');
		$this->db->select($this->table_def_bed.'.nama AS bed');
		$this->db->select($this->table_def.'.cara_masuk');
		$this->db->select($this->table_def.'.konfirmasi');
		$this->db->join($this->table_def_pasien, $this->table_def.'.pasien_id = '.$this->table_def_pasien.'.id', 'left');
		$this->db->join($this->table_def_wilayah." AS provinsi", $this->table_def_pasien.'.provinsi_id = provinsi.id', 'left');
		$this->db->join($this->table_def_wilayah." AS kabupaten", $this->table_def_pasien.'.kabupaten_id = kabupaten.id', 'left');
		$this->db->join($this->table_def_wilayah." AS kecamatan", $this->table_def_pasien.'.kecamatan_id = kecamatan.id', 'left');
		$this->db->join($this->table_def_wilayah." AS kelurahan", $this->table_def_pasien.'.kelurahan_id = kelurahan.id', 'left');
		$this->db->join($this->table_def_agama, $this->table_def_pasien.'.agama_id = '.$this->table_def_agama.'.id', 'left');
		$this->db->join($this->table_def_pendidikan, $this->table_def_pasien.'.pendidikan_id = '.$this->table_def_pendidikan.'.id', 'left');
		$this->db->join($this->table_def_pekerjaan, $this->table_def_pasien.'.pekerjaan_id = '.$this->table_def_pekerjaan.'.id', 'left');
		$this->db->join($this->table_def_rujukan, $this->table_def.'.rujukan_id = '.$this->table_def_rujukan.'.id', 'left');
		$this->db->join($this->table_def_cara_bayar, $this->table_def.'.cara_bayar_id = '.$this->table_def_cara_bayar.'.id', 'left');
		$this->db->join($this->table_def_pegawai, $this->table_def.'.dokter_id = '.$this->table_def_pegawai.'.id', 'left');
		$this->db->join($this->table_def_gedung, $this->table_def.'.gedung_id = '.$this->table_def_gedung.'.id', 'left');
		$this->db->join($this->table_def_ruangan, $this->table_def.'.ruangan_id = '.$this->table_def_ruangan.'.id', 'left');
		$this->db->join($this->table_def_bed, $this->table_def.'.bed_id = '.$this->table_def_bed.'.id', 'left');
        if (count($wheres) > 0) {
			$this->db->where($wheres);
		}
        $query = $this->db->get($this->table_def);
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
		$this->db->select($this->table_def.'.id');
		$this->db->select($this->table_def.'.tanggal');
		$this->db->select($this->table_def.'.no_register');
		$this->db->select($this->table_def.'.pendaftaran_id');
		$this->db->select($this->table_def.'.pasien_id');
		$this->db->select($this->table_def_pasien.'.no_medrec');
		$this->db->select($this->table_def_pasien.'.nama');
		$this->db->select($this->table_def_pasien.'.jenis_kelamin');
		$this->db->select($this->table_def_pasien.'.alamat_jalan');
		$this->db->select($this->table_def_pasien.'.provinsi_id');
		$this->db->select('provinsi.nama AS provinsi');
		$this->db->select($this->table_def_pasien.'.kabupaten_id');
		$this->db->select('kabupaten.nama AS kabupaten');
		$this->db->select($this->table_def_pasien.'.kecamatan_id');
		$this->db->select('kecamatan.nama AS kecamatan');
		$this->db->select($this->table_def_pasien.'.kelurahan_id');
		$this->db->select('kelurahan.nama AS kelurahan');
		$this->db->select($this->table_def_pasien.'.kodepos');
		$this->db->select($this->table_def_pasien.'.telepon');
		$this->db->select($this->table_def_pasien.'.tempat_lahir');
		$this->db->select($this->table_def_pasien.'.tanggal_lahir');
		$this->db->select($this->table_def_pasien.'.golongan_darah');
		$this->db->select($this->table_def_pasien.'.agama_id');
		$this->db->select($this->table_def_agama.'.nama as agama');
		$this->db->select($this->table_def_pasien.'.pendidikan_id');
		$this->db->select($this->table_def_pendidikan.'.nama as pendidikan');
		$this->db->select($this->table_def_pasien.'.pekerjaan_id');
		$this->db->select($this->table_def_pekerjaan.'.nama as pekerjaan');
		$this->db->select($this->table_def.'.umur_tahun');
		$this->db->select($this->table_def.'.umur_bulan');
		$this->db->select($this->table_def.'.umur_hari');
		$this->db->select($this->table_def.'.rujukan_id');
		$this->db->select($this->table_def_rujukan.'.nama AS rujukan');
		$this->db->select($this->table_def.'.cara_bayar_id');
		$this->db->select($this->table_def_cara_bayar.'.nama AS cara_bayar');
		$this->db->select($this->table_def.'.dokter_id');
		$this->db->select($this->table_def_pegawai.'.nama AS dokter');
		$this->db->select($this->table_def.'.cara_masuk');
		$this->db->select($this->table_def.'.gedung_id');
		$this->db->select($this->table_def_gedung.'.nama AS gedung');
		$this->db->select($this->table_def.'.ruangan_id');
		$this->db->select($this->table_def_ruangan.'.nama AS ruangan');
		$this->db->select($this->table_def.'.bed_id');
		$this->db->select($this->table_def_bed.'.nama AS bed');
		$this->db->select($this->table_def.'.cara_masuk');
		$this->db->select($this->table_def.'.konfirmasi');
		$this->db->join($this->table_def_pasien, $this->table_def.'.pasien_id = '.$this->table_def_pasien.'.id', 'left');
		$this->db->join($this->table_def_wilayah." AS provinsi", $this->table_def_pasien.'.provinsi_id = provinsi.id', 'left');
		$this->db->join($this->table_def_wilayah." AS kabupaten", $this->table_def_pasien.'.kabupaten_id = kabupaten.id', 'left');
		$this->db->join($this->table_def_wilayah." AS kecamatan", $this->table_def_pasien.'.kecamatan_id = kecamatan.id', 'left');
		$this->db->join($this->table_def_wilayah." AS kelurahan", $this->table_def_pasien.'.kelurahan_id = kelurahan.id', 'left');
		$this->db->join($this->table_def_agama, $this->table_def_pasien.'.agama_id = '.$this->table_def_agama.'.id', 'left');
		$this->db->join($this->table_def_pendidikan, $this->table_def_pasien.'.pendidikan_id = '.$this->table_def_pendidikan.'.id', 'left');
		$this->db->join($this->table_def_pekerjaan, $this->table_def_pasien.'.pekerjaan_id = '.$this->table_def_pekerjaan.'.id', 'left');
		$this->db->join($this->table_def_rujukan, $this->table_def.'.rujukan_id = '.$this->table_def_rujukan.'.id', 'left');
		$this->db->join($this->table_def_cara_bayar, $this->table_def.'.cara_bayar_id = '.$this->table_def_cara_bayar.'.id', 'left');
		$this->db->join($this->table_def_pegawai, $this->table_def.'.dokter_id = '.$this->table_def_pegawai.'.id', 'left');
		$this->db->join($this->table_def_gedung, $this->table_def.'.gedung_id = '.$this->table_def_gedung.'.id', 'left');
		$this->db->join($this->table_def_ruangan, $this->table_def.'.ruangan_id = '.$this->table_def_ruangan.'.id', 'left');
		$this->db->join($this->table_def_bed, $this->table_def.'.bed_id = '.$this->table_def_bed.'.id', 'left');
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
	
	public function getAllPendaftaranIRJ_IGD($limit = 10, $offset = 0, $orders = array(), $wheres = array(), $likes = array()) {
		$select_irj = "pendaftaran_irj.id, ";
		$select_irj .= "pendaftaran_irj.tanggal, ";
		$select_irj .= "pendaftaran_irj.no_register, ";
		$select_irj .= "1 AS asal_pasien";	// asal_pasien = Rawat Jalan
		$from_irj = "SELECT ".$select_irj." FROM pendaftaran_irj WHERE pendaftaran_irj.tindak_lanjut = 1";
		$union = "UNION ALL";
		$select_igd = "pendaftaran_igd.id, ";
		$select_igd .= "pendaftaran_igd.tanggal, ";
		$select_igd .= "pendaftaran_igd.no_register, ";
		$select_igd .= "2 AS asal_pasien";	// asal_pasien = Rawat Darurat
		$from_igd = "SELECT ".$select_igd." FROM pendaftaran_igd WHERE pendaftaran_igd.tindak_lanjut = 1";
		$SQL = $from_irj." ".$union." ".$from_igd;
		$query = $this->db->query($SQL);
		return $query->result();
	}
	
	public function get_tanggal_awal() {
		$this->db->select($this->table_def.".tanggal");
		$this->db->order_by($this->table_def.".tanggal", "ASC");
		$query = $this->db->get($this->table_def);
		if ($query->num_rows() > 0) {
			$row = $query->first_row();
			return $row->tanggal;
		}
		else {
			return false;
		}
	}
	
	public function get_tanggal_akhir() {
		$this->db->select($this->table_def.".tanggal");
		$this->db->order_by($this->table_def.".tanggal", "DESC");
		$query = $this->db->get($this->table_def);
		if ($query->num_rows() > 0) {
			$row = $query->first_row();
			return $row->tanggal;
		}
		else {
			return false;
		}
	}
	
    public function create($pendaftaran) {
		$this->db->trans_start();
		
		$data = get_object_vars($pendaftaran);
		unset($data['id']);
		$data['konfirmasi'] = false;
        $this->db->insert($this->table_def, $data);
		$id = $this->db->insert_id();
		
		switch ($pendaftaran->cara_masuk) {
			case $this->config->item('ID_CARA_MASUK_RAWAT_JALAN'):
				$this->db->set('tindak_lanjut', $this->config->item('ID_TINDAK_LANJUT_PERAWATAN_DIRAWAT'));
				$this->db->where('id', $pendaftaran->pendaftaran_id);
				$this->db->update($this->table_def_pendaftaran_irj);
				break;
			case $this->config->item('ID_CARA_MASUK_RAWAT_DARURAT'):
				$this->db->set('tindak_lanjut', $this->config->item('ID_TINDAK_LANJUT_PERAWATAN_DIRAWAT'));
				$this->db->where('id', $pendaftaran->pendaftaran_id);
				$this->db->update($this->table_def_pendaftaran_igd);
				break;
		}
		
		$this->incr_no_register();
		
		$register_id = $this->session->userdata('register_no_register_ri_id');
		$this->delete_no_register_from_queue($register_id);
		$this->session->set_userdata('register_no_register_ri', FALSE);
		$this->session->set_userdata('register_no_register_ri_id', 0);
		
		$this->db->trans_complete();
		if ($this->db->trans_status() === TRUE) {
			return $id;
		}
		else {
			return false;
		}
    }
    
    public function update($pendaftaran) {
		$this->db->trans_start();
		
		$data = get_object_vars($pendaftaran);
		unset($data['id']);
        $this->db->where('id', $pendaftaran->id);
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
        $this->db->where('id', $id);
        $this->db->delete($this->table_def); 
    }
	
	public function konfirmasi($id) {
		
		$this->db->where('id', $id);
		$pendaftaran = $this->db->get($this->table_def)->row();
		
		$this->db->where('id', $pendaftaran->bed_id);
		$bed = $this->db->get($this->table_def_bed)->row();
		
		$this->db->trans_start();
		
		$this->db->set('konfirmasi', true);
		$this->db->where('id', $id);
        $this->db->update($this->table_def);
		
		$data = array(
			'tanggal'					=> $pendaftaran->tanggal,
			'pendaftaran_id'			=> $pendaftaran->id,
			'jenis_pelayanan_ri_id'		=> $bed->jenis_pelayanan_ri_id,
			'pasien_id'					=> $pendaftaran->pasien_id,
			'umur_tahun'				=> $pendaftaran->umur_tahun,
			'umur_bulan'				=> $pendaftaran->umur_bulan,
			'umur_hari'					=> $pendaftaran->umur_hari,
			'bed_id'					=> $pendaftaran->bed_id,
			'cara_masuk'				=> $pendaftaran->cara_masuk,
			'pindah_dari_tanggal'		=> null,
			'pindah_dari_bed_id'		=> 0,
			'pindah_ke_tanggal'			=> null,
			'pindah_ke_bed_id'			=> 0,
			'tanggal_keluar'			=> null,
			'cara_pasien_keluar'		=> 0,
			'keadaan_pasien_keluar'		=> 0,
			'cara_bayar_id'				=> $pendaftaran->cara_bayar_id,
			'diagnosa_utama'			=> '',
			'komplikasi'				=> '',
			'sebab_luar_kecelakaan'		=> '',
			'icd_x_id'					=> 0,
			'operasi_tindakan'			=> '',
			'tanggal_tindakan_operasi'	=> null,
			'dokter_id'					=> $pendaftaran->dokter_id,
			'status_masuk'				=> 0,
			'status_keluar'				=> 0
		);
		$this->db->insert($this->table_def_pelayanan, $data);
		$pelayanan_ri_id = $this->db->insert_id();
		
		$this->db->set('status', 1);
		$this->db->set('pelayanan_ri_id', $pelayanan_ri_id);
		$this->db->where('id', $pendaftaran->bed_id);
        $this->db->update($this->table_def_bed);
		
		$this->db->trans_complete();
		if ($this->db->trans_status() === TRUE) {
			return $pelayanan_ri_id;
		}
		else {
			return 0;
		}
	}
	
	public function get_no_register() {
		// Ambil no. register lab yang terakhir
		$row = $this->db->select('id, last_number')
						->where('name', 'no_register_ri')
						->get('code_register')
						->row();
		// Tambah 1 ke no. register
		$no_register = intval($row->last_number) + 1;
		
		// Cari di tabel No. Register Queue apakah no register sudah ada
		// Jika sudah ada tambah 1 ke ke no. register
		$bFound = TRUE;
		while ($bFound) {
			$format_no_register = str_pad($no_register, 6, "0", STR_PAD_LEFT);
			
			$query = $this->db->where('code_register_id', $row->id)
							  ->where('save_code', $format_no_register)
							  ->get('code_register_queue');
			
			if ($query->num_rows() > 0) {
				$bFound = TRUE;
				$no_register++;
			}
			else
				$bFound = FALSE;
		}
		
		$register = array();
		$register['no_register'] = $format_no_register;
		$register['no_register_queue_id'] = $this->insert_no_register_to_queue($row->id, $format_no_register);
		return $register;
	}
	
	public function incr_no_register() {
		// Ambil no. register lab yang terakhir
		$row = $this->db->select('id, last_number')
						->where('name', 'no_register_ri')
						->get('code_register')
						->row();
		// Tambah 1 ke no. register
		$no_register = intval($row->last_number) + 1;
		
		$data = array(
			'last_number' => $no_register
		);
		$this->db->where('name', 'no_register_ri');
		$this->db->update('code_register', $data);
		
		return TRUE;
	}
	
	public function insert_no_register_to_queue($id, $no_register) {
		$data = array(
			'code_register_id'	=> $id,
			'date_save'			=> get_current_date(),
			'save_code'			=> $no_register
		);
        $this->db->insert('code_register_queue', $data);
		$id = $this->db->insert_id();
		return $id;
    }
	
	public function delete_no_register_from_queue($id) {
        $this->db->where('id', $id);
        $this->db->delete('code_register_queue');
    }
	
	public function delete_expire_no_register_from_queue() {
        $this->db->where('date_save <', get_current_date());
        $this->db->delete('code_register_queue');
    }
    
}

?>