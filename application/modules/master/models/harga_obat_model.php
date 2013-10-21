<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Class harga_obat_model
 *
 * @author R.Firmansyah <black.cappuccino@yahoo.com>
 */
class Harga_Obat_Model extends CI_Model {
    protected $table_def = "harga_obat";
    protected $join_table1 = "master_obat";
    
    public function __construct() {
        parent::__construct();
    }
	
    public function getById($id) {
        $this->db->where('harga_obat_id', $id);
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
                harga_obat.harga_obat_id,
                master_obat.master_obat_nama,
                harga_obat.harga_obat_pokok
                ');
        $this->db->join($this->join_table1, 'master_obat.master_obat_id = harga_obat.master_obat_id','left');
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

    public function create($harga_obat) {
	$data = $this->_toArray($harga_obat);
        $this->db->insert($this->table_def, $data);
        return $this->db->insert_id();
    }
    
    public function update($harga_obat) {
	$data = $this->_toArray($harga_obat);
        $this->db->where('harga_obat_id', $harga_obat->harga_obat_id);
        $this->db->update($this->table_def, $data);
    }
    
    public function delete($id) {
        $this->db->where('harga_obat_id', $id);
        $this->db->delete($this->table_def); 
    }
    
    private function _toArray($harga_obat) {
        $data = array(
            'master_obat_id' => $harga_obat->master_obat_id,
            'harga_obat_pokok' => $harga_obat->harga_obat_pokok
        );
        return $data;
    }
}

?>
