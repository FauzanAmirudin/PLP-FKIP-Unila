<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
|  
|--------------------------------------------------------------------------
|  
|  
|  
*/
#[AllowDynamicProperties]
class gf_clock
{
	protected $Clock;
	public $day;
	public $month;
	public $year;
	public $hour;
	public $minute;
	public $second;
	function __construct($timezone = 'Asia/Jakarta')
	{
		date_default_timezone_set($timezone);
		$this->Clock = new DateTime('now');
		$this->day = $this->Clock->format('d');
		$this->month = $this->Clock->format('m');
		$this->year = $this->Clock->format('Y');
		$this->hour = $this->Clock->format('H');
		$this->minute = $this->Clock->format('i');
		$this->second = $this->Clock->format('s');
		$this->now = $this->Clock->format('H:i:s d-m-y');
	}
	// set semester
	public function semester($con = FALSE)
	{
		if ($this->month < 7 && $this->month > 0) {
			if ($con == FALSE) {
				return "genap";
			} else {
				return "ganjil";
			}
		} elseif ($this->month < 13 && $this->month > 6) {
			if ($con == FALSE) {
				return "ganjil";
			} else {
				return "genap";
			}
		} else {
			# code...
		}
	}
}
