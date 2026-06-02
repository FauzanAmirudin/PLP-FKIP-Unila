<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');

class visimisi extends gf_controller
{
	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->data['page'] = 'visimisi';

		$this->load->view("navigation", $this->data);
		$this->load->view("page/visimisi", $this->data);
		$this->load->view("footer", $this->data);
	}
}
