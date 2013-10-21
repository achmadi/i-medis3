<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kelompok_Pelayanan_Askes extends ADMIN_Controller {
	
	protected $form = array(
		array(
			'field'		=> 'nama',
			'label'		=> 'Nama',
			'rules'		=> 'xss_clean|required'
		)
	);
	
    public function __construct() {
		parent::__construct();
		$this->load->model('master/Kelompok_Pelayanan_Askes_Model');
	}
	
	public function index()	{
		$this->data['sub_nav'] = "master/kelompok_pelayanan_askes/sub_nav";
		$this->template->set_title('Kelompok Pelayanan Askes')
					   ->set_css('alertify.core', array('id' => 'toggleCSS'))
					   ->set_js('jquery.dataTables')
					   ->set_js('alertify.min')
			           ->build('master/kelompok_pelayanan_askes/browse');
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
					if ($aColumns[$i] != 'ordering') {
						$aOrders[$aColumns[intval($_GET['iSortCol_'.$i])]] = $_GET['sSortDir_'.$i] === 'asc' ? 'asc' : 'desc';
					}
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
		
		$units = $this->Kelompok_Pelayanan_Askes_Model->getAll($iLimit, $iOffset, $aOrders, array(), $aLikes);
		
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
		
		foreach ($rResult as $kelompok) {
			$row = array();
			$row[] = "<a href=\"".site_url("master/kelompok_pelayanan_askes/edit/".$kelompok->id)."\" style=\"color: #0C9ABB\">".$kelompok->nama."</a>";
			
			$action = "<a class=\"btn btn-success btn-mini\" title=\"Tarif Pelayanan\" data-original-title=\"Tarif Pelayanan\" href=\"".site_url('master/komponen_tarif_askes?kelompok_pelayanan_askes_id='.$kelompok->id)."\">Tarif Pelayanan</a>&nbsp;";
			$action .= "<a class=\"delete-row btn btn-danger btn-mini\" href=\"#\" data-id=\"".$kelompok->id."\" data-original-title=\"Hapus\">Hapus</a>";
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
            redirect('master/kelompok_pelayanan_askes', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run()) {
                $unit = $this->_getDataObject();
                if (isset($unit->id) && $unit->id > 0) {
                    $this->Kelompok_Pelayanan_Askes_Model->update($unit);
                }
                else {
                    $this->Kelompok_Pelayanan_Askes_Model->create($unit);
                }
                redirect('master/kelompok_pelayanan_askes', 'refresh');
            }
        }
		
		if (!empty($id)) {
			$unit = $this->Kelompok_Pelayanan_Askes_Model->getBy(array('id' => $id));
		}
		else {
			$unit = $this->_getEmptyDataObject();
		}
		
		$this->data['data'] = $unit;
		
		$this->data['sub_nav'] = "master/kelompok_pelayanan_askes/sub_nav";
		$this->template->set_title('Tambah/Edit Kelompok Pelayanan Askes')
					   ->set_js('jquery.dataTables')
			           ->build('master/kelompok_pelayanan_askes/edit');
    }
	
	public function delete() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Kelompok_Pelayanan_Askes_Model->delete($id);
		}
    }
    
    private function _getEmptyDataObject() {
		$unit = new stdClass();
		$unit->id		= 0;
        $unit->nama		= '';
		$unit->version	= 0;
        return $unit;
    }
    
    private function _getDataObject() {
		$this->input->post(NULL, TRUE);
		
        $unit = new stdClass();
		$unit->id		= $this->input->post('id') && ($this->input->post('id') > 0) ? $this->input->post('id') : 0;
        $unit->nama		= $this->input->post('nama');
		$unit->version	= $this->input->post('version');
        return $unit;
    }
    
}

/* End of file unit.php */
/* Location: ./application/modules/master/controllers/kelompok_pelayanan_askes.php */