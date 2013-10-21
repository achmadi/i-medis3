<?php

class CSVPegawai extends ADMIN_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('master/Pegawai_Model');
	}
	
	public function index()	{
		$this->load->Library('CSVReader');
		$data = $this->csvreader->parse_file('D:/pegawai.csv');
		foreach ($data as $row) {
			$pegawai = new stdClass();
			$pegawai->id					= 0;
			$pegawai->nip					= 0;
			$pegawai->nama					= $row['NAMA'];
			$pegawai->no_rekening			= $row['REKENING'];
			$pegawai->npwp					= $row['NPWP'];
			$pegawai->jabatan				= 0;
			$pegawai->kelompok				= $row['RUANG'];
			$pegawai->golongan_id			= 0;
			$pegawai->kelompok_id			= 0;
			$pegawai->unit_kerja_id			= 0;
			$pegawai->skor_indeks_langsung	= 0;
			$pegawai->indeks_basic			= $row['BASIC'];
			$pegawai->indeks_posisi			= $row['KARU'];
			$pegawai->indeks_emergency		= $row['EMERGENSI'];
			$pegawai->indeks_resiko			= $row['RESIKO'];
			$pegawai->indeks_pendidikan		= $row['PENDIDIKAN'];
			$pegawai->indeks_masa_kerja		= $row['MASA KERJA'];
			$this->Pegawai_Model->create($pegawai);
		}
	}
	
}

?>
