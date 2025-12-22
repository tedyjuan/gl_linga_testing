<?php
defined('BASEPATH') or exit('No direct script access allowed');
class CR_balance_sheet extends CI_Controller
{
	function __construct()
	{
		// NERACA
		parent::__construct();
		is_logged_in();
		$this->load->model('M_global');
		// LOAD AUTLOADER COMPOSER
		require_once FCPATH . 'vendor/autoload.php';
		$this->company =  $this->session->userdata('sess_company');
		$this->cp_name =  $this->session->userdata('sess_company_name');
	}
	function index()
	{
		$data['judul']     = 'Balance Sheet';
		$data['load_grid'] = 'CR_balance_sheet';
		$data['company']      = $this->company . ' - ' . $this->cp_name;
		$this->load->view("VR_report/VR_balance_sheet", $data);
	}


	
	public function Report()
	{
		// Semua input GET difilter XSS otomatis
		$type_report   = $this->input->get('type_report', TRUE);
		$type_periode  = $this->input->get('type_periode', TRUE);
		$mtd_ytd_date  = $this->input->get('mtd_ytd_date', TRUE);
		$start_date    = $this->input->get('start_date', TRUE);
		$end_date      = $this->input->get('end_date', TRUE);
		//  var_dump($start_date);
		//  var_dump($end_date);
		//   die; 
		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		// ==============================
		// HEADER PERUSAHAAN
		// ==============================
		$sheet->setCellValue('A1', 'PT. BINTANG MITRA PRATAMA');
		$sheet->mergeCells('A1:B1');
		$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
		$sheet->getRowDimension(1)->setRowHeight(25);
		$sheet->getStyle('A1')->getAlignment()
			->setHorizontal('center')
			->setVertical('center');

		// ==============================
		// TITLE LAPORAN
		// ==============================
		if($type_report == 'NRH'){
			$sheet->setCellValue('A2', 'Neraca Header (Induk Skontro)');
		}else{
			$sheet->setCellValue('A2', 'Neraca Detail (Induk Skontro)');
		}
		$sheet->mergeCells('A2:B2');
		$sheet->getStyle('A2')->getFont()->setBold(true)->setSize(13);
		$sheet->getStyle('A2')->getAlignment()->setHorizontal('center');
		$text_periode = "Per Tgl. " . $start_date .' Sampai ' . $end_date;
		$sheet->setCellValue('A3', $text_periode);
		$sheet->mergeCells('A3:B3');
		$sheet->getStyle('A3')->getFont()->setBold(true)->setSize(12);
		$sheet->getStyle('A3')->getAlignment()->setHorizontal('center');

		// Spacing
		$sheet->setCellValue('A4', '');

		// ==============================
		// HEADER TABLE
		// ==============================
		$sheet->setCellValue('A5', 'ACCOUNT');
		$sheet->setCellValue('B5', 'HEAD OFFICE');

		// Width kolom (FIX)
		$sheet->getColumnDimension('A')->setWidth(70);  // panjang fleksibel
		$sheet->getColumnDimension('B')->setWidth(20);

		// Styling header
		$sheet->getStyle('A5:B5')->applyFromArray([
			'font' => ['bold' => true],
			'alignment' => [
				'horizontal' => 'center',
				'vertical' => 'center'
			],
			'borders' => [
				'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
			],
			'fill' => [
				'fillType' => 'solid',
				'startColor' => ['rgb' => 'D9D9D9']
			]
		]);

		// ==============================
		// DATA AKTIVA
		// ==============================
		$data_aktiva = [
			['ACCOUNT 1 SANGAT PANJANG ……… TEST WRAP TEXT', 1000],
			['ACCOUNT 2', 1000],
			['ACCOUNT 3', 1000],
			['ACCOUNT 4', 1000],
		];
		$total_aktiva = 4000;

		$row = 6;

		foreach ($data_aktiva as $d) {

			$sheet->setCellValue("A{$row}", $d[0]);
			$sheet->setCellValue("B{$row}", number_format($d[1], 0));

			// Wrap text kolom A
			$sheet->getStyle("A{$row}")->getAlignment()->setWrapText(true);

			// Border
			$sheet->getStyle("A{$row}:B{$row}")->getBorders()
				->getAllBorders()->setBorderStyle('thin');

			$row++;
		}

		// ==============================
		// TOTAL AKTIVA
		// ==============================
		$sheet->setCellValue("A{$row}", 'TOTAL AKTIVA');
		$sheet->setCellValue("B{$row}", number_format($total_aktiva, 0));

		$sheet->getStyle("A{$row}:B{$row}")->applyFromArray([
			'font' => ['bold' => true],
			'fill' => [
				'fillType' => 'solid',
				'startColor' => ['rgb' => 'D9D9D9']
			],
			'borders' => [
				'allBorders' => ['borderStyle' => 'thin']
			]
		]);

		// ==============================
		// DATA PASIVA
		// ==============================
		$row += 1;

		$data_pasiva = [
			['ACCOUNT PASIVA 1', 1000],
			['ACCOUNT PASIVA 2', 1000],
			['ACCOUNT PASIVA 3', 1000],
			['ACCOUNT PASIVA 4', 1000],
			['ACCOUNT PASIVA 5', 1000],
		];

		foreach ($data_pasiva as $p) {
			$sheet->setCellValue("A{$row}", $p[0]);
			$sheet->setCellValue("B{$row}", number_format($p[1], 0));

			$sheet->getStyle("A{$row}:B{$row}")->getBorders()
				->getAllBorders()->setBorderStyle('thin');

			$row++;
		}

		// ==============================
		// TOTAL PASIVA
		// ==============================
		$total_pasiva = 5000;

		$sheet->setCellValue("A{$row}", 'TOTAL PASIVA');
		$sheet->setCellValue("B{$row}", number_format($total_pasiva, 0));

		$sheet->getStyle("A{$row}:B{$row}")->applyFromArray([
			'font' => ['bold' => true],
			'fill' => [
				'fillType' => 'solid',
				'startColor' => ['rgb' => 'D9D9D9']
			],
			'borders' => [
				'allBorders' => ['borderStyle' => 'thin']
			]
		]);

		// ==============================
		// OUTPUT
		// ==============================
		$filename = 'laporan_neraca_' . date('Ymd_His') . '.xlsx';

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment; filename=\"{$filename}\"");
		header('Cache-Control: max-age=0');

		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
		$writer->save('php://output');
	}
}
