<?php

// Data Mode Edit
define("DATA_MODE_ADD",     "1");
define("DATA_MODE_EDIT",    "2");
define("DATA_MODE_DELETE",	"3");

if ( ! function_exists('get_current_date')) {
	function get_current_date() {
		$timezone = "Asia/Pontianak";
		if (function_exists('date_default_timezone_set'))
			date_default_timezone_set($timezone);
		return date("Y-m-d H:i:s");
	}
}

/*
|--------------------------------------------------------------------------
| Role
|--------------------------------------------------------------------------
*/

if ( ! function_exists('is_super_admin')) {
	function is_super_admin() {
		$CI =& get_instance();
		$role = $CI->auth_user->role;
		return ($role == $CI->config->item('ID_ROLE_SUPER_ADMINISTRATOR'));
	}
}

if ( ! function_exists('is_admin')) {
	function is_admin() {
		$CI =& get_instance();
		$role = $CI->auth_user->role;
		return ($role == $CI->config->item('ID_ROLE_ADMINISTRATOR'));
	}
}

if ( ! function_exists('is_user')) {
	function is_user() {
		$CI =& get_instance();
		$role = $CI->auth_user->role;
		return ($role == $CI->config->item('ID_ROLE_USER'));
	}
}

if ( ! function_exists('get_role')) {
	function get_role() {
		$CI =& get_instance();
		return $CI->config->item('role');
	}
}

if ( ! function_exists('role_descr')) {
	function role_descr($index) {
		$CI =& get_instance();
		if ($index < $CI->config->item('ID_ROLE_SUPER_ADMINISTRATOR') || $index > $CI->config->item('ID_ROLE_USER'))
			return '';
		$role = $CI->config->item('role');
		return $role[$index];
	}
}

/*
|--------------------------------------------------------------------------
| Jenis Kelamin
|--------------------------------------------------------------------------
*/

if ( ! function_exists('jenis_kelamin')) {
	function jenis_kelamin() {
		$CI =& get_instance();
		return $CI->config->item('jenis_kelamin');
	}
}

if ( ! function_exists('jenis_kelamin_descr')) {
	function jenis_kelamin_descr($index) {
		$CI =& get_instance();
		if ($index < $CI->config->item('ID_JENIS_KELAMIN_LAKI_LAKI') || $index > $CI->config->item('ID_JENIS_KELAMIN_PEREMPUAN'))
			return '';
		$jenis_kelamin = $CI->config->item('jenis_kelamin');
		return $jenis_kelamin[$index];
	}
}

/*
|--------------------------------------------------------------------------
| Status Kawin
|--------------------------------------------------------------------------
*/

if ( ! function_exists('status_kawin')) {
	function status_kawin() {
		$CI =& get_instance();
		return $CI->config->item('status_kawin');
	}
}

if ( ! function_exists('status_kawin_descr')) {
	function status_kawin_descr($index) {
		$CI =& get_instance();
		if ($index < $CI->config->item('ID_JENIS_KELAMIN_LAKI_LAKI') || $index > $CI->config->item('ID_JENIS_KELAMIN_PEREMPUAN'))
			return '';
		$status_kawin = $CI->config->item('status_kawin');
		return $status_kawin[$index];
	}
}

/*
|--------------------------------------------------------------------------
| Wilayah
|--------------------------------------------------------------------------
*/

if ( ! function_exists('jenis_wilayah')) {
	function jenis_wilayah() {
		$CI =& get_instance();
		return $CI->config->item('jenis_wilayah');
	}
}

if ( ! function_exists('jenis_wilayah_descr')) {
	function jenis_wilayah_descr($index) {
		$CI =& get_instance();
		$jenis_wilayah = $CI->config->item('jenis_wilayah');
		return $jenis_wilayah[$index];
	}
}

/*
|--------------------------------------------------------------------------
| Helper Jenis Cara Bayar
|--------------------------------------------------------------------------
*/

if ( ! function_exists('get_jenis_cara_bayar')) {
	function get_jenis_cara_bayar() {
		$CI =& get_instance();
		return $CI->config->item('jenis_cara_bayar');
	}
}

if ( ! function_exists('get_jenis_cara_bayar_descr')) {
	function get_jenis_cara_bayar_descr($index) {
		$CI =& get_instance();
		$jenis_cara_bayar = $CI->config->item('jenis_cara_bayar');
		return $jenis_cara_bayar[$index];
	}
}

/*
|--------------------------------------------------------------------------
| Unit
|--------------------------------------------------------------------------
*/

if ( ! function_exists('get_jenis_unit')) {
	function get_jenis_unit() {
		$CI =& get_instance();
		return $CI->config->item('jenis_unit');
	}
}

