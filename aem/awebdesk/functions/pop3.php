<?php
if ( !defined('adesk_POP3_DEBUG') ) define('adesk_POP3_DEBUG', 0);
if ( !defined('adesk_POP3_DEBUG_COMM') ) define('adesk_POP3_DEBUG_COMM', 0);


function adesk_pop3_fetch($host, $port, $username, $password, $method = 'APOP', $max_emails = 120) {
	require_once awebdesk_functions('mime.php');
	require_once awebdesk_pear('POP3.php');
	if ( adesk_POP3_DEBUG ) {
		adesk_flush("Attempting to log onto host $host with user $username and method: $method<br />");
	}
	// initiate PEAR class
	$pop3 = new Net_POP3();
	if ( adesk_POP3_DEBUG_COMM ) $pop3->setDebug();
	// connect
	$pop3->connect($host, $port);
	// login
	$result = $pop3->login($username, $password, $method);
	// authentication
	if ( $result === true ) {
		if ( adesk_POP3_DEBUG ) {
			adesk_flush("Logged onto host $host with user $username<br />");
		}
		$numMsg = $pop3->numMsg();
		if ( gettype($numMsg) != 'integer' ) {
			adesk_flush(sprintf(_a('Could not fetch messages count from Inbox at %s for username %s.<br />'), $host, $username));
		} else {
			if ( adesk_POP3_DEBUG ) {
				adesk_flush(sprintf(_a('Found: [%s] emails in pop account. BUT I am only going to check the first %s this run.. Rerun this to check for more.<br /><br />'), $numMsg, $max_emails));
			}
			for ( $i = 1; $i <= $numMsg; $i++ ) {
				// get the message
				$msg = $pop3->getMsg($i);
				$msg = str_replace("=A0", "", $msg);
				$msg = str_replace(chr(0xa0), "", $msg);
				// run the parser
				$parsed = adesk_mail_extract($msg);
				if ( !$parsed ) {
					if ( adesk_POP3_DEBUG ) {
						adesk_flush(_a('Found an improperly structured email message. Storring it into errors table...'));
					}
					adesk_ihook('adesk_pop3_error', $parsed, $msg);
				} else {
					adesk_ihook('adesk_pop3_parse', $parsed, $msg);
				}
				// delete message
				$pop3->deleteMsg($i);
				if ( $i == $max_emails ) break;
			}
		}
		// disconnect
		if ( adesk_POP3_DEBUG ) {
			adesk_flush('Closing connection with pop server...<br />');
		}
		$pop3->disconnect();
	} else {
		adesk_flush(_a('Could not login to %s using the username: %s and password: *******.<br />', $host, $username));
	}
}

function adesk_pop3_method_find($method, $host, $port, $username, $password) {
	// first check this method
	if ( $method ) {
		$valid = adesk_pop3_method_valid($method, $host, $port, $username, $password);
		if ( $valid ) return $method;
	}
	$methods = adesk_pop3_methods();
	foreach ( $methods as $method ) {
		$valid = adesk_pop3_method_valid($method, $host, $port, $username, $password);
		if ( $valid ) return $method;
	}
	return '';
}

function adesk_pop3_method_valid($method, $host, $port, $username, $password) {
	require_once awebdesk_pear('POP3.php');
	if ( !$method ) {
		adesk_flush('No method passed into adesk_pop3_method_valid()');
		return false;
	}
	$valid = false;
	// open POP3 object
	$pop3 = new Net_POP3();
	//      $pop3->setDebug();
	$pop3->connect($host, $port);
	$result = $pop3->login($username, $password, $method);
	if ( $result === true and gettype($pop3->numMsg()) == 'integer' ) {
		$valid = true;
	}
	if ( $result ) @$pop3->disconnect();
	// if suceeded anyhow
	return $valid;
}

function adesk_pop3_methods() {
	$r = array('APOP', 'PLAIN', 'LOGIN', 'USER', 'DIGEST-MD5', 'CRAM-MD5', 'true');
	if ( !function_exists('posix_uname') ) unset($r['DIGEST-MD5']);
	return $r;
}


?>
