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
class frontpage extends gf_controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('dbconfig');
	}
	public function index()
	{
		if (GF_ENVIRONMENT == "development") {
			$user 	= "0913024042";
			$pass 	= "gheachandra07";
			$name	= "Test Name";
			$npm	= "123456789";
			$npass1	= "123456";
			$npass2	= "123456";
		}
		$this->data['enableregister'] = get_dbconfig('OPENREGISTER');
		$this->alert = implode("\n", get_notification());
		$this->data['input']['username'] = isset($user)  ? $user  : last_input('username');
		$this->data['input']['password'] = isset($pass)  ? $pass  : last_input('password');
		$this->data['input']['name'] 	 = isset($name)  ? $name  : last_input('nama');
		$this->data['input']['npm'] 	 = isset($npm)   ? $npm   : last_input('npm');
		$this->data['input']['pass'] 	 = isset($npass1)? $npass1: "";
		$this->data['input']['npass'] 	 = isset($npass2)? $npass2: "";
		$this->load->view("navigation", $this->data);
		$this->load->view("page/frontpage", $this->data);
		$this->load->view("footer", $this->data);
	}
}
