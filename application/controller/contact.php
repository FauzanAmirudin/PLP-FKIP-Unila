<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');

class contact extends gf_controller
{
	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->data['page'] = 'contact';

		$this->load->view("navigation", $this->data);
		$this->load->view("page/contact", $this->data);
		$this->load->view("footer", $this->data);
	}
}
