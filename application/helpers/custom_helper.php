<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//rupiah 1.000.000,00 
if (!function_exists('rupiah_desimal')) {
	function rupiah_desimal($angka)
	{

		$hasil_rupiah = "Rp " . number_format($angka, 2, ',', '.');
		return $hasil_rupiah;
	}
}
// rupiah Rp 1.000.000
if (!function_exists('rupiah')) {
	function rupiah($angka)
	{
		$hasil_rupiah = "Rp " . number_format($angka, 0, ',', '.');
		return $hasil_rupiah;
	}
}

if (!function_exists('currency')) {
	function currency($angka, $param = null)
	{
		if ($param != null) {
			$hasil_rupiah = $param . " " . number_format($angka, 0, ',', '.');
		} else {
			$hasil_rupiah = number_format($angka, 0, ',', '.');
		}
		return $hasil_rupiah;
	}
}
if (!function_exists('txtclear')) {
	function txtclear($txt)
	{
		return  preg_replace('/\s+/', '', $txt);
	}
}
if (!function_exists('only_numbers')) {
	function only_numbers($txt)
	{
		// Hapus semua karakter kecuali angka
		return preg_replace('/[^0-9]/', '', $txt);
	}
}


if (!function_exists('clean_nik')) {
	function clean_nik($txt)
	{
		// Hilangkan spasi di awal/akhir
		$txt = trim($txt);

		// Hanya izinkan huruf, angka, dan titik
		$txt = preg_replace('/[^a-zA-Z0-9.]/', '', $txt);

		return $txt;
	}
}

if (!function_exists('tgl_dmy')) {

	function tgl_dmy($tanggal)
	{
		// Buat DateTime object dari string input
		$date = DateTime::createFromFormat('M d Y h:iA', $tanggal);

		// Jika parsing berhasil, format ulang
		if ($date !== false) {
			return $date->format('d-m-Y');
		}

		// Jika parsing gagal, kembalikan string asli atau nilai default
		return $tanggal;
	}
}
if (!function_exists('tgl_string')) {
	function tgl_string($tanggal)
	{
		if (empty($tanggal)) return '-';

		// coba parse dengan format datetime lengkap
		$date = DateTime::createFromFormat('Y-m-d H:i:s', $tanggal);

		// kalau gagal, coba parse dengan format tanggal saja
		if (!$date) {
			$date = DateTime::createFromFormat('Y-m-d', $tanggal);
		}

		// kalau masih gagal, kembalikan apa adanya
		if ($date === false) return $tanggal;

		return $date->format('d M, Y'); // contoh: 30 Jul, 2025
	}
}


if (!function_exists('clean_string')) {
	function clean_string($string)
	{
		// Trim leading and trailing spaces and tabs
		return trim($string, " \t");
	}
}



if (!function_exists('is_logged_in')) {
	function is_logged_in()
	{
		$CI = &get_instance();
		$is_logged = $CI->session->userdata('logged');

		if ($is_logged != true) {
			$CI->session->sess_destroy();

			$CI->session->set_flashdata('warning', 'Session expired! Please log in again.');

			if ($CI->input->is_ajax_request()) {
				echo json_encode([
					'session_expired' => true,
					'redirect' => base_url('Auth/logout'),
					'message' => 'Session expired! Please log in again.',
				]);
				http_response_code(401);
				exit;
			} else {
				$CI->session->set_flashdata('warning', 'You do not have access.');
				redirect('Auth');
				exit;
			}
		}
	}
}

if (!function_exists('is_session')) {
	function is_session()
	{
		$CI = &get_instance();
		$is_logged = $CI->session->userdata('logged');
		if ($is_logged == true) {
			redirect('Dashboard');
		}
	}
}

if (!function_exists('bulan_romawi')) {
	function bulan_romawi($tanggal)
	{
		// Pastikan format tanggal valid
		$bulan = date('n', strtotime($tanggal));

		$romawi = [
			1 => 'I',
			2 => 'II',
			3 => 'III',
			4 => 'IV',
			5 => 'V',
			6 => 'VI',
			7 => 'VII',
			8 => 'VIII',
			9 => 'IX',
			10 => 'X',
			11 => 'XI',
			12 => 'XII'
		];

		return $romawi[$bulan];
	}
}
if (!function_exists('badge')) {
	function badge($status)
	{
		switch (strtoupper($status)) {
			case 'APPROVED':
				return 'bg-success';
			case 'REJECT':
				return 'bg-danger';
			case 'OPEN':
				return 'bg-primary';
			default:
				return 'bg-secondary'; // waiting / null
		}
	}
}
if (!function_exists('getMonthName')) {
	function getMonthName($monthNumber)
	{
		// Pastikan input adalah angka antara 1 sampai 12
		if ($monthNumber >= 1 && $monthNumber <= 12) {
			/* * mktime(hour, minute, second, month, day, year) 
			 * Fungsi ini membuat timestamp pada tanggal 1 bulan yang diminta 
			 */
			$timestamp = mktime(0, 0, 0, $monthNumber, 1);
	
			// date('F', ...) memformat timestamp menjadi nama bulan lengkap (English)
			return date('F', $timestamp);
		}
	
		return 'Invalid Month'; // Kembalikan pesan error jika angka tidak valid
	}
}
if (!function_exists('get_device_info')) {
	function get_device_info()
	{
		$CI = &get_instance();
		$CI->load->library('user_agent');

		// Browser & version
		$browser     = $CI->agent->browser();       // contoh: Chrome
		$version     = $CI->agent->version();       // contoh: 142.0.0.0
		$platform    = $CI->agent->platform();      // contoh: Windows 10

		// Device (mobile / desktop)
		if ($CI->agent->is_mobile()) {
			$device = $CI->agent->mobile();         // Android, iPhone, etc
		} else {
			$device = "Desktop";
		}

		// Output format
		return "{$browser} {$version}, {$platform}, {$device}";
	}
}
