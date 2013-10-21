<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kelas extends CI_Controller {

	protected $form = array(
		array(
			'field'		=> 'nama',
			'label'		=> 'Nama',
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
		$this->load->model('master/Kelas_Model');
	}

	public function index()	{
		$this->data['sub_nav'] = "master/kelas/sub_nav";
		$this->template->set_title('Kelas')
					   ->set_css('alertify.core', array('id' => 'toggleCSS'))
					   ->set_js('jquery.dataTables')
					   ->set_js('alertify.min')
			           ->build('master/kelas/browse');
	}

	public function load_data() {
		
		$aColumns = array('nama', 'jenis', 'ordering');
		
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
		
		$kelass = $this->Kelas_Model->getAll($iLimit, $iOffset, $aOrders, array(), $aLikes);
		
		$rResult = $kelass['data'];
		$iFilteredTotal = $kelass['total_rows'];
		$iTotal = $kelass['total_rows'];
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		foreach ($rResult as $i => $kelas) {
			$row = array();
			$row[] = "<a href=\"".site_url("master/kelas/edit/".$kelas->id)."\" data-original-title=\"Edit Kelas\" style=\"color: #0C9ABB\">".$kelas->nama."</a>";
			$row[] = jenis_kelas_descr($kelas->jenis);
			
			$updown = "<div class=\"btn-group\">";
			if ($i > 0) {
				$up = "<a class=\"order_up btn btn-small\" data-id=\"".$kelas->id."\" data-ordering=\"".$kelas->ordering."\" data-original-title=\"\">";
				$up .= "	<i class=\"icon-arrow-up\" data-original-title=\"Share\"></i>";
				$up .= "</a>";
			}
			else {
				$up = "<span style=\"display: inline-block; width: 36px;\"></span>";
			}
			$updown .= $up;
			if ($i < $iTotal - 1) {
				$down = "<a class=\"order_down btn btn-small\" data-id=\"".$kelas->id."\" data-ordering=\"".$kelas->ordering."\" data-original-title=\"\">";
				$down .= "	<i class=\"icon-arrow-down\" data-original-title=\"Report\"></i>";
				$down .= "</a>";
			}
			else {
				$down = "<span style=\"display: inline-block; width: 36px;\"></span>";
			}
			$updown .= $down;
			$updown .= "</div>";
			$row[] = $updown." "."<span class=\"label label\">&nbsp;{$kelas->ordering}&nbsp;</span>";
			
			$action = "<a class=\"delete-row btn btn-danger btn-mini\" href=\"#\" data-id=\"".$kelas->id."\" data-original-title=\"Hapus Kelas\">Hapus</a>";
			
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
            redirect('master/kelas', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $kelas = $this->_getDataObject();
                if (isset($kelas->id) && $kelas->id > 0) {
                    $this->Kelas_Model->update($kelas);
                }
                else {
                    $this->Kelas_Model->create($kelas);
                }
                redirect('master/kelas', 'refresh');
            }
        }
		
		if ($id) {
			$kelas = $this->Kelas_Model->getById($id);
		}
		else {
			$kelas = $this->_getEmptyDataObject();
		}
		
		$this->data['data'] = $kelas;
		
		$this->data['kelas_list'] = get_jenis_kelas();
		
		$this->data['sub_nav'] = "master/kelas/sub_nav";
		$this->template->set_title('Tambah/Edit Kelas')
					   ->set_js('jquery.dataTables')
			           ->build('master/kelas/edit');
    }
    
	public function delete() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Kelas_Model->delete($id);
		}
    }
	
	public function order_up() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$ordering = $this->input->get('ordering');
			$this->Kelas_Model->move(-1, $id, $ordering);
		}
	}
	
	public function order_down() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$ordering = $this->input->get('ordering');
			$this->Kelas_Model->move(1, $id, $ordering);
		}
	}
    
    private function _getEmptyDataObject() {
		$kelas = new stdClass();
		$kelas->id		= 0;
        $kelas->nama	= '';
		$kelas->jenis	= 0;
        return $kelas;
    }
    
    private function _getDataObject() {
		$this->input->post(NULL, TRUE);

        $kelas = new stdClass();
		$kelas->id		= $this->input->post('id') && ($this->input->post('id') > 0) ? $this->input->post('id') : 0;
        $kelas->nama	= $this->input->post('nama');
		$kelas->jenis	= $this->input->post('jenis');
        return $kelas;
    }
    
}

/* End of file kelas.php */
/* Location: ./application/modules/master/controllers/kelas.php */