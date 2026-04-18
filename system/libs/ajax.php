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
class gf_ajax extends gf_controller
{
	var $log;
	var $html;
	private $id;
	private $dir = GF_BASE_PATH . "/application/ajax/";
	function __construct($ajax_id)
	{
		$this->id = $ajax_id;
		$this->log = "Ajax ";
		$dir_ajax = $this->dir;
		if (is_dir($dir_ajax) && file_exists($dir_ajax . $ajax_id . ".php")) {
			$_stat = true;
			$this->log .= $ajax_id . " Ready!\n";
		} else {
			$this->log .= $ajax_id . " Fail to load\n";
		}
	}

	private function load_ajax()
	{
		ob_start();
		include_once($this->dir . $this->id . ".php");
		$html = ob_get_contents();
		ob_end_clean();
		$this->html = $html;
	}

	function do_ajax()
	{
		$this->load_ajax();
		return $this->html;
	}
	function log()
	{
		return $this->log;
	}
}
