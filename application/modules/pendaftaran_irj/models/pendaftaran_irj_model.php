<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pendaftaran_IRJ_Model extends CI_Model {

	protected $table_def_pendaftaran = "pendaftaran_irj";
	protected $table_def_pasien = "pasien";
	protected $table_def_wilayah = "wilayah";
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
		$this->db->select($this->table_def_pendaftaran.'.id');
		$this->db->select($this->table_def_pendaftaran.'.tanggal');
		$this->db->select($this->table_def_pendaftaran.'.no_register');
		$this->db->select($this->table_def_pendaftaran.'.no_antrian');
		$this->db->select($this->table_def_pendaftaran.'.pasien_id');
		$this->db->select($this->table_def_pasien.'.no_medrec');
		$this->db->select($this->table_def_pasien.'.nama');
		$this->db->select($this->table_def_pasien.'.jenis_kelamin');
		$this->db->select($this->table_def_pasien.'.alamat_jalan');
		$this->db->select($this->table_def_pasien.'.provinsi_id');
		$this->db->select('provinsi.nama AS provinsi');
		$this->db->select($this->table_def_pasien.'.kabupaten_id');
		$this->db->select('kabupaten.nama AS kabupaten');
		$this->db->select($this->table_def_pasien.'.kelurahan_id');
		$this->db->select('kelurahan.nama AS kelurahan');
		$this->db->select($this->table_def_pasien.'.kecamatan_id');
		$this->db->select('kecamatan.nama AS kecamatan');
		$this->db->select($this->table_def_pasien.'.kodepos');
		$this->db->select($this->table_def_pasien.'.telepon');
		$this->db->select($this->table_def_pasien.'.tempat_lahir');
		$this->db->select($this->table_def_pasien.'.tanggal_lahir');
		$this->db->select($this->table_def_pendaftaran.'.umur_tahun');
		$this->db->select($this->table_def_pendaftaran.'.umur_bulan');
		$this->db->select($this->table_def_pendaftaran.'.umur_hari');
		$this->db->select($this->table_def_pendaftaran.'.baru');
		$this->db->select($this->table_def_pasien.'.golongan_darah');
		$this->db->select($this->table_def_pasien.'.agama_id');
		$this->db->select($this->table_def_agama.'.nama AS agama');
		$this->db->select($this->table_def_pasien.'.pendidikan_id');
		$this->db->select($this->table_def_pendidikan.'.nama AS pendidikan');
		$this->db->select($this->table_def_pasien.'.pekerjaan_id');
		$this->db->select($this->table_def_pekerjaan.'.nama AS pekerjaan');
		$this->db->select($this->table_def_pasien.'.no_asuransi');
		$this->db->select($this->table_def_pasien.'.peserta_asuransi');
		$this->db->select($this->table_def_pasien.'.status_kawin');
		$this->db->select($this->table_def_pasien.'.nama_keluarga');
		$this->db->select($this->table_def_pasien.'.nama_pasangan');
		$this->db->select($this->table_def_pasien.'.nama_orang_tua');
		$this->db->select($this->table_def_pasien.'.pendidikan_orang_tua_id');
		$this->db->select('pendidikan_orang_tua.nama AS pendidikan_orang_tua');
		$this->db->select($this->table_def_pasien.'.pekerjaan_orang_tua_id');
		$this->db->select('pekerjaan_orang_tua.nama AS pekerjaan_orang_tua');
		$this->db->select($this->table_def_pendaftaran.'.rujukan_id');
		$this->db->select($this->table_def_rujukan.'.nama AS rujukan');
		$this->db->select($this->table_def_pendaftaran.'.cara_bayar_id');
		$this->db->select($this->table_def_cara_bayar.'.nama AS cara_bayar');
		$this->db->select($this->table_def_pendaftaran.'.poliklinik_id');
		$this->db->select($this->table_def_poliklinik.'.nama AS poliklinik');
		$this->db->select($this->table_def_pendaftaran.'.dokter_id');
		$this->db->select($this->table_def_pegawai.'.nama AS dokter');
		$this->db->select($this->table_def_pendaftaran.'.pj_nama');
		$this->db->select($this->table_def_pendaftaran.'.pj_hubungan');
		$this->db->select($this->table_def_pendaftaran.'.pj_pekerjaan_id');
		$this->db->select('pj_pekerjaan.nama AS pj_pekerjaan');
		$this->db->select($this->table_def_pendaftaran.'.pj_alamat');
		$this->db->join($this->table_def_pasien, $this->table_def_pendaftaran.'.pasien_id = '.$this->table_def_pasien.'.id', 'left');
		$this->db->join($this->table_def_wilayah." AS provinsi", $this->table_def_pasien.'.provinsi_id = provinsi.id', 'left');
		$this->db->join($this->table_def_wilayah." AS kabupaten", $this->table_def_pasien.'.kabupaten_id = kabupaten.id', 'left');
		$this->db->join($this->table_def_wilayah." AS kecamatan", $this->table_def_pasien.'.kecamatan_id = kecamatan.id', 'left');
		$this->db->join($this->table_def_wilayah." AS kelurahan", $this->table_def_pasien.'.kelurahan_id = kelurahan.id', 'left');
		$this->db->join($this->table_def_rujukan, $this->table_def_pendaftaran.'.rujukan_id = '.$this->table_def_rujukan.'.id', 'left');
		$this->db->join($this->table_def_cara_bayar, $this->table_def_pendaftaran.'.cara_bayar_id = '.$this->table_def_cara_bayar.'.id', 'left');
		$this->db->join($this->table_def_poliklinik, $this->table_def_pendaftaran.'.poliklinik_id = '.$this->table_def_poliklinik.'.id', 'left');
		$this->db->join($this->table_def_pegawai, $this->table_def_pendaftaran.'.dokter_id = '.$this->table_def_pegawai.'.id', 'left');
		$this->db->join($this->table_def_agama, $this->table_def_pasien.'.agama_id = '.$this->table_def_agama.'.id', 'left');
		$this->db->join($this->table_def_pendidikan, $this->table_def_pasien.'.pendidikan_id = '.$this->table_def_pendidikan.'.id', 'left');
		$this->db->join($this->table_def_pekerjaan, $this->table_def_pasien.'.pekerjaan_id = '.$this->table_def_pekerjaan.'.id', 'left');
		$this->db->join($this->table_def_pendidikan." AS pendidikan_orang_tua", $this->table_def_pasien.'.pendidikan_orang_tua_id = pendidikan_orang_tua.id', 'left');
		$this->db->join($this->table_def_pekerjaan." AS pekerjaan_orang_tua", $this->table_def_pasien.'.pekerjaan_orang_tua_id = pekerjaan_orang_tua.id', 'left');
		$this->db->join($this->table_def_pekerjaan." AS pj_pekerjaan", $this->table_def_pendaftaran.'.pj_pekerjaan_id = pj_pekerjaan.id', 'left');
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
		$this->db->select($this->table_def_pendaftaran.'.id');
		$this->db->select($this->table_def_pendaftaran.'.tanggal');
		$this->db->select($this->table_def_pendaftaran.'.no_register');
		$this->db->select($this->table_def_pendaftaran.'.no_antrian');
		$this->db->select($this->table_def_pendaftaran.'.pasien_id');
		$this->db->select($this->table_def_pasien.'.no_medrec');
		$this->db->select($this->table_def_pasien.'.nama');
		$this->db->select($this->table_def_pasien.'.jenis_kelamin');
		$this->db->select($this->table_def_pasien.'.alamat_jalan');
		$this->db->select($this->table_def_pasien.'.provinsi_id');
		$this->db->select('provinsi.nama AS provinsi');
		$this->db->select($this->table_def_pasien.'.kabupaten_id');
		$this->db->select('kabupaten.nama AS kabupaten');
		$this->db->select($this->table_def_pasien.'.kelurahan_id');
		$this->db->select('kelurahan.nama AS kelurahan');
		$this->db->select($this->table_def_pasien.'.kecamatan_id');
		$this->db->select('kecamatan.nama AS kecamatan');
		$this->db->select($this->table_def_pasien.'.kodepos');
		$this->db->select($this->table_def_pasien.'.telepon');
		$this->db->select($this->table_def_pasien.'.tempat_lahir');
		$this->db->select($this->table_def_pasien.'.tanggal_lahir');
		$this->db->select($this->table_def_pendaftaran.'.umur_tahun');
		$this->db->select($this->table_def_pendaftaran.'.umur_bulan');
		$this->db->select($this->table_def_pendaftaran.'.umur_hari');
		$this->db->select($this->table_def_pendaftaran.'.baru');
		$this->db->select($this->table_def_pasien.'.golongan_darah');
		$this->db->select($this->table_def_pasien.'.agama_id');
		$this->db->select($this->table_def_agama.'.nama AS agama');
		$this->db->select($this->table_def_pasien.'.pendidikan_id');
		$this->db->select($this->table_def_pendidikan.'.nama AS pendidikan');
		$this->db->select($this->table_def_pasien.'.pekerjaan_id');
		$this->db->select($this->table_def_pekerjaan.'.nama AS pekerjaan');
		$this->db->select($this->table_def_pasien.'.no_asuransi');
		$this->db->select($this->table_def_pasien.'.peserta_asuransi');
		$this->db->select($this->table_def_pasien.'.status_kawin');
		$this->db->select($this->table_def_pasien.'.nama_keluarga');
		$this->db->select($this->table_def_pasien.'.nama_pasangan');
		$this->db->select($this->table_def_pasien.'.nama_orang_tua');
		$this->db->select($this->table_def_pasien.'.pendidikan_orang_tua_id');
		$this->db->select('pendidikan_orang_tua.nama AS pendidikan_orang_tua');
		$this->db->select($this->table_def_pasien.'.pekerjaan_orang_tua_id');
		$this->db->select('pekerjaan_orang_tua.nama AS pekerjaan_orang_tua');
		$this->db->select($this->table_def_pendaftaran.'.rujukan_id');
		$this->db->select($this->table_def_rujukan.'.nama AS rujukan');
		$this->db->select($this->table_def_pendaftaran.'.cara_bayar_id');
		$this->db->select($this->table_def_cara_bayar.'.nama AS cara_bayar');
		$this->db->select($this->table_def_pendaftaran.'.poliklinik_id');
		$this->db->select($this->table_def_poliklinik.'.nama AS poliklinik');
		$this->db->select($this->table_def_pendaftaran.'.dokter_id');
		$this->db->select($this->table_def_pegawai.'.nama AS dokter');
		$this->db->select($this->table_def_pendaftaran.'.pj_nama');
		$this->db->select($this->table_def_pendaftaran.'.pj_hubungan');
		$this->db->select($this->table_def_pendaftaran.'.pj_pekerjaan_id');
		$this->db->select('pj_pekerjaan.nama AS pj_pekerjaan');
		$this->db->select($this->table_def_pendaftaran.'.pj_alamat');
		$this->db->join($this->table_def_pasien, $this->table_def_pendaftaran.'.pasien_id = '.$this->table_def_pasien.'.id', 'left');
		$this->db->join($this->table_def_wilayah." AS provinsi", $this->table_def_pasien.'.provinsi_id = provinsi.id', 'left');
		$this->db->join($this->table_def_wilayah." AS kabupaten", $this->table_def_pasien.'.kabupaten_id = kabupaten.id', 'left');
		$this->db->join($this->table_def_wilayah." AS kecamatan", $this->table_def_pasien.'.kecamatan_id = kecamatan.id', 'left');
		$this->db->join($this->table_def_wilayah." AS kelurahan", $this->table_def_pasien.'.kelurahan_id = kelurahan.id', 'left');
		$this->db->join($this->table_def_rujukan, $this->table_def_pendaftaran.'.rujukan_id = '.$this->table_def_rujukan.'.id', 'left');
		$this->db->join($this->table_def_cara_bayar, $this->table_def_pendaftaran.'.cara_bayar_id = '.$this->table_def_cara_bayar.'.id', 'left');
		$this->db->join($this->table_def_poliklinik, $this->table_def_pendaftaran.'.poliklinik_id = '.$this->table_def_poliklinik.'.id', 'left');
		$this->db->join($this->table_def_pegawai, $this->table_def_pendaftaran.'.dokter_id = '.$this->table_def_pegawai.'.id', 'left');
		$this->db->join($this->table_def_agama, $this->table_def_pasien.'.agama_id = '.$this->table_def_agama.'.id', 'left');
		$this->db->join($this->table_def_pendidikan, $this->table_def_pasien.'.pendidikan_id = '.$this->table_def_pendidikan.'.id', 'left');
		$this->db->join($this->table_def_pekerjaan, $this->table_def_pasien.'.pekerjaan_id = '.$this->table_def_pekerjaan.'.id', 'left');
		$this->db->join($this->table_def_pendidikan." AS pendidikan_orang_tua", $this->table_def_pasien.'.pendidikan_orang_tua_id = pendidikan_orang_tua.id', 'left');
		$this->db->join($this->table_def_pekerjaan." AS pekerjaan_orang_tua", $this->table_def_pasien.'.pekerjaan_orang_tua_id = pekerjaan_orang_tua.id', 'left');
		$this->db->join($this->table_def_pekerjaan." AS pj_pekerjaan", $this->table_def_pendaftaran.'.pj_pekerjaan_id = pj_pekerjaan.id', 'left');
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
	
	public function getAllPasien($limit = 10, $offset = 0, $orders = array(), $wheres = array(), $likes = array()) {
		$data = array();
		$this->db->start_cache();
		$this->db->select($this->table_def_pendaftaran.'.id');
		$this->db->select($this->table_def_pendaftaran.'.tanggal');
		$this->db->select($this->table_def_pendaftaran.'.no_register');
		$this->db->select($this->table_def_pendaftaran.'.no_antrian');
		$this->db->select($this->table_def_pendaftaran.'.pasien_id');
		$this->db->select($this->table_def_pasien.'.no_medrec');
		$this->db->select($this->table_def_pasien.'.nama');
		$this->db->select($this->table_def_pasien.'.jenis_kelamin');
		$this->db->select($this->table_def_pasien.'.alamat_jalan');
		$this->db->select($this->table_def_pasien.'.provinsi_id');
		$this->db->select('provinsi.nama AS provinsi');
		$this->db->select($this->table_def_pasien.'.kabupaten_id');
		$this->db->select('kabupaten.nama AS kabupaten');
		$this->db->select($this->table_def_pasien.'.kelurahan_id');
		$this->db->select('kelurahan.nama AS kelurahan');
		$this->db->select($this->table_def_pasien.'.kecamatan_id');
		$this->db->select('kecamatan.nama AS kecamatan');
		$this->db->select($this->table_def_pasien.'.kodepos');
		$this->db->select($this->table_def_pasien.'.telepon');
		$this->db->select($this->table_def_pasien.'.tempat_lahir');
		$this->db->select($this->table_def_pasien.'.tanggal_lahir');
		$this->db->select($this->table_def_pendaftaran.'.umur_tahun');
		$this->db->select($this->table_def_pendaftaran.'.umur_bulan');
		$this->db->select($this->table_def_pendaftaran.'.umur_hari');
		$this->db->select($this->table_def_pendaftaran.'.baru');
		$this->db->select($this->table_def_pasien.'.golongan_darah');
		$this->db->select($this->table_def_pasien.'.agama_id');
		$this->db->select($this->table_def_agama.'.nama AS agama');
		$this->db->select($this->table_def_pasien.'.pendidikan_id');
		$this->db->select($this->table_def_pendidikan.'.nama AS pendidikan');
		$this->db->select($this->table_def_pasien.'.pekerjaan_id');
		$this->db->select($this->table_def_pekerjaan.'.nama AS pekerjaan');
		$this->db->select($this->table_def_pasien.'.no_asuransi');
		$this->db->select($this->table_def_pasien.'.peserta_asuransi');
		$this->db->select($this->table_def_pasien.'.status_kawin');
		$this->db->select($this->table_def_pasien.'.nama_keluarga');
		$this->db->select($this->table_def_pasien.'.nama_pasangan');
		$this->db->select($this->table_def_pasien.'.nama_orang_tua');
		$this->db->select($this->table_def_pasien.'.pendidikan_orang_tua_id');
		$this->db->select('pendidikan_orang_tua.nama AS pendidikan_orang_tua');
		$this->db->select($this->table_def_pasien.'.pekerjaan_orang_tua_id');
		$this->db->select('pekerjaan_orang_tua.nama AS pekerjaan_orang_tua');
		$this->db->select($this->table_def_pendaftaran.'.rujukan_id');
		$this->db->select($this->table_def_rujukan.'.nama AS rujukan');
		$this->db->select($this->table_def_pendaftaran.'.cara_bayar_id');
		$this->db->select($this->table_def_cara_bayar.'.nama AS cara_bayar');
		$this->db->select($this->table_def_pendaftaran.'.poliklinik_id');
		$this->db->select($this->table_def_poliklinik.'.nama AS poliklinik');
		$this->db->select($this->table_def_pendaftaran.'.dokter_id');
		$this->db->select($this->table_def_pegawai.'.nama AS dokter');
		$this->db->select($this->table_def_pendaftaran.'.pj_nama');
		$this->db->select($this->table_def_pendaftaran.'.pj_hubungan');
		$this->db->select($this->table_def_pendaftaran.'.pj_pekerjaan_id');
		$this->db->select($this->table_def_pendaftaran.'.pj_alamat');
		$this->db->join($this->table_def_pasien, $this->table_def_pendaftaran.'.pasien_id = '.$this->table_def_pasien.'.id', 'left');
		$this->db->join($this->table_def_wilayah." AS provinsi", $this->table_def_pasien.'.provinsi_id = provinsi.id', 'left');
		$this->db->join($this->table_def_wilayah." AS kabupaten", $this->table_def_pasien.'.kabupaten_id = kabupaten.id', 'left');
		$this->db->join($this->table_def_wilayah." AS kecamatan", $this->table_def_pasien.'.kecamatan_id = kecamatan.id', 'left');
		$this->db->join($this->table_def_wilayah." AS kelurahan", $this->table_def_pasien.'.kelurahan_id = kelurahan.id', 'left');
		$this->db->join($this->table_def_rujukan, $this->table_def_pendaftaran.'.rujukan_id = '.$this->table_def_rujukan.'.id', 'left');
		$this->db->join($this->table_def_cara_bayar, $this->table_def_pendaftaran.'.cara_bayar_id = '.$this->table_def_cara_bayar.'.id', 'left');
		$this->db->join($this->table_def_poliklinik, $this->table_def_pendaftaran.'.poliklinik_id = '.$this->table_def_poliklinik.'.id', 'left');
		$this->db->join($this->table_def_pegawai, $this->table_def_pendaftaran.'.dokter_id = '.$this->table_def_pegawai.'.id', 'left');
		$this->db->join($this->table_def_agama, $this->table_def_pasien.'.agama_id = '.$this->table_def_agama.'.id', 'left');
		$this->db->join($this->table_def_pendidikan, $this->table_def_pasien.'.pendidikan_id = '.$this->table_def_pendidikan.'.id', 'left');
		$this->db->join($this->table_def_pekerjaan, $this->table_def_pasien.'.pekerjaan_id = '.$this->table_def_pekerjaan.'.id', 'left');
		$this->db->join($this->table_def_pendidikan." AS pendidikan_orang_tua", $this->table_def_pasien.'.pendidikan_orang_tua_id = pendidikan_orang_tua.id', 'left');
		$this->db->join($this->table_def_pekerjaan." AS pekerjaan_orang_tua", $this->table_def_pasien.'.pekerjaan_orang_tua_id = pekerjaan_orang_tua.id', 'left');
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
	
	public function get_tanggal_awal() {
		$this->db->select($this->table_def_pendaftaran.".tanggal");
		$this->db->order_by($this->table_def_pendaftaran.".tanggal", "ASC");
		$query = $this->db->get($this->table_def_pendaftaran);
		if ($query->num_rows() > 0) {
			$row = $query->first_row();
			return $row->tanggal;
		}
		else {
			return false;
		}
	}
	
	public function get_tanggal_akhir() {
		$this->db->select($this->table_def_pendaftaran.".tanggal");
		$this->db->order_by($this->table_def_pendaftaran.".tanggal", "DESC");
		$query = $this->db->get($this->table_def_pendaftaran);
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
		$data_pendaftaran['tindak_lanjut'] = $this->config->item('ID_TINDAK_LANJUT_PERAWATAN_PULANG');
		$data_pendaftaran['pelayanan'] = false;
		$data_pendaftaran['batal'] = false;
		$data_pendaftaran['created'] = get_current_date();
		$data_pendaftaran['created_by'] = 0;
		$data_pendaftaran['modified'] = null;
		$data_pendaftaran['modified_by'] = null;
		$data_pendaftaran['version'] = 0;
        $this->db->insert($this->table_def_pendaftaran, $data_pendaftaran);
        $id = $this->db->insert_id();
		
		$this->incr_no_register();
		
		$register_id = $this->session->userdata('register_no_register_irj_id');
		$this->delete_no_register_from_queue($register_id);
		$this->session->set_userdata('register_no_register_irj', FALSE);
		$this->session->set_userdata('register_no_register_irj_id', 0);
		
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
		$date = date('Y-m-d', strtotime($tanggal));
		$this->db->select('no_antrian');
		$this->db->where('poliklinik_id', $poliklinik_id);
		$this->db->like('tanggal', $date);
		$this->db->order_by('no_antrian', 'DESC');
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
            'no_medrec'					=> $pendaftaran->no_medrec,
            'nama'						=> $pendaftaran->nama,
			'jenis_kelamin'				=> $pendaftaran->jenis_kelamin,
			'alamat_jalan'				=> $pendaftaran->alamat_jalan,
			'provinsi_id'				=> $pendaftaran->provinsi_id,
			'kabupaten_id'				=> $pendaftaran->kabupaten_id,
			'kecamatan_id'				=> $pendaftaran->kecamatan_id,
			'kelurahan_id'				=> $pendaftaran->kelurahan_id,
			'kodepos'					=> $pendaftaran->kodepos,
			'telepon'					=> $pendaftaran->telepon,
			'tempat_lahir'				=> $pendaftaran->tempat_lahir,
			'tanggal_lahir'				=> $pendaftaran->tanggal_lahir,
			'golongan_darah'			=> $pendaftaran->golongan_darah,
			'agama_id'					=> $pendaftaran->agama_id,
			'pendidikan_id'				=> $pendaftaran->pendidikan_id,
			'pekerjaan_id'				=> $pendaftaran->pekerjaan_id,
			'no_asuransi'				=> $pendaftaran->no_asuransi,
			'peserta_asuransi'			=> $pendaftaran->peserta_asuransi,
			'status_kawin'				=> $pendaftaran->status_kawin,
			'nama_keluarga'				=> $pendaftaran->nama_keluarga,
			'nama_pasangan'				=> $pendaftaran->nama_pasangan,
			'nama_orang_tua'			=> $pendaftaran->nama_orang_tua,
			'pendidikan_orang_tua_id'	=> $pendaftaran->pendidikan_orang_tua_id,
			'pekerjaan_orang_tua_id'	=> $pendaftaran->pekerjaan_orang_tua_id
        );
		return $data;
	}
	
	private function _toArrayPendaftaran($pendaftaran) {
		$data = array(
            'pasien_id'					=> $pendaftaran->pasien_id,
            'tanggal'					=> $pendaftaran->tanggal,
			'no_register'				=> $pendaftaran->no_register,
			'umur_tahun'				=> $pendaftaran->umur_tahun,
			'umur_bulan'				=> $pendaftaran->umur_bulan,
			'umur_hari'					=> $pendaftaran->umur_hari,
			'baru'						=> $pendaftaran->baru,
			'rujukan_id'				=> $pendaftaran->rujukan_id,
			'cara_bayar_id'				=> $pendaftaran->cara_bayar_id,
			'poliklinik_id'				=> $pendaftaran->poliklinik_id,
			'dokter_id'					=> $pendaftaran->dokter_id,
			'pj_nama'					=> $pendaftaran->pj_nama,
			'pj_hubungan'				=> $pendaftaran->pj_hubungan,
			'pj_pekerjaan_id'			=> $pendaftaran->pj_pekerjaan_id,
			'pj_alamat'					=> $pendaftaran->pj_alamat
        );
		return $data;
	}
	
	/*
	SELECT
		`poliklinik`.`nama` AS `klinik`
		, SUM(IF(jenis_kelamin = 1, 1, 0)) AS `l`
		, SUM(IF(jenis_kelamin = 2, 1, 0)) AS `p`
		, COUNT(`pasien`.`jenis_kelamin`) AS `total`
		, `pendaftaran_irj`.`tanggal`
	FROM
		`i-medis3`.`pendaftaran_irj`
		LEFT JOIN `i-medis3`.`poliklinik` 
			ON (`pendaftaran_irj`.`poliklinik_id` = `poliklinik`.`id`)
		LEFT JOIN `i-medis3`.`pasien` 
			ON (`pendaftaran_irj`.`pasien_id` = `pasien`.`id`)
	WHERE (`pendaftaran_irj`.`tanggal` >= 1/1/2013)
		OR (`pendaftaran_irj`.`tanggal` <= 12/31/2013)
	GROUP BY `klinik`;
	*/
	
	public function get_kunjungan_per_klinik($limit = 10, $offset = 0, $orders = array(), $wheres = array(), $likes = array()) {
		$data = array();
		$this->db->start_cache();
		$this->db->select($this->table_def_pendaftaran.".tanggal", FALSE);
		$this->db->select($this->table_def_poliklinik.".nama AS klinik", FALSE);
		$this->db->select("SUM(IF(".$this->table_def_pasien.".jenis_kelamin = 1, 1, 0)) AS l", FALSE);
		$this->db->select("SUM(IF(".$this->table_def_pasien.".jenis_kelamin = 2, 1 * 1, 0)) AS p", FALSE);
		$this->db->select("COUNT(".$this->table_def_pasien.".jenis_kelamin) AS total", FALSE);
		$this->db->join($this->table_def_poliklinik, $this->table_def_pendaftaran.'.poliklinik_id = '.$this->table_def_poliklinik.'.id', 'left');
		$this->db->join($this->table_def_pasien, $this->table_def_pendaftaran.'.pasien_id = '.$this->table_def_pasien.'.id', 'left');
		$this->db->group_by($this->table_def_poliklinik.".nama");
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
	
	public function get_kunjungan_per_cara_bayar($limit = 10, $offset = 0, $orders = array(), $wheres = array(), $likes = array()) {
		$data = array();
		$this->db->start_cache();
		$this->db->select($this->table_def_pendaftaran.".tanggal", FALSE);
		$this->db->select($this->table_def_cara_bayar.".nama AS cara_bayar", FALSE);
		$this->db->select("SUM(IF(".$this->table_def_pasien.".jenis_kelamin = 1, 1, 0)) AS l", FALSE);
		$this->db->select("SUM(IF(".$this->table_def_pasien.".jenis_kelamin = 2, 1 * 1, 0)) AS p", FALSE);
		$this->db->select("COUNT(".$this->table_def_pasien.".jenis_kelamin) AS total", FALSE);
		$this->db->join($this->table_def_cara_bayar, $this->table_def_pendaftaran.'.cara_bayar_id = '.$this->table_def_cara_bayar.'.id', 'left');
		$this->db->join($this->table_def_pasien, $this->table_def_pendaftaran.'.pasien_id = '.$this->table_def_pasien.'.id', 'left');
		$this->db->group_by($this->table_def_cara_bayar.".nama");
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
	
	public function get_kunjungan_per_wilayah($limit = 10, $offset = 0, $orders = array(), $wheres = array(), $likes = array()) {
		$data = array();
		$this->db->start_cache();
		$this->db->select($this->table_def_pendaftaran.".tanggal", FALSE);
		$this->db->select($this->table_def_wilayah.".nama AS wilayah", FALSE);
		$this->db->select("SUM(IF(".$this->table_def_pasien.".jenis_kelamin = 1, 1, 0)) AS l", FALSE);
		$this->db->select("SUM(IF(".$this->table_def_pasien.".jenis_kelamin = 2, 1 * 1, 0)) AS p", FALSE);
		$this->db->select("COUNT(".$this->table_def_pasien.".jenis_kelamin) AS total", FALSE);
		$this->db->join($this->table_def_pasien, $this->table_def_pendaftaran.'.pasien_id = '.$this->table_def_pasien.'.id', 'left');
		$this->db->join($this->table_def_wilayah, $this->table_def_pasien.'.provinsi_id = '.$this->table_def_wilayah.'.id', 'left');
		$this->db->group_by($this->table_def_wilayah.".nama");
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
	
	public function get_kunjungan_per_cara_kunjungan($limit = 10, $offset = 0, $orders = array(), $wheres = array(), $likes = array()) {
		$data = array();
		$this->db->start_cache();
		$this->db->select($this->table_def_pendaftaran.".tanggal", FALSE);
		$this->db->select($this->table_def_pendaftaran.".baru AS kunjungan", FALSE);
		$this->db->select("SUM(IF(".$this->table_def_pasien.".jenis_kelamin = 1, 1, 0)) AS l", FALSE);
		$this->db->select("SUM(IF(".$this->table_def_pasien.".jenis_kelamin = 2, 1 * 1, 0)) AS p", FALSE);
		$this->db->select("COUNT(".$this->table_def_pasien.".jenis_kelamin) AS total", FALSE);
		$this->db->join($this->table_def_pasien, $this->table_def_pendaftaran.'.pasien_id = '.$this->table_def_pasien.'.id', 'left');
		$this->db->group_by($this->table_def_pendaftaran.".baru");
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
	
	public function get_buku_pasien_register($limit = 10, $offset = 0, $orders = array(), $wheres = array(), $likes = array()) {
		$data = array();
		$this->db->start_cache();
		$this->db->select($this->table_def_pasien.".no_medrec", FALSE);
		$this->db->select($this->table_def_pasien.".nama", FALSE);
		$this->db->select($this->table_def_pasien.".alamat_jalan", FALSE);
		$this->db->select($this->table_def_agama.".nama AS agama", FALSE);
		$this->db->select($this->table_def_pendidikan.".nama AS pendidikan", FALSE);
		$this->db->select($this->table_def_pekerjaan.".nama AS pekerjaan", FALSE);
		$this->db->select($this->table_def_pasien.".jenis_kelamin", FALSE);
		$this->db->select($this->table_def_pendaftaran.".baru", FALSE);
		$this->db->select($this->table_def_pendaftaran.".umur_tahun", FALSE);
		$this->db->select($this->table_def_cara_bayar.".nama AS cara_bayar", FALSE);
		$this->db->join($this->table_def_pasien, $this->table_def_pendaftaran.'.pasien_id = '.$this->table_def_pasien.'.id', 'left');
		$this->db->join($this->table_def_agama, $this->table_def_pasien.'.agama_id = '.$this->table_def_agama.'.id', 'left');
		$this->db->join($this->table_def_pendidikan, $this->table_def_pasien.'.pendidikan_id = '.$this->table_def_pendidikan.'.id', 'left');
		$this->db->join($this->table_def_pekerjaan, $this->table_def_pasien.'.pekerjaan_id = '.$this->table_def_pekerjaan.'.id', 'left');
		$this->db->join($this->table_def_cara_bayar, $this->table_def_pendaftaran.'.cara_bayar_id = '.$this->table_def_cara_bayar.'.id', 'left');
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
	
	public function get_kartu_rekam_medis($limit = 10, $offset = 0, $orders = array(), $wheres = array(), $likes = array()) {
		$data = array();
		$this->db->start_cache();
		$this->db->select($this->table_def_pasien.".no_medrec", FALSE);
		$this->db->select($this->table_def_pasien.".nama", FALSE);
		$this->db->select($this->table_def_pasien.".alamat_jalan", FALSE);
		$this->db->select($this->table_def_agama.".nama AS agama", FALSE);
		$this->db->select($this->table_def_pendidikan.".nama AS pendidikan", FALSE);
		$this->db->select($this->table_def_pekerjaan.".nama AS pekerjaan", FALSE);
		$this->db->select($this->table_def_pasien.".jenis_kelamin", FALSE);
		$this->db->select($this->table_def_pendaftaran.".baru", FALSE);
		$this->db->select($this->table_def_pendaftaran.".umur_tahun", FALSE);
		$this->db->select($this->table_def_cara_bayar.".nama AS cara_bayar", FALSE);
		$this->db->join($this->table_def_pasien, $this->table_def_pendaftaran.'.pasien_id = '.$this->table_def_pasien.'.id', 'left');
		$this->db->join($this->table_def_agama, $this->table_def_pasien.'.agama_id = '.$this->table_def_agama.'.id', 'left');
		$this->db->join($this->table_def_pendidikan, $this->table_def_pasien.'.pendidikan_id = '.$this->table_def_pendidikan.'.id', 'left');
		$this->db->join($this->table_def_pekerjaan, $this->table_def_pasien.'.pekerjaan_id = '.$this->table_def_pekerjaan.'.id', 'left');
		$this->db->join($this->table_def_cara_bayar, $this->table_def_pendaftaran.'.cara_bayar_id = '.$this->table_def_cara_bayar.'.id', 'left');
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
	
	public function get_bank_nomor_rekam_medis($limit = 10, $offset = 0, $orders = array(), $wheres = array(), $likes = array()) {
		$data = array();
		$this->db->start_cache();
		$this->db->select($this->table_def_pasien.".no_medrec", FALSE);
		$this->db->select($this->table_def_pasien.".nama", FALSE);
		$this->db->select($this->table_def_pasien.".alamat_jalan", FALSE);
		$this->db->select($this->table_def_agama.".nama AS agama", FALSE);
		$this->db->select($this->table_def_pendidikan.".nama AS pendidikan", FALSE);
		$this->db->select($this->table_def_pekerjaan.".nama AS pekerjaan", FALSE);
		$this->db->select($this->table_def_pasien.".jenis_kelamin", FALSE);
		$this->db->select($this->table_def_pendaftaran.".baru", FALSE);
		$this->db->select($this->table_def_pendaftaran.".umur_tahun", FALSE);
		$this->db->select($this->table_def_cara_bayar.".nama AS cara_bayar", FALSE);
		$this->db->join($this->table_def_pasien, $this->table_def_pendaftaran.'.pasien_id = '.$this->table_def_pasien.'.id', 'left');
		$this->db->join($this->table_def_agama, $this->table_def_pasien.'.agama_id = '.$this->table_def_agama.'.id', 'left');
		$this->db->join($this->table_def_pendidikan, $this->table_def_pasien.'.pendidikan_id = '.$this->table_def_pendidikan.'.id', 'left');
		$this->db->join($this->table_def_pekerjaan, $this->table_def_pasien.'.pekerjaan_id = '.$this->table_def_pekerjaan.'.id', 'left');
		$this->db->join($this->table_def_cara_bayar, $this->table_def_pendaftaran.'.cara_bayar_id = '.$this->table_def_cara_bayar.'.id', 'left');
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
	
	/**********************************
	 * BEGIN NO. REGISTER RAWAT JALAN *
	 **********************************/
	
	public function get_no_register() {
		// Ambil no. register lab yang terakhir
		$row = $this->db->select('id, last_number')
						->where('name', 'no_register_irj')
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
						->where('name', 'no_register_irj')
						->get('code_register')
						->row();
		// Tambah 1 ke no. register
		$no_register = intval($row->last_number) + 1;
		
		$data = array(
			'last_number' => $no_register
		);
		$this->db->where('name', 'no_register_irj');
		$this->db->update('code_register', $data);
		
		return TRUE;
	}
	
	public function insert_no_register_to_queue($id, $no_register) {
	
		$timezone = "Asia/Pontianak";
		if (function_exists('date_default_timezone_set'))
			date_default_timezone_set($timezone);
		$current_date = date("Y-m-d H:i:s");
		
		$data = array(
			'code_register_id'	=> $id,
			'date_save'			=> $current_date,
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
		$timezone = "Asia/Pontianak";
		if (function_exists('date_default_timezone_set'))
			date_default_timezone_set($timezone);
		$current_date = date("Y-m-d H:i:s");
		
        $this->db->where('date_save <', $current_date);
        $this->db->delete('code_register_queue');
    }
	
	/********************************
	 * END NO. REGISTER RAWAT JALAN *
	 ********************************/
    
	/********************
	 * BEGIN NO. MEDREC *
	 ********************/
	
	public function get_no_medrec() {
		// Ambil no. medrec yang terakhir
		$row = $this->db->select('id, last_number')
						->where('name', 'no_medrec')
						->get('code_register')
						->row();
		// Tambah 1 ke no. register
		$no_medrec = intval($row->last_number) + 1;
		
		// Cari di tabel No. Register Queue apakah no register sudah ada
		// Jika sudah ada tambah 1 ke ke no. register
		$bFound = TRUE;
		while ($bFound) {
			$format_no_medrec = str_pad($no_medrec, 6, "0", STR_PAD_LEFT);
			
			$query = $this->db->where('code_register_id', $row->id)
							  ->where('save_code', $format_no_medrec)
							  ->get('code_register_queue');
			
			if ($query->num_rows() > 0) {
				$bFound = TRUE;
				$no_medrec++;
			}
			else
				$bFound = FALSE;
		}
		
		$register = array();
		$register['no_medrec'] = $format_no_medrec;
		$register['no_medrec_queue_id'] = $this->insert_no_medrec_to_queue($row->id, $format_no_medrec);
		return $register;
	}
	
	public function incr_no_medrec() {
		// Ambil no. medrec yang terakhir
		$row = $this->db->select('id, last_number')
						->where('name', 'no_medrec')
						->get('code_register')
						->row();
		// Tambah 1 ke no. medrec
		$no_medrec = intval($row->last_number) + 1;
		
		$data = array(
			'last_number' => $no_medrec
		);
		$this->db->where('name', 'no_medrec');
		$this->db->update('code_register', $data);
		
		return TRUE;
	}
	
	public function insert_no_medrec_to_queue($id, $no_medrec) {
		$data = array(
			'code_register_id'	=> $id,
			'date_save'			=> get_current_date(),
			'save_code'			=> $no_medrec
		);
        $this->db->insert('code_register_queue', $data);
		$id = $this->db->insert_id();
		return $id;
    }
	
	public function delete_no_medrec_from_queue($id) {
        $this->db->where('id', $id);
        $this->db->delete('code_register_queue');
    }
	
	public function delete_expire_no_medrec_from_queue() {
        $this->db->where('date_save <', get_current_date());
        $this->db->delete('code_register_queue');
    }
	
	/******************
	 * END NO. MEDREC *
	 ******************/
	
}

?>