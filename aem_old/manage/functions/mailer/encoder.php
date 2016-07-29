<?php

require_once(SWIFT_ABS_PATH . '/Swift/Message/Encoder.php');

//Extend the replacements class
class SendingEngineEncoder extends Swift_Message_Encoder {

	/**
	 * PROPERTIES
	 */
	var $dotFix = false;

	/**
	 * CONSTRUCTOR
	 */

	/**
	 * Retreive an instance of the encoder as a singleton.
	 * New instances are never ever needed since it's monostatic.
	 * @return Message_Encoder
	 */
	function &instance() {
		static $instance = null;
		if ( !$instance ) {
			$instance = array(new SendingEngineEncoder());
		}
		return $instance[0];
	}

	/**
	 * METHODS
	 */
	function setDotFix($dotFix) {
		$this->dotFix = (bool)$dotFix;
	}


	/**
	 * Fixes line endings to be whatever is specified by the user
	 * SMTP requires the CRLF be used, but using sendmail in -t mode uses LF
	 * This method also escapes dots on a start of line to avoid injection
	 * @param string The data to fix
	 * @return string
	 */
	function fixLE($data, $le) {
		$data = str_replace(array("\r\n", "\r"), "\n", $data);
		if ($le != "\n") $data = str_replace("\n", $le, $data);
		return preg_replace('/^\./m', '..\1', $data);
	}

}


?>
