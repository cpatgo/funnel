<?php

require_once(SWIFT_ABS_PATH . '/Swift/Plugin/Decorator.php');

// extend the decorator class
class SendingEngineDecorator extends Swift_Plugin_Decorator {

	var $batch = null;

	var $revert = false;
	var $preDecorator = false;

	var $parts = array('text' => false, 'html' => false);

	var $smarty = null;


	function SendingEngineDecorator(&$batch, &$replacements, $preDecorator = false) {
		$this->batch =& $batch;
		// is this a pre-decorator (fetch: personalized)
		$this->preDecorator = $preDecorator;
		if ( $preDecorator ) {
			$GLOBALS['_swift_message_cache'] = array(
				'store' => array(
					0 => array(), // decorator
					1 => array() // predecorator
				),
				'parts' => array(),
			);
		}
		// go normal stuff :-)
		parent::Swift_Plugin_Decorator($replacements);
	}

	function switchCache($messageID) {
		if ( !isset($GLOBALS['_swift_message_cache']['store'][(int)$this->preDecorator][$messageID]) ) {
			campaign_sender_log("Initializing decorator's multi-message storage slot (cache var) for this message (#$messageID)");
			$GLOBALS['_swift_message_cache']['store'][(int)$this->preDecorator][$messageID] = array();
		}
		if ( !isset($GLOBALS['_swift_message_cache']['parts'][$messageID]) ) {
			campaign_sender_log("Initializing decorator's multi-message storage slot (parts var) for this message (#$messageID)");
			$GLOBALS['_swift_message_cache']['parts'][$messageID] = array('text' => false, 'html' => false);
		}
		$this->store = $GLOBALS['_swift_message_cache']['store'][(int)$this->preDecorator][$messageID];
		$this->parts = $GLOBALS['_swift_message_cache']['parts'][$messageID];
	}

	function beforeSendPerformed(&$e) {
		//campaign_sender_log('beforeSendPerformed for ' . ( $this->preDecorator ? 'predecorator' : 'postdecorator' ) . ' started');
		// grab subscriber info
		$recipients =& $e->getRecipients();
		$to =& $recipients->getTo();
		reset($to);
		list($recipient) = each($to);
		$subscriber = $this->replacements->getReplacementsFor($recipient);
		$messageData =& $this->batch->getMessageData($subscriber['%MESSAGEID%']);
		// check old reverting setting?
		if ( $this->revert ) {
			//
		}
		// castling (for split)
		$this->switchCache($subscriber['%MESSAGEID%']);
		// default setting: revert it at the end of sending loop
		$this->revert = true;
		if ( $this->preDecorator ) { // PREDECORATOR
			$message =& $e->getMessage();
			// headers
			//$this->updateHeaders($message, $subscriber);
			// if we are fetching a personalized message
			if
			(
				( $messageData['htmlfetchurl'] and $messageData['htmlfetch'] == 'cust' )
			or
				( $messageData['textfetchurl'] and $messageData['textfetch'] == 'cust' )
			) {

				campaign_sender_log('PreDecorator: Personalize the URL of a message content...');
				// personalize the url
				parent::beforeSendPerformed($e);
				// fetch personalized message for this subscriber
				$this->fetchPersonalized($e, $subscriber, $messageData);
			} else {
				// don't revert this predecorator
				$this->revert = false;
			}

			# Final wrapping...
			if ( $message->hasChild($this->batch->parts[$messageData['id']]['html']) ) {
				$htmlPart =& $message->getChild($this->batch->parts[$messageData['id']]['html']);

				if ($htmlPart)
					$htmlPart->setBody(message_wrap_html($htmlPart->getBody()));
			}

			if ( $message->hasChild($this->batch->parts[$messageData['id']]['text']) ) {
				$textPart =& $message->getChild($this->batch->parts[$messageData['id']]['text']);

				if ($textPart)
					$textPart->setBody(message_wrap_text($textPart->getBody()));
			}
		} else { // DECORATOR
			// if we are fetching a personalized message
			if
			(
				( $messageData['htmlfetchurl'] and $messageData['htmlfetch'] == 'cust' )
			or
				( $messageData['textfetchurl'] and $messageData['textfetch'] == 'cust' )
			) {
				// (then do personalize message content now, but)
				// don't revert it at the end so the predecorator (message url) can be reverted instead
				$this->revert = false;
			}
			campaign_sender_log('Decorator: Personalize message content...');
			// personalize message content
			parent::beforeSendPerformed($e);

		}

		// now save cache
		$GLOBALS['_swift_message_cache']['store'][(int)$this->preDecorator][$subscriber['%MESSAGEID%']] = $this->store;
		campaign_sender_log(( $this->preDecorator ? 'PreDecorator' : 'Decorator' ) . ( $this->revert ? ' WILL BE ' : ' will NOT be ' ) . 'reverted.');
		//campaign_sender_log(print_r(dbgswift($e), 1));
	}

