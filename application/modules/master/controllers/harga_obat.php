<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Class harga_obat
 *
 * @author R.Firmansyah <black.cappuccino@yahoo.com>
 */
class Harga_Obat extends ADMIN_Controller {
    protected $form = array(
        array(
                'field'		=> 'master_obat_id',
                'label'		=> 'Nama Obat',
                'rules'		=> 'xss_clean|required'
        ),
        array(
                'field'		=> 'harga_obat_pokok',
                'label'		=> 'Harga Pokok',
                'rules'		=> 'xss_clean'
        )
    );

    public function __construct() {
        parent::__construct();
        $this->load->model('master/Harga_Obat_Model');
        $this->load->model('master/Master_Obat_Model');
    }

    public function index()	{
        $this->data['sub_nav'] = "master/harga_obat/sub_nav";
        $this->template->set_title('Harga Obat')
                            ->set_js('jquery.dataTables')
                            ->build('master/harga_obat/browse');
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

        $harga_obats = $this->Harga_Obat_Model->getAll($iLimit, $iOffset, $aOrders, array(), $aLikes);

        $rResult = $harga_obats['data'];
        $iFilteredTotal = $harga_obats['total_rows'];
        $iTotal = $harga_obats['total_rows'];

        /*
        * Output
        */
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        foreach ($rResult as $harga_obat) {
            $row = array();
            $row[] = $harga_obat->master_obat_nama;
            $row[] = $harga_obat->harga_obat_pokok;

            $action = "<a class=\"btn btn-info btn-mini\" href=\"".site_url("master/harga_obat/edit/".$harga_obat->harga_obat_id)."\" data-original-title=\"Edit\">Edit</a>&nbsp;";
            $action .= "<a class=\"btn btn-danger btn-mini\" onClick=\"confirmDelete('".site_url("master/harga_obat/delete/")."','".$harga_obat->master_obat_nama."',$harga_obat->harga_obat_id)\">Hapus</a>";
            //$action .= "<a class=\"btn btn-danger btn-mini\" href=\"".site_url("master/harga_obat/delete/".$harga_obat->harga_obat_id)."\" data-original-title=\"Hapus\">Hapus</a>";

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
            redirect('master/harga_obat', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $harga_obat = $this->_getDataObject();
                if (isset($harga_obat->harga_obat_id) && $harga_obat->harga_obat_id > 0) {
                    $this->Harga_Obat_Model->update($harga_obat);
                }
                else {
                    $this->Harga_Obat_Model->create($harga_obat);
                }
                redirect('master/harga_obat/index', 'refresh');
            }
        }

        if ($id) {
            $harga_obat = $this->Harga_Obat_Model->getById($id);
        }
        else {
            $harga_obat = $this->_getEmptyDataObject();
        }

        $this->data['data'] = $harga_obat;
        $master_obat = $this->Master_Obat_Model->getAll(0);
        $this->data['master_obat_list'] = $master_obat['data'];

        $this->data['sub_nav'] = "master/harga_obat/sub_nav";
        $this->template->set_title('Tambah/Edit Harga Obat')
                            ->set_js('jquery.dataTables')
                            ->build('master/harga_obat/edit');
    }

    public function delete($id) {
        $this->Harga_Obat_Model->delete($id);
        //redirect('master/harga_obat', 'refresh');
    }
    
    private function _getEmptyDataObject() {
	$harga_obat = new stdClass();
	$harga_obat->harga_obat_id = 0;
        $harga_obat->master_obat_id = '';
        $harga_obat->harga_obat_pokok = '';
        return $harga_obat;
    }
    
    private function _getDataObject() {
	$this->input->post(NULL, TRUE);

        $harga_obat = new stdClass();
	$harga_obat->harga_obat_id = isset($_POST['harga_obat_id']) && ($_POST['harga_obat_id'] > 0) ? $_POST['harga_obat_id'] : 0;
        $harga_obat->master_obat_id = $_POST['master_obat_id'];
        $harga_obat->harga_obat_pokok = $_POST['harga_obat_pokok'];
        return $harga_obat;
    }
}

?>
