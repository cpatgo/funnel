<?php

require_once(SWIFT_ABS_PATH . '/Swift/Plugin/Throttler.php');

//Extend the replacements class
class SendingEngineThrottler extends Swift_Plugin_Throttler {

	/**
	 * PROPERTIES
	 */
	var $batch = null;

	/**
	 * CONSTRUCTOR
	 */
	function SendingEngineThrottler(&$batch) {
		$this->batch =& $batch;
	}

	/**
	 * LISTENERS
	 */
	/*
	function beforeSendPerformed(&$e) {
		parent::beforeSendPerformed($e);
	}
	*/

	function sendPerformed(&$e) {
		if ( $this->getEmailsPerMinute() ) campaign_sender_log("Checking the Throttling and Pausing setting...");
		campaign_log_save($this->batch->campaign, $this->batch->action);
		$swift =& $e->getSwift();
		if ( $this->time === null ) {
			$isRotator = isset($swift->connection->connections);
			if ( $isRotator ) {
				$connectionID = $swift->connection->getActive();
				if ( !isset($swift->connection->connections[$connectionID]) ) $connectionID = 0;
				$connection =& $swift->connection->connections[$connectionID];
			} else {
				$connection =& $swift->connection;
			}
			// set this timestamp as connection's
			$mailer =& $connection->_info;
			$this->time = time();
			if ( $this->batch->action == 'send' ) {
			//if ( !$this->batch->it->isLast() ) {
				$date = date('Y-m-d H:i:s', $this->time);
				adesk_sql_update_one('#mailer', 'tstamp', $date, "id = '$mailer[id]'");
				$mailer['tstamp'] = $date;
			}
		}
		if ( $this->batch->action != 'send' ) {
		    $this->setSent($this->getSent() + 1);
		    return;
		}
		parent::sendPerformed($e);
	}

	/**
	 * METHODS
	 */
	function wait($secs) {
		$epm = $this->getEmailsPerMinute();
		campaign_sender_log("Limit Emails For Time Period setting says: Wait $secs seconds ({$epm}EPM), then continue.");
		campaign_log_save($this->batch->campaign, $this->batch->action);
		parent::wait($secs);
	}

	function setEmailsPerMinute($epm) {
		if (!$epm) {
			$this->epm = null;
			return;
		}
		$this->setBytesPerMinute(null);
		$this->epm = abs($epm);
	}

}


?>