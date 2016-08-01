<?php
// base.php

// The base functions, which support including any file from the base of
// the AC directory (adesk_base), from the admin directory (adesk_admin), and
// from the awebdesk library (awebdesk).

function adesk_basedir() {
    if ( isset($GLOBALS['adesk_app_path']) )
    	return $GLOBALS['adesk_app_path'];
    else
		return dirname(dirname(dirname(__FILE__)));
}

function adesk_base($file = '') {
    if ($file != '')
        return adesk_basedir() . '/' . $file;
    return adesk_basedir();
}

function adesk_admin($file = '') {
    if ($file != '')
        return adesk_basedir() . '/manage/' . $file;
    return adesk_basedir() . '/manage';
}

function adesk_api($file = '') {
    if ($file != '')
        return adesk_basedir() . '/api/' . $file;
    return adesk_basedir() . '/api';
}

function adesk_lang($file = '') {
	$base = adesk_basedir();
	// if not standalone
	$standalone = true;
	if ( isset($GLOBALS['site']) and function_exists('adesk_site_isstandalone') ) {
		$standalone = adesk_site_isstandalone();
	} elseif ( defined('adesk_KB_STANDALONE') ) {
		$standalone = adesk_KB_STANDALONE;
	}
	if ( !$standalone )
		$base = dirname($base);
    if ($file != '')
        return $base . '/lang/' . $file;
    return $base . '/lang';
}

function awebdesk_folder() {
	return basename(dirname(dirname(__FILE__)));
}

function awebdesk($file = '') {
    if ( isset($GLOBALS['adesk_library_path']) )
    	$basedir = $GLOBALS['adesk_library_path'];
    else
		$basedir = adesk_basedir() . '/' . basename(dirname(dirname(__FILE__)));
	if ($file != '')
        return $basedir . '/' . $file;
    return $basedir;
}

function awebdesk_includes($file = '') {
    if ($file != '')
        return awebdesk() . '/includes/' . $file;
    return awebdesk() . '/includes';
}

function awebdesk_url($path = '') {
    if ( isset($GLOBALS['adesk_library_url']) )
    	$basedir = $GLOBALS['adesk_library_url'];
    else
		$basedir = adesk_site_plink() . '/' . basename(dirname(dirname(__FILE__)));
	if ($path != '')
        return $basedir . '/' . $path;
    return $basedir;
}

function awebdesk_api($file = '') {
    if ($file != '')
        return awebdesk() . '/api/' . $file;
    return awebdesk() . '/api';
}

function awebdesk_charts($file = '') {
    if ($file != '')
        return awebdesk() . '/charts/' . $file;
    return awebdesk() . '/charts';
}

function awebdesk_classes($file = '') {
    if ($file != '')
        return awebdesk() . '/classes/' . $file;
    return awebdesk() . '/classes';
}

function awebdesk_assets($file = '') {
    if ($file != '')
        return awebdesk() . '/assets/' . $file;
    return awebdesk() . '/assets';
}

function awebdesk_pear($file = '') {
    if ($file != '')
        return awebdesk() . '/pear/' . $file;
    return awebdesk() . '/pear';
}

function awebdesk_functions($file = '') {
    if ($file != '')
        return awebdesk() . '/functions/' . $file;
    return awebdesk() . '/functions';
}

function awebdesk_smarty_plugins($file = '') {
    if ($file != '')
        return awebdesk() . '/smarty_plugins/' . $file;
    return awebdesk() . '/smarty_plugins';
}

function adesk_init() {
    adesk_unescape_gpc();
}

function adesk_unescape_gpc() {
    // turn off escaping
    @set_magic_quotes_runtime(0);
    if ( get_magic_quotes_gpc() ) {
        $input = array(&$_GET, &$_POST, &$_COOKIE, &$_ENV, &$_SERVER);
        while ( list($k,$v) = each($input) ) {
            foreach ( $v as $key => $val ) {
                if ( !is_array($val) ) {
                    $input[$k][$key] = stripslashes($val);
                    continue;
                }
                $input[] =& $input[$k][$key];
            }
        }
        unset($input);
    }
}

?>