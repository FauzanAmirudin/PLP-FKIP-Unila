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
if (!function_exists("printtoPDF")) {
	function printtoPDF($tempelate, $name, $data)
	{
		$printFile = $tempelate;
		$printName = $name;
		$printData = $data;
		// Tentukan folder dimana anda menyimpan folder mpdf
		//require(GF_SYS_PATH.'/plugins/mpdf/mpdf.php');

		$mpdf = new \Mpdf\Mpdf([
			'debug' => true,
			'allow_output_buffering' => true,
			'mode' => 'utf-8',
			'format' => 'A4-P',
			'orientation' => 'P',
			'default_font_size' => 10.5,
			'default_font' => 'arial',
			'margin_left' => 10,
			'margin_right' => 10.5,
			'margin_top' => 10.5,
			'margin_bottom' => 10.5,
			'margin_header' => 0,
			'margin_footer' => 0
		]);
		// Membuat file mpdf baru
		$nama_dokumen = $printName; //Beri nama file PDF hasil.
		//Memulai proses untuk menyimpan variabel php dan html
		ob_start();
		include(GF_APP_PATH . '/tempelatePDF/' . $printFile . '.php');
		//penulisan output selesai, sekarang menutup mpdf dan generate kedalam format pdf
		$html = ob_get_contents(); //Proses untuk mengambil hasil dari OB..
		ob_end_clean();
		//Disini dimulai proses convert UTF-8, kalau ingin ISO-8859-1 cukup dengan mengganti $mpdf->WriteHTML($html);
		$mpdf->WriteHTML(utf8_encode($html));
		$mpdf->SetProtection(array('copy', 'print'), '', 'itsMyDocument');
		$mpdf->Output($nama_dokumen . '.pdf', 'I');
		exit;
	}
}
