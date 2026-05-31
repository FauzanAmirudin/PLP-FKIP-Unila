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
		$this->validate();
	}
	public function submit($data)
	{
		$id = $this->getID($data);
		if ($this->permision) {
			$enableregister = get_dbconfig('OPENREGISTER');
			$registration_done = $this->registrasi->status_check($id);

			if (!$enableregister) {
				$report = "Upload Gagal: Pendaftaran saat ini sedang ditutup.";
			} else if ($registration_done) {
				$report = "Upload Gagal: Pendaftaran Anda telah disetujui, Anda tidak dapat merubah berkas pendaftaran lagi.";
			} else {
				$file = $this->data['user']['USERID'] . "(" . $this->data['user']['ID'] . ")";
				$folder = 'uploads/berkas-pendaftaran/' . get_dbconfig("CURENTYEAR") . '-files/' . str_replace(" ", "_", get_dbconfig("CURENTSEMESTER")) . "/" . $file;
				$upload = $this->input->upload('file', $file, $folder, array("type" => 'zip', "sizelimit" => '1000', "update" => TRUE));
				if ($upload['status']) {
					$this->registrasi->save($id, $upload['data']["FILELINK"], $this->data['user']['USERID']);
				}
				$report = $upload['report'];
			}
		} else {
			$report = "Anda tidak memeiliki izin untuk meng upload file ini.";
		}
		save_notification($report);
		redirect("mahasiswa/pendaftaran/" . $id);
	}
	public function validate()
	{
		require_level("Admin, Operator");
		$this->data['config'] = get_dbconfig();
		$this->data['enableregister'] = get_dbconfig('OPENREGISTER');

		$formSubmitted = isset($_GET['tahun']);
		$tahun   = !empty($_GET['tahun'])   ? (int)$this->input->get('tahun')   : NULL;
		$periode = $formSubmitted            ? (isset($_GET['periode']) && $_GET['periode'] !== '' ? $_GET['periode'] : NULL) : '__DEFAULT__';
		$npm     = $this->input->get('npm');
		$prodi   = $this->input->get('prodi');
		$berkas  = $this->input->get('status');

		$this->data['alltahun'] = $this->registrasi->register_year();

		// Jika form belum disubmit, default ke tahun terbaru
		if ($tahun === NULL && !$formSubmitted) {
			$tahun = !empty($this->data['alltahun']) ? (int)current($this->data['alltahun'])['TAHUNDAFTAR'] : NULL;
		}
		$this->data['tahun'] = $tahun;

		$this->data['allperiode'] = $this->registrasi->register_periode((int)$tahun);

		// Jika form belum disubmit, default ke periode pertama dari tahun aktif
		if ($periode === '__DEFAULT__') {
			$periode = !empty($this->data['allperiode']) ? current($this->data['allperiode'])['PERIODEDAFTAR'] : NULL;
		}
		$this->data['periode'] = $periode;

		$this->data['allprodi'] = $this->registrasi->register_prodi($this->data['tahun'], $this->data['periode']);
		$this->data['prodi'] = !empty($prodi) ? $prodi : NULL;
		$this->data['berkas'] = !empty($berkas) ? $berkas : NULL;
		$this->data['npm'] = !empty($npm) ? $npm : NULL;

		$this->data['registration_list'] = $this->registrasi->list($this->data['tahun'], $this->data['periode'], $this->data['berkas'], $this->data['prodi'], $this->data['npm']);

		$this->data['notification'] = implode("<br/>", get_notification());
		$this->load->view("navigation", $this->data);
		$this->load->view("sidebar", $this->data);
		$this->load->view("page/registrationvalidate", $this->data);
		$this->load->view("footer", $this->data);
	}
	public function data()
	{
		require_level("Admin, Monitor, Operator");
		$this->data['config'] = get_dbconfig();
		$this->data['enableregister'] = get_dbconfig('OPENREGISTER');

		// Gunakan $_GET langsung untuk mendeteksi apakah form sudah di-submit
		// input->get() mengembalikan NULL baik ketika key tidak ada maupun ketika value kosong,
		// sehingga tidak bisa membedakan 'belum filter' vs 'filter semua periode'
		$formSubmitted = isset($_GET['tahun']); // form selalu kirim tahun jika sudah di-submit
		$tahun   = !empty($_GET['tahun'])   ? (int)$this->input->get('tahun')   : NULL;
		$periode = $formSubmitted            ? (isset($_GET['periode']) && $_GET['periode'] !== '' ? $_GET['periode'] : NULL) : '__DEFAULT__';
		$npm     = $this->input->get('npm');
		$prodi   = $this->input->get('prodi');
		$berkas  = $this->input->get('status');

		$this->data['alltahun'] = $this->registrasi->register_year();

		// Jika form belum disubmit, default ke tahun terbaru
		if ($tahun === NULL && !$formSubmitted) {
			$tahun = !empty($this->data['alltahun']) ? (int)current($this->data['alltahun'])['TAHUNDAFTAR'] : NULL;
		}
		$this->data['tahun'] = $tahun;

		$this->data['allperiode'] = $this->registrasi->register_periode((int)$tahun);

		// Jika form belum disubmit, default ke periode pertama dari tahun aktif
		if ($periode === '__DEFAULT__') {
			$periode = !empty($this->data['allperiode']) ? current($this->data['allperiode'])['PERIODEDAFTAR'] : NULL;
		}
		$this->data['periode'] = $periode;

		$this->data['allprodi'] = $this->registrasi->register_prodi((int)$tahun, $periode);
		$this->data['prodi'] = $prodi;

		$this->data['npm'] = $npm;
		$this->data['berkas'] = !empty($berkas) ? $berkas : NULL;

		// Kirim status berkas ke model list
		$this->data['mahasiswa'] = $this->registrasi->list(
			$tahun,
			$periode,
			$this->data['berkas'],
			$prodi,
			$npm
		);

		$data = $this->registrasi->statistic();

		$labels  = $series = [];
		$barCount = 0;
		foreach ($data as $n => $d) {
			array_push($labels, $d["TITLE"] == NULL ? "Kumulatif Data Lampau" : $d["TITLE"] . " (" . $d["SUBTITLE"] . ")");
			array_push($series, $d["JUMLAHPESERTA"]);
			$barCount++;
		}

		$this->data['statistic'] = array(
			"labels" => $labels,
			"series" => $series
		);

		$this->data["barCount"] = $barCount;
		$this->data['notification'] = implode("<br/>", get_notification());
		$this->load->view("navigation", $this->data);
		$this->load->view("sidebar", $this->data);
		$this->load->view("page/registrationlist", $this->data);
		$this->load->view("footer", $this->data);
	}
	
	public function export_excel()
	{
		require_level("Admin, Monitor, Operator");
		
		$tahun = $this->input->get('tahun');
		$periode = $this->input->get('periode');
		$npm = $this->input->get('npm');
		$prodi = $this->input->get('prodi');
		$berkas = $this->input->get('status');

		$alltahun = $this->registrasi->register_year();
		$tahun = !empty($tahun) ? $tahun : (!empty($alltahun) ? current($alltahun)['TAHUNDAFTAR'] : NULL);
		
		$allperiode = $this->registrasi->register_periode((int)$tahun);
		$periode = ($periode !== NULL) ? $periode : (!empty($allperiode) ? current($allperiode)['PERIODEDAFTAR'] : NULL);

		$mahasiswa = $this->registrasi->list($tahun, $periode, $berkas, $prodi, $npm);

		// Suppress all PHP warnings/notices so they don't corrupt the binary file
		error_reporting(0);
		ini_set('display_errors', '0');

		require_once GF_BASE_PATH . '/system/plugins/autoload.php';
		
		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		
		$sheet->setCellValue('A1', 'NO');
		$sheet->setCellValue('B1', 'NPM');
		$sheet->setCellValue('C1', 'NAMA');
		$sheet->setCellValue('D1', 'PROGRAM STUDI');
		$sheet->setCellValue('E1', 'JENIS KELAMIN');
		$sheet->setCellValue('F1', 'NO TELEPON');
		$sheet->setCellValue('G1', 'STATUS BERKAS');

		$sheet->getStyle('A1:G1')->getFont()->setBold(true);

		$row = 2;
		$no = 1;
		if ($mahasiswa != FALSE) {
			foreach ($mahasiswa as $r) {
				$statusBadge = (isset($r["STATUSBERKAS"]) && $r["STATUSBERKAS"] != FALSE) ? $r["STATUSBERKAS"] : "Pengajuan";
				if ($berkas !== NULL && $berkas !== "" && $statusBadge != $berkas) continue;
				
				$sheet->setCellValue('A' . $row, $no);
				$sheet->setCellValueExplicit('B' . $row, $r['NPM'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
				$sheet->setCellValue('C' . $row, $r['NAMA']);
				$sheet->setCellValue('D' . $row, $r['PROGRAMSTUDI']);
				$sheet->setCellValue('E' . $row, $r['JENISKELAMIN']);
				$sheet->setCellValueExplicit('F' . $row, $r['NOTELEPON'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
				$sheet->setCellValue('G' . $row, $statusBadge);
				
				$row++;
				$no++;
			}
		}

		$filename = "Data_Peserta_PLP_" . ($tahun ? $tahun : "All") . "_" . ($periode ? str_replace(' ', '', $periode) : "All") . ".xlsx";

		// Write to a temp file first — this guarantees the binary is clean
		// with absolutely no PHP notices/HTML contaminating it
		$tempFile = tempnam(sys_get_temp_dir(), 'plp_excel_');
		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
		$writer->save($tempFile);
		$filesize = filesize($tempFile);

		// Wipe every output buffer before sending the binary
		while (ob_get_level() > 0) {
			ob_end_clean();
		}

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Content-Length: ' . $filesize);
		header('Cache-Control: max-age=0, no-store, no-cache, must-revalidate');
		header('Pragma: no-cache');

		readfile($tempFile);
		unlink($tempFile);
		exit;
	}

	public function assignment()
	{
		require_level("Admin");
		// Halaman ini hanya memuat view untuk Bulk Assignment.
		// Upload file diproses secara mandiri oleh API di application/controller/api/upload.php

		$this->data['config'] = get_dbconfig();
		$this->alert = implode("\n", get_notification());
		$this->load->view("navigation", $this->data);
		$this->load->view("sidebar", $this->data);
		$this->load->view("page/registrationassignment", $this->data);
		$this->load->view("footer", $this->data);
	}
	private function getID($data)
	{
		if (is_level("Admin, Operator")) {
			$id = $data[0];
			$this->permision = TRUE;
		} else {
			$id = $this->data['user']["ID"];
			if ($id == $data[0]) {
				$this->permision = TRUE;
			} else $this->permision = FALSE;
		}
		return $id;
	}
}
