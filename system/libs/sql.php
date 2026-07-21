<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	SQL Wraper class
 */

/**
 * Class that build query and access MySQL database.
 *
 *
 * @param string $tabelName String contain Tabel name to access. 
 *
 * @return object
 */
#[AllowDynamicProperties]
class  gf_sql
{
	private $sql;
	private $TABLE;
	private $INSERT;
	private $UPDATE;
	private $FROM = "";
	private $COLOUM = "";
	private $WHERE = "";
	private $LIMIT = "";
	private $ORDER = "";
	private $GROUP = "";
	private $JOIN = "";
	private $ALTER = "";
	private $ADDCOL = "";
	private $DROPCOL = "";
	// PHP 8.2+ membutuhkan deklarasi properti eksplisit
	public  $result;
	public  $mysql;
	public  $last_query  = '';
	public  $last_result = null;
	public  $last_error  = '';
	private $SQL_DB_SERVER   = '';
	private $SQL_DB_USERNAME = '';
	private $SQL_DB_PASSWORD = '';
	private $SQL_DB_DATABASE = '';
	function __construct($db = FALSE)
	{
		if ($db == FALSE) return $this;
		$this->SQL_DB_SERVER = trim($db['server']);
		$this->SQL_DB_USERNAME = trim($db['username']);
		$this->SQL_DB_PASSWORD = trim($db['password']);
		$this->SQL_DB_DATABASE = trim($db['database']);
		//Connection between a database and php
		// mysqli_report(MYSQLI_REPORT_OFF): Mencegah mysqli melempar exception di PHP 8.1+
		// sehingga error ditangani oleh sistem framework sendiri.
		mysqli_report(MYSQLI_REPORT_OFF);
		$this->mysql = new mysqli($this->SQL_DB_SERVER, $this->SQL_DB_USERNAME, $this->SQL_DB_PASSWORD, $this->SQL_DB_DATABASE);

		/* check connection */
		if ($this->mysql->connect_errno) {
			// printf("Connect failed: %s\n", $this->mysql->connect_error);
			exit();
		}
	}
	/**
	 * Alter tabel
	 *
	 *
	 * @param bool $rsql Use TRUE instead of executing a query the function will return the query as a string.
	 *
	 * @return self
	 */
	public function alter_tabel(bool $rsql = FALSE)
	{
		if ($rsql === TRUE) {
			return $this->query('ALTER');
		} else {
			return $this->result('ALTER');
		}
	}
	public function add_column(string $column, string $type, string $defaultvalue, string $posisioncolumnafter = '')
	{
		if ($this->ADDCOL != "") $this->ADDCOL .= ", ";
		$this->ADDCOL .= " ADD COLUMN `" . $column . "`";
		$this->ADDCOL .= " " . $type;
		$this->ADDCOL .= " " . $defaultvalue;
		$this->ADDCOL .= empty($posisioncolumnafter) ? "" : " AFTER `" . $posisioncolumnafter . "`";
		return $this;
	}
	public function drop_colum(string $column)
	{
		if ($this->DROPCOL != "") $this->DROPCOL .= ", ";
		$this->DROPCOL .= " DROP COLUMN `" . $column . "`";
		return $this;
	}
	// TABLE SELECTOR
	public function tabel(string $tabelName, $tic = TRUE)
	{
		$num_var = func_num_args();
		$val_var = func_get_args();
		if ($num_var > 0) {
			if ($tic !== FALSE) $tabelName = '`' . $tabelName . '`';
			$this->TABLE = $tabelName;
			return $this;
		} else {
			return "Error, you need provide tabel name!";
		}
	}
	public function join(string $jointabel, $conditions, $type = 'LEFT')
	{
		$sql = $type . ' JOIN ';
		$sql = $sql . "`" . $jointabel . "`";
		$sql = $sql . " ON ";
		if (is_array($conditions)) {
			if (is_array($conditions[0]) && count($conditions[0]) >= 2) {
				foreach ($conditions as $key => $condition) {
					if (is_array($condition)) {
						if ($key > 0) $sql .= ", ";
						$sql .= $this->TABLE . '.' . $condition[0] . ' ' . (isset($condition[2]) ? $condition[2] : "=") . ' `' . $jointabel . '`.' . $condition[1];
					} else {
						trigger_error("Wrong condition!", E_USER_ERROR);
						return FALSE;
					}
				}
			} else if (is_string($conditions[0]) && count($conditions) >= 2) {
				$sql .= $this->TABLE . '.' . $conditions[0] . ' ' . (isset($conditions[2]) ? $conditions[2] : "=") . ' `' . $jointabel . '`.' . $conditions[1];
			} else {
				trigger_error("Wrong condition!", E_USER_ERROR);
				return FALSE;
			}
		} else if (is_string($conditions)) {
			$sql = $sql . $conditions;
		} else {
			trigger_error("Wrong condition!", E_USER_ERROR);
			return FALSE;
		}
		$this->JOIN .= " " . $sql;
		return $this;
	}
	public function auto_join(string $jointabel)
	{
		$sql = 'AUTO JOIN ';
		$sql = $sql . "`" . $jointabel . "`";
		$this->JOIN .= " " . $sql;
		return $this;
	}

