<?php
// mail.php

// Some functions for sending email

require_once(awebdesk_functions('i18n.php'));

function adesk_mail_send_mailer($type, $from_name, $from_email, $body, $subject, $email, $to_name = '', $stype = '0', $host = '', $port = '', $user = '', $pass = '', $enc = 8, $pop3b4smtp = 0, $options = array()) {
	if ( !defined('MAILER_ABS_PATH') ) define('MAILER_ABS_PATH', awebdesk_classes('mailer.php'));
	require_once(MAILER_ABS_PATH);
	$mail = new phpmailer();
	if ( $type != 'html' and $type != 'text' and !isset($options['altBody']) ) $type = 'text';
	$mail->From = $from_email;
	if ( $from_name != '' ) {
		$mail->FromName = stripslashes($from_name);
	}
	@ini_set('sendmail_from', $from_email);
	$mail->Priority = (int)$options['priority'];
	$mail->Encoding = $options['encoding'];
	$mail->CharSet = $options['charset'];
	// set REPLY-TO field
	if ( $options['reply2'] != '' and $options['reply2'] != $from_email ) {
		$mail->AddReplyTo($options['reply2'], $from_name);
	}
	if ( !is_array($email) ) {
		$arr = explode(',', $email);
		$email = array();
		foreach ( $arr as $v ) $email[trim($v)] = $to_name;
	}
	// set TO
	foreach ( $email as $e => $n ) $mail->AddAddress($e, ( $e == $n ? '' : $n ));
	$mail->Subject = $subject;
	if ( $type == 'multi' or $type == 'mime' ) {
		// Add both parts
		if ( is_array($body) and isset($body[0]) ) {
			# We assume here that $body[0] and $body[1] both exist.
			$mail->Body = $body[1];
			$mail->IsHTML(true);
			$mail->AltBody = $body[0];
			$txtPart = $message->attach(new Swift_Message_Part($body[0], 'text/plain', $encoding, $charset));
			$htmPart = $message->attach(new Swift_Message_Part($body[1], 'text/html', $encoding, $charset));
		} elseif ( is_array($body) and isset($body['html']) ) {
			# We assume here that $body[html] and $body[text] both exist.
			$mail->Body = $body['html'];
			$mail->IsHTML(true);
			$mail->AltBody = $body['text'];
		} elseif ( isset($options['altbody']) ) {
			# We assume here that $body[html] and $body[text] both exist.
			$mail->Body = $body;
			$mail->IsHTML(true);
			$mail->AltBody = $options['altBody'];
		} else {
			$mail->Body = $body;
			$mail->IsHTML(true);
			$mail->AltBody = $body;
		}
	} elseif ( $type == 'html' ) {
		$mail->Body = $body;
		$mail->IsHTML(true);
	} else {
		$type = 'text';
		$body = str_replace("\r", '', $body);
		$mail->Body = $body;
		$mail->IsHTML(false);
	}
	if ( $type != 'text' ) {
		$mail->Body = $body;
		$mail->IsHTML(true);
	} else/*if ( $type == 'text' )*/ {
		$body = str_replace("\r", '', $body);
		$mail->Body = $body;
		$mail->IsHTML(false);
	}
	$mail->WordWrap = 0;
	$mail->Timeout = 20;
	if ( isset($options['getsource']) ) {
		$mail->Mailer = 'getsource';
	} elseif ( $stype == 1 ) {
		$mail->Mailer = 'smtp';
		$mail->Host = $host;
		$mail->Port = $port;
		$mail->Helo = 'localhost.localdomain';
		if ( $user == '' and $pass == '' ) {
			$mail->SMTPAuth = false;
		} else {
			$mail->SMTPAuth = true;
			$mail->Username = $user;
			$mail->Password = base64_decode($pass);
		}
	} elseif ( $stype == 0 ) {
		$mail->Mailer = 'mail';
	} else {
		$mail->Mailer = 'sendmail';
	}
	if ( $options['bounce'] != '' and !isset($email[$options['bounce']]) ) $mail->Sender = $options['bounce'];
	// Looking for attachments and inserting if needed
	foreach ( $options['attach'] as $file ) {
		if ( is_array($file) ) {
			// if array, then it's a file
			$mail->AddStringAttachment($file['data'], $file['name'], 'base64', $file['mime_type']);
		} else {
			// if string, then it's a path to attachment
			if ( file_exists($file) ) $mail->AddAttachment($file);
		}
	}
	// set custom headers
	foreach ( $options['headers'] as $header ) {
		$mail->AddCustomHeader($header['name'] . ': ' . $header['value']);
	}
	// run any hooks
	if ( adesk_ihook_exists('adesk_mail_send_mail') )
	$mail = adesk_ihook('adesk_mail_send_mail', $mail, $email, $from_email, $options);
	// send an email
	$sent = $mail->Send();
	if ( !$sent ) {
		// do nothing
		$GLOBALS['adesk_mail_lasterror'] = '';//$mail->Error;
	}
	$mail->ClearAddresses();
	return $sent;
}

