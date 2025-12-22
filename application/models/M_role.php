<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_role extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}
	public function get_paginated_role($limit, $start, $search, $order_by, $order_dir)
	{
		$this->db->select('a.*');
		$this->db->from('roles as a');
		if (!empty($search)) {
			$this->db->group_start()
				->like('a.name', $search)
				->group_end();
		}
		$this->db->limit($limit, $start);
		$this->db->order_by($order_by, $order_dir);

		$query = $this->db->get();
		return $query->result();
	}

	public function count_all_role()
	{
		return $this->db->count_all('roles');
	}
	public function count_filtered_role($search)
	{
		$this->db->like('name', $search);
		$query = $this->db->get('roles');
		return $query->num_rows();
	}
	public function get_where_role($param)
	{
		$this->db->select('a.*');
		$this->db->from('roles as a');
		$this->db->where($param);
		return $this->db->get();
	}
	public function get_menu_role_access($role_id)
	{
		$sql = "SELECT 
					a.id AS id_menu,
					IFNULL(a.`parent_id`, a.`id`) AS parent_id, 
					CASE 
						WHEN a.`parent_id` IS NULL THEN 0
						ELSE a.`sort_order`
					END AS `sort_order_baru`,
					a.`name`,
					b.`role_id`
				FROM `menus` a
				LEFT JOIN `role_menu_access` b ON b.`menu_id` = a.`id` AND b.`role_id` = ?
				WHERE a.`is_active` = '1'
				ORDER BY IFNULL(a.`parent_id`, a.`id`), 
					CASE 
						WHEN a.`parent_id` IS NULL THEN 0
						ELSE a.`sort_order`
					END";
		return $this->db->query($sql, array($role_id))->result();
	}
	public function get_menu_akses($menu_id, $role_id)
	{
		$this->db->select('*');
		$this->db->from('role_menu_access');
		$this->db->where("role_id", $role_id);
		$this->db->where("menu_id", $menu_id);
		return $this->db->get();
	}
}
