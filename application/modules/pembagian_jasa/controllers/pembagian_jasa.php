<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Pembagian Jasa.
 * 
 * @package App
 * @category Controller
 * @author Dadang Dana Suryana
 */
class Pembagian_Jasa extends ADMIN_Controller {
	
	protected $form = array(
		array(
			'field'		=> 'tanggal',
			'label'		=> 'Tanggal',
			'rules'		=> 'xss_clean|required'
		),
	);
	
	public function __construct() {
		parent::__construct();
		$this->load->language('welcome');
		
		$this->load->model('pembagian_jasa/Pembagian_Jasa_Model');
		$this->load->model('pembagian_jasa/Pembagian_Jasa_Detail_Model');
		$this->load->model('pembagian_jasa/Penerima_JP_Detail_Model');
		
		$this->load->model('master/Unit_Model');
		$this->load->model('master/Unit_Detail_Model');
		$this->load->model('master/Tarif_Pelayanan_Model');
		
		$this->load->model('pendaftaran_irj/Pendaftaran_IRJ_Model');
		$this->load->model('pendaftaran_igd/Pendaftaran_IGD_Model');
		$this->load->model('master/Cara_Bayar_Model');
		$this->load->model('master/Poliklinik_Model');
		$this->load->model('master/Poliklinik_Pegawai_Model');
		$this->load->model('master/Kelas_Model');
		$this->load->model('master/Gedung_Model');
		$this->load->model('master/Pasien_Model');
		
		$this->load->model('master/Pegawai_Model');
		
		$this->load->model('master/Insentif_Pemda_Model');
		$this->load->model('master/Insentif_Manajemen_Model');
		$this->load->model('master/Jasa_Pelayanan_Model');

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
        
        if ($this->input->post('Reset')) {
            redirect('pembagian_jasa', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $pembagian_jasa = $this->_getDataObject();
                if (isset($pembagian_jasa->id) && $pembagian_jasa->id > 0) {
                    $this->Pembagian_Jasa_Model->update($pembagian_jasa);
                }
                else {
                    $this->Pembagian_Jasa_Model->create($pembagian_jasa);
                }
				redirect('pembagian_jasa', 'refresh');
            }
        }
		
		if ($id) {
			$pembagian_jasa = $this->Pembagian_Jasa_Model->getById($id);
		}
		else {
			$pembagian_jasa = $this->_getEmptyDataObject();
		}
		
		if ($pembagian_jasa->id) {
			$pembagian_jasa_details = $this->Pembagian_Jasa_Detail_Model->getAll(0, 0, array('id' => 'ASC'), array('pembagian_jasa_id' => $pembagian_jasa->id), array());
			$pembagian_jasa->details = $pembagian_jasa_details['data'];
		}
		else {
			$pembagian_jasa->details = array();
		}
		if ($pembagian_jasa && $pembagian_jasa->pasien_id > 0) {
			$pasien = $this->Pasien_Model->getById($pembagian_jasa->pasien_id);
			$pembagian_jasa->no_medrec = $pasien->no_medrec;
			$pembagian_jasa->nama = $pasien->nama;
		}
		$this->data['data'] = $pembagian_jasa;
		
		$units = $this->Unit_Model->getAll(0, 0, array('ordering' => 'ASC'));
		$this->data['unit_list'] = $units['data'];
		
		$cara_bayar = $this->Cara_Bayar_Model->getAll(0, 0, array('lft'=> 'ASC'), array('jenis' => 'Rincian'));
		$this->data['cara_bayar_list'] = $cara_bayar['data'];
		
		$polikliniks = $this->Poliklinik_Model->getAll(0, 0, array('nama' => 'ASC'));
		$this->data['poliklinik_list'] = $polikliniks['data'];
		
		$kelass = $this->Kelas_Model->getAll(0, 0, array('nama' => 'ASC'));
		$this->data['kelas_list'] = $kelass['data'];
		
		$gedungs = $this->Gedung_Model->getAll(0, 0, array('nama' => 'ASC'));
		$this->data['gedung_list'] = $gedungs['data'];
		
		$this->data['top_nav'] = "pembagian_jasa/top_nav";
		$this->data['top_nav_selected'] = "Pembagian Jasa";
		$this->data['sub_nav'] = "pembagian_jasa/sub_nav1";
		$this->template->set_title('Pembagian Jasa')
					   ->set_css('datepicker')
					   ->set_js('bootstrap-datepicker')
					   ->set_js('autoNumeric')
					   ->set_js('jquery.dataTables')
			           ->build('pembagian_jasa/edit');
    }
    
	public function browse() {
		$this->data['top_nav'] = "pembagian_jasa/top_nav";
		$this->data['top_nav_selected'] = "Browse";
		$this->data['sub_nav'] = "pembagian_jasa/sub_nav3";
		$this->data['sub_nav_selected'] = "Browse";
		$this->template->set_title('Daftar Pemb. Jasa')
					   ->set_js('jquery.dataTables')
			           ->build('pembagian_jasa/browse');
	}
	
