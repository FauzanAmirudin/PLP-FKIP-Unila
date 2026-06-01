<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Model: Informasi Data
|--------------------------------------------------------------------------
|
| CRUD operations for the 'informasi' table.
|
*/
class informasi_data extends gf_model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database('default', 'dbAccess');
	}

	/**
	 * Get single informasi by ID
	 */
	function get(int $id)
	{
		$this->dbAccess->reset();
		return $this->dbAccess->tabel('informasi')
			->where(["ID" => $id])
			->result_row_array();
	}

	/**
	 * Get list of informasi with pagination, ordered by newest first
	 * Only returns items where TANGGAL <= today
	 */
	function list($limit = 4, $offset = 0)
	{
		$this->dbAccess->reset();
		return $this->dbAccess->tabel('informasi')
			->where("`TANGGAL` <= CURDATE()")
			->order('TANGGAL', 'DESC')
			->order('ID', 'DESC')
			->limit($limit, $offset)
			->result_array();
	}

	/**
	 * Get all informasi for admin (including future dates)
	 */
	function list_all()
	{
		$this->dbAccess->reset();
		return $this->dbAccess->tabel('informasi')
			->order('TANGGAL', 'DESC')
			->order('ID', 'DESC')
			->result_array();
	}

	/**
	 * Get recent N informasi for sidebar widget
	 */
	function recent($limit = 3)
	{
		$this->dbAccess->reset();
		return $this->dbAccess->tabel('informasi')
			->where("`TANGGAL` <= CURDATE()")
			->order('TANGGAL', 'DESC')
			->order('ID', 'DESC')
			->limit($limit)
			->result_array();
	}

	/**
	 * Count total published informasi (TANGGAL <= today)
	 */
	function count()
	{
		$this->dbAccess->reset();
		$result = $this->dbAccess
			->column("COUNT(ID) AS TOTAL", FALSE)
			->where("`TANGGAL` <= CURDATE()")
			->result_row_array('informasi');
		return $result ? (int)$result['TOTAL'] : 0;
	}

	/**
	 * Insert new informasi
	 */
	function insert($data)
	{
		$this->dbAccess->reset();
		$max = $this->dbAccess
			->column("MAX(ID) AS MAXID", FALSE)
			->result_row_array('informasi');
		$nextId = ($max && isset($max['MAXID'])) ? (int)$max['MAXID'] + 1 : 1;
		if ($nextId <= 0) $nextId = 1;
		$data['ID'] = $nextId;

		$this->dbAccess->reset();
		return $this->dbAccess->tabel('informasi')->insert($data);
	}

	/**
	 * Update informasi by ID
	 */
	function update(int $id, $data)
	{
		$this->dbAccess->reset();
		return $this->dbAccess->tabel('informasi')
			->where(["ID" => $id])
			->update($data);
	}

	/**
	 * Delete informasi by ID
	 */
	function delete(int $id)
	{
		$this->dbAccess->reset();
		$sql = "DELETE FROM `informasi` WHERE `ID` = " . (int)$id;
		return $this->dbAccess->run($sql);
	}
}
