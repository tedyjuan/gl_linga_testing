<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_audit_list extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model('M_audit_list');
		$this->load->model('M_global');
	}
	function index()
	{
		$data['judul']      = 'Audit List';
		$data['load_grid']  = 'C_audit_list';
		$this->load->view("v_audit_list/grid_audit_list", $data);
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
		$columns        = ['created_at', 'user_create', 'code_depo', 'batch_number','action'];
		$order_by       = $columns[$order_col] ?? 'name';
		$data           = $this->M_audit_list->get_paginated_audit_list($length, $start, $search, $order_by, $dir);
		$total_records  = $this->M_audit_list->count_all_audit_list();
		$total_filtered = $this->M_audit_list->count_filtered_audit_list($search);
		$url_detail       = 'C_audit_list/detail/';
		$result = [];
		foreach ($data as $row) {
			$status = strtolower($row->action); // hasil: created, updated, deleted, posted, unposted

			$classes = [
				'created'   => 'badge bg-primary',
				'updated'   => 'badge bg-warning text-dark',
				'deleted'   => 'badge bg-danger',
				'posted'    => 'badge bg-success',
				'unposted'  => 'badge bg-secondary',
			];

			$label = ucfirst($status);
			$class = $classes[$status] ?? 'badge bg-dark';

			$badge = "<span class='$class'>$label</span>";

			$aksi = '<button type="button" title="Detail" onclick="editform(\'' . $url_detail . '\', \'' . $row->uuid . '\')" class="btn btn-soft-primary"><i class="bi bi-eye"></i></button>';

			$result[] = [
				$row->created_at,
				$row->user_create,
				$row->code_depo,
				$row->batch_number,
				$badge,
				$row->detail,
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
	public function detail($uuid)
	{
		$data =  $this->M_audit_list->get_where_audit_list(['a.uuid' => $uuid])->row();
		if ($data != null) {
			$judul = "Form Edit Audit List";
			$load_grid = "C_audit_list";
			$load_refresh = "C_audit_list/editform/" . $uuid;
			$this->load->view('v_audit_list/edit_audit_list', [
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

}
