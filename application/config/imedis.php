<?php

/*
|--------------------------------------------------------------------------
| Role
|--------------------------------------------------------------------------
*/

$config['ID_ROLE_SUPER_ADMINISTRATOR']	= 1;
$config['ID_ROLE_ADMINISTRATOR']		= 2;
$config['ID_ROLE_USER']					= 3;

$config['IDS_ROLE_SUPER_ADMINISTRATOR']	= "Super Administrator";
$config['IDS_ROLE_ADMINISTRATOR']		= "Aministrator";
$config['IDS_ROLE_USER']				= "User";

$config['role'] = array(
	$config['ID_ROLE_SUPER_ADMINISTRATOR']	=> $config['IDS_ROLE_SUPER_ADMINISTRATOR'], 
	$config['ID_ROLE_ADMINISTRATOR']		=> $config['IDS_ROLE_ADMINISTRATOR'],
	$config['ID_ROLE_USER']					=> $config['IDS_ROLE_USER']
);

/*
|--------------------------------------------------------------------------
| Jenis Kelamin
|--------------------------------------------------------------------------
*/

$config['ID_JENIS_KELAMIN_LAKI_LAKI'] = 1;
$config['ID_JENIS_KELAMIN_PEREMPUAN'] = 2;

$config['IDS_JENIS_KELAMIN_LAKI_LAKI'] = 'Laki-laki';
$config['IDS_JENIS_KELAMIN_PEREMPUAN'] = 'Perempuan';

$config['jenis_kelamin'] = array(
	$config['ID_JENIS_KELAMIN_LAKI_LAKI']	=> $config['IDS_JENIS_KELAMIN_LAKI_LAKI'], 
	$config['ID_JENIS_KELAMIN_PEREMPUAN']	=> $config['IDS_JENIS_KELAMIN_PEREMPUAN']
);

/*
|--------------------------------------------------------------------------
| Golongan Darah
|--------------------------------------------------------------------------
*/

$config['ID_GOLONGAN_DARAH_A'] = 1;
$config['ID_GOLONGAN_DARAH_B'] = 2;
$config['ID_GOLONGAN_DARAH_AB'] = 3;
$config['ID_GOLONGAN_DARAH_O'] = 4;

$config['IDS_GOLONGAN_DARAH_A'] = 'A';
$config['IDS_GOLONGAN_DARAH_B'] = 'B';
$config['IDS_GOLONGAN_DARAH_AB'] = 'AB';
$config['IDS_GOLONGAN_DARAH_O'] = 'O';

$config['golongan_darah'] = array(
	$config['ID_GOLONGAN_DARAH_A']	=> $config['IDS_GOLONGAN_DARAH_A'], 
	$config['ID_GOLONGAN_DARAH_B']	=> $config['IDS_GOLONGAN_DARAH_B'],
	$config['ID_GOLONGAN_DARAH_AB']	=> $config['IDS_GOLONGAN_DARAH_AB'], 
	$config['ID_GOLONGAN_DARAH_O']	=> $config['IDS_GOLONGAN_DARAH_O']
);

/*
|--------------------------------------------------------------------------
| Status Kawin
|--------------------------------------------------------------------------
*/

$config['ID_STATUS_KAWIN_BELUM_KAWIN']	= 1;
$config['ID_STATUS_KAWIN_KAWIN']		= 2;
$config['ID_STATUS_KAWIN_JANDA']		= 3;
$config['ID_STATUS_KAWIN_DUDA']			= 4;

$config['IDS_STATUS_KAWIN_BELUM_KAWIN']	= 'Belum Kawin';
$config['IDS_STATUS_KAWIN_KAWIN']		= 'Kawin';
$config['IDS_STATUS_KAWIN_JANDA']		= 'Janda';
$config['IDS_STATUS_KAWIN_DUDA']		= 'Duda';

$config['status_kawin'] = array(
	$config['ID_STATUS_KAWIN_BELUM_KAWIN']	=> $config['IDS_STATUS_KAWIN_BELUM_KAWIN'], 
	$config['ID_STATUS_KAWIN_KAWIN']		=> $config['IDS_STATUS_KAWIN_KAWIN'],
	$config['ID_STATUS_KAWIN_JANDA']		=> $config['IDS_STATUS_KAWIN_JANDA'], 
	$config['ID_STATUS_KAWIN_DUDA']			=> $config['IDS_STATUS_KAWIN_DUDA']
);

