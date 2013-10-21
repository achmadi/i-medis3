<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pendaftaran_Model extends CI_Model {

	protected $table_def_1 = "pasien";
	protected $table_def_2 = "pendaftaran_ri";
    
	public function __construct() {
        parent::__construct();
    }
	
	public function getById($id) {
		$this->db->select('
			pasien.id AS pasien_id, 
			pendaftaran_irj.id AS pendaftaran_id, 
			pendaftaran_irj.tanggal,
			pendaftaran_irj.no_register,
			pendaftaran_irj.no_antrian,
			pasien.no_medrec, 
			pasien.nama, 
			pasien.jenis_kelamin, 
			pasien.alamat, 
			pasien.tempat_lahir, 
			pasien.tanggal_lahir, 
			pendaftaran_irj.umur_tahun, 
			pendaftaran_irj.umur_bulan, 
			pendaftaran_irj.umur_hari, 
			simrs_pasien.ortu_suami, 
			pasien.agama_id, 
			pasien.pendidikan_id, 
			pasien.pekerjaan_id, 
			pendaftaran_irj.baru, 
			pendaftaran_irj.rujukan_id, 
			pendaftaran_irj.cara_bayar_id, 
			cara_bayar.nama AS cara_bayar,
			pendaftaran_irj.unit_id, 
			unit.nama AS unit, 
			pendaftaran_irj.dokter_id, 
			dokter.nama AS dokter');
		$this->db->join($this->table_def_1, 'pasien.id = pendaftaran_irj.pasien_id', 'left');
		$this->db->join('cara_bayar', 'cara_bayar.id = pendaftaran_irj.cara_bayar_id', 'left');
		$this->db->join('unit', 'unit.id = pendaftaran_irj.unit_id', 'left');
		$this->db->join('dokter', 'dokter.id = pendaftaran_irj.dokter_id', 'left');
        $this->db->where('pendaftaran_irj.id', $id);
        $query = $this->db->get($this->table_def_2);
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
		$this->db->select('
			pendaftaran_irj.id, 
			pendaftaran_irj.tanggal, 
			pendaftaran_irj.no_register,
			pasien.no_medrec, pasien.nama, pasien.jenis_kelamin, pendaftaran_irj.cara_bayar_id, pasien.alamat');
		$this->db->join($this->table_def_1, 'pasien.id = pendaftaran_irj.pasien_id');
		if (count($where) > 0) {
			$this->db->where($where);
		}
		if (count($like) > 0) {
			$this->db->or_like($like);
		}
		$this->db->stop_cache();
		
		$data['total_rows'] = $this->db->count_all_results($this->table_def_2);
		
		if (count($orders) > 0)
			foreach ($orders as $order => $direction)
				$this->db->order_by($order, $direction);
		
		if ($limit == 0) {
            $data['data'] = $this->db->get($this->table_def_2)->result();
        }
        else {
            $data['data'] = $this->db->get($this->table_def_2, $limit, $offset)->result();
        }
		$this->db->flush_cache();
		
		return $data;
	}
	
	public function getAllPasien($limit = 10, $offset = 0, $order = 'pendaftaran_irj.tanggal, simrs_pasien.no_medrec, simrs_pasien.nama', $direction = 'desc', $where = array(), $like = array()) {
		$data = array();
		$this->db->start_cache();
		$this->db->select('pendaftaran_irj.id,
						   pendaftaran_irj.pasien_id,
						   pendaftaran_irj.tanggal,
						   simrs_pasien.no_medrec,
						   simrs_pasien.nama,
						   simrs_pasien.jenis_kelamin,
						   simrs_pasien.alamat,
						   simrs_pasien.tempat_lahir,
						   simrs_pasien.tanggal_lahir,
						   pendaftaran_irj.umur_tahun,
						   pendaftaran_irj.umur_bulan,
						   pendaftaran_irj.umur_hari,
						   simrs_pasien.ortu_suami,
						   pendaftaran_irj.cara_bayar_id,
						   simrs_cara_bayar.nama AS cara_bayar,
						   pendaftaran_irj.unit_id,
						   simrs_unit.nama AS unit,
						   pendaftaran_irj.dokter_id,
						   simrs_dokter.nama AS dokter');
		$this->db->join($this->table_def_1, 'simrs_pasien.id = pendaftaran_irj.pasien_id');
		$this->db->join('cara_bayar', 'simrs_cara_bayar.id = pendaftaran_irj.cara_bayar_id');
		$this->db->join('unit', 'simrs_unit.id = pendaftaran_irj.unit_id');
		$this->db->join('dokter', 'simrs_dokter.id = pendaftaran_irj.dokter_id');
		if (count($where) > 0) {
			$this->db->where($where);
		}
		if (count($like) > 0) {
			$this->db->or_like($like);
		}
		$this->db->stop_cache();
		
		$data['total_rows'] = $this->db->count_all_results($this->table_def_2);
		
		$this->db->order_by($order, $direction);
		if ($limit == 0) {
            $data['data'] = $this->db->get($this->table_def_2)->result();
        }
        else {
            $data['data'] = $this->db->get($this->table_def_2, $limit, $offset)->result();
        }
		$this->db->flush_cache();
		
		return $data;
	}

    public function create($pendaftaran_rj) {
		$this->db->trans_start();
		
		$data_pasien = $this->_toArrayPasien($pendaftaran_rj);
		$this->db->where('id', $pendaftaran_rj->pasien_id);
		$query = $this->db->get($this->table_def_1);
		$pasien_baru = true;
		if ($query->num_rows() > 0)	{
			$pasien_baru = false;
			$this->db->where('id', $pendaftaran_rj->pasien_id);
			$this->db->update($this->table_def_1, $data_pasien);
		}
		else {
			$pasien_baru = true;
			$this->db->insert($this->table_def_1, $data_pasien);
			$pendaftaran_rj->pasien_id = $this->db->insert_id();
		}
		
		$timezone = "Asia/Pontianak";
		if (function_exists('date_default_timezone_set'))
			date_default_timezone_set($timezone);
		$current_date = date("Y-m-d H:i:s");
		
		$no_antrian = $this->getLastNoAntrian($pendaftaran_rj->tanggal, $pendaftaran_rj->unit_id);
		$no_antrian++;
		$data_pendaftaran = $this->_toArrayPendaftaran($pendaftaran_rj);
		$data_pendaftaran['no_antrian'] = $no_antrian;
		$data_pendaftaran['pelayanan'] = false;
		$data_pendaftaran['batal'] = false;
		$data_pendaftaran['created'] = $current_date;
		$data_pendaftaran['created_by'] = 0;
		$data_pendaftaran['modified'] = null;
		$data_pendaftaran['modified_by'] = null;
		$data_pendaftaran['version'] = 0;
        $this->db->insert($this->table_def_2, $data_pendaftaran);
        $id = $this->db->insert_id();
		
		$this->db->trans_complete();
		if ($this->db->trans_status() === TRUE) {
			return $id;
		}
		else {
			return 0;
		}
    }
    
    public function update($pendaftaran_rj) {
		$this->db->trans_start();
		
		$data_pasien = $this->_toArrayPasien($pendaftaran_rj);
		$this->db->where('id', $pendaftaran_rj->pasien_id);
        $this->db->update($this->table_def_1, $data_pasien);
		
		$data_pendaftaran = $this->_toArrayPendaftaran($pendaftaran_rj);
		$data_pendaftaran['modified'] = now();
		$data_pendaftaran['modified_by'] = 0;
		//$data_pendaftaran['version'] = 0;
        $this->db->where('id', $pendaftaran_rj->pendaftaran_id);
        $this->db->update($this->table_def_2, $data_pendaftaran);
		
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
        $this->db->delete($this->table_def_2); 
    }
	
	public function getLastNoAntrian($tanggal, $unit_id) {
		$this->db->select('no_antrian');
        $this->db->where('tanggal', $tanggal);
		$this->db->where('unit_id', $unit_id);
		$this->db->order_by('no_antrian', 'desc');
        $query = $this->db->get($this->table_def_2);
        if ($query->num_rows() > 0) {
			$row = $query->first_row();
			return $row->no_antrian;
        }
		else {
			return 0;
		}
	}
	
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
	
	private function _toArrayPasien($pendaftaran) {
		$data = array(
            'no_medrec'		=> $pendaftaran->no_medrec,
            'nama'			=> $pendaftaran->nama,
			'jenis_kelamin'	=> $pendaftaran->jenis_kelamin,
			'alamat'		=> $pendaftaran->alamat,
			'tempat_lahir'	=> $pendaftaran->tempat_lahir,
			'tanggal_lahir'	=> $pendaftaran->tanggal_lahir,
			'ortu_suami'	=> $pendaftaran->ortu_suami,
			'agama_id'		=> $pendaftaran->agama_id,
			'pendidikan_id'	=> $pendaftaran->pendidikan_id,
			'pekerjaan_id'	=> $pendaftaran->pekerjaan_id
        );
		return $data;
	}
	
	private function _toArrayPendaftaran($pendaftaran) {
		$data = array(
            'pasien_id'		=> $pendaftaran->pasien_id,
            'tanggal'		=> $pendaftaran->tanggal,
			'no_register'	=> $pendaftaran->no_register,
			'umur_tahun'	=> $pendaftaran->umur_tahun,
			'umur_bulan'	=> $pendaftaran->umur_bulan,
			'umur_hari'		=> $pendaftaran->umur_hari,
			'baru'			=> $pendaftaran->baru,
			'rujukan_id'	=> $pendaftaran->rujukan_id,
			'cara_bayar_id'	=> $pendaftaran->cara_bayar_id,
			'unit_id'		=> $pendaftaran->unit_id,
			'dokter_id'		=> $pendaftaran->dokter_id
        );
		return $data;
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
    
}

?>