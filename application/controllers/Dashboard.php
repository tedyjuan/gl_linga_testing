<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Dashboard extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model('M_global');
		$this->username = $this->session->userdata('sess_username');
	}
	function index()
	{
		$data['avatar'] = 'ava-0.png';
		$this->load->view("home", $data);
	}
}
