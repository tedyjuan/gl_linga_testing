<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_cost_center extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}
	public function get_paginated_cost_center($limit, $start, $search, $order_by, $order_dir)
	{
		$this->db->select('*');
		$this->db->from('cost_centers');
		if (!empty($search)) {
			$this->db->group_start()
				->like('code_cost_center', $search)
				->or_like('group_team', $search)
				->or_like('manager', $search)
				->or_like('description', $search)
				->group_end();
		}

		// limit & order
		$this->db->limit($limit, $start);
		$this->db->order_by($order_by, $order_dir);

		$query = $this->db->get();
		return $query->result();
	}

	// Fungsi untuk menghitung total data
	public function count_all_cost_center()
	{
		return $this->db->count_all('cost_centers');
	}
	// Fungsi untuk menghitung jumlah data yang difilter berdasarkan pencarian
	public function count_filtered_cost_center($search)
	{
		$this->db->like('code_cost_center', $search);
		$this->db->or_like('group_team', $search); 
		$this->db->or_like('manager', $search); 
		$this->db->or_like('description', $search); 
		$query = $this->db->get('cost_centers');
		return $query->num_rows();
	}
	public function get_where_cost_center($param)
	{
		$this->db->select('a.*');
		$this->db->from('cost_centers as a');
		$this->db->where($param);
		return $this->db->get();
	}
	public function get_cc_And_groupteam($perusahaan, $depo, $department, $divisi, $segment)
	{
		$this->db->select('
		 b.code_area,
		 CONCAT(b.code_area, c.`code_department`, d.`code_divisi`, e.`code_segment`) AS code_cost_center,
		 CONCAT(b.alias, "/", c.alias, "/", d.alias, "/", e.alias) AS group_team
		');
		$this->db->from('companies a');
		$this->db->join('depos b', 'b.code_company = a.code_company', 'left');
		$this->db->join('departments c', 'c.code_company = a.code_company', 'left');
		$this->db->join('divisions d', 'd.code_company = a.code_company', 'left');
		$this->db->join('segments e', 'e.code_company = a.code_company', 'left');
		$this->db->where('a.code_company', $perusahaan);
		$this->db->where('b.code_depo', $depo);
		$this->db->where('c.code_department', $department);
		$this->db->where('d.code_divisi', $divisi);
		$this->db->where('e.code_segment', $segment);
		return $this->db->get()->row();
	}
	public function getCC_Start_End_ByCompany($start, $end, $code_company)
	{
		$this->db->select('a.code_cost_center, a.code_depo');
		$this->db->from('cost_centers a');
		$this->db->where('a.code_company', $code_company);
		$this->db->where('a.code_cost_center >=', $start);
		$this->db->where('a.code_cost_center <=', $end);
		$query = $this->db->get();

		// Menampilkan hasil query
		return  $query->result();
	}
}
