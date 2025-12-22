<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_petty_cash extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}
	public function get_paginated_petty_cash($limit, $start, $search, $order_by, $order_dir)
	{
		$this->db->select('a.*');
		$this->db->from('petty_cash_headers as a');
		if (!empty($search)) {
			$this->db->group_start()
				->like('a.voucher_no', $search)
				->or_like('a.trans_date', $search)
				->or_like('a.proveniance', $search)
				->or_like('a.flow', $search)
				->group_end();
		}

		// limit & order
		$this->db->limit($limit, $start);
		$this->db->order_by($order_by, $order_dir);

		$query = $this->db->get();
		return $query->result();
	}

	// Fungsi untuk menghitung total data
	public function count_all_petty_cash()
	{
		return $this->db->count_all('petty_cash_headers');
	}
	// Fungsi untuk menghitung jumlah data yang difilter berdasarkan pencarian
	public function count_filtered_petty_cash($search)
	{
		$this->db->like('voucher_no', $search);
		$this->db->or_like('trans_date', $search); 
		$this->db->or_like('proveniance', $search); 
		$this->db->or_like('flow', $search); 
		$query = $this->db->get('petty_cash_headers');
		return $query->num_rows();
	}
	public function get_where_petty_cash($param)
	{
		$this->db->select('a.*');
		$this->db->from('petty_cash_headers as a');
		$this->db->where($param);
		return $this->db->get();
	}
	public function get_coa($cari)
	{
		$sql = "SELECT 
			a.account_number,
			a.name
            FROM chart_of_accounts a 
            WHERE (a.account_number LIKE ? OR a.name LIKE ?)";
		$param = ["%$cari%", "%$cari%"];
		$query = $this->db->query($sql, $param);
		return $query->result_array();
	}
	public function get_amount($voucher_no)
	{
		$this->db->select_sum('debit', 'debit_amount');
		$this->db->select_sum('credit', 'credit_amount');
		$this->db->select('(SUM(debit) - SUM(credit)) AS difference', false);
		$this->db->from('petty_cash_itemprices');
		$this->db->where('voucher_no', $voucher_no);
		$query = $this->db->get();
		return $query->row();
	}
	public function get_item_akun_bank($voucher_no)
	{
		$sql = "SELECT 
				a.`bank_name`,
				a.`account_no`,
				a.`trans_date`,
				a.`item_number`,
				b.`name`
				FROM `petty_cash_banks` a
				INNER JOIN `chart_of_accounts` b ON b.`account_number` = a.`account_no`
				WHERE a.`voucher_no` = ?";
		$query = $this->db->query($sql, array($voucher_no));
		return $query->result();
	}
	public function get_item_debitcredit($voucher_no)
	{
		$sql = "SELECT 
		a.`item_number`,
		a.`account_no`,
		a.`description`,
		a.`debit`,
		a.`credit`,
		b.`name`
		FROM `petty_cash_itemprices` a
		INNER JOIN `chart_of_accounts` b ON b.`account_number` = a.`account_no`
		WHERE a.`voucher_no` = ?";
		$query = $this->db->query($sql, array($voucher_no));
		return $query->result();
	}
	
}
