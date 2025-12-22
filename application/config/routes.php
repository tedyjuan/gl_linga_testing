<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'Dashboard';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['download/(:any)'] = 'C_budget/download/$1';
$route['account-subledger'] = 'CR_account_subledger_overview/Report';
