<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pendaftaran_Lab_Model extends CI_Model {

	protected $table_def_pendaftaran = "pendaftaran_lab";
	protected $table_def_pendaftaran_detail = "pendaftaran_lab_detail";
	protected $table_def_pasien = "pasien";
	protected $table_def_agama = "agama";
	protected $table_def_pendidikan = "pendidikan";
	protected $table_def_pekerjaan = "pekerjaan";
	protected $table_def_rujukan = "rujukan";
	protected $table_def_cara_bayar = "cara_bayar";
	protected $table_def_poliklinik = "poliklinik";
	protected $table_def_pegawai = "pegawai";
	
	const IDS_ORANG_TUA	= 'Orang Tua';
	const IDS_SUAMI		= 'Suami';
	const IDS_ISTRI		= 'Istri';
	const IDS_SAUDARA	= 'Saudara';
	const IDS_WALI		= 'Wali';
	
	const ID_ORANG_TUA	= 1;
	const ID_SUAMI		= 2;
	const ID_ISTRI		= 3;
	const ID_SAUDARA	= 4;
	const ID_WALI		= 5;
	
	protected static $_hubungan_dengan_pasien = array(
		self::ID_ORANG_TUA	=> self::IDS_ORANG_TUA, 
		self::ID_SUAMI		=> self::IDS_SUAMI,
		self::ID_ISTRI		=> self::IDS_ISTRI, 
		self::ID_SAUDARA	=> self::IDS_SAUDARA,
		self::ID_WALI		=> self::IDS_WALI
	);
    
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
		$select = $this->table_def_pendaftaran.'.id, ';
		$select .= $this->table_def_pendaftaran.'.tanggal, ';
		$select .= $this->table_def_pendaftaran.'.no_register, ';
		$select .= $this->table_def_pendaftaran.'.pasien_id, ';
		$select .= $this->table_def_pasien.'.no_medrec, ';
		$select .= $this->table_def_pasien.'.nama, ';
		$select .= $this->table_def_pasien.'.jenis_kelamin, ';
		$select .= $this->table_def_pasien.'.alamat_jalan, ';
		$select .= $this->table_def_pasien.'.tanggal_lahir, ';
		$select .= $this->table_def_agama.'.nama AS agama, ';
		$select .= $this->table_def_pendidikan.'.nama AS pendidikan, ';
		$select .= $this->table_def_pekerjaan.'.nama AS pekerjaan, ';
		$select .= $this->table_def_rujukan.'.nama AS rujukan, ';
		$select .= $this->table_def_pendaftaran.'.cara_bayar_id, ';
		$select .= $this->table_def_cara_bayar.'.nama AS cara_bayar, ';
		$select .= $this->table_def_pendaftaran.'.poliklinik_id, ';
		$select .= $this->table_def_poliklinik.'.nama AS poliklinik, ';
		$select .= $this->table_def_pendaftaran.'.dokter_id, ';
		$select .= $this->table_def_pegawai.'.nama AS dokter';
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
		if (count($likes) > 0) {
			$this->db->or_like($likes);
		}
		$this->db->stop_cache();
		
		$data['total_rows'] = $this->db->count_all_results($this->table_def_pendaftaran);
		
		if (count($orders) > 0)
			foreach ($orders as $order => $direction)
				$this->db->order_by($order, $direction);
		
		if ($limit == 0) {
            $data['data'] = $this->db->get($this->table_def_pendaftaran)->result();
        }
        else {
            $data['data'] = $this->db->get($this->table_def_pendaftaran, $limit, $offset)->result();
        }
		$this->db->flush_cache();
		
		return $data;
	}

    public function create($pendaftaran) {
		$this->db->trans_start();
		
		$data_pasien = $this->_toArrayPasien($pendaftaran);
		$this->db->where('id', $pendaftaran->pasien_id);
		$query = $this->db->get($this->table_def_pasien);
		$pasien_baru = true;
		if ($query->num_rows() > 0)	{
			$pasien_baru = false;
			$this->db->where('id', $pendaftaran->pasien_id);
			$this->db->update($this->table_def_pasien, $data_pasien);
		}
		else {
			$pasien_baru = true;
			$this->db->insert($this->table_def_pasien, $data_pasien);
			$pendaftaran->pasien_id = $this->db->insert_id();
			
			$this->incr_no_medrec();
		
			$no_medrec_id = $this->session->userdata('register_no_medrec_id');
			$this->delete_no_medrec_from_queue($no_medrec_id);
			$this->session->set_userdata('register_no_medrec', FALSE);
			$this->session->set_userdata('register_no_medrec_id', 0);
		}
		
		$no_antrian = $this->getLastNoAntrian($pendaftaran->tanggal, $pendaftaran->poliklinik_id);
		$no_antrian++;
		$data_pendaftaran = $this->_toArrayPendaftaran($pendaftaran);
		$data_pendaftaran['no_antrian'] = $no_antrian;
		$data_pendaftaran['pelayanan'] = false;
		$data_pendaftaran['batal'] = false;
		$data_pendaftaran['created'] = get_current_date();
		$data_pendaftaran['created_by'] = 0;
		$data_pendaftaran['modified'] = null;
		$data_pendaftaran['modified_by'] = null;
		$data_pendaftaran['version'] = 0;
        $this->db->insert($this->table_def_pendaftaran, $data_pendaftaran);
        $id = $this->db->insert_id();
		
		foreach ($pendaftaran->pemeriksaans as $pemeriksaan) {
			$data = array(
				'laboratorium_id'		=> $id,
				'tarif_pelayanan_id'	=> $pemeriksaan->tarif_pelayanan_id,
				'harga'					=> $pemeriksaan->harga
			);
			$this->db->insert($this->table_def_pendaftaran_detail, $data);
		}
		
		$this->incr_no_register();
		
		$register_id = $this->session->userdata('register_no_register_lab_id');
		$this->delete_no_register_from_queue($register_id);
		$this->session->set_userdata('register_no_register_lab', FALSE);
		$this->session->set_userdata('register_no_register_lab_id', 0);
		
		$this->db->trans_complete();
		if ($this->db->trans_status() === TRUE) {
			return $id;
		}
		else {
			return 0;
		}
    }
    
    public function update($pendaftaran) {
		$this->db->trans_start();
		
		$data_pasien = $this->_toArrayPasien($pendaftaran);
		$this->db->where('id', $pendaftaran->pasien_id);
        $this->db->update($this->table_def_pasien, $data_pasien);
		
		$data_pendaftaran = $this->_toArrayPendaftaran($pendaftaran);
		$data_pendaftaran['modified'] = get_current_date();
		$data_pendaftaran['modified_by'] = 0;
		//$data_pendaftaran['version'] = 0;
        $this->db->where('id', $pendaftaran->pendaftaran_id);
        $this->db->update($this->table_def_pendaftaran, $data_pendaftaran);
		
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
        $this->db->delete($this->table_def_pendaftaran); 
    }
	
	public function batal($id) {
		$this->db->set('batal', true);
        $this->db->where('id', $id);
        $this->db->update($this->table_def_pendaftaran);
    }
	
	public function getLastNoAntrian($tanggal, $poliklinik_id) {
		$this->db->select('no_antrian');
        $this->db->where('tanggal', $tanggal);
		$this->db->where('poliklinik_id', $poliklinik_id);
		$this->db->order_by('no_antrian', 'desc');
        $query = $this->db->get($this->table_def_pendaftaran);
        if ($query->num_rows() > 0) {
			$row = $query->first_row();
			return $row->no_antrian;
        }
		else {
			return 0;
		}
	}
	
	public function getHubunganDenganPasien() {
		return self::$_hubungan_dengan_pasien;
	}
	
	public function getHubunganDenganPasienDescr($index) {
		return self::$_hubungan_dengan_pasien[$index];
	}
	
	private function _toArrayPasien($pendaftaran) {
		$data = array(
            'no_medrec'		=> $pendaftaran->no_medrec,
            'nama'			=> $pendaftaran->nama,
			'jenis_kelamin'	=> $pendaftaran->jenis_kelamin,
			'alamat_jalan'	=> $pendaftaran->alamat_jalan,
			'alamat_rt'		=> $pendaftaran->alamat_rt,
			'alamat_rw'		=> $pendaftaran->alamat_rw,
			'provinsi_id'	=> $pendaftaran->provinsi_id,
			'kabupaten_id'	=> $pendaftaran->kabupaten_id,
			'kecamatan_id'	=> $pendaftaran->kecamatan_id,
			'kelurahan_id'	=> $pendaftaran->kelurahan_id,
			'tempat_lahir'	=> $pendaftaran->tempat_lahir,
			'tanggal_lahir'	=> $pendaftaran->tanggal_lahir,
			'agama_id'		=> $pendaftaran->agama_id,
			'pendidikan_id'	=> $pendaftaran->pendidikan_id,
			'pekerjaan_id'	=> $pendaftaran->pekerjaan_id
        );
		return $data;
	}
	
	private function _toArrayPendaftaran($pendaftaran) {
		$data = array(
            'pasien_id'			=> $pendaftaran->pasien_id,
            'tanggal'			=> $pendaftaran->tanggal,
			'no_register'		=> $pendaftaran->no_register,
			'umur_tahun'		=> $pendaftaran->umur_tahun,
			'umur_bulan'		=> $pendaftaran->umur_bulan,
			'umur_hari'			=> $pendaftaran->umur_hari,
			'baru'				=> $pendaftaran->baru,
			'rujukan_id'		=> $pendaftaran->rujukan_id,
			'cara_bayar_id'		=> $pendaftaran->cara_bayar_id,
			'poliklinik_id'		=> $pendaftaran->poliklinik_id,
			'dokter_id'			=> $pendaftaran->dokter_id,
			'pj_nama'			=> $pendaftaran->pj_nama,
			'pj_hubungan'		=> $pendaftaran->pj_hubungan,
			'pj_pekerjaan_id'	=> $pendaftaran->pj_pekerjaan_id,
			'pj_alamat'			=> $pendaftaran->pj_alamat,
        );
		return $data;
	}
	
	/**********************************
	 * BEGIN NO. REGISTER RAWAT JALAN *
	 **********************************/
	
	public function get_no_register() {
		// Ambil no. register lab yang terakhir
		$row = $this->db->select('id, last_number')
						->where('name', 'no_register_lab')
						->get('code_register')
						->row();
		// Tambah 1 ke no. register
		$no_register = intval($row->last_number) + 1;
		
		// Cari di tabel No. Register Queue apakah no register sudah ada
		// Jika sudah ada tambah 1 ke ke no. register
		$bFound = TRUE;
		while ($bFound) {
			$format_no_register = str_pad($no_register, 8, "0", STR_PAD_LEFT);
			
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
						->where('name', 'no_register_lab')
						->get('code_register')
						->row();
		// Tambah 1 ke no. register
		$no_register = intval($row->last_number) + 1;
		
		$data = array(
			'last_number' => $no_register
		);
		$this->db->where('name', 'no_register_lab');
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
	
	/********************************
	 * END NO. REGISTER RAWAT JALAN *
	 ********************************/
	
}

?>