	public function load_data() {
		
		$aColumns = array('tanggal', 'no_medrec', 'nama', 'alamat_jalan', 'jumlah');
		
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
		
		$pembagian_jasas = $this->Pembagian_Jasa_Model->getAll($iLimit, $iOffset, $aOrders, $aWheres, $aLikes);
		
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
			$row[] = $pembagian_jasa->no_medrec;
			$row[] = $pembagian_jasa->nama;
			$row[] = $pembagian_jasa->alamat_jalan;
			$row[] = $pembagian_jasa->jumlah;
			$action = "<a class=\"uraian-button btn btn-success btn-mini\" href=\"".site_url("pembagian_jasa/view_pembagian_jasa/".$pembagian_jasa->id)."\" data-id=\"".$pembagian_jasa->id."\" data-original-title=\"Rincian\" title=\"Rincian Tarif Jasa Pelayanan\">Rincian</a>&nbsp;";
			$action .= "<a id=\"".$pembagian_jasa->id."\" class=\"delete-row btn btn-danger btn-mini\" href=\"#\" data-original-title=\"Hapus\" title=\"Hapus Pembagian Jasa\">Hapus</a>";
			$row[] = $action;
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	
	public function pemda() {
		$bulan = $this->input->post('bulan') ? $this->input->post('bulan') : date("m");
		$tahun = $this->input->post('tahun') ? $this->input->post('tahun') : date("Y");
		
		$this->data['current_bulan'] = $bulan;
		$this->data['current_tahun'] = $tahun;
		$this->data['tahun_list'] = $this->Penerima_JP_Detail_Model->get_tahun();
		
		$this->data['jumlah_pemda'] = $this->Insentif_Pemda_Model->get_jumlah($bulan, $tahun);
		
		$this->data['top_nav'] = "pembagian_jasa/top_nav";
		$this->data['top_nav_selected'] = "Insentif";
		$this->data['sub_nav'] = "pembagian_jasa/sub_nav5";
		$this->data['sub_nav_selected'] = "Pemda";
		$this->template->set_title('Insentif Pemda')
					   ->set_js('jquery.dataTables')
			           ->build('pembagian_jasa/browse_pemda');
	}
	
	public function load_data_pemda() {
		
		$aColumns = array('tanggal', 'jumlah');
		
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
		
		if(isset($_GET['sSearch_0']) && $_GET['sSearch_0'] != "") {
			$tgl = explode("/", $_GET['sSearch_0']);
			$bulan = $tgl[1];
			$tahun = $tgl[2];
			$aWheres["MONTH(insentif_pemda.tanggal)"] = intval($bulan);
			$aWheres["YEAR(insentif_pemda.tanggal)"] = intval($tahun);
		}
		else {
			$aWheres["MONTH(insentif_pemda.tanggal)"] = $_GET['bulan'];
			$aWheres["YEAR(insentif_pemda.tanggal)"] = $_GET['tahun'];
		}
		
		$pemdas = $this->Insentif_Pemda_Model->getAll($iLimit, $iOffset, $aOrders, $aWheres, $aLikes);
		
		$rResult = $pemdas['data'];
		$iFilteredTotal = $pemdas['total_rows'];
		$iTotal = $pemdas['total_rows'];
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		foreach ($rResult as $pemda) {
			$row = array();
			$tanggal = strftime( "%d-%m-%Y %H:%M:%S", strtotime($pemda->tanggal));
			$row[] = $tanggal;
			$row[] = number_format ($pemda->jumlah, 2, ',', '.');
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	
	public function manajemen() {
		$bulan = $this->input->post('bulan') ? $this->input->post('bulan') : date("m");
		$tahun = $this->input->post('tahun') ? $this->input->post('tahun') : date("Y");
		
		$this->data['current_bulan'] = $bulan;
		$this->data['current_tahun'] = $tahun;
		$this->data['tahun_list'] = $this->Penerima_JP_Detail_Model->get_tahun();
		
		$this->data['jumlah_manajemen'] = $this->Insentif_Manajemen_Model->get_jumlah($bulan, $tahun);
		
		$this->data['top_nav'] = "pembagian_jasa/top_nav";
		$this->data['top_nav_selected'] = "Insentif";
		$this->data['sub_nav'] = "pembagian_jasa/sub_nav5";
		$this->data['sub_nav_selected'] = "Manajemen";
		$this->template->set_title('Insentif Manajemen')
					   ->set_js('jquery.dataTables')
			           ->build('pembagian_jasa/browse_manajemen');
	}
	
	public function load_data_manajemen() {
		
		$aColumns = array('tanggal', 'jumlah');
		
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
		
		if(isset($_GET['sSearch_0']) && $_GET['sSearch_0'] != "") {
			$tgl = explode("/", $_GET['sSearch_0']);
			$bulan = $tgl[1];
			$tahun = $tgl[2];
			$aWheres["MONTH(insentif_manajemen.tanggal)"] = intval($bulan);
			$aWheres["YEAR(insentif_manajemen.tanggal)"] = intval($tahun);
		}
		else {
			$aWheres["MONTH(insentif_manajemen.tanggal)"] = $_GET['bulan'];
			$aWheres["YEAR(insentif_manajemen.tanggal)"] = $_GET['tahun'];
		}
		
		$manejemens = $this->Insentif_Manajemen_Model->getAll($iLimit, $iOffset, $aOrders, $aWheres, $aLikes);
		
		$rResult = $manejemens['data'];
		$iFilteredTotal = $manejemens['total_rows'];
		$iTotal = $manejemens['total_rows'];
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		foreach ($rResult as $manejemen) {
			$row = array();
			$tanggal = strftime( "%d-%m-%Y %H:%M:%S", strtotime($manejemen->tanggal));
			$row[] = $tanggal;
			$row[] = number_format ($manejemen->jumlah, 2, ',', '.');
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	
	public function langsung() {
		$bulan = $this->input->post('bulan') ? $this->input->post('bulan') : date("m");
		$tahun = $this->input->post('tahun') ? $this->input->post('tahun') : date("Y");
		
		$this->data['current_bulan'] = $bulan;
		$this->data['current_tahun'] = $tahun;
		$this->data['tahun_list'] = $this->Penerima_JP_Detail_Model->get_tahun();
		
		$this->data['total_langsung'] = $this->Penerima_JP_Detail_Model->get_total($bulan, $tahun);
		
		$this->data['top_nav'] = "pembagian_jasa/top_nav";
		$this->data['top_nav_selected'] = "Insentif";
		$this->data['sub_nav'] = "pembagian_jasa/sub_nav5";
		$this->data['sub_nav_selected'] = "Langsung";
		$this->template->set_title('Insentif Langsung')
					   ->set_js('jquery.dataTables')
			           ->build('pembagian_jasa/browse_langsung');
	}
	
	public function load_data_langsung() {
		
		$aColumns = array('tanggal', 'no_rekening', 'nama', 'jumlah');
		
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
					if ($aColumns[$i] != 'jumlah') {
						$aLikes[$aColumns[$i]] = mysql_real_escape_string($_GET['sSearch']);
					}
				}
			}
		}
		
		/*
		 * Where
		 */
		$aWheres = array();
		
		if(isset($_GET['sSearch_0']) && $_GET['sSearch_0'] != "") {
			$tgl = explode("/", $_GET['sSearch_0']);
			$bulan = $tgl[1];
			$tahun = $tgl[2];
			$aWheres["MONTH(jasa_pelayanan.tanggal)"] = intval($bulan);
			$aWheres["YEAR(jasa_pelayanan.tanggal)"] = intval($tahun);
		}
		else {
			$aWheres["MONTH(jasa_pelayanan.tanggal)"] = $_GET['bulan'];
			$aWheres["YEAR(jasa_pelayanan.tanggal)"] = $_GET['tahun'];
		}
		
		$langsungs = $this->Jasa_Pelayanan_Model->getAll($iLimit, $iOffset, $aOrders, $aWheres, $aLikes);
		
		$rResult = $langsungs['data'];
		$iFilteredTotal = $langsungs['total_rows'];
		$iTotal = $langsungs['total_rows'];
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		foreach ($rResult as $langsung) {
			$row = array();
			$tanggal = strftime( "%d-%m-%Y %H:%M:%S", strtotime($langsung->tanggal));
			$row[] = $tanggal;
			$row[] = $langsung->no_rekening;
			$row[] = $langsung->nama;
			$row[] = number_format ($langsung->jasa_langsung, 2, ',', '.');
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	
	public function tidak_langsung() {
		
		$bulan = $this->input->post('bulan') ? $this->input->post('bulan') : date("m");
		$tahun = $this->input->post('tahun') ? $this->input->post('tahun') : date("Y");
		
		$this->data['current_bulan'] = $bulan;
		$this->data['current_tahun'] = $tahun;
		$this->data['tahun_list'] = $this->Penerima_JP_Detail_Model->get_tahun();
		
		$this->data['total_tidak_langsung'] = $this->Jasa_Pelayanan_Model->get_total($bulan, $tahun);
		
		$this->data['top_nav'] = "pembagian_jasa/top_nav";
		$this->data['top_nav_selected'] = "Insentif";
		$this->data['sub_nav'] = "pembagian_jasa/sub_nav5";
		$this->data['sub_nav_selected'] = "Tidak Langsung";
		$this->template->set_title('Insentif Tidak Langsung')
					   ->set_js('jquery.dataTables')
			           ->build('pembagian_jasa/browse_tidak_langsung');
	}
	
	public function load_data_tidak_langsung() {
		
		$aColumns = array('tanggal', 'nama', 'unit', 'jumlah');
		
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
					switch ($aColumns[$i]) {
						case 'tanggal':
							$aLikes['jasa_pelayanan.tanggal'] = mysql_real_escape_string($_GET['sSearch']);
							break;
						case 'nama':
							$aLikes['pegawai.nama'] = mysql_real_escape_string($_GET['sSearch']);
							break;
						case 'unit':
							$aLikes['unit.nama'] = mysql_real_escape_string($_GET['sSearch']);
							break;
					}
				}
			}
		}
		
		/*
		 * Where
		 */
		$aWheres = array();
		
		if(isset($_GET['sSearch_0']) && $_GET['sSearch_0'] != "") {
			$tgl = explode("/", $_GET['sSearch_0']);
			$bulan = $tgl[1];
			$tahun = $tgl[2];
			$aWheres["MONTH(jasa_pelayanan.tanggal)"] = intval($bulan);
			$aWheres["YEAR(jasa_pelayanan.tanggal)"] = intval($tahun);
		}
		else {
			$aWheres["MONTH(jasa_pelayanan.tanggal)"] = $_GET['bulan'];
			$aWheres["YEAR(jasa_pelayanan.tanggal)"] = $_GET['tahun'];
		}
		
		$tidak_langsungs = $this->Jasa_Pelayanan_Model->getAll($iLimit, $iOffset, $aOrders, $aWheres, $aLikes);
		
		$rResult = $tidak_langsungs['data'];
		$iFilteredTotal = $tidak_langsungs['total_rows'];
		$iTotal = $tidak_langsungs['total_rows'];
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		foreach ($rResult as $tidak_langsung) {
			$row = array();
			$tanggal = strftime( "%d-%m-%Y %H:%M:%S", strtotime($tidak_langsung->tanggal));
			$row[] = $tanggal;
			$row[] = $tidak_langsung->nama;
			$row[] = $tidak_langsung->unit;
			$row[] = number_format ($tidak_langsung->jumlah, 2, ',', '.');
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	
	public function view_pembagian_jasa($id = 0) {
		//$this->data['data'] = unserialize($this->input->get('data'));
		//$pegawais = $this->Pegawai_Model->getAll(0, 0, array('nama' => 'ASC'));
		//$this->data['pegawai_list'] = $pegawais['data'];
		$pembagian_jasa = $this->Pembagian_Jasa_Model->getBy(array('pembagian_jasa.id' => $id));
		$pembagian_jasa_details = $this->Pembagian_Jasa_Detail_Model->getAll(0, 0, array(), array('pembagian_jasa_detail.pembagian_jasa_id' => $pembagian_jasa->id));
		$pembagian_jasa->details = $pembagian_jasa_details['data'];
		$this->data['data'] = $pembagian_jasa;
		$this->load->view('pembagian_jasa/view_pembagian_jasa', $this->data);
	}
	
	public function delete($id) {
 		$this->Pembagian_Jasa_Model->delete($id);
		redirect('pembagian_jasa/view', 'refresh');
    }
	
	public function browse_pasien() {
		$this->load->view('pembagian_jasa/browse_pasien');
	}
	
	public function load_data_pasien() {
		
		$aColumns = array('no_medrec', 'nama', 'jenis_kelamin', 'alamat_jalan', 'tanggal_lahir');
		
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
					$aLikes['pasien.'.$aColumns[$i]] = mysql_real_escape_string($_GET['sSearch']);
				}
			}
		}
		if(isset($_GET['sSearch_0']) && $_GET['sSearch_0'] != "") {
			$aLikes['pasien.no_medrec'] = mysql_real_escape_string($_GET['sSearch_0']);
		}
		if(isset($_GET['sSearch_1']) && $_GET['sSearch_1'] != "") {
			$aLikes['pasien.nama'] = mysql_real_escape_string($_GET['sSearch_1']);
		}
		if(isset($_GET['sSearch_2']) && $_GET['sSearch_2'] != "") {
			$aLikes['pasien.jenis_kelamin'] = mysql_real_escape_string($_GET['sSearch_2']);
		}
		if(isset($_GET['sSearch_3']) && $_GET['sSearch_3'] != "") {
			$aLikes['pasien.alamat_jalan'] = mysql_real_escape_string($_GET['sSearch_3']);
		}
		if(isset($_GET['sSearch_4']) && $_GET['sSearch_4'] != "") {
			$aLikes['pasien.tanggal_lahir'] = mysql_real_escape_string($_GET['sSearch_4']);
		}
		
		$pasiens = $this->Pasien_Model->getAll($iLimit, $iOffset, $aOrders, array(), $aLikes);
		
		$rResult = $pasiens['data'];
		$iFilteredTotal = $pasiens['total_rows'];
		$iTotal = $pasiens['total_rows'];
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		foreach ($rResult as $pasien) {
			$row = array();
			$row[] = "<a href=\"#\" onclick=\"window.select_pasien(".$pasien->id.");return false;\" style=\"color: #4183C4;\">".$pasien->no_medrec."</a>";
			$row[] = $pasien->nama;
			$row[] = jenis_kelamin_descr($pasien->jenis_kelamin);
			$row[] = $pasien->alamat_jalan;
			$row[] = strftime("%d/%m/%Y", strtotime($pasien->tanggal_lahir));
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	
	public function get_pasien_by_id() {
		$pasien_id = $this->input->get('pasien_id');
		$unit_id = $this->input->get('unit_id');
		$unit = $this->Unit_Model->getBy(array('id' => $unit_id));
		switch ($unit->jenis) {
			case $this->config->item('ID_RAWAT_JALAN'):
				$pasien = $this->Pendaftaran_IRJ_Model->getBy(array('pasien_id' => $pasien_id));
				break;
			/*
			case $this->config->item('ID_RAWAT_DARURAT'):
				$pasien = $this->Pendaftaran_IGD_Model->getBy(array('pasien_id' => $pasien_id));
				break;
			case $this->config->item('ID_RAWAT_INAP'):
				$pasien = $this->Rawat_inap_Model->getBy(array('pasien_id' => $pasien_id));
				break;
			*/
		}
		$aPasien = get_object_vars($pasien);
		echo json_encode(array("pasien" => $aPasien));
	}
	
	public function browse_tindakan() {
		$unit_id = $this->input->get('unit_id');
		$this->data['unit_id'] = $unit_id;
		$units = $this->Unit_Model->getAll(0, 0, array('ordering' => 'ASC'));
		$this->data['unit_list'] = $units['data'];
		$this->load->view('pembagian_jasa/browse_tindakan', $this->data);
	}
	
	public function load_data_tindakan() {
		
		$aColumns = array('nama', 'unit_id');
		
		$unit_id = $this->input->get("unit_id");
		
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
		$aOrders = array('lft' => 'ASC');
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
					switch ($aColumns[$i]) {
						case 'nama':
							$aLikes['n.nama'] = mysql_real_escape_string($_GET['sSearch']);
							break;
					} 
				}
			}
		}
		
