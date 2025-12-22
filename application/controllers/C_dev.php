<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_dev extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
	}
	function index()
	{
		$pesan = 'Under Maintenance';
		$data['kode'] = '503';
		$data['pesan'] = nl2br($pesan);
		$this->load->view("error", $data);
		
	}
	
}