	function sendPerformed(&$e) {
		//campaign_sender_log('sendPerformed for ' . ( $this->preDecorator ? 'predecorator ' : 'postdecorator ' ), 1);
		// grab subscriber info
		$recipients =& $e->getRecipients();
		$to =& $recipients->getTo();
		reset($to);
		list($recipient) = each($to);
		$subscriber = $this->replacements->getReplacementsFor($recipient);
		$messageData =& $this->batch->getMessageData($subscriber['%MESSAGEID%']);
		// predecorator (except for embedder until the bug is fixed) is attached first, so cleanup comes first.
		if ( $this->preDecorator ) { // PREDECORATOR
			// here we log the outcome
			if ( $this->batch->action == 'send' or ( defined('adesk_API_REMOTE') and adesk_API_REMOTE ) ) {
				$this->logRecipient($e);
			}

			// if we are fetching a personalized message
			if
			(
				( $messageData['htmlfetchurl'] and $messageData['htmlfetch'] == 'cust' )
			or
				( $messageData['textfetchurl'] and $messageData['textfetch'] == 'cust' )
			) {
				// then revert personalizations
				$this->revertPersonalized($e, $messageData);
			}
			// since this is the first listener firing after the send
			// here we can modify other plugins before their listener's sendPerformed() methods fire
			$swift =& $e->getSwift();
			// if using rotator
			$isRotator = isset($swift->connection->connections);
			$connection = null;
			if ( $isRotator ) {
				$connectionID = $swift->connection->getActive();
				// if all good, connection found
				if ( isset($swift->connection->connections[$connectionID]) ) {
					$connection =& $swift->connection->connections[$connectionID];
				} else {
					// hack -- get first connection if none are active
					$connection =& $swift->connection->connections[0];
				}
			} else {
				$connection =& $swift->connection;
			}
			// connection info found
			$mailer =& $connection->_info;
			// check rotator plugin
			$rotatorPlugin =& $swift->getPlugin('_ROTATOR');
			if ( $isRotator and $rotatorPlugin ) {
				if ( $mailer['threshold'] != $rotatorPlugin->getThreshold() ) {
					$rotatorPlugin->setThreshold($mailer['threshold']);
					$rotatorPlugin->count = $mailer['sent'] % $mailer['threshold'];
				}
			}
			// check throttler plugin
			$throttlerPlugin =& $swift->getPlugin('throttler');
			if ( $throttlerPlugin ) {
				if ( $mailer['epm'] != $throttlerPlugin->getEmailsPerMinute() ) {
					$throttlerPlugin->setEmailsPerMinute($mailer['epm']);
					$throttlerPlugin->setSent(0);
					$throttlerPlugin->time = null;
				}
			}
			// check anti-flood plugin
			$antiFloodPlugin =& $swift->getPlugin('anti-flood');
			if ( $antiFloodPlugin ) {
				if ( $mailer['frequency'] != $antiFloodPlugin->getThreshold() ) {
					$antiFloodPlugin->setThreshold($mailer['frequency']);
					$antiFloodPlugin->count = $mailer['sent'] % $mailer['frequency'];
				}
				if ( $mailer['pause'] != $antiFloodPlugin->getWait() ) {
					$antiFloodPlugin->setWait($mailer['pause']);
					$antiFloodPlugin->count = $mailer['sent'] % $mailer['frequency'];
				}
			}
			// if rotator is used, we do this in that plugin
			if ( !$isRotator ) {
				if ( !$swift->getsource ) {
					// update the mailer in use
					$mailer['sent']++;
					adesk_sql_update_one('#mailer', '=sent', 'sent + 1', "`id` = '$mailer[id]'");
					// print out debugging: completed the mailer update
					campaign_sender_log("Mailer updated.");
				}
			}
		} else { // DECORATOR
			// nothing to do here at the moment
		}
		//campaign_sender_log(print_r(dbgswift($e), 1));
	}

