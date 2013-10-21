<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Gedung_Model extends CI_Model {

	protected $table_def = "gedung";
	protected $table_def_kelas = "kelas";
	protected $table_def_gedung_kelas = "gedung_kelas";
    
	public function __construct() {
        parent::__construct();
    }
	
	public function getBy($where = array()) {
        $this->db->select($this->table_def.".id");
		$this->db->select($this->table_def.".nama");
		$this->db->select($this->table_def.".bagian");
		if (count($where) > 0) {
			$this->db->where($where);
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
		$this->db->select($this->table_def.".id");
		$this->db->select($this->table_def.".nama");
		$this->db->select($this->table_def.".bagian");
		if (count($where) > 0) {
			$this->db->where($where);
		}
		if (count($like) > 0) {
			$this->db->or_like($like);
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
		
		for ($i = 0; $i < count($data['data']); $i++) {
			$data['data'][$i]->kelas = $this->_get_kelas($data['data'][$i]->id);
		}

		return $data;
	}
	
	private function _get_kelas($id) {
		$this->db->select($this->table_def_kelas.".nama AS kelas");
		$this->db->join($this->table_def_kelas, $this->table_def_gedung_kelas.".kelas_id = ".$this->table_def_kelas.".id", "left");
		$this->db->where($this->table_def_gedung_kelas.'.gedung_id', $id);
        $query = $this->db->get($this->table_def_gedung_kelas);
		if ($query->num_rows() > 0) {
			$data = $query->result();
			$kelas = "";
			foreach ($data as $row) {
				$kelas .= (empty($kelas) ? "" : ", ").$row->kelas;
			}
			return $kelas;
        }
		else {
			return "";
		}
	}

    public function create($gedung) {
		$this->db->trans_start();
		
		$data = get_object_vars($gedung);
		unset($data['id']);
		unset($data['kelass']);
        $this->db->insert($this->table_def, $data);
		$id = $this->db->insert_id();
		
		foreach ($gedung->kelass as $kelas) {
			$data_kelas = get_object_vars($kelas);
			unset($data_kelas['id']);
			unset($data_kelas['data_mode']);
			$data_kelas['gedung_id'] = $id;
			$this->db->insert($this->table_def_gedung_kelas, $data_kelas);
		}
		
		$this->db->trans_complete();
		if ($this->db->trans_status() === TRUE) {
			return $id;
		}
		else {
			return 0;
		}
    }
    
    public function update($gedung) {
		$this->db->trans_start();
		
		$data = get_object_vars($gedung);
		unset($data['id']);
		unset($data['kelass']);
        $this->db->where('id', $gedung->id);
        $this->db->update($this->table_def, $data);
		
		foreach ($gedung->kelass as $kelas) {
			$data_kelas = get_object_vars($kelas);
			unset($data_kelas['id']);
			unset($data_kelas['kelas']);
			unset($data_kelas['data_mode']);
            switch ($kelas->data_mode) {
                case DATA_MODE_ADD:
                    $this->db->insert($this->table_def_gedung_kelas, $data_kelas);
                    break;
                case DATA_MODE_EDIT:
                    $this->db->where('id', $kelas->id);
                    $this->db->update($this->table_def_gedung_kelas, $data_kelas);
                    break;
                case DATA_MODE_DELETE:
                    $this->db->where('id', $kelas->id);
                    $this->db->delete($this->table_def_gedung_kelas); 
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
		
		$this->db->where('gedung_id', $id);
        $this->db->delete($this->table_def_gedung_kelas);
		
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