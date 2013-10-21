<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Remunerasi_JP extends ADMIN_Controller {

	protected $form = array(
		array(
			'field'		=> 'pemda',
			'label'		=> 'Pemda',
			'rules'		=> 'xss_clean|required'
		)
	);

    public function __construct() {
		parent::__construct();
		$this->load->model('master/Remunerasi_JP_Model');
		$this->load->model('master/Remunerasi_JP_Detail_Model');
		$this->load->model('master/Jenis_Pelayanan_Model');
		$this->load->model('master/Penerima_JP_Model');
	}

	public function index()	{
		if ($this->input->get('jenis_pelayanan_id')) {
			$jenis_pelayanan_id = $this->input->get('jenis_pelayanan_id');
		}
		else {
			redirect('master/jenis_pelayanan', 'refresh');
		}
		
		$this->data['jenis_pelayanan'] = $this->Jenis_Pelayanan_Model->getById($jenis_pelayanan_id);
		$this->data['sub_nav'] = "master/remunerasi_jp/sub_nav";
		$this->template->set_title('Remunerasi Jasa Pelayanan')
					   ->set_css('alertify.core', array('id' => 'toggleCSS'))
					   ->set_js('jquery.dataTables')
					   ->set_js('alertify.min')
			           ->build('master/remunerasi_jp/browse');
	}

	public function load_data() {
		
		$aColumns = array('pemda', 'dibagikan', 'manajemen', 'sisa');
		
		$jenis_pelayanan_id = $this->input->get("jenis_pelayanan_id");
		
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
		
		$jasa_pelayanans = $this->Remunerasi_JP_Model->getAll($iLimit, $iOffset, $aOrders, array(), $aLikes);
		
		$rResult = $jasa_pelayanans['data'];
		$iFilteredTotal = $jasa_pelayanans['total_rows'];
		$iTotal = $jasa_pelayanans['total_rows'];
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		foreach ($rResult as $jasa_pelayanan) {
			$row = array();
			$row[] = $jasa_pelayanan->pemda;
			$row[] = $jasa_pelayanan->dibagikan;
			$row[] = $jasa_pelayanan->manajemen;
			$row[] = $jasa_pelayanan->sisa;
			
			$action = "<a class=\"btn btn-info btn-mini\" href=\"".site_url("master/remunerasi_jp/edit/".$jasa_pelayanan->id)."\" data-original-title=\"Edit\">Edit</a>&nbsp;";
			$action .= "<a class=\"delete-row btn btn-danger btn-mini\" href=\"#\" data-id=\"".$jasa_pelayanan->id."\" data-original-title=\"Hapus\">Hapus</a>";
			
			$row[] = $action;
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}

	public function add() {
		if ($this->input->get('jenis_pelayanan_id')) {
			$jenis_pelayanan_id = $this->input->get('jenis_pelayanan_id');
		}
		else {
			redirect('master/jenis_pelayanan', 'refresh');
		}
		
		$this->data['jenis_pelayanan_id'] = $jenis_pelayanan_id;
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
            redirect('master/remunerasi_jp', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $remunerasi = $this->_getDataObject();
                if (isset($remunerasi->id) && $remunerasi->id > 0) {
                    $this->Remunerasi_JP_Model->update($remunerasi);
					$this->session->set_flashdata('notification', array('type' => 'success' , 'message' => 'Data Agama telah di update.'));
                }
                else {
                    $this->Remunerasi_JP_Model->create($remunerasi);
					$this->session->set_flashdata('notification', array('type' => 'success' , 'message' => 'Data Agama telah di simpan.'));
                }
                redirect('master/remunerasi_jp', 'refresh');
            }
        }
		
		if ($id) {
			$remunerasi = $this->Remunerasi_JP_Model->getByJenisPelayananId($id);
			if (!$remunerasi) {
				$remunerasi = $this->_getEmptyDataObject();
				$remunerasi->jenis_pelayanan_id = $id;
			}
		}
		else {
			$remunerasi = $this->_getEmptyDataObject();
		}
		
		$this->data['data'] = $remunerasi;
		
		$this->data['sub_nav'] = "master/remunerasi_jp/sub_nav";
		$this->template->set_title('Remunerasi Jasa Pelayanan')
					   ->set_js('autoNumeric')
			           ->build('master/remunerasi_jp/edit');
    }
    
	public function delete() {
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
			$this->Remunerasi_JP_Model->delete($id);
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
		$remunerasi = new stdClass();
		$remunerasi->id					= 0;
		$remunerasi->jenis_pelayanan_id	= 0;
        $remunerasi->pemda				= 0;
		$remunerasi->dibagikan			= 0;
		$remunerasi->manajemen			= 0;
		$remunerasi->sisa				= 0;
		$remunerasi->details			= array();
        return $remunerasi;
    }
    
    private function _getDataObject() {
		$this->input->post(NULL, TRUE);

        $remunerasi = new stdClass();
		$remunerasi->id					= $this->input->post('id') && ($this->input->post('id') > 0) ? $this->input->post('id') : 0;
		$remunerasi->jenis_pelayanan_id	= $this->input->post('jenis_pelayanan_id');
		$pemda = $this->input->post('pemda');
        $remunerasi->pemda				= floatval(str_replace(',', '.', str_replace('.', '', $pemda)));
		$dibagikan = $this->input->post('dibagikan');
		$remunerasi->dibagikan			= floatval(str_replace(',', '.', str_replace('.', '', $dibagikan)));
		$manajemen = $this->input->post('manajemen');
		$remunerasi->manajemen			= floatval(str_replace(',', '.', str_replace('.', '', $manajemen)));
		$sisa = $this->input->post('sisa');
		$remunerasi->sisa				= floatval(str_replace(',', '.', str_replace('.', '', $sisa)));
		
		$remunerasi_details = $this->Remunerasi_JP_Detail_Model->getAll(0, 0, array('ordering' => 'ASC'), array('remunerasi_jp_id' => $remunerasi->id), array());
		$remunerasi->details = $remunerasi_details['data'];
		$aDetails = array();
		if ($remunerasi->details) {
			foreach ($remunerasi->details as $detail) {
				$detail->mode_edit = DATA_MODE_DELETE;
				$aDetails[$detail->id] = $detail;
			}
		}
		if (isset($_POST['remunerasi_detail_id'])) {
            for ($i = 0; $i < count($_POST['remunerasi_detail_id']); $i++) {
                $detail_id = $_POST['remunerasi_detail_id'][$i];
                if (!array_key_exists(intval($detail_id), $aDetails)) {
                    $detail = new StdClass();
                    $detail->id					= $detail_id;
                    $detail->remunerasi_jp_id	= $remunerasi->id;
                    $detail->penerima_jp_id		= $_POST['penerima_jp_id'][$i];
					$detail->proporsi			= $_POST['proporsi'][$i];
					$detail->langsung			= $_POST['langsung'][$i];
					$detail->balancing			= $_POST['balancing'][$i];
					$detail->kebersamaan		= $_POST['kebersamaan'][$i];
                    $detail->mode_edit			= DATA_MODE_ADD;
                    $aDetails[$detail_id]		= $detail;
                }
                else {
                    $aDetails[$detail_id]->penerima_jp_id	= $_POST['penerima_jp_id'][$i];
					$aDetails[$detail_id]->proporsi			= $_POST['proporsi'][$i];
					$aDetails[$detail_id]->langsung			= $_POST['langsung'][$i];
					$aDetails[$detail_id]->balancing		= $_POST['balancing'][$i];
					$aDetails[$detail_id]->kebersamaan		= $_POST['kebersamaan'][$i];
                    $aDetails[$detail_id]->mode_edit		= DATA_MODE_EDIT;
                }
            }
        }
		$remunerasi->details = $aDetails;
		
        return $remunerasi;
    }
    
}

/* End of file remunerasi_jp.php */
/* Location: ./application/controllers/remunerasi_jp.php */