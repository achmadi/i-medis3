<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kelompok_Pelayanan_Askes_Model extends CI_Model {
	
	protected $table_def = "kelompok_pelayanan_askes";
	protected $table_def_detail = "komponen_tarif_askes";
	
	public function __construct() {
        parent::__construct();
		$this->load->library('Nested_Set');
    }
	
	public function getBy($aWhere = array(), $aOrWhere = array())  {
        if (count($aWhere) > 0) {
			$this->db->where($aWhere);
		}
		if (count($aOrWhere) > 0) {
			$this->db->or_where($aOrWhere);
		}
        $query = $this->db->get($this->table_def);
        if ($query->num_rows() > 0) {
			return $query->row();
        }
		else {
			return false;
		}
    }
	
	public function getAll($limit = 10, $offset = 0, $orders = array(), $where = array(), $or_where = array(), $like = array()) {
		$data = array();
		$this->db->start_cache();
		if (count($where) > 0) {
			$this->db->where($where);
		}
		if (count($or_where) > 0) {
			$this->db->where($or_where);
		}
		if (count($like) > 0) {
			$this->db->or_like($like);
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

    public function create($komponen) {
		$this->db->trans_start();
		
		$data = get_object_vars($komponen);
		unset($data['id']);
		$detail['created'] = get_current_date();
		$detail['created_by'] = 0;
		$data['ordering'] = $this->getNextOrder();
        $this->db->insert($this->table_def, $data);
		$id = $this->db->insert_id();
		$this->reorder();
		
		$data = array(
			'id'			=> 0,
			'kelompok_pelayanan_askes_id'		=> $id,
			'nama'			=> $komponen->nama,
			'tarif'			=> 0,
			'bmhp'			=> 0,
			'sarana'		=> 0,
			'yan'			=> 0,
			'medik'			=> 0,
			'anest'			=> 0,
			'jenis'			=> 'Root',
			'parent_id'		=> 0,
			'old_parent_id'	=> 0,
			'created'		=> get_current_date(),
			'created_by'	=> 0,
			'version'		=> 0
		);
		$this->_save_komponen_tarif_askes($data);
		
		$this->db->trans_complete();
		if ($this->db->trans_status() === TRUE) {
			return $id;
		}
		else {
			return 0;
		}
    }
    
    public function update($unit) {
		$this->db->trans_start();
		
		$data = get_object_vars($unit);
		unset($data['id']);
		unset($data['details']);
        $this->db->where('id', $unit->id);
        $this->db->update($this->table_def, $data);
		
		if (isset($unit->details)) {
			foreach ($unit->details as $detail) {
				$data = array(
					'proporsi'		=> $detail->proporsi,
					'langsung'		=> $detail->langsung,
					'balancing'		=> $detail->balancing,
					'kebersamaan'	=> $detail->kebersamaan
				);
				switch ($detail->mode_edit) {
					case DATA_MODE_ADD:
						$this->db->insert($this->table_def_detail, $data);
						break;
					case DATA_MODE_EDIT:
						$this->db->where('id', $detail->id);
						$this->db->update($this->table_def_detail, $data);
						break;
					case DATA_MODE_DELETE:
						$this->db->where('id', $detail->id);
						$this->db->delete($this->table_def_detail); 
						break;
				}
			}
		}
		
		$this->db->trans_complete();
		if ($this->db->trans_status() === TRUE) {
			return $unit->id;
		}
		else {
			return false;
		}
    }
    
    public function delete($id) {
		$this->db->trans_start();
		
        $this->db->where('id', $id);
        $this->db->delete($this->table_def);
		
		$this->db->trans_complete();
		if ($this->db->trans_status() === TRUE) {
			return $id;
		}
		else {
			return 0;
		}
    }
	
	public function getNextOrder($where = '') {
		$this->db->select_max('ordering');;
		if ($where) {
			$this->db->where($where);
		}
		$query = $this->db->get($this->table_def);
		$row = $query->row();
		
		return (int) $row->ordering + 1;
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
	
	private function _save_komponen_tarif_askes($detail) {
		$this->nested_set->setControlParams($this->table_def_detail, 'id');
		
		if (!$root_id = $this->nested_set->getRootId())
			$root_id = $this->_create_root();
		
		if ($detail['parent_id'] == 0) {
			$detail['parent_id'] = $root_id;
		}
		
		if ($detail['old_parent_id'] != $detail['parent_id'] || $detail['id'] == 0) {
			$this->nested_set->setLocation($detail['parent_id'], 'last-child');
		}
		
		if ($detail['id'] == 0) {
			$detail['created'] = get_current_date();
			$detail['created_by'] = 0;
		}
		else {
			$detail['modified'] = get_current_date();
			$detail['modified_by'] = 0;
		}
		
		if (!$id = $this->nested_set->store($detail)) {
			return false;
		}
		
		if (!$this->nested_set->rebuildPath($id)) {
			return false;
		}
		
		/*
		if (!$this->nested_set->rebuild($this->nested_set->getKey(), $this->nested_set->getLeft(), $this->nested_set->getLevel(), $this->nested_set->getPath($id))) {
			return false;
		}
		*/
		
		return $id;

    }
	
	private function _create_root() {
		$data = array(
			'kelompok_pelayanan_askes_id'	=> 0,
			'nama'							=> 'Komponen Tarif Askes',
			'tarif'							=> 0,
			'bmhp'							=> 0,
			'sarana'						=> 0,
			'yan'							=> 0,
			'medik'							=> 0,
			'anest'							=> 0,
			'jenis'							=> 'Root',
			'path'							=> '',
			'parent_id'						=> 0,
			'level'							=> 0,
			'lft'							=> 1,
			'rgt'							=> 2,
			'created'						=> get_current_date(),
			'created_by'					=> 0,
			'modified'						=> null,
			'modified_by'					=> null,
			'version'						=> 0
		);
		$this->db->insert($this->table_def_detail, $data);
		return $this->db->insert_id();
	}
    
}

?>