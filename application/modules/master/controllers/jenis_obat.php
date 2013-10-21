<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Class jenis_obat
 *
 * @author R.Firmansyah <black.cappuccino@yahoo.com>
 */
class Jenis_Obat extends ADMIN_Controller {
    protected $form = array(
        array(
                'field'		=> 'jenis_obat_ref_nama',
                'label'		=> 'Jenis Obat',
                'rules'		=> 'xss_clean|required'
        )
    );

    public function __construct() {
        parent::__construct();
        $this->load->model('master/Jenis_Obat_Model');
    }

    public function index()	{
        $this->data['sub_nav'] = "master/jenis_obat/sub_nav";
        $this->template->set_title('Jenis Obat')
                            ->set_js('jquery.dataTables')
                            ->build('master/jenis_obat/browse');
    }

    public function load_data() {

        $aColumns = array('jenis_obat_ref_nama');

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

        $jeniss = $this->Jenis_Obat_Model->getAll($iLimit, $iOffset, $aOrders, array(), $aLikes);

        $rResult = $jeniss['data'];
        $iFilteredTotal = $jeniss['total_rows'];
        $iTotal = $jeniss['total_rows'];

        /*
        * Output
        */
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        foreach ($rResult as $jenis) {
            $row = array();
            $row[] = $jenis->jenis_obat_ref_nama;

            $action = "<a class=\"btn btn-info btn-mini\" href=\"".site_url("master/jenis_obat/edit/".$jenis->jenis_obat_ref_id)."\" data-original-title=\"Edit\">Edit</a>&nbsp;";
            $action .= "<a class=\"btn btn-danger btn-mini\" onClick=\"confirmDelete('".site_url("master/jenis_obat/delete/")."','".$jenis->jenis_obat_ref_nama."',$jenis->jenis_obat_ref_id)\">Hapus</a>";
            //$action .= "<a class=\"btn btn-danger btn-mini\" href=\"".site_url("master/jenis_obat/delete/".$jenis->jenis_obat_ref_id)."\" data-original-title=\"Hapus\">Hapus</a>";

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
            redirect('master/jenis_obat', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $jenis = $this->_getDataObject();
                if (isset($jenis->jenis_obat_ref_id) && $jenis->jenis_obat_ref_id > 0) {
                    $this->Jenis_Obat_Model->update($jenis);
                }
                else {
                    $this->Jenis_Obat_Model->create($jenis);
                }
                redirect('master/jenis_obat/index', 'refresh');
            }
        }

        if ($id) {
            $jenis = $this->Jenis_Obat_Model->getById($id);
        }
        else {
            $jenis = $this->_getEmptyDataObject();
        }

        $this->data['data'] = $jenis;

        $this->data['sub_nav'] = "master/jenis_obat/sub_nav";
        $this->template->set_title('Tambah/Edit Jenis Obat')
                            ->set_js('jquery.dataTables')
                            ->build('master/jenis_obat/edit');
    }

    public function delete($id) {
        $this->Jenis_Obat_Model->delete($id);
        //redirect('master/jenis_obat', 'refresh');
    }
    
    private function _getEmptyDataObject() {
	$jenis = new stdClass();
	$jenis->jenis_obat_ref_id = 0;
        $jenis->jenis_obat_ref_nama = '';
        return $jenis;
    }
    
    private function _getDataObject() {
	$this->input->post(NULL, TRUE);

        $jenis = new stdClass();
	$jenis->jenis_obat_ref_id = isset($_POST['jenis_obat_ref_id']) && ($_POST['jenis_obat_ref_id'] > 0) ? $_POST['jenis_obat_ref_id'] : 0;
        $jenis->jenis_obat_ref_nama = $_POST['jenis_obat_ref_nama'];
        return $jenis;
    }
}

?>
