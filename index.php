<?php
/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but maintenance, production, testing and live will hide them.
 */

$environments = 'development';
// $environments = 'production';

/*
*---------------------------------------------------------------
* GF Setting
*---------------------------------------------------------------
*
* Different environments will require different levels of error reporting.
* By default development will show errors but testing and live will hide them.
*/
$system_path = 'system';

/*
*---------------------------------------------------------------
* GF Config
*---------------------------------------------------------------
*
* Different environments will require different levels of error reporting.
* By default development will show errors but testing and live will hide them.
*/
$config_path        = 'config';
$application_path   = 'application';
$controller_path    = 'controller';
$model_path         = 'model';
$helper_path        = 'helper';
$mode               = 'pages';
$timezone           = 'Asia/Jakarta';
// --------------------------------------------------------------------
// END OF USER CONFIGURABLE SETTINGS.  DO NOT EDIT BELOW THIS LINE
// --------------------------------------------------------------------
/*
* -------------------------------------------------------------------
*  Now that we know the path, set the main path constants
* -------------------------------------------------------------------
*/
// The name of THIS file
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('GF_BASE_PATH',  dirname(__FILE__));
define('GF_SYS_PATH', GF_BASE_PATH . DIRECTORY_SEPARATOR . trim($system_path));
require(GF_SYS_PATH . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'core.php');