	function recursiveReplace(&$mime, $replacements, &$store) {
		// if mime's content type is html
		if ( is_a($mime, 'Swift_Message_Mime') ) {
			if ( adesk_str_instr('text/html', strtolower((string)$mime->getContentType())) ) {
				// add <br> to all replacement tags
				$replacements = array_map('nl2br', $replacements);
			} elseif ( adesk_str_instr('text/plain', strtolower((string)$mime->getContentType())) ) {
				// fix any custom perstags that can be different in text-only message
				if ( isset($replacements['%SENDER-INFO%']) ) {
					//$replacements['%SENDER-INFO%'] = str_replace(array('<br />', '<br>'), "\n", $replacements['%SENDER-INFO%']);
				}
				// strip html from all replacement tags
				$replacements = str_replace(array('<br />', '<br>'), "\n", $replacements);
				$replacements = array_map('adesk_str_strip_tags', $replacements);
			}
		}
		parent::recursiveReplace($mime, $replacements, $store);
	}

	function recursiveRestore(&$mime, &$store) {
		if ( $this->revert ) parent::recursiveRestore($mime, $store);
	}

	function replace($replacements, $value) {
		# We need to convert the replacements, and to do so, we need the messageid and need to
		# refer back to the message for the right charset ... thus what follows below:
		$messageData =& $this->batch->getMessageData($replacements["%MESSAGEID%"]);
		$charset = $messageData['charset'];
		if ( $charset == '' ) $charset = _i18n("utf-8");
		if (strtoupper($charset) != "UTF-8")
			$replacements = adesk_utf_deepconv("UTF-8", $charset, $replacements);
		// if conditional content is not present
		if ( !adesk_str_instr('%/IF%', strtoupper($value)) ) {
			// run the old, simple replacer
			return parent::replace($replacements, $value);
		}
		require_once(adesk_admin('functions/personalization.php'));
		return personalization_conditional($replacements, $value, true);
	}

	function fetchPersonalized(&$e, &$recipient, $messageData) {
		campaign_sender_log("PreDecorator will fetch $messageData[htmlfetchurl]; therefore, PreDecorator will be reverted instead of Decorator.");

		//campaign_sender_log(( $this->preDecorator ? 'predecorator ' : 'postdecorator ' ) . 'fetch: ' . $messageData['htmlfetchurl'] . " (revert: " . ( $this->revert ? "yes" : "no" ) . ")");
		//campaign_sender_log(print_r(dbgswift($e), 1));
		// extract the message
		$message =& $e->getMessage();
		if ( $messageData['htmlfetchurl'] and $messageData['htmlfetch'] == 'cust' ) {
			$messageData['htmlfetch'] = 'send'; // temp patch
			// first personalize url
			campaign_sender_log("Personalizing HTML message URL...");
			$messageData['htmlfetchurl'] = $this->replace(array_map('urlencode', $recipient), $messageData['htmlfetchurl']);

			// prepare and cleanup html version
			campaign_sender_log("Preparing HTML message...");
			$messageData = $this->batch->prepareHTML($messageData);

			if (strtoupper($messageData['charset']) != "UTF-8")
				$messageData = adesk_utf_deepconv("UTF-8", $messageData['charset'], $messageData);

			$messageData['htmlfetch'] = 'cust'; // revert temp patch
			// and assign it
			if ( $this->batch->parts[$messageData['id']]['html'] ) {
				if ( $message->hasChild($this->batch->parts[$messageData['id']]['html']) ) {
					$htmlPart =& $message->getChild($this->batch->parts[$messageData['id']]['html']);
					$htmlPart->setBody($messageData['html']);

					# This message may ask us to manage the text version; if
					# so, we'll need to set the body of the text part here.
					if ( $this->batch->campaign["managetext"] == 0 && $this->batch->parts[$messageData['id']]['text'] ) {
						if ( $message->hasChild($this->batch->parts[$messageData['id']]['text']) ) {
							$textPart =& $message->getChild($this->batch->parts[$messageData['id']]['text']);
							$textPart->setBody($messageData['text']);
						}
					}

					//campaign_sender_log(print_r($messageData,1));
					//message looks fine here
				}
			}
		}
		if ( $messageData['textfetchurl'] and $messageData['textfetch'] == 'cust' ) {
			$messageData['textfetch'] = 'send'; // temp patch
			// first personalize url
			campaign_sender_log("Personalizing TEXT message URL...");
			$messageData['textfetchurl'] = $this->replace(array_map('urlencode', $recipient), $messageData['textfetchurl']);
			// prepare and cleanup text version
			campaign_sender_log("Preparing TEXT message...");
			$messageData = $this->batch->prepareTEXT($messageData);

			if (strtoupper($messageData['charset']) != "UTF-8")
				$messageData = adesk_utf_deepconv("UTF-8", $messageData['charset'], $messageData);

			$messageData['textfetch'] = 'cust'; // revert temp patch
			// and assign it
			if ( $this->batch->parts[$messageData['id']]['text'] ) {
				if ( $message->hasChild($this->batch->parts[$messageData['id']]['text']) ) {
					$textPart =& $message->getChild($this->batch->parts[$messageData['id']]['text']);
					$textPart->setBody($messageData['text']);
				}
			}
		}

	}

