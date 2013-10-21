<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Class lm_unit
 *
 * @author R.Firmansyah <black.cappuccino@yahoo.com>
 */
class Lm_unit extends CI_Controller {
    protected $form = array(
        array(
                'field'		=> 'satuan_besar_ref_kode',
                'label'		=> 'Kode',
                'rules'		=> 'xss_clean|required'
        ),
        array(
                'field'		=> 'satuan_besar_ref_nama',
                'label'		=> 'Nama',
                'rules'		=> 'xss_clean'
        )
    );

    public function __construct() {
        parent::__construct();
        $this->load->model('master/Lm_unit_Model');
    }

    public function index()	{
        $this->data['sub_nav'] = "master/lm_unit/sub_nav";
        $this->template->set_title('Satuan Besar')
                            ->set_js('jquery.dataTables')
                            ->build('master/lm_unit/browse');
    }

    public function load_data() {

        $aColumns = array('satuan_besar_ref_nama');

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

        $lmunits = $this->Lm_unit_Model->getAll($iLimit, $iOffset, $aOrders, array(), $aLikes);

        $rResult = $lmunits['data'];
        $iFilteredTotal = $lmunits['total_rows'];
        $iTotal = $lmunits['total_rows'];

        /*
        * Output
        */
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        foreach ($rResult as $lmunit) {
            $row = array();
            $row[] = $lmunit->satuan_besar_ref_kode;
            $row[] = $lmunit->satuan_besar_ref_nama;

            $action = "<a class=\"btn btn-info btn-mini\" href=\"".site_url("master/lm_unit/edit/".$lmunit->satuan_besar_ref_id)."\" data-original-title=\"Edit\">Edit</a>&nbsp;";
            $action .= "<a class=\"btn btn-danger btn-mini\" onClick=\"confirmDelete('".site_url("master/lm_unit/delete/")."','".$lmunit->satuan_besar_ref_nama."',$lmunit->satuan_besar_ref_id)\">Hapus</a>";
            //$action .= "<a class=\"btn btn-danger btn-mini\" href=\"".site_url("master/lm_unit/delete/".$lmunit->satuan_besar_ref_id)."\" data-original-title=\"Hapus\">Hapus</a>";

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
            redirect('master/lm_unit', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $lmunit = $this->_getDataObject();
                if (isset($lmunit->satuan_besar_ref_id) && $lmunit->satuan_besar_ref_id > 0) {
                    $this->Lm_unit_Model->update($lmunit);
                }
                else {
                    $this->Lm_unit_Model->create($lmunit);
                }
                redirect('master/lm_unit/index', 'refresh');
            }
        }

        if ($id) {
            $lmunit = $this->Lm_unit_Model->getById($id);
        }
        else {
            $lmunit = $this->_getEmptyDataObject();
        }

        $this->data['data'] = $lmunit;

        $this->data['sub_nav'] = "master/lm_unit/sub_nav";
        $this->template->set_title('Tambah/Edit Satuan Besar')
                            ->set_js('jquery.dataTables')
                            ->build('master/lm_unit/edit');
    }

    public function delete($id) {
        $this->Lm_unit_Model->delete($id);
        //redirect('master/lm_unit', 'refresh');
    }
    
    private function _getEmptyDataObject() {
	$lmunit = new stdClass();
	$lmunit->satuan_besar_ref_id = 0;
        $lmunit->satuan_besar_ref_kode = '';
        $lmunit->satuan_besar_ref_nama = '';
        return $lmunit;
    }
    
    private function _getDataObject() {
	$this->input->post(NULL, TRUE);

        $lmunit = new stdClass();
	$lmunit->satuan_besar_ref_id = isset($_POST['satuan_besar_ref_id']) && ($_POST['satuan_besar_ref_id'] > 0) ? $_POST['satuan_besar_ref_id'] : 0;
        $lmunit->satuan_besar_ref_kode = $_POST['satuan_besar_ref_kode'];
        $lmunit->satuan_besar_ref_nama = $_POST['satuan_besar_ref_nama'];
        return $lmunit;
    }
}

?>
