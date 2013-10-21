<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Show welcome message.
 * 
 * @package App
 * @category Controller
 * @author Ardi Soebrata
 */
class Rawat_Inap extends Admin_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->language('welcome');
		$this->load->model('master/Unit_Model');
		$this->load->model('master/Tarif_Pelayanan_Model');
		$this->load->model('master/Gedung_Model');
		$this->load->model('master/Ruangan_Model');
		$this->load->model('master/Bed_Model');
		$this->load->model('tp2ri/Pendaftaran_RI_Model');
		$this->load->model('rawat_inap/Pelayanan_RI_Model');
		
		$this->load->model('master/Pasien_Model');
		$this->load->model('master/Pegawai_Model');
		
		$this->load->model('master/Tindakan_Model');
	}
	
	public function index() {
		$gedung = $this->Gedung_Model->getAll(0);
		if ($this->input->post('gedung_id', TRUE))
			$this->data['gedung'] = $this->Gedung_Model->getBy(array('id' => $this->input->post('gedung_id', TRUE)));
		else
			$this->data['gedung'] = $gedung['data'][0];
		$this->data['gedung_list'] = $gedung['data'];
		
		$this->data['session_id'] = $this->session->userdata('session_id');
		
		$this->data['top_nav'] = "top_nav";
		$this->data['top_nav_selected'] = "Rawat Inap";
		$this->data['sub_nav'] = "rawat_inap/rawat_inap/sub_nav1";
		$this->data['sub_nav_selected'] = "Bed";
		$this->template->set_title('Rawat inap')
					   ->set_css('datepicker')
					   ->set_js('jquery.validate.min')
					   ->set_js('additional-methods.min')
					   ->set_js('bootstrap-datepicker')
					   ->set_js('jquery.dataTables')
			           ->build('rawat_inap/rawat_inap/browse');
	}
	
	public function load_data() {
		
		$aColumns = array('nama_ruangan', 'nama', 'kelas');
		
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
		$aOrders = array('gedung.nama' => 'ASC', 'ruangan.nama' => 'ASC');
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
		if (isset($_GET['gedung_id'])) {
			$gedung = $this->Gedung_Model->getBy(array('id' => $_GET['gedung_id']));
			$aWheres['bed.gedung_id'] = $gedung->id;
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
		
		$beds = $this->Bed_Model->getAll($iLimit, $iOffset, $aOrders, $aWheres, $aLikes);
		
		$rResult = $beds['data'];
		$iFilteredTotal = $beds['total_rows'];
		$iTotal = $beds['total_rows'];
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		foreach ($rResult as $bed) {
			$row = array();
			if ($bed->status == $this->config->item('ID_STATUS_BED_ISI')) {
				$row[] = '<img class="bed_show_hide" src="'.base_url('assets/img/details_open.png').'">';
			}
			else {
				$row[] = "";
			}
			$row[] = $bed->ruangan;
			$row[] = $bed->nama;
			$row[] = $bed->kelas;
			switch ($bed->status) {
				case $this->config->item('ID_STATUS_BED_KOSONG'):
					$img = "<img src=\"".base_url('assets/img/empty.png')."\" alt=\"Isi\" title=\"Isi\">";
					break;
				case $this->config->item('ID_STATUS_BED_ISI'):
					$img = "<img src=\"".base_url('assets/img/full.png')."\" alt=\"Kosong\" title=\"Kosong\">";
					break;
				case $this->config->item('ID_STATUS_BED_CADANGAN'):
					$img = "<img src=\"".base_url('assets/img/temp.png')."\" alt=\"Sementara\" title=\"Sementara\">";
					break;
			}
			$row[] = $img;
			$row[] = is_null($bed->pelayanan_ri_id) ? 0 : $bed->pelayanan_ri_id;
			$row[] = is_null($bed->pasien_id) ? 0 : $bed->pasien_id;
			$row[] = is_null($bed->no_medrec) ? "" : $bed->no_medrec;
			$row[] = is_null($bed->nama_pasien) ? "" : $bed->nama_pasien;
			$row[] = is_null($bed->jenis_kelamin) ? "" : jenis_kelamin_descr($bed->jenis_kelamin);
			if ($bed->umur_tahun > 0)
				$umur = $bed->umur_tahun." tahun";
			elseif ($bed->umur_bulan > 0)
				$umur = $bed->umur_bulan." bulan";
			elseif ($bed->umur_hari > 0)
				$umur = $bed->umur_hari." hari";
			else
				$umur = 0;
			$row[] = $umur;
			$row[] = $bed->dokter_id;
			$row[] = is_null($bed->dokter) ? "" : $bed->dokter;
			
			$action = "<a class=\"btn btn-info btn-mini\" href=\"".site_url("master/agama/edit/".$bed->id)."\" data-original-title=\"Edit\">Edit</a>&nbsp;";
			$action .= "<a id=\"".$bed->id."\" class=\"delete-row btn btn-danger btn-mini\" href=\"#\" data-original-title=\"Hapus\">Hapus</a>";
			
			//$row[] = $action;
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	
	public function browse_konfirmasi() {
		$this->data['top_nav'] = "top_nav";
		$this->data['top_nav_selected'] = "Konfirmasi";
		$this->data['sub_nav'] = "rawat_inap/rawat_inap/sub_nav2";
		$this->data['sub_nav_selected'] = "Konfirmasi";
		$this->template->set_title('Rawat inap')
					   ->set_css('datepicker')
					   ->set_css('alertify.core', array('id' => 'toggleCSS'))
					   ->set_js('bootstrap-datepicker')	
					   ->set_js('jquery.dataTables')
					   ->set_js('jquery.validate.min')
			           ->build('rawat_inap/rawat_inap/browse_konfirmasi');
	}
	
	public function load_data_konfirmasi() {
		
		$aColumns = array('tanggal', 'no_register', 'no_medrec', 'nama', 'jenis_kelamin', 'cara_masuk', 'bed');
		
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
		$aWheres = array('konfirmasi' => false);
		
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
		
		$pendaftarans = $this->Pendaftaran_RI_Model->getAll($iLimit, $iOffset, $aOrders, $aWheres, $aLikes);
		
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
			$tanggal = strftime( "%d-%m-%Y %H:%M:%S", strtotime($pendaftaran->tanggal));
			$row[] = $tanggal;
			$row[] = $pendaftaran->no_register;
			$row[] = $pendaftaran->no_medrec;
			$row[] = $pendaftaran->nama;
			$row[] = jenis_kelamin_descr($pendaftaran->jenis_kelamin);
			$row[] = cara_masuk_descr($pendaftaran->cara_masuk);
			$row[] = $pendaftaran->bed;
			
			$action = "<a class=\"konfirmasi-row btn btn-info btn-mini\" href=\"#\" data-id=\"".$pendaftaran->id."\" >Konfirmasi</a>";
			
			$row[] = $action;
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	
	public function get_data_konfirmasi() {
		$pendaftaran_id = $this->input->get('pendaftaran_id');
		
		$output = array();
		if ($pendaftaran_id) {
			$pendaftaran = $this->Pendaftaran_RI_Model->getBy(array('pendaftaran_ri.id' => $pendaftaran_id));
			$output['data'] = array();
			$output['data']['pendaftaran_id']	= $pendaftaran->id;
			$output['data']['tanggal']			= $pendaftaran->tanggal;
			$output['data']['no_register']		= $pendaftaran->no_register;
			$output['data']['pasien_id']		= $pendaftaran->pasien_id;
			$output['data']['no_medrec']		= $pendaftaran->no_medrec;
			$output['data']['nama']				= $pendaftaran->nama;
			$output['data']['jenis_kelamin']	= jenis_kelamin_descr($pendaftaran->jenis_kelamin);
			$output['data']['cara_masuk']		= $pendaftaran->cara_masuk;
			$output['data']['disp_cara_masuk']	= cara_masuk_descr($pendaftaran->cara_masuk);
			$output['data']['umur_tahun']		= $pendaftaran->umur_tahun;
			$output['data']['umur_bulan']		= $pendaftaran->umur_bulan;
			$output['data']['umur_hari']		= $pendaftaran->umur_hari;
			$output['data']['rujukan_id']		= $pendaftaran->rujukan_id;
			$output['data']['cara_bayar_id']	= $pendaftaran->cara_bayar_id;
			$output['data']['dokter_id']		= $pendaftaran->dokter_id;
			$output['data']['gedung_id']		= $pendaftaran->gedung_id;
			$output['data']['gedung']			= $pendaftaran->gedung;
			$output['data']['ruangan_id']		= $pendaftaran->ruangan_id;
			$output['data']['bed_id']			= $pendaftaran->bed_id;
			$output['data']['konfirmasi']		= $pendaftaran->id;
		}
		else {
			$output['data'] = array();
			$output['data']['id']			= 0;
		}
		echo json_encode($output);
	}
	
	public function get_ruangan() {
		$gedung_id = $this->input->get('gedung_id');
		$output = array();
		if ($gedung_id) {
			$ruangans = $this->Ruangan_Model->getAll(0, 0, array('ruangan.nama' => 'ASC'), array('gedung_id' => $gedung_id));
			$output['ruangan_list'] = $ruangans['data'];
		}
		else {
			$output['ruangan_list'] = array();
		}
		echo json_encode($output);
	}
	
	public function get_bed() {
		$ruangan_id = $this->input->get('ruangan_id');
		$output = array();
		if ($ruangan_id) {
			$beds = $this->Bed_Model->getAll(0, 0, array('bed.nama' => 'ASC'), array('ruangan_id' => $ruangan_id));
			$output['bed_list'] = $beds['data'];
		}
		else {
			$output['bed_list'] = array();
		}
		echo json_encode($output);
	}
	
	public function konfirmasi() {
		$pelayanan = new stdClass();
		$pelayanan->id = 0;
		$pelayanan->tanggal					= get_current_date();
		$pelayanan->pendaftaran_id			= $this->input->post('pendaftaran_id');
		$pelayanan->pasien_id				= $this->input->post('pasien_id');
		$pelayanan->umur_tahun				= $this->input->post('umur_tahun');
		$pelayanan->umur_bulan				= $this->input->post('umur_bulan');
		$pelayanan->umur_hari				= $this->input->post('umur_hari');
		$pelayanan->bed_id					= $this->input->post('bed_id');
		$pelayanan->rujukan_id				= $this->input->post('rujukan_id');
		$pelayanan->cara_bayar_id           = $this->input->post('cara_bayar_id');
		$pelayanan->dokter_id				= $this->input->post('dokter_id');
		$pelayanan->cara_masuk				= $this->input->post('cara_masuk');
		$pelayanan->pindah_dari_tanggal		= null;
		$pelayanan->pindah_dari_bed_id		= 0;
		$pelayanan->pindah_ke_tanggal       = null;
		$pelayanan->pindah_ke_bed_id        = 0;
		$pelayanan->tanggal_keluar          = 0;
		$pelayanan->cara_pasien_keluar      = 0;
		$pelayanan->keadaan_pasien_keluar   = 0;
		$pelayanan->diagnosa_utama          = 0;
		$pelayanan->komplikasi              = 0;
		$pelayanan->sebab_luar_kecelakaan   = 0;
		$pelayanan->icd_x_id                = 0;
		$pelayanan->operasi_tindakan        = 0;
		$pelayanan->tanggal_tindakan_operasi= 0;
		$pelayanan->status_masuk            = 1;	// Pasien Masuk
		$pelayanan->status_keluar           = 0;
		$this->Pelayanan_RI_Model->create($pelayanan);
		echo "ok";
	}
	
	public function get_data_tindakan() {
		$pelayanan_ri_id = $this->input->get('pelayanan_ri_id');
		$pelayanan = $this->Pelayanan_RI_Model->getBy(array('pelayanan_ri.id' => $pelayanan_ri_id));
		
		$jenis = $this->config->item('ID_RAWAT_INAP');
		$unit = $this->Unit_Model->getBy(array('jenis' => $jenis));
		
		$output = array();
		$output['data'] = array();
		$output['data']['id']				= 0;
		$output['data']['unit_id']			= $unit->id;
		$output['data']['pelayanan_ri_id']	= $pelayanan->id;
		$output['data']['bed_id']			= $pelayanan->bed_id;
		$output['data']['tanggal']			= get_current_date();
		$output['data']['no_medrec']		= $pelayanan->no_medrec;
		$output['data']['nama']				= $pelayanan->nama;
		$output['data']['dokter_id']		= 0;
		$output['data']['tindakan_id']		= 0;
		$output['data']['keterangan']		= '';
		
		echo json_encode($output);
	}
	
	public function get_dokter() {
		$dokters = $this->Pegawai_Model->getAll(0, 0, array('pegawai.nama' => 'ASC'), array('kelompok_pegawai.jenis' => 1));
		$output = array();
		$output['dokter'] = $dokters['data'];
		echo json_encode($output);
	}
	
	public function get_tindakan() {
		$jenis = $this->config->item('ID_RAWAT_INAP');
		$unit = $this->Unit_Model->getBy(array('jenis' => $jenis));
		$row = $this->Tarif_Pelayanan_Model->getBy(array('unit_id' => $unit->id, 'jenis' => 'Root'));
		$tarif_pelayanans = $this->Tarif_Pelayanan_Model->get_tree($row->id, 0, 0, array('n.lft' => 'ASC'), array('n.jenis' => 'Rincian'));
		
		$output = array();
		$output['tindakan'] = $tarif_pelayanans['data'];
		echo json_encode($output);
	}
	
	public function simpan_tindakan() {
		$tindakan = new stdClass();
		$tindakan->id				= $this->input->post('tindakan_dlg_id');
		$tindakan->unit_id			= $this->input->post('tindakan_dlg_unit_id');
		$tindakan->pelayanan_ri_id	= $this->input->post('tindakan_dlg_pelayanan_ri_id');
		$tindakan->bed_id			= $this->input->post('tindakan_dlg_bed_id');
		$tindakan->tanggal			= $this->input->post('tanggal_tindakan');
		$tindakan->tindakan_id		= $this->input->post('tindakan_id');
		//$tindakan->icd_10_id		= $this->input->post('icd_10_id');
		$tindakan->dokter_id		= $this->input->post('dokter_id');
		$tindakan->keterangan		= $this->input->post('keterangan');
		$this->Tindakan_Model->create($tindakan);
		echo "ok";
	}
	
	public function get_data_pindah_bed() {
		$pelayanan_ri_id = $this->input->get('pelayanan_ri_id');
		$pelayanan = $this->Pelayanan_RI_Model->getBy(array('pelayanan_ri.id' => $pelayanan_ri_id));
		
		$output = array();
		$output['data'] = array();
		$output['data']['pelayanan_ri_id']	= $pelayanan->id;
		$output['data']['tanggal']			= get_current_date();
		$output['data']['no_medrec']		= $pelayanan->no_medrec;
		$output['data']['nama']				= $pelayanan->nama;
		$output['data']['bed_lama_id']		= $pelayanan->bed_id;
		$output['data']['bed_lama_bed']		= $pelayanan->bed;
		$output['data']['bed_lama_kelas']	= $pelayanan->kelas;
		
		$beds = $this->Bed_Model->getAll(0, 0, array('ruangan.nama' => 'ASC', 'bed.nama' => 'ASC'), array('bed.gedung_id' => $pelayanan->gedung_id, 'bed.status' => $this->config->item('ID_STATUS_BED_KOSONG')));
		$output['bed_list'] = $beds['data'];
		
		echo json_encode($output);
	}
	
	public function simpan_pindah_bed() {
		$bed = new stdClass();
		$bed->pelayanan_ri_id	= $this->input->post('pindah_bed_dlg_pelayanan_ri_id');
		$bed->bed_lama_id		= $this->input->post('pindah_bed_dlg_bed_lama_id');
		$bed->bed_baru_id		= $this->input->post('pindah_bed_dlg_bed_baru_id');
		$this->Pelayanan_RI_Model->pindah_bed($bed);
		echo "ok";
	}
	
	public function get_data_pindah_ruangan() {
		$pelayanan_ri_id = $this->input->get('pelayanan_ri_id');
		$pelayanan = $this->Pelayanan_RI_Model->getBy(array('pelayanan_ri.id' => $pelayanan_ri_id));
		
		$output = array();
		$output['data'] = array();
		$output['data']['id']				= 0;
		$output['data']['pelayanan_ri_id']	= $pelayanan->id;
		$output['data']['tanggal']			= get_current_date();
		$output['data']['no_medrec']		= $pelayanan->no_medrec;
		$output['data']['nama']				= $pelayanan->nama;
		$output['data']['gedung_id']		= $pelayanan->gedung_id;
		
		$gedungs = $this->Gedung_Model->getAll(0, 0, array('gedung.nama' => 'ASC'));
		$output['gedung_list'] = $gedungs['data'];
		
		echo json_encode($output);
	}
	
	public function get_data_checkout() {
		$pelayanan_ri_id = $this->input->get('pelayanan_ri_id');
		
		$output = array();
		if ($pelayanan_ri_id) {
			$pelayanan = $this->Pelayanan_RI_Model->getBy(array('pelayanan_ri.id' => $pelayanan_ri_id));
			$output['data'] = array();
			$output['data']['id']						= $pelayanan->id;
			$output['data']['bed_id']					= $pelayanan->bed_id;
			$output['data']['tanggal']					= get_current_date();
			$output['data']['no_medrec']				= $pelayanan->no_medrec;
			$output['data']['nama']						= $pelayanan->nama;
			$output['data']['keadaan_pasien_keluar']	= $pelayanan->keadaan_pasien_keluar;
			$output['data']['cara_pasien_keluar']		= $pelayanan->cara_pasien_keluar;
		}
		else {
			$output['data'] = array();
			$output['data']['id']						= 0;
			$output['data']['bed_id']					= 0;
			$output['data']['tanggal']					= '';
			$output['data']['no_medrec']				= '';
			$output['data']['nama']						= '';
			$output['data']['keadaan_pasien_keluar']	= 0;
			$output['data']['cara_pasien_keluar']		= 0;
		}
		
		$keadaan_pasien_keluars = get_keadaan_pasien_keluar();
		$aKeadaanPasienKeluar = array();
		foreach ($keadaan_pasien_keluars as $index => $keadaan_pasien_keluar) {
			$aKeadaanPasienKeluar[] = $keadaan_pasien_keluar . "|" . $index;
		}
		$output['data']['keadaan_pasien_keluar_list'] = $aKeadaanPasienKeluar;
		
		echo json_encode($output);
	}
	
	public function get_cara_pasien_keluar() {
		$keadaan_pasien_keluar = $this->input->get('keadaan_pasien_keluar');
		$output = array();
		if ($keadaan_pasien_keluar) {
			switch ($keadaan_pasien_keluar) {
				case 1:
					$cara_pasien_keluars = get_cara_pasien_keluar_hidup();
					break;
				case 2:
					$cara_pasien_keluars = get_cara_pasien_keluar_mati();
					break;
			}
			$aCaraPasienKeluar = array();
			foreach ($cara_pasien_keluars as $index => $cara_pasien_keluar) {
				$aCaraPasienKeluar[] = $cara_pasien_keluar . "|" . $index;
			}
			$output['data']['cara_pasien_keluar_list'] = $aCaraPasienKeluar;
		}
		echo json_encode($output);
	}
	
	public function checkout() {
		$checkout = new stdClass();
		$checkout->id						= $this->input->post('id');
		$checkout->bed_id					= $this->input->post('bed_id');
		$checkout->tanggal_keluar			= $this->input->post('tanggal_keluar');
		$checkout->keadaan_pasien_keluar	= $this->input->post('keadaan_pasien_keluar');
		$checkout->cara_pasien_keluar		= $this->input->post('cara_pasien_keluar');
		$checkout->status_keluar			= 1; // keluar
		$this->Pelayanan_RI_Model->checkout($checkout);
		echo "ok";
	}
	
	public function get_pasien_by_id($id) {
		$pasien = $this->Pasien_Model->getById($id);
		$aPasien = get_object_vars($pasien);
		echo json_encode(array("pasien" => $aPasien));
	}
	
	public function laporan($rpt = "") {
		$this->data['title'] = "Laporan";
		$this->data['top_nav'] = "rawat_inap/top_nav";
		$this->data['top_nav_selected'] = "Laporan";
		$this->data['sub_nav'] = "rawat_inap/rawat_inap/sub_nav3";
		$this->data['sub_nav_selected'] = "Laporan";
		$this->template->set_title('Laporan Rawat Jalan')
					   ->set_css('datepicker')
					   ->set_js('bootstrap-datepicker')
					   ->set_js('date-picker/date')
					   ->set_js('date-picker/daterangepicker')
					   ->set_js('jquery.dataTables')
			           ->build('rawat_inap/rawat_inap/browse_001');
	}
	

}

/* End of file rawat_inap.php */
/* Location: ./application/modules/rawat_inap/rawat_inap.php */