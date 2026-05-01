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
function zipFilesAndDownload($files, $archive_name)
{
	if (function_exists('apache_setenv')) {
		apache_setenv('no-gzip', '1');
	}
	$zip = new ZipArchive;
	$archive_file = GF_BASE_PATH . DIRECTORY_SEPARATOR .  $archive_name;
	/* create the file and throw the error if unsuccessful */
	if (!$zip->open($archive_file, ZipArchive::CREATE)) {
		echo "FAIL";
	};
	/* add each files of $file_name array to archive */
	foreach ($files as $file) {
		$file_name = basename($file);
		if (file_exists($file)) {
			$zip->addFile($file, $file_name);
		}
	}
	$zip->close();
	
	/* Then download the zipped file. */
	if (file_exists($archive_file)) {
		// Clean the output buffer to avoid corrupted zip files
		if (ob_get_level()) {
			ob_end_clean();
		}
		
		header('Content-Description: File Transfer');
		header('Content-Type: application/zip');
		header('Content-Disposition: attachment; filename="' . basename($archive_file) . '"');
		header('Content-Transfer-Encoding: binary');
		header('Connection: Keep-Alive');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($archive_file));
		
		flush();
		ignore_user_abort(true);
		
		readfile($archive_file);
		
		if (connection_aborted() || !connection_aborted()) {
			unlink($archive_file);
		}
	}
}
