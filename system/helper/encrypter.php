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
if (!function_exists("str_encrypt")) {
	function str_encrypt($string)
	{
		// Use OpenSSl Encryption method 
		$iv_length = openssl_cipher_iv_length(GF_CONFIG["ciphering"]);
		$options = 0;

		// Use openssl_encrypt() function to encrypt the data 
		return openssl_encrypt(
			$string,
			GF_CONFIG["ciphering"],
			GF_CONFIG["encryption_key"],
			$options,
			GF_CONFIG["encryption_iv"]
		);
	}
}
if (!function_exists("str_decrypt")) {
	function str_decrypt($encryption)
	{
		// Use OpenSSl Encryption method 
		$iv_length = openssl_cipher_iv_length(GF_CONFIG["ciphering"]);
		$options = 0;

		// Use openssl_decrypt() function to decrypt the data 
		return openssl_decrypt(
			$encryption,
			GF_CONFIG["ciphering"],
			GF_CONFIG["encryption_key"],
			$options,
			GF_CONFIG["encryption_iv"]
		);
	}
}
