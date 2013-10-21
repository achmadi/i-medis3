<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Pendaftaran Laboratorium.
 * 
 * @package Pendaftaran
 * @category Controller
 * @author Dadang Dana Suryana
 */
class Pendaftaran extends Admin_Controller {
	
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
			'field'		=> 'nama',
			'label'		=> 'Nama',
			'rules'		=> 'xss_clean|required'
		),
		array(
			'field'		=> 'jenis_kelamin',
			'label'		=> 'Jenis Kelamin',
			'rules'		=> 'xss_clean|required'
		),
		array(
			'field'		=> 'alamat_jalan',
			'label'		=> 'Jalan',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'alamat_rt',
			'label'		=> 'RT',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'alamat_rw',
			'label'		=> 'RW',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'provinsi_id',
			'label'		=> 'Provinsi',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'kabupaten_id',
			'label'		=> 'Kabupaten',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'kecamatan_id',
			'label'		=> 'Kecamatan',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'kelurahan_id',
			'label'		=> 'Kelurahan',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'kodepos',
			'label'		=> 'Kodepos',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'telepon',
			'label'		=> 'Telepon',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'tempat_lahir',
			'label'		=> 'Tempat Lahir',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'tanggal_lahir',
			'label'		=> 'Tanggal Lahir',
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
			'field'		=> 'agama_id',
			'label'		=> 'Agama',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'pendidikan_id',
			'label'		=> 'Pendidikan',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'pekerjaan_id',
			'label'		=> 'Pekerjaan',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'rujukan_id',
			'label'		=> 'Rujukan',
			'rules'		=> 'xss_clean|greater_than[0]'
		),
		array(
			'field'		=> 'cara_bayar_id',
			'label'		=> 'Cara Pembayaran',
			'rules'		=> 'xss_clean|greater_than[0]'
		),
		array(
			'field'		=> 'poliklinik_id',
			'label'		=> 'Poliklinik',
			'rules'		=> 'xss_clean|greater_than[0]'
		),
		array(
			'field'		=> 'dokter_id',
			'label'		=> 'Dokter',
			'rules'		=> 'xss_clean|greater_than[0]'
		),
		array(
			'field'		=> 'pj_nama',
			'label'		=> 'Nama Penanggung Jawab',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'pj_hubungan',
			'label'		=> 'Hubungan dengan Pasien',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'pj_pekerjaan_id',
			'label'		=> 'Pekerjaan',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'pj_alamat',
			'label'		=> 'Alamat Penanggung Jawab',
			'rules'		=> 'xss_clean'
		)
	);
	
	public function __construct() {
		parent::__construct();
		$this->load->language('welcome');
		
		$this->load->model('laboratorium/Pendaftaran_Lab_Model');
		$this->load->model('laboratorium/Pendaftaran_Lab_Detail_Model');
		$this->load->model('master/Pasien_Model');
		$this->load->model('master/Wilayah_Model');
		$this->load->model('master/Agama_Model');
		$this->load->model('master/Pendidikan_Model');
		$this->load->model('master/Pekerjaan_Model');
		$this->load->model('master/Rujukan_Model');
		$this->load->model('master/Cara_Bayar_Model');
		$this->load->model('master/Poliklinik_Model');
		$this->load->model('master/Poliklinik_Pegawai_Model');
		$this->load->model('master/Tarif_Pelayanan_Model');
		$this->load->model('master/Unit_Model');
		$this->load->model('master/Gedung_Model');
		
		$this->Pendaftaran_Lab_Model->delete_expire_no_register_from_queue();
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
        
        if ($this->input->post('Batal')) {
            redirect('laboratorium/pendaftaran', 'refresh');
        }
		
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $pendaftaran = $this->_getDataObject();
                if (isset($pendaftaran->pendaftaran_id) && $pendaftaran->pendaftaran_id > 0) {
                    $this->Pendaftaran_Lab_Model->update($pendaftaran);
                }
                else {
                    $this->Pendaftaran_Lab_Model->create($pendaftaran);
                }
				redirect('laboratorium/pendaftaran/index?print='.$id, 'refresh');
            }
        }
		
		if ($id) {
			$this->data['new_pasien'] = false;
			$pendaftaran = $this->Pendaftaran_Lab_Model->getBy(array('id' => $id));
			$pendaftaran->pendaftaran_id = $pendaftaran->id;
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
		
		$provinsis = $this->Wilayah_Model->getAll(0, 0, array('lft' => 'ASC'), array('jenis' => 1));
		$this->data['provinsi_list'] = $provinsis['data'];
		
		$agama = $this->Agama_Model->getAll(0, 0, array('ordering' => 'ASC'));
		$this->data['agama_list'] = $agama['data'];
		
		$pendidikan = $this->Pendidikan_Model->getAll(0, 0, array('id' => 'ASC'));
		$this->data['pendidikan_list'] = $pendidikan['data'];
		
		$pekerjaan = $this->Pekerjaan_Model->getAll(0, 0, array('id' => 'ASC'));
		$this->data['pekerjaan_list'] = $pekerjaan['data'];
		
		$rujukan = $this->Rujukan_Model->getAll(0);
		$this->data['rujukan_list'] = $rujukan['data'];
		
		$cara_bayar = $this->Cara_Bayar_Model->getAll(0, 0, array('id' => 'ASC'), array('jenis <>' => 0));
		$this->data['cara_bayar_list'] = $cara_bayar['data'];
		
		$polikliniks = $this->Poliklinik_Model->getAll(0);
		$this->data['poliklinik_list'] = $polikliniks['data'];
		
		$this->data['pj_hubungan_list'] = $this->Pendaftaran_Lab_Model->getHubunganDenganPasien();
		
		$gedungs = $this->Gedung_Model->getAll(0);
		$this->data['gedung_list'] = $gedungs['data'];
		
		$this->data['top_nav'] = "laboratorium/top_nav";
		$this->data['top_nav_selected'] = "Pendaftaran";
		$this->data['sub_nav'] = "laboratorium/sub_nav";
		$this->template->set_title('Pendaftaran Rawat Jalan')
					   ->set_css('datepicker')
					   ->set_css('dynatree/skin/ui.dynatree')
					   ->set_js('bootstrap-datepicker')
					   ->set_js('jquery.dataTables')
					   ->set_js('jquery.dynatree')
			           ->build('laboratorium/edit');
    }
	
	public function load_data_kegiatan_laboratorium() {
		$unit = $this->Unit_Model->getBy(array('jenis' => $this->config->item('ID_LABORATORIUM')));
		if ($unit) {
			$row = $this->Tarif_Pelayanan_Model->getBy(array('unit_id' => $unit->id, 'jenis' => 'Root'));
			$tree = $this->Tarif_Pelayanan_Model->build_tree($row->id);
		}
		else {
			$tree = array();
		}
		echo json_encode($tree);
	}
    
	public function browse($browse = 0) {
		$this->data['browse'] = $browse;
		$this->data['top_nav'] = "laboratorium/top_nav";
		$this->data['top_nav_selected'] = "Browse";
		$this->data['sub_nav'] = "laboratorium/sub_nav";
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
		$this->template->set_title('Pendaftaran Rawat Jalan')
					   ->set_css('alertify.core', array('id' => 'toggleCSS'))
					   ->set_js('jquery.dataTables')
					   ->set_js('FixedColumns.min')
					   ->set_js('alertify.min')
			           ->build('laboratorium/browse');
	}
	
	public function load_data($browse = 0) {
		
		$aColumns = array('tanggal', 'no_register', 'no_medrec', 'nama', 'jenis_kelamin', 'alamat_jalan', 'tanggal_lahir', 'agama', 'pendidikan', 'pekerjaan', 'rujukan', 'cara_bayar', 'poliklinik', 'dokter');
		
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
		$aWheres['pelayanan'] = false;
		switch ($browse) {
			case 0:
			case 1:
				$aWheres['batal'] = false;
				break;
			case 2:
				$aWheres['batal'] = true;
				break;
		}
		
		$pendaftaran = $this->Pendaftaran_Lab_Model->getAll($iLimit, $iOffset, $aOrders, $aWheres, $aLikes);
		
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
			$row[] = $tanggal;
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
			$row[] = $pendaftaran->rujukan;
			$row[] = $pendaftaran->cara_bayar;
			$row[] = $pendaftaran->poliklinik;
			$row[] = $pendaftaran->dokter;
			
			switch ($browse) {
				case 0:
				case 1:
					$action = "<a class=\"btn btn-info btn-mini\" href=\"".site_url("laboratorium/pendaftaran/edit/".$pendaftaran->id)."\" data-original-title=\"Edit\" title=\"Edit Pendaftaran Rawat Jalan\">Edit</a>&nbsp;";
					$action .= "<a class=\"delete-row btn btn-danger btn-mini\" href=\"#\" data-original-title=\"Hapus\" data-id=\"".$pendaftaran->id."\" title=\"Hapus Pendaftaran Rawat Jalan\">Hapus</a>&nbsp;";
					$action .= "<a class=\"batal-row btn btn-warning btn-mini\" href=\"#\" data-original-title=\"Batal\" data-id=\"".$pendaftaran->id."\" title=\"Batalkan Pendaftaran Rawat Jalan\">Batal</a>&nbsp;";
					break;
				case 2:
					$action = "<a class=\"delete-row btn btn-danger btn-mini\" href=\"#\" data-original-title=\"Hapus\" data-id=\"".$pendaftaran->id."\" title=\"Hapus Pendaftaran Rawat Jalan\">Hapus</a>&nbsp;";
					break;
			}
			
			
			
			$row[] = $action;
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	
	public function delete() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Pendaftaran_Lab_Model->delete($id);
		}
    }
	
	public function batal() {
 		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Pendaftaran_Lab_Model->batal($id);
		}
    }
	
	public function browse_pasien() {
		$this->load->view('laboratorium/browse_pasien');
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
					$aLikes[$aColumns[$i]] = mysql_real_escape_string($_GET['sSearch']);
				}
			}
		}
		if(isset($_GET['sSearch_0']) && $_GET['sSearch_0'] != "") {
			$aLikes['no_medrec'] = mysql_real_escape_string($_GET['sSearch_0']);
		}
		if(isset($_GET['sSearch_1']) && $_GET['sSearch_1'] != "") {
			$aLikes['nama'] = mysql_real_escape_string($_GET['sSearch_1']);
		}
		if(isset($_GET['sSearch_2']) && $_GET['sSearch_2'] != "") {
			$aLikes['jenis_kelamin'] = mysql_real_escape_string($_GET['sSearch_2']);
		}
		if(isset($_GET['sSearch_3']) && $_GET['sSearch_3'] != "") {
			$aLikes['alamat_jalan'] = mysql_real_escape_string($_GET['sSearch_3']);
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
	
	public function get_pasien_by_id($id) {
		$pasien = $this->Pasien_Model->getBy(array('id' => $id));
		$aPasien = get_object_vars($pasien);
		echo json_encode(array("pasien" => $aPasien));
	}
	
	public function doPrint($id) {
		$this->data['data'] = $this->Pendaftaran_Lab_Model->getBy(array('id' => $id));
		$this->load->view('laboratorium/pendaftaran/print', $this->data);
	}
	
	public function get_kabupaten() {
		$provinsi_id = $this->input->get('provinsi_id');
		$options = "<option value=\"0\" selected=\"selected\">- Pilih Kabupaten/Kota -</option>";
		if ($provinsi_id) {
			$data = $this->Wilayah_Model->getAll(0, 0, array('lft' => 'ASC'), array('parent_id' => $provinsi_id));
			$kabupatens = $data['data'];
			
			$kabupaten_id = $this->input->get('kabupaten_id') ? $this->input->get('kabupaten_id') : 0;
			
			foreach ($kabupatens as $kabupaten) {
				$continue = true;
				if ($kabupaten_id > 0) {
					if ($kabupaten->id == $kabupaten_id) {
						$options .= "<option value=\"{$kabupaten->id}\" selected=\"selected\">{$kabupaten->nama}</option>";
						$continue = false;
					}
				}
				if ($continue)
					$options .= "<option value=\"{$kabupaten->id}\">{$kabupaten->nama}</option>";
			}
		}
		echo $options;
	}
	
	public function get_kecamatan() {
		$kabupaten_id = $this->input->get('kabupaten_id');
		$options = "<option value=\"0\" selected=\"selected\">- Pilih Kecamatan -</option>";
		if ($kabupaten_id) {
			$data = $this->Wilayah_Model->getAll(0, 0, array('lft' => 'ASC'), array('parent_id' => $kabupaten_id));
			$kecamatans = $data['data'];
			
			$kecamatan_id = $this->input->get('kecamatan_id') ? $this->input->get('kecamatan_id') : 0;

			foreach ($kecamatans as $kecamatan) {
				$continue = true;
				if ($kecamatan_id > 0) {
					if ($kecamatan->id == $kecamatan_id) {
						$options .= "<option value=\"{$kecamatan->id}\" selected=\"selected\">{$kecamatan->nama}</option>";
						$continue = false;
					}
				}
				if ($continue)
					$options .= "<option value=\"{$kecamatan->id}\">{$kecamatan->nama}</option>";
			}
		}
		echo $options;
	}
	
	public function get_kelurahan() {
		$kecamatan_id = $this->input->get('kecamatan_id');
		$options = "<option value=\"0\" selected=\"selected\">- Pilih Kelurahan/Desa -</option>";
		if ($kecamatan_id) {
			$data = $this->Wilayah_Model->getAll(0, 0, array('lft' => 'ASC'), array('parent_id' => $kecamatan_id));
			$kelurahans = $data['data'];
			
			foreach ($kelurahans as $kelurahan) {
				$options .= "<option value=\"{$kelurahan->id}\">{$kelurahan->nama}</option>";
			}
		}
		echo $options;
	}
	
	public function get_dokter() {
		$poliklinik_id = $this->input->get('poliklinik_id');
		$datas = $this->Poliklinik_Pegawai_Model->getAll(0, 0, array('id' => 'ASC'), array('poliklinik_id' => $poliklinik_id));
		$dokters = $datas['data'];
		
		$dokter_id = $this->input->get('dokter_id') ? $this->input->get('dokter_id') : 0;
		
		$options = "<option value=\"0\" selected=\"selected\">- Pilih Dokter -</option>";
		foreach ($dokters as $dokter) {
			$continue = true;
			if ($dokter_id > 0) {
				if ($dokter->pegawai_id == $dokter_id) {
					$options .= "<option value=\"{$dokter->pegawai_id}\" selected=\"selected\">{$dokter->pegawai}</option>";
					$continue = false;
				}
			}
			if ($continue)
				$options .= "<option value=\"{$dokter->pegawai_id}\">{$dokter->pegawai}</option>";
		}
		echo $options;
	}
	
	public function laporan() {
		$this->data['title'] = "Laporan Laboratorium";
		$this->data['top_nav'] = "laboratorium/top_nav";
		$this->data['top_nav_selected'] = "Laporan";
		$this->data['sub_nav'] = "laboratorium/sub_nav3";
		$this->data['sub_nav_selected'] = "Laporan Laboratorium";
		$this->template->set_title('Laporan Laboratorium')
					   ->set_css('datepicker')
					   ->set_js('bootstrap-datepicker')
					   ->set_js('date-picker/date')
					   ->set_js('date-picker/daterangepicker')
					   ->set_js('jquery.dataTables')
			           ->build('laboratorium/browse_001');
	}
	
	private function _getEmptyDataObject() {
		$pendaftaran = new stdClass();
		$pendaftaran->id				= 0;
		$pendaftaran->tanggal			= get_current_date();
		$pendaftaran->no_register		= $this->_order_no_register();
		$pendaftaran->asal_pasien		= 0;
		$pendaftaran->pendaftaran_id	= 0;
		$pendaftaran->pasien_id			= 0;
		$pendaftaran->no_medrec			= '';
		$pendaftaran->umur_tahun		= 0;
		$pendaftaran->umur_bulan		= 0;
		$pendaftaran->umur_hari			= 0;
		$pendaftaran->rujukan_id		= 0;
		$pendaftaran->cara_bayar_id		= 0;
		$pendaftaran->poliklinik_id		= 0;
		$pendaftaran->dokter_id			= 0;
		$pendaftaran->pj_nama			= '';
		$pendaftaran->pj_hubungan		= 0;
		$pendaftaran->pj_pekerjaan_id	= 0;
		$pendaftaran->pj_alamat			= '';
		$pendaftaran->gedung_id			= 0;
		$pendaftaran->ruangan_id		= 0;
		$pendaftaran->bed_id			= 0;
		$pendaftaran->batal				= false;
		$pendaftaran->version			= 0;
        return $pendaftaran;
    }
	
    private function _getDataObject() {
		$this->input->post(NULL, TRUE);

        $pendaftaran = new stdClass();
		$pendaftaran->pasien_id		= $this->input->post('pasien_id') && ($this->input->post('pasien_id') > 0) ? $this->input->post('pasien_id') : 0;
		$pendaftaran->no_medrec		= $this->input->post('no_medrec');
		$pendaftaran->nama			= $this->input->post('nama');
		$pendaftaran->jenis_kelamin	= $this->input->post('jenis_kelamin');
		$pendaftaran->alamat_jalan	= $this->input->post('alamat_jalan');
		$pendaftaran->alamat_rt		= $this->input->post('alamat_rt');
		$pendaftaran->alamat_rw		= $this->input->post('alamat_rw');
		$pendaftaran->provinsi_id	= $this->input->post('provinsi_id');
		$pendaftaran->kabupaten_id	= $this->input->post('kabupaten_id');
		$pendaftaran->kecamatan_id	= $this->input->post('kecamatan_id');
		$pendaftaran->kelurahan_id	= $this->input->post('kelurahan_id');
		$pendaftaran->kodepos		= $this->input->post('kodepos');
		$pendaftaran->telepon		= $this->input->post('telepon');
		$pendaftaran->tempat_lahir	= $this->input->post('tempat_lahir');
		$pendaftaran->tanggal_lahir	= $this->input->post('tanggal_lahir');
		$pendaftaran->agama_id		= $this->input->post('agama_id');
		$pendaftaran->pendidikan_id	= $this->input->post('pendidikan_id');
		$pendaftaran->pekerjaan_id	= $this->input->post('pekerjaan_id');
		
		$pendaftaran->pendaftaran_id	= $this->input->post('pendaftaran_id') && ($this->input->post('pendaftaran_id') > 0) ? $this->input->post('pendaftaran_id') : 0;
        $pendaftaran->tanggal			= $this->input->post('tanggal');
		$pendaftaran->no_register		= $this->input->post('no_register');
		$pendaftaran->umur_tahun		= $this->input->post('umur_tahun');
		$pendaftaran->umur_bulan		= $this->input->post('umur_bulan');
		$pendaftaran->umur_hari			= $this->input->post('umur_hari');
		$pendaftaran->baru				= $this->input->post('new_pasien');
		$pendaftaran->rujukan_id		= $this->input->post('rujukan_id');
		$pendaftaran->cara_bayar_id		= $this->input->post('cara_bayar_id');
		$pendaftaran->poliklinik_id		= $this->input->post('poliklinik_id');
		$pendaftaran->dokter_id			= $this->input->post('dokter_id');
		$pendaftaran->pj_nama			= $this->input->post('pj_nama');
		$pendaftaran->pj_hubungan		= $this->input->post('pj_hubungan');
		$pendaftaran->pj_pekerjaan_id	= $this->input->post('pj_pekerjaan_id');
		$pendaftaran->pj_alamat			= $this->input->post('pj_alamat');
		
		$pendaftaran->version			= $this->input->post('version');
		
		$data = $this->Pendaftaran_Lab_Detail_Model->getAll(0, 0, array(), array(), array());
        $pendaftaran->pemeriksaans = $data['data'];
        
		$aPemeriksaans = array();
        foreach ($pendaftaran->pemeriksaans as $pemeriksaan) {
            if ($pemeriksaan->laboratorium_id != $pendaftaran->pendaftaran_id) {
                $pemeriksaan->laboratorium_id = $pendaftaran->pendaftaran_id;
            }
            $aPemeriksaans[$pemeriksaan->id] = $pemeriksaan;
            $aPemeriksaans[$pemeriksaan->id]->mode_edit = DATA_MODE_DELETE;
        }
        if (isset($_POST['permintaan_pemeriksaan'])) {
			$aPermintaan = explode(',', $_POST['permintaan_pemeriksaan']);
            for ($i = 0; $i < count($aPermintaan); $i++) {
                $permintaan_id = $aPermintaan[$i];
                if (!array_key_exists(intval($permintaan_id), $aPemeriksaans)) {
                    $permintaan = new StdClass();
					$permintaan->id = $pendaftaran->pendaftaran_id;
                    $permintaan->tarif_pelayanan_id = $permintaan_id;
					$permintaan->harga = '';
                    $permintaan->mode_edit = DATA_MODE_ADD;
                    $aPemeriksaans[$permintaan_id] = $permintaan;
                }
                else {
                    $aPemeriksaans[$permintaan_id]->tarif_pelayanan_id = $permintaan_id;
                    $aPemeriksaans[$permintaan_id]->mode_edit = DATA_MODE_EDIT;
                }
            }
        }
        $pendaftaran->pemeriksaans = $aPemeriksaans;
		
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
		$register = $this->session->userdata('register_no_register_lab');
		$register_id = $this->session->userdata('register_no_register_lab_id');
		// Jika sudah register hapus kemudian pesan
		if ($register) {
			$this->Pendaftaran_Lab_Model->delete_no_register_from_queue($register_id);
		}
		// Jika belum langsung pesan
		$no_register = $this->Pendaftaran_Lab_Model->get_no_register();
		$this->session->set_userdata('register_no_register_lab', TRUE);
		$this->session->set_userdata('register_no_register_lab_id', $no_register['no_register_queue_id']);
		return $no_register['no_register'];
	}
	
}

/* End of file pendaftaran.php */
/* Location: ./application/modules/laboratorium/pendaftaran.php */