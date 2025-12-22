<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_global extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model('M_global');
	}
	public function getDepoByCompany($companyCode)
	{
		$param = ['code_company' => $companyCode];
		// Mengambil data dari model
		$depoList = $this->M_global->getWhere('depos', $param)->result();
		// Mengembalikan data dalam format JSON
		echo json_encode($depoList);
	}

	public function getDepartmentByCompany($companyCode)
	{
		$param = ['code_company' => $companyCode];
		$departmentList = $this->M_global->getWhere('departments', $param)->result();
		// Mengembalikan data dalam format JSON
		echo json_encode($departmentList);
	}

	public function getDivisiByCompany($companyCode)
	{
		$param = ['code_company' => $companyCode];
		$divisiList = $this->M_global->getWhere('divisions', $param)->result();
		// Mengembalikan data dalam format JSON
		echo json_encode($divisiList);
	}

	public function getSegmentByCompany($companyCode)
	{
		$param = ['code_company' => $companyCode];
		$segmentList = $this->M_global->getWhere('segments', $param)->result();
		// Mengembalikan data dalam format JSON
		echo json_encode($segmentList);
	}
	public function getTgb1ByCompany($companyCode)
	{
		$param = ['code_company' => $companyCode];
		$segmentList = $this->M_global->getWhere('trial_balance_account_group_1', $param)->result();
		// Mengembalikan data dalam format JSON
		echo json_encode($segmentList);
	}
	public function getTgb2()
	{
		$param = [
			'code_company' => $this->input->post('companyCode'),
			'code_trialbalance1' => $this->input->post('kode_tbg1'),
		];
		$segmentList = $this->M_global->getWhere('trial_balance_account_group_2', $param)->result();
		// Mengembalikan data dalam format JSON
		echo json_encode($segmentList);
	}
	public function getCostCenterByDepo()
	{
		$param = [
			'code_company' => $this->input->post('company'),
			'code_depo' => $this->input->post('branch'),
		];
		$segmentList = $this->M_global->getWhere('cost_centers', $param)->result();
		echo json_encode($segmentList);
	}
	public function getAccountCenter()
	{
		$company     = $this->input->post('company');
		$cost_center = $this->input->post('cost_center');
		$this->db->select('
			a.code_coa,
			b.name,
			a.code_company
		');
		$this->db->from('account_centers a');
		$this->db->join(
			'chart_of_accounts b',
			'b.code_company = a.code_company AND b.account_number = a.code_coa',
			'inner'
		);
		$this->db->where('a.code_cc', $cost_center);
		$this->db->where('a.code_company', $company);

		$query = $this->db->get()->result();
		echo json_encode($query);
	}
}
