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
function setToProper($name)
{
	return ucwords(strtolower(str_replace(array("'", "\""), array("&apos;", "&quot;"), trim($name))));
}
function nameFIlter($name)
{
	return str_replace(
		["M.Pd\b", "M Pd.", "M. Pd.", "S Pd.", "S. Pd.", ".Pd",  ".Hum" , ".Biomed",  ".Psi",  ".Psi, ",  ".S, ",  "  ", " ,", "..", "Dr. Farida Aryani, M.Pd.",  "Dr. Farida Ariyani, M.pd."],
		["M.Pd.",  "M Pd.", "M.Pd." , "S.Pd.", "S.Pd." , ".Pd.", ".Hum.", ".Biomed.", ".Psi.", ".Psi., ", ".S., ", " ",  ",",  ".",  "Dr. Farida Ariyani, M.Pd.", "Dr. Farida Ariyani, M.Pd."],
		trim($name)
	);
}
function secureInput($input)
{
	return str_replace("'", "&apos;", strip_tags(trim($input)));
}
function hapus_gelar($name)
{
	$gelar = ["Drs.", "Dr.", "M.Pd.", "S.Pd.", "S.E.", "M.S.", "M.Si.", "M.Hum.", "Kons.", "M.Biomed.", ",", "  "];
	return trim(str_replace($gelar, "", trim($name)));
}
function perpendek_nama($fullName)
{
	$segmName	= explode(" ", hapus_gelar(trim($fullName)));
	$firstName = $segmName[0];
	$shortName = $firstName;
	if (count($segmName) > 1) {
		// $shortName .= ' ' . $segmName[1] . ' ';
		$shortName .= ' ';
		foreach ($segmName as $key => $value) {
			if ($key > 0) $shortName .=  substr($value, 0, 1);
		}
	}
	return $shortName;
}
