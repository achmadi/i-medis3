<?php

class CSVImport extends ADMIN_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('master/Wilayah_Model');
	}
	
	public function index()	{
		$this->load->Library('CSVReader');
		$data = $this->csvreader->parse_file('D:/provinsi.csv');
		$wilayah = array();
		$wilayah['Root'] = array(
			'nama' => 'Indonesia',
			'jenis' => 0,
			'children'	=> array()
		);
		foreach ($data as $row) {
			//$row['regioncode']
			//$row['regionname']
			//$row['typename']
			//$row['parent']
			switch ($row['typename']) {
				case 'Provinsi':
					$wilayah['Root']['children'][$row['regioncode']] = array(
						'nama' => $row['regionname'],
						'jenis' => 1,
						'children'	=> array()
					);
					break;
				case 'Kabupaten':
				case 'Kota':
					$typename = $row['typename'] == 'Kabupaten' ? 2 : 3;
					$provinsi = substr($row['regioncode'], 0, 3);
					$wilayah['Root']['children'][$provinsi]['children'][$row['regioncode']] = array(
						'nama' => $row['regionname'],
						'jenis' => $typename,
						'children' => array()
					);
					break;
				case 'Kecamatan':
					$provinsi = substr($row['regioncode'], 0, 3);
					$kabupaten_kota = substr($row['regioncode'], 0, 6);
					$wilayah['Root']['children'][$provinsi]['children'][$kabupaten_kota]['children'][$row['regioncode']] = array(
						'nama' => $row['regionname'],
						'jenis' => 4,
						'children'	=> array()
					);
					break;
				case 'Kelurahan':
				case 'Desa':
					$typename = $row['typename'] == 'Kelurahan' ? 5 : 6;
					$provinsi = substr($row['regioncode'], 0, 3);
					$kabupaten_kota = substr($row['regioncode'], 0, 6);
					$kecamatan = substr($row['regioncode'], 0, 9);
					$wilayah['Root']['children'][$provinsi]['children'][$kabupaten_kota]['children'][$kecamatan]['children'][$row['regioncode']] = array(
						'nama' => $row['regionname'],
						'jenis' => $typename
					);
					break;
			}
		}
		foreach ($wilayah['Root']['children'] as $provinsi) {
			$wilayah = new stdClass();
			$wilayah->id		= 0;
			$wilayah->nama		= $provinsi['nama'];
			$wilayah->jenis		= $provinsi['jenis'];
			$wilayah->parent_id	= 0;
			$wilayah->old_parent_id	= 0;
			$wilayah->version	= 0;
			$provinsi_id = $this->Wilayah_Model->save($wilayah);
			foreach ($provinsi['children'] as $kabupaten_kota) {
				$wilayah = new stdClass();
				$wilayah->id		= 0;
				$wilayah->nama		= $kabupaten_kota['nama'];
				$wilayah->jenis		= $kabupaten_kota['jenis'];
				$wilayah->parent_id	= $provinsi_id;
				$wilayah->old_parent_id	= 0;
				$wilayah->version	= 0;
				$kabupaten_id = $this->Wilayah_Model->save($wilayah);
				foreach ($kabupaten_kota['children'] as $kecamatan) {
					$wilayah = new stdClass();
					$wilayah->id		= 0;
					$wilayah->nama		= $kecamatan['nama'];
					$wilayah->jenis		= $kecamatan['jenis'];
					$wilayah->parent_id	= $kabupaten_id;
					$wilayah->old_parent_id	= 0;
					$wilayah->version	= 0;
					$kecamatan_id = $this->Wilayah_Model->save($wilayah);
					foreach ($kecamatan['children'] as $kelurahan_desa) {
						$wilayah = new stdClass();
						$wilayah->id		= 0;
						$wilayah->nama		= $kelurahan_desa['nama'];
						$wilayah->jenis		= $kelurahan_desa['jenis'];
						$wilayah->parent_id	= $kecamatan_id;
						$wilayah->old_parent_id	= 0;
						$wilayah->version	= 0;
						$this->Wilayah_Model->save($wilayah);
					}
				}
			}
		}
	}
	
}

?>
