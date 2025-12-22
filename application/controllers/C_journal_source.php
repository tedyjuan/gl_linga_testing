<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_journal_source extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model('M_journal_source');
		$this->load->model('M_global');
	}
	function index()
	{
		$data['judul']      = 'List Journal Source';
		$data['load_grid']  = 'C_journal_source';
		$data['load_add']   = 'C_journal_source/add';
		$data['url_delete'] = 'C_journal_source/delete';
		$this->load->view("v_journal_source/grid_journal_source", $data);
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
		$columns        = [
					'code_company',
					'company_name',
					'code_journal_source',
					'code_depo',
					'depo_name',
					'description',
					'action'];
		$order_by       = $columns[$order_col] ?? 'code_company';
		$data           = $this->M_journal_source->get_paginated_journal_source($length, $start, $search, $order_by, $dir);
		$total_records  = $this->M_journal_source->count_all_journal_source();
		$total_filtered = $this->M_journal_source->count_filtered_journal_source($search);
		$url_edit   = 'C_journal_source/editform';
		$url_delete = 'C_journal_source/hapusdata';
		$load_grid  = 'C_journal_source/griddata';
		$result = [];
		foreach ($data as $row) {
			$aksi = '<div class="dropdown">
				<button type="button" class="btn btn-white btn-sm" id="aksi-dropdown-' . $row->code_journal_source . '" data-bs-toggle="dropdown" aria-expanded="false">
					More <i class="bi-chevron-down ms-1"></i>
				</button>
				<div class="dropdown-menu dropdown-menu-sm dropdown-menu-end" aria-labelledby="aksi-dropdown-' . $row->code_journal_source . '">
					<button class="dropdown-item" onclick="editform(\'' . $url_edit . '\', \'' . $row->uuid . '\')">
						<i class="bi bi-pen"></i> Edit
					</button>
					<div class="dropdown-divider"></div>
					<button class="dropdown-item text-danger" onclick="hapus(\'' . $row->uuid . '\', \'' . $url_delete . '\', \'' . $load_grid . '\')">
						<i class="bi bi-trash3"></i> Delete
					</button>
				</div>
			</div>';
			$result[] = [
				$row->code_company,
				$row->code_depo . ' - ' . $row->depo_name,
				$row->code_journal_source,
				$row->description,
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
	function add()
	{
		$data['judul']     = "Form Tambah Journal Source";
		$data['load_back'] = 'C_journal_source/add';
		$data['load_grid'] = 'C_journal_source';
		$this->load->view("v_journal_source/add_journal_source", $data);
	}
	public function simpandata()
	{
		$this->form_validation->set_rules('perusahaan', 'Perusahaan', 'required');
		$this->form_validation->set_rules('depo', 'Code Depo', 'required');
		$this->form_validation->set_rules('kode_journal_source', 'kode journal source', 'required');
		$this->form_validation->set_rules('des', 'Deskripsi', 'required');
		if ($this->form_validation->run() == FALSE) {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => validation_errors(),
			];
			echo json_encode($jsonmsg);
			return;
		}
		$perusahaan = $this->input->post('perusahaan');
		$depo       = $this->input->post('depo');
		$code_js    = $this->input->post('kode_journal_source');
		$des        = $this->input->post('des');
		$param_kode =[
			'code_journal_source' => $code_js,
			'code_depo'           => $depo,
			'code_company'        => $perusahaan
		];
		$exisCode = $this->M_global->getWhere('journal_sources', $param_kode)->num_rows();
		if ($exisCode != null) {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Code Journal is already registered',
			];
			echo json_encode($jsonmsg);
			exit;
		}
		$datainsert = [
			'uuid'                => $this->uuid->v4(),
			'code_journal_source' => $code_js,
			'code_depo'           => $depo,
			'code_company'        => $perusahaan,
			'description'         => $des,
		];
		$this->db->insert('journal_sources', $datainsert);
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
		$cekdata =  $this->M_journal_source->get_where_journal_source(['a.uuid' => $uuid])->row();
		if ($cekdata != null) {
			$code_company = $cekdata->code_company;
			$data['judul'] = "Form Edit Journal Source";
			$data['load_grid'] = "C_journal_source";
			$data['uuid'] = $uuid;
			$data['data'] = $cekdata;
			$data['load_refresh'] = "C_journal_source/editform/" . $uuid;
			$data['depoList'] = $this->M_global->getWhere("depos", ['code_company' => $code_company])->result();
			$this->load->view('v_journal_source/edit_journal_source', $data);
		} else {
			$this->load->view('error');
		}
	}
	public function update()
	{
		// Ambil data dari POST request
		$uuid                = $this->input->post('uuid');
		$depo       = $this->input->post('depo');
		$code_js    = $this->input->post('kode_journal_source');
		$des        = $this->input->post('des');
		// Cek apakah UUID Divisi ada di database
		$data =  $this->M_journal_source->get_where_journal_source(['a.uuid' => $uuid])->row();
		if ($data != null) {
			if($data->code_journal_source != $code_js){
				$param_kode = [
					'a.code_journal_source' => $code_js,
					'a.code_depo'           => $depo,
				];
				$cekkode =  $this->M_journal_source->get_where_journal_source($param_kode)->num_rows();
				if ($cekkode !== 0) {
					$jsonmsg = [
						'hasil' => 'false',
						'pesan' => 'Kode sudah terdaftar',
					];
					echo json_encode($jsonmsg);
					exit;
				}
			}
			$dataupdate = [
				'code_journal_source' => $code_js,
				'code_depo'           => $depo,
				'description'         => $des,
				'updated_at'   => date('Y-m-d H:i:s')
			];
			// Melakukan update data
			$update = $this->M_global->update($dataupdate, 'journal_sources', ['uuid' => $uuid]);
			if ($update) {
				// Jika update berhasil
				$jsonmsg = [
					'hasil' => 'true',
					'pesan' => 'Data Berhasil Diupdate',
				];
				echo json_encode($jsonmsg);
			} else {
				// Jika gagal update
				$jsonmsg = [
					'hasil' => 'false',
					'pesan' => 'Gagal Menyimpan Data',
				];
				echo json_encode($jsonmsg);
			}
			
		} else {
			// Jika UUID Divisi tidak ditemukan
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'UUID Divisi tidak ditemukan',
			];
			echo json_encode($jsonmsg);
		}
	}
	public function hapusdata()
	{
		$uuid = $this->input->post('uuid');
		if (empty($uuid)) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'UUID cannot be empty'
			]);
			return;
		}
		$param_kode = ['a.uuid' => $uuid];
		$journal_source = $this->M_journal_source->get_where_journal_source($param_kode)->row();
		if (!$journal_source) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Data not found'
			]);
			return;
		}
		
		$this->db->trans_begin();
		try {
			$this->db->where('uuid', $uuid)->delete('journal_sources');
			if ($this->db->affected_rows() <= 0) {
				$this->db->trans_rollback();
				echo json_encode([
					'hasil' => 'false',
					'pesan' => 'Failed to delete data or data not found'
				]);
				return;
			}
			// Pastikan semua operasi berhasil
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				echo json_encode([
					'hasil' => 'false',
					'pesan' => 'An error occurred during the transaction, rollback executed'
				]);
			} else {
				$this->db->trans_commit();
				echo json_encode([
					'hasil' => 'true',
					'pesan' => 'Data successfully deleted'
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
