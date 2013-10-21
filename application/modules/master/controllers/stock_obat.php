<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Class stock_obat
 *
 * @author R.Firmansyah <black.cappuccino@yahoo.com>
 */
class Stock_Obat extends CI_Controller {
    protected $form = array(
        array(
                'field'		=> 'master_obat_id',
                'label'		=> 'Nama Obat',
                'rules'		=> 'xss_clean|required'
        ),
        array(
                'field'		=> 'stock_obat_min',
                'label'		=> 'Stok Obat Minimum',
                'rules'		=> 'xss_clean'
        ),
        array(
                'field'		=> 'stock_obat_active',
                'label'		=> 'Aktif',
                'rules'		=> 'xss_clean'
        )
    );

    public function __construct() {
        parent::__construct();
        $this->load->model('master/Stock_Obat_Model');
        $this->load->model('master/Master_Obat_Model');
    }

    public function index()	{
        $this->data['sub_nav'] = "master/stock_obat/sub_nav";
        $this->template->set_title('Stock Obat')
                            ->set_js('jquery.dataTables')
                            ->build('master/stock_obat/browse');
    }

    public function load_data() {

        $aColumns = array('master_obat.master_obat_nama');

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

        $stock_obats = $this->Stock_Obat_Model->getAll($iLimit, $iOffset, $aOrders, array(), $aLikes);

        $rResult = $stock_obats['data'];
        $iFilteredTotal = $stock_obats['total_rows'];
        $iTotal = $stock_obats['total_rows'];

        /*
        * Output
        */
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        foreach ($rResult as $stock_obat) {
            $row = array();
            $row[] = $stock_obat->master_obat_nama;
            $row[] = $stock_obat->stock_obat_min;

            $action = "<a class=\"btn btn-info btn-mini\" href=\"".site_url("master/stock_obat/edit/".$stock_obat->stock_obat_id)."\" data-original-title=\"Edit\">Edit</a>&nbsp;";
            $action .= "<a class=\"btn btn-danger btn-mini\" onClick=\"confirmDelete('".site_url("master/stock_obat/delete/")."','".$stock_obat->master_obat_nama."',$stock_obat->stock_obat_id)\">Hapus</a>";
            //$action .= "<a class=\"btn btn-danger btn-mini\" href=\"".site_url("master/stock_obat/delete/".$stock_obat->stock_obat_id)."\" data-original-title=\"Hapus\">Hapus</a>";

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
            redirect('master/stock_obat', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $stock_obat = $this->_getDataObject();
                if (isset($stock_obat->stock_obat_id) && $stock_obat->stock_obat_id > 0) {
                    $this->Stock_Obat_Model->update($stock_obat);
                }
                else {
                    $this->Stock_Obat_Model->create($stock_obat);
                }
                redirect('master/stock_obat/index', 'refresh');
            }
        }

        if ($id) {
            $stock_obat = $this->Stock_Obat_Model->getById($id);
        }
        else {
            $stock_obat = $this->_getEmptyDataObject();
        }

        $this->data['data'] = $stock_obat;
        $master_obat = $this->Master_Obat_Model->getAll(0);
        $this->data['master_obat_list'] = $master_obat['data'];

        $this->data['sub_nav'] = "master/stock_obat/sub_nav";
        $this->template->set_title('Tambah/Edit Stock Obat')
                            ->set_js('jquery.dataTables')
                            ->build('master/stock_obat/edit');
    }

    public function delete($id) {
        $this->Stock_Obat_Model->delete($id);
        //redirect('master/stock_obat', 'refresh');
    }
    
    private function _getEmptyDataObject() {
	$stock_obat = new stdClass();
	$stock_obat->stock_obat_id = 0;
        $stock_obat->master_obat_id = '';
        $stock_obat->stock_obat_min = '';
        $stock_obat->stock_obat_active = '1';
        return $stock_obat;
    }
    
    private function _getDataObject() {
	$this->input->post(NULL, TRUE);

        $stock_obat = new stdClass();
	$stock_obat->stock_obat_id = isset($_POST['stock_obat_id']) && ($_POST['stock_obat_id'] > 0) ? $_POST['stock_obat_id'] : 0;
        $stock_obat->master_obat_id = $_POST['master_obat_id'];
        $stock_obat->stock_obat_min = $_POST['stock_obat_min'];
        $stock_obat->stock_obat_active = $_POST['stock_obat_active'];
        return $stock_obat;
    }
}

?>