if ( ! function_exists('get_jenis_unit_descr')) {
	function get_jenis_unit_descr($index) {
		$CI =& get_instance();
		$jenis_unit = $CI->config->item('jenis_unit');
		return $jenis_unit[$index];
	}
}

/*
|--------------------------------------------------------------------------
| Instalasi
|--------------------------------------------------------------------------
*/

if ( ! function_exists('unit_is_rawat_inap')) {
	function unit_is_rawat_inap($id) {
		$CI =& get_instance();
		$id_rawat_inap = $CI->config->item('ID_RAWAT_INAP');
		if ($id == $id_rawat_inap)
			return true;
		else
			return false;
	}
}

/*
|--------------------------------------------------------------------------
| Helper Jenis Kelompok Pegawai
|--------------------------------------------------------------------------
*/

if ( ! function_exists('get_jenis_kelompok_pegawai')) {
	function get_jenis_kelompok_pegawai() {
		$CI =& get_instance();
		return $CI->config->item('jenis_kelompok_pegawai');
	}
}

if ( ! function_exists('get_jenis_kelompok_pegawai_descr')) {
	function get_jenis_kelompok_pegawai_descr($index) {
		if (!$index)
			return "";
		$CI =& get_instance();
		$kelompok = $CI->config->item('jenis_kelompok_pegawai');
		return $kelompok[$index];
	}
}

/*
|--------------------------------------------------------------------------
| Helper Kelas
|--------------------------------------------------------------------------
*/

if ( ! function_exists('get_jenis_kelas')) {
	function get_jenis_kelas() {
		$CI =& get_instance();
		return $CI->config->item('kelas');
	}
}

if ( ! function_exists('jenis_kelas_descr')) {
	function jenis_kelas_descr($index) {
		if (!$index)
			return "";
		$CI =& get_instance();
		$kelas = $CI->config->item('kelas');
		return $kelas[$index];
	}
}

/*
|--------------------------------------------------------------------------
| Helper cara masuk pasien rawat inap
|--------------------------------------------------------------------------
*/

if ( ! function_exists('get_cara_masuk')) {
	function get_cara_masuk() {
		$CI =& get_instance();
		return $CI->config->item('cara_masuk');
	}
}

if ( ! function_exists('cara_masuk_descr')) {
	function cara_masuk_descr($index) {
		if (!$index)
			return "";
		$CI =& get_instance();
		$cara_masuk = $CI->config->item('cara_masuk');
		return $cara_masuk[$index];
	}
}

/*
|--------------------------------------------------------------------------
| Status Bed
|--------------------------------------------------------------------------
*/

if ( ! function_exists('status_bed_descr')) {
	function status_bed_descr($index) {
		$CI =& get_instance();
		$status = $CI->config->item('status_bed');
		return $status[$index];
	}
}

/*
|--------------------------------------------------------------------------
| Keadaan Pasien Keluar Helper
|--------------------------------------------------------------------------
*/

if ( ! function_exists('get_keadaan_pasien_keluar')) {
	function get_keadaan_pasien_keluar() {
		$CI =& get_instance();
		return $CI->config->item('keadaan_pasien_keluar');
	}
}

/*
|--------------------------------------------------------------------------
| Kondisi Pasien Keluar (Hidup) Helper
|--------------------------------------------------------------------------
*/

if ( ! function_exists('get_kondisi_pasien_keluar_hidup')) {
	function get_kondisi_pasien_keluar_hidup() {
		$CI =& get_instance();
		return $CI->config->item('kondisi_pasien_keluar_hidup');
	}
}

/*
|--------------------------------------------------------------------------
| Kondisi Pasien Keluar (Mati) Helper
|--------------------------------------------------------------------------
*/

if ( ! function_exists('get_kondisi_pasien_keluar_mati')) {
	function get_kondisi_pasien_keluar_mati() {
		$CI =& get_instance();
		return $CI->config->item('kondisi_pasien_keluar_mati');
	}
}

/*
|--------------------------------------------------------------------------
| Cara Pasien Keluar Helper
|--------------------------------------------------------------------------
*/

if ( ! function_exists('get_cara_pasien_keluar')) {
	function get_cara_pasien_keluar() {
		$CI =& get_instance();
		return $CI->config->item('cara_pasien_keluar');
	}
}

/**
 * Builds an http query string.
 * @param array $query  // of key value pairs to be used in the query
 * @return string       // http query string.
 **/
if ( ! function_exists('build_http_query')) {
	function build_http_query( $query ){
		$query_array = array();
		foreach( $query as $key => $key_value ){
			$query_array[] = $key . '=' . urlencode( $key_value );
		}
		return implode( '&', $query_array );
	}
}

?>
