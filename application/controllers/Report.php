<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report extends CI_Controller
{

	public function export_excel()
	{
		// Load library
		require APPPATH . 'third_party/xlsxwriter/xlsxwriter.class.php';

		$filename = "report.xlsx";

		// Header download
		header('Content-disposition: attachment; filename="' . $filename . '"');
		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");

		// Template header
		$header = [
			'Kode'  => 'string',
			'Nama'  => 'string',
			'Jumlah' => 'number',
		];

		// Contoh data (bisa diganti query)
		$data = [
			['A001', 'Produk A', 1000],
			['A002', 'Produk B', 2000],
		];

		// Tulis ke Excel
		$writer = new XLSXWriter();
		$writer->writeSheetHeader('Sheet1', $header);

		foreach ($data as $row) {
			$writer->writeSheetRow('Sheet1', $row);
		}

		$writer->writeToStdOut();
	}
}
