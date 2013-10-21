<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Class subjenis_obat
 *
 * @author R.Firmansyah <black.cappuccino@yahoo.com>
 */
class Subjenis_Obat extends CI_Controller {
    protected $form = array(
        array(
                'field'		=> 'sub_jenis_obat_ref_nama',
                'label'		=> 'Sub Jenis Obat',
                'rules'		=> 'xss_clean|required'
        )
    );

    public function __construct() {
        parent::__construct();
        $this->load->model('master/Subjenis_Obat_Model');
    }

    public function index()	{
        $this->data['sub_nav'] = "master/subjenis_obat/sub_nav";
        $this->template->set_title('Sub Jenis Obat')
                            ->set_js('jquery.dataTables')
                            ->build('master/subjenis_obat/browse');
    }

    public function load_data() {

        $aColumns = array('sub_jenis_obat_ref_nama');

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

        $subjeniss = $this->Subjenis_Obat_Model->getAll($iLimit, $iOffset, $aOrders, array(), $aLikes);

        $rResult = $subjeniss['data'];
        $iFilteredTotal = $subjeniss['total_rows'];
        $iTotal = $subjeniss['total_rows'];

        /*
        * Output
        */
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        foreach ($rResult as $subjenis) {
            $row = array();
            $row[] = $subjenis->sub_jenis_obat_ref_nama;

            $action = "<a class=\"btn btn-info btn-mini\" href=\"".site_url("master/subjenis_obat/edit/".$subjenis->sub_jenis_obat_ref_id)."\" data-original-title=\"Edit\">Edit</a>&nbsp;";
            $action .= "<a class=\"btn btn-danger btn-mini\" onClick=\"confirmDelete('".site_url("master/subjenis_obat/delete/")."','".$subjenis->sub_jenis_obat_ref_nama."',$subjenis->sub_jenis_obat_ref_id)\">Hapus</a>";
            //$action .= "<a class=\"btn btn-danger btn-mini\" href=\"".site_url("master/subjenis_obat/delete/".$subjenis->sub_jenis_obat_ref_id)."\" data-original-title=\"Hapus\">Hapus</a>";

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
            redirect('master/subjenis_obat', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $subjenis = $this->_getDataObject();
                if (isset($subjenis->sub_jenis_obat_ref_id) && $subjenis->sub_jenis_obat_ref_id > 0) {
                    $this->Subjenis_Obat_Model->update($subjenis);
                }
                else {
                    $this->Subjenis_Obat_Model->create($subjenis);
                }
                redirect('master/subjenis_obat/index', 'refresh');
            }
        }

        if ($id) {
            $subjenis = $this->Subjenis_Obat_Model->getById($id);
        }
        else {
            $subjenis = $this->_getEmptyDataObject();
        }

        $this->data['data'] = $subjenis;

        $this->data['sub_nav'] = "master/subjenis_obat/sub_nav";
        $this->template->set_title('Tambah/Edit Sub Jenis Obat')
                            ->set_js('jquery.dataTables')
                            ->build('master/subjenis_obat/edit');
    }

    public function delete($id) {
        $this->Subjenis_Obat_Model->delete($id);
        //redirect('master/subjenis_obat', 'refresh');
    }
    
    private function _getEmptyDataObject() {
	$subjenis = new stdClass();
	$subjenis->sub_jenis_obat_ref_id = 0;
        $subjenis->sub_jenis_obat_ref_nama = '';
        return $subjenis;
    }
    
    private function _getDataObject() {
	$this->input->post(NULL, TRUE);

        $subjenis = new stdClass();
	$subjenis->sub_jenis_obat_ref_id = isset($_POST['sub_jenis_obat_ref_id']) && ($_POST['sub_jenis_obat_ref_id'] > 0) ? $_POST['sub_jenis_obat_ref_id'] : 0;
        $subjenis->sub_jenis_obat_ref_nama = $_POST['sub_jenis_obat_ref_nama'];
        return $subjenis;
    }
}

?>
