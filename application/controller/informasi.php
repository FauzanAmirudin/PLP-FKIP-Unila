<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');

class informasi extends gf_controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('informasi_data', 'informasi');
	}

	public function index()
	{
		$this->data['page'] = 'informasi';
		$page = isset($_GET['pg']) ? max(1, (int)$_GET['pg']) : 1;
		$perPage = 4;
		$offset = ($page - 1) * $perPage;

		$this->data['articles'] = $this->informasi->list($perPage, $offset);
		$this->data['total'] = $this->informasi->count();
		$this->data['current_page'] = $page;
		$this->data['per_page'] = $perPage;
		$this->data['total_pages'] = max(1, ceil($this->data['total'] / $perPage));
		$this->data['recent'] = $this->informasi->recent(3);

		$this->load->view("navigation", $this->data);
		$this->load->view("page/informasi", $this->data);
		$this->load->view("footer", $this->data);
	}
}
