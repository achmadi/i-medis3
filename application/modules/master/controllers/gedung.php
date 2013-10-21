<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gedung extends ADMIN_Controller {
	
	protected $form = array(
		array(
			'field'		=> 'nama',
			'label'		=> 'Nama',
			'rules'		=> 'xss_clean|required'
		),
		array(
			'field'		=> 'bagian',
			'label'		=> 'Bagian',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'kelas',
			'label'		=> 'Kelas',
			'rules'		=> 'xss_clean|greater_than[0]'
		)
	);

    public function __construct() {
		parent::__construct();
		$this->load->model('master/Gedung_Model');
		$this->load->model('master/Kelas_Model');
		$this->load->model('master/Gedung_Kelas_Model');
	}
	
	public function index()	{
		$this->data['sub_nav'] = "master/gedung/sub_nav";
		$this->template->set_title('Gedung')
					   ->set_css('alertify.core', array('id' => 'toggleCSS'))
					   ->set_js('jquery.dataTables')
					   ->set_js('alertify.min')
					   ->set_js('jquery.validate.min')
			           ->build('master/gedung/browse');
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
					if ($aColumns[$i] == 'nama') {
						$aLikes['nama'] = mysql_real_escape_string($_GET['sSearch']);
						$aLikes['bagian'] = mysql_real_escape_string($_GET['sSearch']);
					}
				}
			}
		}
		
		$gedungs = $this->Gedung_Model->getAll($iLimit, $iOffset, $aOrders, array(), $aLikes);
		
		$rResult = $gedungs['data'];
		$iFilteredTotal = $gedungs['total_rows'];
		$iTotal = $gedungs['total_rows'];
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		foreach ($rResult as $gedung) {
			$row = array();
			$row[] = "<a href=\"#\" class=\"edit-row\" data-id=\"".$gedung->id."\" style=\"color: #0C9ABB\">".$gedung->nama.(empty($gedung->bagian) ? "" : " (".$gedung->bagian.")")."</a>";
			$row[] = $gedung->kelas;
			
			$action = "<a class=\"btn btn-success btn-mini\" href=\"".site_url("master/ruangan?gedung_id=".$gedung->id)."\" data-original-title=\"Ruangan\" title=\"Tambah Ruangan\">Ruangan</a>&nbsp;";
			$action .= "<a id=\"".$gedung->id."\" class=\"delete-row btn btn-danger btn-mini\" href=\"#\" data-original-title=\"Hapus\">Hapus</a>";
			
			$row[] = $action;
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	
	public function get_data() {
		$id = $this->input->get('gedung_id');
		
		$kelas = $this->Kelas_Model->getAll(0);
		
		$output = array();
		if ($id) {
			$gedung = $this->Gedung_Model->getBy(array('id' => $id));
			$output['data'] = array();
			$output['data']['id']		= $gedung->id;
			$output['data']['nama']		= $gedung->nama;
			$output['data']['bagian']	= $gedung->bagian;
			$gedung_kelas_list = $this->Gedung_Kelas_Model->getAll(0, 0, array(), array('gedung_id' => $id));
			$output['data']['kelass']	= $gedung_kelas_list['data'];
		}
		else {
			$output['data'] = array();
			$output['data']['id']			= 0;
			$output['data']['nama']			= '';
			$output['data']['bagian']	= '';
			$output['data']['kelass']		= array();
		}
		$output['data']['kelas_list'] = $kelas['data'];
		echo json_encode($output);
	}
	
	public function simpan() {
		$gedung = $this->_getDataObject();
		if (isset($gedung->id) && $gedung->id > 0) {
			$this->Gedung_Model->update($gedung);
		}
		else {
			$this->Gedung_Model->create($gedung);
		}
		echo "ok";
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
		$this->form_validation->set_message('greater_than', 'Field %s diperlukan');
        $this->form_validation->set_rules($this->form);
        
        if ($this->input->post('batal')) {
            redirect('master/gedung', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $gedung = $this->_getDataObject();
                if (isset($gedung->id) && $gedung->id > 0) {
                    $this->Gedung_Model->update($gedung);
                }
                else {
                    $this->Gedung_Model->create($gedung);
                }
                redirect('master/gedung', 'refresh');
            }
        }
		
		if ($id) {
			$gedung = $this->Gedung_Model->getBy(array('id' => $id));
		}
		else {
			$gedung = $this->_getEmptyDataObject();
		}
		
		$this->data['data'] = $gedung;
		$kelas = $this->Kelas_Model->getAll(0, 0, array('ordering' => 'ASC'));
		$this->data['kelas_list'] = $kelas['data'];
		
		$this->data['sub_nav'] = "master/gedung/sub_nav";
		$this->template->set_title('Cara Pembayaran')
					   ->set_js('jquery.dataTables')
			           ->build('master/gedung/edit');
    }
	
	public function delete() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Gedung_Model->delete($id);
		}
    }
	
	public function get_kelas() {
		$jenis_pelayanan_ri_id = $_GET['jenis_pelayanan_ri_id'];
		$options = "<option value=\"0\" selected=\"selected\">- Pilih Kelas -</option>";
		if ($jenis_pelayanan_ri_id) {
			$kelass = $this->Jenis_Pelayanan_RI_Model->getKelas($jenis_pelayanan_ri_id);
			
			$kls = isset($_GET['kelas']) ? $_GET['kelas'] : 0;

			foreach ($kelass as $kelas) {
				$continue = true;
				if ($kls > 0) {
					if ($kelas->id == $kls) {
						$options .= "<option value=\"{$kelas->id}\" selected=\"selected\">{$kelas->nama}</option>";
						$continue = false;
					}
				}
				if ($continue)
					$options .= "<option value=\"{$kelas->id}\">{$kelas->nama}</option>";
			}
		}
		echo $options;
	}
    
    private function _getEmptyDataObject() {
		$gedung = new stdClass();
		$gedung->id			= 0;
        $gedung->nama		= '';
		$gedung->bagian		= '';
		$gedung->kelass	= array();
        return $gedung;
    }
    
    private function _getDataObject() {
		$this->input->post(NULL, TRUE);
		
        $gedung = new stdClass();
		$gedung->id			= $this->input->post('id') && ($this->input->post('id') > 0) ? $this->input->post('id') : 0;
        $gedung->nama		= $this->input->post('nama');
		$gedung->bagian		= $this->input->post('bagian');
		
		$kelass = $this->Gedung_Kelas_Model->getAll(0, 0, array(), array('gedung_id' => $gedung->id));
		$gedung->kelass = $kelass['data'];
		$aKelass = array();
		if ($gedung->kelass) {
			foreach ($gedung->kelass as $kelas) {
				$kelas->data_mode = DATA_MODE_DELETE;
				$aKelass[$kelas->id] = $kelas;
			}
		}
		if (isset($_POST['kelas_id'])) {
            for ($i = 0; $i < count($_POST['kelas_id']); $i++) {
                $ids = explode('|', $_POST['kelas_id'][$i]);
				$gedung_kelas_id = $ids[1];
                if (!array_key_exists(intval($gedung_kelas_id), $aKelass)) {
                    $kelas = new StdClass();
                    $kelas->id			= $gedung_kelas_id;
                    $kelas->gedung_id	= $gedung->id;
                    $kelas->kelas_id	= $ids[0];
                    $kelas->data_mode	= DATA_MODE_ADD;
                    $aKelass[$gedung_kelas_id] = $kelas;
                }
                else {
                    $aKelass[$gedung_kelas_id]->kelas_id	= $ids[0];
                    $aKelass[$gedung_kelas_id]->data_mode	= DATA_MODE_EDIT;
                }
            }
        }
		$gedung->kelass = $aKelass;
		
        return $gedung;
    }
    
}

/* End of file gedung.php */
/* Location: ./application/modules/master/controllers/gedung.php */