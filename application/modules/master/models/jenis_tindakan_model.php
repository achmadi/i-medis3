<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Jenis_Tindakan_Model extends CI_Model {
	
	protected $table_def = "jenis_tindakan";
	
	const IDS_KECIL		= 'Kecil';
	const IDS_SEDANG	= 'Sedang';
	const IDS_BESAR		= 'Besar';
	const IDS_KHUSUS	= 'Khusus';
	
	const ID_KECIL	= 1;
	const ID_SEDANG	= 2;
	const ID_BESAR	= 3;
	const ID_KHUSUS	= 4;
	
	protected static $_golongan_operasi = array(
		self::ID_KECIL	=> self::IDS_KECIL, 
		self::ID_SEDANG	=> self::IDS_SEDANG,
		self::ID_BESAR	=> self::IDS_BESAR, 
		self::ID_KHUSUS	=> self::IDS_KHUSUS
	);
    
	public function __construct() {
        parent::__construct();
    }
	
	public function getById($id)  {
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
		$this->db->select('jenis_tindakan.id, jenis_tindakan.nama, golongan_operasi, unit.nama AS unit');
		$this->db->join('unit', 'jenis_tindakan.unit_id = unit.id', 'left');
		if (count($where) > 0) {
			$this->db->where($where);
		}
		if (count($like) > 0) {
			$this->db->like($like);
		}
		$this->db->stop_cache();
		
		$rows = $this->db->get($this->table_def)->result();
		$data['total_rows'] = count($rows);
		
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

    public function create($jenis_tindakan) {
        $data = $this->_toArray($jenis_tindakan);
        $this->db->insert($this->table_def, $data);
        return $this->db->insert_id();
    }
    
    public function update($jenis_tindakan) {
        $data = $this->_toArray($jenis_tindakan);
        $this->db->where('id', $jenis_tindakan->id);
        $this->db->update($this->table_def, $data);
    }
    
    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table_def); 
    }
	
	public function getGolonganOperasi() {
		return self::$_golongan_operasi;
	}
	
	public function getGolonganOperasiDescr($index) {
		return self::$_golongan_operasi[$index];
	}
	
	private function _toArray($jenis_tindakan) {
		$data = array(
            'nama'				=> $unit->nama,
			'golongan_operasi'	=> $unit->golongan_operasi,
			'unit_id'			=> $unit->unit_id
        );
		return $data;
	}
    
}

?>