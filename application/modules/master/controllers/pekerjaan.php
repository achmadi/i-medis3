<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pekerjaan extends CI_Controller {

	protected $form = array(
		array(
			'field'		=> 'nama',
			'label'		=> 'Nama',
			'rules'		=> 'xss_clean|required'
		)
	);

    public function __construct() {
		parent::__construct();
		$this->load->model('master/Pekerjaan_Model');
	}

	public function index()	{
		$this->data['sub_nav'] = "master/pekerjaan/sub_nav";
		$this->template->set_title('Pekerjaan')
					   ->set_css('alertify.core', array('id' => 'toggleCSS'))
					   ->set_js('jquery.dataTables')
					   ->set_js('alertify.min')
			           ->build('master/pekerjaan/browse');
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
		
		$pekerjaans = $this->Pekerjaan_Model->getAll($iLimit, $iOffset, $aOrders, array(), $aLikes);
		
		$rResult = $pekerjaans['data'];
		$iFilteredTotal = $pekerjaans['total_rows'];
		$iTotal = $pekerjaans['total_rows'];
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		foreach ($rResult as $pekerjaan) {
			$row = array();
			$row[] = "<a href=\"".site_url("master/pekerjaan/edit/".$pekerjaan->id)."\" data-original-title=\"Edit Pekerjaan\" style=\"color: #0C9ABB\">".$pekerjaan->nama."</a>";
			
			$action = "<a class=\"delete-row btn btn-danger btn-mini\" href=\"#\" data-id=\"".$pekerjaan->id."\" data-original-title=\"Hapus Pekerjaan\">Hapus</a>";
			
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
            redirect('master/pekerjaan', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $pekerjaan = $this->_getDataObject();
                if (isset($pekerjaan->id) && $pekerjaan->id > 0) {
                    $this->Pekerjaan_Model->update($pekerjaan);
                }
                else {
                    $this->Pekerjaan_Model->create($pekerjaan);
                }
                redirect('master/pekerjaan', 'refresh');
            }
        }
		
		if ($id) {
			$pekerjaan = $this->Pekerjaan_Model->getById($id);
		}
		else {
			$pekerjaan = $this->_getEmptyDataObject();
		}
		
		$this->data['data'] = $pekerjaan;

		$this->data['sub_nav'] = "master/pekerjaan/sub_nav";
		$this->template->set_title('Tambah/Edit Pekerjaan')
					   ->set_js('jquery.dataTables')
			           ->build('master/pekerjaan/edit');
    }
    
	public function delete() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Pekerjaan_Model->delete($id);
		}
    }
    
    private function _getEmptyDataObject() {
		$pekerjaan = new stdClass();
		$pekerjaan->id		= 0;
        $pekerjaan->nama	= '';
        return $pekerjaan;
    }
    
    private function _getDataObject() {
		$this->input->post(NULL, TRUE);

        $pekerjaan = new stdClass();
		$pekerjaan->id		= $this->input->post('id') && ($this->input->post('id') > 0) ? $this->input->post('id') : 0;
        $pekerjaan->nama	= $this->input->post('nama');
        return $pekerjaan;
    }
    
}

/* End of file pekerjaan.php */
/* Location: ./application/modules/master/controllers/pekerjaan.php */