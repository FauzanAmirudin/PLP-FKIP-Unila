<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Main Loader 
|--------------------------------------------------------------------------
|
| Initial Loader aplications
|
*/
class GF_LOADER
{
	public $db;
	public $model;
	public $helper;
	public $html = '';
	public $header = TRUE;
	public $script = TRUE;
	public function view($name, $data = [], $sanitize_output = FALSE)
	{
		if (is_array($data) && count($data) > 0) {
			foreach ($data as $key => $value) {
				$$key = $value;
			}
		}
		ob_start();
		include(GF_APP_PATH . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . $name . ".php");
		$buffer = ob_get_contents();
		ob_end_clean();
		if (GF_CONFIG['sanitize_output'] == TRUE) {
			$search = array('/\>[^\S ]+/s', /* strip whitespaces after tags, except space */ '/[^\S ]+\</s', /* strip whitespaces before tags, except space */ '/(\s)+/s', /* shorten multiple whitespace sequences */ '/<!--(.|\s)*?-->/' /* Remove HTML comments */);
			$replace = array('>', '<', '\\1', '');
			$buffer = preg_replace($search, $replace, $buffer);
		}
		$this->html .= $buffer;
	}
	public function model($class, $name = FALSE)
	{
		if (file_exists(GF_MODEL_PATH . DIRECTORY_SEPARATOR . $class . ".php")) {
			require(GF_MODEL_PATH . DIRECTORY_SEPARATOR . $class . ".php");
			$new_model = new $class();
			if ($name == FALSE) {
				$name = $class;
			}
			$this->model[$name] = $new_model;
		} else trigger_error("Model " . $class . " file not found", E_USER_WARNING); 
	}
	public function database($dbname = 'default', $name = FALSE, $return = FALSE)
	{
		if (isset(GF_DB[$dbname])) {
			if ($name == FALSE) {
				$name = 'db';
			};
			$new_database =  new gf_sql(GF_DB[$dbname]);
			if ($return == TRUE) {
				return $new_database;
			} else {
				$this->db[$name] = $new_database;
			}
		} else {
			trigger_error("Database config not found", E_USER_WARNING);
		}
	}
	public function helper($hlpname)
	{
		if (file_exists(GF_HLP_PATH . DIRECTORY_SEPARATOR . $hlpname . ".php")) {
			require(GF_HLP_PATH . DIRECTORY_SEPARATOR . $hlpname . ".php");
		} else if (file_exists(GF_SYS_PATH . "/helper/" . $hlpname . ".php")) {
			require(GF_SYS_PATH . "/helper/" . $hlpname . ".php");
		} else {
			trigger_error("Helper " . $hlpname . " file not found", E_USER_WARNING);
		}
	}
	public function helper_class($hlpname, $name = FALSE, $return = FALSE)
	{
		require(GF_APP_PATH . "/" . GF_HLP_PATH . "/" . $hlpname . ".php");
		if ($name == FALSE) {
			$name = $hlpname;
		};
		$new_helper =  new $hlpname();
		if ($return == TRUE) {
			return $new_helper;
		} else {
			$this->helper[$name] = $new_helper;
		}
	}
}
