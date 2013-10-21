<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pegawai extends CI_Controller {
	
	protected $form = array(
		array(
			'field'		=> 'nip',
			'label'		=> 'NIP',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'nama',
			'label'		=> 'Nama',
			'rules'		=> 'xss_clean|required'
		),
		array(
			'field'		=> 'no_rekening',
			'label'		=> 'No. Rekening',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'npwp',
			'label'		=> 'NPwP',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'jabatan',
			'label'		=> 'Jabatan',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'golongan_id',
			'label'		=> 'Golongan',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'kelompok_id',
			'label'		=> 'Kelompok',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'unit_id',
			'label'		=> 'Unit Kerja',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'indeks_langsung',
			'label'		=> 'Indeks Langsung',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'indeks_basic',
			'label'		=> 'Indeks Basic',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'indeks_posisi',
			'label'		=> 'Indeks Posisi',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'indeks_emergency',
			'label'		=> 'Indeks Emergency',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'indeks_resiko',
			'label'		=> 'Indeks Resiko',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'indeks_pendidikan',
			'label'		=> 'Indeks Pendidikan',
			'rules'		=> 'xss_clean'
		),
		array(
			'field'		=> 'indeks_masa_kerja',
			'label'		=> 'Indeks Masa Kerja',
			'rules'		=> 'xss_clean'
		)
	);

    public function __construct() {
		parent::__construct();
		$this->load->model('master/Pegawai_Model');
		$this->load->model('master/Golongan_Pegawai_Model');
		$this->load->model('master/Kelompok_Pegawai_Model');
		$this->load->model('master/Unit_Model');
		$this->load->model('master/Gedung_Model');
	}

	public function index()	{
		$this->data['sub_nav'] = "master/pegawai/sub_nav";
		$this->template->set_title('Pegawai')
					   ->set_css('alertify.core', array('id' => 'toggleCSS'))
					   ->set_js('jquery.dataTables')
					   ->set_js('FixedColumns.min')
					   ->set_js('alertify.min')
			           ->build('master/pegawai/browse');
	}
    
	public function load_data() {
		
		$aColumns = array('nip', 'nama', 'no_rekening', 'npwp', 'jabatan', 'golongan', 'kelompok', 'unit', 'indeks_langsung', 'indeks_basic', 'indeks_posisi', 'indeks_emergency', 'indeks_resiko', 'indeks_pendidikan', 'indeks_masa_kerja');
		
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
					switch ($aColumns[$i]) {
						case 'nip':
							$aLikes['pegawai.nip'] = mysql_real_escape_string($_GET['sSearch']);
							break;
						case 'no_rekening':
							$aLikes['pegawai.no_rekening'] = mysql_real_escape_string($_GET['sSearch']);
							break;
						case 'nama':
							$aLikes['pegawai.nama'] = mysql_real_escape_string($_GET['sSearch']);
							break;
						case 'jabatan':
							$aLikes['pegawai.jabatan'] = mysql_real_escape_string($_GET['sSearch']);
							break;
						case 'golongan':
							$aLikes['golongan_pegawai.golongan'] = mysql_real_escape_string($_GET['sSearch']);
							break;
						case 'kelompok':
							$aLikes['kelompok_pegawai.nama'] = mysql_real_escape_string($_GET['sSearch']);
							break;
						case 'unit_kerja':
							$aLikes['unit_kerja_pegawai.nama'] = mysql_real_escape_string($_GET['sSearch']);
							break;
					}
				}
			}
		}
		
		$pegawais = $this->Pegawai_Model->getAll($iLimit, $iOffset, $aOrders, array(), $aLikes);
		
		$rResult = $pegawais['data'];
		$iFilteredTotal = $pegawais['total_rows'];
		$iTotal = $pegawais['total_rows'];
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		foreach ($rResult as $pegawai) {
			$row = array();
			$row[] = "<a href=\"".site_url("master/pegawai/edit/".$pegawai->id)."\" data-original-title=\"Edit Pegawai\" style=\"color: #0C9ABB\">".$pegawai->nip."</a>";
			$row[] = "<a href=\"".site_url("master/pegawai/edit/".$pegawai->id)."\" data-original-title=\"Edit Pegawai\" style=\"color: #0C9ABB\">".$pegawai->nama."</a>";
			$row[] = "<a href=\"".site_url("master/pegawai/edit/".$pegawai->id)."\" data-original-title=\"Edit Pegawai\" style=\"color: #0C9ABB\">".$pegawai->no_rekening."</a>";
			$row[] = $pegawai->npwp;
			$row[] = $pegawai->jabatan;
			$row[] = $pegawai->golongan;
			$row[] = $pegawai->kelompok;
			$row[] = $pegawai->unit;
			$row[] = $pegawai->indeks_langsung;
			$row[] = $pegawai->indeks_basic;
			$row[] = $pegawai->indeks_posisi;
			$row[] = $pegawai->indeks_emergency;
			$row[] = $pegawai->indeks_resiko;
			$row[] = $pegawai->indeks_pendidikan;
			$row[] = $pegawai->indeks_masa_kerja;
			
			$action = "<a class=\"delete-row btn btn-danger btn-mini\" href=\"#\" data-id=\"".$pegawai->id."\" data-original-title=\"Hapus Pegawai\">Hapus</a>";
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
        $this->form_validation->set_error_delimiters('<div class="span12 error" style="color: #CC0000;">', '</div>');
		$this->form_validation->set_message('required', 'Field %s diperlukan');
		$this->form_validation->set_message('greater_than', 'Field %s diperlukan');
        $this->form_validation->set_rules($this->form);
        
        if ($this->input->post('batal')) {
            redirect('master/pegawai', 'refresh');
        }
        if ($this->input->post('simpan')) {
            if ($this->form_validation->run() == TRUE) {
                $pegawai = $this->_getDataObject();
                if (isset($pegawai->id) && $pegawai->id > 0) {
                    $this->Pegawai_Model->update($pegawai);
                }
                else {
                    $this->Pegawai_Model->create($pegawai);
                }
                redirect('master/pegawai', 'refresh');
            }
        }

		if ($id) {
			$pegawai = $this->Pegawai_Model->getBy(array('pegawai.id' => $id));
		}
		else {
			$pegawai = $this->_getEmptyDataObject();
		}
		
		$this->data['data'] = $pegawai;
		
		$golongan_pegawai = $this->Golongan_Pegawai_Model->getAll(0, 0, array('nama' => 'ASC'));
		$this->data['golongan_list']= $golongan_pegawai['data'];
		
		$kelompok_pegawai = $this->Kelompok_Pegawai_Model->getAll(0, 0, array('nama' => 'ASC'));
		$this->data['kelompok_list']= $kelompok_pegawai['data'];
		
		$units = $this->Unit_Model->getAll(0, 0, array('nama' => 'ASC'));
		$this->data['unit_list']= $units['data'];

		$this->data['sub_nav'] = "master/pegawai/sub_nav";
		$this->template->set_title('Tambah/Edit Pegawai')
					   ->set_js('autoNumeric')
			           ->build('master/pegawai/edit');
    }
    
	public function delete() {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
			$this->Pegawai_Model->delete($id);
		}
    }
	
	public function get_gedung() {
		$unit_id = $this->input->get('unit_id');
		
		$unit = $this->Unit_Model->getBy(array('id' => $unit_id));
		$output = array(
			"status" => "OK",
			"data" => array()
		);
		if (unit_is_rawat_inap($unit->jenis)) {
			$options = "<option value=\"0\" selected=\"selected\">- Pilih Ruang -</option>";
			
			$data = $this->Gedung_Model->getAll(0);
			$gedungs = $data['data'];
			
			$gedung_id = $this->input->get('gedung_id');
			
			foreach ($gedungs as $gedung) {
				$continue = true;
				if ($gedung_id > 0) {
					if ($gedung->id == $gedung_id) {
						$options .= "<option value=\"{$gedung->id}\" selected=\"selected\">{$gedung->nama}</option>";
						$continue = false;
					}
				}
				if ($continue)
					$options .= "<option value=\"{$gedung->id}\">{$gedung->nama}</option>";
			}
			
			$output['data'] = $options;
		}
		else {
			$output['status'] = "FAILED";
		}
		echo json_encode($output);
	}
    
    private function _getEmptyDataObject() {
		$pegawai = new stdClass();
		$pegawai->id				= 0;
		$pegawai->nip				= '';
        $pegawai->nama				= '';
		$pegawai->no_rekening		= '';
		$pegawai->npwp				= '';
		$pegawai->jabatan			= '';
		$pegawai->golongan_id		= 0;
		$pegawai->kelompok_id		= 0;
		$pegawai->unit_id			= 0;
		$pegawai->gedung_id			= 0;
		$pegawai->indeks_langsung	= 0;
		$pegawai->indeks_basic		= 20;
		$pegawai->indeks_posisi		= 0;
		$pegawai->indeks_emergency	= 0;
		$pegawai->indeks_resiko		= 0;
		$pegawai->indeks_pendidikan	= 0;
		$pegawai->indeks_masa_kerja	= 0;
        return $pegawai;
    }
    
    private function _getDataObject() {
		$this->input->post(NULL, TRUE);
		
        $pegawai = new stdClass();
		$pegawai->id				= $this->input->post('id') && ($this->input->post('id') > 0) ? $this->input->post('id') : 0;
        $pegawai->nip				= $this->input->post('nip');
		$pegawai->nama				= $this->input->post('nama');
		$pegawai->no_rekening		= $this->input->post('no_rekening');
		$pegawai->npwp				= $this->input->post('npwp');
		$pegawai->jabatan			= $this->input->post('jabatan');
		$pegawai->golongan_id		= $this->input->post('golongan_id');
		$pegawai->kelompok_id		= $this->input->post('kelompok_id');
		$pegawai->unit_id			= $this->input->post('unit_id');
		$pegawai->gedung_id			= $this->input->post('gedung_id');
		if ($this->input->post('indeks_langsung')) {
			$indeks_langsung = $this->input->post('indeks_langsung');
			$pegawai->indeks_langsung = floatval(str_replace(',', '.', str_replace('.', '', $indeks_langsung)));
		}
		else {
			$pegawai->indeks_langsung = 0;
		}
		$pegawai->indeks_basic		= $this->input->post('indeks_basic');
		$pegawai->indeks_posisi		= $this->input->post('indeks_posisi');
		$pegawai->indeks_emergency	= $this->input->post('indeks_emergency');
		$pegawai->indeks_resiko		= $this->input->post('indeks_resiko');
		$pegawai->indeks_pendidikan	= $this->input->post('indeks_pendidikan');
		$pegawai->indeks_masa_kerja	= $this->input->post('indeks_masa_kerja');
        return $pegawai;
    }
    
}

/* End of file pegawai.php */
/* Location: ./application/modules/master/controllers/pegawai.php */