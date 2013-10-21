<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Poliklinik_Model extends CI_Model {
	
	protected $table_def = "poliklinik";
	protected $table_child = "poliklinik_pegawai";
	
	public function __construct() {
        parent::__construct();
    }
	
	public function getBy($aWhere = array(), $aOrWhere = array())  {
        if (count($aWhere) > 0) {
			$this->db->where($aWhere);
		}
		if (count($aOrWhere) > 0) {
			$this->db->where($aOrWhere);
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

    public function create($poliklinik) {
		$this->db->trans_start();
		
		$data = get_object_vars($poliklinik);
		unset($data['id']);
		unset($data['dokters']);
        $this->db->insert($this->table_def, $data);
        $id = $this->db->insert_id();
		
		foreach ($poliklinik->dokters as $dokter) {
			$data = get_object_vars($dokter);
			unset($data['id']);
			unset($data['data_mode']);
			$data['poliklinik_id'] = $id;
			$this->db->insert($this->table_child, $data);
		}
		
		$this->db->trans_complete();
		if ($this->db->trans_status() === TRUE) {
			return $id;
		}
		else {
			return 0;
		}
    }
    
    public function update($poliklinik) {
		$this->db->trans_start();
		
        $data = get_object_vars($poliklinik);
		unset($data['id']);
		unset($data['dokters']);
        $this->db->where('id', $poliklinik->id);
        $this->db->update($this->table_def, $data);
		
		foreach ($poliklinik->dokters as $dokter) {
			$data = get_object_vars($dokter);
			unset($data['id']);
			unset($data['data_mode']);
            switch ($dokter->data_mode) {
                case DATA_MODE_ADD:
                    $this->db->insert($this->table_child, $data);
                    break;
                case DATA_MODE_EDIT:
                    $this->db->where('id', $dokter->id);
                    $this->db->update($this->table_child, $data);
                    break;
                case DATA_MODE_DELETE:
                    $this->db->where('id', $dokter->id);
                    $this->db->delete($this->table_child); 
                    break;
            }
        }
		
		$this->db->trans_complete();
		if ($this->db->trans_status() === true) {
			return true;
		}
		else {
			return false;
		}
    }
    
    public function delete($id) {
		$this->db->trans_start();
		
        $this->db->where('id', $id);
        $this->db->delete($this->table_def);
		
		$this->db->where('poliklinik_id', $id);
        $this->db->delete($this->table_child);
		
		$this->db->trans_complete();
		if ($this->db->trans_status() === true) {
			return true;
		}
		else {
			return false;
		}
    }
    
}

?>