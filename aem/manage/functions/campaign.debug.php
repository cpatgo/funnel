<?php

function campaign_sender_log($msg) {
	if ( !isset($GLOBALS['dbgMsg']) ) $GLOBALS['dbgMsg'] = '';
	$GLOBALS['dbgMsg'] .= $msg . "\n\n";
	//if ( !defined('PRINT_SENDING_LOG') ) define('PRINT_SENDING_LOG', 1);
	if ( defined('PRINT_SENDING_LOG') and PRINT_SENDING_LOG ) {
		adesk_flush($msg . "\n\n");
	}
	campaign_log($msg);
}

function campaign_database_log($campaignID, $messageID, $subscriberID, $sent = 0, $comment = '') {
	// check if message logging is on
	//if ( !isset($GLOBALS['log_mailing']) ) return;
	// check if responder logging is on
	//if ( !isset($GLOBALS['log_responder']) ) return;
	if ( !( defined('adesk_API_REMOTE') and adesk_API_REMOTE ) ) {
		if ( $campaignID == 0 ) return;
		if ( $subscriberID == 0 ) return;
	}

	if ( isset($GLOBALS['_hosted_account']) ) {
		adesk_sql_update_one('#backend', '=sentemails', 'sentemails + 1');
	}

	$ary = array(
		'id' => 0,
		'campaignid' => $campaignID,
		'messageid' => $messageID,
		'subscriberid' => $subscriberID,
		'successful' => $sent,
		'=tstamp' => 'NOW()'
	);
	if ( $comment ) {
		$ary['comment'] = $comment;
	} else {
		$ary['=comment'] = 'NULL';
	}
	adesk_sql_insert('#log', $ary);
}

function campaign_debugging($campaign_setting) {
	// check campaign's debugg level
	$debugLevel = $campaign_setting;
	if ( !$debugLevel ) {
		// if campaign debugging is off
		if ( isset($GLOBALS['mailer_log_file']) and $GLOBALS['mailer_log_file'] ) {
			// use engine file settings
			$defaultLevel = ( defined('SWIFT_LOG_FAILURES') ? SWIFT_LOG_FAILURES : 2 ); // default log level
			$debugLevel = ( isset($GLOBALS['mailer_log_level']) ? $GLOBALS['mailer_log_level'] : $defaultLevel );
		} else {
			// use backend settings
			$debugLevel = (int)$GLOBALS['site']['mailer_log_file'];
		}
	}
	$GLOBALS['adesk_swiftmailer_debug_level'] = $debugLevel;
	return $debugLevel;
}

function campaign_log_init($campaign, $process = null, $action = 'send') {
	$logLevel = campaign_debugging($campaign['mailer_log_file']);
	if ( $logLevel < 2 ) $logLevel = 2;
	if ( $action != 'send' ) {
		if ( $logLevel != 4 ) {
			$logLevel = $GLOBALS['adesk_swiftmailer_debug_level'] = 0;
		}
	}
	// set new swift mailer's log object
	$GLOBALS["_SWIFT_LOG"] = null;
	Swift_LogContainer::setLog(new SendingEngineLogger());
	$log =& Swift_LogContainer::getLog();
	$log->hash = rand(10000, 99999);
	$log->add("\n\n\n\n" . date('Y-m-d H:i:s') . "\n\nCampaign #$campaign[id]: $campaign[name]\n\nLog #$log->hash:\n\n\n");
	/*
	$tmp = debug_backtrace();
	foreach ( $tmp as $k => $v ) if ( isset($v['args']) ) $tmp[$k]['args'] = array_map('gettype', $v['args']);
	$log->add(print_r($tmp,1));
	*/
	// set log level
	if ( $log->getLogLevel() != $logLevel ) {
		$log->setLogLevel($logLevel);
	}
	if ( $action != 'send' ) {
		// just hit a stamp
		$tmp = $action . str_repeat(' ', 15 - strlen($action) );
		$log->add("

 =============================================================
 | $tmp                                           |
 =============================================================\n");
		//
	} else {
		// print headers
		if ( $process ) {
			if ( $campaign['mail_transfer'] ) {
				// switching from transfer to sender
				// or starting a new batch
				$log->add("

 =============================================================
 | emailing subscribers                                      |
 =============================================================\n");
			} else {
				// starting transfer
				$log->add("

 =============================================================
 | transferring subscribers                                  |
 =============================================================\n");
			}
		} else {
			// sending a single email
			$log->add("

 =============================================================
 | sending a single email                                    |
 =============================================================\n");
		}
	}
	//if ( $logLevel == 0 ) $log->clear();
}

function campaign_log($msg) {
	//if (!isset($GLOBALS['asdffg']))$GLOBALS['asdffg']=0;$GLOBALS['asdffg']++;$msg .= " $GLOBALS[asdffg]";
	// get swift mailer's log object
	$log =& Swift_LogContainer::getLog();
	return $log->add($msg);
}

function campaign_log_save($campaign, $action = 'send') {
	// get swift mailer's log object
	$log =& Swift_LogContainer::getLog();
	// don't save temp campaigns
	if ( $campaign['id'] == 0 ) {
		$log->clear();
		//Swift_LogContainer::setLog($log);
		return;
	}
	// don't save previews neither
	if ( in_array($action, array('preview', 'messagesize')) ) {
		$log->clear();
		//Swift_LogContainer::setLog($log);
		return;
	}
	// not required to save it
	if ( $campaign['mailer_log_file'] == 0 ) {
		$log->clear();
		//Swift_LogContainer::setLog($log);
		return;
	}
	if ( $GLOBALS['adesk_swiftmailer_debug_level'] == 0 ) {
		$log->clear();
		//Swift_LogContainer::setLog($log);
		return;
	}
	//if ( $GLOBALS['adesk_swiftmailer_debug_level'] != 4 and $action != 'send' ) return $log->clear();
	// if we should save output to a file
	if ( count($log->entries) == 0 or $log->getLogLevel() == 0 ) {
		$log->clear();
		//Swift_LogContainer::setLog($log);
		return;
	}
	$logFilename = adesk_cache_dir('campaign-' . $campaign['id'] . '.log');
	if ( $fh = @fopen($logFilename, 'a') ) {
		//$log->add("Log saved. $log->hash");
		/*
		$tmp = debug_backtrace();
		foreach ( $tmp as $k => $v ) if ( isset($v['args']) ) $tmp[$k]['args'] = array_map('gettype', $v['args']);
		$log->add(print_r($tmp,1));
		*/
		@fwrite($fh, $log->dump(true) . "\n");
		@fclose($fh);
	}
	$log->clear();
	//Swift_LogContainer::setLog($log);
}

function dbgswift(&$e) {
	$swift =& $e->getSwift();
	$predecorator =& $swift->getPlugin('predecorator');
	$decorator =& $swift->getPlugin('decorator');
	reset($e->recipients->to);
	list($k, $v) = each($e->recipients->to);
	$recipient = $predecorator->replacements->getReplacementsFor($k);
	$recipient2 = $decorator->replacements->getReplacementsFor($k);
	if ( $recipient != $recipient2 ) {
		//dbg('different!!! predecorator:', 1);
		//dbg($recipient, 1);
		//dbg('decorator:', 1);
		//dbg($recipient2, 1);
	}
	$recipient['____email'] = $k;
	$recipient['____xxx'] = $v;
	return $recipient;
}

?>
