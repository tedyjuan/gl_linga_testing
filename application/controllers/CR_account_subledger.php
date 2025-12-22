<?php
defined('BASEPATH') or exit('No direct script access allowed');
class CR_account_subledger extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model('M_global');
		$this->company =  $this->session->userdata('sess_company');
	}
	function index()
	{
		$data['judul']     = 'Account Subledger';
		$data['load_grid'] = 'CR_account_subledger';
		$data['depo']      = $this->M_global->getWhere("depos", ['code_company' => $this->company])->result();
		$this->load->view("VR_report/VR_account_subledger", $data);
	}

	
	public function Report()
	{
		// reprot terbaru  
		require APPPATH . 'third_party/xlsxwriter/xlsxwriter.class.php';

		$filename = "Account-Subledger " . date('Y-m-d H:i:s') . '.xlsx';
		header('Content-disposition: attachment; filename="' . $filename . '"');
		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");

		// ============================
		// HEADER STYLE
		// ============================
		$headerStyle = [
			'font'         => 'Calibri',
			'font-size'    => 10,
			'font-style'   => 'bold',
			'border'       => 'left,right,top,bottom',
			'border-style' => 'thin',
			'border-color' => '#000000',
			'halign'       => 'center',
			'valign'       => 'center',
			'fill'         => '#D9D9D9',
		];

		// ============================
		// HEADER KOLOM TABEL
		// ============================
		$header = [
			'BRANCH'      => 'string',
			'COST CENTER' => 'string',
			'TANGGAL'     => 'string',
			'NO VOUCHER'  => 'string',
			'KETERANGAN'  => 'string',
			'SALDO AWAL'  => 'string',
			'DEBIT'       => 'string',
			'KREDIT'      => 'string',
			'SALDO AKHIR' => 'string',
		];

		// ============================
		// BODY STYLE
		// ============================
		$bodyStyle = [
			'font'         => 'Calibri',
			'font-size'    => 10,
			'border'       => 'left,right,top,bottom',
			'border-style' => 'thin',
			'border-color' => '#000000',
		];

		// ============================
		// DATA
		// ============================
		$data = [
			['TVIP - PUSAT', 'PUSAT/UMUM/UMUM/UMUM', '01/01/2025', '2501/11/001', 'PENGISIAN KAS PUSAT 01/01/25-04/01/25', '1.899.193.613', '0', '9.176.430', '1.890.017.183'],
			['TVIP - PUSAT', 'PUSAT/UMUM/UMUM/UMUM', '01/01/2025', '2501/11/003', 'PENGISIAN KAS PUSAT 06/01/25-11/01/25', '-1.890.017.183', '0', '17.500.000', '1.872.517.183'],
			['TVIP - PUSAT', 'PUSAT/UMUM/UMUM/UMUM', '01/01/2025', '2501/11/002', 'PENGISIAN KAS PUSAT 01/01/25-04/01/25', '1.872.517.183', '0', '27.564.341', '1.844.952.842'],
			['TVIP - PUSAT', 'PUSAT/UMUM/UMUM/UMUM', '03/01/2025', '2501/11/004', 'PENGISIAN KAS PUSAT 06/01/25-11/01/25', '1.844.952.842', '0', '14.424.491', '1.830.528.351'],
			['TVIP - PUSAT', 'PUSAT/UMUM/UMUM/UMUM', '06/01/2025', '2501/11/006', 'PENGISIAN KAS PUSAT 06/01/25-11/01/25', '1.830.528.351', '0', '5.000.000', '1.825.528.351'],
			['TVIP - PUSAT', 'PUSAT/UMUM/UMUM/UMUM', '06/01/2025', '2501/11/005', 'PENGISIAN KAS PUSAT 06/01/25-11/01/25', '1.825.528.351', '0', '28.019.233', '1.797.509.118'],
		];

		// ============================
		// HITUNG AUTO WIDTH
		// ============================
		$columns = array_keys($header);
		$maxLen = [];

		foreach ($columns as $i => $colName) {
			$maxLen[$i] = strlen($colName);
		}

		foreach ($data as $row) {
			foreach ($row as $i => $value) {
				$len = strlen((string)$value);
				if ($len > $maxLen[$i]) {
					$maxLen[$i] = $len;
				}
			}
		}

		$autoWidth = [];
		foreach ($maxLen as $i => $charLen) {
			if ($i == 1) {
				$charLen += 5;
			}
			$pixel = ($charLen * 7) + 25;
			$width = round($pixel * 0.14, 2);
			$autoWidth[$i] = $width;
		}

		// ============================
		// TULIS FILE
		// ============================
		$writer = new XLSXWriter();

		

		// === TITEL LAPORAN ===
		$writer->writeSheetRow('Sheet1', ['Laporan Account Ledger & Subledger']);
		$writer->markMergedCell('Sheet1', 0, 0, 0, 8);

		// baris kosong
		$writer->writeSheetRow('Sheet1', ['']);

		// === INFO LAPORAN ===
		$infoRows = [
			['Depo', 'Semua Depo'],
			['Nama Akun', 'KAS BESAR'],
			['No. Akun', '100101'],
			['Departemen', 'Semua Department'],
			['Periode', '01-01-2025 s/d 30-01-2025'],
		];

		foreach ($infoRows as $row) {
			$row = array_pad($row, 9, '');
			$writer->writeSheetRow('Sheet1', $row);
		}

		$writer->writeSheetRow('Sheet1', ['']);
		// === HEADER TABLE (ONLY ONE) ===
		$styles = $headerStyle;
		$styles['widths'] = $autoWidth;

		$writer->writeSheetHeader('Sheet1', $header, $styles);
		// === DATA TABEL ===
		foreach ($data as $row) {
			$writer->writeSheetRow('Sheet1', $row, $bodyStyle);
		}
		$writer->writeToStdOut();
	}
}
