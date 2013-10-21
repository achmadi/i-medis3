<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cara_Bayar extends ADMIN_Controller {
	
	protected $form = array(
		array(
			'field'		=> 'nama',
			'label'		=> 'Nama',
			'rules'		=> 'xss_clean|required'
		),
		array(
			'field'		=> 'jenis_cara_bayar',
			'label'		=> 'Jenis Cara Pembayaran',
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
		$this->load->model('master/Cara_Bayar_Model');
	}
	
	public function index()	{
		$this->data['sub_nav'] = "master/cara_bayar/sub_nav";
		$this->template->set_title('Cara Pembayaran')
					   ->set_css('alertify.core', array('id' => 'toggleCSS'))
					   ->set_js('jquery.dataTables')
					   ->set_js('alertify.min')
					   ->set_js('jquery.validate.min')
			           ->build('master/cara_bayar/browse');
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
		$aOrders = array('lft' => 'ASC');
		if (isset($_GET['iSortCol_0'])) {
			for ($i = 0; $i <intval($_GET['iSortingCols']); $i++) {
				if ($_GET['bSortable_'.intval($_GET['iSortCol_'.$i])] == "true") {
					$aOrders[$aColumns[intval($_GET['iSortCol_'.$i])]] = $_GET['sSortDir_'.$i] === 'asc' ? 'asc' : 'desc';
				}
			}
		}
		
		/*
		 * Where
		 */
		$aWheres = array('jenis <>' => 'Root');
		
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
		
		$cara_bayars = $this->Cara_Bayar_Model->getAll($iLimit, $iOffset, $aOrders, $aWheres, array(), $aLikes);
		
		$rResult = $cara_bayars['data'];
		$iFilteredTotal = $cara_bayars['total_rows'];
		$iTotal = $cara_bayars['total_rows'];
		
		$ordering = array();
		foreach ($rResult as $cara_bayar) {
			$ordering[$cara_bayar->parent_id][] = $cara_bayar->id;
		}
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		foreach ($rResult as $index => $cara_bayar) {
			
			$orderkey = array_search($cara_bayar->id, $ordering[$cara_bayar->parent_id]);
			
			$row = array();
			$j = $cara_bayar->level - 1;
			$indent = '';
			for ($i = 0; $i < $j; $i++) {
				$indent .= '<span class="gi">|&mdash;</span>';
			}
			$nama = $indent.$cara_bayar->nama;
			$row[] = "<a href=\"#\" class=\"edit-row\" data-id=\"".$cara_bayar->id."\" style=\"color: #0C9ABB\">".$nama."</a>";
			$row[] = get_jenis_cara_bayar_descr($cara_bayar->jenis_cara_bayar);
			
			$order = "";
			$order .= "<div class=\"btn-group\" data-toggle=\"buttons-radio\" style=\"margin-right: 2px;\">";
			if (($index > 0 || ($index + $iOffset > 0)) && isset($ordering[$cara_bayar->parent_id][$orderkey - 1])) {
				$order .= "	<a class=\"order_up btn btn-info btn-mini\" type=\"button\" data-id=\"".$cara_bayar->id."\" >";
				$order .= "		<i class=\"icon-white icon-arrow-up\"></i>";
				$order .= "	</a>";
			}
			else {
				$order .= "<span style=\"display: inline-block; width: 28px;\"></span>";
			}
			if (($index < $iTotal - 1 || $index + $iLimit < $iTotal - 1) && isset($ordering[$cara_bayar->parent_id][$orderkey + 1])) {
				$order .= "	<a class=\"order_down btn btn-info btn-mini\" type=\"button\" data-id=\"".$cara_bayar->id."\">";
				$order .= "		<i class=\" icon-white icon-arrow-down\"></i>";
				$order .= "	</a>";
			}
			else {
				$order .= "<span style=\"display: inline-block; width: 28px;\"></span>";
			}
			$order .= "</div>";
			$order .= "<span class=\"label\">&nbsp;".strval($orderkey + 1)."&nbsp;</span>";
			$row[] = $order;
			
			$action = "<a id=\"".$cara_bayar->id."\" class=\"delete-row btn btn-danger btn-mini\" href=\"#\" data-original-title=\"Hapus\">Hapus</a>";
			
			$row[] = $action;
			
			$output['aaData'][] = $row;
		}
		
		echo json_encode($output);
	}
	
	public function get_data() {
		$cara_bayar_id = $this->input->get('cara_bayar_id');
		
		$output = array();
		if ($cara_bayar_id) {
			$cara_bayar = $this->Cara_Bayar_Model->getBy(array('id' => $cara_bayar_id));
			$output['data'] = array();
			$output['data']['id']						= $cara_bayar->id;
			$output['data']['nama']						= $cara_bayar->nama;
			$output['data']['jenis_cara_bayar']			= $cara_bayar->jenis_cara_bayar;
			$output['data']['jenis']					= $cara_bayar->jenis;
			$output['data']['parent_id']				= $cara_bayar->parent_id;
			$output['data']['old_parent_id']			= $cara_bayar->parent_id;
			$output['data']['version']					= $cara_bayar->version;
		}
		else {
			$output['data'] = array();
			$output['data']['id']						= 0;
			$output['data']['nama']						= '';
			$output['data']['jenis_cara_bayar']			= 0;
			$output['data']['jenis']					= 'Kelompok';
			$output['data']['parent_id']				= 0;
			$output['data']['old_parent_id']			= 0;
			$output['data']['version']					= 0;
		}
		
		$cara_bayars = get_jenis_cara_bayar();
		$aCaraBayar = array();
		foreach ($cara_bayars as $index => $cara_bayar) {
			$aCaraBayar[] = $cara_bayar . "|" . $index;
		}
		$output['data']['jenis_cara_bayar_list'] = $aCaraBayar;
		
		$parent_list = $this->Cara_Bayar_Model->getAll(0, 0, array('lft' => 'ASC'), array('jenis' => 'Kelompok'));
		$output['data']['parent_list'] = $parent_list['data'];
		
		echo json_encode($output);
	}
	
	public function simpan() {
		$cara_bayar = $this->_getDataObject();
		$this->Cara_Bayar_Model->save($cara_bayar);
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
        $this->form_validation->set_rules($this->form);
        
        if ($this->input->post('batal')) {
            redirect('master/cara_bayar', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $cara_bayar = $this->_getDataObject();
				$this->Cara_Bayar_Model->save($cara_bayar);
				//$this->session->set_flashdata('notification', array('type' => 'success', 'message' => 'Data Kegiatan Laboratorium telah di update.'));
                redirect('master/cara_bayar', 'refresh');
            }
        }
		
		if ($id) {
			$cara_bayar = $this->Cara_Bayar_Model->getBy(array('id' => $id));
			$cara_bayar->old_parent_id = $cara_bayar->parent_id;
		}
		else {
			$cara_bayar = $this->_getEmptyDataObject();
		}
		
		$this->data['data'] = $cara_bayar;
		$this->data['jenis_cara_bayar_list'] = get_jenis_cara_bayar();
		
		$parent_list = $this->Cara_Bayar_Model->getAll(0, 0, array('lft' => 'ASC'));
		$this->data['parent_list'] = $parent_list['data'];
		
		$this->data['sub_nav'] = "master/cara_bayar/sub_nav";
		$this->template->set_title('Cara Pembayaran')
					   ->set_js('jquery.dataTables')
			           ->build('master/cara_bayar/edit');
    }
	
	public function delete() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Cara_Bayar_Model->delete($id);
		}
    }
	
	public function order_up() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Cara_Bayar_Model->order_up($id);
		}
	}
	
	public function order_down() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Cara_Bayar_Model->order_down($id);
		}
	}
    
    private function _getEmptyDataObject() {
		$cara_bayar = new stdClass();
		$cara_bayar->id					= 0;
        $cara_bayar->nama				= '';
		$cara_bayar->jenis_cara_bayar	= 0;
		$cara_bayar->jenis				= 'Kelompok';
		$cara_bayar->parent_id			= 0;
		$cara_bayar->old_parent_id		= 0;
		$cara_bayar->version			= 0;
        return $cara_bayar;
    }
    
    private function _getDataObject() {
		$this->input->post(NULL, TRUE);
		
        $cara_bayar = new stdClass();
		$cara_bayar->id					= $this->input->post('id') && ($this->input->post('id') > 0) ? $this->input->post('id') : 0;
        $cara_bayar->nama				= $this->input->post('nama');
		$cara_bayar->jenis_cara_bayar	= $this->input->post('jenis_cara_bayar');
		$cara_bayar->jenis				= $this->input->post('jenis');
		$cara_bayar->parent_id			= $this->input->post('parent_id');
		$cara_bayar->old_parent_id		= $this->input->post('old_parent_id');
		$cara_bayar->version			= $this->input->post('version');
        return $cara_bayar;
    }
    
}

/* End of file cara_bayar.php */
/* Location: ./application/modules/master/controllers/cara_bayar.php */