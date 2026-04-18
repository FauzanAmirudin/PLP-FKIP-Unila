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
	apache_setenv('no-gzip', '1');
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
			if (GF_ENVIRONMENT == 'development') echo "Add file:" . $file . "<br/>";
		} elseif (GF_ENVIRONMENT == 'development') {
			echo "Not found! file:" . $file . "<br/>";
		}
	}
	$zip->close();
	if (GF_ENVIRONMENT == 'development') echo "<a href=\"" . set_url($archive_name) . "\">Download</a><br/>";
	/* Then download the zipped file. */
	if (file_exists($archive_file) && GF_ENVIRONMENT != 'development') {
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="' . basename($archive_file) . '"');
		header('Content-Transfer-Encoding: binary');
		header('Connection: Keep-Alive');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . sprintf("%u", filesize($archive_file)));
		ob_clean();
		flush();
		ignore_user_abort(true);
		if (connection_aborted()) {
			unlink($archive_file);
		}
		echo readfile($archive_file);
		unlink($archive_file);
	}
}
