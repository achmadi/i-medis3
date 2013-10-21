<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * TODO:
 * 1. Mask
 */

/**
 * Show welcome message.
 * 
 * @package Pendaftaran_IRJ
 * @category Controller
 * @author Dadang Dana Suryana
 */
class Pendaftaran extends ADMIN_Controller {
	
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
		),
		array(
			'field'		=> 'no_medrec',
			'label'		=> 'No. Medrec',
			'rules'		=> 'xss_clean|required'
		),
		array(
			'field'		=> 'pasien_id',
			'label'		=> 'Pasien ID',
			'rules'		=> 'xss_clean|required'
		),
		array(
			'field'		=> 'pendaftaran_id',
			'label'		=> 'Pendaftaran ID',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'umur_tahun',
			'label'		=> 'Umur Tahun',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'umur_bulan',
			'label'		=> 'Umur Bulan',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'umur_hari',
			'label'		=> 'Umur Hari',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'gedung_id',
			'label'		=> 'Gedung/Bangsal',
			'rules'		=> 'xss_clean|greater_than[0]'
		),
		array(
			'field'		=> 'ruangan_id',
			'label'		=> 'Ruangan/Kamar',
			'rules'		=> 'xss_clean|greater_than[0]'
		),
		array(
			'field'		=> 'bed_id',
			'label'		=> 'Bed',
			'rules'		=> 'xss_clean|greater_than[0]'
		),
		array(
			'field'		=> 'cara_masuk',
			'label'		=> 'Cara Masuk',
			'rules'		=> 'xss_clean'
		)
	);
	
	public function __construct() {
		parent::__construct();
		$this->load->language('welcome');
		
		$this->load->model('Pendaftaran_RI_Model');
		$this->load->model('master/Pasien_Model');
		$this->load->model('master/Wilayah_Model');
		$this->load->model('master/Agama_Model');
		$this->load->model('master/Pendidikan_Model');
		$this->load->model('master/Pekerjaan_Model');
		$this->load->model('master/Cara_Bayar_Model');
		$this->load->model('master/Pegawai_Model');
		$this->load->model('master/Gedung_Model');
		$this->load->model('master/Ruangan_Model');
		$this->load->model('master/Bed_Model');
		
		$this->load->model('pendaftaran_irj/Pendaftaran_IRJ_Model');
		$this->load->model('pendaftaran_igd/Pendaftaran_IGD_Model');
		
		$this->Pendaftaran_RI_Model->delete_expire_no_register_from_queue();
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
        $this->form_validation->set_error_delimiters('<div class="span12 error" style="color: #CC0000;">', '</div>');
		$this->form_validation->set_message('required', 'Field %s diperlukan');
		$this->form_validation->set_message('greater_than', 'Field %s diperlukan');
		$this->form_validation->set_message('less_then', 'Field %s diperlukan');
        $this->form_validation->set_rules($this->form);
        
        if ($this->input->post('batal')) {
            redirect('tp2ri/pendaftaran', 'refresh');
        }
		
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $pendaftaran = $this->_getDataObject();
                if (isset($pendaftaran->id) && $pendaftaran->id > 0) {
                    $id = $pendaftaran->id;
					$this->Pendaftaran_RI_Model->update($pendaftaran);
                }
                else {
                    $id = $this->Pendaftaran_RI_Model->create($pendaftaran);
                }
				redirect('tp2ri/pendaftaran/index?print='.$id, 'refresh');
            }
        }
		
		if ($id) {
			$pendaftaran = $this->Pendaftaran_RI_Model->getBy(array('pendaftaran_ri.id' => $id));
		}
		else {
			$pendaftaran = $this->_getEmptyDataObject();
		}
		
		if ($this->input->get('print')) {
			$this->data['print'] = true;
			$this->data['print_id'] = $this->input->get('print');
		}
		else {
			$this->data['print'] = false;
			$this->data['print_id'] = 0;
		}
		
		$this->data['data'] = $pendaftaran;
		
		$gedungs = $this->Gedung_Model->getAll(0);
		$this->data['gedung_list'] = $gedungs['data'];
		
		//$this->data['union'] = $this->Pendaftaran_RI_Model->getAllPendaftaranIRJ_IGD();
		
		$this->data['top_nav'] = "tp2ri/top_nav";
		$this->data['top_nav_selected'] = "Pendaftaran";
		$this->data['sub_nav'] = "tp2ri/pendaftaran/sub_nav";
		$this->template->set_title('Pendaftaran Rawat Inap')
					   ->set_css('datepicker')
					   ->set_js('bootstrap-datepicker')
					   ->set_js('jquery.dataTables')
			           ->build('tp2ri/pendaftaran/edit');
    }
    
	public function browse($browse = 0) {
		if ($this->input->post('direction')) {
			$direction = $this->input->post('direction');
		}
		else {
			$direction = 0;
		}
		if ($this->input->post('tanggal_current')) {
			$tanggal_current = $this->input->post('tanggal_current');
		}
		else {
			$tanggal_current = get_current_date();
		}
		switch (intval($direction)) {
			case 1:
				$tanggal_current = date('Y-m-d', strtotime($tanggal_current. ' - 1 days'));
				break;
			case 2:
				$tanggal_current = date('Y-m-d', strtotime($tanggal_current. ' + 1 days'));
				break;
		}
		$this->data['tanggal_current'] = $tanggal_current;
		
		if ($this->input->post('tanggal_awal')) {
			$tanggal_awal = $this->input->post('tanggal_awal');
		}
		else {
			$tanggal_awal = $this->Pendaftaran_RI_Model->get_tanggal_awal();
		}
		if ($tanggal_awal > $tanggal_current) {
			$tanggal_awal = $tanggal_current;
		}
		$this->data['tanggal_awal'] = $tanggal_awal;
		
		if ($this->input->get('tanggal_akhir')) {
			$tanggal_akhir = $this->input->post('tanggal_akhir');
		}
		else {
			$tanggal_akhir = $this->Pendaftaran_RI_Model->get_tanggal_akhir();
		}
		if ($tanggal_akhir < $tanggal_current) {
			$tanggal_akhir = $tanggal_current;
		}
		$this->data['tanggal_akhir'] = $tanggal_akhir;
		
		$this->data['browse'] = $browse;
		
		$this->data['top_nav'] = "tp2ri/top_nav";
		$this->data['top_nav_selected'] = "Browse";
		$this->data['sub_nav'] = "tp2ri/pendaftaran/sub_nav";
		switch ($browse) {
			case 1:
				$selected = "Browse1";
				$title = "Daftar Kunjungan";
				break;
			case 2:
				$selected = "Browse2";
				$title = "Daftar Kunjungan yang Dibatalkan";
				break;
		}
		$this->data['title'] = $title;
		$this->data['sub_nav_selected'] = $selected;
		$this->template->set_title('Pendaftaran Rawat Inap')
					   ->set_css('alertify.core', array('id' => 'toggleCSS'))
					   ->set_js('jquery.dataTables')
					   ->set_js('FixedColumns.min')
					   ->set_js('alertify.min')
			           ->build('tp2ri/pendaftaran/browse');
	}
	
	public function load_data() {
		
		$aColumns = array('tanggal', 'no_register', 'no_medrec', 'nama', 'jenis_kelamin', 'alamat_jalan', 'tanggal_lahir', 'agama', 'pendidikan', 'pekerjaan', 'cara_bayar', 'bed');
		
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
		$aLikes['pendaftaran_ri.tanggal'] = $this->input->get('tanggal');
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
		//$aWheres['tanggal'] = $this->input->get('tanggal');
		
		$pendaftaran = $this->Pendaftaran_RI_Model->getAll($iLimit, $iOffset, $aOrders, $aWheres, $aLikes);
		
		$rResult = $pendaftaran['data'];
		$iFilteredTotal = $pendaftaran['total_rows'];
		$iTotal = $pendaftaran['total_rows'];
		
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
			$tanggal = strftime( "%d-%m-%Y %H:%M:%S", strtotime($pendaftaran->tanggal));
			$row[] = "<a href=\"".site_url("tp2ri/pendaftaran/edit/".$pendaftaran->id)."\" data-original-title=\"Edit\" title=\"Edit Pendaftaran Rawat Inap\" style=\"color: #0C9ABB\">".$tanggal."</a>";
			$row[] = $pendaftaran->no_register;
			$row[] = $pendaftaran->no_medrec;
			$row[] = $pendaftaran->nama;
			$row[] = jenis_kelamin_descr($pendaftaran->jenis_kelamin);
			$row[] = $pendaftaran->alamat_jalan;
			$tanggal_lahir = strftime( "%d-%m-%Y", strtotime($pendaftaran->tanggal_lahir));
			$row[] = $tanggal_lahir;
			$row[] = $pendaftaran->agama;
			$row[] = $pendaftaran->pendidikan;
			$row[] = $pendaftaran->pekerjaan;
			$row[] = $pendaftaran->cara_bayar;
			$row[] = $pendaftaran->dokter;
			$row[] = $pendaftaran->bed;
			
			$action = "<a id=\"".$pendaftaran->id."\" class=\"delete-row btn btn-danger btn-mini\" href=\"#\" data-original-title=\"Hapus\" title=\"Hapus Pendaftaran Rawat Jalan\">Hapus</a>&nbsp;";
			$action .= "<a class=\"btn btn-warning btn-mini\" href=\"".site_url("tp2ri/pendaftaran/batal/".$pendaftaran->id)."\" data-original-title=\"Batal\" title=\"Batalkan Pendaftaran Rawat Jalan\">Batal</a>&nbsp;";
			
			$row[] = $action;
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	
	public function delete($id) {
 		$this->Pendaftaran_RI_Model->delete($id);
		redirect('pendaftaran_irj/view', 'refresh');
    }
	
	public function browse_pasien() {
		$this->data['instalasi'] = $this->input->get('instalasi');
		$this->load->view('tp2ri/pendaftaran/browse_pasien', $this->data);
	}
	
	public function load_data_pasien_rj() {
		
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
		$aWheres = array('tindak_lanjut' => $this->config->item('ID_TINDAK_LANJUT_PERAWATAN_PULANG'));
		
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
		
		$pendaftarans = $this->Pendaftaran_IRJ_Model->getAllPasien($iLimit, $iOffset, $aOrders, $aWheres, $aLikes);
		
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
	
	public function load_data_pasien_rd() {
		
		$aColumns = array('no_medrec', 'nama', 'jenis_kelamin', 'alamat_jalan', 'telepon');
		
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
		$aWheres = array('pendaftaran_igd.tindak_lanjut' => $this->config->item('ID_TINDAK_LANJUT_PERAWATAN_PULANG'));
		
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
		
		$pasiens = $this->Pendaftaran_IGD_Model->getAllPasien($iLimit, $iOffset, $aOrders, $aWheres, $aLikes);
		
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
			$row[] = "<a href=\"#\" onclick=\"window.select_pasien(".$pasien->id.", 2);return false;\">".$pasien->no_medrec."</a>";
			$row[] = $pasien->nama;
			$row[] = $pasien->jenis_kelamin;
			$row[] = $pasien->alamat_jalan;
			$row[] = "";
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	
	public function print_form_001() {
		$id = $this->input->get('id');
		$this->data['data'] = $this->Pendaftaran_RI_Model->getBy(array('pendaftaran_ri.id' => $id));
		$this->load->view('tp2ri/pendaftaran/form_001', $this->data);
	}
	
	public function get_gedung_by_id($id) {
		$gedung = $this->Gedung_Model->getBy(array('id' => $id));
		$aGedung = get_object_vars($gedung);
		echo json_encode(array("gedung" => $aGedung));
	}
	
	public function get_ruangan() {
		$gedung_id = $this->input->get('gedung_id');
		$options = "<option value=\"0\" selected=\"selected\">- Pilih Ruangan/Kamar -</option>";
		if ($gedung_id) {
			$ruangans = $this->Ruangan_Model->getAll(0, 0, array('id' => 'ASC'), array('gedung_id' => $gedung_id));
			$ruangans = $ruangans['data'];
			
			foreach ($ruangans as $ruangan) {
				$options .= "<option value=\"{$ruangan->id}\">{$ruangan->nama}</option>";
			}
		}
		echo $options;
	}
	
	public function get_ruangan_by_id($id) {
		$ruangan = $this->Ruangan_Model->getBy(array('id' => $id));
		$aRuangan = get_object_vars($ruangan);
		echo json_encode(array("ruangan" => $aRuangan));
	}
	
	public function get_bed_by_id($id) {
		$bed = $this->Bed_Model->getBy(array('id' => $id));
		$aBed = get_object_vars($bed);
		echo json_encode(array("bed" => $aBed));
	}
	
	public function get_bed() {
		$ruangan_id = $this->input->get('ruangan_id');
		$options = "<option value=\"0\" selected=\"selected\">- Pilih Bed -</option>";
		if ($ruangan_id) {
			$bed = $this->Bed_Model->getAll(0, 0, array('id' => 'ASC'), array('ruangan_id' => $ruangan_id, 'status' => $this->config->item('ID_STATUS_BED_KOSONG')));
			$beds = $bed['data'];
			
			foreach ($beds as $bed) {
				$options .= "<option value=\"{$bed->id}\">{$bed->nama}</option>";
			}
		}
		echo $options;
	}
	
	private function _getEmptyDataObject() {
		$pendaftaran = new stdClass();
		$pendaftaran->id				= 0;
		$pendaftaran->tanggal			= get_current_date();
		$pendaftaran->no_register		= $this->_order_no_register();
		$pendaftaran->pasien_id			= 0;
		$pendaftaran->pendaftaran_id	= 0;
		$pendaftaran->no_medrec			= '';
		$pendaftaran->umur_tahun		= 0;
		$pendaftaran->umur_bulan		= 0;
		$pendaftaran->umur_hari			= 0;
		$pendaftaran->rujukan_id		= 0;
		$pendaftaran->cara_bayar_id		= 0;
		$pendaftaran->dokter_id			= 0;
		$pendaftaran->cara_masuk		= 1;
		$pendaftaran->gedung_id			= 0;
		$pendaftaran->ruangan_id		= 0;
		$pendaftaran->bed_id			= 0;
        return $pendaftaran;
    }
	
    private function _getDataObject() {
		$this->input->post(NULL, TRUE);
		
		$pendaftaran = new stdClass();
		$pendaftaran->id				= $this->input->post('id') && ($this->input->post('id') > 0) ? $this->input->post('id') : 0;
        $pendaftaran->tanggal			= $this->input->post('tanggal');
		$pendaftaran->no_register		= $this->input->post('no_register');
		$pendaftaran->pasien_id			= $this->input->post('pasien_id');
		$pendaftaran->pendaftaran_id	= $this->input->post('pendaftaran_id');
		$pendaftaran->umur_tahun		= $this->input->post('umur_tahun');
		$pendaftaran->umur_bulan		= $this->input->post('umur_bulan');
		$pendaftaran->umur_hari			= $this->input->post('umur_hari');
		$pendaftaran->rujukan_id		= $this->input->post('rujukan_id');
		$pendaftaran->cara_bayar_id		= $this->input->post('cara_bayar_id');
		$pendaftaran->dokter_id			= $this->input->post('dokter_id');
		$pendaftaran->cara_masuk		= $this->input->post('cara_masuk');
		$pendaftaran->gedung_id			= $this->input->post('gedung_id');
		$pendaftaran->ruangan_id		= $this->input->post('ruangan_id');
		$pendaftaran->bed_id			= $this->input->post('bed_id');
        return $pendaftaran;
    }
	
	private function _getAgeDifference($start_date, $end_date) {
		list($start_year, $start_month, $start_date) = explode('-', $start_date);
		list($current_year, $current_month, $current_date) = explode('-', $end_date);
		
		$result = array(
			'year'	=> 0,
			'month'	=> 0,
			'day'	=> 0
		);
	 
		/** days of each month **/
		for ($x = 1 ; $x <= 12 ; $x++) {
			 $dim[$x] = date('t', mktime(0, 0, 0, $x, 1, date('Y')));
		}
	 
		/** calculate differences **/
		$m = $current_month - $start_month;
		$d = $current_date - $start_date;
		$y = $current_year - $start_year;
		
		/** if the start day is ahead of the end day **/
		if ($d < 0) {
			$today_day = $current_date + $dim[intval($current_month)];
			$today_month = $current_month - 1;
			$d = $today_day - $start_date;
			$m = $today_month - $start_month;
			if (($today_month - $start_month) < 0) {
				$today_month += 12;
				$today_year = $current_year - 1;
				$m = $today_month - $start_month;
				$y = $today_year - $start_year;
			}
		}
	 
		/** if start month is ahead of the end month **/
		if($m < 0) {
			$today_month = $current_month + 12;
			$today_year = $current_year - 1;
			$m = $today_month - $start_month;
			$y = $today_year - $start_year;
		}
	 
		/** Calculate dates **/
		if ($y < 0) {
			$result['year'] = 0;
			$result['month'] = 0;
			$result['day'] = 0;
		} else {
			switch ($y) {
				case 0:
					$result['year'] = 0;
					break;
				case 1:
					$result['year'] = $y;
					break;
				default:
					$result['year'] = $y;
			}
			switch($m) {
				case 0:
					$result['month'] = 0;
					break;
				case 1:
					$result['month'] = $m;
					break;
				default:
					$result['month'] = $m;
			}
			switch($d) {
				case 0:
					$result['day'] = 0;
					break;
				case 1:
					$result['day'] = $d;
					break;
				default:
					$result['day'] = $d;
			}
		}
		return $result;
	}
	
	private function _order_no_register() {
		// Cek apakah sudah register
		$register = $this->session->userdata('register_no_register_ri');
		$register_id = $this->session->userdata('register_no_register_ri_id');
		// Jika sudah register hapus kemudian pesan
		if ($register) {
			$this->Pendaftaran_RI_Model->delete_no_register_from_queue($register_id);
		}
		// Jika belum langsung pesan
		$no_register = $this->Pendaftaran_RI_Model->get_no_register();
		$this->session->set_userdata('register_no_register_ri', TRUE);
		$this->session->set_userdata('register_no_register_ri_id', $no_register['no_register_queue_id']);
		return $no_register['no_register'];
	}
	
}

/* End of file pendaftaran.php */
/* Location: ./application/modules/tp2ri/pendaftaran.php */