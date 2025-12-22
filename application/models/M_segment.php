<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_segment extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}
	public function get_paginated_segment($limit, $start, $search, $order_by, $order_dir)
	{
		$this->db->select('segments.*, companies.name AS company_name');
		$this->db->from('segments');
		$this->db->join('companies', 'companies.code_company = segments.code_company', 'left');
		// pencarian
		if (!empty($search)) {
			$this->db->group_start()
				->like('segments.name', $search)
				->or_like('segments.code_segment', $search)
				->or_like('companies.name', $search)
				->group_end();
		}

		// limit & order
		$this->db->limit($limit, $start);
		$this->db->order_by($order_by, $order_dir);

		$query = $this->db->get();
		return $query->result();
	}

	// Fungsi untuk menghitung total data
	public function count_all_segment()
	{
		return $this->db->count_all('segments');
	}
	// Fungsi untuk menghitung jumlah data yang difilter berdasarkan pencarian
	public function count_filtered_segment($search)
	{
		$this->db->like('name', $search);
		$this->db->or_like('code_segment', $search); 
		$query = $this->db->get('segments');
		return $query->num_rows();
	}
	public function get_where_segment($param)
	{
		$this->db->select('a.*, b.name AS nm_company');
		$this->db->from('segments as a');
		$this->db->join('companies as b', 'b.code_company = a.code_company', 'left');
		$this->db->where($param);
		return $this->db->get();
	}
}
