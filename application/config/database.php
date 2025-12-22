<?php
defined('BASEPATH') or exit('No direct script access allowed');

$active_group = 'default';
$query_builder = TRUE;

if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
	// Konfigurasi untuk localhost
	$hostname = '31.97.106.123';
	$username = 'gl_prod';
	$password = 'TUM8UHB3rsam@';
	$database = 'u1721210_general_ledger';
	$port = '3306';
} else {
	// Konfigurasi server 
	$hostname = 'localhost';
	$username = 'gl_prod';
	$password = 'TUM8UHB3rsam@';
	$database = 'u1721210_general_ledger';
	$port = '3306';
}


// Konfigurasi database default
$db['default'] = array(
	'dsn'      => '',
	'hostname' => $hostname,
	'username' => $username,
	'password' => $password,
	'database' => $database,
	'port'     => $port,
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
