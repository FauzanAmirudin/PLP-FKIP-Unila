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
	$config['site_icon'] 		    = '/assets/icon.ico';
	$config["extra-head"]			= '
		<link rel="apple-touch-icon" sizes="57x57" href="'. $config['base_url'] .'/assets/assets/images/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="'. $config['base_url'] .'/assets/assets/images/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="'. $config['base_url'] .'/assets/assets/images/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="'. $config['base_url'] .'/assets/assets/images/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="'. $config['base_url'] .'/assets/assets/images/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="'. $config['base_url'] .'/assets/assets/images/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="'. $config['base_url'] .'/assets/assets/images/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="'. $config['base_url'] .'/assets/assets/images/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="'. $config['base_url'] .'/assets/assets/images/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192"  href="'. $config['base_url'] .'/assets/assets/images/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="'. $config['base_url'] .'/assets/assets/images/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="'. $config['base_url'] .'/assets/assets/images/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="'. $config['base_url'] .'/assets/assets/images/favicon-16x16.png">
		<link rel="manifest" href="'. $config['base_url'] .'/assets/manifest.json">
		<meta name="theme-color" content="#8806D4"/>
		<meta name="msapplication-TileColor" content="#8806D4">
		<meta name="msapplication-TileImage" content="'. $config['base_url'] .'/assets/assets/images/ms-icon-144x144.png">
	';
	$config['site_css'] 			= Array(
		'css/style.min.css'
	);
	$config['site_js'] 					= Array(
		'js/script.min.js'
	);
?>
