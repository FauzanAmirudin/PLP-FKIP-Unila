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
			global $REPORT;
			
			if (empty(trim($komentar))) {
				$REPORT .= '<div class="info info-danger" style="margin-bottom: 10px; padding: 10px; border-radius: 6px; background: #fee2e2; color: #dc2626; border: 1px solid #f87171;"><a>Komentar atau catatan revisi wajib diisi untuk semua penilaian.</a></div>';
				echo $REPORT;
				return;
			}
			
			switch ($respons) {
				case 'Tidak Ada':
					$REPORT .= '<div class="info info-danger" style="margin-bottom: 10px; padding: 10px; border-radius: 6px; background: #fee2e2; color: #dc2626; border: 1px solid #f87171;"><a>Anda belum memilih status respons laporan.</a></div>';
					break;
				case 'Cukup':
				case 'Kurang':
					if ($this->report->response($id, $respons, $komentar)) {
						$REPORT .= '<div class="info info-success" style="margin-bottom: 10px; padding: 10px; border-radius: 6px; background: #dcfce7; color: #16a34a; border: 1px solid #4ade80;"><a>Response dan komentar berhasil disimpan.</a></div>';
					} else {
						$REPORT .= '<div class="info info-danger" style="margin-bottom: 10px; padding: 10px; border-radius: 6px; background: #fee2e2; color: #dc2626; border: 1px solid #f87171;"><a>Response gagal disimpan.</a></div>';
					}
					break;
				default:
					$REPORT .= '<div class="info info-danger" style="margin-bottom: 10px; padding: 10px; border-radius: 6px; background: #fee2e2; color: #dc2626; border: 1px solid #f87171;"><a>Pilihan respons tidak valid.</a></div>';
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