	function revertPersonalized(&$e, $messageData) {
		campaign_sender_log("PreDecorator will revert the message to URL $messageData[htmlfetchurl] so it can be re-fetched for the next subscriber.");
		//campaign_sender_log(( $this->preDecorator ? 'predecorator ' : 'postdecorator ' ) . 'revert: ' . $messageData['htmlfetchurl'] . " (revert: " . ( $this->revert ? "yes" : "no" ) . ")");
		//campaign_sender_log(print_r(dbgswift($e), 1));
		// extract the message
		$message =& $e->getMessage();
		if ( $messageData['htmlfetchurl'] and $messageData['htmlfetch'] == 'cust' ) {
			// assign the original back
			campaign_sender_log("PreDecorator reverting the original HTML message...");
			if ( $this->batch->parts[$messageData['id']]['html'] ) {
				if ( $message->hasChild($this->batch->parts[$messageData['id']]['html']) ) {
					$htmlPart =& $message->getChild($this->batch->parts[$messageData['id']]['html']);
					$htmlPart->setBody($messageData['html']);
				}
			}
		}
		if ( $messageData['textfetchurl'] and $messageData['textfetch'] == 'cust' ) {
			// assign the original back
			campaign_sender_log("PreDecorator reverting the original TEXT message...");
			if ( $this->batch->parts[$messageData['id']]['text'] ) {
				if ( $message->hasChild($this->batch->parts[$messageData['id']]['text']) ) {
					$textPart =& $message->getChild($this->batch->parts[$messageData['id']]['text']);
					$textPart->setBody($messageData['text']);
				}
			}
		}
	}

