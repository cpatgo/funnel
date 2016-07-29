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

if ( isset($GLOBALS['_hosted_account']) ) {
	if ( $GLOBALS['_hosted_account'] != $GLOBALS['_hosted_cname'] && $GLOBALS['_hosted_cname'] != '' ) {
		$site['p_link'] = str_replace($GLOBALS['_hosted_account'], $GLOBALS['_hosted_cname'], $site['p_link']);
	}
}


$debug = (bool)adesk_http_param('debug');
//$test = (bool)adesk_http_param('test');


if ( $debug ) {
	if ( !defined('adesk_POP3_DEBUG') ) define('adesk_POP3_DEBUG', $debug);
}

require_once(adesk_admin('functions/emailaccount.php'));
require_once(awebdesk_functions('pop3.php'));



// Preload the language file
adesk_lang_get('admin');

$email = adesk_php_stdin();

if (isset($GLOBALS["_hosted_account"]) && strpos($email, "unsubscribe-") !== false) {
	emailaccount_parse_hosted(adesk_mail_extract($email), $email);
} else {
	emailaccount_parse(adesk_mail_extract($email), $email);
}

?>
