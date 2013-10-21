<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Class sfund
 *
 * @author R.Firmansyah <black.cappuccino@yahoo.com>
 */
class Sfund extends CI_Controller {
    protected $form = array(
        array(
                'field'		=> 'sdana_nama',
                'label'		=> 'Sumber Dana',
                'rules'		=> 'xss_clean|required'
        )
    );

    public function __construct() {
        parent::__construct();
        $this->load->model('master/Sfund_Model');
    }

    public function index()	{
        $this->data['sub_nav'] = "master/sfund/sub_nav";
        $this->template->set_title('Sumber Dana')
                            ->set_js('jquery.dataTables')
                            ->build('master/sfund/browse');
    }

    public function load_data() {

        $aColumns = array('sdana_nama');

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

        $sfunds = $this->Sfund_Model->getAll($iLimit, $iOffset, $aOrders, array(), $aLikes);

        $rResult = $sfunds['data'];
        $iFilteredTotal = $sfunds['total_rows'];
        $iTotal = $sfunds['total_rows'];

        /*
        * Output
        */
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        foreach ($rResult as $sfund) {
            $row = array();
            $row[] = $sfund->sdana_nama;

            $action = "<a class=\"btn btn-info btn-mini\" href=\"".site_url("master/sfund/edit/".$sfund->sdana_id)."\" data-original-title=\"Edit\">Edit</a>&nbsp;";
            $action .= "<a class=\"btn btn-danger btn-mini\" onClick=\"confirmDelete('".site_url("master/sfund/delete/")."','".$sfund->sdana_nama."',$sfund->sdana_id)\">Hapus</a>";
            //$action .= "<a class=\"btn btn-danger btn-mini\" href=\"".site_url("master/sfund/delete/".$sfund->sdana_id)."\" data-original-title=\"Hapus\">Hapus</a>";

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
            redirect('master/sfund', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $sfund = $this->_getDataObject();
                if (isset($sfund->sdana_id) && $sfund->sdana_id > 0) {
                    $this->Sfund_Model->update($sfund);
                }
                else {
                    $this->Sfund_Model->create($sfund);
                }
                redirect('master/sfund/index', 'refresh');
            }
        }

        if ($id) {
            $sfund = $this->Sfund_Model->getById($id);
        }
        else {
            $sfund = $this->_getEmptyDataObject();
        }

        $this->data['data'] = $sfund;

        $this->data['sub_nav'] = "master/sfund/sub_nav";
        $this->template->set_title('Tambah/Edit Sumber Dana')
                            ->set_js('jquery.dataTables')
                            ->build('master/sfund/edit');
    }

    public function delete($id) {
        $this->Sfund_Model->delete($id);
        //redirect('master/sfund', 'refresh');
    }
    
    private function _getEmptyDataObject() {
	$sfund = new stdClass();
	$sfund->sdana_id = 0;
        $sfund->sdana_nama = '';
        return $sfund;
    }
    
    private function _getDataObject() {
	$this->input->post(NULL, TRUE);

        $sfund = new stdClass();
	$sfund->sdana_id = isset($_POST['sdana_id']) && ($_POST['sdana_id'] > 0) ? $_POST['sdana_id'] : 0;
        $sfund->sdana_nama = $_POST['sdana_nama'];
        return $sfund;
    }
}

?>
