<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_global extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}
	public function insert($data, $tabel)
	{
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();
		$this->db->insert($tabel, $data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return TRUE;
		}
	}
	public function update($data, $tabel, $where)
	{
		$this->db->trans_start();
		$this->db->trans_strict(FALSE);
		$this->db->where($where);
		$this->db->update("$tabel", $data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return TRUE;
		}
	}
	public function getWhere($table, $param = null)
	{
		if ($param != null) {
			$this->db->where($param);
		}
		return $this->db->get($table);
	}

	public function getWhereOrder($table, $order = null, $param = null)
	{
		if ($param != null) {
			$this->db->where($param);
		}
		if ($order != null) {
			$this->db->order_by($order);
		}
		return $this->db->get($table);
	}
	 public function count_company_integrate($kode_company){
		$this->db->select('
		COUNT(DISTINCT b.code_company) AS depos_count,
		COUNT(DISTINCT c.code_company) AS dept_count,
		COUNT(DISTINCT d.code_company) AS divisi_count,
		COUNT(DISTINCT e.code_company) AS segment_count,
		(COUNT(DISTINCT b.code_company) + COUNT(DISTINCT c.code_company) + COUNT(DISTINCT d.code_company) + COUNT(DISTINCT e.code_company)) AS total_count
	');
		$this->db->from('companies a');
		$this->db->join('depos b', 'b.code_company = a.code_company', 'left');
		$this->db->join('departments c', 'c.code_company = a.code_company', 'left');
		$this->db->join('divisions d', 'd.code_company = a.code_company', 'left');
		$this->db->join('segments e', 'e.code_company = a.code_company', 'left');
		$this->db->where('a.code_company', $kode_company); 
		return $this->db->get()->row();

	}
	public function count_all_tabel($tabel)
	{
		return $this->db->count_all($tabel);
	}

	public function generate_code($prefix)
	{
		$current_year = date('Y');

		// Mulai transaksi biar aman (no bentrok antar user)
		$this->db->trans_start();

		// Lock row supaya tidak race condition
		$query = $this->db->query("SELECT * FROM counters WHERE prefix = ? FOR UPDATE", [$prefix]);
		$row = $query->row();

		if (!$row) {
			// Jika belum ada, buat baru
			$this->db->insert('counters', [
				'prefix' => $prefix,
				'last_number' => 0,
				'years' => $current_year
			]);
			$row = (object)[
				'last_number' => 0,
				'years' => $current_year
			];
		}

		// Jika tahun berganti, reset otomatis
		if ($row->years != $current_year) {
			$row->years = $current_year;
			$row->last_number = 0;
		}

		// Tambahkan counter
		$new_number = $row->last_number + 1;

		// Update counter ke DB
		$this->db->where('prefix', $prefix);
		$this->db->update('counters', [
			'last_number' => $new_number,
			'years' => $row->years,
			'updated_at' => date('Y-m-d H:i:s')
		]);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			return false;
		}

		// Format kode akhir: PC2025-000001
		return sprintf('%s%d-%06d', $prefix, $row->years, $new_number);
	}
	public function preview_code($prefix)
	{
		$current_year = date('Y');
		$row = $this->db->get_where('counters', ['prefix' => $prefix])->row();
		if ($row == null) {
			// Jika belum ada, anggap baru (last_number = 0)
			$next_number = 1;
			$years = $current_year;
		} else {
			// Jika tahun beda, auto reset preview ke 1
			if ($row->years != $current_year) {
				$next_number = 1;
				$years = $current_year;
			} else {
				$next_number = $row->last_number + 1;
				$years = $row->years;
			}
		}

		// Format: PC2025-000001
		$next_code = sprintf('%s%d-%06d', $prefix, $years, $next_number);

		return $next_code;
	}
	
}
