#!/usr/local/bin/php
<?php
// require main include file
require_once(dirname(__FILE__) . '/awebdeskend.inc.php');
require_once(awebdesk_functions('process.php'));
require_once(awebdesk_functions('mime.php'));


// turning off some php limits
@ignore_user_abort(1);
@ini_set('max_execution_time', 950 * 60);
@set_time_limit(950 * 60);
$ml = ini_get('memory_limit');
if ( $ml != -1 and (int)$ml < 128 and substr($ml, -1) == 'M') @ini_set('memory_limit', '128M');
set_include_path('.');
@set_magic_quotes_runtime(0);



$debug = (bool)adesk_http_param('debug');
//$test = (bool)adesk_http_param('test');


if ( $debug ) {
	if ( !defined('adesk_POP3_DEBUG') ) define('adesk_POP3_DEBUG', $debug);
}

require_once(adesk_admin('functions/bounce_management.php'));
require_once(adesk_admin('functions/bounce_code.php'));
require_once(awebdesk_functions('pop3.php'));



// Preload the language file
adesk_lang_get('admin');

// we need bounce codes in a global array
$GLOBALS['bouncecodes'] = bounce_code_select_array();

if(!isset($email)){
	$email = adesk_php_stdin();
}

bounce_management_parse(adesk_mail_extract($email), $email);

?>
