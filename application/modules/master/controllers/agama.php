<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agama extends ADMIN_Controller {

	protected $form = array(
		array(
			'field'		=> 'nama',
			'label'		=> 'Nama Agama',
			'rules'		=> 'xss_clean|required'
		)
	);

    public function __construct() {
		parent::__construct();
		$this->load->model('master/Agama_Model');
	}

	public function index()	{
		$this->data['sub_nav'] = "master/agama/sub_nav";
		$this->template->set_title('Agama')
					   ->set_css('alertify.core', array('id' => 'toggleCSS'))
					   ->set_js('jquery.dataTables')
					   ->set_js('alertify.min')
					   ->set_js('jquery.validate.min')
			           ->build('master/agama/browse');
	}

	public function load_data() {
		
		$aColumns = array('nama', 'ordering');
		
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
					if ($aColumns[$i] != 'ordering') {
						$aLikes[$aColumns[$i]] = mysql_real_escape_string($_GET['sSearch']);
					}
				}
			}
		}
		
		$agamas = $this->Agama_Model->getAll($iLimit, $iOffset, $aOrders, array(), $aLikes);
		
		$rResult = $agamas['data'];
		$iFilteredTotal = $agamas['total_rows'];
		$iTotal = $agamas['total_rows'];
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		foreach ($rResult as $i => $agama) {
			$row = array();
			$row[] = "<a href=\"#\" class=\"edit-row\" data-id=\"".$agama->id."\" style=\"color: #0C9ABB\">".$agama->nama."</a>";
			
			$updown = "<div class=\"btn-group\">";
			if ($i > 0) {
				$up = "<a class=\"order_up btn btn-small\" data-id=\"".$agama->id."\" data-ordering=\"".$agama->ordering."\" data-original-title=\"\">";
				$up .= "	<i class=\"icon-arrow-up\" data-original-title=\"Share\"></i>";
				$up .= "</a>";
			}
			else {
				$up = "<span style=\"display: inline-block; width: 36px;\"></span>";
			}
			$updown .= $up;
			if ($i < $iTotal - 1) {
				$down = "<a class=\"order_down btn btn-small\" data-id=\"".$agama->id."\" data-ordering=\"".$agama->ordering."\" data-original-title=\"\">";
				$down .= "	<i class=\"icon-arrow-down\" data-original-title=\"Report\"></i>";
				$down .= "</a>";
			}
			else {
				$down = "<span style=\"display: inline-block; width: 36px;\"></span>";
			}
			$updown .= $down;
			$updown .= "</div>";
			$row[] = $updown." "."<span class=\"label label\">&nbsp;{$agama->ordering}&nbsp;</span>";
			
			$action = "<a class=\"delete-row btn btn-danger btn-mini\" href=\"#\" data-id=\"".$agama->id."\" data-original-title=\"Hapus\">Hapus</a>";
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
            redirect('master/agama', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $agama = $this->_getDataObject();
                if (isset($agama->id) && $agama->id > 0) {
                    $this->Agama_Model->update($agama);
					$this->session->set_flashdata('notification', array('type' => 'success' , 'message' => 'Data Agama telah di update.'));
                }
                else {
                    $this->Agama_Model->create($agama);
					$this->session->set_flashdata('notification', array('type' => 'success' , 'message' => 'Data Agama telah di simpan.'));
                }
                redirect('master/agama', 'refresh');
            }
			else {
				$this->session->set_flashdata('notification', array('type' => 'success' , 'message' => 'Data Agama telah di update.'));
			}
        }
		
		if ($id) {
			$agama = $this->Agama_Model->getById($id);
		}
		else {
			$agama = $this->_getEmptyDataObject();
		}
		
		$this->data['data'] = $agama;
		
		$this->data['sub_nav'] = "master/agama/sub_nav";
		$this->template->set_title('Tambah/Edit Agama')
					   ->set_js('jquery.dataTables')
			           ->build('master/agama/edit');
    }
    
	public function delete() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Agama_Model->delete($id);
		}
    }
	
	public function simpan() {
		$agama = $this->_getDataObject();
		if (isset($agama->id) && $agama->id > 0) {
			$this->Agama_Model->update($agama);
		}
		else {
			$this->Agama_Model->create($agama);
		}
		echo "ok";
	}
	
	public function get_agama($id) {
		$agama = $this->Agama_Model->getById($id);
		$aAgama = get_object_vars($agama);
        echo json_encode(array("agama" => $aAgama));
    }
	
	public function order_up() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$ordering = $this->input->get('ordering');
			$this->Agama_Model->move(-1, $id, $ordering);
		}
	}
	
	public function order_down() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$ordering = $this->input->get('ordering');
			$this->Agama_Model->move(1, $id, $ordering);
		}
	}
    
    private function _getEmptyDataObject() {
		$agama = new stdClass();
		$agama->id		= 0;
        $agama->nama	= '';
        return $agama;
    }
    
    private function _getDataObject() {
		$this->input->post(NULL, TRUE);

        $agama = new stdClass();
		$agama->id		= $this->input->post('id') && $this->input->post('id') > 0 ? $this->input->post('id') : 0;
        $agama->nama	= $this->input->post('nama');
        return $agama;
    }
    
}

/* End of file agama.php */
/* Location: ./application/controllers/agama.php */