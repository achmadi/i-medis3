<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Class apotik_ref
 *
 * @author R.Firmansyah <black.cappuccino@yahoo.com>
 */
class Apotik_Ref extends ADMIN_Controller {
    protected $form = array(
        array(
                'field'		=> 'apotik_ref_nama',
                'label'		=> 'Nama Apotik',
                'rules'		=> 'xss_clean|required'
        ),
        array(
                'field'		=> 'apotik_ref_telp',
                'label'		=> 'Nomor Telepon',
                'rules'		=> 'xss_clean'
        ),
        array(
                'field'		=> 'apotik_ref_alamat',
                'label'		=> 'Alamat',
                'rules'		=> 'xss_clean'
        )
    );

    public function __construct() {
        parent::__construct();
        $this->load->model('master/Apotik_Ref_Model');
    }

    public function index()	{
        $this->data['sub_nav'] = "master/apotik_ref/sub_nav";
        $this->template->set_title('Apotik')
                            ->set_css('alertify.core', array('id' => 'toggleCSS'))
							->set_js('jquery.dataTables')
							->set_js('alertify.min')
                            ->build('master/apotik_ref/browse');
    }

    public function load_data() {

        $aColumns = array('apotik_ref_nama');

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

        $apotik_refs = $this->Apotik_Ref_Model->getAll($iLimit, $iOffset, $aOrders, array(), $aLikes);

        $rResult = $apotik_refs['data'];
        $iFilteredTotal = $apotik_refs['total_rows'];
        $iTotal = $apotik_refs['total_rows'];

        /*
        * Output
        */
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        foreach ($rResult as $apotik_ref) {
            $row = array();
            $row[] = $apotik_ref->apotik_ref_nama;
            $row[] = $apotik_ref->apotik_ref_telp;
            $row[] = $apotik_ref->apotik_ref_alamat;

            $action = "<a class=\"btn btn-info btn-mini\" href=\"".site_url("master/apotik_ref/edit/".$apotik_ref->apotik_ref_id)."\" data-original-title=\"Edit\">Edit</a>&nbsp;";
			$action = "<a class=\"delete-row btn btn-danger btn-mini\" href=\"#\" data-id=\"".$apotik_ref->apotik_ref_id."\" data-original-title=\"Hapus\">Hapus</a>";

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
            redirect('master/apotik_ref', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $apotik_ref = $this->_getDataObject();
                if (isset($apotik_ref->apotik_ref_id) && $apotik_ref->apotik_ref_id > 0) {
                    $this->Apotik_Ref_Model->update($apotik_ref);
                }
                else {
                    $this->Apotik_Ref_Model->create($apotik_ref);
                }
                redirect('master/apotik_ref/index', 'refresh');
            }
        }

        if ($id) {
            $apotik_ref = $this->Apotik_Ref_Model->getById($id);
        }
        else {
            $apotik_ref = $this->_getEmptyDataObject();
        }

        $this->data['data'] = $apotik_ref;

        $this->data['sub_nav'] = "master/apotik_ref/sub_nav";
        $this->template->set_title('Tambah/Edit Apotik')
                            ->set_js('jquery.dataTables')
                            ->build('master/apotik_ref/edit');
    }
	
	public function delete() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Apotik_Ref_Model->delete($id);
		}
    }
    
    private function _getEmptyDataObject() {
	$apotik_ref = new stdClass();
	$apotik_ref->apotik_ref_id = 0;
        $apotik_ref->apotik_ref_nama = '';
        $apotik_ref->apotik_ref_telp = '';
        $apotik_ref->apotik_ref_alamat = '';
        return $apotik_ref;
    }
    
    private function _getDataObject() {
	$this->input->post(NULL, TRUE);

        $apotik_ref = new stdClass();
	$apotik_ref->apotik_ref_id = isset($_POST['apotik_ref_id']) && ($_POST['apotik_ref_id'] > 0) ? $_POST['apotik_ref_id'] : 0;
        $apotik_ref->apotik_ref_nama = $_POST['apotik_ref_nama'];
        $apotik_ref->apotik_ref_telp = $_POST['apotik_ref_telp'];
        $apotik_ref->apotik_ref_alamat = $_POST['apotik_ref_alamat'];
        return $apotik_ref;
    }
}

?>
