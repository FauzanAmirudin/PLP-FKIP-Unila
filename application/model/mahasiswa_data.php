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
class mahasiswa_data extends gf_model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database('default', 'dbAccess');
	}
	function user($login_data)
	{
		return $this->data($login_data['ID']);
	}
	function notice()
	{
		$this->dbAccess->reset();
		return $this->dbAccess->tabel('informasi')->where("`TANGGAL` <= CURDATE() ORDER BY `TANGGAL` DESC")->result_row_array();
	}
	function scajule()
	{
		$this->dbAccess->reset();
		return $this->dbAccess->tabel('jadwal')->where("`WAKTUAKHIR` >= CURDATE() ORDER BY `WAKTUAWAL` DESC")->result_row_array();
	}
	function programstudy_list()
	{
		$this->dbAccess->reset();
		$result = $this->dbAccess->tabel('datamahasiswa')->join('databerkas', '`databerkas`.`USRKEY` = `datamahasiswa`.`USRKEY`')->distinct("PROGRAMSTUDI")->where("PROGRAMSTUDI IS NOT NULL")->where("`databerkas`.`TAHUNDAFTAR` = " . get_dbconfig('CURENTYEAR'))->order("PROGRAMSTUDI")->result_array();
		return $result;
	}
	function check(int $id)
	{
		$result = $this->check_by($id, 'USRKEY');
		return $result;
	}
	function check_by(int $id, $key = 'USRKEY')
	{
		$this->dbAccess->reset();
		$this->dbAccess->where(array($key => $id));
		$result = $this->dbAccess->tabel('datamahasiswa')->result_row_array();
		return $result;
	}
	function data(int $id)
	{
		$this->dbAccess->reset();
		$data = $this->dbAccess->tabel('datamahasiswa')->where(array("datamahasiswa`.`USRKEY" => $id))->result_row_array();
		return $data;
	}
	function list(int $id)
	{
		$this->dbAccess->reset();
		$result = $this->dbAccess->tabel('datamahasiswa')->result_array();
		return $result;
	}
	function data_check(int $id)
	{
		$data = $this->data($id);
		if (!empty($data)) {
			$column = array('NPM', 'JURUSAN', 'NAMAGENTING', 'NOHPGENTING', 'PROGRAMSTUDI', 'SKS', 'IPK', 'JENISKELAMIN', 'AGAMA', 'NOTELEPON', 'UKURANBAJU', 'ALAMATTINGGAL', 'KETRAMPILAN', 'ORGANISASI', 'NAMAAYAH', 'NAMAIBU', 'NOHPORTU', 'ALAMATASAL', 'KECAMATAN', 'KABUPATEN', 'PROPINSI', 'FTPROFIL');
			$result = FALSE;
			foreach ($column as $value) {
				if ($data[$value] !== null) {
					session_save("COMPLETE_DATA", TRUE);
					$result = TRUE;
				} else {
					// var_dump($value);
					session_save("COMPLETE_DATA", FALSE);
					$result = FALSE;
					break;
				}
			}
		} else $result = FALSE;
		return $result;
	}
	function placement(int $id)
	{
		$this->dbAccess->reset();
		$this->dbAccess->tabel('datamahasiswa')
		->join('databerkas', '`databerkas`.`USRKEY` = `datamahasiswa`.`USRKEY`')
		->join('datapenempatan', '`datapenempatan`.`USRKEY` = `datamahasiswa`.`USRKEY`')
		->join('dosen', '`dosen`.`USRKEY` =  `datapenempatan`.`DPLUSRKEY`')
		->where("`datamahasiswa`.`USRKEY` = " . $id);
		// echo $this->dbAccess->last_query;
		$data = $this->dbAccess->result_row_array();
		$this->dbAccess->last_placement = $data;
		return $data;
	}
	function group($id, $dplusrkey = NULL, $lokasidesa = NULL, $lokasisekolah = NULL)
	{
		if(isset($this->dbAccess->last_placement)){
			$data = $this->dbAccess->last_placement;		
		} else {
			$data = $this->placement($id);
		}
		// Use passed $dplusrkey if provided, else fall back to last_placement data
		$filter_dpl = !empty($dplusrkey) ? $dplusrkey : $data['DPLUSRKEY'];
		$this->dbAccess->reset();
		$this->dbAccess->tabel('datamahasiswa')
		->join('datapenempatan', '`datapenempatan`.`USRKEY` = `datamahasiswa`.`USRKEY`')
		->join('databerkas', '`databerkas`.`USRKEY` = `datamahasiswa`.`USRKEY`')
		->where("`databerkas`.`TAHUNDAFTAR` = " . $data['TAHUNDAFTAR'])
		->where("`databerkas`.`PERIODEDAFTAR` = '" . $data['PERIODEDAFTAR'] . "'")
		->where("`datapenempatan`.`DPLUSRKEY` = " . $filter_dpl)
		->order('`datapenempatan`.`LOKASISEKOLAH`', 'ASC')
		->order('`datamahasiswa`.`NPM`', 'ASC');
		$data = $this->dbAccess->result_array();
		return $data;
	}
	function insert($data)
	{
		$this->dbAccess->reset();
		$result = $this->dbAccess->tabel('datamahasiswa')->insert($data);
		return $result;
	}
	function update(int $id, $data)
	{
		$this->dbAccess->reset();
		$result = $this->dbAccess->tabel('datamahasiswa')->where(array("USRKEY" => $id))->update($data);
		return $result;
	}
}
