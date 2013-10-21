<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Jasa_Pelayanan_Model extends CI_Model {

	protected $table_def = "jasa_pelayanan";
	protected $table_def_pegawai = "pegawai";
	protected $table_def_golongan = "golongan_pegawai";
	protected $table_def_kelompok = "kelompok_pegawai";
	protected $table_def_unit = "unit";
	protected $table_def_gedung = "gedung";
	
	/*
	SELECT
		`jasa_pelayanan`.`tanggal`
		, `pegawai`.`no_rekening`
		, `pegawai`.`nama`
		, IFNULL(gedung.nama, IFNULL(unit.nama, kelompok_pegawai.nama)) AS `ruang`
		, `jasa_pelayanan`.`jasa_tak_langsung`
		, `pegawai`.`indeks_basic` AS `indeks`
		, `jasa_pelayanan`.`jasa_tak_langsung` AS `jumlah`
	FROM
		`i-medis3`.`jasa_pelayanan`
		LEFT JOIN `i-medis3`.`pegawai` 
			ON (`jasa_pelayanan`.`pegawai_id` = `pegawai`.`id`)
		LEFT JOIN `i-medis3`.`kelompok_pegawai` 
			ON (`pegawai`.`kelompok_id` = `kelompok_pegawai`.`id`)
		LEFT JOIN `i-medis3`.`unit` 
			ON (`pegawai`.`unit_id` = `unit`.`id`)
		LEFT JOIN `i-medis3`.`gedung` 
			ON (`pegawai`.`gedung_id` = `gedung`.`id`)
	ORDER BY `ruang` ASC, `pegawai`.`nama` ASC;
	*/
	
	public function __construct() {
        parent::__construct();
    }
	
	public function getBy($where = array()) {
		$this->db->select($this->table_def.".id");
		$this->db->select($this->table_def.".tanggal");
		$this->db->select($this->table_def.".pegawai_id");
		$this->db->select($this->table_def_pegawai.".nip");
		$this->db->select($this->table_def_pegawai.".nama");
		$this->db->select($this->table_def_pegawai.".no_rekening");
		$this->db->select($this->table_def_pegawai.".npwp");
		$this->db->select($this->table_def_pegawai.".jabatan");
		$this->db->select($this->table_def_pegawai.".golongan_id");
		$this->db->select($this->table_def_golongan.".nama AS golongan");
		$this->db->select($this->table_def_golongan.".golongan AS level_golongan");
		$this->db->select($this->table_def_golongan.".pajak");
		$this->db->select($this->table_def_pegawai.".kelompok_id");
		$this->db->select($this->table_def_kelompok.".nama AS kelompok");
		$this->db->select($this->table_def_pegawai.".unit_id");
		$this->db->select($this->table_def_unit.".nama AS unit");
		$this->db->select($this->table_def_pegawai.".gedung_id");
		$this->db->select($this->table_def_gedung.".nama AS gedung");
		$this->db->select('IFNULL(gedung.nama, IFNULL(unit.nama, kelompok_pegawai.nama)) AS ruang', false);
		$this->db->select($this->table_def_pegawai.".indeks_basic + ".$this->table_def_pegawai.".indeks_posisi + ".$this->table_def_pegawai.".indeks_emergency + ".$this->table_def_pegawai.".indeks_resiko + ".$this->table_def_pegawai.".indeks_pendidikan + ".$this->table_def_pegawai.".indeks_masa_kerja AS indeks");
		$this->db->select($this->table_def.".jasa_tak_langsung AS jumlah");
		$this->db->join($this->table_def_pegawai, $this->table_def.'.pegawai_id = '.$this->table_def_pegawai.'.id', 'left');
		$this->db->join($this->table_def_golongan, $this->table_def_pegawai.'.golongan_id = '.$this->table_def_golongan.'.id', 'left');
		$this->db->join($this->table_def_kelompok, $this->table_def_pegawai.'.kelompok_id = '.$this->table_def_kelompok.'.id', 'left');
		$this->db->join($this->table_def_unit, $this->table_def_pegawai.'.unit_id = '.$this->table_def_unit.'.id', 'left');
		$this->db->join($this->table_def_gedung, $this->table_def_pegawai.'.gedung_id = '.$this->table_def_gedung.'.id', 'left');
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
		$this->db->select($this->table_def.".tanggal");
		$this->db->select($this->table_def.".pegawai_id");
		$this->db->select($this->table_def_pegawai.".nip");
		$this->db->select($this->table_def_pegawai.".nama");
		$this->db->select($this->table_def_pegawai.".no_rekening");
		$this->db->select($this->table_def_pegawai.".npwp");
		$this->db->select($this->table_def_pegawai.".jabatan");
		$this->db->select($this->table_def_pegawai.".golongan_id");
		$this->db->select($this->table_def_golongan.".nama AS golongan");
		$this->db->select($this->table_def_golongan.".golongan AS level_golongan");
		$this->db->select($this->table_def_golongan.".pajak");
		$this->db->select($this->table_def_pegawai.".kelompok_id");
		$this->db->select($this->table_def_kelompok.".nama AS kelompok");
		$this->db->select($this->table_def_pegawai.".unit_id");
		$this->db->select($this->table_def_unit.".nama AS unit");
		$this->db->select($this->table_def_pegawai.".gedung_id");
		$this->db->select($this->table_def_gedung.".nama AS gedung");
		$this->db->select('IFNULL(gedung.nama, IFNULL(unit.nama, kelompok_pegawai.nama)) AS ruang', false);
		$this->db->select($this->table_def_pegawai.".indeks_basic + ".$this->table_def_pegawai.".indeks_posisi + ".$this->table_def_pegawai.".indeks_emergency + ".$this->table_def_pegawai.".indeks_resiko + ".$this->table_def_pegawai.".indeks_pendidikan + ".$this->table_def_pegawai.".indeks_masa_kerja AS indeks");
		$this->db->select($this->table_def.".jasa_tak_langsung AS jumlah");
		$this->db->join($this->table_def_pegawai, $this->table_def.'.pegawai_id = '.$this->table_def_pegawai.'.id', 'left');
		$this->db->join($this->table_def_golongan, $this->table_def_pegawai.'.golongan_id = '.$this->table_def_golongan.'.id', 'left');
		$this->db->join($this->table_def_kelompok, $this->table_def_pegawai.'.kelompok_id = '.$this->table_def_kelompok.'.id', 'left');
		$this->db->join($this->table_def_unit, $this->table_def_pegawai.'.unit_id = '.$this->table_def_unit.'.id', 'left');
		$this->db->join($this->table_def_gedung, $this->table_def_pegawai.'.gedung_id = '.$this->table_def_gedung.'.id', 'left');
		if (count($where) > 0) {
			$this->db->where($where, NULL, FALSE);
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
	
	public function get_total($bulan, $tahun) {
		$this->db->select_sum('jasa_tak_langsung', 'total');
		$this->db->where('MONTH(tanggal)', $bulan, FALSE);
		$this->db->where('YEAR(tanggal)', $tahun, FALSE);
		$data = $this->db->get($this->table_def)->row();
		if ($data)
			return $data->total;
		else
			return 0;
	}

    public function create($jasa_pelayanan) {
		$this->db->trans_start();
		
		$data = get_object_vars($jasa_pelayanan);
		unset($data['id']);
		//$data['created'] = get_current_date();
		//$data['created_by'] = 0;
		//$data['version'] = 0;
		$this->db->insert($this->table_def, $data);
        $id = $this->db->insert_id();
		
		$this->db->trans_complete();
		if ($this->db->trans_status() === TRUE) {
			return $id;
		}
		else {
			return 0;
		}
    }
    
    public function update($jasa_pelayanan) {
		$this->db->trans_start();
		
		$data = get_object_vars($jasa_pelayanan);
		unset($data['id']);
		//$data['modified'] = get_current_date();
		//$data['modifiedd_by'] = 0;
		//$data['version'] = $jasa_pelayanan.version + 1;
        $this->db->where('id', $jasa_pelayanan->id);
        $this->db->update($this->table_def, $data);
		
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