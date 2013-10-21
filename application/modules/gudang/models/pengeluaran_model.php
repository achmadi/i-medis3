<?php

/**
 * Description of Class pengeluaran_model
 *
 * @author R.Firmansyah <black.cappuccino@yahoo.com>
 */
class Pengeluaran_Model extends CI_Model {
    protected $table_def = "gud_pengeluaran";
    protected $join_table1 = "master_obat";
    protected $join_table2 = "apotik_ref";
    
    public function __construct() {
        parent::__construct();
    }
	
    public function getById($id) {
        $this->db->where('gud_pengeluaran_id', $id);
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
                gud_pengeluaran.gud_pengeluaran_id,
                master_obat.master_obat_nama,
                gud_pengeluaran.qty_satuan_besar,
                gud_pengeluaran.qty_satuan_kecil,
                apotik_ref.apotik_ref_nama
                ');
        $this->db->join($this->join_table1, 'master_obat.master_obat_id = gud_pengeluaran.master_obat_id','left');
        $this->db->join($this->join_table2, 'apotik_ref.apotik_ref_id = gud_pengeluaran.apotik_ref_id','left');
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

    public function create($pengeluaran) {
        $data = $this->_toArray($pengeluaran);
        $this->db->insert($this->table_def, $data);
        return $this->db->insert_id();
    }
    
    public function update($pengeluaran) {
	$data = $this->_toArray($pengeluaran);
        $this->db->where('gud_pengeluaran_id', $pengeluaran->gud_pengeluaran_id);
        $this->db->update($this->table_def, $data);
    }
    
    public function delete($id) {
        $this->db->where('gud_pengeluaran_id', $id);
        $this->db->delete($this->table_def); 
    }
    
    private function _toArray($pengeluaran) {
        $data = array(
            'apotik_ref_id' => $pengeluaran->apotik_ref_id,
            'qty_satuan_besar' => $pengeluaran->qty_satuan_besar,
            'qty_satuan_kecil' => $pengeluaran->qty_satuan_kecil,
            'master_obat_id' => $pengeluaran->master_obat_id
        );
        return $data;
    }
}

?>
