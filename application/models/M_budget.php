<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_budget extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->username = $this->session->userdata('sess_username');
	}
	public function get_paginated_budget($limit, $start, $search, $order_by, $order_dir)
	{
		$this->db->select('
			a.*, 
			b.name AS company_name,
			c.name AS department_name
			');
		$this->db->from('budgeting_headers as a');
		$this->db->join('companies as b', 'b.code_company = a.code_company', 'left');
		$this->db->join('departments as c', 'c.code_department = a.code_department', 'left');
		// pencarian
		if (!empty($search)) {
			$this->db->group_start()
				->like('a.code_budgeting', $search)
				->or_like('a.code_company', $search)
				->group_end();
		}
		// limit & order
		$this->db->limit($limit, $start);
		$this->db->order_by($order_by, $order_dir);
		$query = $this->db->get();
		return $query->result();
	}

	// Fungsi untuk menghitung total data
	public function count_all_budget()
	{
		return $this->db->count_all('budgeting_headers');
	}
	// Fungsi untuk menghitung jumlah data yang difilter berdasarkan pencarian
	public function count_filtered_budget($search)
	{
		$this->db->like('code_budgeting', $search);
		$this->db->or_like('code_company', $search); 
		$query = $this->db->get('budgeting_headers');
		return $query->num_rows();
	}
	public function get_where_budget($param)
	{
		$this->db->select('
			a.*, 
			b.name AS company_name,
			c.name AS department_name,
			c.alias AS department_alias,
			');
		$this->db->from('budgeting_headers as a');
		$this->db->join('companies as b', 'b.code_company = a.code_company', 'left');
		$this->db->join('departments as c', 'c.code_department = a.code_department', 'left');
		$this->db->where($param);
		return $this->db->get();
	}
	public function get_coa_expense($cari, $code_company)
	{
		$sql = "SELECT 
			a.account_number,
			a.name
            FROM chart_of_accounts a 
            WHERE a.account_type = 'expense' 
            AND a.code_company = ? 
            AND (a.account_number LIKE ? OR a.name LIKE ?)";
		$param = [$code_company, "%$cari%", "%$cari%"];
		$query = $this->db->query($sql, $param);
		return $query->result_array();
	}
	
	public function getcode_budgeting($code_department, $alias, $code_company)
	{
		$year = date("Y");
		$this->db->select_max('counter_budgeting', 'max_serial');
		$this->db->from('budgeting_headers');
		$this->db->where('code_company', $code_company);
		$this->db->where('code_department', $code_department);
		$this->db->where('years', $year);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $suffix_id) {
				$tmp = (int)$suffix_id->max_serial + 1;
				$auto_num = sprintf("%04d", $tmp);
			}
		} else {
			$auto_num = "0001";
		}
		return "BGT/{$year}/{$alias}-{$auto_num}";
	}
	public function get_project($code, $code_company)
	{
		$sql = "SELECT 
			a.code_budgeting,
			a.`project_item`,
			a.`project_name`,
			a.`goal_project`,
			a.`project_desc`,
			a.`budget_proposal`,
			a.`filename`
			FROM `budgeting_projects` a
			INNER JOIN `budgeting_headers` b ON b.`code_budgeting` = a.`code_budgeting`
			WHERE a.`code_budgeting` = ?
			AND b.`code_company` = ?
			ORDER BY a.`project_item`";
		$param = [$code, $code_company];
		$query = $this->db->query($sql, $param);
		return $query->result_array();
	}
	public function get_project_items($code, $item, $type, $code_company)
	{
		$sql = "SELECT
				a.`itemnumber`,
				a.`desc`,
				a.`account_number`,
				a.`amount`,
				c.`name`
				FROM `budgeting_project_items` a
				INNER JOIN `budgeting_headers` b ON b.`code_budgeting` = a.`code_budgeting`
				LEFT JOIN `chart_of_accounts` c ON c.`account_number` = a.`account_number`
				WHERE a.`code_budgeting` = ?
				AND a.`project_item` = ?
				AND a.`type_goal` = ?
				AND b.`code_company` = ?
				ORDER BY a.`project_item`";
		$param = [$code, $item, $type, $code_company];
		$query = $this->db->query($sql, $param);
		return $query->result_array();
	}
	public function get_detail_summary($code_budgeting, $code_company)
	{
		$sql = "SELECT 
					a.`opening_balance`,
					SUM(CASE WHEN c.`type_goal` = 'OPEX' THEN COALESCE(c.`amount`, 0) ELSE 0 END) AS opex,
					SUM(CASE WHEN c.`type_goal` = 'CAPEX' THEN COALESCE(c.`amount`, 0) ELSE 0 END) AS capex,
					a.`opening_balance` - (
						SUM(CASE WHEN c.`type_goal` = 'OPEX' THEN COALESCE(c.`amount`, 0) ELSE 0 END) + 
						SUM(CASE WHEN c.`type_goal` = 'CAPEX' THEN COALESCE(c.`amount`, 0) ELSE 0 END)
					) AS difference
				FROM `budgeting_headers` a
				INNER JOIN `budgeting_projects` b ON b.`code_budgeting` = a.`code_budgeting` AND a.`code_company` = b.`code_company`
				INNER JOIN `budgeting_project_items` c ON c.`code_budgeting` = c.`code_budgeting`
					AND a.`code_company` = b.`code_company`
					AND c.`project_item` = b.`project_item`
				WHERE a.`code_budgeting` = ?
				AND a.`code_company` = ?
				GROUP BY a.`opening_balance`
				ORDER BY a.`opening_balance`;
				";
		$param = [$code_budgeting, $code_company];
		$query = $this->db->query($sql, $param);
		return $query->row_array();
	}
	// ================ verify budgeting ==============
	public function get_paginated_budget_verify($limit, $start, $search, $order_by, $order_dir)
	{
		$this->db->select('
			a.*,
			b.`uuid` AS uuid_header,
			c.name AS company_name');
		$this->db->from('budgeting_verify as a');
		$this->db->join('budgeting_headers as b', 'b.code_budgeting = a.code_budgeting and b.code_company = a.code_company', 'inner');
		$this->db->join('companies as c', 'c.code_company = a.code_company', 'inner');
		// pencarian
		if (!empty($search)) {
			$this->db->group_start()
				->like('a.code_budgeting', $search)
				->or_like('a.code_company', $search)
				->group_end();
		}
		$this->db->where('a.user_created', $this->username);
		// limit & order
		$this->db->limit($limit, $start);
		$this->db->order_by($order_by, $order_dir);
		$query = $this->db->get();
		return $query->result();
	}

	// Fungsi untuk menghitung total data
	public function count_all_budget_verify()
	{
		return $this->db->count_all('budgeting_verify');
	}
	// Fungsi untuk menghitung jumlah data yang difilter berdasarkan pencarian
	public function count_filtered_budget_verify($search)
	{
		$this->db->like('code_budgeting', $search);
		$this->db->or_like('code_company', $search);
		$query = $this->db->get('budgeting_verify');
		return $query->num_rows();
	}
}
