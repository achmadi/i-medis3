<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Options extends ADMIN_Controller {

    public function __construct() {
		parent::__construct();
		$this->load->helper('option_helper');
		$this->load->model('master/Wilayah_Model');
	}

	public function index()	{
		
		if ($this->input->post('batal')) {
            redirect('', 'refresh');
        }
		if ($this->input->post('simpan')) {
            $this->_save_options();
			redirect('', 'refresh');
        }
		
		$this->data['data'] = $this->_load_options();
		
		$provinsis = $this->Wilayah_Model->getAll(0, 0, array('lft' => 'ASC'), array('jenis' => 1));
		$this->data['provinsi_list'] = $provinsis['data'];
		
		$this->data['sub_nav'] = "master/options/sub_nav";
		$this->template->set_title('Options')
					   ->set_js('jquery-barcode.min')
			           ->build('master/options/edit');
	}
	
	public function get_provinsi() {
		$provinsi_id = $this->input->get('provinsi_id');
		$options = "<option value=\"0\" selected=\"selected\">- Pilih Provinsi -</option>";
		if ($provinsi_id) {
			$data = $this->Wilayah_Model->getAll(0, 0, array('lft' => 'ASC'), array('parent_id' => 1));
			$provinsis = $data['data'];
			
			foreach ($provinsis as $provinsi) {
				$continue = true;
				if ($provinsi_id > 0) {
					if ($provinsi->id == $provinsi_id) {
						$options .= "<option value=\"{$provinsi->id}\" selected=\"selected\">{$provinsi->nama}</option>";
						$continue = false;
					}
				}
				if ($continue)
					$options .= "<option value=\"{$provinsi->id}\">{$provinsi->nama}</option>";
			}
		}
		echo $options;
	}
	
	public function get_kabupaten() {
		$provinsi_id = $this->input->get('provinsi_id');
		$options = "<option value=\"0\" selected=\"selected\">- Pilih Kabupaten/Kota -</option>";
		if ($provinsi_id) {
			$data = $this->Wilayah_Model->getAll(0, 0, array('lft' => 'ASC'), array('parent_id' => $provinsi_id));
			$kabupatens = $data['data'];
			
			$kabupaten_id = $this->input->get('kabupaten_id') ? $this->input->get('kabupaten_id') : 0;
			
			foreach ($kabupatens as $kabupaten) {
				$continue = true;
				if ($kabupaten_id > 0) {
					if ($kabupaten->id == $kabupaten_id) {
						$options .= "<option value=\"{$kabupaten->id}\" selected=\"selected\">{$kabupaten->nama}</option>";
						$continue = false;
					}
				}
				if ($continue)
					$options .= "<option value=\"{$kabupaten->id}\">{$kabupaten->nama}</option>";
			}
		}
		echo $options;
	}
	
	public function get_kecamatan() {
		$kabupaten_id = $this->input->get('kabupaten_id');
		$options = "<option value=\"0\" selected=\"selected\">- Pilih Kecamatan -</option>";
		if ($kabupaten_id) {
			$data = $this->Wilayah_Model->getAll(0, 0, array('lft' => 'ASC'), array('parent_id' => $kabupaten_id));
			$kecamatans = $data['data'];
			
			$kecamatan_id = $this->input->get('kecamatan_id') ? $this->input->get('kecamatan_id') : 0;

			foreach ($kecamatans as $kecamatan) {
				$continue = true;
				if ($kecamatan_id > 0) {
					if ($kecamatan->id == $kecamatan_id) {
						$options .= "<option value=\"{$kecamatan->id}\" selected=\"selected\">{$kecamatan->nama}</option>";
						$continue = false;
					}
				}
				if ($continue)
					$options .= "<option value=\"{$kecamatan->id}\">{$kecamatan->nama}</option>";
			}
		}
		echo $options;
	}
	
	public function get_kelurahan() {
		$kecamatan_id = $this->input->get('kecamatan_id');
		$options = "<option value=\"0\" selected=\"selected\">- Pilih Kelurahan/Desa -</option>";
		if ($kecamatan_id) {
			$data = $this->Wilayah_Model->getAll(0, 0, array('lft' => 'ASC'), array('parent_id' => $kecamatan_id));
			$kelurahans = $data['data'];
			
			$kelurahan_id = $this->input->get('kelurahan_id') ? $this->input->get('kelurahan_id') : 0;
			
			foreach ($kelurahans as $kelurahan) {
				$continue = true;
				if ($kelurahan_id > 0) {
					if ($kelurahan->id == $kelurahan_id) {
						$options .= "<option value=\"{$kelurahan->id}\" selected=\"selected\">{$kelurahan->nama}</option>";
						$continue = false;
					}
				}
				if ($continue)
					$options .= "<option value=\"{$kelurahan->id}\">{$kelurahan->nama}</option>";
			}
		}
		echo $options;
	}
	
	private function _load_options() {
		$options = new stdClass();
		
		$provinsi_id = get_option('provinsi_id');
		if ($provinsi_id)
			$options->provinsi_id = $provinsi_id;
		else
			$options->provinsi_id = 0;
		
		$kabupaten_id = get_option('kabupaten_id');
		if ($kabupaten_id)
			$options->kabupaten_id = $kabupaten_id;
		else
			$options->kabupaten_id = 0;
		
		$kecamatan_id = get_option('kecamatan_id');
		if ($kecamatan_id)
			$options->kecamatan_id = $kecamatan_id;
		else
			$options->kecamatan_id = 0;
		
		$kelurahan_id = get_option('kelurahan_id');
		if ($kelurahan_id)
			$options->kelurahan_id = $kelurahan_id;
		else
			$options->kelurahan_id = 0;
		
		$barcode_btype = get_option('barcode_btype');
		if ($barcode_btype)
			$options->barcode_btype = $barcode_btype;
		else
			$options->barcode_btype = 0;
		
		/*
		$barcode_backcolor = $this->input->post('backcolor');
		update_option('barcode_backcolor', $barcode_backcolor);
		
		$barcode_forecolor = $this->input->post('forecolor');
		update_option('barcode_forecolor', $barcode_forecolor);
		
		$barcode_bar_width = $this->input->post('bar_width');
		update_option('barcode_bar_width', $barcode_bar_width);
		
		$barcode_bar_height = $this->input->post('bar_height');
		update_option('barcode_bar_height', $barcode_bar_height);
		
		$barcode_module_size = $this->input->post('module_size');
		update_option('barcode_module_size', $barcode_module_size);
		
		$barcode_quiet_zone_size = $this->input->post('quiet_zone_size');
		update_option('barcode_quiet_zone_size', $barcode_quiet_zone_size);
		
		$barcode_rectangular = $this->input->post('rectangular');
		update_option('barcode_rectangular', $barcode_rectangular);
		
		$barcode_pos_x = $this->input->post('pos_x');
		update_option('barcode_pos_x', $barcode_pos_x);
		
		$barcode_pos_y = $this->input->post('pos_y');
		update_option('barcode_pos_y', $barcode_pos_y);
		*/
		
		$barcode_renderer = get_option('barcode_renderer');
		if ($barcode_renderer)
			$options->barcode_renderer = $barcode_renderer;
		else
			$options->barcode_btype = 0;
		
        return $options;
    }
	
	private function _save_options() {
		$provinsi_id = $this->input->post('provinsi_id');
		update_option('provinsi_id', $provinsi_id);
		
		$kabupaten_id = $this->input->post('kabupaten_id');
		update_option('kabupaten_id', $kabupaten_id);
		
		$kecamatan_id = $this->input->post('kecamatan_id');
		update_option('kecamatan_id', $kecamatan_id);
		
		$kelurahan_id = $this->input->post('kelurahan_id');
		update_option('kelurahan_id', $kelurahan_id);
		
		$barcode_btype = $this->input->post('btype');
		update_option('barcode_btype', $barcode_btype);
		
		$barcode_backcolor = $this->input->post('backcolor');
		update_option('barcode_backcolor', $barcode_backcolor);
		
		$barcode_forecolor = $this->input->post('forecolor');
		update_option('barcode_forecolor', $barcode_forecolor);
		
		$barcode_bar_width = $this->input->post('bar_width');
		update_option('barcode_bar_width', $barcode_bar_width);
		
		$barcode_bar_height = $this->input->post('bar_height');
		update_option('barcode_bar_height', $barcode_bar_height);
		
		$barcode_module_size = $this->input->post('module_size');
		update_option('barcode_module_size', $barcode_module_size);
		
		$barcode_quiet_zone_size = $this->input->post('quiet_zone_size');
		update_option('barcode_quiet_zone_size', $barcode_quiet_zone_size);
		
		$barcode_rectangular = $this->input->post('rectangular');
		update_option('barcode_rectangular', $barcode_rectangular);
		
		$barcode_pos_x = $this->input->post('pos_x');
		update_option('barcode_pos_x', $barcode_pos_x);
		
		$barcode_pos_y = $this->input->post('pos_y');
		update_option('barcode_pos_y', $barcode_pos_y);
		
		$barcode_renderer = $this->input->post('renderer');
		update_option('barcode_renderer', $barcode_renderer);
	}
    
}

/* End of file options.php */
/* Location: ./application/controllers/master/options.php */