	// CONDITION
	public function column($columns, $tic = TRUE)
	{
		if ($this->COLOUM != '') $this->COLOUM .= ", ";
		$this->COLOUM .= $this->column_to_string($columns, $tic);
		return $this;
	}
	public function where($columns, $operator = TRUE)
	{
		if (empty($this->WHERE)) {
			$this->WHERE = ' WHERE ';
		} else {
			$this->WHERE .= ' AND ';
		};
		$sql = $this->condition_to_string($columns, $operator, "AND");
		$this->WHERE .= $sql;
		return $this;
	}
	public function or_where($columns, $operator = FALSE)
	{
		if (empty($this->WHERE)) {
			$this->WHERE = 'WHERE ';
		} else {
			$this->WHERE .= ' OR ';
		};
		$sql = $this->condition_to_string($columns, $operator, "OR");
		$this->WHERE .= $sql;
		return $this;
	}
	public function distinct($columns)
	{
		$sql = 'DISTINCT ';
		$sql .= $this->column_to_string($columns);
		$this->COLOUM = $sql . " ";
		return $this;
	}
	public function order($columns, $by = TRUE)
	{
		$sql = $this->ORDER == '' ? ' ORDER BY ' : ", ";
		$sql .= $this->column_to_string($columns);
		$sql .= is_string($by) ? " " . $by : ($by ? ' ASC' : ' DESC');
		$this->ORDER .= $sql;
		return $this;
	}
	public function group($columns)
	{
		$sql = ($this->GROUP == '') ? ' GROUP BY ' : ", ";
		$sql .= $this->column_to_string($columns);
		$this->GROUP .= $sql;
		return $this;
	}
	public function limit(int $num, ?int $start = NULL)
	{
		$this->LIMIT = ' LIMIT ' . (!empty($start) ? $start . ", " . $num : $num);
		return $this;
	}

