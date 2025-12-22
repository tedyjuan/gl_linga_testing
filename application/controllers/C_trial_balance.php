<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_trial_balance extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model('M_global');
		$this->load->model('M_trial_balance');
	}
	function index()
	{
		$data['load_grid']  = 'C_trial_balance';
		$data['load_add']   = 'C_trial_balance/add';
		$data['url_delete'] = 'C_trial_balance/delete';
		$this->load->view("v_trial_balance/tbag_1/grid_tbag", $data);
	}
	public function griddata()
	{
		$start          = $this->input->post('start') ?? 0;
		$length         = $this->input->post('length') ?? 10;
		$search_input   = $this->input->post('search');
		$search         = isset($search_input['value']) ? $search_input['value'] : '';
		$order_input    = $this->input->post('order');
		$order_col      = isset($order_input[0]['column']) ? $order_input[0]['column'] : 0;
		$dir            = isset($order_input[0]['dir']) ? $order_input[0]['dir'] : 'asc';
		$columns        = ['code_company', 'name',  'code_trialbalance1', 'description', 'account_type'];
		$order_by       = $columns[$order_col] ?? 'name';
		$data           = $this->M_trial_balance->get_paginated_trial_balance($length, $start, $search, $order_by, $dir);
		$total_records  = $this->M_trial_balance->count_all_trial_balance("trial_balance_account_group_1");
		$total_filtered = $this->M_trial_balance->count_filtered_trial_balance($search);
		$result = [];
		foreach ($data as $row) {
			$result[] = [
				$row->code_company . ' - ' . $row->name,
				$row->code_trialbalance1,
				$row->description,
				$row->account_type,
			];
		}
		echo json_encode([
			"draw" => intval($this->input->post('draw')) ?? 1,
			"recordsTotal" => $total_records,
			"recordsFiltered" => $total_filtered,
			"data" => $result
		]);
	}
	public function tbag_2(){
		$this->load->view("v_trial_balance/tbag_2/grid_tbag");
	}
	public function griddata_tbag_2()
	{
		$start          = $this->input->post('start') ?? 0;
		$length         = $this->input->post('length') ?? 10;
		$search_input   = $this->input->post('search');
		$search         = isset($search_input['value']) ? $search_input['value'] : '';
		$order_input    = $this->input->post('order');
		$order_col      = isset($order_input[0]['column']) ? $order_input[0]['column'] : 0;
		$dir            = isset($order_input[0]['dir']) ? $order_input[0]['dir'] : 'asc';
		$columns        = ['code_company', 'name',  'code_trialbalance2', 'description', 'code_trialbalance1'];
		$order_by       = $columns[$order_col] ?? 'name';
		$data           = $this->M_trial_balance->get_paginated_trial_balance2($length, $start, $search, $order_by, $dir);
		$total_records  = $this->M_trial_balance->count_all_trial_balance("trial_balance_account_group_2");
		$total_filtered = $this->M_trial_balance->count_filtered_trial_balance2($search);
		$result = [];
		foreach ($data as $row) {
			$result[] = [
				$row->code_company . ' - ' . $row->name,
				$row->code_trialbalance2,
				$row->description,
				$row->code_trialbalance1,
			];
		}
		echo json_encode([
			"draw" => intval($this->input->post('draw')) ?? 1,
			"recordsTotal" => $total_records,
			"recordsFiltered" => $total_filtered,
			"data" => $result
		]);
	}
	 public function tbag_3(){
		$data['load_grid']  = 'C_trial_balance/tbag_3';
		$this->load->view("v_trial_balance/tbag_3/grid_tbag", $data);
	}
	public function griddata_tbag_3()
	{
		$start          = $this->input->post('start') ?? 0;
		$length         = $this->input->post('length') ?? 10;
		$search_input   = $this->input->post('search');
		$search         = isset($search_input['value']) ? $search_input['value'] : '';
		$order_input    = $this->input->post('order');
		$order_col      = isset($order_input[0]['column']) ? $order_input[0]['column'] : 0;
		$dir            = isset($order_input[0]['dir']) ? $order_input[0]['dir'] : 'asc';
		$columns        = ['code_company', 'name',  'code_trialbalance3', 'description', 'code_trialbalance2', 'aksi'];
		$order_by       = $columns[$order_col] ?? 'name';
		$data           = $this->M_trial_balance->get_paginated_trial_balance3($length, $start, $search, $order_by, $dir);
		$total_records  = $this->M_trial_balance->count_all_trial_balance("trial_balance_account_group_3");
		$total_filtered = $this->M_trial_balance->count_filtered_trial_balance3($search);
		$url_edit   = 'C_trial_balance/editform/';
		$url_delete = 'C_trial_balance/hapusdata/';
		$load_grid  = 'C_trial_balance/griddata';
		$result = [];
		foreach ($data as $row) {
			$aksi = '<div class="dropdown">
				<button type="button" class="btn btn-white btn-sm" id="aksi-dropdown-' . $row->code_trialbalance3 . '" data-bs-toggle="dropdown" aria-expanded="false">
					More <i class="bi-chevron-down ms-1"></i>
				</button>
				<div class="dropdown-menu dropdown-menu-sm dropdown-menu-end" aria-labelledby="aksi-dropdown-' . $row->code_trialbalance3 . '">
					<button type="button" class="dropdown-item" onclick="editforms(\'' . $row->uuid . '\')"   data-bs-toggle="modal" data-bs-target="#modaltbg3">
						<i class="bi bi-pen"></i> Edit
					</button>
					<div class="dropdown-divider"></div>
					<button class="dropdown-item text-danger" onclick="hapus(\'' . $row->uuid . '\', \'' . $url_delete . '\', \'' . $load_grid . '\')">
						<i class="bi bi-trash3"></i> Delete
					</button>
				</div>
			</div>';
			$result[] = [
				$row->code_company . ' - ' . $row->name,
				$row->code_trialbalance3,
				$row->description,
				$row->code_trialbalance2,
				$aksi,
			];
		}
		echo json_encode([
			"draw" => intval($this->input->post('draw')) ?? 1,
			"recordsTotal" => $total_records,
			"recordsFiltered" => $total_filtered,
			"data" => $result
		]);
	}

	public function simpandata()
	{
		// Validasi input
		$this->form_validation->set_rules('perusahaan', 'Perusahaan', 'required');
		$this->form_validation->set_rules('kode_tbg1', 'kode trial balance 1', 'required');
		$this->form_validation->set_rules('kode_tbg2', 'kode trial balance 2', 'required');
		$this->form_validation->set_rules('kode_tbg3', 'kode trial balance ', 'required');
		$this->form_validation->set_rules('des', 'Deskripsi', 'required');
		if ($this->form_validation->run() == FALSE) {
			// Jika validasi gagal
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => validation_errors(),
			];
			echo json_encode($jsonmsg);
			return;
		}
		// Ambil data dari request
		$perusahaan = $this->input->post('perusahaan');
		$kode_tbg1  = $this->input->post('kode_tbg1');
		$kode_tbg2  = $this->input->post('kode_tbg2');
		$kode_tbg3  = $this->input->post('kode_tbg3');
		$des        = $this->input->post('des');
		$param_kode = [
			'code_trialbalance3'  => $kode_tbg3,
			'code_company'  => $perusahaan,
		];
		$exisCode = $this->M_global->getWhere('trial_balance_account_group_3', $param_kode)->num_rows();
		if ($exisCode != null) {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Kode Trial Balance sudah digunakan',
			];
			echo json_encode($jsonmsg);
			exit;
		}
		
		// Data untuk insert ke database
		$datainsert = [
			'uuid'               => $this->uuid->v4(),
			'code_company'       => $perusahaan,
			'code_trialbalance3' => $kode_tbg3,
			'description'        => $des,
			'code_trialbalance1' => $kode_tbg1,
			'code_trialbalance2' => $kode_tbg2,
			'created_at'         => date('Y-m-d H:i:s'),
			'updated_at'         => date('Y-m-d H:i:s')
		];
		// Melakukan insert data
		$this->db->insert('trial_balance_account_group_3', $datainsert);
		if ($this->db->affected_rows() > 0) {
			$jsonmsg = [
				'hasil' => 'true',
				'pesan' => 'Data Berhasil Disimpan',
			];
		} else {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Gagal Menyimpan Data',
			];
		}
		echo json_encode($jsonmsg);
	}
	public function editform()
	{
		$uuid = $this->input->post('uuid');
		$data =  $this->M_trial_balance->get_where_trial_balance3(['a.uuid' => $uuid])->row();
		if ($data != null) {
			$code_company = $data->code_company;
			$allcompany = $this->M_global->getWhere('companies')->result();
			$tbag1_Bycompany = $this->M_global->getWhere('trial_balance_account_group_1', ['code_company' => $code_company])->result();
			$tbag2_Bycompany = $this->M_global->getWhere('trial_balance_account_group_2', ['code_company' => $code_company])->result();
			
			$jsonmsg = [
				'hasil' => 'true',
				'pesan' => 'sukses',
				'posdata' => $data,
				'allcompany' => $allcompany,
				'tbag1_Bycompany' => $tbag1_Bycompany,
				'tbag2_Bycompany' => $tbag2_Bycompany,
			];
			echo json_encode($jsonmsg);
		} else {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'data tidak ditemukan',
				'posdata' => []
			];
			echo json_encode($jsonmsg);
		}
	}
	public function hapusdata()
	{
		$uuid = $this->input->post('uuid');
		// Validasi input
		if (empty($uuid)) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'UUID tidak boleh kosong'
			]);
			return;
		}
		$data =  $this->M_trial_balance->get_where_trial_balance3(['a.uuid' => $uuid])->row();
		if ($data == null) {
			$this->db->trans_rollback();
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Data tidak ditemukan'
			]);
			return;
		}
		$this->db->trans_begin();
		try {
			// Ambil data divisi berdasarkan UUID

			// Lakukan penghapusan data di tabel divisions
			$this->db->where('uuid', $uuid)->delete('trial_balance_account_group_3');
			if ($this->db->affected_rows() <= 0) {
				$this->db->trans_rollback();
				echo json_encode([
					'hasil' => 'false',
					'pesan' => 'Data gagal dihapus atau tidak ditemukan'
				]);
				return;
			}
			// Pastikan semua operasi berhasil
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				echo json_encode([
					'hasil' => 'false',
					'pesan' => 'Terjadi kesalahan dalam transaksi, rollback dijalankan'
				]);
			} else {
				$this->db->trans_commit();
				echo json_encode([
					'hasil' => 'true',
					'pesan' => 'Data berhasil dihapus'
				]);
			}
		} catch (Exception $e) {
			// Jika ada error di proses apapun → rollback
			$this->db->trans_rollback();
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Terjadi error: ' . $e->getMessage()
			]);
		}
	}
	public function update()
	{
		
		// Ambil data dari request
		$uuid       = $this->input->post('uuid');
		$perusahaan = $this->input->post('perusahaan');
		$kode_tbg1  = $this->input->post('kode_tbg1');
		$kode_tbg2  = $this->input->post('kode_tbg2');
		$kode_tbg3  = $this->input->post('kode_tbg3');
		$des        = $this->input->post('des');
		$cek_data = $this->M_global->getWhere('trial_balance_account_group_3', ['uuid' => $uuid])->row();
		if($cek_data == null){
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Uuid Tidak ditemukan',
			];
			echo json_encode($jsonmsg);
			exit;
		}
		if ($cek_data->code_trialbalance3 !== $kode_tbg3) {
			$param = ['code_trialbalance3' => $kode_tbg3, 'code_company' => $perusahaan];
			if ($this->M_global->getWhere("trial_balance_account_group_3", $param)->num_rows() != 0) {
				echo json_encode([
					'hasil' => 'false',
					'pesan' => 'Kode Trial Balance sudah digunakan',
				]);
				return;
			}
		}
		// Data untuk insert ke database
		$this->db->trans_start();
		try {
			$dataupdate = [
				'code_company'       => $perusahaan,
				'code_trialbalance3' => $kode_tbg3,
				'description'        => $des,
				'code_trialbalance1' => $kode_tbg1,
				'code_trialbalance2' => $kode_tbg2,
				'updated_at'         => date('Y-m-d H:i:s')
			];
			// Melakukan insert data
			if (!$this->M_global->update($dataupdate, "trial_balance_account_group_3", ['uuid' => $uuid])) {
				$this->db->trans_rollback();
				echo json_encode([
					'hasil' => 'false',
					'pesan' => 'Gagal Menyimpan Data',
				]);
				return;
			}
			// Commit transaksi jika semua berhasil
			$this->db->trans_complete();
			echo json_encode([
				'hasil' => 'true',
				'pesan' => 'Data Berhasil Diupdate',
			]);
		} catch (Exception $e) {
			$this->db->trans_rollback();
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Gagal Menyimpan Data: ' . $e->getMessage(),
			]);
		}
	}
}
