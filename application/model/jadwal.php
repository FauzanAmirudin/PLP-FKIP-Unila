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
	private function _format_dates($row) {
		if ($row) {
			$row['WAKTUAWAL_FORMATTED'] = !empty($row['WAKTUAWAL']) ? date("d M Y", strtotime($row['WAKTUAWAL'])) : '-';
			$row['WAKTUAKHIR_FORMATTED'] = !empty($row['WAKTUAKHIR']) ? date("d M Y", strtotime($row['WAKTUAKHIR'])) : '-';
		}
		return $row;
	}

	function get(int $id)
	{
		$this->dbAccess->reset();
		$row = $this->dbAccess->tabel('jadwal')->where(array("ID" => $id))->result_row_array();
		return $this->_format_dates($row);
	}
	function list()
	{
		$this->dbAccess->reset();
		$rows = $this->dbAccess->where("1=1 ORDER BY `JENIS_PLP` ASC, `MINGGU_KE` ASC, `NOMOR_KEGIATAN` ASC")->result_array('jadwal');
		if (is_array($rows)) {
			foreach ($rows as &$r) {
				$r = $this->_format_dates($r);
			}
		}
		return $rows;
	}
	function dashboard_list()
	{
		$this->dbAccess->reset();
		$rows = $this->dbAccess->where("1=1 ORDER BY `JENIS_PLP` ASC, `MINGGU_KE` ASC, `NOMOR_KEGIATAN` ASC LIMIT 5")->result_array('jadwal');
		if (is_array($rows)) {
			foreach ($rows as &$r) {
				$r = $this->_format_dates($r);
			}
		}
		return $rows;
	}
	function insert($data)
	{
		$this->dbAccess->reset();
		$max = $this->dbAccess
			->column("MAX(ID) AS MAXID", FALSE)
			->result_row_array('jadwal');
		$nextId = ($max && isset($max['MAXID'])) ? (int)$max['MAXID'] + 1 : 1;
		if ($nextId <= 0) $nextId = 1;
		$data['ID'] = $nextId;

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