	/**
	 * update data
	 *
	 *
	 * @param string|array $data Data can be inpute as raw sring or array, raw string must format as SQL standar `COLUMN` = 'Value'.
	 * @param bool $rsql Use TRUE instead of executing a query the function will return the query as a string.
	 *
	 * @return bool
	 */
	function update($data, $rsql = FALSE)
	{
		$sql = " SET ";
		if (is_array($data) === TRUE) {
			// `NAMA` = '$myName', `NPM` = '$myNpm', `JURUSAN` = '$myJurusan', 
			$count  = 0;
			foreach ($data as $column => $value) {
				if ($count > 0) $sql .= ", ";
				$sql .= "`" . $column . "` = ";
				$sql .= is_string($value) ? "'" . $value . "'" : ($value === NULL ? 'NULL' : $value);
				$count++;
			}
		} else {
			$sql .= $data;
		}
		$this->UPDATE = $sql;
		if ($rsql === TRUE) {
			return $this->query('UPDATE');
		} else {
			return $this->result('UPDATE');
		}
	}
	/**
	 * insert data
	 *
	 *
	 * @param string|array $data Data can be inpute as raw sring or array, raw string must format as SQL standar (`COLUMN`) VALUES ('Value').
	 * @param bool $rsql Use TRUE instead of executing a query the function will return the query as a string.
	 *
	 * @return bool
	 */
	function insert($data, $rsql = FALSE)
	{
		$sql = " ";
		// (`USER`, `USERID`, `PASS`, `STAT`, `BASEID`)
		// VALUES 
		// ('$npm', '$npm', '$pass', 'Mahasiswa', '$basetable')
		if (is_array($data) === TRUE) {
			$rColumn = "";
			$rData   = "";
			$count  = 0;
			foreach ($data as $column => $value) {
				if ($count > 0) {
					$rColumn .= ", ";
					$rData .= ", ";
				}
				$rColumn .= "`" . $column . "`";
				$rData   .= is_string($value) ? "'" . htmlspecialchars($value, ENT_QUOTES) . "'" : (empty($value) ? 'NULL' : $value);
				$count++;
			}
			$sql .= "( " . $rColumn . " ) VALUES (" . $rData . " )";
		} else {
			$sql .= $data;
		}
		$this->INSERT = $sql;
		if ($rsql == TRUE) return $this->query('INSERT');
		return $this->result('INSERT');
	}
	/**
	 * delete data
	 *
	 *
	 * @param bool $rsql Use TRUE instead of executing a query the function will return the query as a string.
	 *
	 * @return bool
	 */
	function delete($rsql = FALSE)
	{
		if ($rsql === TRUE) {
			return $this->query('DELETE');
		} else {
			return $this->result('DELETE');
		}
	}
	function dummy_insert($data, $rsql = FALSE)
	{
		return TRUE;
	}
	function dummy_fail_insert($data, $rsql = FALSE)
	{
		return FALSE;
	}
	// SELECT
	function select($tabel, $columns = FALSE, $rsql = FALSE)
	{
		if ($rsql == TRUE) $this->COLOUM .= $this->column_to_string($columns);
		if ($rsql == TRUE) return $this->query($tabel);
		return $this->result_array($tabel);
	}
	/**
	 * Wrappert to contruct SQL query to access database.
	 *
	 *
	 * @param string @name of query type or default is SELECT
	 *
	 * @return array
	 */
	public function result_array($tabel = FALSE)
	{
		if ($tabel !== FALSE) $this->tabel($tabel);
		$result = $this->result($var = 'SELECT', $tabel);
		if ($result === FALSE) return array();
		return $result->fetch_all(MYSQLI_ASSOC);
	}
	public function result_row_array($tabel = FALSE)
	{
		if ($tabel !== FALSE) $this->tabel($tabel);
		$result = $this->result($var = 'SELECT', $tabel);
		if ($result === FALSE) return array();
		$result = $result->fetch_all(MYSQLI_ASSOC);
		if (empty($result)) return array();
		return end($result);
	}
	public function result_fetch_array($tabel = FALSE)
	{
		if ($tabel !== FALSE) $this->TABLE = $tabel;
		$result = $this->result($var = 'SELECT', $tabel);
		if ($result === FALSE) return array();
		return $result->fetch_array(MYSQLI_ASSOC);
	}
	public function result_fetch_object($tabel = FALSE)
	{
		if ($tabel !== FALSE) $this->TABLE = $tabel;
		$result = $this->result($var = 'SELECT', $tabel);
		if ($result === FALSE) return NULL;
		return $result->fetch_object();
	}
	public function count_rows($var = FALSE, $tabel = FALSE)
	{
		if ($tabel !== FALSE) $this->TABLE = $tabel;
		if ($var !== FALSE) $this->WHERE($var);
		$result = $this->result($var = 'SELECT', $tabel);
		if ($result === FALSE) return 0;
		$this->last_result = $result;
		return $result->num_rows;
	}

	// Query BUILDER
	public function query($var = 'SELECT')
	{

		if (empty($this->FROM)) $this->FROM = "FROM ";
		if (empty($this->COLOUM)) $this->COLOUM = "*";
		switch ($var) {
			case 'SELECT':
				$this->sql = "SELECT " . $this->COLOUM . " " . $this->FROM . $this->TABLE . $this->JOIN . $this->WHERE . $this->GROUP . $this->ORDER . $this->LIMIT;
				break;

			case 'INSERT':
				$this->sql = "INSERT INTO " . $this->TABLE . $this->INSERT;
				break;

			case 'UPDATE':
				$this->sql = "UPDATE " . $this->TABLE . $this->UPDATE . $this->WHERE;
				break;
			case 'DELETE':
				$this->sql = "DELETE FROM " . $this->TABLE . $this->WHERE;
				break;

			case 'ALTER':
				$this->sql = "ALTER TABLE " . $this->TABLE . $this->ALTER . $this->DROPCOL . $this->ADDCOL;
				break;
			default:
				$this->sql = $var;
				break;
		}
		return $this->sql;
	}
	// RESULT FUNCTION 
	public function result($var = 'SELECT')
	{
		$this->query($var);
		$result = $this->run();
		return $result;
	}

