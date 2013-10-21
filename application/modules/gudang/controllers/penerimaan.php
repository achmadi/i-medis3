<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Class penerimaan
 *
 * @author R.Firmansyah <black.cappuccino@yahoo.com>
 */
class Penerimaan extends CI_Controller {
    protected $form = array(
        array(
                'field'		=> 'master_obat_id',
                'label'		=> 'Nama Obat',
                'rules'		=> 'xss_clean|required'
        ),
        array(
                'field'		=> 'satuan_besar_ref_id',
                'label'		=> 'Satuan Besar',
                'rules'		=> 'xss_clean'
        ),
        array(
                'field'		=> 'satuan_kecil_ref_id',
                'label'		=> 'Satuan Kecil',
                'rules'		=> 'xss_clean'
        ),
        array(
                'field'		=> 'qty_satuan_besar',
                'label'		=> 'Jumlah Satuan Besar',
                'rules'		=> 'xss_clean'
        ),
        array(
                'field'		=> 'qty_satuan_kecil',
                'label'		=> 'Jumlah Satuan Kecil',
                'rules'		=> 'xss_clean'
        ),
        array(
                'field'		=> 'vendor_id',
                'label'		=> 'Vendor',
                'rules'		=> 'xss_clean'
        ),
        array(
                'field'		=> 'sdana_id',
                'label'		=> 'Sumber Dana',
                'rules'		=> 'xss_clean'
        ),
        array(
                'field'		=> 'harga_obat_sk',
                'label'		=> 'Harga Obat Satuan Kecil',
                'rules'		=> 'xss_clean'
        ),
        array(
                'field'		=> 'harga_obat_sb',
                'label'		=> 'Harga Obat Satuan Besar',
                'rules'		=> 'xss_clean'
        ),
        array(
                'field'		=> 'exp_date',
                'label'		=> 'Tanggal Kadaluarsa',
                'rules'		=> 'xss_clean'
        )
    );

    public function __construct() {
        parent::__construct();
        $this->load->model('gudang/Penerimaan_Model');
        $this->load->model('master/Master_Obat_Model');
        $this->load->model('master/Lm_Unit_Model');
        $this->load->model('master/Sm_Unit_Model');
        $this->load->model('master/Vendor_Model');
        $this->load->model('master/Sfund_Model');
    }

    public function index()	{
        $this->data['sub_nav'] = "gudang/penerimaan/sub_nav";
        $this->template->set_title('Penerimaan')
                            ->set_js('jquery.dataTables')
                            ->build('gudang/penerimaan/browse');
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

        $penerimaans = $this->Penerimaan_Model->getAll($iLimit, $iOffset, $aOrders, array(), $aLikes);

        $rResult = $penerimaans['data'];
        $iFilteredTotal = $penerimaans['total_rows'];
        $iTotal = $penerimaans['total_rows'];

        /*
        * Output
        */
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        foreach ($rResult as $penerimaan) {
            $row = array();
            $row[] = $penerimaan->master_obat_nama;
            $row[] = $penerimaan->qty_satuan_besar." ".$penerimaan->satuan_besar_ref_nama;
            $row[] = $penerimaan->qty_satuan_kecil." ".$penerimaan->satuan_kecil_ref_nama;
            $row[] = $penerimaan->vendor_nama;
            $row[] = $penerimaan->sdana_nama;
            $row[] = $penerimaan->harga_obat_sb;
            $row[] = $penerimaan->harga_obat_sk;
            $row[] = $penerimaan->exp_date;

            $action = "<a class=\"btn btn-info btn-mini\" href=\"".site_url("gudang/penerimaan/edit/".$penerimaan->gud_penerimaan_id)."\" data-original-title=\"Edit\">Edit</a>&nbsp;";
            $action .= "<a class=\"btn btn-danger btn-mini\" onClick=\"confirmDelete('".site_url("gudang/penerimaan/delete/")."','".$penerimaan->master_obat_nama."',$penerimaan->gud_penerimaan_id)\">Hapus</a>";
            //$action .= "<a class=\"btn btn-danger btn-mini\" href=\"".site_url("gudang/penerimaan/delete/".$penerimaan->gud_penerimaan_id)."\" data-original-title=\"Hapus\">Hapus</a>";

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
            redirect('gudang/penerimaan', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $penerimaan = $this->_getDataObject();
                if (isset($penerimaan->gud_penerimaan_id) && $penerimaan->gud_penerimaan_id > 0) {
                    $this->Penerimaan_Model->update($penerimaan);
                }
                else {
                    $this->Penerimaan_Model->create($penerimaan);
                }
                redirect('gudang/penerimaan/index', 'refresh');
            }
        }

        if ($id) {
            $penerimaan = $this->Penerimaan_Model->getById($id);
        }
        else {
            $penerimaan = $this->_getEmptyDataObject();
        }

        $this->data['data'] = $penerimaan;
        $master_obat = $this->Master_Obat_Model->getAll(0);
        $this->data['master_obat_list'] = $master_obat['data'];
        $vendor = $this->Vendor_Model->getAll(0);
        $this->data['vendor_list'] = $vendor['data'];
        $sumber_dana = $this->Sfund_Model->getAll(0);
        $this->data['sumber_dana_list'] = $sumber_dana['data'];

        $this->data['sub_nav'] = "gudang/penerimaan/sub_nav";
        $this->template->set_title('Tambah/Edit Penerimaan')
                            ->set_css('datepicker')
                            ->set_js('bootstrap-datepicker')
                            ->set_js('jquery.dataTables')
                            ->build('gudang/penerimaan/edit');
    }