function adesk_mail_send_swift($type, $from_name, $from_email, $body, $subject, $email, $to_name = '', $stype = '0', $host = '', $port = '', $user = '', $pass = '', $enc = 8, $pop3b4smtp = 0, $options = array()) {
	// turn off our error reporting
	if ( defined('TRAPPERR') && TRAPPERR ) {
		$old_error_handler = restore_error_handler();
	}
	if ( !defined('SWIFT_ABS_PATH') ) {
		define('SWIFT_ABS_PATH', awebdesk('swiftmailer/php' . (int)PHP_VERSION));
	}
	require_once(SWIFT_ABS_PATH . '/Swift.php');
	// set the log to max
	$log =& Swift_LogContainer::getLog();
	$log->setLogLevel($whatever_level = 4);
	if ( isset($GLOBALS['_adesk_mailer_swift']) and $stype != -1 ) {
		// if this function ran before, $swift object is in global scope already
		// but if rotator is used, new connections might be needed
		$swift =& $GLOBALS['_adesk_mailer_swift'];
	} else {
		/*
			init sender class and set sending options
		*/
		if ( $stype == -1 ) {
			// fetch all available connections
			$query = ( isset($options['rotator_query']) ? $options['rotator_query'] : null );

			if($query==null && isset($options['userid']))
				$query = user_get_mail_conns_query($options['userid']);

			$GLOBALS['_adesk_mailer_connections'] = array(); // reset connections
			$connections =& adesk_mail_connections($query);
			if ( count($connections) == 1 ) {
				// if only one connection is used, no need for rotator then
				$swift = new Swift($connections[key($connections)]);
			} else {
				// set connection rotator while loading Swift
				require_once(SWIFT_ABS_PATH . '/Swift/Connection/Rotator.php');
				$swift = new Swift(new Swift_Connection_Rotator($connections));
				// set unique threshold value (minumum set)
				$rotatorPlugin =& $swift->getPlugin('_ROTATOR');
				$rotatorPlugin->setThreshold($GLOBALS['_adesk_mailer_rotator_threshold']);
			}
		} elseif ( $stype == 1 ) {
			require_once(SWIFT_ABS_PATH . '/Swift/Connection/SMTP.php');
			// SMTP CONNECT
			$smtp = new Swift_Connection_SMTP($host, $port, $enc);
			if ( $user != '' or $pass != '' ) {
				if ( $pop3b4smtp ) {
					//Apologies for the filename, it's to stop Swift auto-loading it
					require_once(SWIFT_ABS_PATH . '/Swift/Authenticator/@PopB4Smtp.php');
					//Load the PopB4Smtp authenticator with the pop3 hostname
					$smtp->attachAuthenticator(new Swift_Authenticator_PopB4Smtp($host));
				}
				$smtp->setUsername($user);
				$smtp->setPassword(base64_decode($pass));
			}
			$smtp->setTimeout(15); // default
			$swift = new Swift($smtp);
		} elseif ( $stype == 0 ) {
			require_once(SWIFT_ABS_PATH . '/Swift/Connection/NativeMail.php');
			$swift = new Swift(new Swift_Connection_NativeMail());
		} else {
			require_once(SWIFT_ABS_PATH . '/Swift/Connection/Sendmail.php');
			$swift = new Swift(new Swift_Connection_Sendmail());
			//$swift = new Swift(new Swift_Connection_Sendmail(SWIFT_SENDMAIL_AUTO_DETECT)); // autodetect where is it
		}
		// since this is the first time this function runs on page, save $swift object to global scope
		//$GLOBALS['_adesk_mailer_swift'] =& $swift;
	}
	/*
		assemble a message to go out
	*/
	$encoding = $options['encoding'];
	$charset  = $options['charset'];
	$priority = (int)$options['priority'];
	if ( $priority == 0 ) $priority = 3;

	//Create the message
	$message = new Swift_Message($subject, null, "text/plain", $encoding, $charset);

	// if mime message
	if ( $type == 'multi' or $type == 'mime' ) {
		// Add both parts
		if ( is_array($body) and isset($body[0]) ) {
			# We assume here that $body[0] and $body[1] both exist.
			$txtPart = $message->attach(new Swift_Message_Part($body[0], 'text/plain', $encoding, $charset));
			$htmPart = $message->attach(new Swift_Message_Part($body[1], 'text/html', $encoding, $charset));
		} elseif ( is_array($body) and isset($body['html']) ) {
			# We assume here that $body[html] and $body[text] both exist.
			$txtPart = $message->attach(new Swift_Message_Part($body['text'], 'text/plain', $encoding, $charset));
			$htmPart = $message->attach(new Swift_Message_Part($body['html'], 'text/html', $encoding, $charset));
		} elseif ( isset($options['altbody']) ) {
			# We assume here that $body[html] and $body[text] both exist.
			$txtPart = $message->attach(new Swift_Message_Part($options['altbody'], 'text/plain', $encoding, $charset));
			$htmPart = $message->attach(new Swift_Message_Part($body, 'text/html', $encoding, $charset));
		} else {
			$txtPart = $message->attach(new Swift_Message_Part($body, 'text/plain', $encoding, $charset));
			$htmPart = $message->attach(new Swift_Message_Part($body, 'text/html', $encoding, $charset));
		}
	} elseif ( $type == 'html' ) {
		$htmPart = $message->attach(new Swift_Message_Part($body, 'text/html', $encoding, $charset));
	} else {
		$type = 'text';
		$txtPart = $message->attach(new Swift_Message_Part($body, 'text/plain', $encoding, $charset));
		//$message->setBody($body);
	}
	/*
		set message headers
	*/
	// charset/encoding
	$message->setEncoding($encoding);
	$message->setCharset($charset);
	$message->headers->setCharset($charset);
	// set PRIORITY
	$message->setPriority($priority);
	// set BOUNCE field
	if ( $options['bounce'] != '' ) {
		if ( is_array($email) and !isset($email[$options['bounce']]) ) {
			$message->setReturnPath($options['bounce']);
		} elseif ( !is_array($email) and $email != $options['bounce'] ) {
			$message->setReturnPath($options['bounce']);
		}
	}
	// set FROM
	$from = new Swift_Address($from_email, ( ( $from_name != '' and $from_name != $from_email ) ? $from_name : null ));
	// set TO
	if ( !is_array($email) ) {
		$arr = explode(',', $email);
		$email = array();
		foreach ( $arr as $v ) $email[trim($v)] = $to_name;
	}
	$to = new Swift_RecipientList();
	foreach ( $email as $e => $n ) {
		$to->addTo(new Swift_Address($e, ( ( $n != '' and $n != $e ) ? $n : null )));
	}
	// set REPLY-TO field
	if ( $options['reply2'] != '' and $options['reply2'] != $from_email ) {
		$message->setReplyTo($options['reply2']);
	}
	// add attachments
	foreach ( $options['attach'] as $file ) {
		if ( is_array($file) ) {
			$message->attach(new Swift_Message_Attachment($file['data'], $file['name'], $file['mime_type']));
		} else {
			if ( file_exists($file) ) {
				$message->attach(new Swift_Message_Attachment(new Swift_File($file), adesk_file_basename($file)));
			}
		}
	}
	// set custom headers
	foreach ( $options['headers'] as $header ) {
		$message->headers->set($header['name'], $header['value']);
	}
	// run hook to add extra info
	if ( adesk_ihook_exists('adesk_mail_send_swift') )
		$swift = adesk_ihook('adesk_mail_send_swift', $swift, $message, $to, $from, $options);
	if ( adesk_ihook_exists('adesk_mail_send_message') )
		$message = adesk_ihook('adesk_mail_send_message', $message, $to, $from, $options);
	// if looking for message source, get it
	$swift->getsource = isset($options['getsource']);
	// send it
	$r = $swift->send($message, $to, $from);
	// collect error
	if ( !$r ) {
		$GLOBALS['adesk_mail_lasterror'] = $log->dump(true);
	}
	// revert our error reporting
	if ( defined('TRAPPERR') && TRAPPERR ) {
		if ( !function_exists("adesk_php_error_handler") ) require_once(awebdesk_functions('trapperr.php'));
		$old_error_handler = set_error_handler("adesk_php_error_handler");
	}
	return $r;
}

