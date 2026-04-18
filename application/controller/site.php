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
class site extends gf_controller
{
	function __construct()
	{
		require_login();
		parent::__construct();
		$this->data['user'] = session_get();
		$this->load->helper('dbconfig');
		$this->load->model('user_data', 'user');
		$this->load->model('server_data', 'server');
		$this->load->model('registration_data', 'registrasi');
	}
	public function index()
	{
		error403();
	}
	public function settings()
	{
		require_level("Admin, Operator");
		if (!empty($this->input->post())) {
			$statusPendaftaran 	= $this->input->post('statusPendaftaran');
			$tahunPendaftaram 	= $this->input->post('tahunPendaftaram');
			$periodePendaftaran = $this->input->post('periodePendaftaran');
			$valid = TRUE;
			if (!save_dbconfig("OPENREGISTER", $statusPendaftaran)) {
				$valid = FALSE;
				save_notification("Gagal memperbaharui pengaturan Registrsi<br>");
			}
			if (!save_dbconfig("CURENTYEAR", $tahunPendaftaram)) {
				$valid = FALSE;
				save_notification("Gagal memperbaharui pengaturan Tahun<br>");
			}
			if (!save_dbconfig("CURENTSEMESTER", $periodePendaftaran)) {
				$valid = FALSE;
				save_notification("Gagal memperbaharui pengaturan Semester<br>");
			}
			if ($valid == TRUE) save_notification("Pengaturaan Web diperhaharui.<br>");
		}
		if (is_level('Mahasiswa')) {
			$this->data['registration_done'] = $this->registrasi->status_check($this->data['user']["ID"]);
		}
		$this->data['config'] = get_dbconfig();
		$this->alert = implode("\n", get_notification());
		$this->load->view("header", $this->data);
		$this->load->view("navigation", $this->data);
		$this->load->view("sidebar", $this->data);
		$this->load->view("page/settingsite", $this->data);
		$this->load->view("footer", $this->data);
	}
	public function versionhistory()
	{
		if (is_level('Mahasiswa')) {
			$this->data['registration_done'] = $this->registrasi->status_check($this->data['user']["ID"]);
		}
		$this->data['db_version'] = $this->server->version();
		$this->data['notification'] = implode("<br/>", get_notification());
		$this->load->view("header", $this->data);
		$this->load->view("navigation", $this->data);
		$this->load->view("sidebar", $this->data);
		$this->load->view("page/versionhistory", $this->data);
		$this->load->view("footer", $this->data);
	}
}
