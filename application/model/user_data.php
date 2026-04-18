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
class user_data extends gf_model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database('default', 'dbAccess');
	}
	function login($myusername, $mypassword)
	{
		$this->dbAccess->reset();
		$condition = array(
			"USERID" => $myusername,
			"PASSWORD" => str_encrypt($mypassword)
		);
		$user = $this->dbAccess->tabel('users')->where($condition)->result_row_array();
		return $user;
	}
	function data($login_data)
	{
		$this->dbAccess->reset();
		return $this->get_data($login_data['ID'], $login_data['LEVEL']);
	}
	function check($myusername, $key = "USERID")
	{
		$this->dbAccess->reset();
		if (in_array($key, array(explode(", ", "Admin, Monitor, Operator, DPL, Mahasiswa")))) $condition = array("USERID" => $myusername);
		else  $condition = array($key => $myusername);
		$user = $this->dbAccess->tabel('users')->where($condition)->result_row_array();
		return $user;
	}
	function search($variable, $key, $num = 100)
	{
		$this->dbAccess->reset();
		$condition = array($key => "%".$variable."%");
		$user = $this->dbAccess->tabel('dosen')->where($condition, "LIKE")->result_row_array();
		return $user;
	}
	function get_data(int $id, $level)
	{
		$this->dbAccess->reset();
		if (in_array($level, explode(", ", "Mahasiswa"))) $this->dbAccess->join('datamahasiswa', '`users`.`ID` = `datamahasiswa`.`USRKEY`');
		if (in_array($level, explode(", ", "Admin, Monitor, Operator, DPL"))) $this->dbAccess->join('dosen', '`users`.`ID` = `dosen`.`USRKEY`');
		$user = $this->dbAccess->tabel('users')->where(array("users`.`ID" => $id))->result_row_array();
		return $user;
	}
	function insert($data)
	{
		$this->dbAccess->reset();
		$result = $this->dbAccess->tabel('users')->insert($data);
		return $result;
	}
	function insert_config($data, $type = 'mahasiswa')
	{
		$this->dbAccess->reset();
		switch ($type) {
			case 'DPL':
				$result = $this->dbAccess->tabel('dosen')->insert($data);
				break;
			
			default:
				$result = $this->dbAccess->tabel('datamahasiswa')->insert($data);
				break;
		}
		return $result;
	}
	function update(int $id, $data)
	{
		$this->dbAccess->reset();
		$result = $this->dbAccess->tabel('users')->where(array("ID" => $id))->update($data);
		return $result;
	}
	function notice()
	{
		$this->dbAccess->reset();
		return  $this->dbAccess->tabel('informasi')->where("`TANGGAL` <= CURDATE() ORDER BY `TANGGAL` DESC")->result_row_array();
	}
	function scajule()
	{
		$this->dbAccess->reset();
		return $this->dbAccess->tabel('jadwal')->where("`WAKTUAKHIR` >= CURDATE() ORDER BY `WAKTUAWAL` DESC")->result_row_array();
	}
}