function adesk_mail_send($type, $from_name, $from_email, $body, $subject, $email, $to_name = '', $options = array()) {
	require_once awebdesk_functions('site.php');
	$site = adesk_site_get();

	if (isset($GLOBALS["_hosted_account"])) {
		# Block people with expired accounts from sending
		if (time() > strtotime($_SESSION[$GLOBALS["domain"]]["expire"]))
			return;

		# And people with accounts in any non-normal status
		if ($_SESSION[$GLOBALS["domain"]]["down4"] != "nobody")
			return;
	}

	if ( !isset($options['bounce']) ) {
		$options['bounce'] = $site['awebdesk_bounce'];
	}

	if ( !isset($options['attach']) ) {
		$options['attach'] = array();
	}

	if ( !isset($options['headers']) ) {
		$options['headers'] = array();
	}

	if ( !isset($options['reply2']) ) {
		$options['reply2'] = '';
	}

	if ( !isset($options['priority']) ) {
		$options['priority'] = 3; // 3-normal, 1-low, 5-high
	}

	if ( !isset($options['encoding']) ) {
		$options['encoding'] = _i18n("8bit");
	}

	if ( !isset($options['charset']) ) {
		$options['charset'] = _i18n("utf-8");
	}

	// there are other option fields:
	// getsource, rotator_query
	// but those are checked for existance, so nothing to do here

	// if not using backend table for smtp info and swift is used, we have support for rotator then
	if ( isset($GLOBALS['adesk_mail_engine']) and $GLOBALS['adesk_mail_engine'] == 'swift' and $GLOBALS['adesk_mail_table'] != 'backend' ) {
		// this is used so we can patch other products to use rotator also
		$site['stype'] = -1;
	}
	if ( !isset($site['smenc']) ) $site['smenc'] = 8; // swift mailer specific
	if ( !isset($site['smpop3b4']) ) $site['smpop3b4'] = 0; // swift mailer specific
	// call real mail function that assembles everything
	return adesk_mail_send_raw($type, $from_name, $from_email, $body, $subject, $email, $to_name, $site['stype'], $site['smhost'], $site['smport'], $site['smuser'], $site['smpass'], $site['smenc'], $site['smpop3b4'], $options);
}

