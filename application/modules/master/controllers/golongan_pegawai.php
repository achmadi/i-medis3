<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Golongan_Pegawai extends CI_Controller {

	protected $form = array(
		array(
			'field'		=> 'nama',
			'label'		=> 'Nama',
			'rules'		=> 'xss_clean|required'
		)
	);

    public function __construct() {
		parent::__construct();
		$this->load->model('master/Golongan_Pegawai_Model');
	}

	public function index()	{
		$this->data['sub_nav'] = "master/golongan_pegawai/sub_nav";
		$this->template->set_title('Golongan Pegawai')
					   ->set_css('alertify.core', array('id' => 'toggleCSS'))
					   ->set_js('jquery.dataTables')
					   ->set_js('alertify.min')
			           ->build('master/golongan_pegawai/browse');
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
		
		$golongans = $this->Golongan_Pegawai_Model->getAll($iLimit, $iOffset, $aOrders, array(), $aLikes);
		
		$rResult = $golongans['data'];
		$iFilteredTotal = $golongans['total_rows'];
		$iTotal = $golongans['total_rows'];
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		foreach ($rResult as $golongan) {
			$row = array();
			$row[] = "<a href=\"".site_url("master/golongan_pegawai/edit/".$golongan->id)."\" data-original-title=\"Edit Golongan Pegawai\" style=\"color: #0C9ABB\">".$golongan->nama."</a>";
			$row[] = $golongan->golongan;
			$row[] = $golongan->pajak;
			
			$action = "<a class=\"delete-row btn btn-danger btn-mini\" href=\"#\" data-id=\"".$golongan->id."\" data-original-title=\"Hapus Golongan Pegawai\">Hapus</a>";
			
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
            redirect('master/golongan_pegawai', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $golongan = $this->_getDataObject();
                if (isset($golongan->id) && $golongan->id > 0) {
                    $this->Golongan_Pegawai_Model->update($golongan);
                }
                else {
                    $this->Golongan_Pegawai_Model->create($golongan);
                }
                redirect('master/golongan_pegawai', 'refresh');
            }
        }
		
		if ($id) {
			$golongan = $this->Golongan_Pegawai_Model->getBy(array('id' => $id));
		}
		else {
			$golongan = $this->_getEmptyDataObject();
		}
		
		$this->data['data'] = $golongan;
		
		$this->data['golongan_list'] = $this->Golongan_Pegawai_Model->get_golongan();

		$this->data['sub_nav'] = "master/golongan_pegawai/sub_nav";
		$this->template->set_title('Tambah/Edit Golongan Pegawai')
					   ->set_js('autoNumeric')
			           ->build('master/golongan_pegawai/edit');
    }
    
	public function delete() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Golongan_Pegawai_Model->delete($id);
		}
    }
    
    private function _getEmptyDataObject() {
		$golongan = new stdClass();
		$golongan->id		= 0;
        $golongan->nama		= '';
		$golongan->golongan	= '';
		$golongan->pajak	= '';
        return $golongan;
    }
    
    private function _getDataObject() {
		$this->input->post(NULL, TRUE);

        $golongan = new stdClass();
		$golongan->id		= $this->input->post('id') && ($this->input->post('id') > 0) ? $this->input->post('id') : 0;
        $golongan->nama		= $this->input->post('nama');
		$golongan->golongan	= $this->input->post('golongan');
		$golongan->pajak	= $this->input->post('pajak');
        return $golongan;
    }
    
}

/* End of file golongan_pegawai.php */
/* Location: ./application/modules/master/controllers/golongan_pegawai.php */