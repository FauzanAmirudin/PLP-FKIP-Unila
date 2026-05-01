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
			$komentar = strip_tags(isset($_POST['komentar']) ? $_POST['komentar'] : '');
			if (empty(trim($komentar))) {
				$this->data['error'] = 'Komentar atau catatan revisi wajib diisi untuk semua penilaian.';
				$this->load->view('ajax/report_message', $this->data);
				return;
			}
			
			switch ($respons) {
				case 'Tidak Ada':
					$this->data['error'] = 'Anda belum memilih status respons laporan.';
					break;
				case 'Cukup':
				case 'Kurang':
					if ($this->report->response($id, $respons, $komentar)) {
						$this->data['success'] = 'Response dan komentar berhasil disimpan.';
					} else {
						$this->data['error'] = 'Response gagal disimpan.';
					}
					break;
				default:
					$this->data['error'] = 'Pilihan respons tidak valid.';
					break;
			}
			$this->load->view('ajax/report_message', $this->data);
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
