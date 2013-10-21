<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Pembagian Jasa.
 * 
 * @package App
 * @category Controller
 * @author Dadang Dana Suryana
 */
class Pembagian_Jasa_Askes extends ADMIN_Controller {
	
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
		
		$this->load->model('pembagian_jasa/Pembagian_Jasa_Model');
		$this->load->model('pembagian_jasa/Pembagian_Jasa_Detail_Model');
		$this->load->model('pembagian_jasa/Penerima_JP_Detail_Model');
		
		$this->load->model('master/Unit_Model');
		$this->load->model('master/Unit_Detail_Model');
		$this->load->model('master/Tarif_Pelayanan_Model');
		
		$this->load->model('pendaftaran_irj/Pendaftaran_IRJ_Model');
		$this->load->model('master/Cara_Bayar_Model');
		$this->load->model('master/Poliklinik_Pegawai_Model');
		$this->load->model('master/Pasien_Model');
		
		$this->load->model('master/Pegawai_Model');
		
		$this->Pembagian_Jasa_Model->delete_expire_no_register_from_queue();
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
		
		$cara_bayar = $this->Cara_Bayar_Model->getAll(0, 0, array('id' => 'ASC'));
		$this->data['cara_bayar_list'] = $cara_bayar['data'];
		
		$this->data['top_nav'] = "pembagian_jasa/top_nav";
		$this->data['top_nav_selected'] = "Pembagian Jasa Askes";
		$this->data['sub_nav'] = "pembagian_jasa/sub_nav2";
		$this->template->set_title('Pembagian Jasa')
					   ->set_css('datepicker')
					   ->set_js('bootstrap-datepicker')
					   ->set_js('autoNumeric')
					   ->set_js('jquery.dataTables')
			           ->build('pembagian_jasa/edit');
    }
    
	public function browse($daftar_kunjungan = 0) {
		/*
		if ($this->input->post('daftar_kunjungan')) {
            $this->data['daftar_kunjungan'] = $this->input->post('daftar_kunjungan');
        }
		else {
			$this->data['daftar_kunjungan'] = 1;
		}
		*/
		
		$this->data['daftar_kunjungan'] = $daftar_kunjungan;
		
		$this->data['top_nav'] = "pembagian_jasa/top_nav";
		$this->data['top_nav_selected'] = "Browse";
		$this->data['sub_nav'] = "pembagian_jasa/sub_nav3";
		$this->data['sub_nav_selected'] = "Browse";
		$this->template->set_title('Daftar Pemb. Jasa')
					   ->set_js('jquery.dataTables')
			           ->build('pembagian_jasa/browse');
	}
	
	public function load_data() {
		
		$aColumns = array('tanggal', 'no_register', 'no_medrec', 'nama', 'jumlah');
		
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
			$row[] = $pembagian_jasa->no_register;
			$row[] = $pembagian_jasa->no_medrec;
			$row[] = $pembagian_jasa->nama;
			$row[] = $pembagian_jasa->jumlah;
			
			//$action = "<a class=\"btn btn-info btn-mini\" href=\"".site_url("pembagian_jasa/edit/".$pembagian_jasa->id)."\" data-original-title=\"Edit\" title=\"Edit Pembagian Jasa\">Edit</a>&nbsp;";
			$action = "<a id=\"".$pembagian_jasa->id."\" class=\"delete-row btn btn-danger btn-mini\" href=\"#\" data-original-title=\"Hapus\" title=\"Hapus Pembagian Jasa\">Hapus</a>&nbsp;";
			$row[] = $action;
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	
	public function delete($id) {
 		$this->Pendaftaran_IRJ_Model->delete($id);
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
					$aLikes[$aColumns[$i]] = mysql_real_escape_string($_GET['sSearch']);
				}
			}
		}
		
		$pasiens = $this->Pendaftaran_IRJ_Model->getAll(0, 0, $aOrders, array(), $aLikes);
		
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
			$row[] = "<a href=\"#\" onclick=\"window.select_pasien(".$pasien->pasien_id.");return false;\">".$pasien->no_medrec."</a>";
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
	
	public function get_penerima_jp_detail() {
		$unit_id = $this->input->get('unit_id');
		$counter = $this->input->get('counter');
		$tanggal = $this->input->get('tanggal');
		
		$unit = $this->Unit_Model->getBy(array('id' => $unit_id));
		$root = $this->Unit_Detail_Model->getBy(array('unit_id' => $unit->id, 'jenis' => 'Root'));
		$data = $this->Unit_Detail_Model->get_tree($root->id, 0, 0, array('lft' => 'asc'));
		$unit_detail = $data['data'];
		
		$html = '';
		$index = 0;
		foreach ($unit_detail as $row) {
			if ($row->jenis != 'Root') {
				$html .= '<tr id="row_penerima_jp_detail_'.$counter.'_'.$index.'">';
				$html .= '	<td><input type="hidden" id="penerima_jp_detail_id_'.$counter.'_'.$index.'" name="penerima_jp_detail_id['.$counter.'][]" value="new_penerima_jp_detail_'.$counter.'_'.$index.'" /></td>';
				$html .= '	<td><input type="hidden" id="penerima_jp_detail_tanggal_'.$counter.'_'.$index.'" name="penerima_jp_detail_tanggal['.$counter.'][]" value="'.$tanggal.'" /></td>';
				$html .= '	<td><input type="hidden" id="penerima_jp_detail_nama_'.$counter.'_'.$index.'" name="penerima_jp_detail_nama['.$counter.'][]" value="'.$row->nama.'" /></td>';
				$html .= '	<td><input type="hidden" id="penerima_jp_detail_pegawai_id_'.$counter.'_'.$index.'" name="penerima_jp_detail_pegawai_id['.$counter.'][]" value="" /></td>';
				$html .= '	<td><input type="hidden" id="penerima_jp_detail_jenis_'.$counter.'_'.$index.'" name="penerima_jp_detail_jenis['.$counter.'][]" value="'.$row->jenis_komponen.'" /></td>';
				$html .= '	<td>';
				$html .= '		<input type="hidden" id="penerima_jp_detail_persen_proporsi_'.$counter.'_'.$index.'" name="penerima_jp_detail_persen_proporsi['.$counter.'][]" value="'.$row->proporsi.'" />';
				$html .= '		<input type="hidden" id="penerima_jp_detail_jumlah_proporsi_'.$counter.'_'.$index.'" name="penerima_jp_detail_jumlah_proporsi['.$counter.'][]" value="0" />';
				$html .= '	</td>';
				$html .= '	<td>';
				$html .= '		<input type="hidden" id="penerima_jp_detail_persen_langsung_'.$counter.'_'.$index.'" name="penerima_jp_detail_persen_langsung['.$counter.'][]" value="'.$row->langsung.'" />';
				$html .= '		<input type="hidden" id="penerima_jp_detail_jumlah_langsung_'.$counter.'_'.$index.'" name="penerima_jp_detail_jumlah_langsung['.$counter.'][]" value="0" />';
				$html .= '	</td>';
				$html .= '	<td>';
				$html .= '		<input type="hidden" id="penerima_jp_detail_persen_balancing_'.$counter.'_'.$index.'" name="penerima_jp_detail_persen_balancing['.$counter.'][]" value="'.$row->balancing.'" />';
				$html .= '		<input type="hidden" id="penerima_jp_detail_jumlah_balancing_'.$counter.'_'.$index.'" name="penerima_jp_detail_jumlah_balancing['.$counter.'][]" value="0" />';
				$html .= '	</td>';
				$html .= '	<td>';
				$html .= '		<input type="hidden" id="penerima_jp_detail_persen_kebersamaan_'.$counter.'_'.$index.'" name="penerima_jp_detail_persen_kebersamaan['.$counter.'][]" value="'.$row->kebersamaan.'" />';
				$html .= '		<input type="hidden" id="penerima_jp_detail_jumlah_kebersamaan_'.$counter.'_'.$index.'" name="penerima_jp_detail_jumlah_kebersamaan['.$counter.'][]" value="0" />';
				$html .= '	</td>';
				$html .= '</tr>';
				$index++;
			}
		}
		echo $html;
	}
	
	public function browse_tindakan() {
		$unit_id = $this->input->get('unit_id');
		$this->data['unit_id'] = $unit_id;
		$this->load->view('pembagian_jasa/browse_tindakan', $this->data);
	}
	
	public function load_data_tindakan() {
		
		$aColumns = array('nama');
		
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
					$aLikes[$aColumns[$i]] = mysql_real_escape_string($_GET['sSearch']);
				}
			}
		}
		
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
				$output['aaData'][] = $row;
			}
		}
		
		echo json_encode($output);
	}
	
	public function get_tindakan_by_id($id) {
		$tindakan = $this->Tarif_Pelayanan_Model->getBy(array('id' => $id));
		$aTindakan = get_object_vars($tindakan);
		echo json_encode(array("tindakan" => $aTindakan));
	}
	
	public function pasien_autocomplete() {
		$search = $this->input->get('search');
		$unit_id = $this->input->get('unit_id');
		$unit = $this->Unit_Model->getBy(array('id' => $unit_id));
		switch ($unit->jenis) {
			case 1: // Rawat Jalan
				$datas = $this->Pendaftaran_IRJ_Model->getAll(0, 0, array('no_medrec' => 'asc'), array(), array('no_medrec' => $search));
				$data = $datas['data'];
				$pasien = array();
				foreach ($data as $row) {
					$pasien[] = array(
						'pasien_id'		=> $row->pasien_id, 
						'no_medrec'		=> $row->no_medrec, 
						'nama'			=> $row->nama,
						'cara_bayar_id'	=> $row->cara_bayar_id,
						'cara_bayar'	=> $row->cara_bayar,
						'poliklinik_id'	=> $row->poliklinik_id,
						'poliklinik'	=> $row->poliklinik,
						'dokter_id'		=> $row->dokter_id,
						'dokter'		=> $row->dokter
					);
				}
				echo json_encode(array("pasien" => $pasien));
				break;
			case 2: // Rawat Darurat
				break;
			case 3: // Rawat Inap
				break;
		}
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
	
	public function get_tarif_jenis_pelayanan($id) {
		$query = $this->input->get('q');
		$jenis_pelayanan = $this->Jenis_Pelayanan_Model->getById($id);
		$aTarif = get_object_vars($jenis_pelayanan);
		echo json_encode(array("tarif" => $aTarif));
	}
	
	public function tarif_pelayanan_autocomplete() {
		$query = $this->input->get('q');
		$datas = $this->Tarif_Pelayanan_Model->getAll(0, 0, array('lft' => 'asc'), array(), array('nama' => $query));
		$data = $datas['data'];
		$tarif_pelayanan = array();
		foreach ($data as $row) {
			$tarif_pelayanan[] = array('id' => $row->id, 'nama' => $row->nama, 'jasa_sarana' => $row->jasa_sarana, 'jasa_pelayanan' => $row->jasa_pelayanan);
		}
		echo json_encode(array("tarif_pelayanan" => $tarif_pelayanan));
	}
	
	public function get_uraian() {
		$unit_id = $this->input->get('unit_id');
		$tindakan_id = $this->input->get('tindakan_id');
		
		$unit = $this->Unit_Model->getBy(array('id' => $unit_id));
		$root = $this->Unit_Detail_Model->getBy(array('unit_id' => $unit->id, 'jenis' => 'Root'));
		$details = $this->Unit_Detail_Model->get_tree($root->id, 0, 0, array('lft' => 'ASC'));
		$detail = $details['data'];
		
		$tarif_pelayanan = $this->Tarif_Pelayanan_Model->getBy(array('id' => $tindakan_id));
		if ($tarif_pelayanan) {
			$jml_jp = $tarif_pelayanan->jasa_pelayanan;
		}
		else {
			$jml_jp = 0;
		}
		$pemda = $jml_jp * ($unit->pemda/100);
		$dibagikan = $jml_jp - $pemda;
		$manajemen = $dibagikan * ($unit->manajemen/100);
		$sisa = $dibagikan - $manajemen;
		
		for ($i = 0; $i < count($detail); $i++) {
			$persen_proporsi = $detail[$i]->proporsi;
			$persen_langsung = $detail[$i]->langsung;
			$persen_balancing = $detail[$i]->balancing;
			$persen_kebersamaan = $detail[$i]->kebersamaan;
			
			$jml_proporsi = $sisa * ($persen_proporsi/100);
			$jml_langsung = $jml_proporsi * ($persen_langsung/100);
			$jml_balancing = $jml_proporsi * ($persen_balancing/100);
			$jml_kebersamaan = $jml_proporsi * ($persen_kebersamaan/100);
			
			$detail[$i]->jml_proporsi = $jml_proporsi;
			$detail[$i]->jml_langsung = $jml_langsung;
			$detail[$i]->jml_balancing = $jml_balancing;
			$detail[$i]->jml_kebersamaan = $jml_kebersamaan;
		}
		
		$this->data['setup_jp'] = $detail;
		$pegawais = $this->Pegawai_Model->getAll(0, 0, array('nama' => 'ASC'));
		$this->data['pegawai_list'] = $pegawais['data'];
		
		$this->load->view('pembagian_jasa/uraian', $this->data);
	}
	
	private function _getEmptyDataObject() {
		$pembagian_jasa = new stdClass();
		$pembagian_jasa->id				= 0;
		$pembagian_jasa->unit_id		= 0;
		$pembagian_jasa->transaksi_id	= 0;
		$pembagian_jasa->tanggal		= get_current_date();
		$pembagian_jasa->no_register	= $this->_order_no_register();
		$pembagian_jasa->pasien_id		= 0;
		$pembagian_jasa->dokter_id		= 0;
		$pembagian_jasa->jumlah			= 0;
		$pembagian_jasa->version		= 0;
		$pembagian_jasa->details		= array();
        return $pembagian_jasa;
    }
	
    private function _getDataObject() {
		$this->input->post(NULL, TRUE);
		
		$pembagian_jasa = new stdClass();
		$pembagian_jasa->id				= $this->input->post('id') && ($this->input->post('id') > 0) ? $this->input->post('id') : 0;
        $pembagian_jasa->unit_id		= $this->input->post('unit_id');
		$pembagian_jasa->transaksi_id	= $this->input->post('transaksi_id');
		$pembagian_jasa->tanggal		= $this->input->post('tanggal');
		$pembagian_jasa->no_register	= $this->input->post('no_register');
		$pembagian_jasa->pasien_id		= $this->input->post('pasien_id');
		$pembagian_jasa->dokter_id		= $this->input->post('master_dokter_id');
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
							$pjp_detail->jenis						= $_POST['penerima_jp_detail_jenis'][$i][$j];
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
							$aPenerimaJPDetails[$penerima_jp_detail_id]->jenis			= $_POST['penerima_jp_detail_jenis'][$i][$j];
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
		
        return $pembagian_jasa;
    }
	
	private function _order_no_register() {
		// Cek apakah sudah register
		$register = $this->session->userdata('register_no_register_pj');
		$register_id = $this->session->userdata('register_no_register_pj_id');
		// Jika sudah register hapus kemudian pesan
		if ($register) {
			$this->Pembagian_Jasa_Model->delete_no_register_from_queue($register_id);
		}
		// Jika belum langsung pesan
		$no_register = $this->Pembagian_Jasa_Model->get_no_register();
		$this->session->set_userdata('register_no_register_irj', TRUE);
		$this->session->set_userdata('register_no_register_irj_id', $no_register['no_register_queue_id']);
		return $no_register['no_register'];
	}
	
}

/* End of file pembagian_jasa.php */
/* Location: ./application/modules/pembagian_jasa/pembagian_jasa.php */