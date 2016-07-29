<?php

// pre PHP 5.2 compatibility
if ( !function_exists('json_encode') ) {

	require_once dirname(dirname(__FILE__)) . '/classes/json.php';

	function json_encode($arg) {
		if ( !isset($GLOBALS['services_json']) ) {
			$GLOBALS['services_json'] = new Services_JSON();
		}
		return $GLOBALS['services_json']->encode($arg);
	}
}

// pre PHP 5.2 compatibility
if ( !function_exists('json_decode') ) {

	require_once dirname(dirname(__FILE__)) . '/classes/json.php';

	function json_decode($arg) {
		if ( !isset($GLOBALS['services_json']) ) {
			$GLOBALS['services_json'] = new Services_JSON();
		}
		return $GLOBALS['services_json']->decode($arg);
	}
}

?>
