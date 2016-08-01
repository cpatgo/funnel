<?php
// ajax.php

// Below are some very lightweight functions that handle the backend of
// an ajax framework.  Ajax calls are made via URL -- all that is
// normally handled from the javascript side -- and each is mapped to a
// PHP function that you define.

// All functions must be declared using adesk_ajax_declare("..."), to
// prevent arbitrary functions from being executed.  You can, if you
// like, remove from eligibility any function using the adesk_ajax_retract
// function.

// All you need to do from there on is call the adesk_ajax_run() function,
// which will dispatch the current URL parameters (via the $_GET array)
// to a registered function.  If an ajax function returns an array, then
// the result is returned as a nested XML tree in which the root node is
// the name of the function.  If a scalar value is returned, then a
// single element is returned, again named for the function but with the
// scalar as its child text node.  If no value, a null value, a false
// value or an empty string are returned, then an empty element named
// for the function is returned.

// If the function you're calling isn't declared, an error string will
// be returned, and also if you don't have the proper parameters in your
// URL.  All results are base64 encoded using adesk_b64_encode().

require_once dirname(__FILE__) . '/awebdeskapi.php';
require_once dirname(__FILE__) . '/xml.php';
require_once dirname(__FILE__) . '/php.php';
//require_once dirname(__FILE__) . '/b64.php';

$GLOBALS['adesk_ajax_functions'] = array();

$GLOBALS['adesk_ajax_running'] = false;
if ( !isset($GLOBALS['adesk_ajax_encoding']) ) {
	$GLOBALS['adesk_ajax_encoding'] = true;
}
$GLOBALS['adesk_ajax_data_subject'] = '';

function adesk_ajax_declare($func, $physfunc = true) {
    global $adesk_ajax_functions;

	if (!isset($adesk_ajax_functions[$func])) {
	   	if ($physfunc === true)
			$adesk_ajax_functions[$func] = $func;
		else
			$adesk_ajax_functions[$func] = $physfunc;
	}
}

function adesk_ajax_retract($func) {
    global $adesk_ajax_functions;
    unset($adesk_ajax_functions[$func]);
}

function adesk_ajax_exists($func) {
    global $adesk_ajax_functions;
    return isset($adesk_ajax_functions[$func]);
}

function adesk_ajax_function($func) {
    global $adesk_ajax_functions;
    if (isset($adesk_ajax_functions[$func])) {
        $physfunc = $adesk_ajax_functions[$func];

        if ($physfunc === true)
            return $func;
        return $physfunc;
    } else {
        return false;
    }
}

function adesk_ajax_call($func, $params) {
    global $adesk_ajax_functions;

    if (isset($adesk_ajax_functions[$func]))
        return call_user_func_array(adesk_ajax_function($func), $params);
    else
        return null;
}

function adesk_ajax_call_xml($func, $params) {
    return adesk_ajax_xml($func, adesk_ajax_call($func, $params));
}

function adesk_ajax_call_error($str) {
    return array('error' => $str);
}

function adesk_ajax_dispatch($ary) {
    if (isset($ary['f'])) {
        if (!isset($ary['p']))
            $ary['p'] = array();
        if (adesk_ajax_exists($ary['f']))
            return adesk_ajax_call_xml($ary['f'], $ary['p']);
		else {
            return adesk_ajax_error("Invalid function: ".$ary['f']." was not declared");
		}
    }

    return adesk_ajax_error("Invalid URL (requires f and p[] parameters)");
}

// Fake us into thinking we've already run...

function adesk_ajax_dontrun() {
    $GLOBALS['adesk_ajax_running'] = true;
}

function adesk_ajax_running() {
    return $GLOBALS['adesk_ajax_running'];
}

function adesk_ajax_set_subject($subj) {
    $GLOBALS['adesk_ajax_data_subject'] = $subj;
}