		$unit_id = intval(mysql_real_escape_string($_GET['sSearch_1']));
		
		$row = $this->Tarif_Pelayanan_Model->getBy(array('unit_id' => $unit_id, 'jenis' => 'Root'));
		$pelayanans = $this->Tarif_Pelayanan_Model->get_tree($row->id, $iLimit, $iOffset, $aOrders, array(), $aLikes);
		
		$rResult = $pelayanans['data'];
		$iFilteredTotal = $pelayanans['total_rows'] - 1;
		$iTotal = $pelayanans['total_rows'] - 1;
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		foreach ($rResult as $pelayanan) {
			if ($pelayanan->jenis != 'Root') {
				$row = array();
				
				$lvl = $pelayanan->level - 2;
				$indent = '';
				for ($i = 0; $i < $lvl; $i++) {
					$indent .= '<span class="gi">|&mdash;</span>';
				}
				
				if ($pelayanan->jenis == 'Kelompok') {
					$row[] = $indent.$pelayanan->nama;
				}
				else {
					$row[] = "<a href=\"#\" onclick=\"window.select_tindakan(".$pelayanan->id.");return false;\">".$indent.$pelayanan->nama."</a>";
				}
				$row[] = $pelayanan->unit_id;
				$output['aaData'][] = $row;
			}
		}
		
