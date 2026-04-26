<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');

class about extends gf_controller
{
	function __construct()
	{
		parent::__construct();
		// Load helpers if needed
	}

	public function index()
	{
		$this->data['page'] = 'about';

		$this->load->view("navigation", $this->data);
		$this->load->view("page/about", $this->data);
		$this->load->view("footer", $this->data);
	}
}
