<?php
defined('GF_BASE_PATH') OR exit('No direct script access allowed');

/**
* 	Database setting for applications
*/

// Mendeteksi environment berdasarkan HTTP_HOST
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
$is_local = ($host === 'localhost' || $host === '127.0.0.1' || strpos($host, '.test') !== false || strpos($host, '.local') !== false);

if ($is_local) {
	// Konfigurasi Database Lokal (Laragon / XAMPP)
	$gf_db['default'] = array(
		'server' 	=> 'localhost',
		'username' 	=> 'root',
		'password'	=> '',
		'database'	=> 'test_fkip_plt',
		'baseUser' 	=> 'userplt'
	);
} else {
	// Konfigurasi Database Production (Hosting)
	$gf_db['default'] = array(
		'server' 	=> '127.0.0.1',
		'username' 	=> 'u141095167_root',
		'password'	=> 'Plpfkip2026',
		'database'	=> 'u141095167_test_fkip_plt',
		'baseUser' 	=> 'userplt'
	);
}

// Konfigurasi Backups
$gf_db['backups'] = array(
	'server' 	=> 'localhost',
	'username' 	=> 'test',
	'password'	=> 'test',
	'database'	=> 'test_fkip_plt_live',
	'baseUser' 	=> 'userplt'
);

$baseSett 	= 'setting';
?>
