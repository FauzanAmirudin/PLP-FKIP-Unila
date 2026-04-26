<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');

class test extends gf_controller
{
	function __construct()
	{
		parent::__construct();
		// Di sini biasanya dideklarasikan load model atau helper tambahan jika perlu
	}

	public function index()
	{
		// 1. Melempar variabel data ke View (opsional)
		$this->data['page']  = 'test'; // Variabel untuk mendeteksi menu aktif
		$this->data['judul'] = 'Halaman Uji Coba';
		$this->data['pesan'] = 'Ini adalah pesan yang dikirim dari Controller ke dalam View!';

		// 2. Jika user sudah login masuk ke dashboard (Ini meniru sifat halaman depan/public)
		if (is_login()) {
			redirect('user/dashboard');
		} else {
			// 3. Merakit View secara berurutan sesuai kaidah sistem
			$this->load->view("navigation", $this->data);   // Memuat Navbar Menu
			$this->load->view("page/test", $this->data);    // Memuat Konten Utama yang akan kita buat
			$this->load->view("footer", $this->data);       // Memuat Footer
		}
	}
}
