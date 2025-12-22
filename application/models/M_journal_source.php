<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_journal_source extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}
	public function get_paginated_journal_source($limit, $start, $search, $order_by, $order_dir)
	{
		$this->db->select('a.*, 
						b.name AS company_name,
						c.name AS depo_name,
						');
		$this->db->from('journal_sources as a');
		$this->db->join('companies as b', 'b.code_company = a.code_company', 'inner');
		$this->db->join('depos as c', 'c.code_depo = a.code_depo', 'inner');
		// pencarian
		if (!empty($search)) {
			$this->db->group_start()
				->or_like('a.code_journal_source', $search)
				->or_like('b.name', $search)
				->group_end();
		}

		// limit & order
		$this->db->limit($limit, $start);
		$this->db->order_by($order_by, $order_dir);

		$query = $this->db->get();
		return $query->result();
	}

	// Fungsi untuk menghitung total data
	public function count_all_journal_source()
	{
		return $this->db->count_all('journal_sources');
	}
	// Fungsi untuk menghitung jumlah data yang difilter berdasarkan pencarian
	public function count_filtered_journal_source($search)
	{
		$this->db->like('description', $search);
		$this->db->or_like('code_journal_source', $search); 
		$query = $this->db->get('journal_sources');
		return $query->num_rows();
	}
	public function get_where_journal_source($param)
	{
		$this->db->select('
						a.*,
						b.name AS company_name,
						c.name AS depo_name,
						');
		$this->db->from('journal_sources as a');
		$this->db->join('companies as b', 'b.code_company = a.code_company', 'inner');
		$this->db->join('depos as c', 'c.code_depo = a.code_depo', 'inner');
		$this->db->where($param);
		return $this->db->get();
	}
}
