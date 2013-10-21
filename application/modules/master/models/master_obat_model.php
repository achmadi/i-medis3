<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Class master_obat_model
 *
 * @author R.Firmansyah <black.cappuccino@yahoo.com>
 */
class Master_Obat_Model extends CI_Model {
    protected $table_def = "master_obat";
    protected $join_table1 = "jenis_obat_ref";
    protected $join_table2 = "sub_jenis_obat_ref";
    protected $join_table3 = "golongan_obat_ref";
    protected $join_table4 = "satuan_kecil_ref";
    protected $join_table5 = "satuan_besar_ref";
    
    public function __construct() {
        parent::__construct();
    }
	
    public function getById($id) {
        $this->db->where('master_obat_id', $id);
        $query = $this->db->get($this->table_def);
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
	
    public function getAll($limit = 10, $offset = 0, $orders = array(), $where = array(), $like = array()) {
        $data = array();
        $this->db->start_cache();
        
        $this->db->select('
                master_obat.master_obat_id,
                master_obat.master_obat_kode,
                master_obat.master_obat_nama,
                jenis_obat_ref.jenis_obat_ref_nama,
                sub_jenis_obat_ref.sub_jenis_obat_ref_nama,
                golongan_obat_ref.golongan_obat_ref_nama,
                satuan_kecil_ref.satuan_kecil_ref_nama,
                satuan_besar_ref.satuan_besar_ref_nama
                ');
        $this->db->join($this->join_table1, 'jenis_obat_ref.jenis_obat_ref_id = master_obat.jenis_obat_ref_id','left');
        $this->db->join($this->join_table2, 'sub_jenis_obat_ref.sub_jenis_obat_ref_id = master_obat.sub_jenis_obat_ref_id','left');
        $this->db->join($this->join_table3, 'golongan_obat_ref.golongan_obat_ref_id = master_obat.golongan_obat_ref_id','left');
        $this->db->join($this->join_table4, 'satuan_kecil_ref.satuan_kecil_ref_id = master_obat.satuan_kecil_ref_id','left');
        $this->db->join($this->join_table5, 'satuan_besar_ref.satuan_besar_ref_id = master_obat.satuan_besar_ref_id','left');
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

    public function create($master_obat) {
	$data = $this->_toArray($master_obat);
        $this->db->insert($this->table_def, $data);
        return $this->db->insert_id();
    }
    
    public function update($master_obat) {
	$data = $this->_toArray($master_obat);
        $this->db->where('master_obat_id', $master_obat->master_obat_id);
        $this->db->update($this->table_def, $data);
    }
    
    public function delete($id) {
        $this->db->where('master_obat_id', $id);
        $this->db->delete($this->table_def); 
    }
    
    private function _toArray($master_obat) {
        $data = array(
            'master_obat_kode' => $master_obat->master_obat_kode,
            'master_obat_nama' => $master_obat->master_obat_nama,
            'jenis_obat_ref_id' => $master_obat->jenis_obat_ref_id,
            'sub_jenis_obat_ref_id' => $master_obat->sub_jenis_obat_ref_id,
            'golongan_obat_ref_id' => $master_obat->golongan_obat_ref_id,
            'satuan_kecil_ref_id' => $master_obat->satuan_kecil_ref_id,
            'satuan_besar_ref_id' => $master_obat->satuan_besar_ref_id
        );
        return $data;
    }
    
    public function getSatuanLabel($id) {
        $this->db->select('
                master_obat.satuan_besar_ref_id,
                master_obat.satuan_kecil_ref_id,
                satuan_besar_ref.satuan_besar_ref_nama,
                satuan_kecil_ref.satuan_kecil_ref_nama
                ');
        $this->db->join($this->join_table5, 'master_obat.satuan_besar_ref_id = satuan_besar_ref.satuan_besar_ref_id','left');
        $this->db->join($this->join_table4, 'master_obat.satuan_kecil_ref_id = satuan_kecil_ref.satuan_kecil_ref_id','left');
        $this->db->where('master_obat.master_obat_id', $id); 
        $result = $this->db->get($this->table_def)->result();
        return $result;
    }
}

?>
