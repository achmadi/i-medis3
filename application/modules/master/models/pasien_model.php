<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pasien_Model extends CI_Model {

	protected $table_def = "pasien";
	protected $table_def_wilayah = "wilayah";
	protected $table_def_agama = "agama";
	protected $table_def_pendidikan = "pendidikan";
	protected $table_def_pekerjaan = "pekerjaan";
    
	public function __construct() {
        parent::__construct();
    }
	
	private function _get_select() {
		$sql = "SELECT ";
		$sql .= "pasien.id, ";
		$sql .= "pasien.no_medrec, ";
		$sql .= "pasien.no_medrec_lama, ";
		$sql .= "pasien.nama, ";
		$sql .= "pasien.jenis_kelamin, ";
		$sql .= "pasien.alamat_jalan, ";
		$sql .= "pasien.provinsi_id, ";
		$sql .= "provinsi.nama AS provinsi, ";
		$sql .= "pasien.kabupaten_id, ";
		$sql .= "kabupaten.nama AS kabupaten, ";
		$sql .= "pasien.kecamatan_id, ";
		$sql .= "kecamatan.nama AS kecamatan, ";
		$sql .= "pasien.kelurahan_id, ";
		$sql .= "kelurahan.nama AS kelurahan, ";
		$sql .= "pasien.kodepos, ";
		$sql .= "pasien.telepon, ";
		$sql .= "pasien.tempat_lahir, ";
		$sql .= "pasien.tanggal_lahir, ";
		$sql .= "pasien.golongan_darah, ";
		$sql .= "pasien.agama_id, ";
		$sql .= "agama.nama AS agama, ";
		$sql .= "pasien.suku, ";
		$sql .= "pasien.pendidikan_id, ";
		$sql .= "pendidikan.nama AS pendidikan, ";
		$sql .= "pasien.pekerjaan_id, ";
		$sql .= "pekerjaan.nama AS pekerjaan, ";
		$sql .= "pasien.no_asuransi, ";
		$sql .= "pasien.peserta_asuransi, ";
		$sql .= "pasien.status_kawin, ";
		$sql .= "pasien.nama_keluarga, ";
		$sql .= "pasien.nama_pasangan, ";
		$sql .= "pasien.nama_orang_tua, ";
		$sql .= "pasien.pendidikan_orang_tua_id, ";
		$sql .= "pendidikan_ot.nama AS pendidikan_ot, ";
		$sql .= "pasien.pekerjaan_orang_tua_id, ";
		$sql .= "pekerjaan_ot.nama AS pekerjaan_ot, ";
		$sql .= "pasien.cara_bayar_id, ";
		$sql .= "cara_bayar.nama AS cara_bayar";
		return $sql;
	}
	
	private function _get_from() {
		$sql = "FROM pasien";
		return $sql;
	}
	
	private function _get_join() {
		$sql = "LEFT JOIN wilayah AS provinsi ON (pasien.provinsi_id = provinsi.id) ";
		$sql .= "LEFT JOIN wilayah AS kabupaten ON (pasien.kabupaten_id = kabupaten.id) ";
		$sql .= "LEFT JOIN wilayah AS kecamatan ON (pasien.kecamatan_id = kecamatan.id) ";
		$sql .= "LEFT JOIN wilayah AS kelurahan ON (pasien.kelurahan_id = kelurahan.id) ";
		$sql .= "LEFT JOIN agama ON (pasien.agama_id = agama.id) ";
		$sql .= "LEFT JOIN pekerjaan ON (pasien.pekerjaan_id = pekerjaan.id) ";
		$sql .= "LEFT JOIN pendidikan ON (pasien.pendidikan_id = pendidikan.id) ";
		$sql .= "LEFT JOIN pendidikan AS pendidikan_ot ON (pasien.pendidikan_orang_tua_id = pendidikan_ot.id) ";
		$sql .= "LEFT JOIN pekerjaan AS pekerjaan_ot ON (pasien.pekerjaan_orang_tua_id = pekerjaan_ot.id) ";
		$sql .= "LEFT JOIN cara_bayar ON (pasien.cara_bayar_id = cara_bayar.id)";
		return $sql;
	}
	
	public function getAll2($iLimit = 10, $iOffset = 0, $sOrder = "", $sWhere = "") {
		
		$data = array();
		
		$sql_cond = "SELECT id ";
		$sql_cond .= $this->_get_from()." ";
		if (!empty($sWhere)) {
			$sql_cond .= $sWhere." ";
		}
		if (!empty($sLike)) {
			$sql_cond .= $sLike." ";
		}
		if (!empty($sOrder)) {
			$sql_cond .= $sOrder." ";
		}
		$sql_cond .= "LIMIT ".$iOffset.", ".$iLimit;
		
		$select = "SELECT COUNT(*) AS numrows";
		$from = $this->_get_from();
		$sql = $select." ".$from;
		$query = $this->db->query($sql);
		if ($query->num_rows() == 0) {
			$data['total_rows'] = 0;
		}
		else {
			$row = $query->row();
			$data['total_rows'] = (int) $row->numrows;
		}
		
		$select = $this->_get_select();
		$from = $this->_get_from();
		$join = $this->_get_join();
		$join_to = "JOIN (".$sql_cond.") AS t ON t.id = pasien.id;";
		$sql = $select." ".$from." ".$join." ".$join_to;
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
		   $data['data'] = $query->result();
		}
		else {
			$data['data'] = array();
		}
		return $data;
	}
	
	public function getBy($aWheres = array(), $aOrWheres = array()) {
        $this->db->select($this->table_def.".id");
		$this->db->select($this->table_def.".no_medrec");
		$this->db->select($this->table_def.".no_medrec_lama");
		$this->db->select($this->table_def.".nama");
		$this->db->select($this->table_def.".jenis_kelamin");
		$this->db->select($this->table_def.".alamat_jalan");
		$this->db->select($this->table_def.".provinsi_id");
		$this->db->select("provinsi.nama AS provinsi");
		$this->db->select($this->table_def.".kabupaten_id");
		$this->db->select("kabupaten.nama AS kabupaten");
		$this->db->select($this->table_def.".kecamatan_id");
		$this->db->select("kecamatan.nama AS kecamatan");
		$this->db->select($this->table_def.".kelurahan_id");
		$this->db->select("kelurahan.nama AS kelurahan");
		$this->db->select($this->table_def.".kodepos");
		$this->db->select($this->table_def.".telepon");
		$this->db->select($this->table_def.".tempat_lahir");
		$this->db->select($this->table_def.".tanggal_lahir");
		$this->db->select($this->table_def.".golongan_darah");
		$this->db->select($this->table_def.".agama_id");
		$this->db->select($this->table_def_agama.".nama AS agama");
		$this->db->select($this->table_def.".pendidikan_id");
		$this->db->select($this->table_def_pendidikan.".nama AS pendidikan");
		$this->db->select($this->table_def.".pekerjaan_id");
		$this->db->select($this->table_def_pekerjaan.".nama AS pekerjaan");
		$this->db->select($this->table_def.".no_asuransi");
		$this->db->select($this->table_def.".peserta_asuransi");
		$this->db->select($this->table_def.".status_kawin");
		$this->db->select($this->table_def.".nama_keluarga");
		$this->db->select($this->table_def.".nama_pasangan");
		$this->db->select($this->table_def.".nama_orang_tua");
		$this->db->select($this->table_def.".pendidikan_orang_tua_id");
		$this->db->select("pendidikan_ot.nama AS pendidikan_ot");
		$this->db->select($this->table_def.".pekerjaan_orang_tua_id");
		$this->db->select("pekerjaan_ot.nama AS pekerjaan_ot");
		$this->db->join($this->table_def_wilayah." AS provinsi", $this->table_def.".provinsi_id = provinsi.id", "left");
		$this->db->join($this->table_def_wilayah." AS kabupaten", $this->table_def.".kabupaten_id = kabupaten.id", "left");
		$this->db->join($this->table_def_wilayah." AS kecamatan", $this->table_def.".kecamatan_id = kecamatan.id", "left");
		$this->db->join($this->table_def_wilayah." AS kelurahan", $this->table_def.".kelurahan_id = kelurahan.id", "left");
		$this->db->join($this->table_def_agama, $this->table_def.".agama_id = ".$this->table_def_agama.".id", "left");
		$this->db->join($this->table_def_pendidikan, $this->table_def.".pendidikan_id = ".$this->table_def_pendidikan.".id", "left");
		$this->db->join($this->table_def_pekerjaan, $this->table_def.".pekerjaan_id = ".$this->table_def_pekerjaan.".id", "left");
		$this->db->join($this->table_def_pendidikan." AS pendidikan_ot", $this->table_def.".pendidikan_orang_tua_id = pendidikan_ot.id", "left");
		$this->db->join($this->table_def_pekerjaan." AS pekerjaan_ot", $this->table_def.".pekerjaan_orang_tua_id = pekerjaan_ot.id", "left");
		if (count($aWheres) > 0) {
			$this->db->where($aWheres);
		}
		if (count($aOrWheres) > 0) {
			$this->db->or_where($aOrWheres);
		}
        $query = $this->db->get($this->table_def);
        if ($query->num_rows() > 0) {
			return $query->row();
        }
		else {
			return false;
		}
    }
	
	public function getAll($limit = 10, $offset = 0, $aOrders = array(), $aWheres = array(), $aOrWheres = array(), $aLikes = array(), $aOrLikes = array()) {
		$data = array();
		$this->db->start_cache();
		$this->db->select($this->table_def.".id");
		$this->db->select($this->table_def.".no_medrec");
		$this->db->select($this->table_def.".nama");
		$this->db->select($this->table_def.".jenis_kelamin");
		$this->db->select($this->table_def.".alamat_jalan");
		$this->db->select($this->table_def.".provinsi_id");
		$this->db->select("provinsi.nama AS provinsi");
		$this->db->select($this->table_def.".kabupaten_id");
		$this->db->select("kabupaten.nama AS kabupaten");
		$this->db->select($this->table_def.".kecamatan_id");
		$this->db->select("kecamatan.nama AS kecamatan");
		$this->db->select($this->table_def.".kelurahan_id");
		$this->db->select("kelurahan.nama AS kelurahan");
		$this->db->select($this->table_def.".kodepos");
		$this->db->select($this->table_def.".telepon");
		$this->db->select($this->table_def.".tempat_lahir");
		$this->db->select($this->table_def.".tanggal_lahir");
		$this->db->select($this->table_def.".golongan_darah");
		$this->db->select($this->table_def.".agama_id");
		$this->db->select($this->table_def_agama.".nama AS agama");
		$this->db->select($this->table_def.".pendidikan_id");
		$this->db->select($this->table_def_pendidikan.".nama AS pendidikan");
		$this->db->select($this->table_def.".pekerjaan_id");
		$this->db->select($this->table_def_pekerjaan.".nama AS pekerjaan");
		$this->db->select($this->table_def.".no_asuransi");
		$this->db->select($this->table_def.".peserta_asuransi");
		$this->db->select($this->table_def.".status_kawin");
		$this->db->select($this->table_def.".nama_keluarga");
		$this->db->select($this->table_def.".nama_pasangan");
		$this->db->select($this->table_def.".nama_orang_tua");
		$this->db->select($this->table_def.".pendidikan_orang_tua_id");
		$this->db->select("pendidikan_ot.nama AS pendidikan_ot");
		$this->db->select($this->table_def.".pekerjaan_orang_tua_id");
		$this->db->select("pekerjaan_ot.nama AS pekerjaan_ot");
		$this->db->join($this->table_def_wilayah." AS provinsi", $this->table_def.".provinsi_id = provinsi.id", "left");
		$this->db->join($this->table_def_wilayah." AS kabupaten", $this->table_def.".kabupaten_id = kabupaten.id", "left");
		$this->db->join($this->table_def_wilayah." AS kecamatan", $this->table_def.".kecamatan_id = kecamatan.id", "left");
		$this->db->join($this->table_def_wilayah." AS kelurahan", $this->table_def.".kelurahan_id = kelurahan.id", "left");
		$this->db->join($this->table_def_agama, $this->table_def.".agama_id = ".$this->table_def_agama.".id", "left");
		$this->db->join($this->table_def_pendidikan, $this->table_def.".pendidikan_id = ".$this->table_def_pendidikan.".id", "left");
		$this->db->join($this->table_def_pekerjaan, $this->table_def.".pekerjaan_id = ".$this->table_def_pekerjaan.".id", "left");
		$this->db->join($this->table_def_pendidikan." AS pendidikan_ot", $this->table_def.".pendidikan_orang_tua_id = pendidikan_ot.id", "left");
		$this->db->join($this->table_def_pekerjaan." AS pekerjaan_ot", $this->table_def.".pekerjaan_orang_tua_id = pekerjaan_ot.id", "left");
		if (count($aWheres) > 0) {
			$this->db->where($aWheres);
		}
		if (count($aOrWheres) > 0) {
			$this->db->or_where($aOrWheres);
		}
		if (count($aLikes) > 0) {
			$this->db->like($aLikes);
		}
		if (count($aOrLikes) > 0) {
			$this->db->or_like($aOrLikes);
		}
		$this->db->stop_cache();
		
		$data['total_rows'] = $this->db->count_all_results($this->table_def);
		
		if (count($aOrders) > 0)
			foreach ($aOrders as $order => $direction)
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

    public function create($pasien) {
		$data = get_object_vars($pasien);
		unset($data['id']);
        $this->db->insert($this->table_def, $data);
        $id = $this->db->insert_id();
		
		$this->incr_no_medrec();
		
		$no_medrec_id = $this->session->userdata('register_no_medrec_id');
		$this->delete_no_medrec_from_queue($no_medrec_id);
		$this->session->set_userdata('register_no_medrec', FALSE);
		$this->session->set_userdata('register_no_medrec_id', 0);
		
		return $id;
    }
    
    public function update($pasien) {
		$data = get_object_vars($pasien);
		unset($data['id']);
        $this->db->where('id', $pasien->id);
        $this->db->update($this->table_def, $data);
    }
    
    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table_def); 
    }
	
	public function get_golongan_darah() {
		return array(
			'A'		=> 'A',
			'B'		=> 'B',
			'AB'	=> 'AB',
			'O'		=> 'O'
		);
	}
	
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