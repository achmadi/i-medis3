<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penerima_JP extends ADMIN_Controller {

	protected $form = array(
		array(
			'field'		=> 'nama',
			'label'		=> 'Nama',
			'rules'		=> 'xss_clean|required'
		)
	);

    public function __construct() {
		parent::__construct();
		$this->load->model('master/Penerima_JP_Model');
	}

	public function index()	{
		$this->data['sub_nav'] = "master/penerima_jp/sub_nav";
		$this->template->set_title('Penerima JP')
					   ->set_css('alertify.core', array('id' => 'toggleCSS'))
					   ->set_js('jquery.dataTables')
					   ->set_js('alertify.min')
			           ->build('master/penerima_jp/browse');
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
		
		$penerima_jps = $this->Penerima_JP_Model->getAll($iLimit, $iOffset, $aOrders, array(), $aLikes);
		
		$rResult = $penerima_jps['data'];
		$iFilteredTotal = $penerima_jps['total_rows'];
		$iTotal = $penerima_jps['total_rows'];
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		foreach ($rResult as $penerima_jp) {
			$row = array();
			$row[] = $penerima_jp->nama;
			
			$action = "<a class=\"btn btn-info btn-mini\" href=\"".site_url("master/penerima_jp/edit/".$penerima_jp->id)."\" data-original-title=\"Edit\">Edit</a>&nbsp;";
			$action .= "<a id=\"".$penerima_jp->id."\" class=\"delete-row btn btn-danger btn-mini\" href=\"#\" data-original-title=\"Hapus\">Hapus</a>";
			
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
        $this->form_validation->set_error_delimiters('<div class="error" style="color: #CC0000;">', '</div>');
		$this->form_validation->set_message('required', 'Field %s diperlukan');
        $this->form_validation->set_rules($this->form);
        
        if ($this->input->post('batal')) {
            redirect('master/penerima_jp', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $penerima_jp = $this->_getDataObject();
                if (isset($penerima_jp->id) && $penerima_jp->id > 0) {
                    $this->Penerima_JP_Model->update($penerima_jp);
					$this->session->set_flashdata('notification', array('type' => 'success' , 'message' => 'Data Agama telah di update.'));
                }
                else {
                    $this->Penerima_JP_Model->create($penerima_jp);
					$this->session->set_flashdata('notification', array('type' => 'success' , 'message' => 'Data Agama telah di simpan.'));
                }
                redirect('master/penerima_jp', 'refresh');
            }
			else {
				$this->session->set_flashdata('notification', array('type' => 'success' , 'message' => 'Data Agama telah di update.'));
			}
        }
		
		if ($id) {
			$penerima_jp = $this->Penerima_JP_Model->getById($id);
		}
		else {
			$penerima_jp = $this->_getEmptyDataObject();
		}
		
		$this->data['data'] = $penerima_jp;
		
		$this->data['sub_nav'] = "master/penerima_jp/sub_nav";
		$this->template->set_title('Tambah/Edit Penerima JP')
					   ->set_js('jquery.dataTables')
			           ->build('master/penerima_jp/edit');
    }
    
	public function delete() {
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
			$this->Penerima_JP_Model->delete($id);
		}
    }
    
    private function _getEmptyDataObject() {
		$penerima_jp = new stdClass();
		$penerima_jp->id	= 0;
        $penerima_jp->nama	= '';
        return $penerima_jp;
    }
    
    private function _getDataObject() {
		$this->input->post(NULL, TRUE);

        $penerima_jp = new stdClass();
		$penerima_jp->id	= isset($_POST['id']) && ($_POST['id'] > 0) ? $_POST['id'] : 0;
        $penerima_jp->nama	= $_POST['nama'];
        return $penerima_jp;
    }
    
}

/* End of file penerima_jp.php */
/* Location: ./application/controllers/penerima_jp.php */