		echo json_encode($output);
	}
	
	public function get_tindakan() {
		$id = $this->input->get('tindakan_id');
		$counter = $this->input->get('counter');
		
		$tindakan = $this->Tarif_Pelayanan_Model->getBy(array('id' => $id));
		$unit = $this->Unit_Model->getBy(array('id' => $tindakan->unit_id));
		
		$root = $this->Unit_Detail_Model->getBy(array('unit_id' => $unit->id, 'unit_detail.jenis' => 'Root'));
		$data = $this->Unit_Detail_Model->get_tree($root->id, 0, 0, array('n.lft' => 'asc'));
		$unit_detail = $data['data'];
		
		$oTindakan = new stdClass();
		
		$oTindakan->id = $tindakan->id;
		$oTindakan->nama = $tindakan->nama;
		
		//Jasa sarana
		$oTindakan->jasa_sarana = intval($tindakan->jasa_sarana);
		
		//Jasa Pelayanan
		$oTindakan->jasa_pelayanan = intval($tindakan->jasa_pelayanan);
		
		//Tarif Pemda
		$oTindakan->pemda = intval($unit->pemda);
		$oTindakan->jumlah_pemda = intval($oTindakan->jasa_pelayanan) * intval($unit->pemda)/100;
		
		//Tarif Dibagikan
		$oTindakan->dibagikan = intval($unit->dibagikan);
		$oTindakan->jumlah_dibagikan = intval($oTindakan->jasa_pelayanan) - intval($oTindakan->jumlah_pemda);
		
		//Tarif Manajemen
		$oTindakan->manajemen = intval($unit->manajemen);
		$oTindakan->jumlah_manajemen = intval($oTindakan->jumlah_dibagikan) * intval($oTindakan->manajemen)/100;
	
		//Tarif Sisa
		$oTindakan->sisa = intval($unit->sisa);
		$oTindakan->jumlah_sisa = intval($oTindakan->jumlah_dibagikan) - intval($oTindakan->jumlah_manajemen);
		
		//Tarif Kebersamaan
		$oTindakan->kebersamaan = array();
		
		$html = '';
		$index = 1;
		foreach ($unit_detail as $row) {
			if ($row->jenis != 'Root') {
				$oProporsi = new stdClass();
				// Proporsi
				$oProporsi->proporsi = intval($row->proporsi);
				$oProporsi->jumlah_proporsi = $oTindakan->jumlah_sisa * $oProporsi->proporsi/100;
				
				// Langsung
				$oProporsi->langsung = intval($row->langsung);
				$oProporsi->jumlah_langsung = $oProporsi->jumlah_proporsi * ($oProporsi->langsung/100);
				
				// Kebersamaan
				$oProporsi->kebersamaan = intval($row->kebersamaan);
				$oProporsi->jumlah_kebersamaan = $oProporsi->jumlah_proporsi * ($oProporsi->kebersamaan/100);
				
				$oTindakan->kebersamaan[] = $oProporsi;
				
				$html .= '<tr id="row_penerima_jp_detail_'.$counter.'_'.$index.'">';
				$html .= '	<td><input type="hidden" id="penerima_jp_detail_id_'.$counter.'_'.$index.'" name="penerima_jp_detail_id['.($counter - 1).'][]" value="new_penerima_jp_detail_'.$counter.'_'.$index.'" /></td>';
				$html .= '	<td><input type="hidden" id="penerima_jp_detail_tanggal_'.$counter.'_'.$index.'" name="penerima_jp_detail_tanggal['.($counter - 1).'][]" value="" /></td>';
				$html .= '	<td><input type="hidden" id="penerima_jp_detail_kelompok_id_'.$counter.'_'.$index.'" name="penerima_jp_detail_kelompok_id['.($counter - 1).'][]" value="'.$row->kelompok_id.'" /></td>';
				$html .= '	<td><input type="hidden" id="penerima_jp_detail_jenis_kelompok_'.$counter.'_'.$index.'" name="penerima_jp_detail_jenis_kelompok['.($counter - 1).'][]" value="'.$row->jenis_kelompok.'" /></td>';
				$html .= '	<td><input type="hidden" id="penerima_jp_detail_nama_'.$counter.'_'.$index.'" name="penerima_jp_detail_nama['.($counter - 1).'][]" value="'.$row->kelompok.'" /></td>';
				$html .= '	<td><input type="hidden" id="penerima_jp_detail_unit_id_'.$counter.'_'.$index.'" name="penerima_jp_detail_unit_id['.($counter - 1).'][]" value="'.$tindakan->unit_id.'" /></td>';
				$html .= '	<td><input type="hidden" id="penerima_jp_detail_pegawai_id_'.$counter.'_'.$index.'" name="penerima_jp_detail_pegawai_id['.($counter - 1).'][]" value="" /></td>';
				$html .= '	<td><input type="hidden" id="penerima_jp_detail_pasien_id_'.$counter.'_'.$index.'" name="penerima_jp_detail_pasien_id['.($counter - 1).'][]" value="" /></td>';
				$html .= '	<td><input type="hidden" id="penerima_jp_detail_quantity_'.$counter.'_'.$index.'" name="penerima_jp_detail_quantity['.($counter - 1).'][]" value="" /></td>';
				$html .= '	<td>';
				$html .= '		<input type="hidden" id="penerima_jp_detail_persen_proporsi_'.$counter.'_'.$index.'" name="penerima_jp_detail_persen_proporsi['.($counter - 1).'][]" value="'.$row->proporsi.'" />';
				$html .= '		<input type="hidden" id="penerima_jp_detail_jumlah_proporsi_'.$counter.'_'.$index.'" name="penerima_jp_detail_jumlah_proporsi['.($counter - 1).'][]" value="0" />';
				$html .= '	</td>';
				$html .= '	<td>';
				$html .= '		<input type="hidden" id="penerima_jp_detail_persen_langsung_'.$counter.'_'.$index.'" name="penerima_jp_detail_persen_langsung['.($counter - 1).'][]" value="'.$row->langsung.'" />';
				$html .= '		<input type="hidden" id="penerima_jp_detail_jumlah_langsung_'.$counter.'_'.$index.'" name="penerima_jp_detail_jumlah_langsung['.($counter - 1).'][]" value="0" />';
				$html .= '	</td>';
				$html .= '	<td>';
				$html .= '		<input type="hidden" id="penerima_jp_detail_persen_kebersamaan_'.$counter.'_'.$index.'" name="penerima_jp_detail_persen_kebersamaan['.($counter - 1).'][]" value="'.$row->kebersamaan.'" />';
				$html .= '		<input type="hidden" id="penerima_jp_detail_jumlah_kebersamaan_'.$counter.'_'.$index.'" name="penerima_jp_detail_jumlah_kebersamaan['.($counter - 1).'][]" value="0" />';
				$html .= '	</td>';
				$html .= '</tr>';
				$index++;
			}
		}
		$oTindakan->html = $html;
		
		echo json_encode(array("tindakan" => $oTindakan));
	}
	
	public function get_dokter() {
		//$poliklinik_id = $this->input->get('poliklinik_id');
		$datas = $this->Pegawai_Model->getAll(0, 0, array('nama' => 'ASC'), array('kelompok_pegawai.jenis' => 1));
		$dokters = $datas['data'];
		
		$dokter_id = $this->input->get('dokter_id') ? $this->input->get('dokter_id') : 0;

		$options = "<option value=\"0\" selected=\"selected\">- Pilih Dokter -</option>";
		foreach ($dokters as $dokter) {
			$continue = true;
			if ($dokter_id > 0) {
				if ($dokter->pegawai_id == $dokter_id) {
					$options .= "<option value=\"{$dokter->id}\" selected=\"selected\">{$dokter->nama}</option>";
					$continue = false;
				}
			}
			if ($continue)
				$options .= "<option value=\"{$dokter->id}\">{$dokter->nama}</option>";
		}
		echo $options;
	}
	
	public function get_tarif_jenis_pelayanan($id) {
		$jenis_pelayanan = $this->Unit_Model->getBy(array('id' => $id));
		$aTarif = get_object_vars($jenis_pelayanan);
		echo json_encode(array("tarif" => $aTarif));
	}
	
	public function tarif_pelayanan_autocomplete() {
		$query = $this->input->get('q');
		$datas = $this->Tarif_Pelayanan_Model->getAll(0, 0, array('lft' => 'asc'), array('jenis' => 'Rincian'), array('nama' => $query));
		$data = $datas['data'];
		$tarif_pelayanan = array();
		foreach ($data as $row) {
			$tarif_pelayanan[] = array('id' => $row->id, 'nama' => $row->nama, 'jasa_sarana' => $row->jasa_sarana, 'jasa_pelayanan' => $row->jasa_pelayanan);
		}
		echo json_encode(array("tarif_pelayanan" => $tarif_pelayanan));
	}
	
	public function get_uraian() {
		$this->data['counter'] = $this->input->get('counter');
		$this->data['data'] = unserialize($this->input->get('data'));
		$pegawais = $this->Pegawai_Model->getAll(0, 0, array('nama' => 'ASC'));
		$this->data['pegawai_list'] = $pegawais['data'];
		$this->load->view('pembagian_jasa/uraian', $this->data);
	}
	
	private function _getEmptyDataObject() {
		$pembagian_jasa = new stdClass();
		$pembagian_jasa->id					= 0;
		$pembagian_jasa->unit				= 0;
		$pembagian_jasa->tanggal			= get_current_date();
		$pembagian_jasa->sd_tanggal			= get_current_date();
		$pembagian_jasa->pasien_id			= 0;
		$pembagian_jasa->nama				= '';
		$pembagian_jasa->alamat				= '';
		$pembagian_jasa->cara_bayar_id		= 0;
		$pembagian_jasa->poliklinik_id		= 0;
		$pembagian_jasa->kelas_id			= 0;
		$pembagian_jasa->gedung_id			= 0;
		$pembagian_jasa->jumlah				= 0;
		$pembagian_jasa->version			= 0;
		$pembagian_jasa->details			= array();
        return $pembagian_jasa;
    }
	
    private function _getDataObject() {
		$this->input->post(NULL, TRUE);
		
		$pembagian_jasa = new stdClass();
		$pembagian_jasa->id				= $this->input->post('id') && ($this->input->post('id') > 0) ? $this->input->post('id') : 0;
        $pembagian_jasa->unit			= $this->input->post('unit');
		$pembagian_jasa->tanggal		= $this->input->post('tanggal');
		$pembagian_jasa->sd_tanggal		= $this->input->post('sd_tanggal');
		$pembagian_jasa->pasien_id		= $this->input->post('pasien_id');
		$pembagian_jasa->nama			= $this->input->post('nama');
		$pembagian_jasa->alamat			= $this->input->post('alamat');
		$pembagian_jasa->cara_bayar_id	= $this->input->post('cara_bayar_id');
		$pembagian_jasa->poliklinik_id	= $this->input->post('poliklinik_id');
		$pembagian_jasa->kelas_id		= $this->input->post('kelas_id');
		$pembagian_jasa->gedung_id		= $this->input->post('ruangan_id');
		$pembagian_jasa->jumlah			= $this->input->post('total');
		$pembagian_jasa->version		= $this->input->post('version');
		
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
					$detail->insentif_pemda		= $_POST['insentif_pemda'][$i];
					$detail->insentif_manajemen	= $_POST['insentif_manajemen'][$i];
                    $detail->mode_edit			= DATA_MODE_ADD;
                    $aPembagianJasaDetails[$pembagian_jasa_detail_id] = $detail;
                }
                else {
                    $aPembagianJasaDetails[$pembagian_jasa_detail_id]->tarif_pelayanan_id	= $_POST['tindakan_id'][$i];
					$aPembagianJasaDetails[$pembagian_jasa_detail_id]->pegawai_id			= $_POST['pegawai_id'][$i];
					$aPembagianJasaDetails[$pembagian_jasa_detail_id]->harga_satuan			= $_POST['harga_satuan'][$i];
					$aPembagianJasaDetails[$pembagian_jasa_detail_id]->quantity				= $_POST['quantity'][$i];
					$aPembagianJasaDetails[$pembagian_jasa_detail_id]->insentif_pemda		= $_POST['insentif_pemda'][$i];
					$aPembagianJasaDetails[$pembagian_jasa_detail_id]->insentif_manajemen	= $_POST['insentif_manajemen'][$i];
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
				foreach ($_POST['penerima_jp_detail_id'] as $key => $value) {
					$tkey = $key;
					$tvalue = $value;
				}
				if (isset($_POST['penerima_jp_detail_id'][$i])) {
					for ($j = 0; $j < count($_POST['penerima_jp_detail_id'][$i]); $j++) {
						$penerima_jp_detail_id = $_POST['penerima_jp_detail_id'][$i][$j];
						if (!array_key_exists(intval($penerima_jp_detail_id), $aPenerimaJPDetails)) {
							$pjp_detail = new StdClass();
							$pjp_detail->id							= $penerima_jp_detail_id;
							$pjp_detail->pembagian_jasa_detail_id	= $aPembagianJasaDetails[$pembagian_jasa_detail_id]->id;
							$pjp_detail->tanggal					= $_POST['penerima_jp_detail_tanggal'][$i][$j];
							$pjp_detail->kelompok_id				= $_POST['penerima_jp_detail_kelompok_id'][$i][$j];
							$pjp_detail->jenis_kelompok				= $_POST['penerima_jp_detail_jenis_kelompok'][$i][$j];
							$pjp_detail->unit_id					= $_POST['penerima_jp_detail_unit_id'][$i][$j];
							$pjp_detail->pegawai_id					= $_POST['penerima_jp_detail_pegawai_id'][$i][$j];
							$pjp_detail->pasien_id					= $_POST['penerima_jp_detail_pasien_id'][$i][$j];
							$pjp_detail->quantity					= $_POST['penerima_jp_detail_quantity'][$i][$j];
							$pjp_detail->proporsi					= $_POST['penerima_jp_detail_jumlah_proporsi'][$i][$j];
							$pjp_detail->langsung					= $_POST['penerima_jp_detail_jumlah_langsung'][$i][$j];
							$pjp_detail->kebersamaan				= $_POST['penerima_jp_detail_jumlah_kebersamaan'][$i][$j];
							$pjp_detail->mode_edit					= DATA_MODE_ADD;
							$aPenerimaJPDetails[$penerima_jp_detail_id] = $pjp_detail;
						}
						else {
							$aPenerimaJPDetails[$penerima_jp_detail_id]->tanggal		= $_POST['penerima_jp_detail_tanggal'][$i][$j];
							$aPenerimaJPDetails[$penerima_jp_detail_id]->kelompok_id	= $_POST['penerima_jp_detail_kelompok_id'][$i][$j];
							$aPenerimaJPDetails[$penerima_jp_detail_id]->jenis_kelompok	= $_POST['penerima_jp_detail_jenis_kelompok'][$i][$j];
							$aPenerimaJPDetails[$penerima_jp_detail_id]->unit_id		= $_POST['penerima_jp_detail_unit_id'][$i][$j];
							$aPenerimaJPDetails[$penerima_jp_detail_id]->pegawai_id		= $_POST['penerima_jp_detail_pegawai_id'][$i][$j];
							$aPenerimaJPDetails[$penerima_jp_detail_id]->pasien_id		= $_POST['penerima_jp_detail_pasien_id'][$i][$j];
							$aPenerimaJPDetails[$penerima_jp_detail_id]->quantity		= $_POST['penerima_jp_detail_quantity'][$i][$j];
							$aPenerimaJPDetails[$penerima_jp_detail_id]->proporsi		= $_POST['penerima_jp_detail_jumlah_proporsi'][$i][$j];
							$aPenerimaJPDetails[$penerima_jp_detail_id]->langsung		= $_POST['penerima_jp_detail_jumlah_langsung'][$i][$j];
							$aPenerimaJPDetails[$penerima_jp_detail_id]->kebersamaan	= $_POST['penerima_jp_detail_jumlah_kebersamaan'][$i][$j];
							$aPenerimaJPDetails[$penerima_jp_detail_id]->mode_edit		= DATA_MODE_EDIT;
						}
					}
				}
				$aPembagianJasaDetails[$pembagian_jasa_detail_id]->details = $aPenerimaJPDetails;
				
            }
        }
		$pembagian_jasa->details = $aPembagianJasaDetails;
		
        return $pembagian_jasa;
    }
	
	public function export_to_excel_rpt_001() {
		$this->output->set_header("Content-type: application/vnd.ms-excel");
		$this->output->set_header("Content-disposition: attachment; filename=rpt_001.xls");
		$this->output->set_header("Pragma: no-cache");
		$this->output->set_header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		$this->output->set_header("Expires: 0");
		
		$aWheres = array();
		$aWheres["MONTH(insentif_pemda.tanggal)"] = $this->input->get('bulan');
		$aWheres["YEAR(insentif_pemda.tanggal)"] = $this->input->get('tahun');
			
		$datas = $this->Insentif_Pemda_Model->getAll(0, 0, array(), $aWheres, array());
		$this->data['data'] = $datas['data'];
		$this->data['bulan'] = $this->input->get('bulan');
		$this->data['tahun'] = $this->input->get('tahun');
		$this->load->view("pembagian_jasa/rpt_001", $this->data);
	}
	
	public function export_to_excel_rpt_002() {
		$this->output->set_header("Content-type: application/vnd.ms-excel");
		$this->output->set_header("Content-disposition: attachment; filename=rpt_002.xls");
		$this->output->set_header("Pragma: no-cache");
		$this->output->set_header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		$this->output->set_header("Expires: 0");
		
		$aWheres = array();
		$aWheres["MONTH(insentif_manajemen.tanggal)"] = $this->input->get('bulan');
		$aWheres["YEAR(insentif_manajemen.tanggal)"] = $this->input->get('tahun');
		
		$datas = $this->Insentif_Manajemen_Model->getAll(0, 0, array(), $aWheres, array());
		$this->data['data'] = $datas['data'];
		$this->data['bulan'] = $this->input->get('bulan');
		$this->data['tahun'] = $this->input->get('tahun');
		$this->load->view("pembagian_jasa/rpt_002", $this->data);
	}
	
	public function export_to_excel_rpt_003() {
		$this->output->set_header("Content-type: application/vnd.ms-excel");
		$this->output->set_header("Content-disposition: attachment; filename=rpt_003.xls");
		$this->output->set_header("Pragma: no-cache");
		$this->output->set_header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		$this->output->set_header("Expires: 0");
		
		$aWheres = array();
		$aWheres['penerima_jp_detail.pegawai_id >'] = 0;
		$aWheres["MONTH(penerima_jp_detail.tanggal)"] = $this->input->get('bulan');
		$aWheres["YEAR(penerima_jp_detail.tanggal)"] = $this->input->get('tahun');
		
		$datas = $this->Penerima_JP_Detail_Model->getAll(0, 0, array('nama' => 'ASC'), $aWheres, array());
		$this->data['data'] = $datas['data'];
		$this->data['bulan'] = $this->input->get('bulan');
		$this->data['tahun'] = $this->input->get('tahun');
		$this->load->view("pembagian_jasa/rpt_003", $this->data);
	}
	
	public function export_to_excel_rpt_004() {
		$this->output->set_header("Content-type: application/vnd.ms-excel");
		$this->output->set_header("Content-disposition: attachment; filename=rpt_004.xls");
		$this->output->set_header("Pragma: no-cache");
		$this->output->set_header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		$this->output->set_header("Expires: 0");
		
		$aWheres = array();
		$aWheres["MONTH(jasa_pelayanan.tanggal)"] = $this->input->get('bulan');
		$aWheres["YEAR(jasa_pelayanan.tanggal)"] = $this->input->get('tahun');
			
		$datas = $this->Jasa_Pelayanan_Model->getAll(0, 0, array('unit.nama' => 'ASC', 'jasa_pelayanan.tanggal' => 'ASC'), $aWheres, array());
		$this->data['data'] = $datas['data'];
		$this->data['bulan'] = $this->input->get('bulan');
		$this->data['tahun'] = $this->input->get('tahun');
		$this->load->view("pembagian_jasa/rpt_004", $this->data);
	}
	
}

/* End of file pembagian_jasa.php */
/* Location: ./application/modules/pembagian_jasa/pembagian_jasa.php */