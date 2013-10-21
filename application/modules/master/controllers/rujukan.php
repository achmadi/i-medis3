<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rujukan extends CI_Controller {
	
	protected $form = array(
		array(
			'field'		=> 'nama',
			'label'		=> 'Nama',
			'rules'		=> 'xss_clean|required'
		),
		array(
			'field'		=> 'jenis',
			'label'		=> 'Jenis',
			'rules'		=> 'xss_clean|required'
		)
	);

    public function __construct() {
		parent::__construct();
		$this->load->model('master/Rujukan_Model');
	}
	
	public function index()	{
		$this->data['sub_nav'] = "master/rujukan/sub_nav";
		$this->template->set_title('Rujukan')
					   ->set_css('alertify.core', array('id' => 'toggleCSS'))
					   ->set_js('jquery.dataTables')
					   ->set_js('alertify.min')
			           ->build('master/rujukan/browse');
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
		
		$rujukans = $this->Rujukan_Model->getAll($iLimit, $iOffset, $aOrders, array(), $aLikes);
		
		$rResult = $rujukans['data'];
		$iFilteredTotal = $rujukans['total_rows'];
		$iTotal = $rujukans['total_rows'];
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		foreach ($rResult as $rujukan) {
			$row = array();
			$row[] = $rujukan->nama;
			$row[] = $this->Rujukan_Model->getJenisRujukanDescr($rujukan->jenis);
			
			$action = "<a class=\"btn btn-info btn-mini\" href=\"".site_url("master/rujukan/edit/".$rujukan->id)."\" data-original-title=\"Edit\">Edit</a>&nbsp;";
			$action .= "<a id=\"".$rujukan->id."\" class=\"delete-row btn btn-danger btn-mini\" href=\"#\" data-original-title=\"Hapus\">Hapus</a>";
			
			$row[] = $action;
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	
	public function add() {
		$this->data['is_new'] = true;
		$this->_update();
	}
	
	public function edit($id) {
		$this->data['is_new'] = false;
		$this->_update($id);
	}
    
    private function _update($id = 0) {
		$this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error" style="color: #CC0000; margin-left: 150px;">', '</div>');
        $this->form_validation->set_rules($this->form);
        
        if ($this->input->post('batal')) {
            redirect('master/rujukan', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $rujukan = $this->_getDataObject();
                if (isset($rujukan->id) && $rujukan->id > 0) {
                    $this->Rujukan_Model->update($rujukan);
                }
                else {
                    $this->Rujukan_Model->create($rujukan);
                }
                redirect('master/rujukan', 'refresh');
            }
        }
		
		if ($id) {
			$rujukan = $this->Rujukan_Model->getById($id);
		}
		else {
			$rujukan = $this->_getEmptyDataObject();
		}
		
		$this->data['data'] = $rujukan;
		$this->data['jenis_list'] = $this->Rujukan_Model->getJenisRujukan();
		
		$this->data['sub_nav'] = "master/rujukan/sub_nav";
		$this->template->set_title('Tambah/Edit Rujukan')
					   ->set_js('jquery.dataTables')
			           ->build('master/rujukan/edit');
    }
	
	public function delete() {
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
			$this->Rujukan_Model->delete($id);
		}
    }
    
    private function _getEmptyDataObject() {
		$rujukan = new stdClass();
		$rujukan->id	= 0;
        $rujukan->nama	= '';
		$rujukan->jenis	= 0;
        return $rujukan;
    }
    
    private function _getDataObject() {
		$this->input->post(NULL, TRUE);
		
        $rujukan = new stdClass();
		$rujukan->id	= isset($_POST['id']) && ($_POST['id'] > 0) ? $_POST['id'] : 0;
        $rujukan->nama	= $_POST['nama'];
		$rujukan->jenis	= $_POST['jenis'];
        return $rujukan;
    }
    
}

/* End of file rujukan.php */
/* Location: ./application/modules/master/controllers/rujukan.php */