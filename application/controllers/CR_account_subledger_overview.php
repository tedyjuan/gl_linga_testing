<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CR_account_subledger_overview extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('Tcpdf_custom');
		$this->load->model('M_global');
		$this->load->model('M_report');
		$this->company =  $this->session->userdata('sess_company');
	}
	function index()
	{
		$data['judul']        = 'Subledger Overview';
		$data['load_grid']    = 'CR_account_subledger_overview';
		$data['code_company'] = $this->company;
		$data['depo']         = $this->M_global->getWhere("depos", ['code_company' => $this->company])->result();
		$this->load->view("VR_subledger_overview/VR_report_pdf", $data);
	}
	public function get_account()
	{
		$get_value = $this->input->get('cari');
		$cari = preg_replace("/[^a-zA-Z0-9]/", '', $get_value);
		$hasil = $this->M_report->get_account_centers($cari);
		echo json_encode($hasil);
	}
	public function get_account_to()
	{
		$get_value = $this->input->get('cari');
		$cari = preg_replace("/[^a-zA-Z0-9]/", '', $get_value);
		$hasil = $this->M_report->get_account_to($cari);
		echo json_encode($hasil);
	}
	public function Report()
	{
		$period = $this->input->get('period');
		$branch = $this->input->get('branch');
		$start  = $this->input->get('start');
		$end    = $this->input->get('end');
		// Pakai class custom
		$pdf = new Tcpdf_custom('P', 'mm', 'A4', true, 'UTF-8', false);

		// Metadata
		$pdf->SetCreator('CI3');
		$pdf->SetAuthor('Teddy');
		$pdf->SetTitle('Account Subledger Overview');

		// Margin (top diperbesar karena header)
		$pdf->SetMargins(5, 28, 5);
		$pdf->SetAutoPageBreak(true, 15);
		$pdf->setPrintHeader(true);
		// $pdf->setPrintFooter(true);

		$pdf->AddPage();
		// ============================
		// DATA DUMMY
		// ============================
		$data = [
			["04/01/2025", "2501/01/001", "2501/13", "PENGISIAN KAS PGT 01/01-25/04/01/25", 37717371, 38217263],
			["10/01/2025", "2501/11/002", "2501/13", "PENGELUARAN KAS PUSAT 01/01-11/01/25", 64934224, 71040139],
			["10/01/2025", "2501/11/003", "2501/13", "PENGELUARAN KAS PGT 01/01-11/01/25", 45678901, 954467587],
			["13/01/2025", "2501/13/004", "2501/13", "PENGELUARAN KAS PST 27/01-25/31/01/25", 37190702, 29081511],
			["31/01/2025", "2501/31/005", "2501/15/00", "PENGELUARAN KAS KASBON JAN 25", 0, 0],
		];
		// ============================
		// ACCOUNT TITLE
		// ============================
		$accountTitle = '
		    <table width="100%" cellpadding="3" style="font-size:8px;">
		        <tr>
		            <td>ACCOUNT : 100102001 — KAS KECIL PUSAT</td>
		            <td>COST CENTER : 0199999</td>
		        </tr>
		        <tr>
		            <td><b>* BEGINNING BALANCE ACCOUNT</b></td>
		            <td align="right"><b>' . number_format(10000, 0, ',', '.') . '</b></td>
		        </tr>
		    </table>
		';
		$pdf->writeHTML($accountTitle, false, false, false, false, '');
		$saldo = 119045659;
		$debit = 19045659;
		$credit = 19045659;
		$dummy = '';

		for ($i = 0; $i < 10; $i++) {   // banyak dummy untuk multi-page
			foreach ($data as $row) {

				$saldo = $saldo + $row[4] - $row[5];

				$dummy .= '
                    <table width="100%" cellpadding="3" style="font-size:7px;">
                        <tr>
                            <td width="8%">' . $row[0] . '</td>
                            <td width="14%">' . $row[1] . '</td>
                            <td width="10%">' . $row[2] . '</td>
                            <td width="32%">' . $row[3] . '</td>
                            <td width="12%" align="right">' . number_format($row[4], 0, ',', '.') . '</td>
                            <td width="12%" align="right">' . number_format($row[5], 0, ',', '.') . '</td>
                            <td width="12%" align="right"></td>
                        </tr>
                    </table>
                ';
			}
		}

		$pdf->writeHTML($dummy, false, false, false, false, '');

		// ============================
		// ENDING BALANCE
		// ============================
		$footer = '
            <table width="100%" cellpadding="3" style="font-size:8px;">
                <tr>
                    <td width="64%"><b>* ENDING BALANCE ACCOUNT</b></td>
                    <td width="12%" align="right"><b>' . number_format($debit, 0, ',', '.') . '</b></td>
                    <td width="12%" align="right"><b>' . number_format($credit, 0, ',', '.') . '</b></td>
                    <td width="12%" align="right"><b>' . number_format($saldo, 0, ',', '.') . '</b></td>
                </tr>
            </table>
			<hr>
        ';
		$pdf->writeHTML($footer, false, false, false, false, '');

		// OUTPUT
		$pdf->Output('Account-Subledger-Overview.pdf', 'I');
	}
}