/*
|--------------------------------------------------------------------------
| Wilayah
|--------------------------------------------------------------------------
*/

$config['ID_NEGARA']	= 0;
$config['ID_PROVINSI']	= 1;
$config['ID_KABUPATEN']	= 2;
$config['ID_KOTA']		= 3;
$config['ID_KECAMATAN']	= 4;
$config['ID_KELURAHAN']	= 5;
$config['ID_DESA']		= 6;

$config['IDS_NEGARA']		= 'Negara';
$config['IDS_PROVINSI']		= 'Provinsi';
$config['IDS_KABUPATEN']	= 'Kabupaten';
$config['IDS_KOTA']			= 'Kota';
$config['IDS_KECAMATAN']	= 'Kecamatan';
$config['IDS_KELURAHAN']	= 'Kelurahan';
$config['IDS_DESA']			= 'Desa';

$config['jenis_wilayah'] = array(
	$config['ID_NEGARA']	=> $config['IDS_NEGARA'], 
	$config['ID_PROVINSI']	=> $config['IDS_PROVINSI'],
	$config['ID_KABUPATEN']	=> $config['IDS_KABUPATEN'], 
	$config['ID_KOTA']		=> $config['IDS_KOTA'],
	$config['ID_KECAMATAN']	=> $config['IDS_KECAMATAN'], 
	$config['ID_KELURAHAN']	=> $config['IDS_KELURAHAN'],
	$config['ID_DESA']		=> $config['IDS_DESA']
);

/*
|--------------------------------------------------------------------------
| Jenis Cara Bayar
|--------------------------------------------------------------------------
*/

$config['ID_MEMBAYAR_SENDIRI']	= 1;
$config['ID_ASURANSI']			= 2;
$config['ID_KERINGANAN']		= 3;
$config['ID_GRATIS']			= 4;

$config['IDS_MEMBAYAR_SENDIRI']	= 'Membayar Sendiri';
$config['IDS_ASURANSI']			= 'Asuransi';
$config['IDS_KERINGANAN']		= 'Keringanan';
$config['IDS_GRATIS']			= 'Gratis';

$config['jenis_cara_bayar'] = array(
	$config['ID_MEMBAYAR_SENDIRI']	=> $config['IDS_MEMBAYAR_SENDIRI'], 
	$config['ID_ASURANSI']			=> $config['IDS_ASURANSI'],
	$config['ID_KERINGANAN']		=> $config['IDS_KERINGANAN'], 
	$config['ID_GRATIS']			=> $config['IDS_GRATIS']
);

/*
|--------------------------------------------------------------------------
| Unit
|--------------------------------------------------------------------------
*/

$config['ID_RAWAT_JALAN']				= 1;
$config['ID_RAWAT_DARURAT']				= 2;
$config['ID_RAWAT_INAP']				= 3;
$config['ID_LABORATORIUM']				= 4;
$config['ID_RADIOLOGI']					= 5;
$config['ID_FARMASI']					= 6;
$config['ID_BANK_DARAH']				= 7;
$config['ID_GIZI']						= 8;
$config['ID_BEDAH']						= 9;
$config['ID_TINDAKAN_MEDIK_NON_BEDAH']	= 10;
$config['ID_HOME_CARE']					= 11;
$config['ID_ENDOSCOPY']					= 12;
$config['ID_PEMULASARAN']				= 13;
$config['ID_AMBULANCE']					= 14;

$config['IDS_RAWAT_JALAN']				= 'Rawat Jalan';
$config['IDS_RAWAT_DARURAT']			= 'Rawat Darurat';
$config['IDS_RAWAT_INAP']				= 'Rawat Inap';
$config['IDS_LABORATORIUM']				= 'Laboratorium';
$config['IDS_RADIOLOGI']				= 'Radiologi';
$config['IDS_FARMASI']					= 'Farmasi';
$config['IDS_BANK_DARAH']				= 'Bank Darah';
$config['IDS_GIZI']						= 'Gizi';
$config['IDS_BEDAH']					= 'Bedah';
$config['IDS_TINDAKAN_MEDIK_NON_BEDAH']	= 'Non Bedah';
$config['IDS_HOME_CARE']				= 'Home Care';
$config['IDS_ENDOSCOPY']				= 'Endoscopy';
$config['IDS_PEMULASARAN']				= 'Pemulasaran Jenazah';
$config['IDS_AMBULANCE']				= 'Ambulance';

