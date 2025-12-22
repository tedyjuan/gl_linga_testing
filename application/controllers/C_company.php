<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_company extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model('M_company');
		$this->load->model('M_global');
	}
	function index()
	{
		$data['judul']      = 'Data Company';
		$data['load_grid']  = 'C_company';
		$data['load_add']   = 'C_company/add';
		$data['url_delete'] = 'C_company/delete';
		$this->load->view("v_company/grid_company", $data);
	}
	public function griddata()
	{
		// Fungsi untuk mengambil data dan mengembalikannya ke DataTables dengan server-side processing
		// Menangkap parameter dari DataTables
		$start  = $this->input->get('start');               // Indeks baris pertama untuk halaman
		$length = $this->input->get('length');              // Jumlah data per halaman
		$search = $this->input->get('search')['value'];     // Nilai pencarian
		$order  = $this->input->get('order')[0]['column'];  // Kolom yang akan diurutkan
		$dir    = $this->input->get('order')[0]['dir'];     // Urutan 'asc' atau 'desc'

		// Menentukan kolom untuk pengurutan berdasarkan parameter 'order'
		$order_by = ['code_company', 'name', 'action'][$order];

		// Mengambil data dengan pagination dan pencarian
		$data = $this->M_company->get_paginated_companies($length, $start, $search, $order_by, $dir);

		// Menghitung jumlah total data
		$total_records = $this->M_company->count_all_companies();
		$total_filtered = $this->M_company->count_filtered_companies($search);
		$url_edit   = 'C_company/editform/';
		// $url_delete = 'C_company/hapusdata/';
		// $load_grid  = 'C_company/griddata';
		// Format data untuk dikirimkan ke DataTables
		$result = [];
		foreach ($data as $row) {
			$aksi = '<button type="button"  onclick="editform(\'' . $url_edit . '\', \'' . $row->uuid . '\')" class="btn btn-soft-primary"><i class="bi bi-pen"></i> Edit</button>';

			// $aksi = '<div class="dropdown">
			// 	<button type="button" class="btn btn-white btn-sm" id="aksi-dropdown-' . $row->code_company . '" data-bs-toggle="dropdown" aria-expanded="false">
			// 		More <i class="bi-chevron-down ms-1"></i>
			// 	</button>
			// 	<div class="dropdown-menu dropdown-menu-sm dropdown-menu-end" aria-labelledby="aksi-dropdown-' . $row->code_company . '">
			// 		<button class="dropdown-item editbtn" onclick="editform(\'' . $url_edit . '\', \'' . $row->uuid . '\')">
			// 			<i class="bi bi-pen"></i> Edit
			// 		</button>
			// 		<div class="dropdown-divider"></div>
			// 		</div>
			// 		<button class="dropdown-item text-danger" onclick="hapus(\'' . $row->uuid . '\', \'' . $url_delete . '\', \'' . $load_grid . '\')">
			// 			<i class="bi bi-trash3"></i> Delete
			// 		</button>
			// 		</div>';
			$result[] = [
				$row->code_company,  // code Company
				$row->name,          // nama Company
				$aksi                // Aksi
			];
		}

		// Mengirimkan data dalam format JSON
		echo json_encode([
			"draw" => $_GET['draw'],  // Draw counter (untuk menyinkronkan dengan DataTables)
			"recordsTotal" => $total_records,  // Total data
			"recordsFiltered" => $total_filtered,  // Data yang difilter
			"data" => $result
		]);
	}

	function add()
	{
		$data['judul']     = "Form Tambah Company";
		$data['load_back'] = 'C_company/add';
		$data['load_grid'] = 'C_company';
		$this->load->view("v_company/add_company", $data);
	}
	public function simpandata()
	{
		// Validasi input
		$this->form_validation->set_rules('company_code', 'Kode Perusahaan', 'required');
		$this->form_validation->set_rules('company_name', 'Nama Perusahaan', 'required');

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
		$company_code = $this->input->post('company_code');
		$company_name = $this->input->post('company_name');

		// Cek apakah kode perusahaan sudah ada
		$this->load->database();
		$this->db->where('code_company', $company_code);
		$existingCompany = $this->db->get('companies')->num_rows() > 0;

		if ($existingCompany) {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Kode perusahaan sudah digunakan',
			];
			echo json_encode($jsonmsg);
		} else {

			// Data untuk insert ke database
			$datainsert = [
				'uuid'         => $this->uuid->v4(),
				'code_company' => $company_code,
				'name'         => $company_name,
				'created_at'   => date('Y-m-d H:i:s'),
				'updated_at'   => date('Y-m-d H:i:s')
			];

			// Melakukan insert data
			$this->db->insert('companies', $datainsert);
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
	}
	public function editform($uuid)
	{
		$data = $this->M_company->get_company_by_uuid($uuid);
		if ($data) {
			$judul = "Form Edit Company";
			$load_grid = "C_company";
			$load_refresh = "C_company/editform/" . $uuid;
			$this->load->view('v_company/edit_company', [
				'judul' => $judul,
				'load_grid' => $load_grid,
				'load_refresh' => $load_refresh,
				'data' => $data,
				'uuid' => $uuid
			]);
		} else {
			$this->load->view('error');
		}
	}
	// Fungsi untuk update data perusahaan
	public function updatedata()
	{
		// Ambil data dari POST request
		$uuid = $this->input->post('uuid');  // Ambil UUID dari input POST
		$company_name = $this->input->post('company_name');  // Ambil nama perusahaan

		// Cek apakah UUID perusahaan ada di database
		$existingCompany =  $this->M_global->getWhereOrder('companies','id', ['uuid' => $uuid])->num_rows() > 0;
		if ($existingCompany) {
			// Siapkan data yang akan diupdate
			$dataupdate = [
				'name' => $company_name,
				'updated_at' => date('Y-m-d H:i:s')  // Format waktu sekarang
			];

			// Melakukan update data
			$update = $this->M_global->update($dataupdate, 'companies', ['uuid' => $uuid]);
			if ($update) {
				// Jika update berhasil
				$jsonmsg = [
					'hasil' => 'true',
					'pesan' => 'Data Berhasil Diupdate',
				];
			} else {
				// Jika gagal update
				$jsonmsg = [
					'hasil' => 'false',
					'pesan' => 'Gagal Menyimpan Data',
				];
			}
		} else {
			// Jika UUID perusahaan tidak ditemukan
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'UUID perusahaan tidak ditemukan',
			];
		}

		// Mengembalikan hasil dalam format JSON
		echo json_encode($jsonmsg);
	}
	public function search()
	{
		$get_value = $this->input->get('getCompany');
		$cari = preg_replace("/[^a-zA-Z0-9]/", '', $get_value);
		$this->db->like('code_company', $cari);
		$this->db->or_like('name', $cari);
		$hasil = $this->db->get('companies')->result();
		echo json_encode($hasil);
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

		// Mulai transaksi
		$this->db->trans_begin();

		try {
			// Ambil data divisi berdasarkan UUID
			$depos = $this->M_company->get_company_by_uuid($uuid);
			if ($depos == null) {
				$this->db->trans_rollback();
				echo json_encode([
					'hasil' => 'false',
					'pesan' => 'Data tidak ditemukan'
				]);
				exit;
			}
			// Cek apakah perusahaan masih memiliki entitas lain
			$count_company = $this->M_global->count_company_integrate($depos->code_company);
			if ($count_company != null) {
				$depos_count     = $count_company->depos_count;
				$dept_count      = $count_company->dept_count;
				$divisi_count    = $count_company->divisi_count;
				$segment_count   = $count_company->segment_count;
				$total_referensi = $count_company->total_count;
				if ($total_referensi != 0) {
					if ($depos_count != 0) {
						$this->db->trans_rollback();
						echo json_encode([
							'hasil' => 'false',
							'pesan' => 'Data Company masih digunakan di master Depos'
						]);
						exit;
					}
					if ($dept_count != 0) {
						$this->db->trans_rollback();
						echo json_encode([
							'hasil' => 'false',
							'pesan' => 'Data Company masih digunakan di master Depeartement'
						]);
						exit;
					}
					if ($divisi_count != 0) {
						$this->db->trans_rollback();
						echo json_encode([
							'hasil' => 'false',
							'pesan' => 'Data Company masih digunakan di master Divisi'
						]);
						exit;
					}
					if ($segment_count != 0) {
						$this->db->trans_rollback();
						echo json_encode([
							'hasil' => 'false',
							'pesan' => 'Data Company masih digunakan di master Segment'
						]);
						exit;
					}
				}
			}
			$this->db->where('uuid', $uuid)->delete('companies');

			if ($this->db->affected_rows() <= 0) {
				$this->db->trans_rollback();
				echo json_encode([
					'hasil' => 'false',
					'pesan' => 'Data gagal dihapus atau tidak ditemukan'
				]);
				exit;
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
