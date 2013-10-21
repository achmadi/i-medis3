<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rujukan_Model extends CI_Model {

	protected $table_def = "rujukan";
	
	/*
	Rujukan
		Rujukan Medis
			Rumah Sakit
			Bidan
			Diterima Dari Puskesmas
			Diterima Dari Fasilitas Kesehatan Lain
			Dikembalikan ke Puskesmas
			Dikembalikan ke Fasilitasn Kesehatan Lain
			Dikembalikan Ke Rumah Sakit Asal
		Rujukan Non Medis
			Polisi
	Non Rujukan
		Datang Sendiri
	Dirujuk
		Pasien Rujukan
		Diterima Kembali
	*/
	
	const IDS_RUJUKAN_MEDIS		= 'Rujukan Medis';
	const IDS_RUJUKAN_NON_MEDIS	= 'Rujukan Non Medis';
	const IDS_NON_RUJUKAN		= 'Non Rujukan';
	const IDS_DIRUJUK			= 'Dirujuk';
	
	const ID_RUJUKAN_MEDIS		= 1;
	const ID_RUJUKAN_NON_MEDIS	= 2;
	const ID_NON_RUJUKAN		= 3;
	const ID_DIRUJUK			= 4;
	
	protected static $_jenis_rujukan = array(
		self::ID_RUJUKAN_MEDIS		=> self::IDS_RUJUKAN_MEDIS, 
		self::ID_RUJUKAN_NON_MEDIS	=> self::IDS_RUJUKAN_NON_MEDIS, 
		self::ID_NON_RUJUKAN		=> self::IDS_NON_RUJUKAN,
		self::ID_DIRUJUK			=> self::IDS_DIRUJUK
	);
    
	public function __construct() {
        parent::__construct();
    }
	
	public function getById($id) {
        $this->db->where('id', $id);
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
		if (count($where) > 0) {
			$this->db->where($where);
		}
		if (count($like) > 0) {
			$this->db->like($like);
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

    public function create($rujukan) {
        $data = $this->_toArray($rujukan);
        $this->db->insert($this->table_def, $data);
        return $this->db->insert_id();
    }
    
    public function update($rujukan) {
        $data = $this->_toArray($rujukan);
        $this->db->where('id', $rujukan->id);
        $this->db->update($this->table_def, $data);
    }
    
    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table_def); 
    }
	
	public function getJenisRujukan() {
		return self::$_jenis_rujukan;
	}
	
	public function getJenisRujukanDescr($index) {
		return self::$_jenis_rujukan[$index];
	}
	
	private function _toArray($rujukan) {
		$data = array(
            'nama'	=> $rujukan->nama,
			'jenis'	=> $rujukan->jenis
        );
		return $data;
	}
    
}

?>