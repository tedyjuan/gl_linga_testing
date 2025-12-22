<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_audit_list extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}
	public function get_paginated_audit_list($limit, $start, $search, $order_by, $order_dir)
	{
		$this->db->select('a.*');
		$this->db->from('audit_lists as a');
		if (!empty($search)) {
			$this->db->group_start()
				->like('a.batch_number', $search)
				->or_like('a.voucher_number', $search)
				->or_like('a.action', $search)
				->or_like('a.user_create', $search)
				->or_like('a.created_at', $search)
				->group_end();
		}

		// limit & order
		$this->db->limit($limit, $start);
		$this->db->order_by($order_by, $order_dir);

		$query = $this->db->get();
		return $query->result();
	}

	// Fungsi untuk menghitung total data
	public function count_all_audit_list()
	{
		return $this->db->count_all('audit_lists');
	}
	// Fungsi untuk menghitung jumlah data yang difilter berdasarkan pencarian
	public function count_filtered_audit_list($search)
	{
		$this->db->like('batch_number', $search);
		$this->db->or_like('voucher_number', $search);
		$this->db->or_like('action', $search);
		$this->db->or_like('user_create', $search);
		$this->db->or_like('created_at', $search);
		$query = $this->db->get('audit_lists');
		return $query->num_rows();
	}
	public function get_where_audit_list($param)
	{
		$this->db->select('a.*');
		$this->db->from('audit_lists as a');
		$this->db->where($param);
		return $this->db->get();
	}
}
