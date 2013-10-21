<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Poliklinik_Pegawai_Model extends CI_Model {
	
	protected $table_def = "poliklinik_pegawai";
	protected $table_def_poliklinik = "poliklinik";
	protected $table_def_pegawai = "pegawai";
    
	public function __construct() {
        parent::__construct();
    }
	
	public function getBy($aWhere = array(), $aOrWhere = array())  {
		$select = $this->table_def.'.id, ';
		$select = $this->table_def.'.poliklinik_id, ';
		$select = $this->table_def_poliklinik.'.nama AS poliklinik, ';
		$select = $this->table_def.'.pegawai_id, ';
		$select = $this->table_def_pegawai.'.nama AS pegawai, ';
		$this->db->select($select);
		$this->db->join($this->table_def_poliklinik, $this->table_def.'.poliklinik_id = '.$this->table_def_poliklinik.'.id', 'left');
		$this->db->join($this->table_def_pegawai, $this->table_def.'.pegawai_id = '.$this->table_def_pegawai.'.id', 'left');
        if (count($aWhere) > 0) {
			$this->db->where($aWhere);
		}
		if (count($aOrWhere) > 0) {
			$this->db->where($aOrWhere);
		}
        $query = $this->db->get($this->table_def);
        if ($query->num_rows() > 0) {
			return $query->result();
        }
		else {
			return false;
		}
    }
	
	public function getAll($limit = 10, $offset = 0, $orders = array(), $where = array(), $like = array()) {
		$data = array();

		$this->db->start_cache();
		$select = $this->table_def.".id, ";
		$select .= $this->table_def.".poliklinik_id, ";
		$select .= $this->table_def_poliklinik.".nama AS poliklinik, ";
		$select .= $this->table_def.".pegawai_id, ";
		$select .= $this->table_def_pegawai.".nama AS pegawai";
		$this->db->select($select);
		$this->db->join($this->table_def_poliklinik, $this->table_def.'.poliklinik_id = '.$this->table_def_poliklinik.'.id', 'left');
		$this->db->join($this->table_def_pegawai, $this->table_def.'.pegawai_id = '.$this->table_def_pegawai.'.id', 'left');
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
    
}

?>