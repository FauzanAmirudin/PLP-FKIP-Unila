<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| 
|--------------------------------------------------------------------------
| 
| 
| 
*/
class server_data extends gf_model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database('default', 'dbAccess');
	}
	function info($key)
	{
		$this->dbAccess->reset();
		return $this->dbAccess->info($key);
	}
	function version()
	{
		return $this->info('innodb_version');
	}
}