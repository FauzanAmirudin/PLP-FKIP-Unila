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
class kaprodi_data extends gf_model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database('default', 'dbAccess');
	}
	function data(int $id)
	{
		$this->dbAccess->reset();
		return $this->dbAccess->tabel('kaprodi')->where(array("ID" => $id))->result_row_array();
	}
	function list()
	{
		$this->dbAccess->reset();
		return $this->dbAccess->tabel('kaprodi')->result_array();
	}
	function insert($data)
	{
		$this->dbAccess->reset();
		$result = $this->dbAccess->tabel('kaprodi')->insert($data);
		return $result;
	}
	function update(int $id, $data)
	{
		$this->dbAccess->reset();
		$result = $this->dbAccess->tabel('kaprodi')->where(array("ID" => $id))->update($data);
	}
}