function adesk_ajax_run() {
    if (adesk_ajax_running())
        return;

    $GLOBALS['adesk_ajax_running'] = true;
	adesk_ajax_print(adesk_ajax_dispatch($_GET));
}

function adesk_ajax_print($str) {
    header("Content-Type: text/xml; charset=utf-8");
	header("Cache-control: no-store, max-age=0, must-revalidate");
    echo "<?xml version='1.0' encoding='utf-8'?>\n";
    echo adesk_ajax_okchars($str);
	adesk_flush();
}

function adesk_ajax_run_error($str) {
	adesk_ajax_print(adesk_ajax_error($str));
}

function adesk_ajax_error($str) {
    return adesk_ajax_xml("error", $str, false);
}

function adesk_ajax_api_result($stat, $str, $arr = array()) {
	if ( !is_array($arr) ) $arr = array();
	$arr["succeeded"] = intval($stat);
	$arr["message"] = $str;
	return $arr;
}

function adesk_ajax_api_nopermission($subject) {
	return adesk_ajax_api_result(false, sprintf(_a("You do not have a permission to %s"), $subject));
}

function adesk_ajax_api_added($subject, $arr = array()) {
	return adesk_ajax_api_result(true, sprintf(_a("%s added"), $subject), $arr);
}

function adesk_ajax_api_updated($subject, $arr = array()) {
	return adesk_ajax_api_result(true, sprintf(_a("%s updated"), $subject), $arr);
}

function adesk_ajax_api_deleted($subject, $arr = array()) {
	return adesk_ajax_api_result(true, sprintf(_a("%s deleted"), $subject), $arr);
}

function adesk_ajax_api_saved($subject, $arr = array()) {
	return adesk_ajax_api_result(true, sprintf(_a("%s saved"), $subject), $arr);
}

function adesk_ajax_api_autosaved($subject, $arr = array()) {
	return adesk_ajax_api_result(true, sprintf(_a("%s auto-saved"), $subject), $arr);
}

function adesk_ajax_xml($elem, $data, $b64 = true) {
	if ($b64 && $GLOBALS['adesk_ajax_encoding'])
		require_once dirname(__FILE__) . '/b64.php';
	$pos = strpos($elem, "!");
	if ($pos !== false)
		$elem = substr($elem, $pos+1);
	$pos = strpos($elem, ".");
	if ($pos !== false)
		$elem = substr($elem, $pos+1);
    if (isset($data[0])) {
        if ($GLOBALS['adesk_ajax_data_subject'] != '')
            $data = array($GLOBALS['adesk_ajax_data_subject'] => $data);
        else
            $data = array('row' => $data);
    }

	if (defined("adesk_XML_WRITE_NEW")) {
		if ($b64 && $GLOBALS['adesk_ajax_encoding'])
			return adesk_xml_write_new($data, $elem, "adesk_b64_encode");
		else
			return adesk_xml_write_new($data, $elem);
	} else {
		if ($b64 && $GLOBALS['adesk_ajax_encoding'])
			return adesk_xml_write($data, "", $elem, "adesk_b64_encode");
		else
			return adesk_xml_write($data, "", $elem);
	}
}

function adesk_ajax_dont_encode() {
    $GLOBALS['adesk_ajax_encoding'] = false;
}

function adesk_ajax_get_admin() {
    require_once awebdesk_functions('manage.php');
    if (($GLOBALS['admin'] = adesk_admin_get()) == false) {
        return false;
    }

    return true;
}

function adesk_ajax_okchars($str) {
	# Certain (mostly control) characters are illegal in XML, even if they're acceptable in 
	# Unicode/UTF-8.  If we leave them in, we'll get parse errors in some browsers.
	#
	# What's good: tab, newline, carriage return.  What's bad: about everything else.
	# See http://www.w3.org/TR/REC-xml/#charsets for details.

	return preg_replace('/[\x0-\x8\xb\xc\xe\xf\x10-\x1f]/', '', $str);
}

?>
