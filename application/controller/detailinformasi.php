<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');

class detailinformasi extends gf_controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('informasi_data', 'informasi');
	}

	public function index()
	{
		$this->data['page'] = 'detailinformasi';
		$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
		$article = $this->informasi->get($id);

		if (empty($article)) {
			redirect("informasi");
			return;
		}

		$this->data['article'] = $article;
		$this->data['recent'] = $this->informasi->recent(3);

		$this->load->view("navigation", $this->data);
		$this->load->view("page/detailinformasi", $this->data);
		$this->load->view("footer", $this->data);
	}
}
