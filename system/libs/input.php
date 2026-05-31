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
class gf_input
{
	function __construct()
	{
		unset($_SESSION['gf_input']);
		if (!empty($_POST)) {
			$_SESSION['gf_input'] = $_POST;
		}
		if (!function_exists("last_input")) {
			function last_input($name)
			{
				if (isset($_SESSION['gf_input']) && !empty($_SESSION['gf_input'])) {
					return $_SESSION['gf_input'][$name];
				}
				return '';
			}
		}
	}
	function post($name = NULL)
	{
		if ($name !== NULL) {
			return isset($_POST[$name]) && !empty($_POST[$name]) ? $this->filter_string($_POST[$name]) : NULL;
		} else {
			if (isset($_POST) && count($_POST) > 0) {
				foreach ($_POST as $name => $value) {
					$post[$name] = $this->filter_string($value);
				}
				return $post;
			} else return NULL;
		}
	}
	function get($name = NULL)
	{
		if ($name !== NULL) {
			return isset($_GET[$name]) && !empty($_GET[$name]) ? $this->filter_string($_GET[$name]) : NULL;
		} else {
			if (isset($_GET) && count($_GET) > 0) {
				foreach ($_GET as $name => $value) {
					$get[$name] = $this->filter_string($value);
				}
				return $get;
			} else return NULL;
		}
	}
	function upload(string $fieldname, string $fileName, string $upFolder = '', array $option = [])
	{
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		if (isset($_FILES[$fieldname])) {
			if (empty($upFolder)) $upFolder = GF_CONFIG["FOLDER_DEFAULT"];
			if (isset($option['type'])) $option['type'] = explode(", ", $option['type']);
			$targetPath = GF_BASE_PATH . DIRECTORY_SEPARATOR . $upFolder;
			$name = $_FILES[$fieldname]['name'];
			$tmpName = $_FILES[$fieldname]['tmp_name'];
			$error = $_FILES[$fieldname]['error'];
			$size = $_FILES[$fieldname]['size'];
			$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
			$link = str_replace('\\', '/', $upFolder . DIRECTORY_SEPARATOR . $fileName . "." . $ext);
			$valid = true;
			switch ($error) {
				case UPLOAD_ERR_OK:
					//validate file extensions
					if (isset($option['type']) && is_array($option['type']) && !in_array($ext, $option['type'])) {
						$valid = false;
						$report = 'Anda hanya dapat upload dengan tipe file berikut: ' .  implode(", ", $option['type']) . ".";
					}
					//validate file size
					if (isset($option['sizelimit']) && !empty($option['sizelimit']) && ($size / 1024 > $option['sizelimit'])) {
						$valid = false;
						$report = 'Ukuran File yang anda upload terlalu besar, kompress file anda hingga tidak lebih dari ' . $option['sizelimit'] . ' Kb.';
					}
					if (!is_dir($targetPath)) {
						if (!mkdir($targetPath, 0777, true)) {
							$valid = false;
							$report = 'Error! upload directory no exist.';
						}
					}
	
					if (isset($option['type'])) {
						$fileExist = FALSE;
						foreach ($option['type'] as $Eext) {
							$fileExist = file_exists($targetPath . DIRECTORY_SEPARATOR . $fileName . '.' . $Eext);
							if ($fileExist) break;
						}
					} else {
						$fileExist = file_exists($targetPath . DIRECTORY_SEPARATOR . $fileName . '.' . $ext);
					}
					if ($fileExist && $valid) {
						if (isset($option['update']) && $option['update'] == TRUE) {
							if (isset($option['type'])) {
								$fileExist = false;
								foreach ($option['type'] as $Eext) {
									$fileExist = file_exists($targetPath . DIRECTORY_SEPARATOR . $fileName . '.' . $Eext);
									if ($fileExist) $unlink = unlink($targetPath . DIRECTORY_SEPARATOR . $fileName . '.' . $Eext);
									if ($fileExist && !$unlink) break;
								}
							} else {
								$unlink = unlink($targetPath . DIRECTORY_SEPARATOR . $fileName . '.' . $ext);
							}
							if (!$unlink) {
								$valid = false;
								$report = 'Error! Can\'t remove previous file.';
							}
						} else {
							$valid = false;
							$report = 'Gagal! File sudah tersedia di server.';
						}
					}
					//upload file
					if ($valid) {
						if (!move_uploaded_file($tmpName, $targetPath . DIRECTORY_SEPARATOR . $fileName . '.' . $ext)) {
							$valid = false;
							$report = 'Error! Can\'t save uploaded file please contact admin.';
						}
					}
					break;
				case UPLOAD_ERR_INI_SIZE:
					$valid = false;
					$report = 'Ukuran File yang anda upload melebihi batas yang diijinkan di php.ini.';
					break;
				case UPLOAD_ERR_FORM_SIZE:
					$valid = false;
					$report = 'Ukuran File yang anda upload melebihi batas yang ditentukan secara specified di dalam HTML form.';
					break;
				case UPLOAD_ERR_PARTIAL:
					$valid = false;
					$report = 'Hanya berhasil mengupload sebagian file';
					break;
				case UPLOAD_ERR_NO_FILE:
					$valid = false;
					$report = 'Silahkan pilih dahulu File yang akan anda upload';
					break;
				case UPLOAD_ERR_NO_TMP_DIR:
					$valid = false;
					$report = 'Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3.';
					break;
				case UPLOAD_ERR_CANT_WRITE:
					$valid = false;
					$report = 'Failed to write file to disk. Introduced in PHP 5.1.0.';
					break;
				case UPLOAD_ERR_EXTENSION:
					$valid = false;
					$report = 'File upload stopped by extension. Introduced in PHP 5.2.0.';
					break;
				default:
					$valid = false;
					$report = 'Error! Unknown server error.';
					break;
			}
			if ($valid) {
				$report = 'Upload file anda berhasil';
				if (isset($option['fileHash']) && $option['fileHash'] == TRUE) $fileHash = md5_file($targetPath . DIRECTORY_SEPARATOR . $fileName . '.' . $ext);
				$data = array(
					"FILEPATH"	=> str_replace('\\', '/', $upFolder),
					"FILELINK"	=> $link,
					"FILENAME"	=> $fileName,
					"FILEEXT"	=> $ext,
					"FILEHASH"	=> isset($fileHash) ? $fileHash : '',
					"TIMESTAMP"	=> full_time(),
				);
			} else $data = [];
		} else {
			$valid = FALSE;
			$report = "File filed is empty!";
			$data = [];
		}
		return array(
			"status" => $valid,
			"report" => $valid ? $report : 'Upload gagal!<br/>' . $report,
			"data" => $data,
		);
	}
	private function filter_string(string $str)
	{
		// FILTER_SANITIZE_STRING dihapus di PHP 8.1+, diganti dengan htmlspecialchars yang setara.
		return htmlspecialchars(strip_tags(stripslashes(trim($str))), ENT_QUOTES, 'UTF-8');
	}
}
