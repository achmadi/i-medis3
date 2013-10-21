<?php

/**
 * Description of Class penerimaan_model
 *
 * @author R.Firmansyah <black.cappuccino@yahoo.com>
 */
class Penerimaan_Model extends CI_Model {
    protected $table_def = "gud_penerimaan";
    protected $table_aux = "gud_gudang";
    protected $join_table1 = "master_obat";
    protected $join_table2 = "satuan_besar_ref";
    protected $join_table3 = "satuan_kecil_ref";
    protected $join_table4 = "vendor";
    protected $join_table5 = "sumber_dana";
    
    public function __construct() {
        parent::__construct();
    }
	
    public function getById($id) {
        $this->db->where('gud_penerimaan_id', $id);
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
                gud_penerimaan.gud_penerimaan_id,
                master_obat.master_obat_nama,
                satuan_besar_ref.satuan_besar_ref_nama,
                satuan_kecil_ref.satuan_kecil_ref_nama,
                gud_penerimaan.qty_satuan_besar,
                gud_penerimaan.qty_satuan_kecil,
                vendor.vendor_nama,
                sumber_dana.sdana_nama,
                gud_penerimaan.harga_obat_sb,
                gud_penerimaan.harga_obat_sk,
                gud_penerimaan.exp_date
                ');
        $this->db->join($this->join_table1, 'master_obat.master_obat_id = gud_penerimaan.master_obat_id','left');
        $this->db->join($this->join_table2, 'satuan_besar_ref.satuan_besar_ref_id = gud_penerimaan.satuan_besar_ref_id','left');
        $this->db->join($this->join_table3, 'satuan_kecil_ref.satuan_kecil_ref_id = gud_penerimaan.satuan_kecil_ref_id','left');
        $this->db->join($this->join_table4, 'vendor.vendor_id = gud_penerimaan.vendor_id','left');
        $this->db->join($this->join_table5, 'sumber_dana.sdana_id = gud_penerimaan.sdana_id','left');
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

    public function create($penerimaan) {
        $dataWH = $this->_toArrayWH($penerimaan);
        $this->db->insert($this->table_aux, $dataWH);
	$dataAcc = $this->_toArrayAcc($penerimaan);
        $this->db->insert($this->table_def, $dataAcc);
        return $this->db->insert_id();
    }
    
    public function update($penerimaan) {
	$data = $this->_toArrayAcc($penerimaan);
        $this->db->where('gud_penerimaan_id', $penerimaan->gud_penerimaan_id);
        $this->db->update($this->table_def, $data);
    }
    
    public function delete($id) {
        $this->db->where('gud_penerimaan_id', $id);
        $this->db->delete($this->table_def); 
    }
    
    private function _toArrayAcc($penerimaan) {
        $data = array(
            'master_obat_id' => $penerimaan->master_obat_id,
            'satuan_besar_ref_id' => $penerimaan->satuan_besar_ref_id,
            'satuan_kecil_ref_id' => $penerimaan->satuan_kecil_ref_id,
            'qty_satuan_besar' => $penerimaan->qty_satuan_besar,
            'qty_satuan_kecil' => $penerimaan->qty_satuan_kecil,
            'vendor_id' => $penerimaan->vendor_id,
            'sdana_id' => $penerimaan->sdana_id,
            'harga_obat_sk' => $penerimaan->harga_obat_sk,
            'harga_obat_sb' => $penerimaan->harga_obat_sb,
            'exp_date' => $penerimaan->exp_date
        );
        return $data;
    }
    
    private function _toArrayWH($penerimaan) {
        $data = array(
            'gud_mst_obat_id' => $penerimaan->master_obat_id,
            'gud_sk_ref_id' => $penerimaan->satuan_kecil_ref_id,
            'gud_qty_sk' => $penerimaan->qty_satuan_kecil,
            'gud_sdana_id' => $penerimaan->sdana_id,
            'gud_ho_sk' => $penerimaan->harga_obat_sk,
            'gud_exp_date' => $penerimaan->exp_date
        );
        return $data;
    }
}

?>