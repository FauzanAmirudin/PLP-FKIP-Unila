<?php
defined('GF_BASE_PATH') OR exit('No direct script access allowed');
/**
* 	Databse setting for aplications
*/
	$gf_db['default'] = array(
		'server' 	=> 'localhost',
		'username' 	=> 'test',
		'password'	=> 'test',
		'database'	=> 'test_fkip_plt',
		'baseUser' 	=> 'userplt'
	);
	$gf_db['backups'] = array(
		'server' 	=> 'localhost',
		'username' 	=> 'test',
		'password'	=> 'test',
		'database'	=> 'test_fkip_plt_live',
		'baseUser' 	=> 'userplt'
	);
	$baseSett 	= 'setting';
?>
