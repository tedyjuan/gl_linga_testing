<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_chart_of_account extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}
	public function get_paginated_chart_of_account($limit, $start, $search, $order_by, $order_dir)
	{
		$this->db->select('a.*, b.name AS company_name');
		$this->db->from('chart_of_accounts as a');
		$this->db->join('companies as b', 'b.code_company = a.code_company', 'left');
		// pencarian
		if (!empty($search)) {
			$this->db->group_start()
				->like('a.name', $search)
				->or_like('a.account_number', $search)
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
	public function count_all_chart_of_account()
	{
		return $this->db->count_all('chart_of_accounts');
	}
	// Fungsi untuk menghitung jumlah data yang difilter berdasarkan pencarian
	public function count_filtered_chart_of_account($search)
	{
		$this->db->like('name', $search);
		$this->db->or_like('account_number', $search); 
		$this->db->or_like('name', $search); 
		$this->db->or_like('code_company', $search); 
		$this->db->or_like('account_type', $search); 
		$query = $this->db->get('chart_of_accounts');
		return $query->num_rows();
	}
	public function get_where_chart_of_account($param)
	{
		$this->db->select('a.*, b.name AS nm_company');
		$this->db->from('chart_of_accounts as a');
		$this->db->join('companies as b', 'b.code_company = a.code_company', 'left');
		$this->db->where($param);
		return $this->db->get();
	}
	public function get_tbag1Bycompany($code_company, $akun_type = null)
	{
		$this->db->select('account_type, code_trialbalance1, description, code_company');
		$this->db->from('trial_balance_account_group_1');
		$this->db->where('code_company', $code_company);
		if($akun_type != null){
			// hanya menampilkan ke tbg1 by akun spesifix
			$this->db->where('account_type', $akun_type);
			$this->db->group_by('account_type, code_trialbalance1, description, code_company');
		}
		$this->db->order_by('account_type', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_tbag2Bycompany($code_company, $tbag1)
	{
		$this->db->select('code_trialbalance2, description');
		$this->db->from('trial_balance_account_group_2');
		$this->db->where('code_company', $code_company);
		$this->db->where('code_trialbalance1', $tbag1);
		$this->db->order_by('code_trialbalance2', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_tbag3Bycompany($code_company, $tbag2)
	{
		$this->db->select('code_trialbalance3, description');
		$this->db->from('trial_balance_account_group_3');
		$this->db->where('code_company', $code_company);
		$this->db->where('code_trialbalance2', $tbag2);
		$this->db->order_by('code_trialbalance3', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_coa($param)
	{
		// Menggunakan query builder untuk membangun query
		$this->db->select('
			a.uuid,
			a.account_number,
			a.code_company,
			a.name AS name_coa,
			a.account_method,
			a.account_type,
			a.cost_center_type,
			a.code_header,
			a.code_ledger,
			a.account_group,
			a.code_trialbalance1,
			a.code_trialbalance2,
			a.code_trialbalance3,
			a.description AS des,
			b.name AS name_company,
			c.description AS tbag1,
			d.description AS tbag2,
			e.description AS tbag3
		');
		$this->db->from('chart_of_accounts a');
		// Menggunakan join untuk menggabungkan tabel
		$this->db->join('companies b', 'b.code_company = a.code_company', 'left');
		$this->db->join('trial_balance_account_group_1 c', 'c.code_trialbalance1 = a.code_trialbalance1 AND c.code_company = a.code_company', 'left');
		$this->db->join('trial_balance_account_group_2 d', 'd.code_trialbalance2 = a.code_trialbalance2 AND d.code_company = a.code_company', 'left');
		$this->db->join('trial_balance_account_group_3 e', 'e.code_trialbalance3 = a.code_trialbalance3 AND e.code_company = a.code_company', 'left');

		// Menggunakan query binding untuk menggantikan penyisipan langsung variabel
		$this->db->where($param);

		// Menjalankan query dan mengembalikan hasilnya
		return $this->db->get();
	}
	public function get_depo($code_company)
	{
		$this->db->select('
			a.code_company,
			a.name
		');
		$this->db->from('depos a');
		$this->db->where('a.code_company', $code_company);
		return $this->db->get();
	}
	public function get_paginated_depos($limit, $start, $search = null, $order_by, $order_dir, $code_company)
	{
		$this->db->select('a.code_depo, a.name');
		$this->db->from('depos a');
		$this->db->join('cost_centers b', 'b.code_depo = a.code_depo AND b.code_company = a.code_company', 'inner');
		$this->db->where('a.code_company', $code_company);
		if($search != null){
			$this->db->like('a.name', $search);
			$this->db->or_like('a.code_depo', $search);
		}
		$this->db->limit($limit, $start);
		$this->db->order_by($order_by, $order_dir);
		$this->db->group_by('a.code_depo');
		$query = $this->db->get();
		return $query->result();
	}

	// Fungsi untuk menghitung total data
	public function count_all_depos($code_company)
	{
		$this->db->select('a.code_depo');
		$this->db->from('depos a');
		$this->db->join('cost_centers b', 'b.code_depo = a.code_depo AND b.code_company = a.code_company', 'inner');
		$this->db->where('a.code_company', $code_company);
		$this->db->group_by('a.code_depo');
		return $this->db->count_all_results();
	}

	public function count_filtered_depos($search, $code_company)
	{
		$this->db->select('a.code_depo');
		$this->db->from('depos a');
		$this->db->join('cost_centers b', 'b.code_depo = a.code_depo AND b.code_company = a.code_company', 'inner');
		$this->db->like('a.name', $search);
		$this->db->or_like('a.code_depo', $search);
		$this->db->where('a.code_company', $code_company);
		$this->db->group_by('a.code_depo');
		$query = $this->db->get();
		return $query->num_rows();
	}
	 public function min_max_cc($uuid,  $type){
		$this->db->select('b.code_cc AS kode_cc, c.group_team');
		$this->db->from('chart_of_accounts a');
		$this->db->join('account_centers b', 'b.code_coa = a.account_number AND b.code_company = a.code_company');
		$this->db->join('cost_centers c', 'c.code_cost_center = b.code_cc AND c.code_company = a.code_company');
		$this->db->where('a.uuid', $uuid);
		if ($type == 'min') {
			$this->db->order_by('b.code_cc', 'ASC');
		} else {
			$this->db->order_by('b.code_cc', 'DESC');
		}
		$this->db->limit(1);
		return $this->db->get()->row();
	 }

	public function hapus_cc($uuid)
	{
		// Query DELETE dengan INNER JOIN dan query binding
		$sql = "
		DELETE b
		FROM account_centers b
		INNER JOIN chart_of_accounts a ON b.code_coa = a.account_number
										AND a.code_company = b.code_company
		WHERE a.uuid = ?
		";
		// Eksekusi query dengan binding parameter
		$this->db->query($sql, array($uuid));
	}
	public function get_depo_details($uuid, $code_company)
	{

		// Query Builder
		$this->db->select('c.code_depo, d.name');
		$this->db->from('chart_of_accounts a');
		$this->db->join('account_centers b', 'b.code_company = a.code_company AND b.code_coa = a.account_number');
		$this->db->join('cost_centers c', 'c.code_company = a.code_company AND c.code_cost_center = b.code_cc');
		$this->db->join('depos d', 'd.code_company = a.code_company AND d.code_depo = c.code_depo');
		$this->db->where('a.uuid', $uuid);
		$this->db->where('a.code_company', $code_company);
		$this->db->limit(1);

		// Eksekusi query
		$query = $this->db->get();
		return $query->row(); 
	}

	public function get_paginated_cost_center($limit, $start, $search, $order_by, $order_dir, $code_company)
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
		$this->db->where('code_company', $code_company);
		// limit & order
		$this->db->limit($limit, $start);
		$this->db->order_by($order_by, $order_dir);

		$query = $this->db->get();
		return $query->result();
	}
	public function count_all_cost_center($code_company)
	{
		$this->db->select('a.*');
		$this->db->from('cost_centers a');
		$this->db->where('a.code_company', $code_company);
		return $this->db->count_all_results();
	}
	public function count_filtered_cost_center($search = null, $code_company)
	{
		$this->db->select('a.group_team, a.code_cost_center');
		$this->db->from('cost_centers a');
		$this->db->where('a.code_company', $code_company);
		if($search != null){
			$this->db->like('a.group_team', $search);
			$this->db->or_like('a.code_cost_center', $search);
		}
		$query = $this->db->get();
		return $query->num_rows();
	}
}