function adesk_mail_send_raw() {
	if ( !isset($GLOBALS['adesk_mail_engine']) ) $GLOBALS['adesk_mail_engine'] = 'mailer'; // push PHPMailer for now
	$args = func_get_args();
	return call_user_func_array(str_replace('raw', $GLOBALS['adesk_mail_engine'], __FUNCTION__), $args);
}

/*
	email preparing functions
*/


function adesk_mail_prepare($name, $vars = array(), $loc = 'public') {
	require_once(awebdesk_functions('smarty.php'));
	global $site, $admin;
	if ( $loc != 'admin' ) $loc = 'public';
	$r = array('type' => 'mime', 'body' => '');
	// if file is missing, don't use that email version
	$path  = ( $loc == 'admin' ? 'manage/' : ( $loc == 'public' ? '' : 'awebdesk/' ) );
	$path .= 'templates/emails';
	if ( !file_exists(adesk_base("$path/$name.txt")) ) $r['type'] = 'html';
	if ( !file_exists(adesk_base("$path/$name.htm")) ) $r['type'] = 'text';
	$html = $text = '';
	$smarty = new adesk_Smarty($loc, true);
	$smarty->assign('site', $site);
	$smarty->assign('admin', $admin);
	if ( count($vars) ) $smarty->assign($vars);
	if ( $r['type'] != 'html' ) {
		$text = $smarty->fetch($name.'.txt');
	}
	if ( $r['type'] != 'text' ) {
		$html = $smarty->fetch($name.'.htm');
	}
	if ( $html and $text ) {
		$r['body'] = array(
			'html' => $html,
			'text' => $text,
		);
	} else {
		if ( $html ) {
			$r['body'] = $html;
		} elseif ( $text ) {
			$r['body'] = $text;
		} else {
			die('Fatal application detected. Please contact site admin about this error if possible.');
		}
	}
	return $r;
}

