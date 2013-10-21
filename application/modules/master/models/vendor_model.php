<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Class vendor_model
 *
 * @author R.Firmansyah <black.cappuccino@yahoo.com>
 */
class Vendor_Model extends CI_Model {
    protected $table_def = "vendor";
    
    public function __construct() {
        parent::__construct();
    }
	
    public function getById($id) {
        $this->db->where('vendor_id', $id);
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

    public function create($vendor) {
	$data = $this->_toArray($vendor);
        $this->db->insert($this->table_def, $data);
        return $this->db->insert_id();
    }
    
    public function update($vendor) {
	$data = $this->_toArray($vendor);
        $this->db->where('vendor_id', $vendor->vendor_id);
        $this->db->update($this->table_def, $data);
    }
    
    public function delete($id) {
        $this->db->where('vendor_id', $id);
        $this->db->delete($this->table_def); 
    }
    
    private function _toArray($vendor) {
        $data = array(
            'vendor_nama' => $vendor->vendor_nama,
            'vendor_telp' => $vendor->vendor_telp,
            'vendor_alamat' => $vendor->vendor_alamat
        );
        return $data;
    }
}

?>