<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_journal_posted extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model('M_journal_posted');
		$this->load->model('M_journal_entry');
		$this->load->model('M_global');
		$this->name = $this->session->userdata('sess_name');
		$this->company = $this->session->userdata('sess_company');
	}
	function index()
	{
		$data['judul']      = 'Journal Posted';
		$data['load_grid']  = 'C_journal_posted';
		$data['load_add']   = 'C_journal_posted/add';
		$data['url_delete'] = 'C_journal_posted/delete';
		$param['code_company'] = $this->session->userdata('sess_company');
		$data['depos'] = $this->M_global->getWhere('depos', $param)->result();
		$data['journal_sources'] = $this->M_global->getWhere('journal_sources', $param)->result();
		$this->load->view("v_journal_posted/grid_journal_posted", $data);
	}
	public function griddata()
	{
		
		$start          = $this->input->post('start') ?? 0;
		$length         = $this->input->post('length') ?? 10;
		$search_input   = $this->input->post('search');
		$search         = isset($search_input['value']) ? $search_input['value'] : '';
		$order_input    = $this->input->post('order');
		$order_col      = isset($order_input[0]['column']) ? $order_input[0]['column'] : 0;
		$dir            = isset($order_input[0]['dir']) ? $order_input[0]['dir'] : 'desc';
		$columns        = ['transaction_date', 'code_journal_source', 'batch_number', 'voucher_number', 'action'];
		$order_by       = $columns[$order_col] ?? 'name';
		$data           = $this->M_journal_posted->get_paginated_journal_posted($length, $start, $search, $order_by, $dir);
		$total_records  = $this->M_journal_posted->count_all_journal_posted();
		$total_filtered = $this->M_journal_posted->count_filtered_journal_posted($search);
		$url_detail       = 'C_journal_posted/detailform';
		$result = [];
		foreach ($data as $row) {
			$aksi = '<button type="button"  onclick="editform(\'' . $url_detail . '\', \'' . $row->uuid . '\')" class="btn btn-soft-primary"><i class="bi bi-eye"></i> Detail</button>';
			$result[] = [
				$row->transaction_date,
				$row->code_journal_source . ' - ' . $row->journal_source_name,
				$row->batch_number,
				$row->voucher_number,
				$aksi,
			];
		}
		echo json_encode([
			"draw" => intval($this->input->post('draw')) ?? 1,
			"recordsTotal" => $total_records,
			"recordsFiltered" => $total_filtered,
			"data" => $result
		]);
	}

	public function detailform($uuid)
	{
		$cekdata =  $this->M_journal_entry->get_where_journal_entry(['a.uuid' => $uuid])->row();
		if ($cekdata != null) {
			$param = ['batch_number' => $cekdata->batch_number];
			$data['head'] = $this->M_journal_entry->get_where_journal_entry($param)->row();
			$data['journal_item']   = $this->M_journal_entry->get_journal_entry_item($param)->result();
			$data['judul']          = "Detail Journal Posted";
			$data['load_grid']      = 'C_journal_posted';
			$data['load_refresh']   = "C_journal_posted/detailform/" . $uuid;
			$data['uuid']           = $uuid;
			$data['data']           = $cekdata;
			$this->load->view("v_journal_entry/detail_journal_entry", $data);
		} else {
			$this->load->view('error');
		}
	}
	public function unposting_journal()
	{
		$branch  = $this->input->post('branch');
		$periode = $this->input->post('date_periode_journal');

		list($year, $month) = explode('-', $periode);
		$month = (int)$month;

		$param = [
			'code_company' => $this->company,
			'code_depo'    => $branch,
			'year'         => $year,
			'period'       => $month,
		];

		// 1. VALIDASI FISCAL PERIOD
		$cek_period = $this->M_global->getWhere('fiscal_periods', $param)->row();
		if (!$cek_period) {
			echo json_encode(['hasil' => 'false', 'pesan' => 'Fiscal period not found']);
			return;
		}
		if ($cek_period->status == 'closed') {
			echo json_encode(['hasil' => 'false', 'pesan' => 'Period closed']);
			return;
		}

		// 2. AMBIL POSTING BALANCE YANG AKAN DI-RESET
		$balances = $this->db->get_where('posting_balances', $param)->result();
		if (empty($balances)) {
			echo json_encode(['hasil' => 'false', 'pesan' => 'No posting found']);
			return;
		}

		// 3. TRANSAKSI
		$this->db->trans_begin();

		// 4. RESET NILAI (SET 0)
		$this->db
			->set('debit', 0)
			->set('credit', 0)
			->set('updated_at', date('Y-m-d H:i:s'))
			->where($param)
			->update('posting_balances');

		// 5. UPDATE JOURNAL STATUS
		$this->db
			->set('status', 'unposted')
			->where('code_depo', $branch)
			->where('year', $year)
			->where('period', $month)
			->update('journals');

		// 6. COMMIT
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(['hasil' => 'false', 'pesan' => 'Unposting failed']);
		} else {
			$this->db->trans_commit();
			echo json_encode(['hasil' => 'true', 'pesan' => 'Unposting success']);
		}
	}
}
