<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pendidikan extends CI_Controller {

	protected $form = array(
		array(
			'field'		=> 'nama',
			'label'		=> 'Nama',
			'rules'		=> 'xss_clean|required'
		)
	);

    public function __construct() {
		parent::__construct();
		$this->load->model('master/Pendidikan_Model');
	}

	public function index()	{
		$this->data['sub_nav'] = "master/pendidikan/sub_nav";
		$this->template->set_title('Pendidikan')
					   ->set_css('alertify.core', array('id' => 'toggleCSS'))
					   ->set_js('jquery.dataTables')
					   ->set_js('alertify.min')
					   ->set_js('jquery.validate.min')
			           ->build('master/pendidikan/browse');
	}

	public function load_data() {
		
		$aColumns = array('nama');
		
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
		$aOrders = array('ordering' => 'ASC');
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
		
		$pendidikans = $this->Pendidikan_Model->getAll($iLimit, $iOffset, $aOrders, array(), $aLikes);
		
		$rResult = $pendidikans['data'];
		$iFilteredTotal = $pendidikans['total_rows'];
		$iTotal = $pendidikans['total_rows'];
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		foreach ($rResult as $pendidikan) {
			$row = array();
			$row[] = "<a href=\"#\" class=\"edit-row\" data-id=\"".$pendidikan->id."\" style=\"color: #0C9ABB\">".$pendidikan->nama."</a>";
			
			$action = "<a class=\"delete-row btn btn-danger btn-mini\" href=\"#\" data-id=\"".$pendidikan->id."\" data-original-title=\"Hapus\">Hapus</a>";
			
			$row[] = $action;
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	
	public function get_data() {
		$pendidikan_id = $this->input->get('pendidikan_id');
		
		$output = array();
		if ($pendidikan_id) {
			$pendidikan = $this->Pendidikan_Model->getBy(array('pendidikan.id' => $pendidikan_id));
			$output['data']	= $pendidikan;
		}
		else {
			$output['data']	= $this->_getEmptyDataObject();
		}
		
		echo json_encode($output);
	}
	
	public function simpan() {
		$pendidikan = $this->_getDataObject();
		if (isset($pendidikan->id) && $pendidikan->id > 0) {
			$this->Pendidikan_Model->update($pendidikan);
		}
		else {
			$this->Pendidikan_Model->create($pendidikan);
		}
		echo "ok";
	}
    
	public function delete() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Pendidikan_Model->delete($id);
		}
    }
    
    private function _getEmptyDataObject() {
		$pendidikan = new stdClass();
		$pendidikan->id		= 0;
        $pendidikan->nama	= '';
        return $pendidikan;
    }
    
    private function _getDataObject() {
		$this->input->post(NULL, TRUE);

        $pendidikan = new stdClass();
		$pendidikan->id		= isset($_POST['id']) && ($_POST['id'] > 0) ? $_POST['id'] : 0;
        $pendidikan->nama	= $_POST['nama'];
        return $pendidikan;
    }
    
}

/* End of file pendidikan.php */
/* Location: ./application/master/controllers/pendidikan.php */