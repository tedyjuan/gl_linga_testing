<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_chart_of_account extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model('M_chart_of_account');
		$this->load->model('M_global');
	}
	function index()
	{
		$data['judul']      = 'Chart Of Account';
		$data['load_grid']  = 'C_chart_of_account';
		$data['load_add']   = 'C_chart_of_account/add';
		$data['url_delete'] = 'C_chart_of_account/delete';
		$this->load->view("v_chart_of_account/grid_coa", $data);
	}
	public function griddata()
	{
		// Catat waktu mulai
		$start          = $this->input->post('start') ?? 0;
		$length         = $this->input->post('length') ?? 10;
		$search_input   = $this->input->post('search');
		$search         = isset($search_input['value']) ? $search_input['value'] : '';
		$order_input    = $this->input->post('order');
		$order_col      = isset($order_input[0]['column']) ? $order_input[0]['column'] : 0;
		$dir            = isset($order_input[0]['dir']) ? $order_input[0]['dir'] : 'asc';
		$columns        = ['code_company', 'account_number', 'name', 'account_type','action'];
		$order_by       = $columns[$order_col] ?? 'name';
		$data           = $this->M_chart_of_account->get_paginated_chart_of_account($length, $start, $search, $order_by, $dir);
		$total_records  = $this->M_chart_of_account->count_all_chart_of_account();
		$total_filtered = $this->M_chart_of_account->count_filtered_chart_of_account($search);
		$url_add_ledger     = 'C_chart_of_account/add_ledger';
		$url_add_subledger  = 'C_chart_of_account/add_subledger';
		$url_edit           = 'C_chart_of_account/editform';
		$url_delete         = 'C_chart_of_account/hapusdata';
		$load_grid          = 'C_chart_of_account/griddata';
		$url_edit_ledger    = 'C_chart_of_account/editform_ledger';
		$url_edit_subledger = 'C_chart_of_account/editform_subledger';
		$result = [];
		foreach ($data as $row) {
			$spacing = '';
			$aksi = '<div class="dropdown">
					<button type="button" class="btn btn-white btn-sm" id="aksi-dropdown-' . $row->account_number . '" data-bs-toggle="dropdown" aria-expanded="false">
						More <i class="bi-chevron-down ms-1"></i>
					</button>
					<div class="dropdown-menu dropdown-menu-sm dropdown-menu-end" aria-labelledby="aksi-dropdown-' . $row->account_number . '">';
			if ($row->account_category == 'header') {
				$aksi .= '
						<button class="dropdown-item" onclick="add_data(\'' . $url_add_ledger . '\', \'' . $row->uuid . '\')">
							<i class="bi bi-plus-circle"></i> Tambah Ledger
						</button>
						<button class="dropdown-item editbtn" onclick="editform(\'' . $url_edit . '\', \'' . $row->uuid . '\')">
							<i class="bi bi-pen"></i> Edit header
						  </button>
						  ';
			}
			if ($row->account_category == 'ledger') {
				$spacing = '&nbsp;&nbsp;&nbsp;&nbsp;'; 
				$aksi .= '<button class="dropdown-item" onclick="add_data(\'' . $url_add_subledger . '\', \'' . $row->uuid . '\')">
								<i class="bi bi-plus-circle"></i> Tambah Subledger
							</button>
							<button class="dropdown-item " onclick="editform(\'' . $url_edit_ledger . '\', \'' . $row->uuid . '\')">
								<i class="bi bi-pen"></i> Edit Ledger
							</button>';
			} 
			if ($row->account_category == 'subledger' ) {
				$spacing = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';  
				$aksi .= '<button class="dropdown-item " onclick="editform(\'' . $url_edit_subledger . '\', \'' . $row->uuid . '\')">
								<i class="bi bi-pen"></i> Edit Sub-Ledger
							</button>';
			}

			// Tombol "Delete"
			$aksi .= '<div class="dropdown-divider"></div>
			<button class="dropdown-item text-danger" onclick="hapus(\'' . $row->uuid . '\', \'' . $url_delete . '\', \'' . $load_grid . '\')">
                  <i class="bi bi-trash3"></i> Delete
              </button>';

			$aksi .= '</div></div>';
			$result[] = [
				$row->code_company,
				$spacing.$row->account_number,
				$row->name,
				$row->account_type,
				$aksi,
			];
		}
		echo json_encode([
			"draw" => intval($this->input->post('draw')) ?? 1,
			"recordsTotal" => $total_records,
			"recordsFiltered" => $total_filtered,
			"data" => $result,
			
		]);
	}
	
	public function depoData()
	{
		$company = $this->input->post('company_id');
		$data   = $this->M_chart_of_account->get_depo($company)->result_array();
		echo json_encode(['data' => $data]);
	}
	public function costCenterData()
	{
		$company = $this->input->post('company_id');
		$data = $this->M_global->getWhere('cost_centers', ['code_company' => $company])->result_array();
		echo json_encode(['data' => $data]);
	}
	function add()
	{
		$data['judul']     = "Form Tambah Header COA";
		$data['load_back'] = 'C_chart_of_account/add';
		$data['load_grid'] = 'C_chart_of_account';
		$this->load->view("v_chart_of_account/add_header_coa", $data);
	}
	
	public function get_tbag1(){
		$code_company = $this->input->post('code_company');
		$akun_type    = $this->input->post('akun_type');
		$tbag1 = $this->M_chart_of_account->get_tbag1Bycompany($code_company, $akun_type);
		echo json_encode($tbag1);
	}
	 public function get_tbag2(){
		$code_company = $this->input->post('code_company');
		$tbag1 = $this->input->post('tbag1');
		$tbag2 = $this->M_chart_of_account->get_tbag2Bycompany($code_company, $tbag1);
		echo json_encode($tbag2);
	}
	 public function get_tbag3(){
		$code_company = $this->input->post('code_company');
		$tbag2 = $this->input->post('tbag2');
		$tbag3 = $this->M_chart_of_account->get_tbag3Bycompany($code_company, $tbag2);
		echo json_encode($tbag3);
	}
	//@ ==================================================
	//@ ==================================================
	//@ ================= HEADER =========================
	public function simpandata()
	{
		// Validasi input
		$this->form_validation->set_rules('perusahaan', 'Perusahaan', 'required');
		$this->form_validation->set_rules('no_akun', 'No Chart Of Account', 'required');
		$this->form_validation->set_rules('nama_akun', 'Nama Chart Of Account', 'required');
		$this->form_validation->set_rules('akun_dc', 'Metode coa', 'required');
		$this->form_validation->set_rules('akun_group', 'Akun group', 'required');
		$this->form_validation->set_rules('akun_type', 'Akun Type', 'required');
		if ($this->form_validation->run() == FALSE) {
			// Jika validasi gagal
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => validation_errors(),
			];
			echo json_encode($jsonmsg);
			return;
		}
		$perusahaan = $this->input->post('perusahaan');
		$no_akun    = $this->input->post('no_akun');
		$nama_akun  = $this->input->post('nama_akun');
		$akun_dc    = $this->input->post('akun_dc');
		$akun_group = $this->input->post('akun_group');
		$akun_type  = $this->input->post('akun_type');
		$tbag1      = $this->input->post('tbag1');
		$tbag2      = $this->input->post('tbag2');
		$tbag3      = $this->input->post('tbag3');
		$deskripsi  = $this->input->post('deskripsi');
		// Cek apakah kode Chart Of Account sudah ada
		$param_kode =[
			'account_number'  => $no_akun,
			'code_company'  => $perusahaan,
		];
		$exisCode = $this->M_global->getWhere('chart_of_accounts', $param_kode)->num_rows();
		if ($exisCode != null) {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Code Chart Of Account already exist',
			];
			echo json_encode($jsonmsg);
			exit;
		}
		$param_nama = [
			'name'         => $nama_akun,
			'code_company' => $perusahaan,
		];
		$exisName = $this->M_global->getWhere('chart_of_accounts', $param_nama)->num_rows();
		if ($exisName != null) {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Name Chart Of Account already exist',
			];
			echo json_encode($jsonmsg);
			exit;
		} 
		// Data untuk insert ke database
		// kalau 4 = unit 
		$datainsert = [
			'uuid'               => $this->uuid->v4(),
			'account_number'     => $no_akun,
			'code_company'       => $perusahaan,
			'name'               => $nama_akun,
			'description'        => $deskripsi,
			'account_type'       => $akun_type,
			'account_method'     => $akun_dc,
			'account_group'      => $akun_group,
			'account_category'   => 'header',
			'code_trialbalance1' => $tbag1,
			'code_trialbalance2' => $tbag2,
			'code_trialbalance3' => $tbag3,
			'created_at'         => date('Y-m-d H:i:s'),
			'updated_at'         => date('Y-m-d H:i:s')
		];
		// Melakukan insert data
		$this->db->insert('chart_of_accounts', $datainsert);
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
		$cekdata =  $this->M_chart_of_account->get_where_chart_of_account(['a.uuid' => $uuid])->row();
		if ($cekdata != null) {
			$code_company = $cekdata->code_company;
			$akun_type    = $cekdata->account_type;
			$tbag1        = $cekdata->code_trialbalance1;
			$tbag2        = $cekdata->code_trialbalance2;
			$data['data']         = $cekdata;
			$data['uuid']         = $uuid;
			$data['code_company'] = $code_company;
			$data['judul']        = "Form Edit Header COA";
			$data['load_grid']    = "C_chart_of_account";
			$data['load_refresh'] = "C_chart_of_account/editform/" . $uuid;
			$data['type_akun']    = $this->M_chart_of_account->get_tbag1Bycompany($code_company);
			$data['tbag1List']    = $this->M_chart_of_account->get_tbag1Bycompany($code_company, $akun_type);
			$data['tbag2List']    = $this->M_global->getWhere("trial_balance_account_group_2", ['code_company' => $code_company, 'code_trialbalance1' => $tbag1 ])->result();
			$data['tbag3List']    = $this->M_global->getWhere("trial_balance_account_group_3", ['code_company' => $code_company, 'code_trialbalance2' => $tbag2])->result();
		
			$this->load->view('v_chart_of_account/edit_header_coa', $data);
		} else {
			$this->load->view('error');
		}
	}
	public function update()
	{
		// Ambil data dari POST request
		$uuid       = $this->input->post('uuid');
		$no_akun    = $this->input->post('no_akun');
		$nama_akun  = $this->input->post('nama_akun');
		$akun_dc    = $this->input->post('akun_dc');
		$akun_group = $this->input->post('akun_group');
		$akun_type  = $this->input->post('akun_type');
		$tbag1      = $this->input->post('tbag1');
		$tbag2      = $this->input->post('tbag2');
		$tbag3      = $this->input->post('tbag3');
		$deskripsi  = $this->input->post('deskripsi');

		// Cek apakah kode Chart Of Account sudah ada
		$exisCoa = $this->M_global->getWhere('chart_of_accounts', ['uuid' => $uuid])->row();

		if ($exisCoa != null) {
			// Mengecek apakah nomor akun sudah ada
			if ($exisCoa->account_number != $no_akun) {
				$param_kode = [
					'account_number'  => $no_akun,
					'code_company'  => $exisCoa->code_company,
				];
				$exisCode = $this->M_global->getWhere('chart_of_accounts', $param_kode)->num_rows();
				if ($exisCode != 0) {
					// Rollback transaksi jika kode sudah ada
					$this->db->trans_rollback();
					$jsonmsg = [
						'hasil' => 'false',
						'pesan' => 'Code Chart Of Account already exist',
					];
					echo json_encode($jsonmsg);
					exit;
				}
			}

			// Mengecek apakah nama akun sudah ada
			if ($exisCoa->name != $nama_akun) {
				$param_nama = [
					'name'         => $nama_akun,
					'code_company' => $exisCoa->code_company,
				];
				$exisName = $this->M_global->getWhere('chart_of_accounts', $param_nama)->num_rows();
				if ($exisName != null) {
					// Rollback transaksi jika nama sudah ada
					$this->db->trans_rollback();
					$jsonmsg = [
						'hasil' => 'false',
						'pesan' => 'Name Chart Of Account already exist',
					];
					echo json_encode($jsonmsg);
					exit;
				}
			}
			// Data untuk update
			$dataupdate = [
				'name'               => $nama_akun,
				'description'        => $deskripsi,
				'account_type'       => $akun_type,
				'account_method'     => $akun_dc,
				'account_group'      => $akun_group,
				'cost_center_type'   => 'unit',
				'code_trialbalance1' => $tbag1,
				'code_trialbalance2' => $tbag2,
				'code_trialbalance3' => $tbag3,
				'created_at'         => date('Y-m-d H:i:s'),
				'updated_at'         => date('Y-m-d H:i:s')
			];

			// Melakukan update data
			$updatedb =$this->M_global->update($dataupdate, 'chart_of_accounts', ['uuid' => $uuid]);

			if ($updatedb == TRUE) {
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
			// Rollback transaksi jika UUID tidak terdaftar
			$this->db->trans_rollback();
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'UUID not registered'
			]);
			return;
		}

		// Menampilkan hasil
	}

	public function hapusdata()
	{
		$uuid = $this->input->post('uuid');
		if (empty($uuid)) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'UUID cannot be empty'
			]);
			exit;
		}
		$param_kode = ['a.uuid' => $uuid];
		$Cek_coa = $this->M_chart_of_account->get_where_chart_of_account($param_kode)->row();
		if (!$Cek_coa) {
			// Jika data tidak ditemukan
			$this->db->trans_rollback();
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Data not found'
			]);
			exit;
		}
		
		$this->db->trans_begin();
		try {
			//@ note:
			//@ jika header maka cek apakah memiliki ledger
			//@ jika edger maka cek apakah memiliki sub-ledger
			$kategori       = $Cek_coa->account_category;
			$code_company   = $Cek_coa->code_company;
			$account_number = $Cek_coa->account_number;
			$param_cheking  = [];
			if($kategori == 'header'){
				$param_cheking = [
					'a.code_header' => $account_number, // 1000
					'a.code_company' => $code_company,
				];
				$pesan = 'Failed to delete, COA No: ' . $account_number . ' still has Ledger accounts.';
				
			}
			if ($kategori == 'ledger') {
				$param_cheking = [
					'a.code_ledger' => $account_number, // 100010
					'a.code_company' => $code_company,
				];
				$pesan = 'Failed to delete, COA No: ' . $account_number . ' still has sub-Ledger accounts.';
			}
			$cek_ledger = $this->M_chart_of_account->get_where_chart_of_account($param_cheking)->num_rows();
			if ($cek_ledger != 0) {
				$jsonmsg = [
					'hasil' => 'false',
					'pesan' => $pesan,
				];
				echo json_encode($jsonmsg);
				exit;
			}

			$categori_coa = $Cek_coa->account_category;
			if($categori_coa != 'header'){
				$this->M_chart_of_account->hapus_cc($uuid);
			}
			// Lakukan penghapusan data di tabel C_chart_of_accountons
			$this->db->where('uuid', $uuid)->delete('chart_of_accounts');
			if ($this->db->affected_rows() <= 0) {
				$this->db->trans_rollback();
				echo json_encode([
					'hasil' => 'false',
					'pesan' => 'Failed to delete data or data not found'
				]);
				exit;
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
				'pesan' => 'An error occurred: ' . $e->getMessage()
			]);
		}
	}


	//@ ==================================================
	//@ ==================================================
	//@ ================= LEDGER =========================
	function add_ledger($uuid)
	{
		$cekdata =  $this->M_chart_of_account->get_coa(['a.uuid' => $uuid])->row();
		if ($cekdata != null) {

			$data['data']          = $cekdata;
			$data['uuid']          = $uuid;
			$data['judul']         = "Form Tambah Ledger COA";
			$data['load_grid']     = "C_chart_of_account";
			$data['load_refresh']  = "C_chart_of_account/add_ledger/" . $uuid;
			$this->load->view('v_chart_of_account/add_ledger_coa', $data);
		} else {
			$this->load->view('error');
		}
	}
	public function griddata_depo()
	{
		$company_id     = $this->input->post('company_id');
		$start          = $this->input->post('start');
		$length         = $this->input->post('length');
		$search         = $this->input->post('search')['value'];
		$order          = $this->input->post('order')[0]['column'];
		$dir            = $this->input->post('order')[0]['dir'];
		$order_by       = ['code_depo', 'name', 'action'][$order];
		$data           = $this->M_chart_of_account->get_paginated_depos($length, $start, $search, $order_by, $dir, $company_id);
		$total_records  = $this->M_chart_of_account->count_all_depos($company_id);
		$total_filtered = $this->M_chart_of_account->count_filtered_depos($search, $company_id);
		$result = [];
		foreach ($data as $key => $row) {
			$depo = $row->code_depo . ' - ' . $row->name;
			$aksi = '<input type="radio" onclick="pilihdepo(\'' . $row->code_depo . '\', \'' . $row->name . '\')" class="form-check-input" id="radio' . $key . '">';
			$result[] = [
				$aksi,
				$depo,
			];
		}

		// Output JSON ke DataTables
		echo json_encode([
			"draw"            => intval($this->input->post('draw')),
			"recordsTotal"    => $total_records,
			"recordsFiltered" => $total_filtered,
			"data"            => $result,
			"company_id"            => $company_id,
		]);
	}
	public function griddata_cc()
	{
		$company_id     = $this->input->post('company_id');
		$start    = $this->input->post('start');
		$length   = $this->input->post('length');
		$search   = $this->input->post('search')['value'];
		$order    = $this->input->post('order')[0]['column'];
		$dir      = $this->input->post('order')[0]['dir'];
		$order_by = ['group_team', 'action'][$order];
		$data           = $this->M_chart_of_account->get_paginated_cost_center($length, $start, $search, $order_by, $dir, $company_id);
		$total_records  = $this->M_chart_of_account->count_all_cost_center($company_id);
		$total_filtered = $this->M_chart_of_account->count_filtered_cost_center($search, $company_id);
		$result = [];
		foreach ($data as $key => $row) {
			$group = '(' . $row->code_cost_center . ') ' . $row->group_team;
			$aksi = '<input type="radio"  onclick="pilihsatuan(\'' . $group . '\')" class="form-check-input" id="radio' . $key . '">';
			$result[] = [
				$aksi,
				$group,
			];
		}
		echo json_encode([
			"draw" => intval($this->input->post('draw')) ?? 1,
			"recordsTotal" => $total_records,
			"recordsFiltered" => $total_filtered,
			"data" => $result
		]);
	}
	public function simpanLedger()
	{
		$this->form_validation->set_rules('id_number_ledger', 'Number ledger', 'required');
		$this->form_validation->set_rules('nama_akun_ledger', 'Nama Ledger COA', 'required');
		$this->form_validation->set_rules('deskripsi', 'Deskripsi COA', 'required');
		$this->form_validation->set_rules('uuid', 'uuid header coa ', 'required');
		if ($this->form_validation->run() == FALSE) {
			// Jika validasi gagal
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => validation_errors(),
			];
			echo json_encode($jsonmsg);
			return;
		}
		
		$uuid             = $this->input->post('uuid');
		$cekcoa = $this->M_global->getWhere('chart_of_accounts', ['uuid' => $uuid])->row();
		if($cekcoa != null){
			$post_cc_start      = $this->input->post('cc_start');
			$post_cc_end        = $this->input->post('cc_end');
			$cc_depo            = $this->input->post('cc_kode_depo');
			$id_number_ledger   = $this->input->post('id_number_ledger');
			$nama_akun_ledger   = $this->input->post('nama_akun_ledger');
			$deskripsi          = $this->input->post('deskripsi');
			$account_number     = $cekcoa->account_number;
			$code_company       = $cekcoa->code_company;
			$account_type       = $cekcoa->account_type;
			$account_method     = $cekcoa->account_method;
			$account_group      = $cekcoa->account_group;
			$code_trialbalance1 = $cekcoa->code_trialbalance1;
			$code_trialbalance2 = $cekcoa->code_trialbalance2;
			$code_trialbalance3 = $cekcoa->code_trialbalance3;
			$noakun_ledger      = $account_number. $id_number_ledger;
			$param = [
				'account_number' => $noakun_ledger,
				'code_company'   => $code_company,
			];
			$cekledger = $this->M_global->getWhere('chart_of_accounts', $param)->num_rows();
			if($cekledger != 0){
				$jsonmsg = [
					'hasil' => 'false',
					'pesan' => 'Ledger No already in use in company ' . $code_company,
				];
				echo json_encode($jsonmsg);
				exit;
			}


			// === TRANSAKSI DB ===
			$this->db->trans_strict(TRUE);
			$this->db->trans_begin();
			$data_cc_res = [];
			if($post_cc_start != '' && $post_cc_end != ''){
				$cost_center_type = 'unit';
				preg_match('/\((\d+)\)/', $post_cc_start, $start_match);
				preg_match('/\((\d+)\)/', $post_cc_end, $end_match);
				$data_cc_awal = '';
				$data_cc_akhir = '';
				// Cek apakah pencocokan berhasil
				if (isset($start_match[1])) {
					$data_cc_awal = $start_match[1];
				}

				if (isset($end_match[1])) {
					$data_cc_akhir = $end_match[1];
				}
				$this->load->model('M_cost_center');
				$data_cc_res = $this->M_cost_center->getCC_Start_End_ByCompany($data_cc_awal, $data_cc_akhir, $code_company);
			}else{
				$cost_center_type = 'depo';
				$where_cc = [
					'code_depo'    => $cc_depo,
					'code_company' => $code_company,
				];
				$data_cc_res = $this->M_global->getWhere('cost_centers', $where_cc)->result();
			}
			$new_data_pb = [];
			$data_cc = [];
			if (!empty($data_cc_res)) {
				foreach ($data_cc_res as $row) {
					$data_cc[] = [
						'uuid'         => $this->uuid->v4(),
						'code_coa'     => (int)$noakun_ledger,
						'code_cc'      => $row->code_cost_center,
						'code_company' => $code_company,
						'created_at'   => date('Y-m-d H:i:s'),
						'updated_at'   => date('Y-m-d H:i:s'),
					];
					for ($i = 1; $i <= 12; $i++) {
						$new_data_pb[] = [
							'uuid'             => $this->uuid->v4(),
							'code_company'     => $code_company,
							'code_depo'        => $row->code_depo,
							'year'             => date('Y'),
							'period'           => $i,
							'code_cost_center' => $row->code_cost_center,
							'code_coa'         => (int)$noakun_ledger,
							'opening_balance'  => 0,
							'debit'            => 0,
							'credit'           => 0,
							'created_at'   => date('Y-m-d H:i:s'),
							'updated_at'   => date('Y-m-d H:i:s'),
						];
					}
	
				}
				$this->db->insert_batch('posting_balances', $new_data_pb);
				$this->db->insert_batch('account_centers', $data_cc);
			}
			
			// Melakukan insert data
			$datainsert = [
				'uuid'               => $this->uuid->v4(),
				'account_number'     => (int)$noakun_ledger,
				'name'               => $nama_akun_ledger,
				'code_company'       => $code_company,
				'description'        => $deskripsi,
				'account_type'       => $account_type,
				'account_method'     => $account_method,
				'account_group'      => $account_group,
				'cost_center_type'   => $cost_center_type,
				'code_header'        => $account_number,
				'account_category'   => 'ledger',
				'code_trialbalance1' => $code_trialbalance1,
				'code_trialbalance2' => $code_trialbalance2,
				'code_trialbalance3' => $code_trialbalance3,
				'created_at'         => date('Y-m-d H:i:s'),
				'updated_at'         => date('Y-m-d H:i:s')
			];
			
			$this->db->insert('chart_of_accounts', $datainsert);
			if ($this->db->trans_status() === FALSE || $this->db->affected_rows() <= 0) {
				$jsonmsg = [
					'hasil' => 'false',
					'pesan' => 'failed to save data',
				];
				echo json_encode($jsonmsg);
				exit;
			}
			$this->db->trans_commit();
			echo json_encode([
				'hasil' => 'true',
				'pesan' => 'Data successfully saved',
			]);
			return;
		}else{
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'ID-COA data not found',
			];
			echo json_encode($jsonmsg);
		}
		
	}
	public function updateLedger()
	{
		$this->form_validation->set_rules('nama_akun_ledger', 'Nama Ledger COA', 'required');
		$this->form_validation->set_rules('deskripsi', 'Deskripsi COA', 'required');
		$this->form_validation->set_rules('uuid', 'uuid header coa ', 'required');
		if ($this->form_validation->run() == FALSE) {
			// Jika validasi gagal
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => validation_errors(),
			];
			echo json_encode($jsonmsg);
			return;
		}
		
		$uuid             = $this->input->post('uuid');
		$cekcoa = $this->M_global->getWhere('chart_of_accounts', ['uuid' => $uuid])->row();
		if($cekcoa != null){
			$post_cc_start      = $this->input->post('cc_start');
			$post_cc_end        = $this->input->post('cc_end');
			$cc_depo            = $this->input->post('cc_kode_depo');
			$nama_akun_ledger   = $this->input->post('nama_akun_ledger');
			$deskripsi          = $this->input->post('deskripsi');
			$account_number     = $cekcoa->account_number;
			$code_company       = $cekcoa->code_company;
		
			// === TRANSAKSI DB ===
			$this->db->trans_strict(TRUE);
			$this->db->trans_begin();
			$this->M_chart_of_account->hapus_cc($uuid);
			if($post_cc_start != '' && $post_cc_end != ''){
				$cost_center_type = 'unit';
				preg_match('/\((\d+)\)/', $post_cc_start, $start_match);
				preg_match('/\((\d+)\)/', $post_cc_end, $end_match);
				$data_cc_awal = '';
				$data_cc_akhir = '';
				// Cek apakah pencocokan berhasil
				if (isset($start_match[1])) {
					$data_cc_awal = $start_match[1];
				}

				if (isset($end_match[1])) {
					$data_cc_akhir = $end_match[1];
				}
				$this->load->model('M_cost_center');
				$data_cc_res = $this->M_cost_center->getCC_Start_End_ByCompany($data_cc_awal, $data_cc_akhir, $code_company);
			}else{
				$cost_center_type = 'depo';
				$where_cc = [
					'code_depo'    => $cc_depo,
					'code_company' => $code_company,
				];
				$data_cc_res = $this->M_global->getWhere('cost_centers', $where_cc)->result();
			}
			if (!empty($data_cc_res)) {
				foreach ($data_cc_res as $row) {
					$data_cc[] = [
						'uuid'         => $this->uuid->v4(),
						'code_coa'     => $account_number,
						'code_cc'      => $row->code_cost_center,
						'code_company' => $code_company
					];
				}
				$this->db->insert_batch('account_centers', $data_cc);
			}
			
			$update_ledger = [
				'name'               => $nama_akun_ledger,
				'description'        => $deskripsi,
				'cost_center_type'   => $cost_center_type,
				'updated_at'         => date('Y-m-d H:i:s')
			];
			
			$updatedb = $this->M_global->update($update_ledger, 'chart_of_accounts', ['uuid' => $uuid]);

			if ($updatedb === FALSE) {
				$jsonmsg = [
					'hasil' => 'false',
					'pesan' => 'failed to save data',
				];
				echo json_encode($jsonmsg);
				exit;
			}
			$this->db->trans_commit();
			echo json_encode([
				'hasil' => 'true',
				'pesan' => 'Data successfully updated',
			]);
			return;
		}else{
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'ID-COA data not found',
			];
			echo json_encode($jsonmsg);
		}
		
	}
	
	 public function editform_ledger($uuid){
		$data_ledger =  $this->M_chart_of_account->get_coa(['a.uuid' => $uuid])->row();
		if ($data_ledger != null) {
			$cc_type = $data_ledger->cost_center_type;
			$code_company = $data_ledger->code_company;
			if($cc_type == 'unit'){
				$data['min'] = $this->M_chart_of_account->min_max_cc($uuid, "min");
				$data['max'] = $this->M_chart_of_account->min_max_cc($uuid, "max");
				$data['depo'] = '';
			}else{
				$data['min'] = '';
				$data['max'] = '';
				$data['depo'] = $this->M_chart_of_account->get_depo_details($uuid, $code_company);
			}
			$params = [
				'a.account_number' => $data_ledger->code_header,
				'a.code_company' => $code_company
			];
			$data_header =  $this->M_chart_of_account->get_coa($params)->row();
			$data['data']         = $data_header;
			$data['data_ledger']  = $data_ledger;
			$data['uuid']         = $uuid;
			$data['judul']        = "Form Edit Ledger COA";
			$data['load_grid']    = "C_chart_of_account";
			$data['load_refresh'] = "C_chart_of_account/editform_ledger/" . $uuid;
			$this->load->view('v_chart_of_account/edit_ledger_coa', $data);
		} else {
			$this->load->view('error');
		}
	 }
	//@ ==================================================
	//@ ==================================================
	//@ ================= SUB-LEDGER =========================
	function add_subledger($uuid)
	{
		$cekdata =  $this->M_chart_of_account->get_coa(['a.uuid' => $uuid])->row();
		if ($cekdata != null) {
			// $cc_type = $cekdata->cost_center_type;
			$code_company         = $cekdata->code_company;
			$data['data']         = $cekdata;
			$data['uuid']         = $uuid;
			$data['code_company'] = $code_company;
			$data['judul']        = "Form Tambah Sub-Ledger COA";
			$data['load_grid']    = "C_chart_of_account";
			$data['load_refresh'] = "C_chart_of_account/add_subledger/" . $uuid;
			$this->load->view('v_chart_of_account/add_sub_ledger_coa', $data);
		} else {
			$this->load->view('error');
		}
	}
	public function simpanSubLedger()
	{
		$this->form_validation->set_rules('id_number_subledger', 'Number Sub-ledger', 'required');
		$this->form_validation->set_rules('nama_akun_subledger', 'Nama Sub-ledger COA', 'required');
		$this->form_validation->set_rules('deskripsi', 'Deskripsi COA', 'required');
		$this->form_validation->set_rules('uuid', 'uuid header coa ', 'required');

		if ($this->form_validation->run() == FALSE) {
			// Jika validasi gagal
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => validation_errors(),
			];
			echo json_encode($jsonmsg);
			return;
		}

		// Memulai transaksi
		$this->db->trans_begin();

		$id_number_subledger = $this->input->post('id_number_subledger');
		$nama_akun_subledger = $this->input->post('nama_akun_subledger');
		$deskripsi        = $this->input->post('deskripsi');
		$uuid             = $this->input->post('uuid');

		$cekcoa = $this->M_global->getWhere('chart_of_accounts', ['uuid' => $uuid])->row();
		if ($cekcoa != null) {
			$account_number     = $cekcoa->account_number;
			$code_company       = $cekcoa->code_company;
			$account_type       = $cekcoa->account_type;
			$code_header        = $cekcoa->code_header;
			$account_method     = $cekcoa->account_method;
			$account_group      = $cekcoa->account_group;
			$code_trialbalance1 = $cekcoa->code_trialbalance1;
			$code_trialbalance2 = $cekcoa->code_trialbalance2;
			$code_trialbalance3 = $cekcoa->code_trialbalance3;
			$noakun_subledger   = $account_number . $id_number_subledger;
			$param = [
				'account_number' => $noakun_subledger,
				'code_company'   => $code_company,
			];
			$cek_subledger = $this->M_global->getWhere('chart_of_accounts', $param)->num_rows();
			if ($cek_subledger != 0) {
				// Jika nomor ledger sudah ada
				$jsonmsg = [
					'hasil' => 'false',
					'pesan' => 'Sub-Ledger No already in use in company ' . $code_company,
				];
				echo json_encode($jsonmsg);
				$this->db->trans_rollback(); // Rollback jika ada error
				return;
			}

			$datainsert = [
				'uuid'               => $this->uuid->v4(),
				'account_number'     => (int)$noakun_subledger,
				'name'               => $nama_akun_subledger,
				'code_company'       => $code_company,
				'description'        => $deskripsi,
				'account_type'       => $account_type,
				'account_method'     => $account_method,
				'account_group'      => $account_group,
				'code_header'        => $code_header,
				'code_ledger'        => $account_number,
				'account_category'   => 'subledger',
				'code_trialbalance1' => $code_trialbalance1,
				'code_trialbalance2' => $code_trialbalance2,
				'code_trialbalance3' => $code_trialbalance3,
				'created_at'         => date('Y-m-d H:i:s'),
				'updated_at'         => date('Y-m-d H:i:s'),
			];

			// Insert to chart_of_accounts
			$this->db->insert('chart_of_accounts', $datainsert);

			$param_cc = [
				'code_company' => $code_company,
				'code_coa'     => $account_number, // Ini COA-nya ledger 
			];

			$cek_cc = $this->M_global->getWhere('account_centers', $param_cc)->result();
			if (!empty($cek_cc)) {
				$datacc = [];
				foreach ($cek_cc as $row) {
					$datacc[] = [
						'uuid'         => $this->uuid->v4(),
						'code_coa'     => (int)$noakun_subledger,
						'code_cc'      => $row->code_cc,
						'code_company' => $code_company
					];
				}
				// Insert to account_centers
				$this->db->insert_batch('account_centers', $datacc);
			}

			// Mengecek status transaksi dan commit jika berhasil
			if ($this->db->trans_status() === FALSE) {
				// Jika terjadi kesalahan, rollback transaksi
				$jsonmsg = [
					'hasil' => 'false',
					'pesan' => 'failed to save data',
				];
				echo json_encode($jsonmsg);
				$this->db->trans_rollback(); // Rollback jika ada error
			} else {
				// Jika semua berhasil, commit transaksi
				$this->db->trans_commit(); // Commit transaksi
				echo json_encode([
					'hasil' => 'true',
					'pesan' => 'Data successfully saved',
				]);
			}
		} else {
			// Jika ID-COA tidak ditemukan
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'ID-COA data not found',
			];
			echo json_encode($jsonmsg);
			$this->db->trans_rollback(); // Rollback transaksi jika ID-COA tidak ditemukan
		}
	}
	public function editform_subledger($uuid)
	{
		$data_sub_ledger =  $this->M_chart_of_account->get_coa(['a.uuid' => $uuid])->row();
		if ($data_sub_ledger != null) {
			$code_company = $data_sub_ledger->code_company;
			$params = [
				'a.account_number' => $data_sub_ledger->code_ledger,
				'a.code_company'   => $code_company
			];
			$data_ledger             = $this->M_chart_of_account->get_coa($params)->row();
			$data['data']            = $data_ledger;
			$data['data_sub_ledger'] = $data_sub_ledger;
			$data['uuid']            = $uuid;
			$data['judul']           = "Form Edit Sub-Ledger COA";
			$data['load_grid']       = "C_chart_of_account";
			$data['load_refresh']    = "C_chart_of_account/editform_subledger/" . $uuid;
			$this->load->view('v_chart_of_account/edit_subledger_coa', $data);
		} else {
			$this->load->view('error');
		}
	}
	public function updateSubLedger()
	{
		$this->form_validation->set_rules('nama_akun_subledger', 'Nama Sub-ledger COA', 'required');
		$this->form_validation->set_rules('deskripsi', 'Deskripsi COA', 'required');
		$this->form_validation->set_rules('uuid', 'uuid header coa ', 'required');
		if ($this->form_validation->run() == FALSE) {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => validation_errors(),
			];
			echo json_encode($jsonmsg);
			return;
		}
		$this->db->trans_begin();
		$nama_akun_subledger = $this->input->post('nama_akun_subledger');
		$deskripsi        = $this->input->post('deskripsi');
		$uuid             = $this->input->post('uuid');

		$cekcoa = $this->M_global->getWhere('chart_of_accounts', ['uuid' => $uuid])->row();
		if ($cekcoa != null) {
			$dataupdate = [
				'name'               => $nama_akun_subledger,
				'description'        => $deskripsi,
				'updated_at'         => date('Y-m-d H:i:s'),
			];
			$where_update = ['uuid' => $uuid]; 
			$this->db->where($where_update);
			$this->db->update('chart_of_accounts', $dataupdate);
			if ($this->db->trans_status() === FALSE) {
				$jsonmsg = [
					'hasil' => 'false',
					'pesan' => 'failed to update data',
				];
				echo json_encode($jsonmsg);
				$this->db->trans_rollback(); 
			} else {
				$this->db->trans_commit();
				echo json_encode([
					'hasil' => 'true',
					'pesan' => 'Data successfully updated',
				]);
			}
		} else {
			// Jika ID-COA tidak ditemukan
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'ID-COA data not found',
			];
			echo json_encode($jsonmsg);
			$this->db->trans_rollback(); // Rollback transaksi jika ID-COA tidak ditemukan
		}
	}
}
