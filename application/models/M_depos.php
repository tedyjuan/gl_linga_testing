<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_depos extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}
	public function get_paginated_depos($limit, $start, $search, $order_by, $order_dir)
	{
		$this->db->like('name', $search);
		$this->db->or_like('code_depo', $search);
		$this->db->limit($limit, $start);
		$this->db->order_by($order_by, $order_dir);
		$query = $this->db->get('depos');
		return $query->result();
	}
	// Fungsi untuk menghitung total data
	public function count_all_depos()
	{
		return $this->db->count_all('depos');
	}
	// Fungsi untuk menghitung jumlah data yang difilter berdasarkan pencarian
	public function count_filtered_depos($search)
	{
		$this->db->like('name', $search);
		$this->db->or_like('code_depo', $search);
		$query = $this->db->get('depos');
		return $query->num_rows();
	}
	public function get_depos_by_uuid($uuid)
	{
		$this->db->select('depos.*, companies.name as nm_company');
		$this->db->from('depos');
		$this->db->join('companies', 'depos.code_company = companies.code_company', 'left');
		$this->db->where('depos.uuid', $uuid);
		$query = $this->db->get();
		return $query->row(); 
	}
	
}
