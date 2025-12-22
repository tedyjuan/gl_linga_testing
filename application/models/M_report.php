<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_report extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}
	public function get_account_centers($cari)
	{
		$sql = "SELECT 
				a.`account_number`,
				a.`name`
				FROM chart_of_accounts a 
				WHERE a.code_company = ?
				AND a.`code_header` <> ''
				AND (a.account_number LIKE ? OR a.name LIKE ?)
				LIMIT 7";
		$param = [ $this->session->userdata('sess_company'), "%$cari%", "%$cari%",];
		return $this->db->query($sql, $param)->result();
	}
	public function get_account_to($strat_account)
	{
		$sql = "SELECT 
                a.`account_number`,
                a.`name`
            FROM chart_of_accounts a 
            WHERE a.code_company = ?
              AND a.`code_header` <> ''
              AND a.`account_number` >= ?";

		$param = [
			$this->session->userdata('sess_company'),
			$strat_account
		];

		return $this->db->query($sql, $param)->result();
	}

}
