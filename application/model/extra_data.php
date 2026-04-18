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
class extra_data extends gf_model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database('default', 'dbAccess');
	}
	function check(int $id)
	{
		$this->dbAccess->reset();
		return $this->dbAccess->tabel('aditionaldata')->where(array("USRKEY" => $id))->result_row_array();
	}
	function data(int $id)
	{
		$this->dbAccess->reset();
		return $this->dbAccess->tabel('aditionaldata')->where(array("USRKEY" => $id))->result_row_array();
	}
	function list()
	{
		$this->dbAccess->reset();
		return $this->dbAccess->tabel('aditionaldata')->result_array();
	}
	function insert($data)
	{
		$this->dbAccess->reset();
		$result = $this->dbAccess->tabel('aditionaldata')->insert($data);
		return $result;
	}
	function update(int $id, $data)
	{
		$this->dbAccess->reset();
		$result = $this->dbAccess->tabel('aditionaldata')->where(array("ID" => $id))->update($data);
	}
}
