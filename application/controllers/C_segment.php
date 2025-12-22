<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_segment extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model('M_segment');
		$this->load->model('M_global');
	}
	function index()
	{
		$data['judul']      = 'List Data Segment';
		$data['load_grid']  = 'C_segment';
		$data['load_add']   = 'C_segment/add';
		$data['url_delete'] = 'C_segment/delete';
		$this->load->view("v_segment/grid_segment", $data);
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
		$columns        = ['code_company', 'company_name', 'code_segment', 'name', 'action'];
		$order_by       = $columns[$order_col] ?? 'name';
		$data           = $this->M_segment->get_paginated_segment($length, $start, $search, $order_by, $dir);
		$total_records  = $this->M_segment->count_all_segment();
		$total_filtered = $this->M_segment->count_filtered_segment($search);
		$url_edit       = 'C_segment/editform/';
		$url_delete     = 'C_segment/hapusdata/';
		$load_grid      = 'C_segment/griddata';
		$result = [];
		foreach ($data as $row) {
			$aksi = '<div class="dropdown">
				<button type="button" class="btn btn-white btn-sm" id="aksi-dropdown-' . $row->code_segment . '" data-bs-toggle="dropdown" aria-expanded="false">
					More <i class="bi-chevron-down ms-1"></i>
				</button>
				<div class="dropdown-menu dropdown-menu-sm dropdown-menu-end" aria-labelledby="aksi-dropdown-' . $row->code_segment . '">
					<button class="dropdown-item editbtn" onclick="editform(\'' . $url_edit . '\', \'' . $row->uuid . '\')">
						<i class="bi bi-pen"></i> Edit
					</button>
					<div class="dropdown-divider"></div>
					<button class="dropdown-item text-danger" onclick="hapus(\'' . $row->uuid . '\', \'' . $url_delete . '\', \'' . $load_grid . '\')">
						<i class="bi bi-trash3"></i> Delete
					</button>
				</div>
			</div>';
			$result[] = [
				$row->code_company . ' - ' . $row->company_name,
				$row->code_segment,
				$row->name,
				$row->alias,
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
		$data['judul']     = "Form Tambah Segment";
		$data['load_back'] = 'C_segment/add';
		$data['load_grid'] = 'C_segment';
		$this->load->view("v_segment/add_segment", $data);
	}
	public function simpandata()
	{
		// Validasi input
		$this->form_validation->set_rules('perusahaan', 'Perusahaan', 'required');
		$this->form_validation->set_rules('kode_segment', 'Code segment', 'required');
		$this->form_validation->set_rules('nama_segment', 'Nama segment', 'required');
		$this->form_validation->set_rules('alias', 'Nama Alias', 'required');
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
		$perusahaan  = $this->input->post('perusahaan');
		$code_segment = $this->input->post('kode_segment');
		$nama_segment = $this->input->post('nama_segment');
		$alias       = $this->input->post('alias');
		// Cek apakah kode segment sudah ada
		$param_kode = [
			'code_segment'  => $code_segment
		];
		$exisCode = $this->M_global->getWhere('segments', $param_kode)->num_rows();
		if ($exisCode != null) {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Kode segment sudah digunakan',
			];
			echo json_encode($jsonmsg);
			exit;
		}
		$param_alias = ['alias'  => $alias];
		$exisalias = $this->M_global->getWhere('segments', $param_alias)->num_rows();
		if ($exisalias != null) {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Alias sudah digunakan',
			];
			echo json_encode($jsonmsg);
			exit;
		}
		$param_nama = [
			'name'         => $nama_segment,
			'code_company' => $perusahaan,
		];
		$exisName = $this->M_global->getWhere('segments', $param_nama)->num_rows();
		if ($exisName != null) {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Kode segment sudah digunakan',
			];
			echo json_encode($jsonmsg);
			exit;
		}
		// Data untuk insert ke database
		$datainsert = [
			'uuid'         => $this->uuid->v4(),
			'code_segment'  => $code_segment,
			'code_company' => $perusahaan,
			'name'         => $nama_segment,
			'alias'        => $alias,
			'created_at'   => date('Y-m-d H:i:s'),
			'updated_at'   => date('Y-m-d H:i:s')
		];
		// Melakukan insert data
		$this->db->insert('segments', $datainsert);
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
	public function editform($uuid)
	{
		$data =  $this->M_segment->get_where_segment(['a.uuid' => $uuid])->row();
		if ($data != null) {
			$judul = "Form Edit segment";
			$load_grid = "C_segment";
			$load_refresh = "C_segment/editform/" . $uuid;
			$this->load->view('v_segment/edit_segment', [
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
	// Fungsi untuk update data segment
	public function update()
	{
		// Ambil data dari POST request
		$uuid = $this->input->post('uuid');
		$perusahaan  = $this->input->post('perusahaan');
		$code_segment = $this->input->post('kode_segment');
		$nama_segment = $this->input->post('nama_segment');
		$alias_post       = $this->input->post('alias');
		// Cek apakah UUID segment ada di database
		$data =  $this->M_segment->get_where_segment(['a.uuid' => $uuid])->row();
		if ($data != null) {
			$cek_cost_center =  $this->M_global->getWhere('cost_centers', ['code_segment' => $data->code_segment])->num_rows();
			if ($cek_cost_center != 0) {
				$jsonmsg = [
					'hasil' => 'false',
					'pesan' => 'Tidak bisa mengubah Data Segment karena sedang digunakan di cost centers.',
				];
				echo json_encode($jsonmsg);
				exit;
			}
			if ($data->code_segment == $code_segment) {
				$p_kode = 'LOLOS';
			} else {
				$param_kode = ['a.code_segment' => $code_segment];
				$cekkode =  $this->M_segment->get_where_segment($param_kode)->num_rows();
				if ($cekkode == 0) {
					$p_kode = 'LOLOS';
				} else {
					$p_kode = 'TTIDAK_LOLOS';
				}
			}
			if ($data->name == $nama_segment) {
				$p_nama = 'LOLOS';
			} else {
				$param_nama = ['a.name' => $nama_segment, "a.code_company" => $perusahaan];
				$ceknama =  $this->M_segment->get_where_segment($param_nama)->num_rows();
				if ($ceknama == 0) {
					$p_nama = 'LOLOS';
				} else {
					$p_nama = 'TTIDAK_LOLOS';
				}
			}
			if ($data->alias == $alias_post) {
				$p_alias = 'LOLOS';
			} else {
				$param_alias = ['a.alias' => $alias_post];
				$cekalias =  $this->M_segment->get_where_segment($param_alias)->num_rows();
				if ($cekalias == 0) {
					$p_alias = 'LOLOS';
				} else {
					$p_alias = 'TTIDAK_LOLOS';
				}
			}
			if ($p_kode == 'LOLOS' && $p_nama == 'LOLOS' && $p_alias == 'LOLOS') {
				// Siapkan data yang akan diupdate
				$dataupdate = [
					'uuid'         => $this->uuid->v4(),
					'code_segment'  => $code_segment,
					'name'         => $nama_segment,
					'alias'        => $alias_post,
					'code_company' => $perusahaan,
					'updated_at'   => date('Y-m-d H:i:s')
				];
				// Melakukan update data
				$update = $this->M_global->update($dataupdate, 'segments', ['uuid' => $uuid]);
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
				$pesan = '';
				if ($p_kode == 'TIDAK_LOLOS') {
					$pesan = 'Kode Depo sudah terdaftar';
				}
				if ($p_nama == 'TIDAK_LOLOS') {
					$pesan = 'Nama sudah terdaftar';
				}
				if ($p_alias == 'TIDAK_LOLOS') {
					$pesan = 'Singkatan sudah terdaftar';
				}

				$jsonmsg = [
					'hasil' => 'false',
					'pesan' => $pesan,
				];
				echo json_encode($jsonmsg);
			}
		} else {
			// Jika UUID segment tidak ditemukan
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'UUID segment tidak ditemukan',
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
		// Ambil data segment berdasarkan UUID
		$param_kode = ['a.uuid' => $uuid];
		$segment = $this->M_segment->get_where_segment($param_kode)->row();
		// Jika data tidak ditemukan
		if (!$segment) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Data tidak ditemukan'
			]);
			return;
		}
		$cek_cc = $this->M_global->getWhere('cost_centers', ['code_segment' => $segment->code_segment])->num_rows();
		if ($cek_cc != 0) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Tidak bisa Menghapus Data, karena sedang digunakan di cost centers.',
			]);
			return;
		}
		$this->db->trans_begin();
		try {
			
			// Lakukan penghapusan data di tabel segments
			$this->db->where('uuid', $uuid)->delete('segments');
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
