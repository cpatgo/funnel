<?php
// init.php

@set_magic_quotes_runtime(0);
ini_set('magic_quotes_runtime', 0);
error_reporting(E_ALL);

require_once(dirname(dirname(dirname(dirname(__FILE__))))) . '/manage/awebdeskend.inc.php';
require_once(dirname(dirname(dirname(__FILE__)))) . '/functions/basic.php';
require_once(dirname(dirname(dirname(__FILE__)))) . '/functions/ajax.php';
require_once(dirname(dirname(dirname(__FILE__)))) . '/functions/mail.php';


if ( !isset($site) ) $site = adesk_site_get();
if ( !isset($admin) ) $admin = adesk_admin_get();


// Preload the language file
adesk_lang_get('admin');



adesk_ajax_declare('testmail', 'adesk_api_testemail');
adesk_api_run();


function adesk_api_testemail($email, $type, $host, $port, $user, $pass, $enc, $pop3b4) {
	$names = array(0 => 'mail()', 1 => 'SMTP', 3 => 'SendMail'); // -1: rotator
	if ( !isset($names[$type]) ) $type = 0;
	$email = adesk_b64_decode($email);
	$host = adesk_b64_decode($host);
	$port = adesk_b64_decode($port);
	$user = adesk_b64_decode($user);
	$pass = adesk_b64_decode($pass);
	$enc = adesk_b64_decode($enc);
	$pop3b4 = adesk_b64_decode($pop3b4);
	$r = array(
		'email' => $email,
		'type' => $type,
		'host' => $host,
		'port' => $port,
		'user' => $user,
		'pass' => $pass,
		'enc' => $enc,
		'pop3b4' => $pop3b4,
		'succeeded' => 0
	);
	if ( !adesk_admin_ismaingroup() ) return $r;
	$site =& $GLOBALS['site'];
	$to_name = $r['email'];
	if ( isset($site['site_name']) ) {
		$from_name = $site['site_name'];
	} elseif ( isset($site['sname']) ) {
		$from_name = $site['sname'];
	} else {
		$from_name = $_SERVER['SERVER_NAME'];
	}
	if ( isset($site['emfrom']) ) {
		$from_email = $site['emfrom'];
	} elseif ( isset($site['awebdesk_from']) ) {
		$from_email = $site['awebdesk_from'];
	} else {
		$from_email = 'test@' . $_SERVER['SERVER_NAME'];
	}
	$bounce_email = $site['awebdesk_bounce'];
	$subject = _a("Mail Sending Options Test");
	$message = sprintf(_a("If you have received this email, that means that Mail Sending Options %s are set properly."), $names[$type]);

	$options = array(
		'bounce' => $site['awebdesk_bounce'],
		'attach' => array(),
		'headers' => array(),
		'reply2' => '',
		'priority' => 3, // 3-normal, 1-low, 5-high
		'encoding' => _i18n("8bit"),
		'charset' => _i18n("utf-8"),
	);

	$r['succeeded'] = adesk_mail_send_raw(
		'text',
		$from_name,
		$from_email,
		$message,
		$subject,
		$email,
		$to_name = $email,
		$type,
		$host,
		$port,
		$user,
		base64_encode($pass),
		$enc,
		$pop3b4,
		$options
	);
	return $r;
}

?>
