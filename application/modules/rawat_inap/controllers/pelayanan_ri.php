<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Pelayanan Rawat Inap.
 * 
 * @package Pelayanan_RI
 * @category Controller
 * @author Dadang Dana Suryana
 */
class Pelayanan_RI extends Admin_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Pelayanan_RI_Model');
		$this->load->model('Tindakan_Model');
	}

	public function index($id) {
		$this->data['data'] = $this->Pelayanan_RI_Model->getBy(array('pelayanan_ri.id' => $id));
		
		$this->data['sub_nav'] = "rawat_inap/pelayanan_ri/sub_nav";
		$this->template->set_title('Pelayanan Rawat Inap')
					   ->set_css('alertify.core', array('id' => 'toggleCSS'))
					   ->set_js('jquery.dataTables')
					   ->set_js('alertify.min')
			           ->build('rawat_inap/pelayanan_ri/edit');
    }
	
	public function load_data_tindakan() {
		
		$aColumns = array('tanggal', 'tindakan', 'dokter', 'harga');
		
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
		
		$tindakans = $this->Tindakan_Model->getAll($iLimit, $iOffset, $aOrders, $aWheres, $aLikes);
		
		$rResult = $tindakans['data'];
		$iFilteredTotal = $tindakans['total_rows'];
		$iTotal = $tindakans['total_rows'];
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		foreach ($rResult as $tindakan) {
			$row = array();
			$tanggal = strftime( "%d-%m-%Y %H:%M:%S", strtotime($tindakan->tanggal));
			$row[] = $tanggal;
			$row[] = $tindakan->tarif_pelayanan;
			$row[] = $tindakan->dokter;
			$row[] = $tindakan->tarif;
			
			$action = "<a class=\"delete-row btn btn-danger btn-mini\" href=\"#\" data-original-title=\"Hapus\" data-id=\"".$tindakan->id."\" title=\"Hapus Pendaftaran Rawat Jalan\">Hapus</a>&nbsp;";
			$row[] = $action;
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	
}

/* End of file pelayanan_ri.php */
/* Location: ./application/modules/rawat_inap/pelayanan_ri.php */