<?php
defined('BASEPATH') or exit('No direct script access allowed');


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Border;

class CR_profit_and_lost extends CI_Controller
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
		$data['judul']     = 'Profit And Lost Statement (P&L)';
		$data['load_grid'] = 'CR_profit_and_lost';
		$data['company']      = $this->company . ' - ' . $this->cp_name;
		$this->load->view("VR_report/VR_profit_and_lost", $data);
	}



	public function Report()
	{
		// ==============================
		// INPUT
		// ==============================
		$start_date   = $this->input->get('start_date', TRUE);
		$end_date     = $this->input->get('end_date', TRUE);
		$type_periode = $this->input->get('type_periode', TRUE); //MTD or YTD
		$mtd_ytd_date = $this->input->get('mtd_ytd_date', TRUE);
		// ==============================
		// DATA DEPARTEMENT (HEADER KOLOM)
		// ==============================
		$departement = [
			(object)['alias' => 'MGMT'],
			(object)['alias' => 'CSA'],
			(object)['alias' => 'SDEV'],
		];

		// ==============================
		// DATA COST CENTER
		// ==============================
		$data_cosenter = [
			[
				'no_acc' => '6001',
				'nama'   => 'BIAYA OPERASIONAL',
				'level'  => 1,
				'nilai'  => [
					'MGMT' => 309513472,
					'CSA'  => 276288103,
					'SDEV' => 2979858834,
				]
			],
			[
				'no_acc' => '600101',
				'nama'   => 'GAJI DAN UPAH',
				'level'  => 2,
				'nilai'  => [
					'MGMT' => 107935220,
					'CSA'  => 179636069,
					'SDEV' => 2242688909,
				]
			],
			[
				'no_acc' => '600102',
				'nama'   => 'PENGOBATAN',
				'level'  => 2,
				'nilai'  => [
					'MGMT' => 6783366,
					'CSA'  => 7666914,
					'SDEV' => 33710810,
				]
			],
			[
				'no_acc' => '6002',
				'nama'   => 'BIAYA ADMINISTRASI',
				'level'  => 1,
				'nilai'  => [
					'MGMT' => 215979642,
					'CSA'  => 4199400,
					'SDEV' => 68371176,
				]
			],
			[
				'no_acc' => '600201',
				'nama'   => 'PENGOBATAN Tes',
				'level'  => 2,
				'nilai'  => [
					'MGMT' => 6783366,
					'CSA'  => 7666914,
					'SDEV' => 33710810,
				]
			],
		];

		// ==============================
		// SPREADSHEET INIT
		// ==============================
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		// ==============================
		// HEADER PERUSAHAAN
		// ==============================
		$sheet->setCellValue('A1', 'PT. BINTANG MITRA PRATAMA');
		$sheet->mergeCells('A1:E1');
		$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
		$sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
		$pesan =  'Laporan Biaya Per Cost Center ' . $type_periode;
		$sheet->setCellValue('A2', $pesan);
		$sheet->mergeCells('A2:E2');
		$sheet->getStyle('A2')->getFont()->setBold(true)->setSize(13);
		$sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

		$sheet->setCellValue('A3', "Periode {$start_date} s/d {$end_date}");
		$sheet->mergeCells('A3:E3');
		$sheet->getStyle('A3')->getAlignment()->setHorizontal('center');

		// ==============================
		// HEADER TABLE
		// ==============================
		$sheet->setCellValue('A5', 'NO ACC');
		$sheet->setCellValue('B5', 'NAMA AKUN');

		$startColIndex = 3; // C
		foreach ($departement as $i => $dep) {
			$col = Coordinate::stringFromColumnIndex($startColIndex + $i);
			$sheet->setCellValue($col . '5', $dep->alias);
		}

		$lastColIndex = $startColIndex + count($departement) - 1;
		$lastCol = Coordinate::stringFromColumnIndex($lastColIndex);

		$sheet->getStyle("A5:{$lastCol}5")->applyFromArray([
			'font' => ['bold' => true],
			'alignment' => ['horizontal' => 'center'],
			'borders' => [
				'allBorders' => ['borderStyle' => Border::BORDER_THIN]
			],
			'fill' => [
				'fillType' => 'solid',
				'startColor' => ['rgb' => 'D9D9D9']
			]
		]);

		// ==============================
		// DATA ROW
		// ==============================
		$row = 6;
		$grandTotal = [];

		foreach ($data_cosenter as $acc) {

			$sheet->setCellValue("A{$row}", $acc['no_acc']);
			$sheet->setCellValue("B{$row}", $acc['level'] == 2 ? '   ' . $acc['nama'] : $acc['nama']);

			if ($acc['level'] == 1) {
				$sheet->getStyle("A{$row}:{$lastCol}{$row}")
					->getFont()->setBold(true);
			}

			$colIndex = $startColIndex;
			foreach ($departement as $dep) {

				$col = Coordinate::stringFromColumnIndex($colIndex);
				$value = $acc['nilai'][$dep->alias] ?? 0;

				$sheet->setCellValue($col . $row, $value);
				$sheet->getStyle($col . $row)
					->getNumberFormat()->setFormatCode('#,##0');

				// TOTAL LEVEL 1 SAJA
				if ($acc['level'] == 1) {
					$grandTotal[$dep->alias] = ($grandTotal[$dep->alias] ?? 0) + $value;
				}

				$colIndex++;
			}

			$sheet->getStyle("A{$row}:{$lastCol}{$row}")
				->getBorders()->getAllBorders()
				->setBorderStyle(Border::BORDER_THIN);

			$row++;
		}

		// ==============================
		// GRAND TOTAL
		// ==============================
		$sheet->setCellValue("B{$row}", 'Total Biaya per Cost Center');
		$sheet->getStyle("A{$row}:{$lastCol}{$row}")
			->getFont()->setBold(true);

		$colIndex = $startColIndex;
		foreach ($departement as $dep) {

			$col = Coordinate::stringFromColumnIndex($colIndex);
			$sheet->setCellValue($col . $row, $grandTotal[$dep->alias] ?? 0);
			$sheet->getStyle($col . $row)
				->getNumberFormat()->setFormatCode('#,##0');

			$colIndex++;
		}

		$sheet->getStyle("A{$row}:{$lastCol}{$row}")
			->getBorders()->getAllBorders()
			->setBorderStyle(Border::BORDER_THIN);

		// ==============================
		// AUTO WIDTH
		// ==============================
		foreach (range('A', $lastCol) as $col) {
			$sheet->getColumnDimension($col)->setAutoSize(true);
		}

		// ==============================
		// OUTPUT
		// ==============================
		$filename = 'laporan_profit_and_lost_' . date('Ymd_His') . '.xlsx';

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment; filename=\"{$filename}\"");
		header('Cache-Control: max-age=0');

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
		exit;
	}
}
