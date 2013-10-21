<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Class pengeluaran
 *
 * @author R.Firmansyah <black.cappuccino@yahoo.com>
 */
class Pengeluaran extends CI_Controller {
    protected $form = array(
        array(
                'field'		=> 'apotik_ref_id',
                'label'		=> 'Nama Apotik',
                'rules'		=> 'xss_clean|required'
        ),
        array(
                'field'		=> 'master_obat_id',
                'label'		=> 'Nama Obat',
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
        )
    );

    public function __construct() {
        parent::__construct();
        $this->load->model('gudang/Pengeluaran_Model');
        $this->load->model('master/Master_Obat_Model');
        $this->load->model('master/Apotik_Ref_Model');
    }

    public function index()	{
        $this->data['sub_nav'] = "gudang/pengeluaran/sub_nav";
        $this->template->set_title('Pengeluaran')
                            ->set_js('jquery.dataTables')
                            ->build('gudang/pengeluaran/browse');
    }

    public function load_data() {

        $aColumns = array('apotik_ref.apotik_ref_nama');

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

        $pengeluarans = $this->Pengeluaran_Model->getAll($iLimit, $iOffset, $aOrders, array(), $aLikes);

        $rResult = $pengeluarans['data'];
        $iFilteredTotal = $pengeluarans['total_rows'];
        $iTotal = $pengeluarans['total_rows'];

        /*
        * Output
        */
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        foreach ($rResult as $pengeluaran) {
            $row = array();
            $row[] = $pengeluaran->apotik_ref_nama;
            $row[] = $pengeluaran->master_obat_nama;
            $row[] = $pengeluaran->qty_satuan_besar;
            $row[] = $pengeluaran->qty_satuan_kecil;

            $action = "<a class=\"btn btn-info btn-mini\" href=\"".site_url("gudang/pengeluaran/edit/".$pengeluaran->gud_pengeluaran_id)."\" data-original-title=\"Edit\">Edit</a>&nbsp;";
            $action .= "<a class=\"btn btn-danger btn-mini\" onClick=\"confirmDelete('".site_url("gudang/pengeluaran/delete/")."','".$pengeluaran->apotik_ref_nama."',$pengeluaran->gud_pengeluaran_id)\">Hapus</a>";
            //$action .= "<a class=\"btn btn-danger btn-mini\" href=\"".site_url("gudang/pengeluaran/delete/".$pengeluaran->gud_pengeluaran_id)."\" data-original-title=\"Hapus\">Hapus</a>";

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
            redirect('gudang/pengeluaran', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $pengeluaran = $this->_getDataObject();
                if (isset($pengeluaran->gud_pengeluaran_id) && $pengeluaran->gud_pengeluaran_id > 0) {
                    $this->Pengeluaran_Model->update($pengeluaran);
                }
                else {
                    $this->Pengeluaran_Model->create($pengeluaran);
                }
                redirect('gudang/pengeluaran/index', 'refresh');
            }
        }

        if ($id) {
            $pengeluaran = $this->Pengeluaran_Model->getById($id);
        }
        else {
            $pengeluaran = $this->_getEmptyDataObject();
        }

        $this->data['data'] = $pengeluaran;
        $master_obat = $this->Master_Obat_Model->getAll(0);
        $this->data['master_obat_list'] = $master_obat['data'];
        $apotik = $this->Apotik_Ref_Model->getAll(0);
        $this->data['apotik_list'] = $apotik['data'];

        $this->data['sub_nav'] = "gudang/pengeluaran/sub_nav";
        $this->template->set_title('Tambah/Edit Pengeluaran')
                            ->set_js('jquery.dataTables')
                            ->build('gudang/pengeluaran/edit');
    }

    public function delete($id) {
        $this->Pengeluaran_Model->delete($id);
        //redirect('gudang/pengeluaran', 'refresh');
    }
    
    private function _getEmptyDataObject() {
	$pengeluaran = new stdClass();
        
	$pengeluaran->gud_pengeluaran_id = 0;
        $pengeluaran->master_obat_id = '';
        $pengeluaran->qty_satuan_besar = '';
        $pengeluaran->qty_satuan_kecil = '';
        $pengeluaran->apotik_ref_id = 0;
        return $pengeluaran;
    }
    
    private function _getDataObject() {
	$this->input->post(NULL, TRUE);

        $pengeluaran = new stdClass();
	$pengeluaran->gud_pengeluaran_id = isset($_POST['gud_pengeluaran_id']) && ($_POST['gud_pengeluaran_id'] > 0) ? $_POST['gud_pengeluaran_id'] : 0;
        $pengeluaran->master_obat_id = $_POST['master_obat_id'];
        $pengeluaran->qty_satuan_besar = $_POST['qty_satuan_besar'];
        $pengeluaran->qty_satuan_kecil = $_POST['qty_satuan_kecil'];
        $pengeluaran->apotik_ref_id = $_POST['apotik_ref_id'];
        return $pengeluaran;
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
