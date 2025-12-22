<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_account_balance extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}
	
	public function get_coa($coa, $cc, $year, $branch)
	{
		$this->db->select('period, debit, credit');
		$this->db->from('posting_balances a');
		$this->db->where('a.year', $year);
		$this->db->where('a.code_depo', $branch);
		$this->db->where('a.code_cost_center', $cc);
		$this->db->where('a.code_coa', $coa);
		$this->db->order_by('a.period', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_opening_ending_balance($coa, $cc, $year, $branch)
	{
		$this->db->select('
        MIN(IFNULL(opening_balance, 0)) AS opening_balance,
        (
            MIN(IFNULL(opening_balance, 0)) 
            + SUM(IFNULL(debit, 0)) 
            - SUM(IFNULL(credit, 0))
        ) AS ending_balance
    	', false); // false = tidak di-escape

		$this->db->from('posting_balances');
		$this->db->where('year', $year);
		$this->db->where('code_depo', $branch);
		$this->db->where('code_cost_center', $cc);
		$this->db->where('code_coa', $coa);

		return $this->db->get()->row();
	}
}
