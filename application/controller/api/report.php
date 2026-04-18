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
class report extends gf_controller
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
		$this->load->model('report_data', 'report');
		$this->load->model('extra_data', 'extra');
		$this->data['user'] = session_get();
	}
	public function index()
	{
		error_page('505');
	}
	public function response()
	{
		require_level('Admin, DPL, Monitor');
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$id = strip_tags($_GET['id']);
			$respons = strip_tags($_POST['respons']);
			$komentar = strip_tags(isset($_POST['komentar']) ? $_POST['komentar'] : 'Tidak Ada');
			global $REPORT;
			switch ($respons) {
				case 'Tidak Ada':
					$REPORT .= '<div class="info info-danger"><a>Anda belum memasukan respons laopran</a></div>';
					break;
				case 'Cukup':
					if ($this->report->response($id, $respons, $komentar)) {
						$REPORT .= '<div class="info info-success"><a>Response berhasil di simpan</a></div>';
					} else {
						$REPORT .= '<div class="info info-danger"><a>Response gagal disimpan</a></div>';
					}
					break;
				case 'Kurang':
					if ($this->report->response($id, $respons, $komentar)) {
						$REPORT .= '<div class="info info-success"><a>Response berhasil di simpan</a></div>';
					} else {
						$REPORT .= '<div class="info info-danger"><a>Response gagal disimpan</a></div>';
					}
					break;
				default:
					$REPORT .= '<div class="info info-danger"><a>Anda belum memasukan respons laopran</a></div>';
					break;
			}
			echo $REPORT;
		}
	}
	public function comment()
	{
		require_level('Admin, Operator, Monitor, DPL, Mahasiswa');
		$id = strip_tags($_GET['id']);
		$report = $this->report->get($id);
		if (!empty($report["RESPONSE"])) {
			echo "<b>Respon DPL:</b><b> " . $report["RESPONSE"] . "</b>";
			if ($report['KRITIKSARAN'] != null) {
				echo "<br/><b>Saran DPL:</b><br><a>" . $report['KRITIKSARAN'] . "</a>";
			}
		}
	}
}