    public function delete($id) {
        $this->Penerimaan_Model->delete($id);
        //redirect('gudang/penerimaan', 'refresh');
    }
    
    private function _getEmptyDataObject() {
	$penerimaan = new stdClass();
        $current_date = date("Y-m-d H:i:s");
        
	$penerimaan->gud_penerimaan_id = 0;
        $penerimaan->master_obat_id = '';
        $penerimaan->satuan_besar_ref_id = '';
        $penerimaan->satuan_kecil_ref_id = '';
        $penerimaan->qty_satuan_besar = '';
        $penerimaan->qty_satuan_kecil = '';
        $penerimaan->vendor_id = '';
        $penerimaan->sdana_id = '';
        $penerimaan->harga_obat_sb = '';
        $penerimaan->harga_obat_sk = '';
        $penerimaan->exp_date = $current_date;
        return $penerimaan;
    }
    
    private function _getDataObject() {
	$this->input->post(NULL, TRUE);

        $penerimaan = new stdClass();
	$penerimaan->gud_penerimaan_id = isset($_POST['gud_penerimaan_id']) && ($_POST['gud_penerimaan_id'] > 0) ? $_POST['gud_penerimaan_id'] : 0;
        $penerimaan->master_obat_id = $_POST['master_obat_id'];
        $penerimaan->satuan_besar_ref_id = $_POST['satuan_besar_ref_id'];
        $penerimaan->satuan_kecil_ref_id = $_POST['satuan_kecil_ref_id'];
        $penerimaan->qty_satuan_besar = $_POST['qty_satuan_besar'];
        $penerimaan->qty_satuan_kecil = $_POST['qty_satuan_kecil'];
        $penerimaan->vendor_id = $_POST['vendor_id'];
        $penerimaan->sdana_id = $_POST['sdana_id'];
        $penerimaan->harga_obat_sb = $_POST['harga_obat_sb'];
        $penerimaan->harga_obat_sk = $_POST['harga_obat_sk'];
        $penerimaan->exp_date = $_POST['exp_date'];
        return $penerimaan;
    }
    
    public function get_satuan() {
        $master_obat_id = $_GET['master_obat_id'];
        //$options = "<option value=\"0\" selected=\"selected\">- Pilih Kelurahan/Desa -</option>";
        if ($master_obat_id) {
            //$labels = new stdClass();
            $labels = $this->Master_Obat_Model->getSatuanLabel($master_obat_id);
            
            foreach($labels as $label){
              $sLabel = $label->satuan_besar_ref_id."-".$label->satuan_besar_ref_nama."-".$label->satuan_kecil_ref_id."-".$label->satuan_kecil_ref_nama;   
            }
        }
        echo $sLabel;
    }
}

?>
