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
class registration_data extends gf_model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database('default', 'dbAccess');
		$this->year = get_dbconfig('CURENTYEAR');
		$this->period = get_dbconfig('CURENTSEMESTER');
	}
	/* SECTION Registration mutator */
	/**
	 * Save
	 *
	 * @param  int $id
	 * @param  string $berkas
	 * @param  string $validator
	 * @return array
	 */
	function save(int $id, string $berkas, string $validator, int $year = NULL, string $periode = NULL)
	{
		$year = $year === NULL ? $this->year : $year;
		$periode = $periode === NULL ? $this->period : $periode;
		$this->dbAccess->reset();
		$registered = $this->get_berkas($id, $year, $periode);
		if (!empty($registered)) $result = $this->update_berkas($id, "USRKEY", $berkas, $year, $periode);
		else $result = $this->insert_berkas($id, $year, $periode, $berkas);
		$registered = $this->get_berkas($id, $year, $periode);
		$this->set_status($registered['ID'], "Pengajuan", NULL, $validator, $year, $periode);
		return $this->get_status($id, $year, $periode);
	}
	/**
	 * status_update
	 *
	 * @param  int $id
	 * @param  string $state
	 * @param  string $validator
	 * @param  int $year
	 * @param  string $periode
	 * @param  string $key
	 * @return array
	 */
	function status_update(int $id, string $state, string $note, string $validator, int $year = NULL, string $periode = NULL, string $key = NULL)
	{
		$year = $year === NULL ? $this->year : $year;
		$periode = $periode === NULL ? $this->period : $year;
		$berkasid = !empty($key) ? $this->check_by($id, $key, $year, $periode)['BRKSKEY'] : $id;
		$result = $this->set_status($berkasid, $state,  $note, $validator);
		$result = $this->get_status($berkasid, NULL, NULL, "ID");
		return $result;
	}
	/* !SECTION */

	/* SECTION Registration accessor */
	/**
	 * data
	 *
	 * TODO Add logic to retrive data from cache (get_status)
	 *
	 * @param  int $id
	 * @return array
	 */
	function get(int $id)
	{
		$result = $this->get_status($id);
		return $result;
	}
	/**
	 * data
	 *
	 * TODO Add logic to retrive data from cache (get_status)
	 *
	 * @param  int $id
	 * @return array
	 */
	function data(int $id)
	{
		$result = $this->get_status($id, NULL, NULL, 'USRKEY');
		return $result;
	}
	/**
	 * check
	 *
	 * TODO Add logic to retrive data from cache (get_status)
	 *
	 * @param  int $id
	 * @param  int $year optional
	 * @param  string $period optional
	 * @return array
	 */
	function check(int $id, int $year = NULL, string $period = NULL)
	{
		$year = $year === NULL ? $this->year : $year;
		$period = $period === NULL ? $this->period : $year;
		$result = $this->get_status($id, $year, $period);
		return $result;
	}
	/**
	 * data_check
	 *
	 * TODO Add logic to retrive data from cache (get_status)
	 * 
	 * @param  int $id
	 * @param  string $key
	 * @param  int $year optional
	 * @param  string $period optional
	 * @return array
	 */
	function status_check(int $id, int $year = NULL, $periode = NULL)
	{
		$this->dbAccess->reset();
		$result = $this->get_status($id, $year, $periode, 'USRKEY');
		if (!empty($result) && $result['STATUSBERKAS'] == 'Disetujui') {
			return TRUE;
		} else return FALSE;
	}
	/**
	 * check_by
	 *
	 * @param  int $id
	 * @param  string $key
	 * @param  int $year optional
	 * @param  string $period optional
	 * @return array
	 */
	function check_by(int $id,  string $key = '`USRKEY`', $year = NULL, $period = NULL)
	{
		$condition = $key . " = " . $id;
		if ($year === NULL) $year = $this->year;
		if ($year !== FALSE) $condition .= " AND `databerkas`.`TAHUNDAFTAR` = " . $year;
		if ($period === NULL) $period = $this->period;
		if ($period !== FALSE) $period .= " AND `databerkas`.`PERIODEDAFTAR` = " . $period;
		$result = current($this->data_registration($condition));
		return $result;
	}
	/**
	 * list
	 * 
	 * Suport  mysql 5.6 & mysql 8.0 with difrence aproace
	 * 
	 * @param  string $condition
	 * @return array
	 */
	function list(int $year = NULL, string $periode = NULL, string $status = NULL, string $prodi = NULL, string $npm = NULL)
	{
		$condition = array();
		if (!empty($year)) $condition["`TAHUNDAFTAR`"] = $year;
		if (!empty($periode)) $condition["`PERIODEDAFTAR`"] = $periode;
		if (!empty($npm)) {
			$status = NULL;
			$condition["NPM"] = $npm;
		} else if (!empty($prodi) && empty($npm)) $condition["PROGRAMSTUDI"] = $prodi;

		$result = $this->data_registration($condition, $status);
		return $result;
	}
	/**
	 * incerment_update
	 *
	 * Update with add row with new value
	 * 
	 * @param  int $id
	 * @param  array $data
	 * @param  string $key
	 * @return array new data.
	 */
	function statistic($year = 0)
	{
		$this->dbAccess->reset();
		if (!empty($year)) $this->dbAccess->where("`databerkas`.`TAHUNDAFTAR` = " . $year);
		$data = $this->dbAccess->tabel("datamahasiswa")
			->join("databerkas", "`databerkas`.`USRKEY` = `datamahasiswa`.`USRKEY`", "INNER")
			->column("COUNT(`datamahasiswa`.`ID`) AS JUMLAHPESERTA", FALSE)
			->column("`databerkas`.`TAHUNDAFTAR` AS TITLE")
			->column("`databerkas`.`PERIODEDAFTAR` AS SUBTITLE")
			->group("`databerkas`.`TAHUNDAFTAR`")
			->group("`databerkas`.`PERIODEDAFTAR`")
			->result_array();
		// var_dump($data);
		// echo $this->dbAccess->last_query;
		return $data;
	}
	function register_year()
	{
		return $this->parameter("`databerkas`.`TAHUNDAFTAR`");
	}
	function register_periode(int $year = NULL)
	{
		return $this->parameter("`databerkas`.`PERIODEDAFTAR`", $year);
	}
	function register_prodi(int $year = NULL, string $periode = NULL)
	{
		return $this->parameter("`datamahasiswa`.`PROGRAMSTUDI`", $year, $periode);
	}

	/* !SECTION */



	/**
	 * insert
	 *
	 * @param  array $data
	 * @return array
	 */
	private function insert_berkas(int $id, int $year, string  $periode, string $berkas)
	{
		$this->dbAccess->reset();
		$data = array(
			"USRKEY"		=> $id,
			"BERKASDAFTAR"	=> $berkas,
			"TAHUNDAFTAR"	=> $year,
			"PERIODEDAFTAR"	=> $periode
		);
		$result = $this->dbAccess->tabel('databerkas')->insert($data);
		return $result;
	}
	/**
	 * update
	 *
	 * Update with key
	 * 
	 * @param  int $id
	 * @param  array $data
	 * @param  string $key
	 * @return bool
	 */
	private function update_berkas(int $key, string $col = "USRKEY", string $berkas, int $year, string $periode)
	{
		$this->dbAccess->reset();
		$data = array(
			"USRKEY"		=> $key,
			"BERKASDAFTAR"	=> $berkas,
			"TAHUNDAFTAR"	=> $year,
			"PERIODEDAFTAR"	=> $periode
		);
		$this->dbAccess->where(array($col => $key));
		$result = $this->dbAccess->tabel('databerkas')->update($data);
		return $result;
	}
	/**
	 * incerment_update
	 *
	 * Update with add row with new value
	 * 
	 * @param  int $berkasid
	 * @param  array $data
	 * @param  string $key
	 * @return array new data.
	 */
	private function set_status($berkasid, $state, $note, $validator)
	{
		$valid = array(
			"BRKSKEY"		=> $berkasid,
			"STATUSBERKAS"	=> $state,
			"VALIDATOR"		=> $validator,
			"NOTEBERKAS"	=> $note
		);
		$this->dbAccess->reset(TRUE);
		$result = $this->dbAccess->reset(TRUE)->tabel('datastatus')->insert($valid);
		return $result;
	}

	private function get_berkas($key, $year = NULL, $periode = NULL, $col = NULL)
	{
		if (empty($col)) $col = 'USRKEY';
		$this->dbAccess->reset(TRUE);
		if (!empty($year)) $this->dbAccess->where("`TAHUNDAFTAR` = " . $year);
		if (!empty($periode)) $this->dbAccess->where("`PERIODEDAFTAR` = '" . $periode . "'");
		return $this->dbAccess
			->tabel('databerkas')
			->where('`databerkas`.`' . $col . '` = ' . $key)
			->result_row_array();
	}
	private function get_status($key, $year = NULL, $periode = NULL, $col = NULL)
	{
		if (empty($col)) $col = 'ID';
		$this->dbAccess->reset(TRUE);
		if (!empty($year)) $this->dbAccess->where("`TAHUNDAFTAR` = " . $year);
		if (!empty($periode)) $this->dbAccess->where("`PERIODEDAFTAR` = '" . $periode . "'");
		$result = $this->dbAccess
			->tabel('databerkas')
			->column(array(
				'`databerkas`.`ID`',
				'`databerkas`.`USRKEY`',
				'`databerkas`.`TAHUNDAFTAR`',
				'`databerkas`.`PERIODEDAFTAR`',
				'`databerkas`.`BERKASDAFTAR`',
				'`databerkas`.`DATEREQUEST`',
				'`datastatus`.`STATUSBERKAS`',
				'`datastatus`.`NOTEBERKAS`',
				'`datastatus`.`VALIDATOR`',
				'`datastatus`.`DATEVALID`'
			))
			->join('datastatus', '`databerkas`.`ID` = `datastatus`.`BRKSKEY`')
			->order('`datastatus` . `DATEVALID`')
			->where('`databerkas`.`' . $col . '` = ' . $key)
			->result_row_array();
		// echo ($this->dbAccess->last_query);
		return $result;
	}
	private function data_registration($condition = '', $status = NULL)
	{
		$this->dbAccess->reset();
		$suport = $this->dbAccess->suport_version('20.0.0');
		if (empty($condition)) $condition = "`databerkas`.`TAHUNDAFTAR` = " . $this->year . " AND `databerkas`.`PERIODEDAFTAR` = '" . $this->period . "'";
		$this->dbAccess->tabel('datamahasiswa')
			->column(array(
				'`databerkas`.`ID`',
				'`databerkas`.`USRKEY`',
				'`datamahasiswa`.`NAMA`',
				'`datamahasiswa`.`NPM`',
				'`datamahasiswa`.`PROGRAMSTUDI`',
				'`datamahasiswa`.`JENISKELAMIN`',
				'`datamahasiswa`.`NOTELEPON`',
				'`databerkas`.`TAHUNDAFTAR`',
				"`databerkas`.`ID` AS BERKASID",
				'`databerkas`.`PERIODEDAFTAR`',
				'`databerkas`.`BERKASDAFTAR`',
				'`databerkas`.`DATEREQUEST`',
				'`datastatus`.`STATUSBERKAS`',
				'`datastatus`.`NOTEBERKAS`',
				'`datastatus`.`VALIDATOR`',
				'`datastatus`.`DATEVALID`'
			))
			->join('databerkas', '`databerkas`.`USRKEY` = `datamahasiswa`.`USRKEY`', 'INNER')
			->join('datastatus', '`datastatus`.`BRKSKEY` = `databerkas`.`ID`', 'LEFT')
			->where($condition)
			->order("TAHUNDAFTAR", 'DESC')
			->order("PERIODEDAFTAR", 'DESC')
			->order("NPM", 'ASC')
			->order("DATEVALID", 'DESC');
		if ($suport) {
			$this->dbAccess
				->column("row_number() over ( PARTITION BY datastatus.BRKSKEY ORDER BY datastatus.DATEVALID DESC ) AS NUMRECORD", FALSE);
			$tabel = $this->dbAccess->query();
			$result = $this->dbAccess->reset(TRUE)
				->tabel("(" . $tabel . ") AS NUMRECORD", FALSE)
				->where("NUMRECORD = 1");
			if (!empty($status)) $this->dbAccess->where("`STATUSBERKAS` = '" . $status . "'");
			$result = $this->dbAccess->result_array();
		} else {
			$result = $this->dbAccess->result_array();

			$last_row['USRKEY'] = 0;
			foreach ($result as $key => $row) {
				if ($last_row['USRKEY'] == $row['USRKEY']) unset($result[$key]);
				else {
					$last_row['USRKEY'] = $row['USRKEY'];
					if (!empty($status) && $row['STATUSBERKAS'] != $status) unset($result[$key]);
					// var_dump($row);
				}
			}
		}
		// echo ($this->dbAccess->last_query);
		return $result;
	}
	public function parameter($key, int $year = NULL, string $periode = NULL)
	{
		$this->dbAccess->reset(TRUE);
		if (!empty($tahun)) $this->dbAccess->where("`databerkas`.`TAHUNDAFTAR` = " . $tahun);
		if (!empty($periode)) $this->dbAccess->where("`databerkas`.`PERIODEDAFTAR` = '" . $periode . "'");
		$parameter = $this->dbAccess->tabel('datamahasiswa')
			->join('databerkas', '`datamahasiswa`.`USRKEY` = `databerkas`.`USRKEY`')
			->distinct($key)
			->order($key, "DESC")
			->result_array();
		return $parameter;
	}
}
