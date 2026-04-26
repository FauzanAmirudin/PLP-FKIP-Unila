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
		$this->load->model('informasi_data', 'informasi');
		$this->load->model('gallery_data', 'gallery');
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
		$this->load->view("navigation", $this->data);
		$this->load->view("sidebar", $this->data);
		$this->load->view("page/versionhistory", $this->data);
		$this->load->view("footer", $this->data);
	}
	/**
	 * Kelola Informasi - List all
	 */
	public function informasi()
	{
		require_level("Admin, Operator");
		$this->data['informasi_list'] = $this->informasi->list_all();
		$this->data['notification'] = implode("<br/>", get_notification());
		$this->load->view("navigation", $this->data);
		$this->load->view("sidebar", $this->data);
		$this->load->view("page/kelolainformasi", $this->data);
		$this->load->view("footer", $this->data);
	}
	/**
	 * Kelola Informasi - Create
	 */
	public function informasi_create()
	{
		require_level("Admin, Operator");
		if (!empty($this->input->post())) {
			$data = [
				"JUDUL"     => $this->input->post('judul'),
				"TANGGAL"   => $this->input->post('tanggal'),
				"INFORMASI" => $this->input->post('informasi'),
				"TAG"       => $this->input->post('tag'),
				"PENULIS"   => $this->input->post('penulis'),
			];
			// Handle optional image upload
			if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
				$folder = 'uploads' . DIRECTORY_SEPARATOR . 'informasi-images';
				$file = 'info_' . time();
				$upload = $this->input->upload('gambar', $file, $folder, [
					"type"      => 'jpg, jpeg, png',
					"sizelimit" => '1000',
					"update"    => TRUE
				]);
				if ($upload['status']) {
					$data['GAMBAR'] = $upload['data']['FILELINK'];
				} else {
					save_notification($upload['report']);
					redirect("site/informasi_create");
					return;
				}
			}
			$result = $this->informasi->insert($data);
			if ($result) {
				save_notification("Informasi berhasil ditambahkan.");
			} else {
				save_notification("Gagal menambahkan informasi.");
			}
			redirect("site/informasi");
			return;
		}
		$this->data['edit_mode'] = false;
		$this->data['article'] = null;
		$this->data['notification'] = implode("<br/>", get_notification());
		$this->load->view("navigation", $this->data);
		$this->load->view("sidebar", $this->data);
		$this->load->view("page/kelolainformasiform", $this->data);
		$this->load->view("footer", $this->data);
	}
	/**
	 * Kelola Informasi - Edit
	 */
	public function informasi_edit()
	{
		require_level("Admin, Operator");
		$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
		$article = $this->informasi->get($id);
		if (empty($article)) {
			save_notification("Informasi tidak ditemukan.");
			redirect("site/informasi");
			return;
		}
		if (!empty($this->input->post())) {
			$data = [
				"JUDUL"     => $this->input->post('judul'),
				"TANGGAL"   => $this->input->post('tanggal'),
				"INFORMASI" => $this->input->post('informasi'),
				"TAG"       => $this->input->post('tag'),
				"PENULIS"   => $this->input->post('penulis'),
			];
			// Handle remove image checkbox
			if ($this->input->post('hapus_gambar') == '1') {
				$data['GAMBAR'] = NULL;
			}
			// Handle optional image upload (overrides remove)
			if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
				$folder = 'uploads' . DIRECTORY_SEPARATOR . 'informasi-images';
				$file = 'info_' . $id . '_' . time();
				$upload = $this->input->upload('gambar', $file, $folder, [
					"type"      => 'jpg, jpeg, png',
					"sizelimit" => '1000',
					"update"    => TRUE
				]);
				if ($upload['status']) {
					$data['GAMBAR'] = $upload['data']['FILELINK'];
				} else {
					save_notification($upload['report']);
					redirect("site/informasi_edit&id=" . $id);
					return;
				}
			}
			$result = $this->informasi->update($id, $data);
			if ($result) {
				save_notification("Informasi berhasil diperbarui.");
			} else {
				save_notification("Gagal memperbarui informasi.");
			}
			redirect("site/informasi");
			return;
		}
		$this->data['edit_mode'] = true;
		$this->data['article'] = $article;
		$this->data['notification'] = implode("<br/>", get_notification());
		$this->load->view("navigation", $this->data);
		$this->load->view("sidebar", $this->data);
		$this->load->view("page/kelolainformasiform", $this->data);
		$this->load->view("footer", $this->data);
	}
	/**
	 * Kelola Informasi - Delete
	 */
	public function informasi_delete()
	{
		require_level("Admin, Operator");
		$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
		if ($id > 0) {
			$result = $this->informasi->delete($id);
			if ($result) {
				save_notification("Informasi berhasil dihapus.");
			} else {
				save_notification("Gagal menghapus informasi.");
			}
		}
		redirect("site/informasi");
	}

	/**
	 * Kelola Gallery - List all
	 */
	public function gallery()
	{
		require_level("Admin, Operator");
		$this->data['gallery_list'] = $this->gallery->list_all();
		$this->data['notification'] = implode("<br/>", get_notification());
		$this->load->view("navigation", $this->data);
		$this->load->view("sidebar", $this->data);
		$this->load->view("page/kelolagallery", $this->data);
		$this->load->view("footer", $this->data);
	}

	/**
	 * Kelola Gallery - Upload foto baru
	 */
	public function gallery_create()
	{
		require_level("Admin, Operator");
		if (!empty($this->input->post())) {
			if (!isset($_FILES['gambar']) || $_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
				save_notification("Foto wajib dipilih untuk diupload.");
				redirect("site/gallery_create");
				return;
			}
			$folder   = 'uploads' . DIRECTORY_SEPARATOR . 'gallery-images';
			$filename = 'gallery_' . time();
			$upload   = $this->input->upload('gambar', $filename, $folder, [
				"type"      => 'jpg, jpeg, png',
				"sizelimit" => '1000',
				"update"    => TRUE
			]);
			if ($upload['status']) {
				$data = [
					"GAMBAR"      => $upload['data']['FILELINK'],
					"KETERANGAN"  => $this->input->post('keterangan'),
					"PENULIS"     => isset($this->data['user']['USERID']) ? $this->data['user']['USERID'] : 'Admin',
				];
				$this->gallery->insert($data);
				save_notification("Foto berhasil diupload ke galeri.");
			} else {
				save_notification($upload['report']);
			}
			redirect("site/gallery");
			return;
		}
		$this->data['edit_mode']    = false;
		$this->data['photo']        = null;
		$this->data['notification'] = implode("<br/>", get_notification());
		$this->load->view("navigation", $this->data);
		$this->load->view("sidebar", $this->data);
		$this->load->view("page/kelolagalleryform", $this->data);
		$this->load->view("footer", $this->data);
	}

	/**
	 * Kelola Gallery - Edit keterangan
	 */
	public function gallery_edit()
	{
		require_level("Admin, Operator");
		$id    = isset($_GET['id']) ? (int)$_GET['id'] : 0;
		$photo = $this->gallery->get($id);
		if (empty($photo)) {
			save_notification("Foto tidak ditemukan.");
			redirect("site/gallery");
			return;
		}
		if (!empty($this->input->post())) {
			$data = [
				"KETERANGAN" => $this->input->post('keterangan'),
			];
			$result = $this->gallery->update($id, $data);
			save_notification($result ? "Keterangan foto berhasil diperbarui." : "Gagal memperbarui keterangan.");
			redirect("site/gallery");
			return;
		}
		$this->data['edit_mode']    = true;
		$this->data['photo']        = $photo;
		$this->data['notification'] = implode("<br/>", get_notification());
		$this->load->view("navigation", $this->data);
		$this->load->view("sidebar", $this->data);
		$this->load->view("page/kelolagalleryform", $this->data);
		$this->load->view("footer", $this->data);
	}

	/**
	 * Kelola Gallery - Hapus foto
	 */
	public function gallery_delete()
	{
		require_level("Admin, Operator");
		$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
		if ($id > 0) {
			$photo = $this->gallery->get($id);
			if (!empty($photo)) {
				// Hapus file fisik dari server
				$filePath = GF_BASE_PATH . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $photo['GAMBAR']);
				if (file_exists($filePath)) {
					@unlink($filePath);
				}
				$result = $this->gallery->delete($id);
				save_notification($result ? "Foto berhasil dihapus." : "Gagal menghapus foto.");
			} else {
				save_notification("Foto tidak ditemukan.");
			}
		}
		redirect("site/gallery");
	}
}
