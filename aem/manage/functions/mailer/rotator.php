<?php

require_once(SWIFT_ABS_PATH . '/Swift/Connection/Rotator.php');
require_once(SWIFT_ABS_PATH . '/Swift/Plugin/ConnectionRotator.php');

//Extend the replacements class
class SendingEngineRotator extends Swift_Connection_Rotator {

	/**
	 * PROPERTIES
	 */
	var $batch = null;

	var $lastConnection = null;

	/**
	 * CONSTRUCTOR
	 */
	function SendingEngineRotator(&$batch, $connections = array()) {
		$this->batch =& $batch;
		parent::Swift_Connection_Rotator($connections);
	}

	function nextConnection() {
		if ( $this->batch and $this->batch->campaign ) {
			campaign_sender_log("Starting to initiate the new connection...");
			campaign_log_save($this->batch->campaign['id'], $this->batch->action);
		}
		$r = parent::nextConnection();
		if ( $r ) {
			$connectionID = $this->getActive();
			// if all good, connection found
			if ( isset($this->connections[$connectionID]) ) {
				$connection =& $this->connections[$connectionID];
				$log =& Swift_LogContainer::getLog();
				$name = get_class($connection);
				if ( adesk_str_instr('smtp', strtolower($name)) ) {
					$name .= " (" . $connection->username . '@' . $connection->server . ':' . $connection->port . ")";
				}
				$log->add("Using connection of type '" . $name . "' in rotator.");
				$mailer =& $connection->_info;
				if ( isset($mailer['dotfix']) ) {
					$encoder =& SendingEngineEncoder::instance();
					$encoder->setDotFix($mailer['dotfix']);
				}
				$log->add("Will use it for next " . $mailer['threshold'] . " emails.");
				// update connections in db and array
				adesk_sql_update_one('#mailer', '=current', "IF(id = '$mailer[id]', 1, 0)");
				foreach ( $this->connections as $k => $v ) {
					$this->connections[$k]->_info['current'] = $k == $connectionID;
				}
				$log->add("Current mailer set.");
			}
		} else {
			//
		}
		if ( $this->batch and $this->batch->campaign ) campaign_log_save($this->batch->campaign['id'], $this->batch->action);
		return $r;
	}

	/**
	 * Call the current connection's postConnect() method
	 */
	function postConnect(&$instance) {
		// this is our overload: load our rotator plugin instead!
		if ( !$instance->getPlugin("_ROTATOR") ) {
			$instance->attachPlugin(new SendingEngineRotatorPlugin(), "_ROTATOR");
			//$instance->attachPlugin(new Swift_Plugin_ConnectionRotator(), "_ROTATOR");
		}
		parent::postConnect($instance);
	}

}





class SendingEngineRotatorPlugin extends Swift_Plugin_ConnectionRotator {

	// constructor overload
	function SendingEngineRotatorPlugin($threshold = 1) {
		parent::Swift_Plugin_ConnectionRotator($threshold);
	}

	function getThreshold() {
		if ( !$this->threshold ) $this->setThreshold(1);
		return parent::getThreshold();
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
		campaign_sender_log("Checking the Rotator setting...");
		campaign_log_save($this->batch->campaign['id'], $this->batch->action);
		// since this is the first listener firing after the send
		// here we can modify other plugins before their listener's sendPerformed() methods fire
		$swift =& $e->getSwift();
		if ( !$swift->getsource ) {
			$connectionID = $swift->connection->getActive();
			if ( isset($swift->connection->connections[$connectionID]) ) {
				// connection found
				$connection =& $swift->connection->connections[$connectionID];
				// connection info found
				$mailer =& $connection->_info;
				// update the mailer in use
				$mailer['sent']++;
				adesk_sql_update_one('#mailer', '=sent', 'sent + 1', "`id` = '$mailer[id]'");
				// print out debugging: completed the mailer update
				campaign_sender_log("Mailer updated.");
			}
			parent::sendPerformed($e);
		}
	}
}

?>