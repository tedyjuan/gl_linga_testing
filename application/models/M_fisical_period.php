<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_fisical_period extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}
	public function get_paginated_fisical_period($limit, $start, $search, $order_by, $order_dir)
	{
		$year = $this->input->post('tahun');
		$branch = $this->input->post('post_branch');
		$this->db->select('a.*, b.name as depo_name');
		$this->db->from('fiscal_periods as a');
		$this->db->join('depos b', 'b.code_depo = a.code_depo AND b.code_company = a.code_company', 'inner');
		if($year != ''){
			$this->db->where('a.year', $year);
		}else{
			$this->db->where('a.year', date('Y'));
		}
		if($branch != ''){
			$this->db->where('a.code_depo', $branch);
		}
		if (!empty($search)) {
			$this->db->group_start()
				->like('a.period', $search)
				->or_like('a.start_date', $search)
				->or_like('a.end_date', $search)
				->or_like('a.status', $search)
				->group_end();
		}

		// limit & order
		$this->db->limit($limit, $start);
		$this->db->order_by($order_by, $order_dir);

		$query = $this->db->get();
		return $query->result();
	}

	
	public function get_where_fisical_period($param)
	{
		$this->db->select('a.*, b.name as depo_name');
		$this->db->from('fiscal_periods as a');
		$this->db->join('depos b', 'b.code_depo = a.code_depo AND b.code_company = a.code_company', 'inner');
		$this->db->where($param);
		return $this->db->get();
	}
}