	public function run($sql = FALSE, $val = FALSE)
	{
		if ($sql !== FALSE) $this->sql = $sql;
		$result = $this->mysql->query($this->sql);
		$this->last_result = $result;
		$this->last_query = $this->sql;
		$this->last_error = $this->mysql->error;
		if ($this->last_error !== '') {
			trigger_error($this->last_error . '<br/><br/><code/>"' . $this->last_query . '"</code>', E_USER_WARNING);
		}
		// $this->mysql->close();
		return $result;
	}

	// RESET ALL
	public function reset($reset_tabel = TRUE)
	{
		$this->SELECT	= NULL;
		$this->WHERE	= NULL;
		$this->FROM		= NULL;
		$this->COLOUM 	= NULL;
		$this->ORDER	= NULL;
		$this->GROUP	= NULL;
		$this->ALTER	= NULL;
		$this->LIMIT	= NULL;
		$this->last_error = '';
		$this->last_query = '';
		if ($reset_tabel) $this->JOIN = NULL;
		return $this;
	}

	// WORKER
	private function column_to_string($columns, $tic = TRUE)
	{
		$sql = '';
		if (is_array($columns) === TRUE) {
			foreach ($columns as $key => $column) {
				if (is_string($column)) {
					if (stripos($column, " as ") == FALSE) {
						$column = ltrim(rtrim(trim($column), '`'), '`');
						if (!empty($sql)) $sql .= ", ";
						if ($tic) $column = "`" . $column . "`";
						$sql .= $column;
					} else {
						$column = preg_split("/ as /i", $column);
						$key = ltrim(rtrim(trim($column[0]), '`'), '`');
						if (!empty($sql)) $sql .= ", ";
						if ($tic) $key = "`" . $key . "`";
						$sql .= $key . " AS " . $column[1];
					}
				} else {
					trigger_error("Wrong data type column name!", E_USER_ERROR);
					return FALSE;
				}
			}
		} else if (is_string($columns)) {
			$columns = explode(",", $columns);
			foreach ($columns as $column) {
				if (stripos($column, " as ") == FALSE) {
					$column = ltrim(rtrim(trim($column), '`'), '`');
					if (!empty($sql)) $sql .= ", ";
					if ($tic) $column = "`" . $column . "`";
					$sql .= $column;
				} else {
					$column = preg_split("/ as /i", $column);
					$key = ltrim(rtrim(trim($column[0]), '`'), '`');
					if (!empty($sql)) $sql .= ", ";
					if ($tic) $key = "`" . $key . "`";
					$sql .= $key . " AS " . $column[1];
				}
			}
		} else {
			trigger_error("Wrong data type column name!", E_USER_ERROR);
			return FALSE;
		}
		return $sql;
	}
	private function condition_to_string($columns, $operator, $sparator)
	{
		if (is_bool($operator)) {
			$operator = $operator ? " = " : " <> ";
		} else if (is_string($operator)) {
			$operator = " " . $operator . " ";
		} else {
			trigger_error("Wrong sparator!", E_USER_ERROR);
			return FALSE;
		}
		$sql = '';
		if (is_array($columns) === TRUE) {
			$num = 0;
			foreach ($columns as $key => $value) {
				if ($num > 0) {
					$sql .= ' ' . $sparator . ' ';
				}
				if (is_string($key)) {
					$key = ltrim(rtrim(trim($key), '`'), '`');
					$value 	= is_string($value) && strpos($value, 'null') === false ? "'" . $value . "'" : $value;
					$key 	= is_string($key) ? "`" . $key . "`" . $operator : '';
					$sql 	.= $key . $value;
				} else {
					$sql 	.= $value;
				}
				$num++;
			}
		} else {
			$sql .= $columns;
		}
		return $sql;
	}
	/**
	 * info
	 *
	 * @param  string $key
	 * @return mixed arry or string
	 */
	public function info(string $key = '')
	{
		$this->reset();
		$info = $this->run('SHOW VARIABLES LIKE "%version%"')->fetch_all(MYSQLI_ASSOC);
		if (!empty($key)) {
			foreach ($info as $item) {
				if ($item['Variable_name'] == $key) return $item['Value'];
			}
			return NULL;
		} else return $info;
	}
	public function suport_version(string $version)
	{
		$db_version = $this->info('innodb_version') ?? '0';
		return version_compare($version, (string)$db_version, "<=");
	}
}
