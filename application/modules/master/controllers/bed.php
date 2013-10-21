<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bed extends ADMIN_Controller {
	
	protected $form = array(
		array(
			'field'		=> 'nama',
			'label'		=> 'Nama',
			'rules'		=> 'xss_clean|required'
		)
	);
	
	var $gedung_id = 0;
	var $ruangan_id = 0;

    public function __construct() {
		parent::__construct();
		$this->load->model('master/Bed_Model');
		$this->load->model('master/Ruangan_Model');
		$this->load->model('master/Gedung_Model');
		$this->load->model('master/Kelas_Model');
		$this->load->model('master/Gedung_Kelas_Model');
	}
	
	public function index()	{
		if (!$this->setAttachedTo())
			redirect('master/gedung', 'refresh');
		
		$this->data['sub_nav'] = "master/bed/sub_nav";
		$this->template->set_title('Bed')
					   ->set_css('alertify.core', array('id' => 'toggleCSS'))
					   ->set_js('jquery.dataTables')
					   ->set_js('alertify.min')
					   ->set_js('jquery.validate.min')
			           ->build('master/bed/browse');
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
		$aWheres = array('bed.gedung_id' => $this->gedung_id, 'bed.ruangan_id' => $this->ruangan_id);
		
		/*
		 * Like
		 */
		$aLikes = array();
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
			for ($i = 0; $i < count($aColumns); $i++) {
				if (isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true") {
					switch ($aColumns[$i]) {
						case "nama":
							$aLikes["bed.nama"] = mysql_real_escape_string($_GET['sSearch']);
							break;
						case "kelas":
							$aLikes["kelas.nama"] = mysql_real_escape_string($_GET['sSearch']);
							break;
					}
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
			$row[] = "<a href=\"#\" class=\"edit-row\" data-id=\"".$bed->id."\" style=\"color: #0C9ABB\">".$bed->nama."</a>";
			$row[] = $bed->kelas;
			
			$action = "<a id=\"".$bed->id."\" class=\"delete-row btn btn-danger btn-mini\" href=\"#\" data-original-title=\"Hapus\">Hapus</a>";
			
			$row[] = $action;
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	
	public function get_data() {
		$bed_id = $this->input->get('bed_id');
		$ruangan_id = $this->input->get('ruangan_id');
		$gedung_id = $this->input->get('gedung_id');
		
		$kelas = $this->Gedung_Kelas_Model->getAll(0, 0, array(), array('gedung_kelas.gedung_id' => $gedung_id));
		
		$output = array();
		if ($bed_id) {
			$bed = $this->Bed_Model->getBy(array('bed.id' => $bed_id));
			$output['data'] = array();
			$output['data']['id']			= $bed->id;
			$output['data']['nama']			= $bed->nama;
			$output['data']['gedung_id']	= $bed->gedung_id;
			$output['data']['ruangan_id']	= $bed->ruangan_id;
			$output['data']['kelas_id']		= $bed->kelas_id;
		}
		else {
			$output['data'] = array();
			$output['data']['id']			= 0;
			$output['data']['nama']			= '';
			$output['data']['gedung_id']	= $gedung_id;
			$output['data']['ruangan_id']	= $ruangan_id;
			$output['data']['kelas_id']		= 0;
		}
		$output['data']['kelas_list'] = $kelas['data'];
		echo json_encode($output);
	}
	
	public function simpan() {
		$bed = $this->_getDataObject();
		if (isset($bed->id) && $bed->id > 0) {
			$this->Bed_Model->update($bed);
		}
		else {
			$this->Bed_Model->create($bed);
		}
		echo "ok";
	}
	
	public function delete() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Bed_Model->delete($id);
		}
    }
    
    private function _getDataObject() {
		$this->input->post(NULL, TRUE);
		
        $bed = new stdClass();
		$bed->id					= $this->input->post('id') ? $this->input->post('id') : 0;
        $bed->nama					= $this->input->post('nama');
		$bed->gedung_id				= $this->input->post('gedung_id');
		$bed->ruangan_id			= $this->input->post('ruangan_id');
		$bed->kelas_id				= $this->input->post('kelas_id');
        return $bed;
    }
	
	private function setAttachedTo() {
		if ($this->input->get('gedung_id'))
			$this->gedung_id = $this->input->get('gedung_id');
		else
			return false;
		
		$this->data['gedung'] = $this->Gedung_Model->getBy(array('id' => $this->gedung_id));
		
		if (isset($_GET['ruangan_id']))
			$this->ruangan_id = $_GET['ruangan_id'];
		else
			return false;

		$this->data['ruangan'] = $this->Ruangan_Model->getBy(array('id' => $this->ruangan_id));
		
		return true;
	}
    
}

/* End of file bed.php */
/* Location: ./application/modules/master/controllers/bed.php */