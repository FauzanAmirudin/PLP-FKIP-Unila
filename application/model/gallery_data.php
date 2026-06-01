<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Model: Gallery Data
|--------------------------------------------------------------------------
|
| CRUD operations for the 'gallery' table.
|
*/
class gallery_data extends gf_model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database('default', 'dbAccess');
	}

	/**
	 * Get single gallery photo by ID
	 */
	function get(int $id)
	{
		$this->dbAccess->reset();
		return $this->dbAccess->tabel('gallery')
			->where(["ID" => $id])
			->result_row_array();
	}

	/**
	 * Get paginated gallery for public (newest first)
	 */
	function list($limit = 12, $offset = 0)
	{
		$this->dbAccess->reset();
		return $this->dbAccess->tabel('gallery')
			->order('ID', 'DESC')
			->limit($limit, $offset)
			->result_array();
	}

	/**
	 * Get all gallery photos for admin (newest first)
	 */
	function list_all()
	{
		$this->dbAccess->reset();
		return $this->dbAccess->tabel('gallery')
			->order('ID', 'DESC')
			->result_array();
	}

	/**
	 * Count total gallery photos
	 */
	function count()
	{
		$this->dbAccess->reset();
		$result = $this->dbAccess
			->column("COUNT(ID) AS TOTAL", FALSE)
			->result_row_array('gallery');
		return $result ? (int)$result['TOTAL'] : 0;
	}

	/**
	 * Insert new gallery photo
	 */
	function insert($data)
	{
		$this->dbAccess->reset();
		return $this->dbAccess->tabel('gallery')->insert($data);
	}

	/**
	 * Update gallery photo (keterangan only) by ID
	 */
	function update(int $id, $data)
	{
		$this->dbAccess->reset();
		return $this->dbAccess->tabel('gallery')
			->where(["ID" => $id])
			->update($data);
	}

	/**
	 * Delete gallery photo record by ID
	 */
	function delete(int $id)
	{
		$this->dbAccess->reset();
		return $this->dbAccess->tabel('gallery')
			->where(["ID" => $id])
			->delete();
	}
}
