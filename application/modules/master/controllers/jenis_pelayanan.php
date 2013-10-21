<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jenis_Pelayanan extends CI_Controller {

	protected $form = array(
		array(
			'field'		=> 'nama',
			'label'		=> 'Nama Pelayanan',
			'rules'		=> 'xss_clean|required'
		),
		array(
			'field'		=> 'jenis',
			'label'		=> 'Jenis Unit',
			'rules'		=> 'xss_clean|required'
		)
	);
	
	protected $remunerasi_form = array(
		array(
			'field'		=> 'pemda',
			'label'		=> 'Pemda',
			'rules'		=> 'xss_clean|required'
		)
	);

    public function __construct() {
		parent::__construct();
		$this->load->model('master/Unit_Model');
		$this->load->model('master/Unit_Detail_Model');
	}

	public function index()	{
		$this->data['sub_nav'] = "master/jenis_pelayanan/sub_nav";
		$this->template->set_title('Jenis Pelayanan')
					   ->set_css('alertify.core', array('id' => 'toggleCSS'))
					   ->set_js('jquery.dataTables')
					   ->set_js('alertify.min')
			           ->build('master/jenis_pelayanan/browse');
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
					$aLikes['n.'.$aColumns[$i]] = mysql_real_escape_string($_GET['sSearch']);
				}
			}
		}
		
		$units = $this->Unit_Model->getAll($iLimit, $iOffset, $aOrders, array(), $aLikes);
		
		$rResult = $units['data'];
		$iFilteredTotal = $units['total_rows'];
		$iTotal = $units['total_rows'];
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		foreach ($rResult as $index => $unit) {
			$row = array();
			$row[] = $unit->nama;
			
			$action = "<a class=\"btn btn-success btn-mini\" href=\"".site_url("master/tarif_pelayanan/index?unit_id=".$unit->id)."\" data-original-title=\"Tarif Pelayanan\" title=\"Tarif Pelayanan\">Tarif Pelayanan</a>&nbsp;";
			$action .= "<a class=\"btn btn-success btn-mini\" href=\"".site_url("master/jenis_pelayanan/setup_jp/".$unit->id)."\" data-original-title=\"Setup Jasa Pelayanan\">Setup JP</a>";
			
			$row[] = $action;
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	
	/*
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
            redirect('master/jenis_pelayanan', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $jenis = $this->_getDataObject();
                if (isset($jenis->id) && $jenis->id > 0) {
                    $this->Unit_Model->update($jenis);
                }
                else {
                    $this->Unit_Model->create($jenis);
                }
                redirect('master/jenis_pelayanan', 'refresh');
            }
			else {
				$this->session->set_flashdata('notification', array('type' => 'success' , 'message' => 'GAGAL.'));
			}
        }
		
		if ($id) {
			$jenis = $this->Unit_Model->getById($id);
		}
		else {
			$jenis = $this->_getEmptyDataObject();
		}
		
		$this->data['data'] = $jenis;
		
		$this->data['jenis_list'] = $this->Unit_Model->getJenisUnit();
		
		$this->data['sub_nav'] = "master/jenis_pelayanan/sub_nav";
		$this->template->set_title('Tambah/Edit Jenis Pelayanan Rawat Inap')
					   ->set_js('jquery.dataTables')
			           ->build('master/jenis_pelayanan/edit');
    }
	*/
	public function setup_jp($id = 0) {
		$this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error" style="color: #CC0000;">', '</div>');
		$this->form_validation->set_message('required', 'Field %s diperlukan');
        $this->form_validation->set_rules($this->remunerasi_form);
        
        if ($this->input->post('batal')) {
            redirect('master/jenis_pelayanan', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $jenis = $this->_getDataObject();
				$this->Unit_Model->update($jenis);
                redirect('master/jenis_pelayanan', 'refresh');
            }
			else {
				$this->session->set_flashdata('notification', array('type' => 'success' , 'message' => 'GAGAL.'));
			}
        }
		
		$unit = $this->Unit_Model->getBy(array('id' => $id));
		$root = $this->Unit_Detail_Model->getBy(array('unit_id' => $unit->id, 'unit_detail.jenis' => 'Root'));
		$details = $this->Unit_Detail_Model->get_tree($root->id, 0, 0, array('n.lft' => 'ASC'));
		$unit->details = $details['data'];
		
		$this->data['data'] = $unit;
		
		$this->data['sub_nav'] = "master/jenis_pelayanan/sub_nav2";
		$this->template->set_title('Setup Jasa Pelayanan')
					   ->set_js('autoNumeric')
			           ->build('master/jenis_pelayanan/setup_jp');
    }
    
	public function delete() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Unit_Model->delete($id);
		}
    }
	
	public function order_up() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$ordering = $this->input->get('ordering');
			$this->Unit_Model->move(-1, $id, $ordering);
		}
	}
	
	public function order_down() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$ordering = $this->input->get('ordering');
			$this->Unit_Model->move(1, $id, $ordering);
		}
	}
	
	public function get_penerima_jp() {
		$id = $_GET['penerima_jp_id'];
		$data = $this->Penerima_JP_Model->getAll(0, 0, array('nama' => 'ASC'));
		$penerima_jps = $data['data'];

		$options = "<option value=\"0\">- Pilih Uraian -</option>";
		foreach ($penerima_jps as $penerima_jp) {
			if ($id == $penerima_jp->id)
				$options .= "<option value=\"{$penerima_jp->id}\" selected=\"selected\">{$penerima_jp->nama}</option>";
			else
				$options .= "<option value=\"{$penerima_jp->id}\">{$penerima_jp->nama}</option>";
		}
		echo $options;
	}
    
    private function _getEmptyDataObject() {
		$unit = new stdClass();
		$unit->id			= 0;
        $unit->nama			= '';
		$unit->jenis		= 0;
		$unit->pemda		= 0;
		$unit->dibagikan	= 0;
		$unit->manajemen	= 0;
		$unit->sisa			= 0;
		$unit->details		= array();
        return $unit;
    }
    
    private function _getDataObject() {
		$this->input->post(NULL, TRUE);

        $unit = new stdClass();
		$unit->id = isset($_POST['id']) && ($_POST['id'] > 0) ? $_POST['id'] : 0;
		if ($this->input->post('nama')) {
			$unit->nama = $this->input->post('nama');
		}
		if ($this->input->post('jenis')) {
			$unit->jenis = $this->input->post('jenis');
		}
		if ($this->input->post('pemda')) {
			$pemda = $this->input->post('pemda');
			$unit->pemda = floatval(str_replace(',', '.', str_replace('.', '', $pemda)));
		}
		else {
			$unit->pemda = 0;
		}
		if ($this->input->post('dibagikan')) {
			$dibagikan = $this->input->post('dibagikan');
			$unit->dibagikan = floatval(str_replace(',', '.', str_replace('.', '', $dibagikan)));
		}
		else {
			$unit->dibagikan = 0;
		}
		if ($this->input->post('manajemen')) {
			$manajemen = $this->input->post('manajemen');
			$unit->manajemen = floatval(str_replace(',', '.', str_replace('.', '', $manajemen)));
		}
		else {
			$unit->manajemen = 0;
		}
		if ($this->input->post('sisa')) {
			$sisa = $this->input->post('sisa');
			$unit->sisa = floatval(str_replace(',', '.', str_replace('.', '', $sisa)));
		}
		else {
			$unit->sisa = 0;
		}
		if ($this->input->post('unit_detail_id')) {
			$root = $this->Unit_Detail_Model->getBy(array('unit_id' => $unit->id, 'jenis' => 'Root'));
			$unit_details = $this->Unit_Detail_Model->get_tree($root->id, 0, 0, array('lft' => 'ASC'));
			$unit->details = $unit_details['data'];
			$aDetails = array();
			if ($unit->details) {
				foreach ($unit->details as $detail) {
					if ($detail->jenis != 'Root') {
						$detail->mode_edit = DATA_MODE_DELETE;
						$aDetails[$detail->id] = $detail;
					}
				}
			}
			for ($i = 0; $i < count($_POST['unit_detail_id']); $i++) {
                $detail_id = $_POST['unit_detail_id'][$i];
                if (!array_key_exists(intval($detail_id), $aDetails)) {
                    $detail = new StdClass();
                    $detail->id					= $detail_id;
                    $detail->jenis_pelayanan_id	= $pelayanan->id;
                    $detail->penerima_jp_id		= $_POST['penerima_jp_id'][$i];
					
					if (isset($_POST['proporsi'][$i])) {
						$proporsi = $_POST['proporsi'][$i];
						$detail->proporsi = floatval(str_replace(',', '.', str_replace('.', '', $proporsi)));
					}
					else {
						$detail->proporsi = 0;
					}
					
					if (isset($_POST['langsung'][$i])) {
						$langsung = $_POST['langsung'][$i];
						$detail->langsung = floatval(str_replace(',', '.', str_replace('.', '', $langsung)));
					}
					else {
						$detail->langsung = 0;
					}
					
					if (isset($_POST['kebersamaan'][$i])) {
						$kebersamaan = $_POST['kebersamaan'][$i];
						$detail->kebersamaan = floatval(str_replace(',', '.', str_replace('.', '', $kebersamaan)));
					}
					else {
						$detail->kebersamaan = 0;
					}
					
                    $detail->mode_edit			= DATA_MODE_ADD;
                    $aDetails[$detail_id]		= $detail;
                }
                else {
					
					if (isset($_POST['proporsi'][$i])) {
						$proporsi = $_POST['proporsi'][$i];
						$aDetails[$detail_id]->proporsi = floatval(str_replace(',', '.', str_replace('.', '', $proporsi)));
					}
					else {
						$aDetails[$detail_id]->proporsi = 0;
					}
					
					if (isset($_POST['langsung'][$i])) {
						$langsung = $_POST['langsung'][$i];
						$aDetails[$detail_id]->langsung = floatval(str_replace(',', '.', str_replace('.', '', $langsung)));
					}
					else {
						$aDetails[$detail_id]->langsung = 0;
					}
					
					if (isset($_POST['kebersamaan'][$i])) {
						$kebersamaan = $_POST['kebersamaan'][$i];
						$aDetails[$detail_id]->kebersamaan = floatval(str_replace(',', '.', str_replace('.', '', $kebersamaan)));
					}
					else {
						$aDetails[$detail_id]->kebersamaan = 0;
					}
					
                    $aDetails[$detail_id]->mode_edit	= DATA_MODE_EDIT;
                }
            }
			$unit->details = $aDetails;
		}
        return $unit;
    }
    
}

/* End of file jenis_pelayanan.php */
/* Location: ./application/controllers/jenis_pelayanan.php */