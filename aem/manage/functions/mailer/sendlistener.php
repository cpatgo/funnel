<?php

require_once(SWIFT_ABS_PATH . '/Swift/Plugin/AntiFlood.php');

class SendingEngineHandler extends Swift_Plugin_AntiFlood {

	/**
	 * PROPERTIES
	 */
	var $batch = null;

	/**
	 * CONSTRUCTOR
	 */
	function SendingEngineHandler(&$batch, $threshold, $wait = 0) {
		$this->batch =& $batch;
		parent::Swift_Plugin_AntiFlood($threshold, $wait);
	}

	/**
	 * LISTENERS
	 */
	/*
		this is the last initialized plugin. so it's prepend comes last, and so does cleanup.
		for that reason we are extending this last one for duplicate/pause/stall checks,
		to be the closest we can get to actual "send email" action

		Pre-decorator comes in first, so cleanup (also first) that does logging is in that plugin
	*/
	function beforeSendPerformed(&$e) {
		$GLOBALS['stopCheck'] = $e->stopCheck = 0;
		// direct check to see if user paused or stopped mailing in the meantime (full stop!)
		if ( $this->batch->campaign['id'] > 0 and $this->batch->action == 'send' ) {
			$statusCheckSQL = @adesk_sql_query("SELECT status, mail_send FROM #campaign WHERE `id` = '{$this->batch->campaign['id']}'");
			if ( !$statusCheckSQL ) {
				$err = adesk_sql_error_number() . ': ' . adesk_sql_error();
				campaign_sender_log("!!! [+] Setting HARD (campaign) STOP: campaign info could not be fetched ($err) !!!");
				$GLOBALS['stopCheck'] = $e->stopCheck = 2;
				return;
			}
			$statusCheck = mysql_fetch_assoc($statusCheckSQL);
			if ( $statusCheck['status'] == 3 or $statusCheck['status'] == 4 or ( $this->batch->process and $statusCheck['mail_send'] == 1 ) ) {
				campaign_sender_log("!!! [+] Setting HARD (campaign) STOP: campaign was either paused or stopped in the meantime !!!");
				$GLOBALS['stopCheck'] = $e->stopCheck = 2;
				return;
			}
		}
		$swift =& $e->getSwift();
		$decorator =& $swift->getPlugin('decorator');
		reset($e->recipients->to);
		list($k, $v) = each($e->recipients->to);
		$recipient = $decorator->replacements->getReplacementsFor($k);
		$eid = $recipient['%PERS_ID%'];
		$tbl_id = $recipient['%%PERS_TBLID%%'];
		$nl = $recipient['currentnl'];
		// direct check to see if this recipient already received this mailing in some other process (don't send to this one)
		$check = false;
		if ( $this->batch->campaign['id'] > 0 and $this->batch->action == 'send' ) {
			if ( $this->batch->campaign['type'] == 'responder' ) {
				$check = true;
				//$statusCheckSQL = @adesk_sql_query("SELECT COUNT(*) FROM #subscriber_responder WHERE subscriberid = '$eid' AND listid = '$nl' AND campaignid = '{$this->batch->campaign['id']}'");
				$statusCheckSQL = @adesk_sql_query("SELECT COUNT(*) FROM #subscriber_responder WHERE subscriberid = '$eid' AND campaignid = '{$this->batch->campaign['id']}'");
			} elseif ( $this->batch->campaign['type'] == 'special' and $this->batch->campaign['realcid'] ) {
				$check = true;
				//$statusCheckSQL = @adesk_sql_query("SELECT COUNT(*) FROM #subscriber_responder WHERE subscriberid = '$eid' AND listid = '$nl' AND campaignid = '{$this->batch->campaign['realcid']}'");
				$statusCheckSQL = @adesk_sql_query("SELECT COUNT(*) FROM #subscriber_responder WHERE subscriberid = '$eid' AND campaignid = '{$this->batch->campaign['realcid']}'");
			} elseif ( $this->batch->process ) {
				$check = true;
				$statusCheckSQL = @adesk_sql_query("SELECT COUNT(*) FROM #x{$this->batch->campaign['sendid']} WHERE `id` = '$tbl_id' AND `sent` = 1");
			}
		}
		if ( $check ) {
			if ( !$statusCheckSQL ) {
				$err = adesk_sql_error_number() . ': ' . adesk_sql_error();
				campaign_sender_log("!!! [+] Setting HARD (campaign) STOP: subscriber could not be double-checked ($err) !!!");
				$GLOBALS['stopCheck'] = $e->stopCheck = 2;
				return;
			}
			list($statusCheck) = mysql_fetch_row($statusCheckSQL);
			if ( $statusCheck > 0 ) {
				campaign_sender_log("!!! [+] Setting SOFT (subscriber) STOP: subscriber will be skipped (already received it in another process?) !!!");
				$GLOBALS['stopCheck'] = $e->stopCheck = 1;
				return;
			}
		}
		if ( !$swift->getsource ) {
			$isRotator = isset($swift->connection->connections);
			if ( $isRotator ) {
				$connectionID = $swift->connection->getActive();
				if ( !isset($swift->connection->connections[$connectionID]) ) $connectionID = 0;
				$connection =& $swift->connection->connections[$connectionID];
				$mailer =& $connection->_info;
				// check this mailer
				$query = "SELECT sent, current, tstamp FROM #mailer WHERE id = '$mailer[id]'";
				$sql = adesk_sql_query($query);
				if ( !$sql or !@adesk_sql_num_rows($sql) ) {
					campaign_sender_log("!!! [+] Setting HARD (campaign) STOP: could not fetch new mailer info !!!");
					$GLOBALS['stopCheck'] = $e->stopCheck = 2;
					return;
				}
				$info = adesk_sql_fetch_assoc($sql);
				if ( !$info['current'] ) {
					/*
					campaign_sender_log("!!! [+] Setting HARD (campaign) STOP: this mailer is no longer used !!!");
					$GLOBALS['stopCheck'] = $e->stopCheck = 2;
					return;
					*/
					// fetch the next connection as we want to continue using that one
					$swift->connection->lastConnection = $connectionID + 1;
					// disconnect
					$swift->disconnect();
					// reconnect
					$swift->connect();
					// now reset the last connection we just added
					$swift->connection->lastConnection = null;
				}
				// check offsets in tstamp only if sending
				if ( $this->batch->action == 'send' ) {
					// if offset is really different
					if ( $mailer['tstamp'] and $info['tstamp'] != $mailer['tstamp'] ) {
						// if time-based limits are used, find the limit size in seconds
						// then check if the change occurred outside of that span
						// if it did, then we don't care as it can be restarted
						// if it didn't, we should stop so we don't break that connections limit
						if ( $mailer['limit'] ) {
							// limit's timespan in hours
							$offset = ( $mailer['limitspan'] == 'day' ? 24 : 1 );
							// hours to seconds
							$offset *= 3600;
							// check if the offset is under the expected value
							if ( (int)strtotime($info['tstamp']) - (int)strtotime($mailer['tstamp']) < $offset ) {
								campaign_sender_log("!!! [+] Setting HARD (campaign) STOP: mailer already in use !!!");
								$GLOBALS['stopCheck'] = $e->stopCheck = 2;
								return;
							}
						}
					}
				}
				if ( $info['sent'] != $mailer['sent'] ) {
					// update the connection info in rotator plugin
					$rotatorPlugin =& $swift->getPlugin('_ROTATOR');
					if ( $rotatorPlugin ) {
						$rotatorPlugin->count = $info['sent'] % $rotatorPlugin->getThreshold();
					}
					// update the connection info in sendlistener/anti-flood (this) plugin
					$this->count = 0;
					// update the connection info in throttler plugin
					$throttlerPlugin =& $swift->getPlugin('throttler');
					if ( $throttlerPlugin ) {
						// Set the number of messages per minute limit
						$epm = (int)$mailer['limit'];
						if ( $epm ) {
							// conv days into hours
							if ( $mailer['limitspan'] == 'day' ) $epm /= 24;
							// conv hours into minutes
							$epm /= 60;
							// this many sent already
							//$throttlerPlugin->setSent((int)( $mailer['sent'] % ( $epm / 60 ) ));
							$throttlerPlugin->setSent(0);
						}
					}
				}
			}
		}
		if ( $swift->getsource ) {
			campaign_sender_log("\n\nAbort the sending - we just need to prepare message source.\n\n\n\n");
		} else {
			campaign_sender_log("SENDING AN EMAIL TO $k ($tbl_id=$eid@$nl):");
		}
		//dbg("should send to: $k ($eid:$tbl_id)", 1);
	}

	function sendPerformed(&$e) {
		// our version allows zeros (don't switch) and it does not disconnect while sleeping
		if ( $this->getThreshold() > 0 and $this->getWait() > 0 ) {
			campaign_sender_log("Checking the Limit Emails For Time Period setting...");
			//$swift =& $e->getSwift();
			$this->count++;
			if ( $this->batch->action == 'send' and $this->count >= $this->getThreshold() ) {
			//if ( !$this->batch->it->isLast() and $this->count >= $this->getThreshold() ) {
				//$swift->disconnect();
				$this->wait($this->getWait());
				//$swift->connect();
				$this->count = 0;
			}

		}
		// print out debugging: completed the subscriber
		campaign_sender_log("Subscriber processed.\n\n**********\n");
	}

	/**
	 * METHODS
	 */
	function wait($secs) {
		$threshold = $this->getThreshold();
		campaign_sender_log("Throttling and Pausing setting says: Wait $secs seconds after $threshold emails, then continue.");
		campaign_log_save($this->batch->campaign, $this->batch->action);
		parent::wait($secs);
	}

}

?>