$config['jenis_unit'] = array(
	$config['ID_RAWAT_JALAN']				=> $config['IDS_RAWAT_JALAN'], 
	$config['ID_RAWAT_DARURAT']				=> $config['IDS_RAWAT_DARURAT'],
	$config['ID_RAWAT_INAP']				=> $config['IDS_RAWAT_INAP'], 
	$config['ID_LABORATORIUM']				=> $config['IDS_LABORATORIUM'],
	$config['ID_RADIOLOGI']					=> $config['IDS_RADIOLOGI'], 
	$config['ID_FARMASI']					=> $config['IDS_FARMASI'],
	$config['ID_BANK_DARAH']				=> $config['IDS_BANK_DARAH'], 
	$config['ID_GIZI']						=> $config['IDS_GIZI'],
	$config['ID_BEDAH']						=> $config['IDS_BEDAH'],
	$config['ID_TINDAKAN_MEDIK_NON_BEDAH']	=> $config['IDS_TINDAKAN_MEDIK_NON_BEDAH'],
	$config['ID_HOME_CARE']					=> $config['IDS_HOME_CARE'],
	$config['ID_ENDOSCOPY']					=> $config['IDS_ENDOSCOPY'],
	$config['ID_PEMULASARAN']				=> $config['IDS_PEMULASARAN'],
	$config['ID_AMBULANCE']					=> $config['IDS_AMBULANCE']
);

/*
|--------------------------------------------------------------------------
| Tindak Lanjut Perawatan Rawat Jalan
|--------------------------------------------------------------------------
*/

$config['ID_TINDAK_LANJUT_PERAWATAN_PULANG']	= 1;
$config['ID_TINDAK_LANJUT_PERAWATAN_DIRAWAT']	= 2;
$config['ID_TINDAK_LANJUT_PERAWATAN_DIRUJUK']	= 3;

$config['IDS_TINDAK_LANJUT_PERAWATAN_PULANG']	= 'Pulang';
$config['IDS_TINDAK_LANJUT_PERAWATAN_DIRAWAT']	= 'Dirawat';
$config['IDS_TINDAK_LANJUT_PERAWATAN_DIRUJUK']	= 'Dirujuk';

$config['tindak_lanjut_perawatan'] = array(
	$config['ID_TINDAK_LANJUT_PERAWATAN_PULANG']	=> $config['IDS_TINDAK_LANJUT_PERAWATAN_PULANG'], 
	$config['ID_TINDAK_LANJUT_PERAWATAN_DIRAWAT']	=> $config['IDS_TINDAK_LANJUT_PERAWATAN_DIRAWAT'],
	$config['ID_TINDAK_LANJUT_PERAWATAN_DIRUJUK']	=> $config['IDS_TINDAK_LANJUT_PERAWATAN_DIRUJUK']
);

/*
|--------------------------------------------------------------------------
| Jenis Kelompok Pegawai
|--------------------------------------------------------------------------
*/

$config['ID_JENIS_KELOMPOK_PEGAWAI_DOKTER']		= 1;
$config['ID_JENIS_KELOMPOK_PEGAWAI_MANJEMEN']	= 2;
$config['ID_JENIS_KELOMPOK_PEGAWAI_APOTEKER']	= 3;
$config['ID_JENIS_KELOMPOK_PEGAWAI_LAIN_LAIN']	= 4;

$config['IDS_JENIS_KELOMPOK_PEGAWAI_DOKTER']	= 'Dokter';
$config['IDS_JENIS_KELOMPOK_PEGAWAI_MANJEMEN']	= 'Manajemen';
$config['IDS_JENIS_KELOMPOK_PEGAWAI_APOTEKER']	= 'Apoteker';
$config['IDS_JENIS_KELOMPOK_PEGAWAI_LAIN_LAIN']	= 'Lain-lain';

$config['jenis_kelompok_pegawai'] = array(
	$config['ID_JENIS_KELOMPOK_PEGAWAI_DOKTER']		=> $config['IDS_JENIS_KELOMPOK_PEGAWAI_DOKTER'], 
	$config['ID_JENIS_KELOMPOK_PEGAWAI_MANJEMEN']	=> $config['IDS_JENIS_KELOMPOK_PEGAWAI_MANJEMEN'],
	$config['ID_JENIS_KELOMPOK_PEGAWAI_APOTEKER']	=> $config['IDS_JENIS_KELOMPOK_PEGAWAI_APOTEKER'], 
	$config['ID_JENIS_KELOMPOK_PEGAWAI_LAIN_LAIN']	=> $config['IDS_JENIS_KELOMPOK_PEGAWAI_LAIN_LAIN']
);

