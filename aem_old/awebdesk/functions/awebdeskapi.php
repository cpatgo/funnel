<?php
// awebdeskapi.php

// Functions for internal (ajax calls) and external api

function adesk_api_error($text) {
	$remote = ( defined('adesk_API_REMOTE') and adesk_API_REMOTE );
	$output = ( $remote? adesk_api_output(adesk_API_REMOTE_OUTPUT) : 'xml' );

	// if internal (ajax calls), return unmodified version in xml
	if ( !$remote ) return adesk_ajax_run_error($text);

	// convert an array for remote
	$r = adesk_api_error_build($text);
	// push output back as well
	$r['result_output'] = $output;

	// encode as needed
	if ( $output == 'xml' ) {
		adesk_api_xml($r);
	} elseif ( $output == 'serialize' ) {
		adesk_api_serialize($r);
	} elseif ( $output == 'json' ) {
		adesk_api_json($r);
	}
}

function adesk_api_run() {
    if (adesk_ajax_running())
        return;

    $GLOBALS['adesk_ajax_running'] = true;

	$remote = ( defined('adesk_API_REMOTE') and adesk_API_REMOTE );
	$output = ( $remote? adesk_api_output(adesk_API_REMOTE_OUTPUT) : 'xml' );

	//$action = ( isset($_GET['f']) ? $_GET['f'] : '' );
    $r = adesk_api_dispatch($_GET);

	if ( !$remote ) {
		// if internal (ajax calls), return unmodified version in xml
		adesk_ajax_print(adesk_ajax_xml(adesk_http_param('f'), $r));
		return;
	} else {
		// convert an array for remote
		if ( !isset($r['result_code']) ) {
			// result code
			if ( isset($r['succeeded']) ) {
				$r['result_code'] = $r['succeeded'];
				unset($r['succeeded']);
			} else {
				$r['result_code'] = (int)(bool)$r;
			}
		}
		if ( !isset($r['result_message']) ) {
			// result message
			if ( isset($r['message']) ) {
				$r['result_message'] = $r['message'];
				unset($r['message']);
			} else {
				if ( $r['result_code'] ) {
					$r['result_message'] = _a("Success: Something is returned");
				} else {
					$r['result_message'] = _a("Failed: Nothing is returned");
				}
			}
		}

		// push output back as well
		$r['result_output'] = $output;
	}

	// encode as needed
	if ( $output == 'xml' ) {
		adesk_api_xml($r);
	} elseif ( $output == 'serialize' ) {
		adesk_api_serialize($r);
	} elseif ( $output == 'json' ) {
		adesk_api_json($r);
	}
}

function adesk_api_xml($arr) {
	adesk_ajax_print(adesk_ajax_xml($_GET['f'], $arr));
}

function adesk_api_serialize($arr) {
	header('Content-type: text/plain; charset=' . _i18n("utf-8"));
	echo serialize($arr);
	exit;
}

function adesk_api_json($arr) {
	require_once awebdesk_functions('json.php');
	echo json_encode($arr);
}

function adesk_api_output($output) {
	return ( !in_array($output, array('xml', 'serialize', 'json')) ? 'xml' : $output );
}

function adesk_api_input($action) {
	return array(
		// translate action
		'f' => $action['action'],
		// translate params
		'p' => array_map('adesk_http_param', $action['params']),
	);
}

function adesk_api_dispatch($ary) {
    if (isset($ary['f'])) {
        if (!isset($ary['p']))
            $ary['p'] = array();
        if (adesk_ajax_exists($ary['f']))
            return adesk_ajax_call($ary['f'], $ary['p']);
		else {
            return adesk_api_error_build("Invalid function: ".$ary['f']." was not declared");
		}
    }

    return adesk_api_error_build("Invalid URL");
}

function adesk_api_error_build($text) {
	return array(
		'result_code' => 0,
		'result_message' => $text,
	);
}

?>
