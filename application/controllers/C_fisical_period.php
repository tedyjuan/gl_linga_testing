<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_fisical_period extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model('M_fisical_period');
		$this->load->model('M_global');
		$this->company = $this->session->userdata('sess_company');
	}
	function index()
	{
		$data['judul']      = 'Fiscal Period';
		$data['load_grid']  = 'C_fisical_period';
		$data['load_add']   = 'C_fisical_period/add';
		$data['url_delete'] = 'C_fisical_period/delete';
		$param['code_company'] = $this->session->userdata('sess_company');
		$data['depos'] = $this->M_global->getWhere('depos', $param)->result();
		$this->load->view("v_fisical_period/grid_fisical_period", $data);
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
		$columns        = ['year', 'period', 'status', 'action'];
		$order_by       = $columns[$order_col] ?? 'name';
		$data           = $this->M_fisical_period->get_paginated_fisical_period($length, $start, $search, $order_by, $dir);
		$url_edit   = 'C_fisical_period/editform/';
		$result = [];
		foreach ($data as  $row) {
			$aksi = '<button type="button"  onclick="editform(\'' . $url_edit . '\', \'' . $row->uuid . '\')" class="btn btn-soft-primary">Edit</button>';
			$badge_status = '';
			if ($row->status == 'open') {
				$badge_status = '<span class="badge bg-success">Open</span>';
			} elseif ($row->status == 'closed') {
				$badge_status = '<span class="badge bg-danger">Closed</span>';
			} 
			$input = $row->year . '-' . str_pad($row->period, 2, '0', STR_PAD_LEFT);
			$timestamp = strtotime($input);
			$format_Month_Y = date('F Y', $timestamp);
			$result[] = [
				$row->code_depo . ' - ' . $row->depo_name,
				$format_Month_Y,
				$badge_status,
				$aksi,
			];
		}
		echo json_encode([
			"draw" => intval($this->input->post('draw')) ?? 1,
			"recordsTotal" => 12,
			"recordsFiltered" => 12,
			"data" => $result
		]);
	}
	function add()
	{
		$data['judul']     = "Add Template Fiscal Period";
		$data['load_back'] = 'C_fisical_period/add';
		$data['load_grid'] = 'C_fisical_period';
		$param['code_company'] = $this->session->userdata('sess_company');
		$data['depos'] = $this->M_global->getWhere('depos', $param)->result();
		$this->load->view("v_fisical_period/add_fisical_period", $data);
	}
	public function simpandata()
	{
		// Validasi input
		$this->form_validation->set_rules('year', 'year', 'required');
		$this->form_validation->set_rules('branch', 'branch', 'required');
		if ($this->form_validation->run() == FALSE) {
			// Jika validasi gagal
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => validation_errors(),
			];
			echo json_encode($jsonmsg);
			return;
		}
		$year  = $this->input->post('year');
		$branch = $this->input->post('branch');
		$param_kode =[
			'code_company' => $this->company,
			'code_depo'    => $branch,
			'year'         => $year,
		];
		$exisName = $this->M_global->getWhere('fiscal_periods', $param_kode)->num_rows();
		if ($exisName != 0) {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Template Fiscal Period is already exist for the selected branch and year',
			];
			echo json_encode($jsonmsg);
			exit;
		} 
		$fiscalBatch = [];
		for($i= 1; $i <=12; $i++){
			// Data untuk insert ke database
			$fiscalBatch[] = [
				'uuid'         => $this->uuid->v4(),
				'code_company' => $this->company,
				'code_depo'    => $branch,
				'year'         => $year,
				'period'       => $i,
				'start_date'   => date('Y-m-d', strtotime($year . '-' . str_pad($i, 2, '0', STR_PAD_LEFT) . '-01')),
				'end_date'     => date('Y-m-t', strtotime($year . '-' . str_pad($i, 2, '0', STR_PAD_LEFT) . '-01')),
				'status'       => 'closed',
				'created_at'   => date('Y-m-d H:i:s'),
				'updated_at'   => date('Y-m-d H:i:s')
			];
		}
		
		// Melakukan insert data
		$this->db->insert_batch('fiscal_periods', $fiscalBatch);
		if ($this->db->affected_rows() > 0) {
			$jsonmsg = [
				'hasil' => 'true',
				'pesan' => 'Data successfully saved',
			];
		} else {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Failed to save data',
			];
		}
		echo json_encode($jsonmsg);
	}
	public function editform($uuid)
	{
		$cekdata = $this->M_fisical_period->get_where_fisical_period(['a.uuid' => $uuid])->row();
		if ($cekdata != null) {
			$data['judul']        = "Edit Fiscal Period";
			$data['load_grid']    = 'C_fisical_period';
			$data['load_refresh']    = "C_fisical_period/editform/" . $uuid;
			$data['uuid']         = $uuid;
			$data['data']         = $cekdata;
			$this->load->view("v_fisical_period/edit_fisical_period", $data);
		} else {
			$this->load->view('error');
		}
	}
	public function update()
	{
		$uuid = $this->input->post('uuid');
		$status = $this->input->post('status');
		$data =  $this->M_fisical_period->get_where_fisical_period(['a.uuid' => $uuid])->row();
		if ($data != null) {
			$dataupdate = [
				'status'     => $status,
				'updated_at' => date('Y-m-d H:i:s')
			];
			$update = $this->M_global->update($dataupdate, 'fiscal_periods', ['uuid' => $uuid]);
			if ($update == true) {
				$jsonmsg = [
					'hasil' => 'true',
					'pesan' => 'Data successfully updated',
				];
				echo json_encode($jsonmsg);
			} else {
				$jsonmsg = [
					'hasil' => 'false',
					'pesan' => 'Failed to update data',
				];
				echo json_encode($jsonmsg);
			}
		} else {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'UUID Fiscal Period not found',
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
		$param_kode = ['a.uuid' => $uuid];
		$divisi = $this->M_fisical_period->get_where_fisical_period($param_kode)->row();
		// Jika data tidak ditemukan
		if (!$divisi) {
			$this->db->trans_rollback();
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Data tidak ditemukan'
			]);
			return;
		}
		$cek_cc = $this->M_global->getWhere('cost_centers', ['code_fisical_period' => $divisi->code_fisical_period])->num_rows();
		if ($cek_cc != 0) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Tidak bisa Menghapus Data, karena sedang digunakan di cost centers.',
			]);
			return;
		}
		$this->db->trans_begin();
		try {
			// Ambil data divisi berdasarkan UUID
			
			// Lakukan penghapusan data di tabel divisions
			$this->db->where('uuid', $uuid)->delete('divisions');
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

	
}
