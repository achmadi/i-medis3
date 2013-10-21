<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Class vendor
 *
 * @author R.Firmansyah <black.cappuccino@yahoo.com>
 */
class Vendor extends CI_Controller {
    protected $form = array(
        array(
                'field'		=> 'vendor_nama',
                'label'		=> 'Nama Vendor',
                'rules'		=> 'xss_clean|required'
        ),
        array(
                'field'		=> 'vendor_telp',
                'label'		=> 'Nomor Telepon',
                'rules'		=> 'xss_clean'
        ),
        array(
                'field'		=> 'vendor_alamat',
                'label'		=> 'Alamat',
                'rules'		=> 'xss_clean'
        )
    );

    public function __construct() {
        parent::__construct();
        $this->load->model('master/Vendor_Model');
    }

    public function index()	{
        $this->data['sub_nav'] = "master/vendor/sub_nav";
        $this->template->set_title('Vendor')
                            ->set_js('jquery.dataTables')
                            ->build('master/vendor/browse');
    }

    public function load_data() {

        $aColumns = array('vendor_nama');

        /* 
        * Paging
        */
        if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' ) {
            $iLimit = intval( $_GET['iDisplayLength'] );
            $iOffset = intval( $_GET['iDisplayStart'] );
        }

        /*
        * Ordering
        */
        $aOrders = array();
        if (isset($_GET['iSortCol_0'])) {
            for ($i = 0; $i <intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_'.intval($_GET['iSortCol_'.$i])] == "true") {
                    $aOrders[$aColumns[intval($_GET['iSortCol_'.$i])]] = $_GET['sSortDir_'.$i] === 'asc' ? 'asc' : 'desc';
                }
            }
        }

        /*
        * Like
        */
        $aLikes = array();
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            for ($i = 0; $i < count($aColumns); $i++) {
                if (isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true") {
                    $aLikes[$aColumns[$i]] = mysql_real_escape_string($_GET['sSearch']);
                }
            }
        }

        $vendors = $this->Vendor_Model->getAll($iLimit, $iOffset, $aOrders, array(), $aLikes);

        $rResult = $vendors['data'];
        $iFilteredTotal = $vendors['total_rows'];
        $iTotal = $vendors['total_rows'];

        /*
        * Output
        */
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        foreach ($rResult as $vendor) {
            $row = array();
            $row[] = $vendor->vendor_nama;
            $row[] = $vendor->vendor_telp;
            $row[] = $vendor->vendor_alamat;

            $action = "<a class=\"btn btn-info btn-mini\" href=\"".site_url("master/vendor/edit/".$vendor->vendor_id)."\" data-original-title=\"Edit\">Edit</a>&nbsp;";
            $action .= "<a class=\"btn btn-danger btn-mini\" onClick=\"confirmDelete('".site_url("master/vendor/delete/")."','".$vendor->vendor_nama."',$vendor->vendor_id)\">Hapus</a>";
            //$action .= "<a class=\"btn btn-danger btn-mini\" href=\"".site_url("master/vendor/delete/".$vendor->vendor_id)."\" data-original-title=\"Hapus\">Hapus</a>";

            $row[] = $action;
            $output['aaData'][] = $row;
        }

        echo json_encode($output);
    }

    public function add() {
        $this->data['is_new'] = true;
        $this->_update();
    }

    public function edit($id) {
        $this->data['is_new'] = false;
        $this->_update($id);
    }
    
    private function _update($id = 0) {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error" style="color: #CC0000; margin-left: 150px;">', '</div>');
        $this->form_validation->set_rules($this->form);

        if ($this->input->post('batal')) {
            redirect('master/vendor', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $vendor = $this->_getDataObject();
                if (isset($vendor->vendor_id) && $vendor->vendor_id > 0) {
                    $this->Vendor_Model->update($vendor);
                }
                else {
                    $this->Vendor_Model->create($vendor);
                }
                redirect('master/vendor/index', 'refresh');
            }
        }

        if ($id) {
            $vendor = $this->Vendor_Model->getById($id);
        }
        else {
            $vendor = $this->_getEmptyDataObject();
        }

        $this->data['data'] = $vendor;

        $this->data['sub_nav'] = "master/vendor/sub_nav";
        $this->template->set_title('Tambah/Edit Vendor')
                            ->set_js('jquery.dataTables')
                            ->build('master/vendor/edit');
    }

    public function delete($id) {
        $this->Vendor_Model->delete($id);
        //redirect('master/vendor', 'refresh');
    }
    
    private function _getEmptyDataObject() {
	$vendor = new stdClass();
	$vendor->vendor_id = 0;
        $vendor->vendor_nama = '';
        $vendor->vendor_telp = '';
        $vendor->vendor_alamat = '';
        return $vendor;
    }
    
    private function _getDataObject() {
	$this->input->post(NULL, TRUE);

        $vendor = new stdClass();
	$vendor->vendor_id = isset($_POST['vendor_id']) && ($_POST['vendor_id'] > 0) ? $_POST['vendor_id'] : 0;
        $vendor->vendor_nama = $_POST['vendor_nama'];
        $vendor->vendor_telp = $_POST['vendor_telp'];
        $vendor->vendor_alamat = $_POST['vendor_alamat'];
        return $vendor;
    }
}

?>