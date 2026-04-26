<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');

class gallery extends gf_controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('gallery_data', 'gallery');
	}

	public function index()
	{
		$this->data['page'] = 'gallery';

		$perPage = 12;
		$currentPage = isset($_GET['pg']) ? max(1, (int)$_GET['pg']) : 1;
		$offset = ($currentPage - 1) * $perPage;

		$this->data['photos']       = $this->gallery->list($perPage, $offset);
		$this->data['total']        = $this->gallery->count();
		$this->data['current_page'] = $currentPage;
		$this->data['per_page']     = $perPage;
		$this->data['total_pages']  = max(1, ceil($this->data['total'] / $perPage));

		$this->load->view("navigation", $this->data);
		$this->load->view("page/gallery", $this->data);
		$this->load->view("footer", $this->data);
	}
}
