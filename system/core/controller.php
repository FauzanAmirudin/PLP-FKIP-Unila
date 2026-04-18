<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Main Controller 
|--------------------------------------------------------------------------
|
| Initial Controller aplications
|
*/
class gf_controller
{
	public $load;
	public $libs;
	public $system;
	function __construct()
	{
		$this->load = new GF_LOADER;
	}
	public function GF_PREPARE()
	{
		$this->controler_name = get_class($this);
		if ($this->load->model != null) {
			foreach ($this->load->model as $key => $value) {
				$this->$key = $value;
				$this->$key->GF_PREPARE();
			}
		}
		if ($this->libs != null) {
			foreach ($this->libs as $name => $libname) {
				require(GF_APP_PATH . DIRECTORY_SEPARATOR . "libs" . DIRECTORY_SEPARATOR . $libname . ".php");
				if ($name == FALSE) {
					$name = $libname;
				};
				if (class_exists($libname)) {
					$this->$name = new $libname();
				}
			}
		}
		if ($this->system != null) {
			foreach ($this->system as $sysname) {
				$classname = "gf_" . $sysname;
				if (class_exists($classname)) {
					$this->$sysname = new $classname();
				}
			}
		}
	}
	public function GF_EXIT()
	{
		if ($this->load->header == TRUE) {
			$GF_htmlpage = '<!Doctype html>' . chr(0x0A);;
			$GF_htmlpage .= '<html lang="' . GF_CONFIG['site_language'] . '">' . chr(0x0A);
			$GF_htmlpage .= '<head>' . chr(0x0A);
			$GF_htmlpage .= '<meta charset="utf-8">' . chr(0x0A);
			$GF_htmlpage .= '<meta name="viewport" content="width=device-width, initial-scale=1">' . chr(0x0A);
			$GF_htmlpage .= '<title>' . GF_CONFIG["site_title"] . '</title>';
			$GF_htmlpage .= '<meta name="description" content="' . GF_CONFIG["site_description"] . '">' . chr(0x0A);
			$GF_htmlpage .=	'<link rel="shortcut icon" href="' . '' . GF_CONFIG['base_url'] . '/' . GF_CONFIG['site_icon'] . '">' . chr(0x0A);
			$GF_htmlpage .= GF_CONFIG["extra-head"];
			if (is_array(GF_CONFIG['site_css'])) {
				foreach (GF_CONFIG['site_css'] as $css) {
					$GF_htmlpage .= '<link href="' . '' . GF_CONFIG['base_url'] . '/assets/';
					$GF_htmlpage .= $css;
					$GF_htmlpage .= '" rel="stylesheet" type="text/css">' . chr(0x0A);
				}
			} else {
				$GF_htmlpage .= '<link href="asset';
				$GF_htmlpage .= GF_CONFIG['site_css'];
				$GF_htmlpage .= '" rel="stylesheet" type="text/css">\n' . chr(0x0A);
			}

			$GF_htmlpage .= '</head>' . chr(0x0A);
			$GF_htmlpage .= '<body>' . chr(0x0A);
			echo (GF_CONFIG['sanitize_output'] == TRUE ? preg_replace("/\r|\n/", '', $GF_htmlpage) : $GF_htmlpage);
		}
		echo ($this->load->html);
		if ($this->load->script == TRUE) {
			/* jS Files for Applications */
			$GF_htmlpage = '';
			if (is_array(GF_CONFIG['site_js'])) {
				foreach (GF_CONFIG['site_js'] as $js) {
					$GF_htmlpage .= '<script type="text/javascript" src="' . GF_CONFIG['base_url'] . '/assets/';
					$GF_htmlpage .= $js;
					$GF_htmlpage .= '"></script>' . chr(0x0A);
				}
			} else {
				$GF_htmlpage .= '<script type="text/javascript" src="' . GF_CONFIG['base_url'] . '/assets/';
				$GF_htmlpage .= GF_CONFIG['site_js'];
				$GF_htmlpage .= '"></script>' . chr(0x0A);
			}
			/* Report System For Applications */
			if (isset($this->alert) && $this->alert != "") {
				$GF_htmlpage .= '<script type="text/javascript">';
				$GF_htmlpage .= 'alert("' . preg_replace("/\"/","'", $this->alert) . '")';
				$GF_htmlpage .= '</script>' . chr(0x0A);
			}
			$GF_htmlpage .= '</body>' . chr(0x0A);
			$GF_htmlpage .= '</html>' . chr(0x0A);
			echo (GF_CONFIG['sanitize_output'] == TRUE ? preg_replace("/\r|\n/", '', $GF_htmlpage) : $GF_htmlpage);
		}
	}
};
