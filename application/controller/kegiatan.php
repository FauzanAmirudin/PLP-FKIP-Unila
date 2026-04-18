<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Contorler
|--------------------------------------------------------------------------
|
| Controler aplications
|
*/
class kegiatan extends gf_controller
{
	function __construct()
	{
		parent::__construct();
		require_login();
		$this->load->helper('dbconfig');
		$this->load->model('jadwal');
		$this->data['user'] = session_get();
		$this->load->model('registration_data', 'registrasi');
		$this->load->model('server_data', 'server');
	}
	public function jadwal()
	{
		if (is_level('Mahasiswa')) {
			$this->data['registration_done'] = $this->registrasi->status_check($this->data['user']["ID"]);
		}
		$this->data['notification'] = implode("<br/>", get_notification());
		$this->data['jadwals'] = $this->jadwal->list();
		$this->load->view("header", $this->data);
		$this->load->view("navigation", $this->data);
		$this->load->view("sidebar", $this->data);
		$this->load->view("page/jadwal", $this->data);
		$this->load->view("footer", $this->data);
	}
}
