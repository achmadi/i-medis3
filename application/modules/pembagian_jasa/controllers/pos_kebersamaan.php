<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Pembagian Jasa.
 * 
 * @package App
 * @category Controller
 * @author Dadang Dana Suryana
 */
class Pos_Kebersamaan extends ADMIN_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->language('welcome');
		
		$this->load->model('master/Pegawai_Model');
		$this->load->model('pembagian_jasa/Penerima_JP_Detail_Model');
		$this->load->model('pembagian_jasa/Jasa_Pelayanan_Model');
	}

	public function index() {
		$bulan = $this->input->post('bulan') ? $this->input->post('bulan') : date("m");
		$tahun = $this->input->post('tahun') ? $this->input->post('tahun') : date("Y");
		
		$this->data['current_bulan'] = $bulan;
		$this->data['current_tahun'] = $tahun;
		$this->data['tahun_list'] = $this->Penerima_JP_Detail_Model->get_tahun();
		
		$this->data['top_nav'] = "pembagian_jasa/top_nav";
		$this->data['top_nav_selected'] = "Pos Kebersamaan";
		$this->data['sub_nav'] = "pembagian_jasa/sub_nav4";
		$this->template->set_title('Pos Kebersamaan')
					   ->set_js('jquery.dataTables')
			           ->build('pembagian_jasa/edit_pos_kebersamaan');
	}
	
	public function load_data() {
		
		$aColumns = array('no_rekening', 'nama');
		
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
		$aOrders = array('ruang' => 'ASC', 'nama' => 'ASC');
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
						case 'no_rekening':
							$aLikes['no_rekening'] = mysql_real_escape_string($_GET['sSearch']);
							break;
						case 'nama':
							$aLikes['pegawai.nama'] = mysql_real_escape_string($_GET['sSearch']);
							break;
					}
				}
			}
		}
		
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
		
		$data = $this->Jasa_Pelayanan_Model->getAll($iLimit, $iOffset, $aOrders, $aWheres, $aLikes);
		
		$rResult = $data['data'];
		$iFilteredTotal = $data['total_rows'];
		$iTotal = $data['total_rows'];
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		foreach ($rResult as $pegawai) {
			$row = array();
			$row[] = $pegawai->tanggal;
			$row[] = $pegawai->no_rekening;
			$row[] = $pegawai->nama;
			$row[] = $pegawai->ruang;
			$row[] = $pegawai->nip;
			$row[] = $pegawai->npwp;
			$row[] = $pegawai->golongan;
			$row[] = $pegawai->indeks;
			$row[] = number_format($pegawai->jumlah, 2, ',', '.');
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	
	public function hitung_kebersamaan() {
		$bulan = $this->input->get('bulan');
		$tahun = $this->input->get('tahun');
		
		$this->run_phase_1($bulan, $tahun);
		$this->run_phase_2($bulan, $tahun);
		
		echo 'OK';
	}
	
	public function run_phase_1($bulan, $tahun) {
		
		//ambil jumlah kebersamaan simpan di $jumlah_kebersamaan
		$jumlah_kebersamaan = $this->Penerima_JP_Detail_Model->get_jumlah_kebersamaan($bulan, $tahun);
		
		//hitung dan jumlahkan semua skor pegawai
		$jumlah_skor = $this->Pegawai_Model->get_jumlah_skor_tidak_langsung();
		
		//ambil semua data pegawai kecuali dokter, apoteker dan manajemen
		$data = $this->Pegawai_Model->getAll(0, 0, array('pegawai.nip' => 'ASC'), array('kelompok_pegawai.jenis' => 4));
		$pegawais = $data['data'];
		
		//untuk semua pegawai lakukan:
		foreach ($pegawais As $pegawai) {
			//skor = (basic + posisi + emergency + pendidikan + masa kerja + resiko)
			$skor = $pegawai->indeks_basic + $pegawai->indeks_posisi + $pegawai->indeks_emergency + $pegawai->indeks_pendidikan + $pegawai->indeks_masa_kerja + $pegawai->indeks_resiko;
			
			// isentif = skor/jumlah_skor * jumlah_kebersamaan
			if ($jumlah_kebersamaan > 0) {
				$insentif = ($skor/$jumlah_skor) * $jumlah_kebersamaan;
			}
			else {
				$insentif = 0;
			}
			
			//Simpan
			if ($insentif > 0) {
				$jasa_pelayanan = new stdClass();

				$tanggal = date('Y-m-d H:i:s', mktime(0, 0, 0, $bulan, 1, $tahun));
				
				$data = $this->Jasa_Pelayanan_Model->getBy(array('pegawai_id' => $pegawai->id, 'tanggal' => $tanggal, ));
				
				if ($data) {
					$jasa_pelayanan->id = $data->id;
					$jasa_pelayanan->jasa_tak_langsung = $insentif;
					$this->Jasa_Pelayanan_Model->update($jasa_pelayanan);
				}
				else {
					$jasa_pelayanan->tanggal = $tanggal;
					$jasa_pelayanan->pegawai_id = $pegawai->id;
					$jasa_pelayanan->jasa_tak_langsung = $insentif;
					$this->Jasa_Pelayanan_Model->create($jasa_pelayanan);
				}
			}
		}
	}
	
	public function run_phase_2($bulan, $tahun) {
		
		// Ambil semua Kelompok Pegawai
		$kelompoks = $this->Pegawai_Model->getAllKelompok();
		
		// Untuk setiap Kelompok Pegawai
		foreach ($kelompoks as $kelompok) {
			
			// Ambil jumlah tarif langsung pegawai simpan di jumlah_tarif_langsung
			$jumlah_langsung = $this->Penerima_JP_Detail_Model->get_jumlah_langsung($bulan, $tahun, $kelompok->ruang);
			
			// Ambil total indeks langsung pegawai simpan di total_indeks_langsung
			$jumlah_skor_langsung = $this->Pegawai_Model->get_jumlah_skor_langsung($kelompok->ruang);
			
			$pegawais = $this->Pegawai_Model->getPerKelompok($kelompok->ruang);
			
			// Untuk setiap Pegawai
			foreach ($pegawais As $pegawai) {
				// Hitung tarif langsung pegawai
				$insentif = $jumlah_skor_langsung/$pegawai->indeks_langsung * $jumlah_langsung;
				
				//Simpan
				if ($insentif > 0) {
					$jasa_pelayanan = new stdClass();

					$tanggal = date('Y-m-d H:i:s', mktime(0, 0, 0, $bulan, 1, $tahun));

					$data = $this->Jasa_Pelayanan_Model->getBy(array('pegawai_id' => $pegawai->id, 'tanggal' => $tanggal, ));

					if ($data) {
						$jasa_pelayanan->id = $data->id;
						$jasa_pelayanan->jasa_langsung = $insentif;
						$this->Jasa_Pelayanan_Model->update($jasa_pelayanan);
					}
					else {
						$jasa_pelayanan->tanggal = $tanggal;
						$jasa_pelayanan->pegawai_id = $pegawai->id;
						$jasa_pelayanan->jasa_langsung = $insentif;
						$this->Jasa_Pelayanan_Model->create($jasa_pelayanan);
					}
				}
				
			}
			
		}
	}
	
}

/* End of file pos_kebersamaan.php */
/* Location: ./application/modules/pembagian_jasa/pos_kebersamaan.php */