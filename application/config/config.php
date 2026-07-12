<?php
defined('GF_BASE_PATH') OR exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Site Setting 
|--------------------------------------------------------------------------
|
| Initial Setting adn data aplications
|
*/
	$config["ctrl_default"] 		= 'frontpage';
	$config["func_default"] 		= 'index';
	$config["folder_default"]		= array(
		'upload'	=> 'uploads',
		'tmp'		=> 'tmp',
		'log'		=> 'log'
	);
	$config["encryption_key"]		= "ev465d4fbgn767myn6b5y6grnugin6rcb6u576m8m1xe8mh281hgx8mj13m1h32jc09";
	$config["encryption_iv"]		= '1234567891011121'; 
	$config["ciphering"] 			= "AES-128-CTR";
	$config['sanitize_output']		= TRUE;

	$config["libs"] 				= ["captcha", "clock", "sessions"];
	$config["helper"] 				= ["response", "notification", "encrypter", "url", "form", "zip", "pdf", "time", "login", "nameProcessor"];
	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
	$base_dir = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
	$config['base_url'] = rtrim($protocol . $host . $base_dir, '/');
	$config['site_language'] 	    = 'id';
	$config['site_title'] 		    = 'PLP FKIP UNILA';
	$config['site_description']	    = 'Aplikasi pengiriman laporan kegiatan PLP FKIP UNILA';
	$config['site_icon'] 		    = '/assets/images/fkip.png';
	$config["extra-head"]			= '
		<link rel="icon" type="image/png" sizes="192x192"  href="'. $config['base_url'] .'/assets/images/fkip.png">
		<link rel="icon" type="image/png" sizes="32x32" href="'. $config['base_url'] .'/assets/images/fkip.png">
		<link rel="icon" type="image/png" sizes="96x96" href="'. $config['base_url'] .'/assets/images/fkip.png">
		<link rel="icon" type="image/png" sizes="16x16" href="'. $config['base_url'] .'/assets/images/fkip.png">
		<link rel="apple-touch-icon" href="'. $config['base_url'] .'/assets/images/fkip.png">
		<link rel="manifest" href="'. $config['base_url'] .'/assets/manifest.json">
		<meta name="theme-color" content="#B33791"/>
		<meta name="msapplication-TileColor" content="#B33791">
	';
	$config['site_css'] 			= Array(
		'css/style.min.css?v=2'
	);
	$config['site_js'] 					= Array(
		'js/script.min.js'
	);
?>
