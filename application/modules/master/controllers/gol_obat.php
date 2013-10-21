<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Class gol_obat
 *
 * @author R.Firmansyah <black.cappuccino@yahoo.com>
 */
class Gol_Obat extends ADMIN_Controller {
    protected $form = array(
        array(
                'field'		=> 'golongan_obat_ref_nama',
                'label'		=> 'Golongan Obat',
                'rules'		=> 'xss_clean|required'
        )
    );

    public function __construct() {
        parent::__construct();
        $this->load->model('master/Gol_Obat_Model');
    }

    public function index()	{
        $this->data['sub_nav'] = "master/gol_obat/sub_nav";
        $this->template->set_title('Golongan Obat')
                            ->set_css('alertify.core', array('id' => 'toggleCSS'))
						    ->set_js('jquery.dataTables')
					        ->set_js('alertify.min')
                            ->build('master/gol_obat/browse');
    }

    public function load_data() {

        $aColumns = array('golongan_obat_ref_nama');

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

        $gols = $this->Gol_Obat_Model->getAll($iLimit, $iOffset, $aOrders, array(), $aLikes);

        $rResult = $gols['data'];
        $iFilteredTotal = $gols['total_rows'];
        $iTotal = $gols['total_rows'];

        /*
        * Output
        */
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        foreach ($rResult as $gol) {
            $row = array();
            $row[] = $gol->golongan_obat_ref_nama;

            $action = "<a class=\"btn btn-info btn-mini\" href=\"".site_url("master/gol_obat/edit/".$gol->golongan_obat_ref_id)."\" data-original-title=\"Edit\">Edit</a>&nbsp;";
			$action .= "<a class=\"delete-row btn btn-danger btn-mini\" href=\"#\" data-id=\"".$gol->golongan_obat_ref_id."\" data-original-title=\"Hapus\">Hapus</a>";

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
            redirect('master/gol_obat', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $gol = $this->_getDataObject();
                if (isset($gol->golongan_obat_ref_id) && $gol->golongan_obat_ref_id > 0) {
                    $this->Gol_Obat_Model->update($gol);
                }
                else {
                    $this->Gol_Obat_Model->create($gol);
                }
                redirect('master/gol_obat/index', 'refresh');
            }
        }

        if ($id) {
            $gol = $this->Gol_Obat_Model->getById($id);
        }
        else {
            $gol = $this->_getEmptyDataObject();
        }

        $this->data['data'] = $gol;

        $this->data['sub_nav'] = "master/gol_obat/sub_nav";
        $this->template->set_title('Tambah/Edit Golongan Obat')
                            ->set_js('jquery.dataTables')
                            ->build('master/gol_obat/edit');
    }
	
	public function delete() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Gol_Obat_Model->delete($id);
		}
    }
    
    private function _getEmptyDataObject() {
	$gol = new stdClass();
	$gol->golongan_obat_ref_id = 0;
        $gol->golongan_obat_ref_nama = '';
        return $gol;
    }
    
    private function _getDataObject() {
	$this->input->post(NULL, TRUE);

        $gol = new stdClass();
	$gol->golongan_obat_ref_id = isset($_POST['golongan_obat_ref_id']) && ($_POST['golongan_obat_ref_id'] > 0) ? $_POST['golongan_obat_ref_id'] : 0;
        $gol->golongan_obat_ref_nama = $_POST['golongan_obat_ref_nama'];
        return $gol;
    }
}

?>
