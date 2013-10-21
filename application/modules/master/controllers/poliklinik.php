<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Poliklinik extends ADMIN_Controller {
	
	protected $form = array(
		array(
			'field'		=> 'nama',
			'label'		=> 'Nama',
			'rules'		=> 'xss_clean|required'
		)
	);
	
    public function __construct() {
		parent::__construct();
		$this->load->model('master/Poliklinik_Model');
		$this->load->model('master/Pegawai_Model');
		$this->load->model('master/Poliklinik_Pegawai_Model');
	}
	
	public function index()	{
		$this->data['sub_nav'] = "master/poliklinik/sub_nav";
		$this->template->set_title('Poliklinik')
					   ->set_css('alertify.core', array('id' => 'toggleCSS'))
					   ->set_js('jquery.dataTables')
					   ->set_js('alertify.min')
			           ->build('master/poliklinik/browse');
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
		
		$polikliniks = $this->Poliklinik_Model->getAll($iLimit, $iOffset, $aOrders, array(), $aLikes);
		
		$rResult = $polikliniks['data'];
		$iFilteredTotal = $polikliniks['total_rows'];
		$iTotal = $polikliniks['total_rows'];
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		foreach ($rResult as $poliklinik) {
			$row = array();
			$row[] = "<a href=\"".site_url("master/poliklinik/edit/".$poliklinik->id)."\" data-original-title=\"Edit Poliklinik\" style=\"color: #0C9ABB\">".$poliklinik->nama."</a>";
			
			$action = "<a class=\"delete-row btn btn-danger btn-mini\" href=\"#\" data-id=\"".$poliklinik->id."\" data-original-title=\"Hapus\">Hapus</a>";
			
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
            redirect('master/poliklinik', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run()) {
                $poliklinik = $this->_getDataObject();
                if (isset($poliklinik->id) && $poliklinik->id > 0) {
                    $this->Poliklinik_Model->update($poliklinik);
                }
                else {
                    $this->Poliklinik_Model->create($poliklinik);
                }
                redirect('master/poliklinik', 'refresh');
            }
        }
		
		if (!empty($id)) {
			$poliklinik = $this->Poliklinik_Model->getBy(array('id' => $id));
			$dokters = $this->Poliklinik_Pegawai_Model->getAll(0, 0, array('id' => 'ASC'), array('poliklinik_id' => $poliklinik->id));
			if ($dokters)
				$poliklinik->dokters = $dokters['data'];
			else
				$poliklinik->dokters = array();
		}
		else {
			$poliklinik = $this->_getEmptyDataObject();
		}
		
		$this->data['data'] = $poliklinik;
		
		$this->data['sub_nav'] = "master/poliklinik/sub_nav";
		$this->template->set_title('Tambah/Edit Poliklinik')
					   ->set_js('jquery.dataTables')
			           ->build('master/poliklinik/edit');
    }
	
	public function delete() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Poliklinik_Model->delete($id);
		}
    }
	
	public function get_dokter() {
		$id = $this->input->get('dokter_id');
		$data = $this->Pegawai_Model->getAll(0, 0, array('nama' => 'ASC'));
		$dokters = $data['data'];

		$options = "<option value=\"0\">- Pilih Dokter -</option>";
		foreach ($dokters as $dokter) {
			if ($id == $dokter->id)
				$options .= "<option value=\"{$dokter->id}\" selected=\"selected\">{$dokter->nama}</option>";
			else
				$options .= "<option value=\"{$dokter->id}\">{$dokter->nama}</option>";
		}
		echo $options;
	}
    
    private function _getEmptyDataObject() {
		$poliklinik = new stdClass();
		$poliklinik->id			= 0;
        $poliklinik->nama		= '';
		$poliklinik->dokters	= array();
        return $poliklinik;
    }
    
    private function _getDataObject() {
		$this->input->post(NULL, TRUE);
		
        $poliklinik = new stdClass();
		$poliklinik->id		= $this->input->post('id') && ($this->input->post('id') > 0) ? $this->input->post('id') : 0;
        $poliklinik->nama	= $this->input->post('nama');
		
		$dokters = $this->Poliklinik_Pegawai_Model->getAll(0, 0, array('id' => 'ASC'), array('poliklinik_id' => $poliklinik->id));
		$poliklinik->dokters = $dokters['data'];
		$aDokters = array();
		if ($poliklinik->dokters) {
			foreach ($poliklinik->dokters as $dokter) {
				unset($dokter->poliklinik);
				unset($dokter->pegawai);
				$dokter->data_mode = DATA_MODE_DELETE;
				$aDokters[$dokter->id] = $dokter;
			}
		}
		if (isset($_POST['unit_dokter_id'])) {
            for ($i = 0; $i < count($_POST['unit_dokter_id']); $i++) {
                $poliklinik_pegawai_id = $_POST['unit_dokter_id'][$i];
                if (!array_key_exists(intval($poliklinik_pegawai_id), $aDokters)) {
                    $dokter = new StdClass();
                    $dokter->id				= $poliklinik_pegawai_id;
                    $dokter->poliklinik_id	= $poliklinik->id;
                    $dokter->pegawai_id		= $_POST['dokter_id'][$i];
                    $dokter->data_mode		= DATA_MODE_ADD;
                    $aDokters[$poliklinik_pegawai_id] = $dokter;
                }
                else {
                    $aDokters[$poliklinik_pegawai_id]->pegawai_id	= $_POST['dokter_id'][$i];
                    $aDokters[$poliklinik_pegawai_id]->data_mode	= DATA_MODE_EDIT;
                }
            }
        }
		$poliklinik->dokters = $aDokters;
		
        return $poliklinik;
    }
    
}

/* End of file poliklinik.php */
/* Location: ./application/modules/master/controllers/poliklinik.php */