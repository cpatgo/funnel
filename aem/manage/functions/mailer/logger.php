<?php

require_once(SWIFT_ABS_PATH . '/Swift/Log/DefaultLog.php');

//Extend the replacements class
class SendingEngineLogger extends Swift_Log_DefaultLog {

	/**
	 * PROPERTIES
	 */
	var $_realLevel = null;

	/**
	 * CONSTRUCTOR
	 */
	function SendingEngineLogger() {
		$this->_realLevel = $this->logLevel;
	}

	/**
	 * METHODS
	 */
	function add($text, $type = SWIFT_LOG_NORMAL) {
		// add comment type prefix at the beginning (swiftmailer stuff)
		if ( $type != '' ) $text = $type . " " . $text;
		// try to add datetimestamps
		$stamp = date('Y-m-d H:i:s');
		if ( $this->logLevel == 4 ) {
			// do microtime processing
			$now = adesk_microtime_get();
			if ( !isset($GLOBALS['adesk_campaign_timer']) ) {
				// first instance, set zero
				$time = 'starting';
			} else {
				// subtract from previous stamp, 6 decimal roundup
				$time = round($now - $GLOBALS['adesk_campaign_timer'], 6);
			}
			// set this stamp as last
			$GLOBALS['adesk_campaign_timer'] = $now;
			// add digits to form 8char string
			if ( strlen("$time") < 8 ) $time .= str_repeat(0, 8 - strlen($time));
			//if ( adesk_str_instr('E-', "$time") ) $time = 'tooshort';//$time = '0.000000';
			// add it to msg
			$text = "[[$stamp $time]] $text";
		} elseif ( $this->logLevel > 0 ) {
			$text = "[[$stamp]] $text";
		}
		$this->entries[] = $text;
		if ( $this->getMaxSize() > 0 ) $this->entries = array_slice($this->entries, (-1 * $this->getMaxSize()));
	}

	function setLogLevel($level = 2) {
		$this->logLevel = (int)$level;
	}

}

?>