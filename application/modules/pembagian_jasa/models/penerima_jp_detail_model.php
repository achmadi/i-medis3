<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Penerima_JP_Detail_Model extends CI_Model {

	protected $table_def = "penerima_jp_detail";
	protected $table_def_pegawai = "pegawai";
	protected $table_def_kelompok = "kelompok_pegawai";
	protected $table_def_unit = "unit";
	protected $table_def_gedung = "gedung";
    
	public function __construct() {
        parent::__construct();
    }
	
	public function getBy($where = array()) {
		$select = $this->table_def.".id, ";
		$select .= $this->table_def.".pembagian_jasa_detail_id, ";
		$select .= $this->table_def.".tanggal, ";
		$select .= $this->table_def.".pegawai_id, ";
		$select .= $this->table_def_pegawai.".no_rekening, ";
		$select .= $this->table_def_pegawai.".nama, ";
		$select .= $this->table_def.".proporsi, ";
		$select .= $this->table_def.".langsung, ";
		$select .= $this->table_def.".kebersamaan";
		$this->db->select($select);
		$this->db->join($this->table_def_pegawai, $this->table_def.".pegawai_id = ".$this->table_def_pegawai.".id");
		if (count($where) > 0) {
			$this->db->where($where);
		}
        $this->db->where($this->table_def.'.id', $id);
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
		$select .= $this->table_def.".pembagian_jasa_detail_id, ";
		$select .= $this->table_def.".tanggal, ";
		$select .= $this->table_def.".pegawai_id, ";
		$select .= $this->table_def_pegawai.".no_rekening, ";
		$select .= $this->table_def_pegawai.".nama, ";
		$select .= $this->table_def.".proporsi, ";
		$select .= $this->table_def.".langsung, ";
		$select .= $this->table_def.".kebersamaan";
		$this->db->select($select);
		$this->db->join($this->table_def_pegawai, $this->table_def.".pegawai_id = ".$this->table_def_pegawai.".id", "left");
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
	
	public function get_tahun() {
		$this->db->select('YEAR(tanggal) AS tahun', FALSE);
		$this->db->group_by("YEAR(tanggal)");
		$data = $this->db->get($this->table_def)->result();
		return $data;
	}
	/*
	SELECT
    SUM(langsung * quantity) AS `langsung`
FROM
    `i-medis3`.`penerima_jp_detail`
    LEFT JOIN `i-medis3`.`pegawai` 
        ON (`penerima_jp_detail`.`pegawai_id` = `pegawai`.`id`)
    LEFT JOIN `i-medis3`.`kelompok_pegawai` 
        ON (`pegawai`.`kelompok_id` = `kelompok_pegawai`.`id`)
    LEFT JOIN `i-medis3`.`unit` 
        ON (`pegawai`.`unit_id` = `unit`.`id`)
    LEFT JOIN `i-medis3`.`gedung` 
        ON (`pegawai`.`gedung_id` = `gedung`.`id`)
WHERE (YEAR(tanggal) =2013
    AND MONTH(tanggal) =9
    AND IFNULL(gedung.nama, IFNULL(unit.nama, kelompok_pegawai.nama)) ='Dokter Umum')
GROUP BY IFNULL(gedung.nama, IFNULL(unit.nama, kelompok_pegawai.nama));
	*/
	public function get_jumlah_langsung($bulan, $tahun, $ruang) {
		$this->db->select_sum($this->table_def.'.langsung * '.$this->table_def.'.quantity', 'langsung', false);
		$this->db->join($this->table_def_pegawai, $this->table_def.".pegawai_id = ".$this->table_def_pegawai.".id", "left");
		$this->db->join($this->table_def_kelompok, $this->table_def_pegawai.".kelompok_id = ".$this->table_def_kelompok.".id", "left");
		$this->db->join($this->table_def_unit, $this->table_def_pegawai.".unit_id = ".$this->table_def_unit.".id", "left");
		$this->db->join($this->table_def_gedung, $this->table_def_pegawai.".gedung_id = ".$this->table_def_gedung.".id", "left");
		$this->db->where('YEAR('.$this->table_def.'.tanggal)', $tahun, FALSE);
		$this->db->where('MONTH('.$this->table_def.'.tanggal)', $bulan, FALSE);
		//$this->db->where('IFNULL('.$this->table_def_gedung.'.nama, IFNULL('.$this->table_def_unit.'.nama, '.$this->table_def_kelompok.'.nama)) =', $ruang);
		$data = $this->db->get($this->table_def)->row();
		if ($data)
			return $data->langsung;
		else
			return 0;
	}
	
	public function get_total($bulan, $tahun) {
		$this->db->select_sum($this->table_def.'.langsung * '.$this->table_def.'.quantity', 'langsung', false);
		$this->db->where('YEAR('.$this->table_def.'.tanggal)', $tahun, FALSE);
		$this->db->where('MONTH('.$this->table_def.'.tanggal)', $bulan, FALSE);
		$data = $this->db->get($this->table_def)->row();
		if ($data)
			return $data->langsung;
		else
			return 0;
	}
	
	public function get_jumlah_kebersamaan($bulan, $tahun) {
		$this->db->select_sum('kebersamaan * quantity', 'kebersamaan');
		$this->db->where('MONTH(tanggal)', $bulan, FALSE);
		$this->db->where('YEAR(tanggal)', $tahun, FALSE);
		$data = $this->db->get($this->table_def)->row();
		if ($data)
			return $data->kebersamaan;
		else
			return 0;
	}
    
}

?>