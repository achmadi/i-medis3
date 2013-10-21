<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Class master_obat
 *
 * @author R.Firmansyah <black.cappuccino@yahoo.com>
 */
class Master_Obat extends CI_Controller {
    protected $form = array(
        array(
                'field'		=> 'master_obat_kode',
                'label'		=> 'kode Obat',
                'rules'		=> 'xss_clean|required'
        ),
        array(
                'field'		=> 'master_obat_nama',
                'label'		=> 'Nama Obat',
                'rules'		=> 'xss_clean|required'
        ),
        array(
                'field'		=> 'jenis_obat_ref_id',
                'label'		=> 'Jenis Obat',
                'rules'		=> 'xss_clean|required'
        ),
        array(
                'field'		=> 'sub_jenis_obat_ref_id',
                'label'		=> 'Sub Jenis Obat',
                'rules'		=> 'xss_clean|required'
        ),
        array(
                'field'		=> 'golongan_obat_ref_id',
                'label'		=> 'Golongan Obat',
                'rules'		=> 'xss_clean|required'
        ),
        array(
                'field'		=> 'satuan_kecil_ref_id',
                'label'		=> 'Satuan Kecil',
                'rules'		=> 'xss_clean|required'
        ),  
        array(
                'field'		=> 'satuan_besar_ref_id',
                'label'		=> 'Satuan Besar',
                'rules'		=> 'xss_clean|required'
        ),
    );

    public function __construct() {
        parent::__construct();
        $this->load->model('master/Master_Obat_Model');
        $this->load->model('master/Jenis_Obat_Model');
        $this->load->model('master/Subjenis_Obat_Model');
        $this->load->model('master/Gol_Obat_Model');
        $this->load->model('master/Sm_Unit_Model');
        $this->load->model('master/Lm_Unit_Model');
    }

    public function index()	{
        $this->data['sub_nav'] = "master/master_obat/sub_nav";
        $this->template->set_title('Master Obat')
                            ->set_css('alertify.core', array('id' => 'toggleCSS'))
							->set_js('jquery.dataTables')
							->set_js('alertify.min')
                            ->build('master/master_obat/browse');
    }

    public function load_data() {

        $aColumns = array('master_obat_nama');

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

        $master_obats = $this->Master_Obat_Model->getAll($iLimit, $iOffset, $aOrders, array(), $aLikes);

        $rResult = $master_obats['data'];
        $iFilteredTotal = $master_obats['total_rows'];
        $iTotal = $master_obats['total_rows'];

        /*
        * Output
        */
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        foreach ($rResult as $master_obat) {
            $row = array();
            $row[] = $master_obat->master_obat_kode;
            $row[] = $master_obat->master_obat_nama;
            $row[] = $master_obat->jenis_obat_ref_nama;
            $row[] = $master_obat->sub_jenis_obat_ref_nama;
            $row[] = $master_obat->golongan_obat_ref_nama;
            $row[] = $master_obat->satuan_kecil_ref_nama;
            $row[] = $master_obat->satuan_besar_ref_nama;

            $action = "<a class=\"btn btn-info btn-mini\" href=\"".site_url("master/master_obat/edit/".$master_obat->master_obat_id)."\" data-original-title=\"Edit\">Edit</a>&nbsp;";
            $action .= "<a class=\"delete-row btn btn-danger btn-mini\" href=\"#\" data-id=\"".$master_obat->master_obat_id."\" data-original-title=\"Hapus\">Hapus</a>";

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
            redirect('master/master_obat', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $master_obat = $this->_getDataObject();
                if (isset($master_obat->master_obat_id) && $master_obat->master_obat_id > 0) {
                    $this->Master_Obat_Model->update($master_obat);
                }
                else {
                    $this->Master_Obat_Model->create($master_obat);
                }
                redirect('master/master_obat/index', 'refresh');
            }
        }

        if ($id) {
            $master_obat = $this->Master_Obat_Model->getById($id);
        }
        else {
            $master_obat = $this->_getEmptyDataObject();
        }

        $this->data['data'] = $master_obat;
        $jenis_obat = $this->Jenis_Obat_Model->getAll(0);
        $this->data['jenis_obat_list'] = $jenis_obat['data'];
        $subjenis_obat = $this->Subjenis_Obat_Model->getAll(0);
        $this->data['subjenis_obat_list'] = $subjenis_obat['data'];
        $gol_obat = $this->Gol_Obat_Model->getAll(0);
        $this->data['gol_obat_list'] = $gol_obat['data'];
        $sm_unit = $this->Sm_Unit_Model->getAll(0);
        $this->data['sm_unit_list'] = $sm_unit['data'];
        $lm_unit = $this->Lm_Unit_Model->getAll(0);
        $this->data['lm_unit_list'] = $lm_unit['data'];

        $this->data['sub_nav'] = "master/master_obat/sub_nav";
        $this->template->set_title('Tambah/Edit Master Obat')
                            ->set_js('jquery.dataTables')
                            ->build('master/master_obat/edit');
    }
	
	public function delete() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Master_Obat_Model->delete($id);
		}
    }
    
    private function _getEmptyDataObject() {
	$master_obat = new stdClass();
	$master_obat->master_obat_id = 0;
        $master_obat->master_obat_kode = '';
        $master_obat->master_obat_nama = '';
        $master_obat->jenis_obat_ref_id = '';
        $master_obat->sub_jenis_obat_ref_id = '';
        $master_obat->golongan_obat_ref_id = '';
        $master_obat->satuan_kecil_ref_id = '';
        $master_obat->satuan_besar_ref_id = '';
        return $master_obat;
    }
    
    private function _getDataObject() {
	$this->input->post(NULL, TRUE);

        $master_obat = new stdClass();
	$master_obat->master_obat_id = isset($_POST['master_obat_id']) && ($_POST['master_obat_id'] > 0) ? $_POST['master_obat_id'] : 0;
        $master_obat->master_obat_kode = $_POST['master_obat_kode'];
        $master_obat->master_obat_nama = $_POST['master_obat_nama'];
        $master_obat->jenis_obat_ref_id = $_POST['jenis_obat_ref_id'];
        $master_obat->sub_jenis_obat_ref_id = $_POST['sub_jenis_obat_ref_id'];
        $master_obat->golongan_obat_ref_id = $_POST['golongan_obat_ref_id'];
        $master_obat->satuan_kecil_ref_id = $_POST['satuan_kecil_ref_id'];
        $master_obat->satuan_besar_ref_id = $_POST['satuan_besar_ref_id'];
        return $master_obat;
    }
}

?>
