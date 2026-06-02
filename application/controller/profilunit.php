<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');

class profilunit extends gf_controller
{
	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->data['page'] = 'profilunit';

		$this->load->view("navigation", $this->data);
		$this->load->view("page/profilunit", $this->data);
		$this->load->view("footer", $this->data);
	}
}
