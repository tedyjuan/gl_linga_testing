<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_user extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model('M_user');
		$this->load->model('M_global');
		$this->company = $this->session->userdata('sess_company');
		$this->cp_name = $this->session->userdata('sess_company_name');
	}
	function index()
	{
		$data['judul']      = 'List Data User';
		$data['load_grid']  = 'C_user';
		$data['load_add']   = 'C_user/add';
		$data['url_delete'] = 'C_user/delete';
		$this->load->view("v_user/grid_user", $data);
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
		$columns        = ['name', 'username', 'role_id','action'];
		$order_by       = $columns[$order_col] ?? 'name';
		$data           = $this->M_user->get_paginated_user($length, $start, $search, $order_by, $dir);
		$total_records  = $this->M_user->count_all_user();
		$total_filtered = $this->M_user->count_filtered_user($search);
		$url_edit   = 'C_user/editform/';
		$url_delete = 'C_user/hapusdata/';
		$load_grid  = 'C_user/griddata';
		$result = [];
		foreach ($data as $row) {
			$aksi = '<div class="dropdown">
				<button type="button" class="btn btn-white btn-sm" id="aksi-dropdown-' . $row->id . '" data-bs-toggle="dropdown" aria-expanded="false">
					More <i class="bi-chevron-down ms-1"></i>
				</button>
				<div class="dropdown-menu dropdown-menu-sm dropdown-menu-end" aria-labelledby="aksi-dropdown-' . $row->id . '">
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
				$row->name,
				$row->username,
				$row->role_name,
				$aksi,
			];
		}
		echo json_encode([
			"draw"            => intval($this->input->post('draw')) ?? 1,
			"recordsTotal"    => $total_records,
			"recordsFiltered" => $total_filtered,
			"data"            => $result
		]);
	}
	function add()
	{
		$data['judul']     = "Form Add User";
		$data['load_back'] = 'C_user/add';
		$data['load_grid'] = 'C_user';
		$data['user']      = $this->M_global->getWhere('roles')->result();
		$data['company']   = $this->company . ' - ' . $this->cp_name;
		$this->load->view("v_user/add_user", $data);
	}
	public function simpandata()
	{
		// Validasi input
		$this->form_validation->set_rules('role_access', 'Perusahaan', 'required');
		$this->form_validation->set_rules('name_user', 'Nama User', 'required');
		$this->form_validation->set_rules('username', 'username', 'required');
		if ($this->form_validation->run() == FALSE) {
			// Jika validasi gagal
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => validation_errors(),
			];
			echo json_encode($jsonmsg);
			return;
		}
		$role_access  = $this->input->post('role_access');
		$name_user = $this->input->post('name_user');
		$username = $this->input->post('username');
		// Cek apakah kode User sudah ada
		$param_kode =[
			'username'  => $username
		];
		$exisCode = $this->M_global->getWhere('users', $param_kode)->num_rows();
		if ($exisCode != null) {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Username already exists.',
			];
			echo json_encode($jsonmsg);
			exit;
		}
		  //membuat pasword
		$pasword_default = password_hash($username, PASSWORD_DEFAULT);
		  // Data untuk insert ke database
		$datainsert = [
			'uuid'                => $this->uuid->v4(),
			'name'                => $name_user,
			'username'            => $username,
			'role_id'             => $role_access,
			'password_hash'       => $pasword_default,
			'is_password_default' => 1,
			'code_company'        => $this->company,
			'created_at'          => date('Y-m-d H:i:s'),
			'updated_at'          => date('Y-m-d H:i:s'),
			'soft_delete'         => 1,
		];
		$this->db->insert('users', $datainsert);
		if ($this->db->affected_rows() > 0) {
			$jsonmsg = [
				'hasil' => 'true',
				'pesan' => 'Data has been successfully saved.',
			];
		} else {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Failed to save data.',
			];
		}
		echo json_encode($jsonmsg);
	}
	public function editform($uuid)
	{

		$cekdata = $this->M_user->get_where_user(['a.uuid' => $uuid])->row();
		if ($cekdata != null) {
			$data['judul']        = "Edit User";
			$data['load_grid']    = 'C_user';
			$data['load_refresh'] = "C_user/editform/" . $uuid;
			$data['uuid']         = $uuid;
			$data['myadata']      = $cekdata;
			$data['company']      = $this->company . ' - ' . $this->cp_name;
			$data['user']         = $this->M_global->getWhere('roles')->result();
			$this->load->view("v_user/edit_user", $data);
		} else {
			$this->load->view('error');
		}
	}
	// Fungsi untuk update data User
	public function update()
	{
		$uuid        = $this->input->post('uuid');
		$role_access = $this->input->post('role_access');
		$name_user   = $this->input->post('name_user');
		$username    = $this->input->post('username');
		$data =  $this->M_user->get_where_user(['a.uuid' => $uuid])->row();
		if ($data != null) {
			if($data->name != $name_user){
				$param_nama = ['a.name' => $name_user];
				$ceknama =  $this->M_user->get_where_user($param_nama)->num_rows();
				if ($ceknama !== 0) {
					$jsonmsg = [
						'hasil' => 'false',
						'pesan' =>  'Username already exists.',
					];
					echo json_encode($jsonmsg);
					exit;
				}
			}
			
			$dataupdate = [
				'name'       => $name_user,
				'username'   => $username,
				'role_id'    => $role_access,
				'updated_at' => date('Y-m-d H:i:s'),
			];
			$update = $this->M_global->update($dataupdate, 'users', ['uuid' => $uuid]);
			if ($update) {
				$jsonmsg = [
					'hasil' => 'true',
					'pesan' => 'Data has been successfully Update.',
				];
				echo json_encode($jsonmsg);
			} else {
				$jsonmsg = [
					'hasil' => 'false',
					'pesan' => 'Failed to update data.',
				];
				echo json_encode($jsonmsg);
			}
		} else {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'UUID not found',
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
				'pesan' => 'UUID not found'
			]);
			return;
		}

		// Cek data
		$user = $this->M_user->get_where_user(['a.uuid' => $uuid])->row();

		if (!$user) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Data not found'
			]);
			return;
		}

		// Mulai transaksi
		$this->db->trans_begin();

		// Soft delete
		$this->db->where('uuid', $uuid)
			->update('users', [
				'soft_delete' => 0
			]);

		// Jika tidak ada perubahan
		if ($this->db->affected_rows() <= 0) {
			$this->db->trans_rollback();
			echo json_encode([
				'hasil' => 'false',
				'pesan' =>"Failed to change data"
			]);
			return;
		}

		// Cek status transaksi
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode([
				'hasil' => 'false',
				'pesan' =>'A transaction error occurred'
			]);
			return;
		}

		// Commit
		$this->db->trans_commit();

		echo json_encode([
			'hasil' => 'true',
			'pesan' => 'Data deactivated successfully'
		]);
	}
}
