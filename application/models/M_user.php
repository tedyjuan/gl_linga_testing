<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_user extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}
	public function get_paginated_user($limit, $start, $search, $order_by, $order_dir)
	{
		$this->db->select('a.*,
						b.name AS company_name,
						c.name AS role_name
						');
		$this->db->from('users as a');
		$this->db->join('companies as b', 'b.code_company = a.code_company', 'left');
		$this->db->join('roles as c', 'c.id = a.role_id', 'left');
		$this->db->where('a.soft_delete', 1);
		$this->db->where('a.id <>', 1);
		// pencarian
		if (!empty($search)) {
			$this->db->group_start()
				->like('a.name', $search)
				->or_like('a.username', $search)
				->or_like('c.name', $search)
				->group_end();
		}

		// limit & order
		$this->db->limit($limit, $start);
		$this->db->order_by($order_by, $order_dir);

		$query = $this->db->get();
		return $query->result();
	}

	// Fungsi untuk menghitung total data
	public function count_all_user()
	{
		return $this->db->count_all('users');
	}
	// Fungsi untuk menghitung jumlah data yang difilter berdasarkan pencarian
	public function count_filtered_user($search)
	{
		$this->db->like('name', $search);
		$this->db->or_like('username', $search); 
		$this->db->where('id <>', 1);
		$query = $this->db->get('users');
		return $query->num_rows();
	}
	public function get_where_user($param)
	{
		$this->db->select('a.*,
						b.name AS company_name,
						c.name AS role_name
						');
		$this->db->from('users as a');
		$this->db->join('companies as b', 'b.code_company = a.code_company', 'left');
		$this->db->join('roles as c', 'c.id = a.role_id', 'left');
		$this->db->where('a.soft_delete', 1);
		$this->db->where('a.id <>', 1);
		$this->db->where($param);
		return $this->db->get();
	}
}
