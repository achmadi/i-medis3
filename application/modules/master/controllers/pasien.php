<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pasien extends CI_Controller {
	
	protected $form = array(
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
			'field'		=> 'tempat_lahir',
			'label'		=> 'Tempat Lahir',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'tanggal_lahir',
			'label'		=> 'Tanggal Lahir',
			'rules'		=> 'xss_clean'
		),
	);
	
    public function __construct() {
		parent::__construct();
		$this->load->helper('option_helper');
		$this->load->model('master/Pasien_Model');
		$this->load->model('master/Wilayah_Model');
		$this->load->model('master/Agama_Model');
		$this->load->model('master/Pendidikan_Model');
		$this->load->model('master/Pekerjaan_Model');
		
		$this->Pasien_Model->delete_expire_no_medrec_from_queue();
	}
	
	public function index()	{
		$this->data['sub_nav'] = "master/pasien/sub_nav";
		$this->template->set_title('Pasien')
					   ->set_css('alertify.core', array('id' => 'toggleCSS'))
					   ->set_css('datepicker')
					   ->set_js('bootstrap-datepicker')
					   ->set_js('jquery.dataTables')
					   ->set_js('FixedColumns.min')
					   ->set_js('alertify.min')
					   ->set_js('jquery.validate.min')
			           ->build('master/pasien/browse');
	}
	
	public function load_data() {
		
		$aColumns = array('no_medrec', 'nama', 'jenis_kelamin', 'alamat_jalan', 'provinsi', 'kabupaten', 'kecamatan', 'kelurahan', 'kodepos', 'telepon', 'tempat_lahir', 'tanggal_lahir', 'golongan_darah', 'agama', 'pendidikan', 'pekerjaan', 'no_asuransi', 'peserta_asuransi', 'status_kawin', 'nama_keluarga', 'nama_pasangan', 'nama_orang_tua', 'pendidikan_ot', 'pekerjaan_ot');
		
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
		$sOrder = "";
		if (isset($_GET['iSortCol_0'])) {
			for ($i = 0; $i <intval($_GET['iSortingCols']); $i++) {
				if ($_GET['bSortable_'.intval($_GET['iSortCol_'.$i])] == "true") {
					switch ($aColumns[intval($_GET['iSortCol_'.$i])]) {
						case 'no_medrec':
							$sOrder .= (!empty($sOrder) ? " " : "")."pasien.".$aColumns[intval($_GET['iSortCol_'.$i])]." ".($_GET['sSortDir_'.$i] === 'asc' ? 'asc' : 'desc');
							break;
						case 'nama':
							$sOrder .= (!empty($sOrder) ? " " : "")."pasien.".$aColumns[intval($_GET['iSortCol_'.$i])]." ".($_GET['sSortDir_'.$i] === 'asc' ? 'asc' : 'desc');
							break;
					}
				}
			}
		}
		if (!empty($sOrder)) {
			$sOrder = "ORDER BY ".$sOrder;
		}
		
		/*
		 * Where
		 */
		$sWhere = "";
		
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
			$row[] = "<a href=\"#\" class=\"edit-row\" data-id=\"".$pasien->id."\" style=\"color: #0C9ABB\">".$pasien->no_medrec."</a>";
			$row[] = $pasien->nama;
			$row[] = jenis_kelamin_descr($pasien->jenis_kelamin);
			$row[] = $pasien->alamat_jalan;
			$row[] = $pasien->provinsi;
			$row[] = $pasien->kabupaten;
			$row[] = $pasien->kecamatan;
			$row[] = $pasien->kelurahan;
			$row[] = $pasien->kodepos;
			$row[] = $pasien->telepon;
			$tanggal_lahir = strftime( "%d-%m-%Y", strtotime($pasien->tanggal_lahir));
			$row[] = $pasien->tempat_lahir . ", " . $tanggal_lahir;
			$row[] = $pasien->golongan_darah;
			$row[] = $pasien->agama;
			$row[] = $pasien->pendidikan;
			$row[] = $pasien->pekerjaan;
			$row[] = $pasien->no_asuransi;
			$row[] = $pasien->peserta_asuransi;
			$row[] = status_kawin_descr($pasien->status_kawin);
			$row[] = $pasien->nama_keluarga;
			$row[] = $pasien->nama_pasangan;
			$row[] = $pasien->nama_orang_tua;
			$row[] = $pasien->pendidikan_ot;
			$row[] = $pasien->pekerjaan_ot;
			
			$action = "<a class=\"delete-row btn btn-danger btn-mini\" href=\"#\" data-id=\"".$pasien->id."\" data-original-title=\"Hapus\">Hapus</a>";
			
			$row[] = $action;
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	
	public function get_data() {
		$pasien_id = $this->input->get('pasien_id');
		
		$output = array();
		if ($pasien_id) {
			$pasien = $this->Pasien_Model->getBy(array('pasien.id' => $pasien_id));
			$output['data']	= $pasien;
		}
		else {
			$output['data']	= $this->_getEmptyDataObject();
		}
		
		$provinsis = $this->Wilayah_Model->getAll(0, 0, array('lft' => 'ASC'), array('jenis' => 1));
		$output['provinsi_list'] = $provinsis['data'];
		
		$golongan_darah = $this->config->item('golongan_darah');
		$aGolonganDarah = array();
		foreach ($golongan_darah as $key => $val) {
			$aGolonganDarah[] = $val;
		}
		$output['golongan_darah_list'] = $aGolonganDarah;
		
		$agama = $this->Agama_Model->getAll(0, 0, array('ordering' => 'ASC'));
		$output['agama_list'] = $agama['data'];
		
		$pendidikan = $this->Pendidikan_Model->getAll(0, 0, array('id' => 'ASC'));
		$output['pendidikan_list'] = $pendidikan['data'];
		
		$pekerjaan = $this->Pekerjaan_Model->getAll(0, 0, array('id' => 'ASC'));
		$output['pekerjaan_list'] = $pekerjaan['data'];
		
		$status_kawin = $this->config->item('status_kawin');
		$aStatusKawin = array();
		foreach ($status_kawin as $key => $val) {
			$aStatusKawin[] = $val;
		}
		$output['status_kawin_list'] = $aStatusKawin;
		/*
		$this->data['pj_hubungan_list'] = $this->Pendaftaran_IRJ_Model->getHubunganDenganPasien();
		
		$this->data['status_kawin_list'] = status_kawin();*/
		
		echo json_encode($output);
	}
	
	public function simpan() {
		$pasien = $this->_getDataObject();
		if (isset($pasien->id) && $pasien->id > 0) {
			$this->Pasien_Model->update($pasien);
		}
		else {
			$this->Pasien_Model->create($pasien);
		}
		echo "ok";
	}
	
	public function delete() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Pasien_Model->delete($id);
		}
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
	
	public function get_data_kabupaten() {
		$provinsi_id = $this->input->get('provinsi_id');
		
		$output = array();
		$data = $this->Wilayah_Model->getAll(0, 0, array('lft' => 'ASC'), array('parent_id' => $provinsi_id));
		if ($data['data']) {
			$output['success'] = true;
			$output['data']	= $data['data'];
		}
		else {
			$output['success'] = false;
			$output['data']	= array();
		}
		echo json_encode($output);
	}
	
	public function get_data_kecamatan() {
		$kabupaten_id = $this->input->get('kabupaten_id');
		
		$output = array();
		$data = $this->Wilayah_Model->getAll(0, 0, array('lft' => 'ASC'), array('parent_id' => $kabupaten_id));
		if ($data['data']) {
			$output['success'] = true;
			$output['data']	= $data['data'];
		}
		else {
			$output['success'] = false;
			$output['data']	= array();
		}
		echo json_encode($output);
	}
	
	public function get_data_kelurahan() {
		$kecamatan_id = $this->input->get('kecamatan_id');
		
		$output = array();
		$data = $this->Wilayah_Model->getAll(0, 0, array('lft' => 'ASC'), array('parent_id' => $kecamatan_id));
		if ($data['data']) {
			$output['success'] = true;
			$output['data']	= $data['data'];
		}
		else {
			$output['success'] = false;
			$output['data']	= array();
		}
		echo json_encode($output);
	}
    
    private function _getEmptyDataObject() {
		$pasien = new stdClass();
		$pasien->id							= 0;
        $pasien->no_medrec					= $this->_order_no_medrec();
		$pasien->nama						= '';
		$pasien->jenis_kelamin				= 0;
		$pasien->alamat_jalan				= '';
		$pasien->provinsi_id				= 0;
		$pasien->kabupaten_id				= 0;
		$pasien->kecamatan_id				= 0;
		$pasien->kelurahan_id				= 0;
		$pasien->kodepos					= '';
		$pasien->telepon					= '';
		$pasien->tempat_lahir				= '';
		$pasien->tanggal_lahir				= '';
		$pasien->golongan_darah				= '';
		$pasien->agama_id					= 0;
		$pasien->pendidikan_id				= 0;
		$pasien->pekerjaan_id				= 0;
		$pasien->no_asuransi				= '';
		$pasien->peserta_asuransi			= '';
		$pasien->status_kawin				= 0;
		$pasien->nama_keluarga				= '';
		$pasien->nama_pasangan				= '';
		$pasien->nama_orang_tua				= '';
		$pasien->pendidikan_orang_tua_id	= 0;
		$pasien->pekerjaan_orang_tua_id		= 0;
        return $pasien;
    }
    
    private function _getDataObject() {
		$this->input->post(NULL, TRUE);
		
        $pasien = new stdClass();
		$pasien->id							= $this->input->post('id') && ($this->input->post('id') > 0) ? $this->input->post('id') : 0;
        $pasien->no_medrec					= $this->input->post('no_medrec');
		$pasien->nama						= $this->input->post('nama');
		$pasien->jenis_kelamin				= $this->input->post('jenis_kelamin');
		$pasien->alamat_jalan				= $this->input->post('alamat_jalan');
		$pasien->provinsi_id				= $this->input->post('provinsi_id');
		$pasien->kabupaten_id				= $this->input->post('kabupaten_id');
		$pasien->kecamatan_id				= $this->input->post('kecamatan_id');
		$pasien->kelurahan_id				= $this->input->post('kelurahan_id');
		$pasien->kodepos					= $this->input->post('kodepos');
		$pasien->telepon					= $this->input->post('telepon');
		$pasien->tempat_lahir				= $this->input->post('tempat_lahir');
		$pasien->tanggal_lahir				= $this->input->post('tanggal_lahir');
		$pasien->golongan_darah				= $this->input->post('golongan_darah');
		$pasien->agama_id					= $this->input->post('agama_id');
		$pasien->pendidikan_id				= $this->input->post('pendidikan_id');
		$pasien->pekerjaan_id				= $this->input->post('pekerjaan_id');
		$pasien->no_asuransi				= $this->input->post('no_asuransi');
		$pasien->peserta_asuransi			= $this->input->post('peserta_asuransi');
		$pasien->status_kawin				= $this->input->post('status_kawin');
		$pasien->nama_keluarga				= $this->input->post('nama_keluarga');
		$pasien->nama_pasangan				= $this->input->post('nama_pasangan');
		$pasien->nama_orang_tua				= $this->input->post('nama_orang_tua');
		$pasien->pendidikan_orang_tua_id	= $this->input->post('pendidikan_orang_tua_id');
		$pasien->pekerjaan_orang_tua_id		= $this->input->post('pekerjaan_orang_tua_id');
        return $pasien;
    }
	
	private function _order_no_medrec() {
		// Cek apakah sudah register
		$register = $this->session->userdata('register_no_medrec');
		$register_id = $this->session->userdata('register_no_medrec_id');
		// Jika sudah register hapus kemudian pesan
		if ($register) {
			$this->Pasien_Model->delete_no_medrec_from_queue($register_id);
		}
		// Jika belum langsung pesan
		$no_register = $this->Pasien_Model->get_no_medrec();
		$this->session->set_userdata('register_no_medrec', TRUE);
		$this->session->set_userdata('register_no_medrec_id', $no_register['no_medrec_queue_id']);
		return $no_register['no_medrec'];
	}
    
}

/* End of file pasien.php */
/* Location: ./application/controllers/pasien.php */