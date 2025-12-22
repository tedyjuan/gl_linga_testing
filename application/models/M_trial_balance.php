<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_trial_balance extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}
	//# global ===================================================================================
	// Fungsi untuk menghitung total data
	public function count_all_trial_balance($tabel)
	{
		return $this->db->count_all($tabel);
	}
	//# end global ===================================================================================


	public function get_paginated_trial_balance($limit, $start, $search, $order_by, $order_dir)
	{
		$this->db->select('
						a.code_company,
						a.code_trialbalance1,
						a.account_type,
						a.description,
						b.name');
		$this->db->from('trial_balance_account_group_1 as a');
		$this->db->join('companies as b', 'b.code_company = a.code_company', 'left');
		// pencarian
		if (!empty($search)) {
			$this->db->group_start()
				->like('a.code_trialbalance1', $search)
				->or_like('a.description', $search)
				->or_like('a.account_type', $search)
				->or_like('b.name', $search)
				->group_end();
		}

		// limit & order
		$this->db->limit($limit, $start);
		$this->db->order_by($order_by, $order_dir);

		$query = $this->db->get();
		return $query->result();
	}

	// @trial balance 1=======================================================
	public function count_filtered_trial_balance($search)
	{
		// Fungsi untuk menghitung jumlah data yang difilter berdasarkan pencarian
		$this->db->like('code_trialbalance1', $search);
		$this->db->or_like('description', $search); 
		$query = $this->db->get('trial_balance_account_group_1');
		return $query->num_rows();
	}
	public function get_where_trial_balance($param)
	{
		$this->db->select('
						a.code_company,
						a.code_trialbalance1,
						a.account_type,
						a.description,
						b.name');
		$this->db->from('trial_balance_account_group_1 as a');
		$this->db->join('companies as b', 'b.code_company = a.code_company', 'inner');
		$this->db->where($param);
		return $this->db->get();
	}
	// @END trial balance 1=======================================================

	//! Start trial balance 2=======================================================
	public function get_paginated_trial_balance2($limit, $start, $search, $order_by, $order_dir)
	{
		$this->db->select('
						a.code_company,
						a.code_trialbalance1,
						a.code_trialbalance2,
						a.description,
						b.name');
		$this->db->from('trial_balance_account_group_2 as a');
		$this->db->join('companies as b', 'b.code_company = a.code_company', 'inner');
		// pencarian
		if (!empty($search)) {
			$this->db->group_start()
				->like('a.code_trialbalance1', $search)
				->or_like('a.code_trialbalance2', $search)
				->or_like('a.description', $search)
				->or_like('b.name', $search)
				->group_end();
		}

		// limit & order
		$this->db->limit($limit, $start);
		$this->db->order_by($order_by, $order_dir);

		$query = $this->db->get();
		return $query->result();
	}

	// Fungsi untuk menghitung jumlah data yang difilter berdasarkan pencarian
	public function count_filtered_trial_balance2($search)
	{
		$this->db->like('code_trialbalance1', $search);
		$this->db->or_like('code_trialbalance2', $search);
		$this->db->or_like('description', $search);
		$query = $this->db->get('trial_balance_account_group_2');
		return $query->num_rows();
	}
	public function get_where_trial_balance2($param)
	{
		$this->db->select('
						a.code_company,
						a.code_trialbalance1,
						a.code_trialbalance2,
						a.description,
						b.name');
		$this->db->from('trial_balance_account_group_2 as a');
		$this->db->join('companies as b', 'b.code_company = a.code_company', 'inner');
		$this->db->where($param);
		return $this->db->get();
	}
	//! end trial balance 2=======================================================
	// Start trial balance 2=======================================================
	public function get_paginated_trial_balance3($limit, $start, $search, $order_by, $order_dir)
	{
		$this->db->select('
						a.uuid,
						a.code_company,
						a.code_trialbalance3,
						a.code_trialbalance2,
						a.description,
						b.name');
		$this->db->from('trial_balance_account_group_3 as a');
		$this->db->join('companies as b', 'b.code_company = a.code_company', 'inner');
		// pencarian
		if (!empty($search)) {
			$this->db->group_start()
				->like('a.code_trialbalance3', $search)
				->or_like('a.code_trialbalance2', $search)
				->or_like('a.description', $search)
				->or_like('b.name', $search)
				->group_end();
		}

		// limit & order
		$this->db->limit($limit, $start);
		$this->db->order_by($order_by, $order_dir);

		$query = $this->db->get();
		return $query->result();
	}

	// Fungsi untuk menghitung jumlah data yang difilter berdasarkan pencarian
	public function count_filtered_trial_balance3($search)
	{
		$this->db->like('code_trialbalance3', $search);
		$this->db->or_like('code_trialbalance2', $search);
		$this->db->or_like('description', $search);
		$query = $this->db->get('trial_balance_account_group_3');
		return $query->num_rows();
	}
	public function get_where_trial_balance3($param)
	{
		$this->db->select('
						a.uuid,
						a.code_company,
						a.code_trialbalance1,
						a.code_trialbalance2,
						a.code_trialbalance3,
						a.description,
						b.name');
		$this->db->from('trial_balance_account_group_3 as a');
		$this->db->join('companies as b', 'b.code_company = a.code_company', 'inner');
		$this->db->where($param);
		return $this->db->get();
	}
	//! end trial balance 2=======================================================
}
