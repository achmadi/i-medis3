<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kelompok_Pegawai extends CI_Controller {

	protected $form = array(
		array(
			'field'		=> 'nama',
			'label'		=> 'Nama Kelompok',
			'rules'		=> 'xss_clean|required'
		),
		array(
			'field'		=> 'jenis',
			'label'		=> 'jenis',
			'rules'		=> 'xss_clean'
		)
	);

    public function __construct() {
		parent::__construct();
		$this->load->model('master/Kelompok_Pegawai_Model');
	}

	public function index()	{
		$this->data['sub_nav'] = "master/kelompok_pegawai/sub_nav";
		$this->template->set_title('Kelompok Pegawai')
					   ->set_css('alertify.core', array('id' => 'toggleCSS'))
					   ->set_js('jquery.dataTables')
					   ->set_js('alertify.min')
			           ->build('master/kelompok_pegawai/browse');
	}

	public function load_data() {
		
		$aColumns = array('nama', 'jenis');
		
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
		
		$kelompok_pegawais = $this->Kelompok_Pegawai_Model->getAll($iLimit, $iOffset, $aOrders, array(), $aLikes);
		
		$rResult = $kelompok_pegawais['data'];
		$iFilteredTotal = $kelompok_pegawais['total_rows'];
		$iTotal = $kelompok_pegawais['total_rows'];
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		foreach ($rResult as $kelompok) {
			$row = array();
			$row[] = "<a href=\"".site_url("master/kelompok_pegawai/edit/".$kelompok->id)."\" data-original-title=\"Edit Kelompok Pegawai\" style=\"color: #0C9ABB\">".$kelompok->nama."</a>";
			$row[] = get_jenis_kelompok_pegawai_descr($kelompok->jenis);
			
			$action = "<a class=\"delete-row btn btn-danger btn-mini\" href=\"#\" data-id=\"".$kelompok->id."\" data-original-title=\"Hapus Kelompok Pegawai\">Hapus</a>";
			
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
            redirect('master/kelompok_pegawai', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $kelompok_pegawai = $this->_getDataObject();
                if (isset($kelompok_pegawai->id) && $kelompok_pegawai->id > 0) {
                    $this->Kelompok_Pegawai_Model->update($kelompok_pegawai);
                }
                else {
                    $this->Kelompok_Pegawai_Model->create($kelompok_pegawai);
                }
                redirect('master/kelompok_pegawai', 'refresh');
            }
        }
		
		if ($id) {
			$kelompok_pegawai = $this->Kelompok_Pegawai_Model->getById($id);
		}
		else {
			$kelompok_pegawai = $this->_getEmptyDataObject();
		}
		
		$this->data['data'] = $kelompok_pegawai;
		
		$this->data['jenis_list'] = get_jenis_kelompok_pegawai();

		$this->data['sub_nav'] = "master/kelompok_pegawai/sub_nav";
		$this->template->set_title('Tambah/Edit Kelompok Pegawai')
					   ->set_js('jquery.dataTables')
			           ->build('master/kelompok_pegawai/edit');
    }
    
	public function delete() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Kelompok_Pegawai_Model->delete($id);
		}
    }
    
    private function _getEmptyDataObject() {
		$kelompok_pegawai = new stdClass();
		$kelompok_pegawai->id		= 0;
        $kelompok_pegawai->nama		= '';
		$kelompok_pegawai->jenis	= 0;
        return $kelompok_pegawai;
    }
    
    private function _getDataObject() {
		$this->input->post(NULL, TRUE);

        $kelompok_pegawai = new stdClass();
		$kelompok_pegawai->id		= $this->input->post('id') && ($this->input->post('id') > 0) ? $this->input->post('id') : 0;
        $kelompok_pegawai->nama		= $this->input->post('nama');
		$kelompok_pegawai->jenis	= $this->input->post('jenis');
        return $kelompok_pegawai;
    }
    
}

/* End of file kelompok_pegawai.php */
/* Location: ./application/modules/master/controllers/kelompok_pegawai.php */