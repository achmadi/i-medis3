<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * TODO:
 * 3. masukan umur keluar tanggal lahir
 * 4. kartu status umur pake th bl hr
 * 5. batal hilangkan di daftar kunjungan
 * 6. kasih fasilitas filter dan print kartu status di daftar kunjungan
 * 7. perbaiki report
 * 8. print tracer, kartu pendaftara, kartu pasien, kartu status
 */

/**
 * Pendaftaran Rawat Jalan.
 * 
 * @package Pendaftaran_IRJ
 * @category Controller
 * @author Dadang Dana Suryana
 */
class Pendaftaran_IRJ extends Admin_Controller {
	
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
			'field'		=> 'pendaftaran_id',
			'label'		=> 'Pendaftaran ID',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'pasien_id',
			'label'		=> 'Pasien ID',
			'rules'		=> 'xss_clean'
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
			'label'		=> 'Alamat',
			'rules'		=> 'xss_clean|required'
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
			'rules'		=> 'xss_clean|callback_cara_bayar_check'
		),
		array(
			'field'		=> 'no_asuransi',
			'label'		=> 'No. Asuransi',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'poliklinik_id',
			'label'		=> 'Poliklinik',
			'rules'		=> 'xss_clean|greater_than[0]'
		),
		array(
			'field'		=> 'dokter_id',
			'label'		=> 'Dokter',
			'rules'		=> 'xss_clean'
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
		
		$this->load->helper('option_helper');
		$this->load->model('Pendaftaran_IRJ_Model');
		$this->load->model('master/Pasien_Model');
		$this->load->model('master/Wilayah_Model');
		$this->load->model('master/Agama_Model');
		$this->load->model('master/Pendidikan_Model');
		$this->load->model('master/Pekerjaan_Model');
		$this->load->model('master/Rujukan_Model');
		$this->load->model('master/Cara_Bayar_Model');
		$this->load->model('master/Poliklinik_Model');
		$this->load->model('master/Poliklinik_Pegawai_Model');
		
		$this->Pendaftaran_IRJ_Model->delete_expire_no_register_from_queue();
		$this->Pendaftaran_IRJ_Model->delete_expire_no_medrec_from_queue();
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
            redirect('pendaftaran_irj', 'refresh');
        }
		
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $pendaftaran = $this->_getDataObject();
                if (isset($pendaftaran->pendaftaran_id) && $pendaftaran->pendaftaran_id > 0) {
                    $this->Pendaftaran_IRJ_Model->update($pendaftaran);
					redirect('pendaftaran_irj/browse/1', 'refresh');
                }
                else {
                    $id = $this->Pendaftaran_IRJ_Model->create($pendaftaran);
                }
				$baru = $this->input->post('baru');
				redirect('pendaftaran_irj/index?print='.$id.'&baru='.$baru, 'refresh');
            }
        }
		
		if ($id) {
			$pendaftaran = $this->Pendaftaran_IRJ_Model->getBy(array('pendaftaran_irj.id' => $id));
			$pendaftaran->pendaftaran_id = $pendaftaran->id;
		}
		else {
			$pendaftaran = $this->_getEmptyDataObject();
		}
		
		if ($this->input->get('print')) {
			$this->data['print'] = true;
			$this->data['print_id'] = $this->input->get('print');
			$this->data['print_baru'] = $this->input->get('baru');
		}
		else {
			$this->data['print'] = false;
			$this->data['print_id'] = 0;
			$this->data['print_baru'] = false;
		}
		
		$this->data['data'] = $pendaftaran;
		
		$provinsis = $this->Wilayah_Model->getAll(0, 0, array('lft' => 'ASC'), array('jenis' => 1));
		$this->data['provinsi_list'] = $provinsis['data'];
		
		$this->data['golongan_darah_list'] = $this->Pasien_Model->get_golongan_darah();
		
		$agama = $this->Agama_Model->getAll(0, 0, array('ordering' => 'ASC'));
		$this->data['agama_list'] = $agama['data'];
		
		$pendidikan = $this->Pendidikan_Model->getAll(0, 0, array('id' => 'ASC'));
		$this->data['pendidikan_list'] = $pendidikan['data'];
		
		$pekerjaan = $this->Pekerjaan_Model->getAll(0, 0, array('id' => 'ASC'));
		$this->data['pekerjaan_list'] = $pekerjaan['data'];
		
		$rujukan = $this->Rujukan_Model->getAll(0);
		$this->data['rujukan_list'] = $rujukan['data'];
		
		$cara_bayar = $this->Cara_Bayar_Model->getAll(0, 0, array('lft'=> 'ASC'), array('jenis' => 'Rincian'));
		$this->data['cara_bayar_list'] = $cara_bayar['data'];
		
		$polikliniks = $this->Poliklinik_Model->getAll(0);
		$this->data['poliklinik_list'] = $polikliniks['data'];
		
		$this->data['pj_hubungan_list'] = $this->Pendaftaran_IRJ_Model->getHubunganDenganPasien();
		
		$this->data['status_kawin_list'] = status_kawin();
		
		$this->data['top_nav'] = "pendaftaran_irj/top_nav";
		$this->data['top_nav_selected'] = "Pendaftaran";
		$this->data['sub_nav'] = "pendaftaran_irj/sub_nav";
		$this->template->set_title('Pendaftaran Rawat Jalan')
					   ->set_css('datepicker')
					   ->set_js('bootstrap-datepicker')
					   ->set_js('jquery.dataTables')
			           ->build('pendaftaran_irj/edit');
    }
	
	public function cara_bayar_check($value) {
		if ($value == '0|0') {
			$this->form_validation->set_message('cara_bayar_check', '%s diperlukan');
			return FALSE;
		}
		else {
			return TRUE;
		}
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
			$tanggal_awal = $this->Pendaftaran_IRJ_Model->get_tanggal_awal();
		}
		if ($tanggal_awal > $tanggal_current) {
			$tanggal_awal = $tanggal_current;
		}
		$this->data['tanggal_awal'] = $tanggal_awal;
		
		if ($this->input->get('tanggal_akhir')) {
			$tanggal_akhir = $this->input->post('tanggal_akhir');
		}
		else {
			$tanggal_akhir = $this->Pendaftaran_IRJ_Model->get_tanggal_akhir();
		}
		if ($tanggal_akhir < $tanggal_current) {
			$tanggal_akhir = $tanggal_current;
		}
		$this->data['tanggal_akhir'] = $tanggal_akhir;
		
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
		$aWheres['tanggal'] = $this->input->get('tanggal');
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
			$row[] = "<a href=\"".site_url("pendaftaran_irj/edit/".$pendaftaran->id)."\" data-original-title=\"Edit\" title=\"Edit Pendaftaran Rawat Jalan\" style=\"color: #0C9ABB\">".$tanggal."</a>&nbsp;";
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
					$action = "<a class=\"btn btn-info btn-mini\" href=\"".site_url("pendaftaran_irj/edit/".$pendaftaran->id)."\" data-original-title=\"Edit\" title=\"Edit Pendaftaran Rawat Jalan\">Edit</a>&nbsp;";
					//$action .= "<a class=\"delete-row btn btn-danger btn-mini\" href=\"#\" data-original-title=\"Hapus\" data-id=\"".$pendaftaran->id."\" title=\"Hapus Pendaftaran Rawat Jalan\">Hapus</a>&nbsp;";
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
	
	public function browse_daftar_pasien() {
		$this->data['top_nav'] = "pendaftaran_irj/top_nav";
		$this->data['top_nav_selected'] = "Browse Daftar Pasien";
		$this->data['sub_nav'] = "pendaftaran_irj/sub_nav3";
		$this->data['title'] = "Daftar Pasien";
		$this->data['sub_nav_selected'] = "Daftar Pasien";
		$this->template->set_title('Daftar Pasien')
					   ->set_css('alertify.core', array('id' => 'toggleCSS'))
					   ->set_js('jquery.dataTables')
					   ->set_js('alertify.min')
			           ->build('pendaftaran_irj/browse_daftar_pasien');
	}
	
	public function load_data_daftar_pasien() {
		
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
		
		/*
		 * Or Like
		 */
		$aOrLikes = array();
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
			for ($i = 0; $i < count($aColumns); $i++) {
				if (isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true") {
					switch ($aColumns[$i]) {
						case 'no_medrec':
							$aOrLikes['pasien.no_medrec'] = mysql_real_escape_string($_GET['sSearch']);
							break;
						case 'nama':
							$aOrLikes['pasien.nama'] = mysql_real_escape_string($_GET['sSearch']);
							break;
						case 'jenis_kelamin':
							$jenis_kelamin = 0;
							switch ($_GET['sSearch']) {
								case "Laki-laki":
									$jenis_kelamin = 1;
									break;
								case "Perempuan":
									$jenis_kelamin = 2;
									break;
							}
							$aOrLikes['pasien.jenis_kelamin'] = $jenis_kelamin;
							break;
						case 'alamat_jalan':
							$aOrLikes['pasien.alamat_jalan'] = mysql_real_escape_string($_GET['sSearch']);
							break;
						case 'tanggal_lahir':
							$aOrLikes['pasien.tanggal_lahir'] = mysql_real_escape_string($_GET['sSearch']);
							break;
					}
				}
			}
		}
		
		/*
		 * Where
		 */
		$aWheres = array();
		
		$pasiens = $this->Pasien_Model->getAll2($iLimit, $iOffset, $aOrders, $aWheres, array(), $aLikes, $aOrLikes);
		
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
			$row[] = "<a href=\"#\" class=\"edit-row\" data-id=\"".$pasien->id."\" style=\"color: #0C9ABB\">".$pasien->no_medrec."</a>";
			$row[] = $pasien->nama;
			$row[] = jenis_kelamin_descr($pasien->jenis_kelamin);
			$row[] = $pasien->alamat_jalan;
			$tanggal_lahir = strftime( "%d-%m-%Y", strtotime($pasien->tanggal_lahir));
			$row[] = $tanggal_lahir;
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	
	public function get_data_pasien() {
		$pasien_id = $this->input->get('pasien_id');
		
		$output = array();
		if ($pasien_id) {
			$pasien = $this->Pasien_Model->getBy(array('pasien.id' => $pasien_id));
			$output['data'] = array();
			$output['data']	= $pasien;
		}
		else {
			$output['data'] = array();
			$output['data']	= new stdClass();
		}
		$provinsis = $this->Wilayah_Model->getAll(0, 0, array('lft' => 'ASC'), array('jenis' => 1));
		$output['provinsi_list'] = $provinsis['data'];
		echo json_encode($output);
	}
	
	public function get_data_kabupaten() {
		$provinsi_id = $this->input->get('provinsi_id');
		$output = array();
		if ($provinsi_id) {
			$data = $this->Wilayah_Model->getAll(0, 0, array('lft' => 'ASC'), array('parent_id' => $provinsi_id));
			$output['kabupaten_list'] = $data['data'];
		}
		echo json_encode($output);
	}
	
	public function get_data_kecamatan() {
		$kabupaten_id = $this->input->get('kabupaten_id');
		$output = array();
		if ($kabupaten_id) {
			$data = $this->Wilayah_Model->getAll(0, 0, array('lft' => 'ASC'), array('parent_id' => $kabupaten_id));
			$output['kecamatan_list'] = $data['data'];
		}
		echo json_encode($output);
	}
	
	public function get_data_kelurahan() {
		$kecamatan_id = $this->input->get('kecamatan_id');
		$output = array();
		if ($kecamatan_id) {
			$data = $this->Wilayah_Model->getAll(0, 0, array('lft' => 'ASC'), array('parent_id' => $kecamatan_id));
			$output['kelurahan_list'] = $data['data'];
		}
		echo json_encode($output);
	}
	
	public function delete() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Pendaftaran_IRJ_Model->delete($id);
		}
    }
	
	public function batal() {
 		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Pendaftaran_IRJ_Model->batal($id);
		}
    }
	
	public function browse_pasien() {
		$this->load->view('pendaftaran_irj/browse_pasien');
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
		$sOrder = "pasien.no_medrec ASC";
		/*
		if (isset($_GET['iSortCol_0'])) {
			for ($i = 0; $i <intval($_GET['iSortingCols']); $i++) {
				if ($_GET['bSortable_'.intval($_GET['iSortCol_'.$i])] == "true") {
					$aOrders[$aColumns[intval($_GET['iSortCol_'.$i])]] = $_GET['sSortDir_'.$i] === 'asc' ? 'asc' : 'desc';
				}
			}
		}
		 * 
		 */
		if (!empty($sOrder)) {
			$sOrder = "ORDER BY ".$sOrder;
		}
		
		/*
		 * Where
		 */
		$sWhere = "";
		
		/*
		 * Like
		 */
		$aLikes = array();
		if(isset($_GET['sSearch_0']) && $_GET['sSearch_0'] != "") {
			$aLikes[] = "pasien.no_medrec LIKE '%".mysql_real_escape_string($_GET['sSearch_0'])."%'";
		}
		if(isset($_GET['sSearch_1']) && $_GET['sSearch_1'] != "") {
			$aLikes[] = "pasien.nama LIKE '%".mysql_real_escape_string($_GET['sSearch_1'])."%'";
		}
		if(isset($_GET['sSearch_2']) && $_GET['sSearch_2'] != "") {
			$aLikes[] = "pasien.jenis_kelamin LIKE '%".mysql_real_escape_string($_GET['sSearch_2'])."%'";
		}
		if(isset($_GET['sSearch_3']) && $_GET['sSearch_3'] != "") {
			$aLikes[] = "pasien.alamat_jalan LIKE '%".mysql_real_escape_string($_GET['sSearch_3'])."%'";
		}
		if(isset($_GET['sSearch_4']) && $_GET['sSearch_4'] != "") {
			$aLikes[] = "pasien.tanggal_lahir LIKE '%".mysql_real_escape_string($_GET['sSearch_4'])."%'";
		}
		if (count($aLikes) > 0) {
			$sLike = "(".implode(' AND ', $aLikes).")";
			$sWhere = !empty($sWhere) ? $sWhere." AND ".$sLike : "WHERE ".$sLike;
		}
		
		$pasiens = $this->Pasien_Model->getAll2($iLimit, $iOffset, $sOrder, $sWhere);
		
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
		$id = $this->input->get('pasien_id');
		$pasien = $this->Pasien_Model->getBy(array('pasien.id' => $id));
		$aPasien = get_object_vars($pasien);
		echo json_encode(array("pasien" => $aPasien));
	}
	
	public function get_pendaftaran_by_id() {
		$pendaftaran_id = $this->input->get('pendaftaran_id');
		$pendaftaran = $this->Pendaftaran_IRJ_Model->getBy(array('pendaftaran_irj.id' => $pendaftaran_id));
		$aPendaftaran = get_object_vars($pendaftaran);
		echo json_encode(array("pendaftaran" => $aPendaftaran));
	}
	
	public function get_pendaftaran_by_no_medrec() {
		$no_medrec = $this->input->get('no_medrec');
		$pendaftaran = $this->Pendaftaran_IRJ_Model->getBy(array('pasien.no_medrec' => $no_medrec));
		$aPendaftaran = get_object_vars($pendaftaran);
		echo json_encode(array("pendaftaran" => $aPendaftaran));
	}
	
	public function get_pasien_by_no_medrec($no_medrec) {
		$pasien = $this->Pasien_Model->getBy(array('pasien.no_medrec' => $no_medrec));
		$output = array();
		if ($pasien) {
			$output['status'] = "ok";
			$output['pasien'] = $aPasien = get_object_vars($pasien);
		}
		else {
			$output['status'] = "failed";
		}
		echo json_encode($output);
	}
	
	public function get_jenis_cara_bayar() {
		$cara_bayar_id = $this->input->get('cara_bayar_id');
		$cara_bayar = $this->Cara_Bayar_Model->getBy(array('cara_bayar.id' => $cara_bayar_id));
		if ($cara_bayar) {
			$jenis_cara_bayar = $cara_bayar->jenis_cara_bayar;
		}
		else {
			$jenis_cara_bayar = 0;
		}
		echo json_encode(array("jenis_cara_bayar" => $jenis_cara_bayar));
	}
	
	public function print_form_001($id) {
		$this->data['data'] = $this->Pendaftaran_IRJ_Model->getBy(array('pendaftaran_irj.id' => $id));
		$this->load->view('pendaftaran_irj/form_001', $this->data);
	}
	
	public function print_form_002($id) {
		$this->data['data'] = $this->Pendaftaran_IRJ_Model->getBy(array('pendaftaran_irj.id' => $id));
		$this->load->view('pendaftaran_irj/form_002', $this->data);
	}
	
	public function print_form_003($id) {
		$data = $this->Pendaftaran_IRJ_Model->getBy(array('pendaftaran_irj.id' => $id));
		$data->jenis_kelamin = $data->jenis_kelamin == 1 ? "L" : "P";
		$data->status_kawin = status_kawin_descr($data->status_kawin);
		$this->data['data'] = $data;
		$this->load->view('pendaftaran_irj/form_003', $this->data);
	}
	
	public function print_form_004($id) {
		$this->data['data'] = $this->Pendaftaran_IRJ_Model->getBy(array('pendaftaran_irj.id' => $id));
		$this->load->view('pendaftaran_irj/form_004', $this->data);
	}
	
	public function print_form_005($id) {
		$this->data['data'] = $this->Pendaftaran_IRJ_Model->getBy(array('pendaftaran_irj.id' => $id));
		$this->load->view('pendaftaran_irj/form_005', $this->data);
	}
	
	public function get_provinsi() {
		$provinsi_id = $this->input->get('provinsi_id');
		$options = "<option value=\"0\" selected=\"selected\">- Pilih Provinsi -</option>";
		if ($provinsi_id) {
			$data = $this->Wilayah_Model->getAll(0, 0, array('lft' => 'ASC'), array('parent_id' => 1));
			$provinsis = $data['data'];
			
			foreach ($provinsis as $provinsi) {
				$continue = true;
				if ($provinsi_id > 0) {
					if ($provinsi->id == $provinsi_id) {
						$options .= "<option value=\"{$provinsi->id}\" selected=\"selected\">{$provinsi->nama}</option>";
						$continue = false;
					}
				}
				if ($continue)
					$options .= "<option value=\"{$provinsi->id}\">{$provinsi->nama}</option>";
			}
		}
		echo $options;
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
			
			$kelurahan_id = $this->input->get('kelurahan_id') ? $this->input->get('kelurahan_id') : 0;
			
			foreach ($kelurahans as $kelurahan) {
				$continue = true;
				if ($kelurahan_id > 0) {
					if ($kelurahan->id == $kelurahan_id) {
						$options .= "<option value=\"{$kelurahan->id}\" selected=\"selected\">{$kelurahan->nama}</option>";
						$continue = false;
					}
				}
				if ($continue)
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
	
	public function generate_medrec() {
		echo json_encode(array("no_medrec" => $this->_order_no_medrec()));
	}
	
	private function _getEmptyDataObject() {
		$pendaftaran = new stdClass();
		$pendaftaran->pasien_id					= 0;
		$pendaftaran->no_medrec					= '';
		$pendaftaran->nama						= '';
		$pendaftaran->jenis_kelamin				= -1;
		$pendaftaran->alamat_jalan				= '';
		$pendaftaran->provinsi_id				= get_option('provinsi_id');
		$pendaftaran->kabupaten_id				= get_option('kabupaten_id');
		$pendaftaran->kecamatan_id				= 0;
		$pendaftaran->kelurahan_id				= 0;
		$pendaftaran->kodepos					= '';
		$pendaftaran->telepon					= '';
		$pendaftaran->tempat_lahir				= '';
		$pendaftaran->tanggal_lahir				= '';
		$pendaftaran->golongan_darah			= '';
		$pendaftaran->agama_id					= 0;
		$pendaftaran->pendidikan_id				= 0;
		$pendaftaran->pekerjaan_id				= 0;
		$pendaftaran->no_asuransi				= '';
		$pendaftaran->peserta_asuransi			= '';
		$pendaftaran->status_kawin				= 0;
		$pendaftaran->nama_keluarga				= '';
		$pendaftaran->nama_pasangan				= '';
		$pendaftaran->nama_orang_tua			= '';
		$pendaftaran->pendidikan_orang_tua_id	= 0;
		$pendaftaran->pekerjaan_orang_tua_id	= 0;
		
		$pendaftaran->pendaftaran_id			= 0;
		$pendaftaran->tanggal					= get_current_date();
		$pendaftaran->no_register				= $this->_order_no_register();
		$pendaftaran->umur_tahun				= 0;
		$pendaftaran->umur_bulan				= 0;
		$pendaftaran->umur_hari					= 0;
		$pendaftaran->baru						= true;
		$pendaftaran->rujukan_id				= 0;
		$pendaftaran->cara_bayar_id				= 0;
		$pendaftaran->poliklinik_id				= 0;
		$pendaftaran->dokter_id					= 0;
		$pendaftaran->pj_nama					= '';
		$pendaftaran->pj_hubungan				= 0;
		$pendaftaran->pj_pekerjaan_id			= 0;
		$pendaftaran->pj_alamat					= '';
			
		$pendaftaran->version					= 0;
        return $pendaftaran;
    }
	
    private function _getDataObject() {
		$this->input->post(NULL, TRUE);

        $pendaftaran = new stdClass();
		$pendaftaran->pasien_id					= $this->input->post('pasien_id') && ($this->input->post('pasien_id') > 0) ? $this->input->post('pasien_id') : 0;
		$pendaftaran->no_medrec					= $this->input->post('no_medrec');
		$pendaftaran->nama						= $this->input->post('nama');
		$pendaftaran->jenis_kelamin				= $this->input->post('jenis_kelamin');
		$pendaftaran->alamat_jalan				= $this->input->post('alamat_jalan');
		$pendaftaran->provinsi_id				= $this->input->post('provinsi_id');
		$pendaftaran->kabupaten_id				= $this->input->post('kabupaten_id');
		$pendaftaran->kecamatan_id				= $this->input->post('kecamatan_id');
		$pendaftaran->kelurahan_id				= $this->input->post('kelurahan_id');
		$pendaftaran->kodepos					= $this->input->post('kodepos');
		$pendaftaran->telepon					= $this->input->post('telepon');
		$pendaftaran->tempat_lahir				= $this->input->post('tempat_lahir');
		$pendaftaran->tanggal_lahir				= $this->input->post('tanggal_lahir');
		$pendaftaran->golongan_darah			= $this->input->post('golongan_darah');
		$pendaftaran->agama_id					= $this->input->post('agama_id');
		$pendaftaran->pendidikan_id				= $this->input->post('pendidikan_id');
		$pendaftaran->pekerjaan_id				= $this->input->post('pekerjaan_id');
		$pendaftaran->no_asuransi				= $this->input->post('no_asuransi');
		$pendaftaran->peserta_asuransi			= $this->input->post('peserta_asuransi');
		$pendaftaran->status_kawin				= $this->input->post('status_kawin');
		$pendaftaran->nama_keluarga				= $this->input->post('nama_keluarga');
		$pendaftaran->nama_pasangan				= $this->input->post('nama_pasangan');
		$pendaftaran->nama_orang_tua			= $this->input->post('nama_orang_tua');
		$pendaftaran->pendidikan_orang_tua_id	= $this->input->post('pendidikan_orang_tua_id');
		$pendaftaran->pekerjaan_orang_tua_id	= $this->input->post('pekerjaan_orang_tua_id');
		
		$pendaftaran->pendaftaran_id	= $this->input->post('pendaftaran_id') && ($this->input->post('pendaftaran_id') > 0) ? $this->input->post('pendaftaran_id') : 0;
        $pendaftaran->tanggal			= $this->input->post('tanggal');
		$pendaftaran->no_register		= $this->input->post('no_register');
		$pendaftaran->umur_tahun		= $this->input->post('umur_tahun');
		$pendaftaran->umur_bulan		= $this->input->post('umur_bulan');
		$pendaftaran->umur_hari			= $this->input->post('umur_hari');
		$pendaftaran->baru				= $this->input->post('baru');
		$pendaftaran->rujukan_id		= $this->input->post('rujukan_id');
		$cara_bayar = explode('|', $this->input->post('cara_bayar_id'));
		$pendaftaran->cara_bayar_id		= $cara_bayar[0];
		$pendaftaran->poliklinik_id		= $this->input->post('poliklinik_id');
		$pendaftaran->dokter_id			= $this->input->post('dokter_id');
		$pendaftaran->pj_nama			= $this->input->post('pj_nama');
		$pendaftaran->pj_hubungan		= $this->input->post('pj_hubungan');
		$pendaftaran->pj_pekerjaan_id	= $this->input->post('pj_pekerjaan_id');
		$pendaftaran->pj_alamat			= $this->input->post('pj_alamat');
		
		$pendaftaran->version			= $this->input->post('version');
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
			$this->Pendaftaran_IRJ_Model->delete_no_register_from_queue($register_id);
		}
		// Jika belum langsung pesan
		$no_register = $this->Pendaftaran_IRJ_Model->get_no_register();
		$this->session->set_userdata('register_no_register_irj', TRUE);
		$this->session->set_userdata('register_no_register_irj_id', $no_register['no_register_queue_id']);
		return $no_register['no_register'];
	}
	
	private function _order_no_medrec() {
		// Cek apakah sudah register
		$register = $this->session->userdata('register_no_medrec');
		$register_id = $this->session->userdata('register_no_medrec_id');
		// Jika sudah register hapus kemudian pesan
		if ($register) {
			$this->Pendaftaran_IRJ_Model->delete_no_medrec_from_queue($register_id);
		}
		// Jika belum langsung pesan
		$no_register = $this->Pendaftaran_IRJ_Model->get_no_medrec();
		$this->session->set_userdata('register_no_medrec', TRUE);
		$this->session->set_userdata('register_no_medrec_id', $no_register['no_medrec_queue_id']);
		return $no_register['no_medrec'];
	}
	
	public function laporan($rpt = "") {
		if ($this->input->post('report_range1')) {
			$this->data['tanggal_dari'] = $this->input->post('tanggal_dari');
			$this->data['tanggal_sampai'] = $this->input->post('tanggal_sampai');
		}
		else {
			//
		}
		$this->data['title'] = "Laporan Rawat Jalan";
		$this->data['top_nav'] = "pendaftaran_irj/top_nav";
		$this->data['top_nav_selected'] = "Laporan";
		$this->data['sub_nav'] = "pendaftaran_irj/sub_nav";
		$this->data['sub_nav_selected'] = "Laporan";
		if ($rpt == "") {
			$rpt = "browse_001";
		}
		$this->data['laporan'] = $rpt;
		$this->template->set_title('Laporan Rawat Jalan')
					   ->set_css('datepicker')
					   ->set_js('bootstrap-datepicker')
					   ->set_js('date-picker/date')
					   ->set_js('date-picker/daterangepicker')
					   ->set_js('jquery.dataTables')
			           ->build('pendaftaran_irj/'.$rpt);
	}
	
	public function load_data_report($laporan = "") {
		
		switch($laporan) {
			case "browse_001":
				$aColumns = array('klinik', 'l', 'p', 'total');
				break;
			case "browse_002":
				$aColumns = array('cara_bayar', 'l', 'p', 'total');
				break;
			case "browse_003":
				$aColumns = array('wilayah', 'l', 'p', 'total');
				break;
			case "browse_004":
				$aColumns = array('kunjungan', 'l', 'p', 'total');
				break;
			case "browse_005":
				$aColumns = array('no_medrec', 'nama', 'alamat_jalan', 'agama');
				break;
			case "browse_006":
				$aColumns = array('no_medrec', 'nama', 'alamat_jalan', 'agama');
				break;
			case "browse_007":
				$aColumns = array('no_medrec', 'nama', 'alamat_jalan', 'agama');
				break;
		}
		
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
		/*
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
		*/
		
		switch($laporan) {
			case "browse_001":
				$reports = $this->Pendaftaran_IRJ_Model->get_kunjungan_per_klinik($iLimit, $iOffset, $aOrders, $aWheres, $aLikes);
				break;
			case "browse_002":
				$reports = $this->Pendaftaran_IRJ_Model->get_kunjungan_per_cara_bayar($iLimit, $iOffset, $aOrders, $aWheres, $aLikes);
				break;
			case "browse_003":
				$reports = $this->Pendaftaran_IRJ_Model->get_kunjungan_per_wilayah($iLimit, $iOffset, $aOrders, $aWheres, $aLikes);
				break;
			case "browse_004":
				$reports = $this->Pendaftaran_IRJ_Model->get_kunjungan_per_cara_kunjungan($iLimit, $iOffset, $aOrders, $aWheres, $aLikes);
				break;
			case "browse_005":
				$reports = $this->Pendaftaran_IRJ_Model->get_buku_pasien_register($iLimit, $iOffset, $aOrders, $aWheres, $aLikes);
				break;
			case "browse_006":
				$reports = $this->Pendaftaran_IRJ_Model->get_kartu_rekam_medis($iLimit, $iOffset, $aOrders, $aWheres, $aLikes);
				break;
			case "browse_007":
				$reports = $this->Pendaftaran_IRJ_Model->get_bank_nomor_rekam_medis($iLimit, $iOffset, $aOrders, $aWheres, $aLikes);
				break;
		}
		
		$rResult = $reports['data'];
		$iFilteredTotal = $reports['total_rows'];
		$iTotal = $reports['total_rows'];
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		foreach ($rResult as $report) {
			$row = array();
			
			switch($laporan) {
				case "browse_001":
					$row[] = $report->klinik;
					$row[] = $report->l;
					$row[] = $report->p;
					$row[] = $report->total;
					break;
				case "browse_002":
					$row[] = $report->cara_bayar;
					$row[] = $report->l;
					$row[] = $report->p;
					$row[] = $report->total;
					break;
				case "browse_003":
					$row[] = $report->wilayah;
					$row[] = $report->l;
					$row[] = $report->p;
					$row[] = $report->total;
					break;
				case "browse_004":
					$row[] = $report->kunjungan == 1 ? "Baru" : "Lama";
					$row[] = $report->l;
					$row[] = $report->p;
					$row[] = $report->total;
					break;
				case "browse_005":
					$row[] = $report->no_medrec;
					$row[] = $report->nama;
					$row[] = $report->alamat_jalan;
					$row[] = $report->agama;
					$row[] = $report->pendidikan;
					$row[] = $report->pekerjaan;
					break;
				case "browse_006":
					$row[] = $report->no_medrec;
					$row[] = $report->nama;
					$row[] = $report->alamat_jalan;
					$row[] = $report->agama;
					$row[] = $report->pendidikan;
					$row[] = $report->pekerjaan;
					break;
				case "browse_007":
					$row[] = $report->no_medrec;
					$row[] = $report->nama;
					$row[] = $report->alamat_jalan;
					$row[] = $report->agama;
					$row[] = $report->pendidikan;
					$row[] = $report->pekerjaan;
					break;
			}
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	
	public function to_print($rpt = "") {
		//$this->data['tgl_dari'] = $_REQUEST['tanggal_dari'];
		//$this->data['tgl_sampai'] = $_REQUEST['tanggal_sampai'];
		switch($rpt) {
			case "browse_001":
				$data = $this->Pendaftaran_IRJ_Model->get_kunjungan_per_klinik(0);
				break;
			case "browse_002":
				$data = $this->Pendaftaran_IRJ_Model->get_kunjungan_per_cara_bayar(0);
				break;
			case "browse_003":
				$data = $this->Pendaftaran_IRJ_Model->get_kunjungan_per_wilayah(0);
				break;
			case "browse_004":
				$data = $this->Pendaftaran_IRJ_Model->get_kunjungan_per_cara_kunjungan(0);
				break;
			case "browse_005":
				$data = $this->Pendaftaran_IRJ_Model->get_buku_pasien_register(0);
				break;
			case "browse_006":
				$data = $this->Pendaftaran_IRJ_Model->get_kartu_rekam_medis(0);
				break;
			case "browse_007":
				$data = $this->Pendaftaran_IRJ_Model->get_bank_nomor_rekam_medis(0);
				break;
		}
		$this->data['data'] = $data['data'];
		
		switch ($rpt) {
			case 'browse_001':
				$rpt = 'rpt_001';
				break;
			case 'browse_002':
				$rpt = 'rpt_002';
				break;
			case 'browse_003':
				$rpt = 'rpt_003';
				break;
			case 'browse_004':
				$rpt = 'rpt_004';
				break;
			case 'browse_005':
				$rpt = 'rpt_005';
				break;
			case 'browse_006':
				$rpt = 'rpt_006';
				break;
			case 'browse_007':
				$rpt = 'rpt_007';
				break;
		}
		
		$this->load->view('pendaftaran_irj/'.$rpt, $this->data);
	}
	
	public function to_excel($rpt = "") {
		//$this->data['tgl_dari'] = $_REQUEST['tanggal_dari'];
		//$this->data['tgl_sampai'] = $_REQUEST['tanggal_sampai'];
		switch($rpt) {
			case "browse_001":
				$data = $this->Pendaftaran_IRJ_Model->get_kunjungan_per_klinik(0);
				break;
			case "browse_002":
				$data = $this->Pendaftaran_IRJ_Model->get_kunjungan_per_cara_bayar(0);
				break;
			case "browse_003":
				$data = $this->Pendaftaran_IRJ_Model->get_kunjungan_per_wilayah(0);
				break;
			case "browse_004":
				$data = $this->Pendaftaran_IRJ_Model->get_kunjungan_per_cara_kunjungan(0);
				break;
			case "browse_005":
				$data = $this->Pendaftaran_IRJ_Model->get_buku_pasien_register(0);
				break;
			case "browse_006":
				$data = $this->Pendaftaran_IRJ_Model->get_kartu_rekam_medis(0);
				break;
			case "browse_007":
				$data = $this->Pendaftaran_IRJ_Model->get_bank_nomor_rekam_medis(0);
				break;
		}
		$this->data['data'] = $data['data'];
		
		switch ($rpt) {
			case 'browse_001':
				$rpt = 'rpt_001';
				break;
			case 'browse_002':
				$rpt = 'rpt_002';
				break;
			case 'browse_003':
				$rpt = 'rpt_003';
				break;
			case 'browse_004':
				$rpt = 'rpt_004';
				break;
			case 'browse_005':
				$rpt = 'rpt_005';
				break;
			case 'browse_006':
				$rpt = 'rpt_006';
				break;
			case 'browse_007':
				$rpt = 'rpt_007';
				break;
		}
		
		$this->output->set_header("Content-type: application/vnd.ms-excel");
		$this->output->set_header("Content-disposition: attachment; filename=".$rpt.".xls");
		$this->output->set_header("Pragma: no-cache");
		$this->output->set_header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		$this->output->set_header("Expires: 0");
		
		$this->load->view('pendaftaran_irj/'.$rpt, $this->data);
    }
	
}

/* End of file pendaftaran_irj.php */
/* Location: ./application/modules/pendaftaran_irj/pendaftaran_irj.php */