<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Tcpdf_lib
{
	public function __construct()
	{
		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';
	}
	
}
