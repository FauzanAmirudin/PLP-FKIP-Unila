<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Main Model 
|--------------------------------------------------------------------------
|
| Initial Model aplications
|
*/
class gf_model
{
	public $load;
	function __construct()
	{
		$this->load = new GF_LOADER;
	}
	public function GF_PREPARE()
	{
		if ($this->load->db != null) {
			foreach ($this->load->db as $key => $value) {
				$this->$key = $value;
			}
		}
	}
}
