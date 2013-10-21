<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Pembagian Jasa.
 * 
 * @package App
 * @category Controller
 * @author Dadang Dana Suryana
 */
class Kasir extends ADMIN_Controller {
	
	protected $form = array(
		array(
			'field'		=> 'tanggal',
			'label'		=> 'Tanggal',
			'rules'		=> 'xss_clean|required'
		),
		array(
			'field'		=> 'no_register',
			'label'		=> 'No. Register',
			'rules'		=> 'xss_clean|required'
		)
	);
	
	public function __construct() {
		parent::__construct();
		$this->load->language('welcome');
		
		$this->load->model('kasir/Kasir_Model');
		$this->load->model('pendaftaran_irj/Pendaftaran_IRJ_Model');
		
		$this->Kasir_Model->delete_expire_no_register_from_queue();
	}

	public function index() {
		$this->data['is_new'] = true;
		$this->_update();
	}
	
	public function edit($id) {
		$this->data['is_new'] = false;
		$this->_update($id);
	}
	
	private function _update($id = 0) {
		$this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error" style="color: #CC0000;">', '</div>');
		$this->form_validation->set_message('required', 'Field %s diperlukan');
		$this->form_validation->set_message('greater_than', 'Field %s diperlukan');
		$this->form_validation->set_message('less_then', 'Field %s diperlukan');
        $this->form_validation->set_rules($this->form);
        
        if ($this->input->post('batal')) {
            redirect('kasir', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $kasir = $this->_getDataObject();
                if (isset($kasir->id) && $kasir->id > 0) {
                    $this->Kasir_Model->update($kasir);
                }
                else {
                    $this->Kasir_Model->create($kasir);
                }
				redirect('kasir', 'refresh');
            }
        }
		
		if ($id) {
			$kasir = $this->Kasir_Model->getById($id);
		}
		else {
			$kasir = $this->_getEmptyDataObject();
		}
		
		$this->data['data'] = $kasir;
		
		$this->data['instalasi_list'] = array($this->config->item('ID_RAWAT_JALAN')		=> $this->config->item('IDS_RAWAT_JALAN'),
											  $this->config->item('ID_RAWAT_DARURAT')	=> $this->config->item('IDS_RAWAT_DARURAT'),
											  $this->config->item('ID_RAWAT_INAP')		=> $this->config->item('IDS_RAWAT_INAP'));
		
		$this->data['top_nav'] = "kasir/top_nav";
		$this->data['top_nav_selected'] = "Kasir";
		$this->data['sub_nav'] = "kasir/sub_nav1";
		$this->template->set_title('Kasir')
					   ->set_css('datepicker')
					   ->set_js('bootstrap-datepicker')
					   ->set_js('autoNumeric')
					   ->set_js('jquery.dataTables')
			           ->build('kasir/edit');
    }
    
	public function browse() {
		$this->data['top_nav'] = "kasir/top_nav";
		$this->data['top_nav_selected'] = "Browse";
		$this->data['sub_nav'] = "kasir/sub_nav3";
		$this->data['sub_nav_selected'] = "Browse";
		$this->template->set_title('Kasir')
					   ->set_js('jquery.dataTables')
			           ->build('kasir/browse');
	}
	
	public function load_data() {
		
		$aColumns = array('tanggal', 'no_register', 'no_medrec', 'nama');
		
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
		
		/*
		 * Where
		 */
		$aWheres = array();
		
		$pembagian_jasas = $this->Kasir_Model->getAll($iLimit, $iOffset, $aOrders, $aWheres, $aLikes);
		
		$rResult = $pembagian_jasas['data'];
		$iFilteredTotal = $pembagian_jasas['total_rows'];
		$iTotal = $pembagian_jasas['total_rows'];
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		foreach ($rResult as $pembagian_jasa) {
			$row = array();
			$row[] = $pembagian_jasa->tanggal;
			$row[] = $pembagian_jasa->no_register;
			$row[] = $pembagian_jasa->no_medrec;
			$row[] = $pembagian_jasa->nama;
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	
	public function delete() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Kasir_Model->delete($id);
		}
    }
	