	/*
		this is the first initialized plugin. so it's prepend comes first, and so does cleanup.
		for that reason we are extending this last one for duplicate/pause/stall checks,
		to be the closest we can get to actual "send email" action

		Anti-flood comes in last, so cleanup (also first) that does logging is in that plugin
	*/
	function logRecipient(&$e) {
		// log progress
		$logged = false;
		$swift =& $e->getSwift();
		$decorator =& $swift->getPlugin('decorator');
		$log =& Swift_LogContainer::getLog();
		// grab (the only) recipient
		reset($e->recipients->to);
		list($k, $v) = each($e->recipients->to); // k=email, v=name
		// get personalization
		$subscriber = $decorator->replacements->getReplacementsFor($k);
		// print out debugging
		$eid = $subscriber['%PERS_ID%'];
		$tbl_id = $subscriber['%%PERS_TBLID%%'];
		$msg_id = $subscriber['%MESSAGEID%'];
		$nl = $subscriber['currentnl'];
		campaign_sender_log("SENT TO SUBSCRIBER $k ($tbl_id=$eid@$nl)");

		// print out?
		if ( $this->batch->process ) {
			$msg = ( isset($this->failed[$k]) ? 'FAILED' : 'sent' );
			echo "<!-- $msg: $k ($eid) -->\n";
		}

		// update the campaign
		if ( $this->batch->campaign['id'] and $this->batch->action == 'send' ) {
			campaign_sender_log("Saving the sending result...");
			// if in process (dealing with X table)
			if ( $tbl_id > 0 and $this->batch->process ) {
				// update it
				if ( !adesk_sql_update_one('#x' . $this->batch->campaign['sendid'], 'sent', 1, "id = '$tbl_id'") ) {
					$err = '#' . adesk_sql_error_number() . ' - ' . adesk_sql_error();
					campaign_sender_log("\n\n[+] COULD NOT UPDATE SUBSCRIBER $eid (#$tbl_id) !!!\n\nError $err\n\n\n\n");
					campaign_log_save($this->batch->campaign['id'], $this->batch->action);
					die("update subscriber failed ($tbl_id): $err");
				}
				if ( @adesk_sql_affected_rows($GLOBALS["db_link"]) == 0 ) {
					campaign_sender_log("\n\n[+] DID NOT UPDATE SUBSCRIBER $eid (#$tbl_id) !!!\n\nError $err\n\n\n\n");
					campaign_log_save($this->batch->campaign['id'], $this->batch->action);
					die("did not update subscriber $k");
				}
			}
			// update the campaign (and process)
			$this->batch->update();
			// if it is a valid subscriber (not a test with dummy email)
			if ( $eid > 0 ) {
				// if responder or reminder
				if ( in_array($this->batch->campaign['type'], array('responder', 'reminder')) ) {
					// add this responder to this subscriber (save it)
					//subscriber_responder_log($eid, $nl, $this->batch->campaign['id'], $msg_id);
					subscriber_responder_log($eid, $this->batch->campaign['listslist'], $this->batch->campaign['id'], $msg_id);
				} elseif ( $this->batch->campaign['type'] == 'special' and $this->batch->campaign['realcid'] ) {
					// add REAL responder to this subscriber (save it)
					//subscriber_responder_log($eid, $nl, $this->batch->campaign['realcid'], $msg_id);
					subscriber_responder_log($eid, $this->batch->campaign['listslist'], $this->batch->campaign['realcid'], $msg_id);
				}
				// log message only if failed
				$cid = ( $this->batch->campaign['realcid'] ? $this->batch->campaign['realcid'] : $this->batch->campaign['id'] );
				$sent = !isset($e->failed[$k]);
				$msg = ( !$sent ? $log->dump(true) : '' );
				// save to database log
				campaign_database_log($cid, $msg_id, $eid, (int)$sent, $msg);
				$logged = true;
			}
			// print out debugging: completed the subscriber update
			campaign_sender_log("Subscriber updated.");
		}

		if ( !$logged and defined('adesk_API_REMOTE') and adesk_API_REMOTE ) {
			campaign_database_log($cid, $msg_id, $eid, (int)$sent, $msg);
		}
	}



/*
	function updateHeaders(&$message, $subscriber) {
		$nl = $subscriber['currentnl'];
		if ( $this->_prevList != $nl ) {
			// set BOUNCE MANAGEMENT
			$this->updateBounceManagement($message, $subscriber);
			// set CUSTOM HEADERS
			$this->updateCustomHeaders($message, $subscriber);
			// set custom mailer header if changed with language change
			if ( trim(_i18n('AEM')) != '' )
				$message->headers->set('X-Mailer', trim(_i18n('AEM')));
		}
		$this->_prevList = $nl;
	}


	function updateBounceManagement(&$message, $subscriber) {
		$nl = $subscriber['currentnl'];
		// set sender
		$frome = $this->msgData['mfrom'];
		$reply2 = $this->msgData['reply2'];
		if ( $reply2 != '' and $reply2 != $frome ) {
			$message->setReplyTo($reply2);
		}
		if ( isset($this->bounce[$nl]['method']) ) {
			$return_path = ( $this->bounce[$nl]['email'] != '' ? $this->bounce[$nl]['email'] : $frome );
		} else {
			$return_path = $frome;
		}
		// set BOUNCE field
		$message->setReturnPath($return_path);
	}

	function updateCustomHeaders(&$message, $subscriber) {
		// set CUSTOM HEADERS
		$nl = $subscriber['currentnl'];
		if ( $this->addedHeaders === $this->customHeaders[$nl] ) return;
		foreach ( $this->addedHeaders as $k => $v ) { // check all headers added to message already
			// if it will not be used in this iteration...
			if ( !isset($this->customHeaders[$nl][$k]) or $this->customHeaders[$nl][$k] != $v ) {
				$message->headers->remove($k); // ... (not the same list, different custom headers)
				unset($this->addedHeaders[$k]); // then remove it
			}
		}
		foreach ( $this->customHeaders[$nl] as $k => $v ) { // now add headers for this list
			if ( !isset($this->addedHeaders[$k]) or $this->addedHeaders[$k] != $v ) {
				$message->headers->set($k, $v);
				$this->addedHeaders[$k] = $v;
			}
		}
		//$message->headers->set('X-mid', '%X-MID%');
	}
*/


}


?>
