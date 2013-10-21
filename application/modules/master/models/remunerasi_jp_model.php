<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Remunerasi_JP_Model extends CI_Model {

	protected $table_def = "remunerasi_jp";
	protected $table_def_detail = "remunerasi_jp_detail";
    
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
	
	public function getByJenisPelayananId($id) {
        $this->db->where('jenis_pelayanan_id', $id);
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

    public function create($remunerasi) {
		$this->db->trans_start();
		
		$data = get_object_vars($remunerasi);
		unset($data['id']);
		unset($data['details']);
		$data['ordering'] = $this->getNextOrder();
        $this->db->insert($this->table_def, $data);
		$id = $this->db->insert_id();
		$this->reorder();
		
		foreach ($remunerasi->details as $detail) {
			$data_detail = get_object_vars($detail);
			unset($data_detail['id']);
			unset($data_detail['mode_edit']);
			$data_detail['remunerasi_jp_id'] = $id;
			$this->db->insert($this->table_def_detail, $data_detail);
		}
		
		$this->db->trans_complete();
		if ($this->db->trans_status() === TRUE) {
			return $id;
		}
		else {
			return 0;
		}
    }
    
    public function update($remunerasi) {
		$this->db->trans_start();
		
        $data = get_object_vars($remunerasi);
		unset($data['id']);
        $this->db->where('id', $remunerasi->id);
        $this->db->update($this->table_def, $data);
		
		foreach ($remunerasi->details as $detail) {
			$data_detail = get_object_vars($detail);
			unset($data_detail['id']);
			unset($data_detail['mode_edit']);
            switch ($detail->mode_edit) {
                case DATA_MODE_ADD:
					$data_detail['remunerasi_jp_id'] = $remunerasi->id;
                    $this->db->insert($this->table_def_detail, $data_detail);
                    break;
                case DATA_MODE_EDIT:
                    $this->db->where('id', $detail->id);
                    $this->db->update($this->table_def_detail, $data_detail);
                    break;
                case DATA_MODE_DELETE:
                    $this->db->where('id', $detail->id);
                    $this->db->delete($this->table_def_detail); 
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
		
		$this->db->where('remunerasi_jp_id', $id);
        $this->db->delete($this->table_def_detail);
		
		$this->db->trans_complete();
		if ($this->db->trans_status() === true) {
			return true;
		}
		else {
			return false;
		}
    }
	
	public function getNextOrder($where = '') {
		$this->db->select_max('ordering');;
		if ($where) {
			$this->db->where($where);
		}
		$query = $this->db->get($this->table_def);
		$row = $query->row();
		
		if (!empty($row))
			$ordering = $row->ordering + 1;
		else
			$ordering = 1;
		
		return (int) $ordering;
	}
	
	public function reorder($where = '') {

		$this->db->select('id, ordering');
		$this->db->where('ordering >=', 0);
		if ($where) {
			$this->db->where($where);
		}
		$this->db->order_by('ordering', 'ASC');
		$query = $this->db->get($this->table_def);
		
		if ($query->num_rows() > 0) {
			$rows = $query->result();
			foreach ($rows as $i => $row) {
				if ($row->ordering >= 0) {
					if ($row->ordering != $i + 1) {
						// Update the row ordering field.
						$this->db->set('ordering', $i + 1);
						$this->db->where('id', $row->id);
						$this->db->update($this->table_def);
					}
				}
			}
        }
		return true;
	}

}

?>