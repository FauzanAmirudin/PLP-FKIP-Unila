<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');

/*
* -------------------------------------------------------------------
*  From index config set the main path constants
* -------------------------------------------------------------------
*/
define('GF_ENVIRONMENT', trim($environments));
define('GF_MODE', trim($mode));

define('GF_APP_PATH', GF_BASE_PATH . DIRECTORY_SEPARATOR . trim($application_path));
define('GF_GONFIG_PATH', GF_APP_PATH . DIRECTORY_SEPARATOR . trim($config_path));
define('GF_CONTROL_PATH', GF_APP_PATH . DIRECTORY_SEPARATOR . trim($controller_path));
define('GF_MODEL_PATH', GF_APP_PATH . DIRECTORY_SEPARATOR . trim($model_path));
define('GF_HLP_PATH', GF_APP_PATH . DIRECTORY_SEPARATOR . trim($helper_path));

/*
*---------------------------------------------------------------
* Set Time Zone
*---------------------------------------------------------------
*/
date_default_timezone_set($timezone);

/*
*---------------------------------------------------------------
* Load Config
*---------------------------------------------------------------
*/
require(GF_GONFIG_PATH . "/config.php");
require(GF_GONFIG_PATH . "/db.php");


define('GF_CONFIG', $config);
define('GF_DB', $gf_db);

