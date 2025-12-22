<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_company extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}
	public function get_paginated_companies($limit, $start, $search, $order_by, $order_dir)
	{
		$this->db->like('name', $search); 
		$this->db->or_like('code_company', $search); 
		$this->db->limit($limit, $start); 
		$this->db->order_by($order_by, $order_dir); // Sorting berdasarkan kolom dan arah

		$query = $this->db->get('companies');
		return $query->result(); 
	}
	// Fungsi untuk menghitung total data
	public function count_all_companies()
	{
		return $this->db->count_all('companies');
	}
	// Fungsi untuk menghitung jumlah data yang difilter berdasarkan pencarian
	public function count_filtered_companies($search)
	{
		$this->db->like('name', $search);
		$this->db->or_like('code_company', $search); 
		$query = $this->db->get('companies');
		return $query->num_rows();
	}
	public function get_company_by_uuid($uuid)
	{
		$this->db->select('name, code_company');  // Menentukan kolom yang diambil
		$this->db->where('uuid', $uuid);         // Menentukan kondisi berdasarkan UUID
		$query = $this->db->get('companies');    // Melakukan query pada tabel 'companies'

		if ($query->num_rows() > 0) {
			return $query->row();  // Mengembalikan satu baris data jika ditemukan
		} else {
			return null;  // Mengembalikan null jika data tidak ditemukan
		}
	}
}
