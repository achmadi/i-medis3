<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pembagian_Jasa_Model extends CI_Model {

	protected $table_def = "pembagian_jasa";
	protected $table_def_detail = "pembagian_jasa_detail";
	protected $table_def_penerima_jp_detail = "penerima_jp_detail";
	protected $table_def_pasien = "pasien";
	protected $table_def_pemda = "insentif_pemda";
	protected $table_def_manajemen = "insentif_manajemen";
	protected $table_def_jasa_pelayanan = "jasa_pelayanan";
    
	public function __construct() {
        parent::__construct();
    }
	
	public function getBy($where = array()) {
		$select = $this->table_def.".id, ";
		$select .= $this->table_def.".unit, ";
		$select .= $this->table_def.".tanggal, ";
		$select .= $this->table_def.".sd_tanggal, ";
		$select .= $this->table_def.".pasien_id, ";
		$select .= $this->table_def_pasien.".no_medrec, ";
		$select .= $this->table_def_pasien.".nama, ";
		$select .= $this->table_def_pasien.".alamat_jalan, ";
		$select .= $this->table_def.".cara_bayar_id, ";
		$select .= $this->table_def.".poliklinik_id, ";
		$select .= $this->table_def.".kelas_id, ";
		$select .= $this->table_def.".gedung_id, ";
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
		$select .= $this->table_def.".unit, ";
		$select .= $this->table_def.".tanggal, ";
		$select .= $this->table_def.".sd_tanggal, ";
		$select .= $this->table_def.".pasien_id, ";
		$select .= $this->table_def_pasien.".no_medrec, ";
		$select .= $this->table_def_pasien.".nama, ";
		$select .= $this->table_def_pasien.".alamat_jalan, ";
		$select .= $this->table_def.".cara_bayar_id, ";
		$select .= $this->table_def.".poliklinik_id, ";
		$select .= $this->table_def.".kelas_id, ";
		$select .= $this->table_def.".gedung_id, ";
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
		unset($data['nama']);
		unset($data['alamat']);
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
			unset($data['insentif_pemda']);
			unset($data['insentif_manajemen']);
			unset($data['mode_edit']);
			$data['pembagian_jasa_id'] = $id;
			$this->db->insert($this->table_def_detail, $data);
			$pembagian_jasa_detail_id = $this->db->insert_id();
			
			$data_pemda = array(
				'tanggal'	=> $pembagian_jasa->tanggal,
				'jumlah'	=> $detail->insentif_pemda * $detail->quantity
			);
			$this->db->insert($this->table_def_pemda, $data_pemda);
			
			$data_manajemen = array(
				'tanggal'	=> $pembagian_jasa->tanggal,
				'jumlah'	=> $detail->insentif_manajemen * $detail->quantity
			);
			$this->db->insert($this->table_def_manajemen, $data_manajemen);
			
			foreach ($detail->details as $pjp_detail) {
				$data = get_object_vars($pjp_detail);
				unset($data['id']);
				unset($data['mode_edit']);
				$data['pembagian_jasa_detail_id'] = $pembagian_jasa_detail_id;
				$this->db->insert($this->table_def_penerima_jp_detail, $data);
				
				if (intval($pjp_detail->pegawai_id) > 0) {
					$langsung = array();
					$langsung['tanggal'] = $pjp_detail->tanggal;
					$langsung['pegawai_id'] = $pjp_detail->pegawai_id;
					$langsung['jasa_langsung'] = $pjp_detail->langsung;
					$this->db->insert($this->table_def_jasa_pelayanan, $langsung);
				}
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
		unset($data['nama']);
		unset($data['alamat']);
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
    
}

?>