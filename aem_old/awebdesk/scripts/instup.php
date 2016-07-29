<?php

error_reporting(E_ALL);
@ini_set('max_execution_time', 950 * 60);
@set_time_limit (950 * 60);
@set_magic_quotes_runtime(false);
@session_start();

$globalPath = dirname(dirname(__FILE__));
$publicPath = dirname($globalPath);
$adminPath = $publicPath . '/manage';


// define constants here
define('adesk_LANG_NEW', 1);


require_once($adminPath . '/functions/awebdesk.php');
require_once($globalPath . '/functions/instup.php');
require_once($globalPath . '/functions/base.php');
require_once($globalPath . '/functions/php.php');
require_once($globalPath . '/functions/http.php');
require_once($globalPath . '/functions/lang.php');
require_once($globalPath . '/functions/file.php');
require_once($globalPath . '/functions/ajax.php');
require_once($globalPath . '/functions/sql.php');
require_once($globalPath . '/functions/tz.php');
require_once($globalPath . '/functions/utf.php');

// this file has ihooks
require_once($globalPath . '/functions/ihook.php');
require_once($adminPath . '/functions/ihooks.php');


/*
	WHITELIST
*/
$allowed = array(
	'instup!database_check' => '',
	'instup!admin_check' => '',
	'instup!auth_check' => '',
	'instup!plink_send' => '',
);

// Preload the language file
$lang = ( isset($_COOKIE['adesk_lang']) ? $_COOKIE['adesk_lang'] : 'english' );
adesk_lang_load(adesk_lang_file($lang, 'admin'));


// require ajax include
require_once awebdesk_includes("awebdeskapi.php");

?>
