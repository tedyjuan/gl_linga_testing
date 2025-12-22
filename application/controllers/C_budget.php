<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_budget extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model('M_budget');
		$this->load->model('M_global');
	}
	function index()
	{
		$data['judul']      = 'Budget Data';
		$data['load_grid']  = 'C_budget';
		$data['load_add']   = 'C_budget/add';
		$data['url_delete'] = 'C_budget/delete';
		$this->load->view("v_budget/grid_budget", $data);
	}
	public function griddata()
	{
		$start        = $this->input->post('start') ?? 0;
		$length       = $this->input->post('length') ?? 10;
		$search_input = $this->input->post('search');
		$draw         = intval($this->input->post('draw')) ?? 1;
		$search       = isset($search_input['value']) ? $search_input['value'] : '';

		// Dapatkan parameter order yang dikirim oleh DataTable
		$order_input    = $this->input->post('order');
		$order_col      = isset($order_input[0]['column']) ? $order_input[0]['column'] : 0;
		if (isset($order_input[0]['dir'])) {
			if ($draw % 2 == 0) {
				$dir = 'asc'; // Jika draw genap
			} else {
				$dir = 'desc'; // Jika draw ganjil
			}
		} else {
			$dir = 'desc'; 
		}
		// Kolom yang dapat diurutkan
		if($draw == 1){
			$columns        = ['a.code_budgeting'];
		}else{
			$columns        = ['a.code_company', 'a.code_budgeting', 'c.name'];
		}
		// Tentukan kolom mana yang akan diurutkan berdasarkan order yang diterima
		$order_by       = $columns[$order_col] ?? 'id';
		$data           = $this->M_budget->get_paginated_budget($length, $start, $search, $order_by, $dir);
		$total_records  = $this->M_budget->count_all_budget();
		$total_filtered = $this->M_budget->count_filtered_budget($search);
		$url_detail       = 'C_budget/detailform';
		$url_delete     = 'C_budget/hapusdata';
		$load_grid      = 'C_budget/griddata';
		$result = [];
		foreach ($data as $row) {
			$aksi = '<div class="dropdown">
				<button type="button" class="btn btn-white btn-sm" id="aksi-dropdown-' . $row->code_budgeting . '" data-bs-toggle="dropdown" aria-expanded="false">
					More <i class="bi-chevron-down ms-1"></i>
				</button>
				<div class="dropdown-menu dropdown-menu-sm dropdown-menu-end" aria-labelledby="aksi-dropdown-' . $row->code_budgeting . '">
					<button class="dropdown-item editbtn" onclick="editform(\'' . $url_detail . '\', \'' . $row->uuid . '\')">
						<i class="bi bi-eye"></i> Detail
					</button>
					<div class="dropdown-divider"></div>
					<button class="dropdown-item text-danger" onclick="hapus(\'' . $row->uuid . '\', \'' . $url_delete . '\', \'' . $load_grid . '\')">
						<i class="bi bi-trash3"></i> Delete
					</button>
				</div>
			</div>';
			if($row->status_budgeting == 'OPEN'){
				$class = 'bg-primary';
			}else if($row->status_budgeting == 'REVIEW'){
				$class = 'bg-warning ';
			}else if($row->status_budgeting == 'APPROVED'){
				$class = 'bg-success ';
			}else if($row->status_budgeting == 'REJECT'){
				$class = 'bg-danger';
			}else if($row->status_budgeting == 'CLOSED'){
				$class = 'bg-secondary';
			}
			$ststus = '<span class="badge '.$class.'">'. strtolower($row->status_budgeting).'</span>';
			$result[] = [
				$row->code_company . ' - ' . $row->company_name,
				$row->code_budgeting,
				$row->department_name,
				$ststus,
				$aksi,
			];
		}
		echo json_encode([
			"draw" => $draw,
			"recordsTotal" => $total_records,
			"recordsFiltered" => $total_filtered,
			"data" => $result,
		]);
	}
	function add()
	{
		$data['judul']     = "Form Budget";
		$data['load_back'] = 'C_budget/add';
		$data['load_grid'] = 'C_budget';
		$data['perusahaanList'] = $this->M_global->getWhereOrder('companies')->result();
		$this->load->view("v_budget/add_budget", $data);
	}

	public function simpandata()
	{
		$data = $this->input->post(); 
		$code_company    = $data['perusahaan'];
		$code_department = $data['department'];
		$opening_balance = $data['saldo_awal'];
		$extend_budget   = $data['perpanjang_angaran'];
		$project_amount  = $data['jumlah_project'];
		$p_dept = [
			"code_department" => $code_department,
			"code_company" => $code_company,
		];
		// Cek apakah data department ada
		$data_department = $this->M_global->getWhere("departments", $p_dept)->row();
		if ($data_department == null) {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Data Departement tidak di temukan',
			];
			echo json_encode($jsonmsg);
			exit;
		}
		// Mulai transaksi
	
		$code_budgeting = $this->M_global->generate_code("BGT");
		$budgeting_header = [
			'uuid'              => $this->uuid->v4(),
			'code_budgeting'    => $code_budgeting,
			'code_company'      => $code_company,
			'code_department'   => $code_department,
			'opening_balance'   => (int)str_replace('.', '', $opening_balance),
			'extend_budget'     => (int)str_replace('.', '', $extend_budget),
			'project_amount'    => (int)str_replace('.', '', $project_amount),
			'years'             => date('Y'),
			'date_budgeting'    => date('Y-m-d'),
			'status_budgeting'  => "OPEN",
		];
		$this->db->trans_start();
		$this->db->insert('budgeting_headers', $budgeting_header);

		$projectsData  = [];
		$countaData    = [];
		$countbData    = [];
		$uploadedFiles = [];
		$file_uploads  = [];
		$uploadError   = false;
		$uploadPath    = './uploads/';
		if (!is_dir($uploadPath)) {
			mkdir($uploadPath, 0777, true);
		}

		foreach ($data['projects'] as $key => $project) {
			if (isset($_FILES['projects']['name'][$key]['project_file']) && $_FILES['projects']['error'][$key]['project_file'] == 0) {
				$this->load->library('upload');
				// Akses data file yang diupload
				$_FILES['upload_file'] = [
					'name'     => $_FILES['projects']['name'][$key]['project_file'],
					'type'     => $_FILES['projects']['type'][$key]['project_file'],
					'tmp_name' => $_FILES['projects']['tmp_name'][$key]['project_file'],
					'error'    => $_FILES['projects']['error'][$key]['project_file'],
					'size'     => $_FILES['projects']['size'][$key]['project_file'],
				];

				// Memeriksa ekstensi file yang diizinkan
				$ext = strtolower(pathinfo($_FILES['upload_file']['name'], PATHINFO_EXTENSION));
				if (!in_array($ext, ['doc', 'docx', 'xlsx', 'pdf'])) {
					$uploadError = true;
					$errorMsg = "Format file harus doc, docx, xlsx, pdf.";
					break;
				}

				// Memeriksa ukuran file
				if ($_FILES['upload_file']['size'] > (15 * 1024 * 1024)) {
					$uploadError = true;
					$errorMsg = "Ukuran file maksimal 15 MB.";
					break;
				}

				$nama_baru = 'file_' . time() . '_' . $key . '.' . $ext;
				$config = [
					'upload_path'   => $uploadPath,
					'allowed_types' => 'doc|docx|xlsx|pdf',  // Perubahan di sini
					'max_size'      => 15360,
					'file_name'     => $nama_baru,
					'overwrite'     => false,
				];

				$this->upload->initialize($config);
				if ($this->upload->do_upload('upload_file')) {
					$data_upload        = $this->upload->data();
					$file_uploads[$key] = $data_upload['file_name'];
					$uploadedFiles[]    = $data_upload['full_path'];
				} else {
					$errorMsg = $this->upload->display_errors();
					$uploadError = true;
					break;
				}

				if ($uploadError) {
					// Jika ada error upload file, rollback transaksi dan hapus file yang diupload
					foreach ($uploadedFiles as $file) {
						if (file_exists($file)) unlink($file);
					}

					// Rollback transaksi database
					$this->db->trans_rollback();

					echo json_encode([
						'hasil' => 'false',
						'pesan' => $errorMsg ?? 'Gagal upload file.'
					]);
					return;
				}
			} else {
				$nama_baru = '';
				$jsonmsg = [
					'hasil' => 'false',
					'pesan' => "Data File Tidak Boleh Kosong di salahsatu project",
				];
				echo json_encode($jsonmsg);
				$this->db->trans_rollback(); 
				return;
			}

			if (!empty($project['counta']) && !empty($project['countb'])) {
				$set_data = 'ALL';
			} elseif (!empty($project['counta'])) {
				$set_data = 'OPEX';
			} elseif (!empty($project['countb'])) {
				$set_data = 'CAPEX';
			} else {
				$jsonmsg = [
					'hasil' => 'false',
					'pesan' => 'Tujuan Proyek Tidak boleh kosong',
				];
				echo json_encode($jsonmsg);
				$this->db->trans_rollback(); 
				return;
			}

			$projectsData[] = [
				'uuid'            => $this->uuid->v4(),
				'code_budgeting'  => $code_budgeting,
				'code_company'    => $code_company,
				'project_item'    => $key,
				'project_name'    => $project['project_name'],
				'goal_project'    => $set_data,
				'project_desc'    => $project['project_desc'],
				'budget_proposal' => (int)str_replace('.', '', $project['usulan_anggaran']),
				'filename'        => $nama_baru,
			];
			if (!empty($project['counta'])) {
				foreach ($project['counta'] as $ca => $item_a) {
					$counta = json_decode($item_a, true);
					$countaData[] = [
						'uuid'           => $this->uuid->v4(),
						'code_budgeting' => $code_budgeting,
						'code_company'   => $code_company,
						'project_item'   => $key,
						'itemnumber'     => $ca,
						'type_goal'      => 'OPEX',
						'desc'           => $counta['keterangan'],
						'account_number' => $counta['account'],
						'amount'         => (int)str_replace('.', '', $counta['jumlah']),
					];
				}
			}
			if (!empty($project['countb'])) {
				foreach ($project['countb'] as $cb => $item_b) {
					$countb = json_decode($item_b, true);
					$countbData[] = [
						'uuid'           => $this->uuid->v4(),
						'code_budgeting' => $code_budgeting,
						'code_company'   => $code_company,
						'project_item'   => $key,
						'itemnumber'     => $cb,
						'type_goal'      => 'CAPEX',
						'desc'           => $countb['keterangan'],
						'amount'         => (int)str_replace('.', '', $countb['jumlah']),
					];
				}
			}

			
		}

		if (!empty($projectsData)) {
			if (!$this->db->insert_batch('budgeting_projects', $projectsData)) {
				$this->db->trans_rollback();
				$jsonmsg = [
					'hasil' => 'false',
					'pesan' => 'Gagal menyimpan data proyek',
				];
				echo json_encode($jsonmsg);
				exit;
			}
		}

		if (!empty($countaData)) {
			if (!$this->db->insert_batch('budgeting_project_items', $countaData)) {
				$this->db->trans_rollback();
				$jsonmsg = [
					'hasil' => 'false',
					'pesan' => 'Gagal menyimpan data counta',
				];
				echo json_encode($jsonmsg);
				exit;
			}
		}

		if (!empty($countbData)) {
			if (!$this->db->insert_batch('budgeting_project_items', $countbData)) {
				$this->db->trans_rollback(); 
				$jsonmsg = [
					'hasil' => 'false',
					'pesan' => 'Gagal menyimpan data countb',
				];
				echo json_encode($jsonmsg);
				exit;
			}
		}

		// Menyelesaikan transaksi
		$this->db->trans_complete();

		if ($this->db->trans_status() == FALSE) {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Terjadi kesalahan, data tidak tersimpan.',
			];
			echo json_encode($jsonmsg);
		} else {
			$jsonmsg = [
				'hasil' => 'true',
				'pesan' => 'Data saved successfully.',
			];
			echo json_encode($jsonmsg);
		}
	}

	public function update()
	{
		// Ambil data dari POST request
		$uuid        = $this->input->post('uuid');
		$code_budget = $this->input->post('kode_budget');
		$nama_budget = $this->input->post('nama_budget');
		$alias_post  = $this->input->post('alias');
		// Cek apakah UUID Budget ada di database
		$data =  $this->M_budget->get_where_budget(['a.uuid' => $uuid])->row();
		if ($data != null) {
			$code_company = $data->code_company;
			$cek_cost_center =  $this->M_global->getWhere('cost_centers', ['code_budget' => $data->code_budget])->num_rows();
			if ($cek_cost_center != 0) {
				$jsonmsg = [
					'hasil' => 'false',
					'pesan' => 'Tidak bisa mengubah Data Budget karena sedang digunakan di cost centers.',
				];
				echo json_encode($jsonmsg);
				exit;
			}
			if ($data->code_budget != $code_budget) {
				$cekkode =  $this->M_budget->get_where_budget(['a.code_budget' => $code_budget, "a.code_company" => $code_company])->num_rows();
				if ($cekkode != 0) {
					$jsonmsg = [
						'hasil' => 'false',
						'pesan' => 'Kode Depo sudah terdaftar',
					];
					echo json_encode($jsonmsg);
					exit;
				}
			}
			if ($data->name != $nama_budget) {
				$param_nama = ['a.name' => $nama_budget, "a.code_company" => $code_company];
				$ceknama =  $this->M_budget->get_where_budget($param_nama)->num_rows();
				if ($ceknama !== 0) {
					$jsonmsg = [
						'hasil' => 'false',
						'pesan' => 'Nama sudah terdaftar',
					];
					echo json_encode($jsonmsg);
					exit;
				}
			}
			if ($data->alias !== $alias_post) {
				$cekalias =  $this->M_budget->get_where_budget(['a.alias' => $alias_post])->num_rows();
				if ($cekalias !== 0) {
					$jsonmsg = [
						'hasil' => 'false',
						'pesan' => 'Alias sudah terdaftar',
					];
					echo json_encode($jsonmsg);
					exit;
				}
			}
			$dataupdate = [
				'uuid'         => $this->uuid->v4(),
				'code_budget'  => $code_budget,
				'name'         => $nama_budget,
				'alias'        => $alias_post,
				'updated_at'   => date('Y-m-d H:i:s')
			];
			// Melakukan update data
			$update = $this->M_global->update($dataupdate, 'budgetons', ['uuid' => $uuid]);
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
			// Jika UUID Budget tidak ditemukan
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'UUID Budget tidak ditemukan',
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
		$budget = $this->M_budget->get_where_budget($param_kode)->row();
		// Jika data tidak ditemukan
		if (!$budget) {
			$this->db->trans_rollback();
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Data tidak ditemukan'
			]);
			return;
		}
		$cek_cc = $this->M_global->getWhere('cost_centers', ['code_budget' => $budget->code_budget])->num_rows();
		if ($cek_cc != 0) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Tidak bisa Menghapus Data, karena sedang digunakan di cost centers.',
			]);
			return;
		}
		$this->db->trans_begin();
		try {
			// Ambil data budget berdasarkan UUID

			// Lakukan penghapusan data di tabel budgetons
			$this->db->where('uuid', $uuid)->delete('budgetons');
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

	public function Coa_expense()
	{
		$get_value = $this->input->get('cari');
		$code_company = $this->input->get('code_company');
		$cari = preg_replace("/[^a-zA-Z0-9]/", '', $get_value);
		$hasil = $this->M_budget->get_coa_expense($cari, $code_company);
		echo json_encode($hasil);
	}

	public function detailform($uuid)
	{
		$cekdata =  $this->M_budget->get_where_budget(['a.uuid' => $uuid])->row();
		if ($cekdata != null) {
			$code         = $cekdata->code_budgeting;
			$code_company = $cekdata->code_company;
			$project      = $this->M_budget->get_project($code, $code_company);
			$summary      = $this->M_budget->get_detail_summary($code, $code_company);
			$param = [
				"code_budgeting" => $code,
				"code_company" => $code_company,
			];
			$data['verify']      = $this->M_global->getWhere("budgeting_verify", $param)->row_array();
			$data_project = [];
			if(!empty($project)){
				foreach ($project as $row){
					$data_project[] = [
						'code_budgeting'  => $row['code_budgeting'],
						'project_item'    => $row['project_item'],
						'project_name'    => $row['project_name'],
						'goal_project'    => $row['goal_project'],
						'project_desc'    => $row['project_desc'],
						'budget_proposal' => $row['budget_proposal'],
						'filename'        => $row['filename'],
						'item_opex'     => $this->M_budget->get_project_items($code, $row['project_item'], "OPEX", $code_company),
						'item_capex'    => $this->M_budget->get_project_items($code, $row['project_item'], "CAPEX", $code_company),
					];
				}
			}
			$data['judul']          = "Detail Budget (". $code.")";
			$data['load_back']      = "C_budget/detailform/" . $uuid;
			$data['load_grid']      = 'C_budget';
			$data['code']           = $code;
			$data['uuid']           = $uuid;
			$data['data']           = $cekdata;
			$data['summary']        = $summary;
			$data['data_project']   = $data_project;
			$this->load->view("v_budget/detail_budget", $data);
		} else {
			$this->load->view('error');
		}
	}

	// ===============  menu verify / histori ==========
	public function verify()
	{
		$this->form_validation->set_rules('status_budgeting', 'Perusahaan', 'required');
		$this->form_validation->set_rules('deskripsi_verify', 'Code Divisi', 'required');
		if ($this->form_validation->run() == FALSE) {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => validation_errors(),
			];
			echo json_encode($jsonmsg);
			return;
		}
		$uuid             = $this->input->post('uuid');
		$status_budgeting = $this->input->post('status_budgeting');
		$deskripsi_verify = $this->input->post('deskripsi_verify');
		$data = $this->M_global->getWhere('budgeting_headers', ['uuid' => $uuid])->row_array();
		if ($data == null) {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'ID Budgeting tidak di temukan',
			];
			echo json_encode($jsonmsg);
			exit;
		}
		$code_budgeting = $data['code_budgeting'];
		$code_company   = $data['code_company'];
		$param = [
			"code_budgeting" => $code_budgeting,
			"code_company"   => $code_company,
		];
		$cek_veryfy = $this->M_global->getWhere('budgeting_verify', $param)->num_rows();
		if($cek_veryfy == 0){
			$datainsert = [
				'uuid'               => $this->uuid->v4(),
				'code_budgeting'     => $code_budgeting,
				'code_company'       => $code_company,
				'status_budgeting'   => $status_budgeting,
				'verification_date'  => date('Y-m-d H:i:s'),
				'verification_notes' => $deskripsi_verify,
				'user_created'       => $this->session->userdata('sess_username'),
			];
			// Melakukan insert data
			$save = $this->M_global->insert($datainsert, 'budgeting_verify');
			$dataupdate = [
				'status_budgeting'  => $status_budgeting,
			];
			// Melakukan update data
			$update = $this->M_global->update($dataupdate, 'budgeting_headers', $param);
			if ($update == "TRUE" &&  $save == "TRUE") {
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
		}else{
			$jsonmsg = [
			'hasil' => 'false',
			'pesan' => 'Verify Sudah Di lakukan',
			];
			echo json_encode($jsonmsg);
		}
	}
	function index_verify()
	{
		$data['judul']      = 'Budget Verification History';
		$data['load_grid']  = 'C_budget';
		$data['load_add']   = 'C_budget/add';
		$data['url_delete'] = 'C_budget/delete';
		$this->load->view("v_budget/grid_budget_verify", $data);
	}

	public function griddata_verify()
	{
		$start        = $this->input->post('start') ?? 0;
		$length       = $this->input->post('length') ?? 10;
		$search_input = $this->input->post('search');
		$draw         = intval($this->input->post('draw')) ?? 1;
		$search       = isset($search_input['value']) ? $search_input['value'] : '';

		// Dapatkan parameter order yang dikirim oleh DataTable
		$order_input    = $this->input->post('order');
		$order_col      = isset($order_input[0]['column']) ? $order_input[0]['column'] : 0;
		if (isset($order_input[0]['dir'])) {
			if ($draw % 2 == 0) {
				$dir = 'asc'; // Jika draw genap
			} else {
				$dir = 'desc'; // Jika draw ganjil
			}
		} else {
			$dir = 'desc';
		}
		// Kolom yang dapat diurutkan
		if ($draw == 1) {
			$columns        = ['a.code_budgeting'];
		} else {
			$columns        = ['a.code_company', 'a.code_budgeting'];
		}
		// Tentukan kolom mana yang akan diurutkan berdasarkan order yang diterima
		$order_by       = $columns[$order_col] ?? 'id';
		$data           = $this->M_budget->get_paginated_budget_verify($length, $start, $search, $order_by, $dir);
		$total_records  = $this->M_budget->count_all_budget_verify();
		$total_filtered = $this->M_budget->count_filtered_budget_verify($search);
		$url_detail       = 'C_budget/detailform';
		$result = [];
		foreach ($data as $row) {
			$aksi = '<div class="dropdown">
				<button type="button" class="btn btn-white btn-sm" id="aksi-dropdown-' . $row->code_budgeting . '" data-bs-toggle="dropdown" aria-expanded="false">
					More <i class="bi-chevron-down ms-1"></i>
				</button>
				<div class="dropdown-menu dropdown-menu-sm dropdown-menu-end" aria-labelledby="aksi-dropdown-' . $row->code_budgeting . '">
					<button class="dropdown-item editbtn" onclick="editform(\'' . $url_detail . '\', \'' . $row->uuid_header . '\')">
						<i class="bi bi-eye"></i> Detail
					</button>
				</div>
			</div>';
			if ($row->status_budgeting == 'APPROVED') {
				$class = 'bg-success';
			} else if ($row->status_budgeting == 'REJECT') {
				$class = 'bg-danger';
			}
			$ststus = '<span class="badge ' . $class . '">' . strtolower($row->status_budgeting) . '</span>';
			$result[] = [
				$row->code_company . ' - ' . $row->company_name,
				$row->code_budgeting,
				$ststus,
				$aksi,
			];
		}
		echo json_encode([
			"draw"            => $draw,
			"recordsTotal"    => $total_records,
			"recordsFiltered" => $total_filtered,
			"data"            => $result,
		]);
	}

	public function download($filename)
	{
		$this->load->helper('download');
		$file_path = FCPATH . 'uploads/' . $filename;
		if (file_exists($file_path)) {
			force_download($file_path, NULL);
		} else {
			$this->load->view("error_landingpage");
		}
	}
}
