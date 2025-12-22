<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Border;
class CR_trialbalance extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('Tcpdf_custom');
		$this->load->model('M_global');
		$this->load->model('M_report');
		$this->company =  $this->session->userdata('sess_company');
		$this->cp_name =  $this->session->userdata('sess_company_name');
	}
	function index()
	{
		$data['judul']        = 'Trialbalance';
		$data['load_grid']    = 'CR_trialbalance';
		$data['company']      = $this->company . ' - ' . $this->cp_name;
		$data['depo']         = $this->M_global->getWhere("depos", ['code_company' => $this->company])->result();
		$this->load->view("VR_report/VR_trialbalance", $data);
	}

	public function Report()
	{
		// ==============================
		// SPREADSHEET INIT
		// ==============================
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		// ==============================
		// TITLE
		// ==============================
		$title = 'TRIALBALANCE SALDO [Branch : Head office ]';
		$sheet->setCellValue('A1', $title);
		$sheet->mergeCells('A1:F1');
		$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);

		$sheet->setCellValue('A2', 'Periode 1 Januari s/d 31 Januari 2025');
		$sheet->mergeCells('A2:F2');
		$sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

		// ==============================
		// HEADER TABLE
		// ==============================
		$header = [
			'A4' => 'NO. AKUN',
			'B4' => 'NAMA AKUN',
			'C4' => 'SALDO AWAL',
			'D4' => 'DEBIT',
			'E4' => 'KREDIT',
			'F4' => 'SALDO AKHIR',
		];

		foreach ($header as $cell => $text) {
			$sheet->setCellValue($cell, $text);
		}

		$sheet->getStyle('A4:F4')->getFont()->setBold(true);
		$sheet->getStyle('A4:F4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

		// ==============================
		// DATA DUMMY (SEPERTI GAMBAR)
		// ==============================
		$data = [
			['100101', 'KAS BESAR', 21899193613, 9573992306, 7910785492, 3562400427],
			['100102', 'KAS KECIL', 0, 0, 0, 0],
			['100102001', 'KAS KECIL PUSAT', 28541059, 206887452, 200669000, 34759511],
			['100102002', 'KAS KECIL GA', -744500, 7615000, 7073500, -203000],
			['100102003', 'KAS KECIL ICT', 1640700, 5048279, 5580979, 1108000],
			['100102004', 'KAS KECIL BENGKEL', 831200, 9603197, 9027797, 1406600],
			['100102005', 'KAS KECIL OPS RCM', -689000, 16388680, 14206080, 1493600],
			['100102006', 'KAS KECIL PURCHASING', 25213651, 38114420, 36308849, 27019222],
			['100103', 'KAS BESAR DEPO', -38176337180, 0, 0, 0],

			['1002', 'BANK', 0, 0, 0, 0],
			['100201', 'BCA 2022 - PUSAT', 14126116061, 209656099704, 208947345782, -13417362139],
			['100202', 'BCA 1008 - PUSAT', 554761035, 3585678196, 3587926307, 552512924],
			['100203', 'BCA 31988 - PUSAT', 189538719, 4500000000, 4139086156, 550452563],
			['100204', 'BCA 9996 - PUSAT', 530250933, 44507097519, 44512776633, 524580819],
			['100205', 'BCA 86688 - PUSAT', 276590073, 1702233409, 1477669193, 501154289],
			['100206', 'MEGA 9898-8 - PUSAT', 65686673, 221308, 41549, 60794710],
			['100207', 'OCBC NISP - 08826', -30000000, 0, 50000, 24881740],
			['100208', 'DANAMON', 509406963, 72303110, 96836, 581613237],
		];

		$row = 5;
		$totalDebit = $totalKredit = 0;

		foreach ($data as $d) {
			$sheet->fromArray($d, null, 'A' . $row);

			$totalDebit  += $d[3];
			$totalKredit += $d[4];

			$row++;
		}

		// ==============================
		// TOTAL
		// ==============================
		$sheet->setCellValue('B' . $row, 'TOTAL');
		$sheet->setCellValue('D' . $row, $totalDebit);
		$sheet->setCellValue('E' . $row, $totalKredit);
		$sheet->setCellValue('F' . $row, $totalDebit - $totalKredit);

		$sheet->getStyle('A' . $row . ':F' . $row)->getFont()->setBold(true);

		// ==============================
		// FORMAT NUMBER
		// ==============================
		$sheet->getStyle('C5:F' . $row)
			->getNumberFormat()
			->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

		// ==============================
		// BORDER
		// ==============================
		$sheet->getStyle('A4:F' . $row)->getBorders()->getAllBorders()
			->setBorderStyle(Border::BORDER_THIN);

		// AUTO WIDTH
		foreach (range('A', 'F') as $col) {
			$sheet->getColumnDimension($col)->setAutoSize(true);
		}

		// ==============================
		// OUTPUT
		// ==============================
		$filename = 'report_trialbalance_' . date('Ymd_His') . '.xlsx';

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment; filename=\"{$filename}\"");
		header('Cache-Control: max-age=0');

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
		exit;
	}
}
