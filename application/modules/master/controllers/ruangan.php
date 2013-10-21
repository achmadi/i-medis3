<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ruangan extends CI_Controller {
	
	protected $form = array(
		array(
			'field'		=> 'nama',
			'label'		=> 'Nama',
			'rules'		=> 'xss_clean|required'
		),
		array(
			'field'		=> 'single_bed',
			'label'		=> 'Single Bed',
			'rules'		=> 'xss_clean'
		)
	);
	
	var $gedung_id = 0;

    public function __construct() {
		parent::__construct();
		$this->load->model('master/Ruangan_Model');
		$this->load->model('master/Gedung_Model');
		$this->load->model('master/Kelas_Model');
		$this->load->model('master/Gedung_Kelas_Model');
	}
	
	public function index()	{
		if (!$this->setAttachedTo())
			redirect('master/gedung', 'refresh');
		
		$this->data['sub_nav'] = "master/ruangan/sub_nav";
		$this->template->set_title('Ruangan')
					   ->set_css('alertify.core', array('id' => 'toggleCSS'))
					   ->set_js('jquery.dataTables')
					   ->set_js('alertify.min')
					   ->set_js('jquery.validate.min')
			           ->build('master/ruangan/browse');
	}
	
	public function load_data() {
		
		if (!$this->setAttachedTo())
			redirect('master/gedung', 'refresh');
		
		$aColumns = array('nama', 'kelas');
		
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
		$aOrders = array('id' => 'ASC');
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
		$aWheres = array('gedung_id' => $_GET['gedung_id']);
		
		/*
		 * Like
		 */
		$aLikes = array();
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
			for ($i = 0; $i < count($aColumns); $i++) {
				if (isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true") {
					switch ($aColumns[$i]) {
						case "nama":
							$aLikes["ruangan.nama"] = mysql_real_escape_string($_GET['sSearch']);
							break;
						case "kelas":
							$aLikes["kelas.nama"] = mysql_real_escape_string($_GET['sSearch']);
							break;
					}
				}
			}
		}
		
		$ruangans = $this->Ruangan_Model->getAll($iLimit, $iOffset, $aOrders, $aWheres, $aLikes);
		
		$rResult = $ruangans['data'];
		$iFilteredTotal = $ruangans['total_rows'];
		$iTotal = $ruangans['total_rows'];
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		foreach ($rResult as $ruangan) {
			$row = array();
			$row[] = "<a href=\"#\" class=\"edit-row\" data-id=\"".$ruangan->id."\" style=\"color: #0C9ABB\">".$ruangan->nama."</a>";
			$row[] = $ruangan->kelas;
			
			$action = "<a class=\"btn btn-success btn-mini\" href=\"".site_url("master/bed?gedung_id={$this->gedung_id}&ruangan_id={$ruangan->id}")."\" data-original-title=\"Bed\" title=\"Tambah Bed\">Bed</a>&nbsp;";
			$action .= "<a id=\"{$ruangan->id}\" class=\"delete-row btn btn-danger btn-mini\" href=\"#\" data-original-title=\"Hapus\">Hapus</a>";
			
			$row[] = $action;
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	
	public function get_data() {
		$ruangan_id = $this->input->get('ruangan_id');
		$gedung_id = $this->input->get('gedung_id');
		
		$kelas = $this->Gedung_Kelas_Model->getAll(0, 0, array(), array('gedung_kelas.gedung_id' => $gedung_id));
		
		$output = array();
		if ($ruangan_id) {
			$ruangan = $this->Ruangan_Model->getBy(array('id' => $ruangan_id));
			$output['data'] = array();
			$output['data']['id']			= $ruangan->id;
			$output['data']['nama']			= $ruangan->nama;
			$output['data']['gedung_id']	= $ruangan->gedung_id;
			$output['data']['kelas_id']		= $ruangan->kelas_id;
		}
		else {
			$output['data'] = array();
			$output['data']['id']			= 0;
			$output['data']['nama']			= '';
			$output['data']['gedung_id']	= $gedung_id;
			$output['data']['kelas_id']		= 0;
		}
		$output['data']['kelas_list'] = $kelas['data'];
		echo json_encode($output);
	}
	
	public function simpan() {
		$ruangan = $this->_getDataObject();
		if (isset($ruangan->id) && $ruangan->id > 0) {
			$this->Ruangan_Model->update($ruangan);
		}
		else {
			$this->Ruangan_Model->create($ruangan);
		}
		echo "ok";
	}
	
	public function delete() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Ruangan_Model->delete($id);
		}
    }
    
    private function _getDataObject() {
		$this->input->post(NULL, TRUE);
		
        $ruangan = new stdClass();
		$ruangan->id		= isset($_POST['id']) && ($_POST['id'] > 0) ? $_POST['id'] : 0;
        $ruangan->nama		= $_POST['nama'];
		$ruangan->gedung_id	= $_POST['gedung_id'];
		$ruangan->kelas_id	= $_POST['kelas_id'];
        return $ruangan;
    }
	
	private function setAttachedTo() {
		if (isset($_GET['gedung_id']))
			$this->gedung_id = $_GET['gedung_id'];
		else
			return false;
		
		$this->data['gedung'] = $this->Gedung_Model->getBy(array('id' => $this->gedung_id));
		
		return true;
	}
    
}

/* End of file ruangan.php */
/* Location: ./application/modules/master/controllers/ruangan.php */