/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 */
switch (GF_ENVIRONMENT) {
    case 'debug':
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        if (version_compare(PHP_VERSION, '7.0.0', '>=')) {
            error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
        } else {
            error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
        }
        define('GF_ERROR_LV',  0);

        break;
    case 'development':
    case 'maintenance':
        error_reporting(E_ALL);
        ini_set('display_errors', E_ALL);
        define('GF_ERROR_LV',  E_ALL);
        break;

    case 'live':
    case 'testing':
    case 'production':
        error_reporting(0);
        ini_set('display_errors', 0);
        define('GF_ERROR_LV',  0);
        break;

    default:
        header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
        echo 'The application environment is not set correctly.';
        exit(1); // EXIT_ERROR
}
if (GF_ERROR_LV > 0) {
    // user defined error handling function
    // PHP 8.0+ tidak lagi mengirim parameter $context ke-5 ke error handler
    function GF_ERROR_HANDLER($errno, $errmsg, $filename, $linenum)
    {
        static $index = 0;
        static $layer = 999999;
        $index++;
        $layer--;
        // timestamp for the error entry
        $dt = date("Y-m-d H:i:s (T)");

        // define an assoc array of error string
        // in reality the only entries we should
        // consider are E_WARNING, E_NOTICE, E_USER_ERROR,
        // E_USER_WARNING and E_USER_NOTICE
        $errortype = array(
            E_ERROR              => 'Error',
            E_WARNING            => 'Warning',
            E_PARSE              => 'Parsing Error',
            E_NOTICE             => 'Notice',
            E_CORE_ERROR         => 'Core Error',
            E_CORE_WARNING       => 'Core Warning',
            E_COMPILE_ERROR      => 'Compile Error',
            E_COMPILE_WARNING    => 'Compile Warning',
            E_USER_ERROR         => 'User Error',
            E_USER_WARNING       => 'User Warning',
            E_USER_NOTICE        => 'User Notice',
            E_STRICT             => 'Runtime Notice',
            E_RECOVERABLE_ERROR  => 'Catchable Fatal Error'
        );
        // set of errors for which a var trace will be saved
        $user_errors = array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE);
        $err = '';
        $err .= "<div class=\"gf_error_messege\" style=\"font-size: 11pt; border: solid 1px #ff0000; border-radius: 0.2em; padding: .2em; background: #ff9595; color: #ffffff; margin: .2em; max-width: 100%;\">\n";
        $err .= "\t<a onmousedown=\"gf_open_error_window(this)\">ERROR NUM " . $index . "</a>\n";
        $err .= "\t<div onmouseenter=\"gf_init_error_windows(this)\" style=\"border: .9px solid rgb(243 0 0);border-radius: 0.4em;background: rgb(255, 255, 255);color: rgb(255, 0, 0);max-width: 100%;position: absolute; z-index: $layer;\">\n";
        $err .= "\t\t<div class=\"gf_error_window_mover\" style=\"cursor: move; background: linear-gradient(to bottom right, red, #a900ff);\">\n";
        $err .= "\t\t\t<div style=\"font-weight: bold;text-transform: uppercase;color: white;padding: .5em .5em .8em .5em; display: block;position: relative;\">\n";
        $err .= "\t\t\t\t<a style=\"padding-right: 3rem;\">ERROR NUM " . $index . " - " . $errortype[$errno] . " (" . $errno . ")</a><span onmousedown=\"gf_close_error_window(this)\" style=\"cursor: pointer; position: absolute; top: 50%; right: 0%; padding: 12px 16px; transform: translate(0%, -50%);\">x</span>";
        $err .= "\t\t\t</div>\n";
        $err .= "\t\t</div>\n";
        $err .= "\t\t<div style=\"color: #0e0e0e;\">\n";
        $err .= "\t\t\t<div style=\"padding: .3em;margin: .4em .2em;\">\n";
        $err .= "\t\t\t\t<a style=\"font-size: 10pt;border: solid 1px #777777;border-radius: .3em;padding: .2em .4em .2em .4em;background: #cbccd2;color: #000000;\">File: " . $filename . "</a>\n";
        $err .= "\t\t\t</div>\n";
        $err .= "\t\t\t<div style=\"padding: .3em;margin: .4em .2em;\">\n";
        $err .= "\t\t\t\t<a style=\"font-size: 10pt;border: solid 1px #af00eb;border-radius: .3em;padding: .2em .4em .2em .4em;background: #ffffff;color: #000000;\">Line: " . $linenum . "</a>\n";
        $err .= "\t\t\t</div>\n";
        $err .= "\t\t</div>\n";
        $err .= "\t\t<div style=\"font-size: 8pt; border: solid 1px #614cfd;border-radius: 4px;padding: 3px 10px 3px 10px;background: #ecedf6;color: #0e0e0e;margin: .5rem;\">\n";
        $errors = explode("#", $errmsg);
        foreach ($errors as $error) {
            $err .= "\t\t\t<p>" . $error . "</p>\n";
        }
        $err .= "\t\t\t<p>\n";
        if (in_array($errno, $user_errors) && $vars != FALSE) {
            $err .= "\t<vartrace>" . serialize($vars) . "</vartrace>\n";
        }
        $err .= "\t\t\t</p>\n";
        $err .= "\t\t</div>\n";
        $err .= "\t\t<div class=\"gf_error_window_mover\"  style=\"cursor: move; font-size: 8pt;text-align:  right;align-content:  right; margin: 3px;\">\n";
        $err .= "\t\t\t<a>" . $dt . "</a>\n";
        $err .= "\t\t</div>\n";
        $err .= "\t</div>\n";
        $err .= "</div>\n";

        // for testing
        if (GF_ERROR_LV > 1) echo $err;

        $log_path = GF_APP_PATH . DIRECTORY_SEPARATOR . "log";
        $date = date("Y-m-d");

        if (!is_dir($log_path)) {
            if (!mkdir($log_path, 0777, true)) {
                echo 'Error! upload directory no exist.';
            }
        }
        // save to the error log, and e-mail me if there is a critical user error
        error_log($err, 3, $log_path . DIRECTORY_SEPARATOR . "error_(" . date("Y-m-d") . ").html");
        if ($errno == E_USER_ERROR) {
            //mail("phpdev@example.com", "Critical User Error", $err);
        }
    }
    function GF_SHUTDOWN()
    {
        $lasterror = error_get_last();
        if ($lasterror != NULL) {
            if (in_array($lasterror['type'], array(E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR, E_CORE_WARNING, E_COMPILE_WARNING, E_PARSE))) {
                GF_ERROR_HANDLER($lasterror['type'], $lasterror['message'], $lasterror['file'], $lasterror['line'], FALSE);
            }
            ob_start();
            if (GF_ENVIRONMENT == 'development' || GF_ENVIRONMENT == 'debug') include_once("error.php");
            if (GF_ENVIRONMENT == 'maintenance') include_once("maintenance.php");
            $buffer = ob_get_contents();
            ob_end_clean();
            // if (GF_CONFIG['sanitize_output'] == TRUE) {
            //     $search = array('/\>[^\S ]+/s', /* strip whitespaces after tags, except space */ '/[^\S ]+\</s', /* strip whitespaces before tags, except space */ '/(\s)+/s', /* shorten multiple whitespace sequences */ '/<!--(.|\s)*?-->/' /* Remove HTML comments */);
            //     $replace = array('>', '<', '\\1', '');
            //     $buffer = preg_replace($search, $replace, $buffer);
            // }
            echo $buffer;
        }
    }
    // we will do our own error handling
    register_shutdown_function('GF_SHUTDOWN');
    $old_error_handler = set_error_handler("GF_ERROR_HANDLER");
}

