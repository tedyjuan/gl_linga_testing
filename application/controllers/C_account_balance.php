<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_account_balance extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model('M_account_balance');
		$this->load->model('M_global');
	}
	function index()
	{
		$data['judul']      = 'Account Balance Setup';
		$data['load_grid']  = 'C_account_balance';
		$data['load_add']   = 'C_account_balance/add';
		$data['url_delete'] = 'C_account_balance/delete';
		$this->load->view("v_account_balance/grid_account_balance", $data);
	}

	public function getCOA()
	{
		$coa         = $this->input->post('coa');
		$cc          = $this->input->post('cost_center');
		$year        = $this->input->post('year');
		$branch      = $this->input->post('branch');

		// Query detail 12 bulan
		$period_data = $this->M_account_balance->get_coa($coa, $cc, $year, $branch);

		// Jadikan array berdasarkan bulan 1–12
		$final_period = [];
		for ($i = 1; $i <= 12; $i++) {
			$final_period[$i] = [
				'period' => $i,
				'debit' => 0,
				'credit' => 0
			];
		}

		// Isi data asli ke posisi period terkait
		foreach ($period_data as $row) {
			$p = (int)$row->period;
			$final_period[$p]['debit']  = (float)$row->debit;
			$final_period[$p]['credit'] = (float)$row->credit;
		}

		// Query opening & ending balance
		$oe = $this->M_account_balance->get_opening_ending_balance($coa, $cc, $year, $branch);

		$response = [
			'opening_balance' => (float)$oe->opening_balance,
			'ending_balance'  => (float)$oe->ending_balance,
			'period'          => array_values($final_period) // untuk loop di JS
		];

		echo json_encode($response);
	}

	public function savedata()
	{
		// START TRANSACTION
		$this->db->trans_begin();

		try {

			$opening_balance = $this->input->post('opening_balance');
			$nilai_op        = (int)str_replace('.', '', $opening_balance);

			$company     = $this->input->post('company');
			$branch      = $this->input->post('branch');
			$year        = $this->input->post('year');
			$cost_center = $this->input->post('cost_center');
			$code_coa    = $this->input->post('code_coa');

			$param = [
				'code_company'     => $company,
				'code_depo'        => $branch,
				'year'             => $year,
				'code_cost_center' => $cost_center,
				'code_coa'         => $code_coa,
			];

			$exisPB = $this->M_global->getWhere('posting_balances', $param)->num_rows();

			// ================================================
			//  INSERT BARU
			// ================================================
			if ($exisPB == 0) {

				$new_data_pb = [];
				for ($i = 1; $i <= 12; $i++) {
					$new_data_pb[] = [
						'uuid'             => $this->uuid->v4(),
						'code_company'     => $company,
						'code_depo'        => $branch,
						'year'             => $year,
						'period'           => $i,
						'code_cost_center' => $cost_center,
						'code_coa'         => $code_coa,
						'opening_balance'  => $nilai_op,
						'debit'            => 0,
						'credit'           => 0,
					];
				}

				$this->db->insert_batch('posting_balances', $new_data_pb);
				$pesan = 'Data saved successfully.';
			}
			// ================================================
			//  UPDATE DATA YANG SUDAH ADA
			// ================================================
			else {

				for ($j = 1; $j <= 12; $j++) {
					$where = [
						'code_company'     => $company,
						'code_depo'        => $branch,
						'year'             => $year,
						'code_cost_center' => $cost_center,
						'code_coa'         => $code_coa,
						'period'           => $j
					];

					$updateData = [
						'opening_balance' => $nilai_op,
						'updated_at'      => date('Y-m-d H:i:s')
					];

					$this->db->update('posting_balances', $updateData, $where);
					$pesan = 'Data updated successfully.';
				}
			}

			// ================================================
			//  CEK TRANSAKSI
			// ================================================
			if ($this->db->trans_status() === FALSE) {
				// Jika ada error → rollback
				$this->db->trans_rollback();
				echo json_encode([
					'hasil' => 'false',
					'pesan' => 'An error occurred while saving the data.',
				]);
				return;
			}

			// Commit jika semua sukses
			$this->db->trans_commit();
			$oe = $this->M_account_balance->get_opening_ending_balance($code_coa, $cost_center, $year, $branch);
			echo json_encode([
				'hasil' => 'true',
				'pesan' => $pesan,
				'opening_balance' => (float)$oe->opening_balance,
				'ending_balance'  => (float)$oe->ending_balance,
			]);
			return;
		} catch (Exception $e) {
			// Tangani exception
			$this->db->trans_rollback();
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Error: ' . $e->getMessage(),
			]);
			return;
		}
	}
}
