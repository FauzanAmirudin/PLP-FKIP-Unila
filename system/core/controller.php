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
#[AllowDynamicProperties]
class gf_controller
{
	public $load;
	public $libs;
	public $system;
	// PHP 8.2+ melarang dynamic properties — deklarasikan secara eksplisit
	public $data          = [];
	public $permision     = FALSE;
	public $alert         = '';
	public $controler_name = '';
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
				$clean_alert = str_replace(array('\n', '\r', "\n", "\r"), array('<br>', '', '<br>', ''), $this->alert);
				
				$GF_htmlpage .= '
				<div id="modernAlert" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); z-index: 999999; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
					<div style="background: #ffffff; border-radius: 12px; padding: 24px; width: 90%; max-width: 380px; text-align: center; box-shadow: 0 10px 40px rgba(0,0,0,0.2); animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
						<div style="background: #fdf0fa; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
							<svg viewBox="0 0 24 24" fill="none" stroke="#C562AF" stroke-width="2.5" width="28" height="28">
								<circle cx="12" cy="12" r="10"></circle>
								<line x1="12" y1="8" x2="12" y2="12"></line>
								<line x1="12" y1="16" x2="12.01" y2="16"></line>
							</svg>
						</div>
						<h3 style="font-family: \'Poppins\', sans-serif; font-size: 18px; font-weight: 600; color: #1e293b; margin: 0 0 10px;">Informasi Sistem</h3>
						<p style="font-family: \'Poppins\', sans-serif; font-size: 14px; color: #4b5563; margin: 0 0 24px; line-height: 1.6; text-align: left;">' . $clean_alert . '</p>
						<button onclick="document.getElementById(\'modernAlert\').style.display=\'none\'" style="background: #C562AF; color: #fff; border: none; padding: 10px 24px; border-radius: 8px; font-family: \'Poppins\', sans-serif; font-weight: 600; font-size: 14px; cursor: pointer; width: 100%; transition: background 0.2s;">Tutup</button>
					</div>
				</div>
				<style>
					@keyframes popIn {
						0% { opacity: 0; transform: scale(0.9); }
						100% { opacity: 1; transform: scale(1); }
					}
				</style>' . chr(0x0A);
			}
			$GF_htmlpage .= '</body>' . chr(0x0A);
			$GF_htmlpage .= '</html>' . chr(0x0A);
			echo (GF_CONFIG['sanitize_output'] == TRUE ? preg_replace("/\r|\n/", '', $GF_htmlpage) : $GF_htmlpage);
		}
	}
};