/*
|--------------------------------------------------------------------------
| Kelas Rawat Inap
|--------------------------------------------------------------------------
*/

$config['ID_KELAS_VVIP']	= 1;
$config['ID_KELAS_VIP']		= 2;
$config['ID_KELAS_I']		= 3;
$config['ID_KELAS_II']		= 4;
$config['ID_KELAS_III']		= 5;
$config['ID_KELAS_KHUSUS']	= 6;
$config['ID_KELAS_ISOLASI']	= 7;
$config['ID_KELAS_UMUM']	= 8;


$config['IDS_KELAS_VVIP']		= "VVIP";
$config['IDS_KELAS_VIP']		= "VIP";
$config['IDS_KELAS_I']			= "I";
$config['IDS_KELAS_II']			= "II";
$config['IDS_KELAS_III']		= "III";
$config['IDS_KELAS_KHUSUS']		= "Khusus";
$config['IDS_KELAS_ISOLASI']	= "Isolasi";
$config['IDS_KELAS_UMUM']		= "Umum";

$config['kelas'] = array(
	$config['ID_KELAS_VVIP']	=> $config['IDS_KELAS_VVIP'], 
	$config['ID_KELAS_VIP']		=> $config['IDS_KELAS_VIP'],
	$config['ID_KELAS_I']		=> $config['IDS_KELAS_I'], 
	$config['ID_KELAS_II']		=> $config['IDS_KELAS_II'],
	$config['ID_KELAS_III']		=> $config['IDS_KELAS_III'],
	$config['ID_KELAS_KHUSUS']	=> $config['IDS_KELAS_KHUSUS'],
	$config['ID_KELAS_ISOLASI']	=> $config['IDS_KELAS_ISOLASI'], 
	$config['ID_KELAS_UMUM']	=> $config['IDS_KELAS_UMUM']
);

/*
|--------------------------------------------------------------------------
| Tindak Lanjut Perawatan
|--------------------------------------------------------------------------
*/

$config['ID_TINDAK_LANJUT_PERAWATAN_PULANG']	= 1;
$config['ID_TINDAK_LANJUT_PERAWATAN_DIRAWAT']	= 2;

$config['IDS_TINDAK_LANJUT_PERAWATAN_PULANG']	= "Rawat Jalan";
$config['IDS_TINDAK_LANJUT_PERAWATAN_DIRAWAT']	= "Rawat Darurat";

$config['tindak_lanjut_perawatan'] = array(
	$config['ID_TINDAK_LANJUT_PERAWATAN_PULANG']	=> $config['IDS_TINDAK_LANJUT_PERAWATAN_PULANG'], 
	$config['ID_TINDAK_LANJUT_PERAWATAN_DIRAWAT']	=> $config['IDS_TINDAK_LANJUT_PERAWATAN_DIRAWAT']
);

/*
|--------------------------------------------------------------------------
| Cara masuk pasien rawat inap
|--------------------------------------------------------------------------
*/

$config['ID_CARA_MASUK_RAWAT_JALAN']	= 1;
$config['ID_CARA_MASUK_RAWAT_DARURAT']	= 2;

$config['IDS_CARA_MASUK_RAWAT_JALAN']	= "Rawat Jalan";
$config['IDS_CARA_MASUK_RAWAT_DARURAT']	= "Rawat Darurat";

$config['cara_masuk_rawat_inap'] = array(
	$config['ID_CARA_MASUK_RAWAT_JALAN']	=> $config['IDS_CARA_MASUK_RAWAT_JALAN'], 
	$config['ID_CARA_MASUK_RAWAT_DARURAT']	=> $config['IDS_CARA_MASUK_RAWAT_DARURAT']
);

/*
|--------------------------------------------------------------------------
| Status Bed
|--------------------------------------------------------------------------
*/

$config['ID_STATUS_BED_KOSONG']		= 1;
$config['ID_STATUS_BED_ISI']		= 2;
$config['ID_STATUS_BED_CADANGAN']	= 3;

$config['IDS_STATUS_BED_KOSONG']	= 'Kosong';
$config['IDS_STATUS_BED_ISI']		= 'Isi';
$config['IDS_STATUS_BED_CADANGAN']	= 'Sementara';

