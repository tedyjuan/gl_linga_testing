<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_role extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model('M_role');
		$this->load->model('M_global');
	}
	function index()
	{
		$data['judul']      = 'Master Role';
		$data['load_grid']  = 'C_role';
		$data['load_add']   = 'C_role/add';
		$data['url_delete'] = 'C_role/delete';
		$this->load->view("v_role/grid_role", $data);
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
		$columns        = ['name'];
		$order_by       = $columns[$order_col] ?? 'name';
		$data           = $this->M_role->get_paginated_role($length, $start, $search, $order_by, $dir);
		$total_records  = $this->M_role->count_all_role();
		$total_filtered = $this->M_role->count_filtered_role($search);
		$url_edit       = 'C_role/role_menu_access/';
		$url_delete     = 'C_role/hapusdata/';
		$load_grid      = 'C_role/griddata';
		$result = [];
		foreach ($data as $keys=>$row) {
			$aksi = '<div class="dropdown">
				<button type="button" class="btn btn-white btn-sm" id="aksi-dropdown-' . $keys . '" data-bs-toggle="dropdown" aria-expanded="false">
					More <i class="bi-chevron-down ms-1"></i>
				</button>
				<div class="dropdown-menu dropdown-menu-sm dropdown-menu-end" aria-labelledby="aksi-dropdown-' . $keys . '">
					<button class="dropdown-item editbtn" onclick="editform(\'' . $url_edit . '\', \'' . $row->uuid . '\')">
						<i class="bi bi-grid"></i> Role access
					</button>
					<div class="dropdown-divider"></div>
					<button class="dropdown-item text-danger" onclick="hapus(\'' . $row->uuid . '\', \'' . $url_delete . '\', \'' . $load_grid . '\')">
						<i class="bi bi-trash3"></i> Delete
					</button>
				</div>
			</div>';
			$result[] = [
				$row->name,
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
		$data['judul']     = "Form Add Role";
		$data['load_back'] = 'C_role/add';
		$data['load_grid'] = 'C_role';
		$this->load->view("v_role/add_role", $data);
	}
	public function simpandata()
	{
		$this->form_validation->set_rules('name_role', 'Role access', 'required');
		if ($this->form_validation->run() == FALSE) {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => validation_errors(),
			];
			echo json_encode($jsonmsg);
			return;
		}
		$name_role  = $this->input->post('name_role');
		$exisRole = $this->M_global->getWhere('roles', ['name'  => $name_role])->num_rows();
		if ($exisRole != null) {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Role access has already been used',
			];
			echo json_encode($jsonmsg);
			exit;
		}
		$datainsert = [
			'uuid' => $this->uuid->v4(),
			'name' => $name_role
		];
		$this->db->insert('roles', $datainsert);
		if ($this->db->affected_rows() > 0) {
			$jsonmsg = [
				'hasil' => 'true',
				'pesan' => 'Data saved successfully',
			];
		} else {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Failed to save data',
			];
		}
		echo json_encode($jsonmsg);
	}
	public function role_menu_access($uuid)
	{
		$mydata =  $this->M_role->get_where_role(['a.uuid' => $uuid])->row();
		if ($mydata != null) {
			$name = $mydata->name;
			$menu =  $this->M_role->get_menu_role_access($mydata->id);
			if(!empty($menu)){
				$data['judul']   = "Access Menu Role : " .$name;
				$data['uuid']    = $uuid;
				$data['menu']    = $menu;
				$data['load_grid']    = "C_role";
				$data['load_refresh'] = "C_role/role_menu_access/" . $uuid;
				$this->load->view('v_role/menu_role_access', $data);
			}else{
				$this->load->view('error');
			}
		} else {
			$this->load->view('error');
		}
	}
	// Fungsi untuk update data Role
	public function update()
	{
		// Ambil data dari POST request
		$uuid = $this->input->post('uuid'); 
		$code_role = $this->input->post('kode_role');
		$nama_role = $this->input->post('nama_role');
		$alias_post       = $this->input->post('alias');
		// Cek apakah UUID Role ada di database
		$data =  $this->M_role->get_where_role(['a.uuid' => $uuid])->row();
		if ($data != null) {
			$code_company = $data->code_company;
			$cek_cost_center =  $this->M_global->getWhere('cost_centers', ['code_role' => $data->code_role])->num_rows();
			if ($cek_cost_center != 0) {
				$jsonmsg = [
					'hasil' => 'false',
					'pesan' => 'Cannot update Role data because it is still being used in cost centers.',
				];
				echo json_encode($jsonmsg);
				exit;
			}
			if($data->code_role != $code_role){
				$cekkode =  $this->M_role->get_where_role(['a.code_role' => $code_role, "a.code_company" => $code_company ])->num_rows();
				if ($cekkode != 0) {
					$jsonmsg = [
						'hasil' => 'false',
						'pesan' => 'Role code is already registered',
					];
					echo json_encode($jsonmsg);
					exit;
				}
			}
			if($data->name != $nama_role){
				$param_nama = ['a.name' => $nama_role, "a.code_company" => $code_company];
				$ceknama =  $this->M_role->get_where_role($param_nama)->num_rows();
				if ($ceknama !== 0) {
					$jsonmsg = [
						'hasil' => 'false',
						'pesan' => 'Name is already registered',
					];
					echo json_encode($jsonmsg);
					exit;
				}
			}
			if($data->alias !== $alias_post){
				$cekalias =  $this->M_role->get_where_role(['a.alias' => $alias_post])->num_rows();
				if ($cekalias !== 0) {
					$jsonmsg = [
						'hasil' => 'false',
						'pesan' => 'Alias is already registered',
					];
					echo json_encode($jsonmsg);
					exit;
				}
			}
			$dataupdate = [
				'code_role'  => $code_role,
				'name'         => $nama_role,
				'alias'        => $alias_post,
				'updated_at'   => date('Y-m-d H:i:s')
			];
			// Melakukan update data
			$update = $this->M_global->update($dataupdate, 'roles', ['uuid' => $uuid]);
			if ($update) {
				// Jika update berhasil
				$jsonmsg = [
					'hasil' => 'true',
					'pesan' => 'Data updated successfully',
				];
				echo json_encode($jsonmsg);
			} else {
				// Jika gagal update
				$jsonmsg = [
					'hasil' => 'false',
					'pesan' => 'Failed to updated data',
				];
				echo json_encode($jsonmsg);
			}
			
		} else {
			// Jika UUID Role tidak ditemukan
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'UUID Role not found',
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
		$role = $this->M_role->get_where_role($param_kode)->row();
		if (!$role) {
			$this->db->trans_rollback();
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Data not found'
			]);
			return;
		}
		$cek_cc = $this->M_global->getWhere('role_menu_access', ['role_id' => $role->id])->num_rows();
		if ($cek_cc != 0) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => "Cannot delete the data, it is still being used in the role menu."
			]);
			return;
		}
		$this->db->trans_begin();
		try {
			$this->db->where('uuid', $uuid)->delete('roles');
			if ($this->db->affected_rows() <= 0) {
				$this->db->trans_rollback();
				echo json_encode([
					'hasil' => 'false',
					'pesan' => "Data failed to delete, UUID not found."
				]);
				return;
			}
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				echo json_encode([
					'hasil' => 'false',
					'pesan' => "An error occurred in the transaction."
				]);
			} else {
				$this->db->trans_commit();
				echo json_encode([
					'hasil' => 'true',
					'pesan' => "The data has been successfully deleted."
				]);
			}
		} catch (Exception $e) {
			$this->db->trans_rollback();
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'An error occurred: ' . $e->getMessage()
			]);
		}
	}


	 public function set_role_menu(){
		$uuid     = $this->input->post('uuid');
		$idmenu   = $this->input->post('idmenu'); // id submenu
		$menuhead = $this->input->post('menuhead'); // hapus / tambah
		$parent   = $this->input->post('parent'); // id header
		$mydata = $this->M_role->get_where_role(['a.uuid' => $uuid])->row();
		if ($mydata != null) {
			$role_id = $mydata->id;
			$menu_id = $idmenu;
			$sub_menu = $this->M_role->get_menu_akses($menu_id, $role_id)->row();
			$header_menu = $this->M_role->get_menu_akses($parent, $role_id)->row();
			if($sub_menu == null){
				if($header_menu == null ){
					if ($menuhead == "on") {
						$dataheader = [
							'role_id' => $role_id,
							'menu_id' => $parent
						];
						$this->db->insert('role_menu_access', $dataheader);
					} 
				}
				$datainsert = [
					'role_id' => $role_id,
					'menu_id' => $menu_id
				];
				$this->db->insert('role_menu_access', $datainsert);
				$jsonmsg = [
					'hasil' => 'true',
					'pesan' => 'Data saved successfully',
				];
				echo json_encode($jsonmsg);
			}else{
				// jika sub menu tidak kosong maka sudah di pastikan sudah punya header
				if ($menuhead == "off") {
					$param = [
						'role_id' => $role_id,
						'menu_id' => $parent
					];
					$this->db->where($param)->delete('role_menu_access');
				}
				$param = [
					'role_id' => $role_id,
					'menu_id' => $menu_id
				];
				$this->db->where($param)->delete('role_menu_access');
				$jsonmsg = [
					'hasil' => 'true',
					'pesan' => 'Data has been successfully deactivated',
				];
				echo json_encode($jsonmsg);
			}
			
		} else {
			$jsonmsg = [
			'hasil' => 'false',
			'pesan' => "",
			];
			echo json_encode($jsonmsg);
		}
	 }
}