	public function get_pasien_by_no_medrec() {
		$instalasi = $this->input->get('instalasi');
		$no_medrec = $this->input->get('no_medrec');
		$output = array();
		switch (intval($instalasi)) {
			case $this->config->item('ID_RAWAT_JALAN'):
				$pendaftaran = $this->Pendaftaran_IRJ_Model->getBy(array('pasien.no_medrec' => $no_medrec));
				break;
			case $this->config->item('ID_RAWAT_DARURAT'):
				$pendaftaran = $this->Pendaftaran_IGD_Model->getBy(array('pasien.no_medrec' => $no_medrec));
				break;
			case $this->config->item('ID_RAWAT_INAP'):
				$pendaftaran = $this->Pelayanan_RI_Model->getBy(array('pasien.no_medrec' => $no_medrec));
				break;
		}
		
		if ($pendaftaran) {
			$output['status'] = "ok";
			$output['pendaftaran'] = $pendaftaran;
		}
		else {
			$output['status'] = "failed";
		}
		echo json_encode($output);
	}
	
	public function browse_pasien() {
		$this->data['instalasi'] = $this->input->get('instalasi');
		$this->load->view('kasir/browse_pasien', $this->data);
	}
	
	public function load_data_pasien() {
		
		$unit = $this->input->get('unit');
		
		$aColumns = array('tanggal', 'no_register', 'no_medrec', 'nama', 'jenis_kelamin', 'alamat_jalan', 'telepon');
		
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
		 * Where
		 */
		$aWheres = array();
		
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
		
		switch (intval($unit)) {
			case $this->config->item('ID_RAWAT_JALAN'):
				$pendaftarans = $this->Pendaftaran_IRJ_Model->getAllPasien($iLimit, $iOffset, $aOrders, $aWheres, $aLikes);
				break;
			case $this->config->item('ID_RAWAT_DARURAT'):
				$pendaftarans = $this->Pendaftaran_IRJ_Model->getAllPasien($iLimit, $iOffset, $aOrders, $aWheres, $aLikes);
				break;
			case $this->config->item('ID_RAWAT_INAP'):
				$pendaftarans = $this->Pendaftaran_IRJ_Model->getAllPasien($iLimit, $iOffset, $aOrders, $aWheres, $aLikes);
				break;
		}
		
		$rResult = $pendaftarans['data'];
		$iFilteredTotal = $pendaftarans['total_rows'];
		$iTotal = $pendaftarans['total_rows'];
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		foreach ($rResult as $pendaftaran) {
			$row = array();
			$row[] = "<a href=\"#\" onclick=\"window.select_pasien(".$pendaftaran->id.", 1);return false;\">".$pendaftaran->tanggal."</a>";
			$row[] = $pendaftaran->no_register;
			$row[] = $pendaftaran->no_medrec;
			$row[] = $pendaftaran->nama;
			$row[] = $pendaftaran->alamat_jalan;
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	
	public function laporan_kasir_rj() {
		$this->data['title'] = "Laporan Kasir Rawat Jalan";
		$this->data['top_nav'] = "kasir/top_nav";
		$this->data['top_nav_selected'] = "Laporan Kasir Rawat Jalan";
		$this->data['sub_nav'] = "kasir/sub_nav2";
		$this->data['sub_nav_selected'] = "Laporan";
		$this->template->set_title('Laporan Kasir Rawat Jalan')
					   ->set_css('datepicker')
					   ->set_js('bootstrap-datepicker')
					   ->set_js('date-picker/date')
					   ->set_js('date-picker/daterangepicker')
					   ->set_js('jquery.dataTables')
			           ->build('kasir/browse_001');
	}
	
	public function laporan_kasir_igd() {
		$this->data['title'] = "Laporan Kasir IGD";
		$this->data['top_nav'] = "kasir/top_nav";
		$this->data['top_nav_selected'] = "Laporan Kasir IGD";
		$this->data['sub_nav'] = "kasir/sub_nav3";
		$this->data['sub_nav_selected'] = "Laporan";
		$this->template->set_title('Laporan Kasir IGD')
					   ->set_css('datepicker')
					   ->set_js('bootstrap-datepicker')
					   ->set_js('date-picker/date')
					   ->set_js('date-picker/daterangepicker')
					   ->set_js('jquery.dataTables')
			           ->build('kasir/browse_001');
	}
	
	public function laporan_kasir_ri() {
		$this->data['title'] = "Laporan Kasir Rawat Inap";
		$this->data['top_nav'] = "kasir/top_nav";
		$this->data['top_nav_selected'] = "Laporan Kasir Rawat Inap";
		$this->data['sub_nav'] = "kasir/sub_nav4";
		$this->data['sub_nav_selected'] = "Laporan";
		$this->template->set_title('Laporan Kasir Rawat Inap')
					   ->set_css('datepicker')
					   ->set_js('bootstrap-datepicker')
					   ->set_js('date-picker/date')
					   ->set_js('date-picker/daterangepicker')
					   ->set_js('jquery.dataTables')
			           ->build('kasir/browse_001');
	}
	
	private function _getEmptyDataObject() {
		$kasir = new stdClass();
		$kasir->id				= 0;
		$kasir->instalasi		= 0;
		$kasir->tanggal			= get_current_date();
		$kasir->no_register		= $this->_order_no_register();
		$kasir->pasien_id		= 0;
		$kasir->cara_bayar_id	= 0;
		$kasir->poliklinik_id	= 0;
		$kasir->bed_id			= 0;
		$kasir->dokter_id		= 0;
		$kasir->version			= 0;
		$kasir->details			= array();
        return $kasir;
    }
	
    private function _getDataObject() {
		$this->input->post(NULL, TRUE);
		
		$kasir = new stdClass();
		$kasir->id				= $this->input->post('id') && ($this->input->post('id') > 0) ? $this->input->post('id') : 0;
		$kasir->instalasi		= $this->input->post('instalasi');
		$kasir->tanggal			= $this->input->post('tanggal');
		$kasir->no_register		= $this->input->post('no_register');
		$kasir->pasien_id		= $this->input->post('pasien_id');
		$kasir->cara_bayar_id	= $this->input->post('cara_bayar_id');
		$kasir->poliklinik_id	= $this->input->post('poliklinik_id');
		$kasir->bed_id			= $this->input->post('bed_id');
		$kasir->dokter_id		= $this->input->post('dokter_id');
		$kasir->version			= $this->input->post('version');
		/*
		$pembagian_jasa_details = $this->Pembagian_Jasa_Detail_Model->getAll(0, 0, array('id' => 'ASC'), array('pembagian_jasa_id' => $pembagian_jasa->id), array());
		$pembagian_jasa->details = $pembagian_jasa_details['data'];
		$aPembagianJasaDetails = array();
		if ($pembagian_jasa->details) {
			foreach ($pembagian_jasa->details as $detail) {
				$detail->mode_edit = DATA_MODE_DELETE;
				$aPembagianJasaDetails[$detail->id] = $detail;
			}
		}
		if (isset($_POST['pembagian_jasa_detail_id'])) {
            for ($i = 0; $i < count($_POST['pembagian_jasa_detail_id']); $i++) {
                $pembagian_jasa_detail_id = $_POST['pembagian_jasa_detail_id'][$i];
				if (!array_key_exists(intval($pembagian_jasa_detail_id), $aPembagianJasaDetails)) {
                    $detail = new StdClass();
                    $detail->id					= $pembagian_jasa_detail_id;
                    $detail->pembagian_jasa_id	= $pembagian_jasa->id;
                    $detail->tarif_pelayanan_id	= $_POST['tindakan_id'][$i];
					$detail->pegawai_id			= $_POST['dokter_id'][$i];
                    $detail->harga_satuan		= $_POST['harga_satuan'][$i];
					$detail->quantity			= $_POST['quantity'][$i];
                    $detail->mode_edit			= DATA_MODE_ADD;
                    $aPembagianJasaDetails[$pembagian_jasa_detail_id] = $detail;
                }
                else {
                    $aPembagianJasaDetails[$pembagian_jasa_detail_id]->tarif_pelayanan_id	= $_POST['tindakan_id'][$i];
					$aPembagianJasaDetails[$pembagian_jasa_detail_id]->pegawai_id			= $_POST['pegawai_id'][$i];
					$aPembagianJasaDetails[$pembagian_jasa_detail_id]->harga_satuan			= $_POST['harga_satuan'][$i];
					$aPembagianJasaDetails[$pembagian_jasa_detail_id]->quantity				= $_POST['quantity'][$i];
                    $aPembagianJasaDetails[$pembagian_jasa_detail_id]->mode_edit			= DATA_MODE_EDIT;
                }
				
				$penerima_jp_details = $this->Penerima_JP_Detail_Model->getAll(0, 0, array('id' => 'ASC'), array('pembagian_jasa_detail_id' => $pembagian_jasa_detail_id));
				$aPembagianJasaDetails[$pembagian_jasa_detail_id]->details = $penerima_jp_details['data'];
				$aPenerimaJPDetails = array();
				if ($aPembagianJasaDetails[$pembagian_jasa_detail_id]->details) {
					foreach ($aPembagianJasaDetails[$pembagian_jasa_detail_id]->details as $detail) {
						$detail->mode_edit = DATA_MODE_DELETE;
						$aPenerimaJPDetails[$detail->id] = $detail;
					}
				}
				if (isset($_POST['penerima_jp_detail_id'][$i])) {
					for ($j = 0; $j < count($_POST['penerima_jp_detail_id'][$i]); $j++) {
						$penerima_jp_detail_id = $_POST['penerima_jp_detail_id'][$i][$j];
						if (!array_key_exists(intval($penerima_jp_detail_id), $aPenerimaJPDetails)) {
							$pjp_detail = new StdClass();
							$pjp_detail->id							= $penerima_jp_detail_id;
							$pjp_detail->pembagian_jasa_detail_id	= $aPembagianJasaDetails[$pembagian_jasa_detail_id]->id;
							$pjp_detail->tanggal					= $_POST['penerima_jp_detail_tanggal'][$i][$j];
							$pjp_detail->pegawai_id					= $_POST['penerima_jp_detail_pegawai_id'][$i][$j];
							$pjp_detail->tarif_langsung				= $_POST['penerima_jp_detail_tarif_langsung'][$i][$j];
							$pjp_detail->proporsi					= $_POST['penerima_jp_detail_jumlah_proporsi'][$i][$j];
							$pjp_detail->langsung					= $_POST['penerima_jp_detail_jumlah_langsung'][$i][$j];
							$pjp_detail->balancing					= $_POST['penerima_jp_detail_jumlah_balancing'][$i][$j];
							$pjp_detail->kebersamaan				= $_POST['penerima_jp_detail_jumlah_kebersamaan'][$i][$j];
							$pjp_detail->mode_edit					= DATA_MODE_ADD;
							$aPenerimaJPDetails[$penerima_jp_detail_id] = $pjp_detail;
						}
						else {
							$aPenerimaJPDetails[$penerima_jp_detail_id]->tanggal		= $_POST['penerima_jp_detail_tanggal'][$i][$j];
							$aPenerimaJPDetails[$penerima_jp_detail_id]->pegawai_id		= $_POST['penerima_jp_detail_pegawai_id'][$i][$j];
							$aPenerimaJPDetails[$penerima_jp_detail_id]->tarif_langsung	= $_POST['penerima_jp_detail_tarif_langsung'][$i][$j];
							$aPenerimaJPDetails[$penerima_jp_detail_id]->proporsi		= $_POST['penerima_jp_detail_jumlah_proporsi'][$i][$j];
							$aPenerimaJPDetails[$penerima_jp_detail_id]->langsung		= $_POST['penerima_jp_detail_jumlah_langsung'][$i][$j];
							$aPenerimaJPDetails[$penerima_jp_detail_id]->balancing		= $_POST['penerima_jp_detail_jumlah_balancing'][$i][$j];
							$aPenerimaJPDetails[$penerima_jp_detail_id]->kebersamaan	= $_POST['penerima_jp_detail_jumlah_kebersamaan'][$i][$j];
							$aPenerimaJPDetails[$penerima_jp_detail_id]->mode_edit		= DATA_MODE_EDIT;
						}
					}
				}
				$aPembagianJasaDetails[$pembagian_jasa_detail_id]->details = $aPenerimaJPDetails;
				
            }
        }
		$pembagian_jasa->details = $aPembagianJasaDetails;
		*/
        return $kasir;
    }
	
	private function _order_no_register() {
		// Cek apakah sudah register
		$register = $this->session->userdata('register_no_register_kasir');
		$register_id = $this->session->userdata('register_no_register_kasir_id');
		// Jika sudah register hapus kemudian pesan
		if ($register) {
			$this->Kasir_Model->delete_no_register_from_queue($register_id);
		}
		// Jika belum langsung pesan
		$no_register = $this->Kasir_Model->get_no_register();
		$this->session->set_userdata('register_no_register_kasir', TRUE);
		$this->session->set_userdata('register_no_register_kasir_id', $no_register['no_register_queue_id']);
		return $no_register['no_register'];
	}
	
}

/* End of file kasir.php */
/* Location: ./application/modules/kasir/kasir.php */