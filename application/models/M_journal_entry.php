<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_journal_entry extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}
	public function get_paginated_journal_entry($limit, $start, $search, $order_by, $order_dir)
	{
		$batch_voucher = trim($this->input->post('batch_voucher'));
		$date_periode  = $this->input->post('date_periode');
		$journal_type  = $this->input->post('journal_type');
		$post_branch  = $this->input->post('post_branch');
		$this->db->select('a.*, b.description AS journal_source_name');
		$this->db->from('journals as a');
		$this->db->join('journal_sources as b', 'b.code_journal_source = a.code_journal_source', 'left');
		$this->db->where('a.status', 'unposted');
		if ($date_periode != '') {
			// cek apakah mengandung " to "
			if (strpos($date_periode, ' to ') !== false) {
				// format range: start to end
				$dateRange = explode(' to ', $date_periode);
				if (count($dateRange) === 2) {
					$startDate = $dateRange[0];
					$endDate   = $dateRange[1];
					$this->db->where('a.transaction_date >=', $startDate);
					$this->db->where('a.transaction_date <=', $endDate);
				} 
			} else {
				// format single date
				$startDate = $date_periode;
				$endDate   = $date_periode;
				$this->db->where('a.transaction_date', $startDate);
			}
		}
		// filter jenis jurnal
		if ($journal_type != '') {
			$this->db->where('a.code_journal_source', $journal_type);
		}
		// filter batch / voucher
		if ($batch_voucher != '') {
			$this->db->where('a.batch_number', $batch_voucher);
		}
		if ($post_branch != '') {
			$this->db->where('a.code_depo', $post_branch);
		}
		// pencarian
		if (!empty($search)) {
			$this->db->group_start()
				->like('a.batch_number', $search)
				->or_like('a.voucher_number', $search)
				->or_like('a.transaction_date', $search)
				->or_like('a.code_journal_source', $search)
				->group_end();
		}

		// limit & order
		$this->db->limit($limit, $start);
		$this->db->order_by($order_by, $order_dir);

		$query = $this->db->get();
		return $query->result();
	}

	// Fungsi untuk menghitung total data
	public function count_all_journal_entry()
	{
		return $this->db->count_all('journals');
	}
	// Fungsi untuk menghitung jumlah data yang difilter berdasarkan pencarian
	public function count_filtered_journal_entry($search)
	{
		$this->db->like('batch_number', $search);
		$this->db->or_like('voucher_number', $search); 
		$this->db->or_like('transaction_date', $search); 
		$this->db->or_like('code_journal_source', $search); 
		$query = $this->db->get('journals');
		return $query->num_rows();
	}
	public function get_where_journal_entry($param)
	{
		$this->db->select('a.*,
						 b.description AS journal_source_name,
						c.name AS branch_name');
		$this->db->from('journals as a');
		$this->db->join('journal_sources as b', 'b.code_journal_source = a.code_journal_source', 'left');
		$this->db->join('depos as c', 'c.code_depo = a.code_depo', 'left');
		$this->db->where($param);
		return $this->db->get();
	}
	public function get_journal_entry_item($param)
	{
		$this->db->select('a.*,
						b.group_team,
						c.name AS account_name');
		$this->db->from('journal_items as a');
		$this->db->join('cost_centers as b', 'b.code_cost_center = a.code_cost_center', 'left');
		$this->db->join('chart_of_accounts as c', 'c.account_number = a.code_coa', 'left');
		$this->db->where($param);
		return $this->db->get();
	}


	 public function get_cc($cari)
	{
		$sql = "SELECT 
			a.code_cost_center,
			a.group_team
			FROM cost_centers a 
			WHERE (a.code_cost_center LIKE ? OR a.group_team LIKE ?)
			AND a.code_company = ?";
		$param = ["%$cari%", "%$cari%", $this->session->userdata('sess_company')];
		return $this->db->query($sql, $param)->result();
	}

	 public function generate_batch_code($batch_type, $batch_date, $branch){
		$date_part = date('ymd', strtotime($batch_date));
		$prefix = $batch_type . $branch . $date_part;
		$this->db->select('MAX(batch_code) AS max_code');
		$this->db->from('journal_entries');
		$this->db->like('batch_code', $prefix, 'after');
		$query = $this->db->get();
		$row = $query->row();
		if ($row && $row->max_code) {
			$last_sequence = (int)substr($row->max_code, strlen($prefix)); 
			$new_sequence = $last_sequence + 1;
		} else {
			$new_sequence = 1;	
		}
		$new_batch_code = $prefix . str_pad($new_sequence, 4, '0', STR_PAD_LEFT);
		return $new_batch_code;
	 }
	public function preview_code_journal($batch_code,  $date, $branch)
	{
		$current_year = date('Y', strtotime($date));
		$month_year = date('m', strtotime($date));
		$sql = "SELECT IFNULL(MAX(a.`sequence`), 0) AS seq
				FROM `journals` AS a
				WHERE a.`year` = ?
				AND a.`period` = ?
				AND a.`code_journal_source`= ?
				AND a.`code_depo` = ?
		";
		$param = [
			$current_year,
			$month_year,
			$batch_code,
			$branch,
		];
		$query = $this->db->query($sql, $param);
		$row = $query->row();
		$next_sequence = $row->seq + 1;
		$formatted_sequence = str_pad($next_sequence, 4, '0', STR_PAD_LEFT);
		$preview_code = $batch_code . '/' . $current_year . $month_year . '/' . $formatted_sequence;
		return $preview_code;

	}

	public function geser_batch_journal($batch_type, $branch, $year, $period)
	{
		// 1. AMBIL SEMUA DATA JURNAL DALAM 1 PERIODE
		$journals = $this->db
			->where('code_journal_source', $batch_type)
			->where('code_depo', $branch)
			->where('year', $year)
			->where('period', $period)
			->order_by('transaction_date', 'ASC')
			->order_by('id', 'ASC')
			->get('journals')
			->result();

		if (!$journals) {
			return;
		}

		$seq = 1;
		$updateJournal = [];
		$updateItem = [];

		foreach ($journals as $j) {

			$new_counter = sprintf("%s/%s%s/%04d", $batch_type, $year, $period, $seq);
			// UPDATE HEADER
			$updateJournal[] = [
				'id'             => $j->id,
				'sequence'       => $seq,
				'batch_number'   => $new_counter,
				'voucher_number' => $new_counter
			];

			// UPDATE DETAIL (journal_items)
			// sesuaikan sequence_header + batch_number baru
			$updateItem[] = [
				'sequence_header' => $seq,
				'batch_number'    => $new_counter,
				'old_batch'       => $j->batch_number // untuk where nanti
			];

			$seq++;
		}

		// 2. UPDATE JOURNALS (HEADER)
		if (!empty($updateJournal)) {
			$this->db->update_batch('journals', $updateJournal, 'id');
		}

		// 3. UPDATE JOURNAL ITEMS (DETAIL)
		foreach ($updateItem as $ui) {
			$this->db
				->where('batch_number', $ui['old_batch'])
				->update('journal_items', [
					'sequence_header' => $ui['sequence_header'],
					'batch_number'    => $ui['batch_number']
				]);
		}
	}

	 public function check_journal_balance($branch, $year, $month){
		$sql = "SELECT
				j.batch_number,
				j.transaction_date,
				COALESCE(SUM(ji.debit), 0) AS total_debit,
				COALESCE(SUM(ji.credit), 0) AS total_credit,
				(COALESCE(SUM(ji.debit), 0) - COALESCE(SUM(ji.credit), 0)) AS difference
			FROM journals j
			LEFT JOIN journal_items ji ON j.batch_number = ji.batch_number
			WHERE j.code_depo = ?
			AND j.status = 'unposted'
			AND YEAR(j.transaction_date) = ?
			AND MONTH(j.transaction_date) = ?
			GROUP BY j.batch_number, j.transaction_date
			HAVING difference <> 0;";
		$param = [$branch, $year, $month];
		$query = $this->db->query($sql, $param)->result();
		return $query;
	}
	public function get_summary_posting($code_depo, $year, $period)
	{
		$sql = "SELECT
				ji.code_coa,
				ji.code_cost_center,
				SUM(ji.debit) AS total_debit,
				SUM(ji.credit) AS total_credit
			FROM journal_items ji
			JOIN journals j ON j.batch_number = ji.batch_number
			WHERE j.code_depo = ?
			AND j.year = ?
			AND j.period = ?
			AND j.status = 'unposted'
			GROUP BY ji.code_coa, ji.code_cost_center
		";
		return $this->db->query($sql, [$code_depo, $year, $period])->result();
	}
}
