<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Class apotik_ref_model
 *
 * @author R.Firmansyah <black.cappuccino@yahoo.com>
 */
class Apotik_Ref_Model extends CI_Model {
    protected $table_def = "apotik_ref";
    
    public function __construct() {
        parent::__construct();
    }
	
    public function getById($id) {
        $this->db->where('apotik_ref_id', $id);
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

    public function create($apotik_ref) {
	$data = $this->_toArray($apotik_ref);
        $this->db->insert($this->table_def, $data);
        return $this->db->insert_id();
    }
    
    public function update($apotik_ref) {
	$data = $this->_toArray($apotik_ref);
        $this->db->where('apotik_ref_id', $apotik_ref->apotik_ref_id);
        $this->db->update($this->table_def, $data);
    }
    
    public function delete($id) {
        $this->db->where('apotik_ref_id', $id);
        $this->db->delete($this->table_def); 
    }
    
    private function _toArray($apotik_ref) {
        $data = array(
            'apotik_ref_nama' => $apotik_ref->apotik_ref_nama,
            'apotik_ref_telp' => $apotik_ref->apotik_ref_telp,
            'apotik_ref_alamat' => $apotik_ref->apotik_ref_alamat
        );
        return $data;
    }
}

?>
