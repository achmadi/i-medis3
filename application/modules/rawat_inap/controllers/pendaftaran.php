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
			'field'		=> 'unit_id',
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
		
		$this->load->model('Pendaftaran_Model');
		/*
		$this->load->model('master/Pasien_Model');
		$this->load->model('master/Wilayah_Model');
		$this->load->model('master/Agama_Model');
		$this->load->model('master/Pendidikan_Model');
		$this->load->model('master/Pekerjaan_Model');
		$this->load->model('master/Rujukan_Model');
		$this->load->model('master/Cara_Bayar_Model');
		$this->load->model('master/Unit_Model');
		$this->load->model('master/Unit_Dokter_Model');
		*/
		//$this->Pendaftaran_Model->delete_expire_no_register_from_queue();
		
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
        
        if ($this->input->post('Reset')) {
            redirect('pendaftaran_irj', 'refresh');
        }
		
		if ($this->input->post('check_no_medrec')) {
            $no_medrec = $_POST['no_medrec'];
            if ($no_medrec) {
                $pasien = $this->Pasien_Model->getBy(array('id', $no_medrec));
            }
        }
		else {
			$this->data['check_no_medrec'] = false;
		}
		
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $pendaftaran = $this->_getDataObject();
                if (isset($pendaftaran->id) && $pendaftaran->id > 0) {
                    $this->Pendaftaran_IRJ_Model->update($pendaftaran);
                }
                else {
                    $this->Pendaftaran_IRJ_Model->create($pendaftaran);
                }
				redirect('pendaftaran_irj/index?print='.$id, 'refresh');
            }
        }
		
		if ($id) {
			$this->data['set_pasien'] = true;
			$this->data['pasien_baru'] = false;
			$pendaftaran = $this->Pendaftaran_IRJ_Model->getBy(array('id' => $id));
		}
		else {
			$pendaftaran = $this->_getEmptyDataObject();
			if (isset($pasien)) {
				if ($pasien) {
					$pendaftaran_irj->pasien_id		= $pasien->id;
					$pendaftaran_irj->no_medrec		= $pasien->no_medrec;
					$pendaftaran_irj->nama			= $pasien->nama;
					$pendaftaran_irj->jenis_kelamin	= $pasien->jenis_kelamin;
					$pendaftaran_irj->alamat		= $pasien->alamat;
					$pendaftaran_irj->tempat_lahir	= $pasien->tempat_lahir;
					$pendaftaran_irj->tanggal_lahir	= $pasien->tanggal_lahir;
					$pendaftaran_irj->ortu_suami	= $pasien->ortu_suami;
					$date_difference = $this->_getAgeDifference($pasien->tanggal_lahir, date("Y-m-d"));
					$pendaftaran_irj->umur_tahun	= $date_difference['year'];
					$pendaftaran_irj->umur_bulan	= $date_difference['month'];
					$pendaftaran_irj->umur_hari		= $date_difference['day'];
					$pendaftaran_irj->baru			= false;

					$this->data['set_pasien'] = true;
					$this->data['pasien_baru'] = false;
				}
				else {
					$this->data['set_pasien'] = false;
					$this->data['pasien_baru'] = true;
				}
			}
			else {
				$this->data['set_pasien'] = false;
				$this->data['pasien_baru'] = true;
			}
		}
		
		if (isset($_GET['print']))
			$this->data['print'] = true;
		else
			$this->data['print'] = false;
		
		$this->data['data'] = $pendaftaran;
		/*
		$this->data['provinsi_list'] = $this->Wilayah_Model->getProvinsi();
		
		$agama = $this->Agama_Model->getAll(0, 0, array('id' => 'ASC'));
		$this->data['agama_list'] = $agama['data'];
		
		$pendidikan = $this->Pendidikan_Model->getAll(0, 0, array('id' => 'ASC'));
		$this->data['pendidikan_list'] = $pendidikan['data'];
		
		$pekerjaan = $this->Pekerjaan_Model->getAll(0, 0, array('id' => 'ASC'));
		$this->data['pekerjaan_list'] = $pekerjaan['data'];
		
		$rujukan = $this->Rujukan_Model->getAll(0);
		$this->data['rujukan_list'] = $rujukan['data'];
		
		$cara_bayar = $this->Cara_Bayar_Model->getAll(0, 0, array('id' => 'ASC'));
		$this->data['cara_bayar_list'] = $cara_bayar['data'];
		
		$units = $this->Unit_Model->getAll(0);
		$this->data['poliklinik_list'] = $units['data'];
		
		$this->data['pj_hubungan_list'] = $this->Pendaftaran_IRJ_Model->getHubunganDenganPasien();
		*/
		if ($this->data['check_no_medrec']) {
            if (isset($_POST['jform']['no_medrec'])) {
                if ($_POST['jform']['no_medrec']) {
                    $pendaftaran_irj->no_medrec  = $_POST['jform']['no_medrec'];
                    $pendaftaran_irj->baru       = true;
                }
            }
        }
		
		$this->data['top_nav'] = "top_nav";
		$this->data['top_nav_selected'] = "Pendaftaran";
		$this->data['sub_nav'] = "pendaftaran/sub_nav";
		$this->template->set_title('Pendaftaran Rawat Jalan')
					   ->set_css('datepicker')
					   ->set_js('bootstrap-datepicker')
					   ->set_js('jquery.dataTables')
			           ->build('pendaftaran/edit');
    }
    
	public function browse($browse = 0) {
		$this->data['browse'] = $browse;
		$this->data['top_nav'] = "pendaftaran_irj/top_nav";
		$this->data['top_nav_selected'] = "Browse";
		$this->data['sub_nav'] = "pendaftaran_irj/sub_nav";
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
			           ->build('pendaftaran_irj/browse');
	}
	
	public function load_data($browse = 0) {
		
		$aColumns = array('tanggal', 'no_register', 'no_medrec', 'nama', 'jenis_kelamin', 'alamat_jalan', 'tanggal_lahir', 'agama', 'pendidikan', 'pekerjaan', 'rujukan', 'cara_bayar', 'unit', 'dokter');
		
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
		
		$pendaftaran = $this->Pendaftaran_IRJ_Model->getAll($iLimit, $iOffset, $aOrders, $aWheres, $aLikes);
		
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
			$row[] = $this->_getJenisKelaminDescr($pendaftaran->jenis_kelamin);
			$row[] = $pendaftaran->alamat_jalan;
			$tanggal_lahir = strftime( "%d-%m-%Y", strtotime($pendaftaran->tanggal_lahir));
			$row[] = $tanggal_lahir;
			$row[] = $pendaftaran->agama;
			$row[] = $pendaftaran->pendidikan;
			$row[] = $pendaftaran->pekerjaan;
			$row[] = $pendaftaran->rujukan;
			$row[] = $pendaftaran->cara_bayar;
			$row[] = $pendaftaran->unit;
			$row[] = $pendaftaran->dokter;
			
			$action = "<a class=\"btn btn-info btn-mini\" href=\"".site_url("pendaftaran_irj/edit/".$pendaftaran->id)."\" data-original-title=\"Edit\" title=\"Edit Pendaftaran Rawat Jalan\">Edit</a>&nbsp;";
			$action .= "<a id=\"".$pendaftaran->id."\" class=\"delete-row btn btn-danger btn-mini\" href=\"#\" data-original-title=\"Hapus\" title=\"Hapus Pendaftaran Rawat Jalan\">Hapus</a>&nbsp;";
			$action .= "<a class=\"btn btn-warning btn-mini\" href=\"".site_url("pendaftaran_irj/batal/".$pendaftaran->id)."\" data-original-title=\"Batal\" title=\"Batalkan Pendaftaran Rawat Jalan\">Batal</a>&nbsp;";
			
			$row[] = $action;
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	
	public function delete($id) {
 		$this->Pendaftaran_IRJ_Model->delete($id);
		redirect('pendaftaran_irj/view', 'refresh');
    }
	
	public function batal($id) {
 		//$this->Pendaftaran_IRJ_Model->delete($id);
		redirect('pendaftaran_irj/view', 'refresh');
    }
	
	public function load_data_pasien() {
		
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
			//$row[] = "<a href=\"#\" onclick=\"window.select_pasien(".$pasien->id.");\">".$pasien->no_medrec."</a>";
			$row[] = $pasien->no_medrec;
			$row[] = $pasien->nama;
			$row[] = $pasien->jenis_kelamin;
			$row[] = $pasien->alamat_jalan;
			$row[] = $pasien->telepon;
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	
	public function doPrint($id) {
		$this->data['data'] = $this->Pendaftaran_IRJ_Model->getBy(array('id', $id));
		$this->load->view('pendaftaran_irj/print', $this->data);
	}
	
	public function getById($id = 0) {
		$pasien = $this->Pendaftaran_IRJ_Model->getBy(array('id', $id));
		$aPasien = array(
			'id'					=> $pasien->pendaftaran_id, 
			'no_medrec'				=> $pasien->no_medrec,
			'nama'					=> $pasien->nama,
			'jenis_kelamin'			=> $pasien->jenis_kelamin,
			'alamat'				=> $pasien->alamat,
			'tempat_lahir'			=> $pasien->tempat_lahir,
			'tanggal_lahir'			=> $pasien->tanggal_lahir,
			'umur_tahun'			=> $pasien->umur_tahun,
			'umur_bulan'			=> $pasien->umur_bulan,
			'umur_hari'				=> $pasien->umur_hari,
			'ortu_suami'			=> $pasien->ortu_suami,
			'tanggal_pendaftaran'	=> $pasien->tanggal_pendaftaran,
			'cara_bayar'			=> $pasien->cara_bayar,
			'poliklinik'			=> $pasien->poliklinik,
			'dokter'				=> $pasien->dokter
		);
		echo json_encode(array("pasien" => $aPasien));
	}
	
	public function get_pasien($id) {
		$pasien = $this->Pasien_Model->getBy(array('id' => $id));
		$aPasien = array(
			'id'					=> $pasien->id, 
			'no_medrec'				=> $pasien->no_medrec,
			'nama'					=> $pasien->nama,
			'jenis_kelamin'			=> $pasien->jenis_kelamin,
			'alamat'				=> $pasien->alamat,
			'tempat_lahir'			=> $pasien->tempat_lahir,
			'tanggal_lahir'			=> $pasien->tanggal_lahir
		);
		echo json_encode(array("pasien" => $aPasien));
	}
	
	public function get_kabupaten() {
		$provinsi_id = $_GET['provinsi_id'];
		$options = "<option value=\"0\" selected=\"selected\">- Pilih Kabupaten/Kota -</option>";
		if (provinsi_id) {
			$kabupatens = $this->Wilayah_Model->getKabupaten($provinsi_id);

			$kabupaten_id = isset($_GET['kabupaten_id']) ? $_GET['kabupaten_id'] : 0;
			
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
		$kabupaten_id = $_GET['kabupaten_id'];
		$options = "<option value=\"0\" selected=\"selected\">- Pilih Kecamatan -</option>";
		if ($kabupaten_id) {
			$kecamatans = $this->Wilayah_Model->getKecamatan($kabupaten_id);
			
			$kecamatan_id = isset($_GET['kecamatan_id']) ? $_GET['kecamatan_id'] : 0;

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
		$kecamatan_id = $_GET['kecamatan_id'];
		$options = "<option value=\"0\" selected=\"selected\">- Pilih Kelurahan/Desa -</option>";
		if ($kecamatan_id) {
			$kelurahans = $this->Wilayah_Model->getKelurahan($kecamatan_id);

			
			foreach ($kelurahans as $kelurahan) {
				$options .= "<option value=\"{$kelurahan->id}\">{$kelurahan->nama}</option>";
			}
		}
		echo $options;
	}
	
	public function get_dokter() {
		$unit_id = $_GET['poliklinik_id'];
		$dokters = $this->Poliklinik_Dokter_Model->getBy($unit_id);
		
		$dokter_id = isset($_GET['dokter_id']) ? $_GET['dokter_id'] : 0;

		$options = "<option value=\"0\" selected=\"selected\">- Pilih Dokter -</option>";
		foreach ($dokters as $dokter) {
			$continue = true;
			if ($dokter_id > 0) {
				if ($dokter->dokter_id == $dokter_id) {
					$options .= "<option value=\"{$dokter->dokter_id}\" selected=\"selected\">{$dokter->nama_dokter}</option>";
					$continue = false;
				}
			}
			if ($continue)
				$options .= "<option value=\"{$dokter->dokter_id}\">{$dokter->nama_dokter}</option>";
		}
		echo $options;
	}
	
	private function _getEmptyDataObject() {
		$pendaftaran = new stdClass();
		$pendaftaran->pasien_id		= 0;
		$pendaftaran->no_medrec		= '';
		$pendaftaran->nama			= '';
		$pendaftaran->jenis_kelamin	= -1;
		$pendaftaran->alamat_jalan	= '';
		$pendaftaran->alamat_rt		= '';
		$pendaftaran->alamat_rw		= '';
		$pendaftaran->provinsi_id	= 0;
		$pendaftaran->kabupaten_id	= 0;
		$pendaftaran->kecamatan_id	= 0;
		$pendaftaran->kelurahan_id	= 0;
		$pendaftaran->kodepos		= '';
		$pendaftaran->telepon		= '';
		$pendaftaran->tempat_lahir	= '';
		$pendaftaran->tanggal_lahir	= '';
		$pendaftaran->agama_id		= 0;
		$pendaftaran->pendidikan_id	= 0;
		$pendaftaran->pekerjaan_id	= 0;
		
		$timezone = "Asia/Pontianak";
		if (function_exists('date_default_timezone_set'))
			date_default_timezone_set($timezone);
		$current_date = date("Y-m-d H:i:s");
		
		$pendaftaran->pendaftaran_id	= 0;
		$pendaftaran->tanggal			= $current_date;
		$pendaftaran->no_register		= $this->_order_no_register();
		$pendaftaran->umur_tahun		= 0;
		$pendaftaran->umur_bulan		= 0;
		$pendaftaran->umur_hari			= 0;
		$pendaftaran->baru				= true;
		$pendaftaran->rujukan_id		= 0;
		$pendaftaran->cara_bayar_id		= 0;
		$pendaftaran->unit_id			= 0;
		$pendaftaran->dokter_id			= 0;
		$pendaftaran->pj_nama			= '';
		$pendaftaran->pj_hubungan		= 0;
		$pendaftaran->pj_pekerjaan_id	= 0;
		$pendaftaran->pj_alamat			= '';
        return $pendaftaran;
    }
	
    private function _getDataObject() {
		$this->input->post(NULL, TRUE);

        $pendaftaran = new stdClass();
		$pendaftaran->pasien_id		= isset($_POST['pasien_id']) && ($_POST['pasien_id'] > 0) ? $_POST['pasien_id'] : 0;
		$pendaftaran->no_medrec		= $_POST['no_medrec'];
		$pendaftaran->nama			= $_POST['nama'];
		$pendaftaran->jenis_kelamin	= $_POST['jenis_kelamin'];
		$pendaftaran->alamat_jalan	= $_POST['alamat_jalan'];
		$pendaftaran->alamat_rt		= $_POST['alamat_rt'];
		$pendaftaran->alamat_rw		= $_POST['alamat_rw'];
		$pendaftaran->provinsi_id	= $_POST['provinsi_id'];
		$pendaftaran->kabupaten_id	= $_POST['kabupaten_id'];
		$pendaftaran->kecamatan_id	= $_POST['kecamatan_id'];
		$pendaftaran->kelurahan_id	= $_POST['kelurahan_id'];
		$pendaftaran->kodepos		= $_POST['kodepos'];
		$pendaftaran->telepon		= $_POST['telepon'];
		$pendaftaran->tempat_lahir	= $_POST['tempat_lahir'];
		$pendaftaran->tanggal_lahir	= $_POST['tanggal_lahir'];
		$pendaftaran->agama_id		= $_POST['agama_id'];
		$pendaftaran->pendidikan_id	= $_POST['pendidikan_id'];
		$pendaftaran->pekerjaan_id	= $_POST['pekerjaan_id'];
		
		$pendaftaran->pendaftaran_id	= isset($_POST['pendaftaran_id']) && ($_POST['pendaftaran_id'] > 0) ? $_POST['pendaftaran_id'] : 0;
        $pendaftaran->tanggal			= $_POST['tanggal'];
		$pendaftaran->no_register		= $_POST['no_register'];
		$pendaftaran->umur_tahun		= $_POST['umur_tahun'];
		$pendaftaran->umur_bulan		= $_POST['umur_bulan'];
		$pendaftaran->umur_hari			= $_POST['umur_hari'];
		$pendaftaran->baru				= $_POST['baru'];
		$pendaftaran->rujukan_id		= $_POST['rujukan_id'];
		$pendaftaran->cara_bayar_id		= $_POST['cara_bayar_id'];
		$pendaftaran->unit_id			= $_POST['unit_id'];
		$pendaftaran->dokter_id			= $_POST['dokter_id'];
		$pendaftaran->pj_nama			= $_POST['pj_nama'];
		$pendaftaran->pj_hubungan		= $_POST['pj_hubungan'];
		$pendaftaran->pj_pekerjaan_id	= $_POST['pj_pekerjaan_id'];
		$pendaftaran->pj_alamat			= $_POST['pj_alamat'];
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
		$register = $this->session->userdata('register_no_register_irj');
		$register_id = $this->session->userdata('register_no_register_irj_id');
		// Jika sudah register hapus kemudian pesan
		if ($register) {
			$this->Pendaftaran_Model->delete_no_register_from_queue($register_id);
		}
		// Jika belum langsung pesan
		$no_register = $this->Pendaftaran_Model->get_no_register();
		$this->session->set_userdata('register_no_register_irj', TRUE);
		$this->session->set_userdata('register_no_register_irj_id', $no_register['no_register_queue_id']);
		return $no_register['no_register'];
	}
	
	private function _getJenisKelaminDescr($jk) {
		switch ($jk) {
			case 1:
				return "Laki-laki";
			case 2:
				return "Perempuan";
		}
	}
	
}

/* End of file pendaftaran.php */
/* Location: ./application/modules/pendaftaran_irj/pendaftaran.php */