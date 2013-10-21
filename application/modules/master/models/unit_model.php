<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Unit_Model extends CI_Model {
	
	protected $table_def = "unit";
	protected $table_def_detail = "unit_detail";
	protected $table_def_tarif_pelayanan = "tarif_pelayanan";
	
	public function __construct() {
        parent::__construct();
		$this->load->library('Nested_Set');
    }
	
	public function getBy($aWhere = array())  {
        if (count($aWhere) > 0) {
			$this->db->where($aWhere);
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

    public function create($unit) {
		$this->db->trans_start();
		
		$data = get_object_vars($unit);
		unset($data['id']);
		$data['ordering'] = $this->getNextOrder();
        $this->db->insert($this->table_def, $data);
		$id = $this->db->insert_id();
		$this->reorder();
		
		$data = array(
			'id'				=> 0,
			'unit_id'			=> $id,
			'kelompok_id'		=> 0,
			'proporsi'			=> 0,
			'langsung'			=> 0,
			'kebersamaan'		=> 0,
			'jenis'				=> 'Root',
			'parent_id'			=> 0,
			'old_parent_id'		=> 0,
			'created'			=> get_current_date(),
			'created_by'		=> 0,
			'version'			=> 0
		);
		$unit_detail_id = $this->_save_unit_detail($data);
		
		$data = array(
			'id'				=> 0,
			'unit_id'			=> $id,
			'nama'				=> $unit->nama,
			'jasa_sarana'		=> 0,
			'jasa_pelayanan'	=> 0,
			'jenis'				=> 'Root',
			'parent_id'			=> 0,
			'old_parent_id'		=> 0,
			'created'			=> get_current_date(),
			'created_by'		=> 0,
			'version'			=> 0
		);
		$tarif_pelayanan_id = $this->_save_tarif_pelayanan($data);
		
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
	
	private function _save_unit_detail($detail) {
		$this->nested_set->setControlParams($this->table_def_detail, 'id');
		
		if (!$root_id = $this->nested_set->getRootId())
			$root_id = $this->_create_root_unit_detail();
		
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
	
	private function _save_tarif_pelayanan($tarif) {
		$this->nested_set->setControlParams($this->table_def_tarif_pelayanan, 'id');
		
		if (!$root_id = $this->nested_set->getRootId())
			$root_id = $this->_create_root_tarif_pelayanan();
		
		if ($tarif['parent_id'] == 0) {
			$tarif['parent_id'] = $root_id;
		}
		
		if ($tarif['old_parent_id'] != $tarif['parent_id'] || $tarif['id'] == 0) {
			$this->nested_set->setLocation($tarif['parent_id'], 'last-child');
		}
		
		if ($tarif['id'] == 0) {
			$tarif['created'] = get_current_date();
			$tarif['created_by'] = 0;
		}
		else {
			$tarif['modified'] = get_current_date();
			$tarif['modified_by'] = 0;
		}
		
		if (!$id = $this->nested_set->store($tarif)) {
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
	
	private function _create_root_unit_detail() {
		$data = array(
			'unit_id'			=> 0,
			'nama'				=> 'Komponen Jasa Pelayanan',
			'tarif_langsung'	=> false,
			'proporsi'			=> 0,
			'langsung'			=> 0,
			'kebersamaan'		=> 0,
			'jenis'				=> 'Root',
			'path'				=> '',
			'parent_id'			=> 0,
			'level'				=> 0,
			'lft'				=> 1,
			'rgt'				=> 2,
			'created'			=> get_current_date(),
			'created_by'		=> 0,
			'modified'			=> null,
			'modified_by'		=> null,
			'version'			=> 0
		);
		$this->db->insert($this->table_def_detail, $data);
		return $this->db->insert_id();
	}
	
	private function _create_root_tarif_pelayanan() {
		$data = array(
			'unit_id'			=> 0,
			'nama'				=> 'Tarif Pelayanan',
			'jasa_sarana'		=> 0,
			'jasa_pelayanan'	=> 0,
			'jenis'				=> 'Root',
			'path'				=> '',
			'parent_id'			=> 0,
			'level'				=> 0,
			'lft'				=> 1,
			'rgt'				=> 2,
			'created'			=> get_current_date(),
			'created_by'		=> 0,
			'modified'			=> null,
			'modified_by'		=> null,
			'version'			=> 0
		);
		$this->db->insert($this->table_def_tarif_pelayanan, $data);
		return $this->db->insert_id();
	}
    
}

?>