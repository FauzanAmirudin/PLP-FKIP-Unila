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
	$config['base_url'] 			= 'https://localhost/!_PPL2';
	$config['site_language'] 	    = 'id';
	$config['site_title'] 		    = 'PLP FKIP UNILA';
	$config['site_description']	    = 'Aplikasi pengiriman laporan kegiatan PLP FKIP UNILA';
	$config['site_icon'] 		    = '/assets/icon.ico';
	$config["extra-head"]			= '';
	$config['site_css'] 			= Array(
		'css/style.min.css'
	);
	$config['site_js'] 					= Array(
		'js/script.min.js'
	);
?>