/*
*---------------------------------------------------------------
* Start Sessions
*---------------------------------------------------------------
*/
session_start([
    'cookie_lifetime' => 86400
]);
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    session_destroy();
    session_unset();
    session_start([
        'cookie_lifetime' => 86400
    ]);
}
$_SESSION['LAST_ACTIVITY'] = time();
if (isset($_SESSION['STAT'])) {
    $logStatus = $_SESSION['STAT'];
} else {
    $logStatus = '';
}
$REPORT = '';

/*
*---------------------------------------------------------------
* Load plugins
*---------------------------------------------------------------
*/
include_once(GF_SYS_PATH . "/plugins/autoload.php");


/*
*---------------------------------------------------------------
* Load Framework Core
*---------------------------------------------------------------
*/
$GF_COMPONENS = array(
    "core" => array("loader", "model", "controller"),
    "system" => array_merge(array("input", "sql"), GF_CONFIG['libs'])
);
foreach ($GF_COMPONENS as $GF_SECTION => $GF_COMPONEN) {
    foreach ($GF_COMPONEN as $GF_FILE) {
        switch ($GF_SECTION) {
            case 'core':
                $GF_COMPONEN_FILE =  DIRECTORY_SEPARATOR . "core" . DIRECTORY_SEPARATOR . $GF_FILE . ".php";
                $GF_FILE = strtoupper($GF_FILE);
                break;
            case 'system':
                $GF_COMPONEN_FILE =  DIRECTORY_SEPARATOR . "libs" . DIRECTORY_SEPARATOR . $GF_FILE . ".php";
                break;
            default:
                $GF_COMPONEN_FILE = '';
                break;
        }
        if (file_exists(GF_APP_PATH . $GF_COMPONEN_FILE)) {
            if (class_exists($GF_FILE, false)) {
                trigger_error($GF_SECTION . ' class blocked please don\'t use "' . $GF_FILE . '" as class.', E_USER_WARNING);
            } else {
                include_once(GF_APP_PATH . $GF_COMPONEN_FILE);
                // Check to see whether the include declared the class
                if (!class_exists($GF_FILE, false)) {
                    trigger_error("System framwork error, system class not found.", E_USER_WARNING);
                }
            }
        } else if (file_exists(GF_SYS_PATH . $GF_COMPONEN_FILE)) {
            if (class_exists('GF_' . $GF_FILE, false)) {
                trigger_error($GF_SECTION . ' class blocked please don\'t use "' . $GF_FILE . '" in "
                ' . GF_SYS_PATH . $GF_COMPONEN_FILE . '"', E_USER_WARNING);
            } else {
                include_once(GF_SYS_PATH . $GF_COMPONEN_FILE);
                // Check to see whether the include declared the class
                if (!class_exists('GF_' . $GF_FILE, false)) {
                    trigger_error('Library file found but not declare corect class: "' . $GF_FILE . '"', E_USER_WARNING);
                }
            }
        } else {
            trigger_error("Library $GF_FILE file not found!", E_USER_ERROR);
        }
    }
}
$request_path = '';
if (isset($_GET['page']) && trim($_GET['page']) !== '') {
    $request_path = trim($_GET['page'], "/");
} else if (isset($_SERVER["PATH_INFO"]) && trim($_SERVER["PATH_INFO"], "/") !== "") {
    $request_path = trim($_SERVER["PATH_INFO"], "/");
} else {
    $request_path = GF_CONFIG["ctrl_default"] . '/' . GF_CONFIG["func_default"];
}
define('GF_REQUEST', explode("/", $request_path));
$GF_REQUEST_DATA = GF_REQUEST;
$GF_CONTROLER_PATH = GF_CONTROL_PATH;
foreach (GF_REQUEST as $URI) {
    if (file_exists($GF_CONTROLER_PATH . DIRECTORY_SEPARATOR . $URI . ".php")) {
        $GF_CONTROLER = array_shift($GF_REQUEST_DATA);
        break;
    } else {
        $GF_CONTROLER_PATH .= DIRECTORY_SEPARATOR . array_shift($GF_REQUEST_DATA);
    }
    $GF_CONTROLER = NULL;
}
$GF_CTRL_FUNC = !empty($GF_CONTROLER) && empty($GF_REQUEST_DATA) ? GF_CONFIG["func_default"] : array_shift($GF_REQUEST_DATA);
if (file_exists($GF_CONTROLER_PATH . DIRECTORY_SEPARATOR . $GF_CONTROLER . ".php")) {
    if (class_exists($GF_CONTROLER, false)) {
        trigger_error('Class controller ' . $GF_CONTROLER . ' already declare please please change contoller name and don\'t use prefix "GF_".', E_USER_WARNING);
    } else {
        include_once($GF_CONTROLER_PATH . DIRECTORY_SEPARATOR . $GF_CONTROLER . ".php");
        if (class_exists($GF_CONTROLER, false)) {
            global $GF_CORE;
            foreach (GF_CONFIG["helper"]  as  $GF_HELPER) {
                if (file_exists(GF_HLP_PATH . "/" . $GF_HELPER . ".php")) {
                    require(GF_HLP_PATH . "/" . $GF_HELPER . ".php");
                } else if (file_exists(GF_SYS_PATH . "/helper/" . $GF_HELPER . ".php")) {
                    require(GF_SYS_PATH . "/helper/" . $GF_HELPER . ".php");
                } else {
                    trigger_error("Helper $GF_FILE file not found!", E_USER_ERROR);
                }
            }
            $GF_CORE = new $GF_CONTROLER();
            $GF_CORE->system = $GF_COMPONENS;
            foreach ($GF_COMPONENS as $GF_COMPONEN => $GF_ITEM) {
                $GF_CORE->$GF_COMPONEN = $GF_ITEM;
            }
            $GF_CORE->GF_PREPARE();
            if (method_exists($GF_CORE, $GF_CTRL_FUNC)) {
                $GF_CORE->$GF_CTRL_FUNC($GF_REQUEST_DATA);
                $GF_CORE->GF_EXIT();
            } else {
                header("HTTP/1.0 404 Not Found");
                echo "<h1>404 Not Found</h1>";
                echo "The page that you have requested could not be found.";
            }
            exit();
        } else {
            trigger_error("Controller file found but not declare corect class please change claas to: $GF_CONTROLER.", E_USER_WARNING);
        }
    }
} else {
    header("HTTP/1.0 404 Not Found");
    echo "<h1>404 Not Found</h1>";
    echo "The page that you have requested could not be found.";
}
