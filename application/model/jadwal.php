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
class jadwal extends gf_model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database('default', 'dbAccess');
	}
	function get(int $id)
	{
		$this->dbAccess->reset();
		return $this->dbAccess->tabel('jadwal')->where(array("ID" => $id))->result_row_array();
	}
	function list()
	{
		$this->dbAccess->reset();
		return $this->dbAccess->order("ID", "DESC")->result_array('jadwal');
	}
	function insert($data)
	{
		$this->dbAccess->reset();
		$result = $this->dbAccess->tabel('jadwal')->insert($data);
		return $result;
	}
	function update(int $id, $data)
	{
		$this->dbAccess->reset();
		$result = $this->dbAccess->tabel('jadwal')->where(array("ID" => $id))->update($data);
		return $result;
	}
	function delete(int $id)
	{
		$this->dbAccess->reset();
		$result = $this->dbAccess->tabel('jadwal')->where(array("ID" => $id))->delete();
		return $result;
	}
}