$config['status_bed'] = array(
	$config['ID_STATUS_BED_KOSONG']		=> $config['IDS_STATUS_BED_KOSONG'], 
	$config['ID_STATUS_BED_ISI']		=> $config['IDS_STATUS_BED_ISI'],
	$config['ID_STATUS_BED_CADANGAN']	=> $config['IDS_STATUS_BED_CADANGAN']
);

/*
|--------------------------------------------------------------------------
| Keadaan Pasien Keluar
|--------------------------------------------------------------------------
*/

$config['ID_HIDUP']	= 1;
$config['ID_MATI']	= 2;

$config['IDS_HIDUP']	= 'Hidup';
$config['IDS_MATI']		= 'Mati';

$config['keadaan_pasien_keluar'] = array(
	$config['ID_HIDUP']	=> $config['IDS_HIDUP'], 
	$config['ID_MATI']	=> $config['IDS_MATI']
);

/*
|--------------------------------------------------------------------------
| Kondisi Pasien Keluar (Hidup)
|--------------------------------------------------------------------------
*/

$config['ID_KONDISI_SEMBUH']		= 1;
$config['ID_KONDISI_MEMBAIK']		= 2;
$config['ID_KONDISI_BELUM_SEMBUH']	= 3;

$config['IDS_KONDISI_SEMBUH']		= 'Sembuh';
$config['IDS_KONDISI_MEMBAIK']		= 'Membaik';
$config['IDS_KONDISI_BELUM_SEMBUH']	= 'Belum Sembuh';

$config['kondisi_pasien_keluar_hidup'] = array(
	$config['ID_KONDISI_SEMBUH']		=> $config['IDS_KONDISI_SEMBUH'], 
	$config['ID_KONDISI_MEMBAIK']		=> $config['IDS_KONDISI_MEMBAIK'],
	$config['ID_KONDISI_BELUM_SEMBUH']	=> $config['IDS_KONDISI_BELUM_SEMBUH']
);

/*
|--------------------------------------------------------------------------
| Kondisi Pasien Keluar (Mati)
|--------------------------------------------------------------------------
*/

$config['ID_KONDISI_KURANG_48_JAM']		= 4;
$config['ID_KONDISI_LEBIH_DARI_48_JAM']	= 5;

$config['IDS_KONDISI_KURANG_48_JAM']		= '< 48 jam';
$config['IDS_KONDISI_LEBIH_DARI_48_JAM']	= '>= 48 jam';

$config['kondisi_pasien_keluar_mati'] = array(
	$config['ID_KONDISI_KURANG_48_JAM']		=> $config['IDS_KONDISI_KURANG_48_JAM'], 
	$config['ID_KONDISI_LEBIH_DARI_48_JAM']	=> $config['IDS_KONDISI_LEBIH_DARI_48_JAM']
);

/*
|--------------------------------------------------------------------------
| Cara Pasien Keluar
|--------------------------------------------------------------------------
*/

$config['ID_CARA_KELUAR_PULANG']		= 1;
$config['ID_CARA_KELUAR_DIRUJUK']		= 2;
$config['ID_CARA_KELUAR_KE_RS_LAIN']	= 3;
$config['ID_CARA_KELUAR_PULANG_PAKSA']	= 4;
$config['ID_CARA_KELUAR_LARI']			= 5;

$config['IDS_CARA_KELUAR_PULANG']		= 'Pulang';
$config['IDS_CARA_KELUAR_DIRUJUK']		= 'Dirujuk';
$config['IDS_CARA_KELUAR_KE_RS_LAIN']	= 'Pindah ke RS Lain';
$config['IDS_CARA_KELUAR_PULANG_PAKSA']	= 'Pulang Paksa';
$config['IDS_CARA_KELUAR_LARI']			= 'Lari';

$config['cara_pasien_keluar'] = array(
	$config['ID_CARA_KELUAR_PULANG']		=> $config['IDS_CARA_KELUAR_PULANG'], 
	$config['ID_CARA_KELUAR_DIRUJUK']		=> $config['IDS_CARA_KELUAR_DIRUJUK'],
	$config['ID_CARA_KELUAR_KE_RS_LAIN']	=> $config['IDS_CARA_KELUAR_KE_RS_LAIN'], 
	$config['ID_CARA_KELUAR_PULANG_PAKSA']	=> $config['IDS_CARA_KELUAR_PULANG_PAKSA'],
	$config['ID_CARA_KELUAR_LARI']			=> $config['IDS_CARA_KELUAR_LARI']
);

?>