/*
SWIFT MAILER SUPPORTING FUNCTIONS
*/
function &adesk_mail_connections($query = null) {
	if ( !$query ) {
		$query = "SELECT * FROM #$GLOBALS[adesk_mail_table]";
		if ( $GLOBALS['adesk_mail_table'] != 'backend' ) $query .= " ORDER BY corder";
	}
	$hash = md5($query);
	// initialize the connection cache
	if ( !isset($GLOBALS['_adesk_mailer_connections']) ) {
		$GLOBALS['_adesk_mailer_connections'] = array();
	}
	// if already fetched from db, return it immediately
	if ( !isset($GLOBALS['_adesk_mailer_connections'][$hash]) ) {
		// unique threshold (minumum value) for now
		$GLOBALS['_adesk_mailer_rotator_threshold'] = 1;
		$GLOBALS['_adesk_mailer_connections'][$hash] = array();
		$smtp = array();
		$sql = adesk_sql_query($query);
		while ( $row = mysql_fetch_assoc($sql) ) {
			/*
				init sender class and set sending options
			*/
			if ( $row['type'] == 1 ) { // SMTP CONNECT
				require_once(SWIFT_ABS_PATH . '/Swift/Connection/SMTP.php');
				$smtp[$row['id']] = new Swift_Connection_SMTP($row['host'], $row['port'], $row['encrypt']);
				if ( $row['user'] != '' or $row['pass'] != '' ) {
					if ( $row['pop3b4smtp'] ) {
						//Apologies for the filename, it's to stop Swift auto-loading it
						require_once(SWIFT_ABS_PATH . '/Swift/Authenticator/@PopB4Smtp.php');
						//Load the PopB4Smtp authenticator with the pop3 hostname
						$smtp[$row['id']]->attachAuthenticator(new Swift_Authenticator_PopB4Smtp($row['host']));
					}
					$smtp[$row['id']]->setUsername($row['user']);
					$smtp[$row['id']]->setPassword(base64_decode($row['pass']));
				}
				$smtp[$row['id']]->setTimeout(15); // default
				$GLOBALS['_adesk_mailer_connections'][$hash][$row['id']] =& $smtp[$row['id']];
			} elseif ( $row['type'] == 0 ) { // MAIL()
				require_once(SWIFT_ABS_PATH . '/Swift/Connection/NativeMail.php');
				$GLOBALS['_adesk_mailer_connections'][$hash][$row['id']] = new Swift_Connection_NativeMail();
			} else {//if ( $row['type'] == 3 ) { // SENDMAIL CALL
				require_once(SWIFT_ABS_PATH . '/Swift/Connection/Sendmail.php');
				$GLOBALS['_adesk_mailer_connections'][$hash][$row['id']] = new Swift_Connection_Sendmail();
			}
			// save first connection's threshold
			if ( !isset($threshold) ) $threshold = $row['threshold'];
			// save emails per minute limit
			if ( !isset($row['limit']) ) {
				$row['epm'] = 2000; // old hardcoded value
			} else {
				// set emails per minute limit for this connection to _emailsperminute property of a connection object
				$row['epm'] = (int)$row['limit'] / 60;
				if ( $row['limitspan'] == 'day' ) $row['epm'] /= 24;
			}
			// set row info for this connection to _info property of a connection object
			// we will use: frequency, pause, threshold, and limits later, also: dotfix
			$GLOBALS['_adesk_mailer_connections'][$hash][$row['id']]->_info = $row;
		}
		// save (unique) threshold to global var
		$GLOBALS['_adesk_mailer_rotator_threshold'] = ( isset($threshold) ? $threshold : 1 );
	}
	reset($GLOBALS['_adesk_mailer_connections'][$hash]);
	return $GLOBALS['_adesk_mailer_connections'][$hash];
}



?>
