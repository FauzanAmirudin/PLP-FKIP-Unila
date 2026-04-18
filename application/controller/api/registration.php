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
class registration extends gf_controller
{
	function __construct()
	{
		require_login();
		parent::__construct();
		$this->load->helper('dbconfig');
		$this->load->model('user_data', 'user');
		$this->load->model('mahasiswa_data', 'mahasiswa');
		$this->load->model('registration_data', 'registrasi');
		$this->load->model('kaprodi_data', 'kaprodi');
		$this->load->model('extra_data', 'extra');
		$this->data['user'] = session_get();
	}
	public function index()
	{
		error_page('505');
	}
	public function validatioon()
	{
		require_level("Admin, Operator");
		$this->data['enableregister'] = get_dbconfig('OPENREGISTER');
		$response = '';
		if (!empty($this->input->post())) {
			$berkasid = $this->input->post('idmahasiswa');
			$npm = $this->input->post('npmmahasiswa');
			$status = $this->input->post('status');
			$catatan = $this->input->post('catatanberkas');
			$catatan = strip_tags(!empty($catatan) ? $catatan : "Tidak ada.");
			$berkas = $this->registrasi->check_by($berkasid, "`datastatus`.`BRKSKEY`");
			$mahasiswa = $this->mahasiswa->check_by($npm, "NPM");
			if (!empty($berkas)) {
				switch ($status) {
					case 'approved':
						$state = 'Disetujui';
						if ($state !== $berkas["STATUSBERKAS"]) {
							$result = $this->registrasi->status_update($berkasid, $state, $catatan, session_get('USERID'), NULL, NULL);
							if ($result) $response .= "Status berkas " . $mahasiswa["NAMA"] . " telah diubah menjadi <b>" . $result["STATUSBERKAS"] . "</b>.";
						} else $response .= 'Status berkas ' . $mahasiswa["NAMA"] . ' tidak diubah, berkas sudah berstatus ' . $berkas["STATUSBERKAS"] . ' yang dilakukan oleh: ' . $berkas["VALIDATOR"] . ' pada tanggal ' . $berkas["DATEVALID"] . '.';
						break;

					case 'rejected':
						$state = 'Ditolak';
						$result = $this->registrasi->status_update($berkasid, $state, $catatan, session_get('USERID'), NULL, NULL);
						if ($result) $response .= "Status berkas " . $mahasiswa["NAMA"] . " telah diubah menjadi <b>" . $result["STATUSBERKAS"] . "</b>.";
						break;

					case 'delete':
						$response .= "Maaf, fitur delete belum tersedia.";
						break;

					default:
						$response .= "Maaf, fitur ini belum tersedia.";
						break;
				}
			} else $response .= 'Berkas tidak ditemukan!';
		} else $response .= "Maaf anda tidak memiliki izin untuk melakukan perubahan status.";
		echo $response;
	}
	public function comment()
	{
		$berkasid = $this->input->get('berkas');
		$status = $this->registrasi->check($berkasid);
		if (!empty($status["NOTEBERKAS"])) {
			echo $status['NOTEBERKAS'];
		}
		exit;
	}
}