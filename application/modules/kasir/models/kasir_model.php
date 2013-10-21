<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kasir_Model extends CI_Model {

	protected $table_def = "kasir";
    
	public function __construct() {
        parent::__construct();
    }
	
	public function getBy($where = array()) {
		$select = $this->table_def.".id, ";
		$select .= $this->table_def.".unit_id, ";
		$select .= $this->table_def.".tanggal, ";
		$select .= $this->table_def.".no_register, ";
		$select .= $this->table_def.".pasien_id, ";
		$select .= $this->table_def_pasien.".no_medrec, ";
		$select .= $this->table_def_pasien.".nama, ";
		$select .= $this->table_def.".jumlah";
		$this->db->select($select);
		$this->db->join($this->table_def_pasien, $this->table_def_pasien.'.id = '.$this->table_def.'.pasien_id', 'left');
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
		$select = $this->table_def.".id, ";
		$select .= $this->table_def.".unit_id, ";
		$select .= $this->table_def.".tanggal, ";
		$select .= $this->table_def.".no_register, ";
		$select .= $this->table_def.".pasien_id, ";
		$select .= $this->table_def_pasien.".no_medrec, ";
		$select .= $this->table_def_pasien.".nama, ";
		$select .= $this->table_def.".jumlah";
		$this->db->select($select);
		$this->db->join($this->table_def_pasien, $this->table_def.'.pasien_id = '.$this->table_def_pasien.'.id', 'left');
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
		
		return $data;
	}

    public function create($pembagian_jasa) {
		$this->db->trans_start();
		
		$data = get_object_vars($pembagian_jasa);
		unset($data['id']);
		unset($data['details']);
		$data['created'] = get_current_date();
		$data['created_by'] = 0;
		$data['version'] = 0;
		$this->db->insert($this->table_def, $data);
        $id = $this->db->insert_id();
		
		foreach ($pembagian_jasa->details as $detail) {
			$data = get_object_vars($detail);
			unset($data['id']);
			unset($data['details']);
			unset($data['mode_edit']);
			$data['pembagian_jasa_id'] = $id;
			$this->db->insert($this->table_def_detail, $data);
			$pembagian_jasa_detail_id = $this->db->insert_id();
			
			foreach ($detail->details as $pjp_detail) {
				$data = get_object_vars($pjp_detail);
				unset($data['id']);
				unset($data['mode_edit']);
				$data['pembagian_jasa_detail_id'] = $pembagian_jasa_detail_id;
				$this->db->insert($this->table_def_penerima_jp_detail, $data);
			}
			
		}
		
		$this->db->trans_complete();
		if ($this->db->trans_status() === TRUE) {
			return $id;
		}
		else {
			return 0;
		}
    }
    
    public function update($pembagian_jasa) {
		$this->db->trans_start();
		
		$data = get_object_vars($pembagian_jasa);
		unset($data['id']);
		unset($data['details']);
        $this->db->where('id', $pembagian_jasa->id);
        $this->db->update($this->table_def, $data);
		
		foreach ($pembagian_jasa->details as $detail) {
			$data = get_object_vars($detail);
			unset($data['id']);
			unset($data['mode_edit']);
            switch ($detail->mode_edit) {
                case 1:
                    $this->db->insert($this->table_def_detail, $data);
                    break;
                case 2:
                    $this->db->where('id', $detail->id);
                    $this->db->update($this->table_def_detail, $data);
                    break;
                case 3:
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
		
		$this->db->where('pembagian_jasa_id', $id);
        $this->db->delete($this->table_def_detail);
		
		$this->db->trans_complete();
		if ($this->db->trans_status() === true) {
			return true;
		}
		else {
			return false;
		}
    }
	
	public function get_no_register() {
		// Ambil no. register lab yang terakhir
		$row = $this->db->select('id, last_number')
						->where('name', 'no_register_kasir')
						->get('code_register')
						->row();
		// Tambah 1 ke no. register
		$no_register = intval($row->last_number) + 1;
		
		// Cari di tabel No. Register Queue apakah no register sudah ada
		// Jika sudah ada tambah 1 ke ke no. register
		$bFound = TRUE;
		while ($bFound) {
			$format_no_register = str_pad($no_register, 6, "0", STR_PAD_LEFT);
			
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
	
	public function incr_no_register() {
		// Ambil no. register lab yang terakhir
		$row = $this->db->select('id, last_number')
						->where('name', 'no_register_kasir')
						->get('code_register')
						->row();
		// Tambah 1 ke no. register
		$no_register = intval($row->last_number) + 1;
		
		$data = array(
			'last_number' => $no_register
		);
		$this->db->where('name', 'no_register_kasir');
		$this->db->update('code_register', $data);
		
		return TRUE;
	}
	
	public function insert_no_register_to_queue($id, $no_register) {
		$data = array(
			'code_register_id'	=> $id,
			'date_save'			=> get_current_date(),
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
        $this->db->where('date_save <', get_current_date());
        $this->db->delete('code_register_queue');
    }
    
}

?>