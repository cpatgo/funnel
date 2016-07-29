<?php

require_once(awebdesk_classes('assets.php'));
require_once(awebdesk_functions('sql.php'));

function adesk_assets_params(&$params) {
    $url = '';

    foreach ($params as $var => $val)
        $url .= "&".urlencode($var)."=".urlencode($val);

    return $url;
}

function adesk_assets_admin($action, $params = array()) {
    return adesk_site_alink("desk.php?action=".urlencode($action) . adesk_assets_params($params));
}

function adesk_assets_public($action, $params = array()) {
    return adesk_site_plink("index.php?action=".urlencode($action) . adesk_assets_params($params));
}

function adesk_assets_ischecked($var) {
    if ($var == '')
        return 0;
    return 1;
}

function adesk_assets_process($smarty) {
    require_once adesk_admin('functions/assets.php');

    $proc = assetsFactory::Getassets($smarty);
    $proc->process($smarty);
}

function adesk_assets_switch(&$smarty, $where, $action, $whitelist = array()) {
	$action = basename($action);

	# What if it's . or ..?  Or a hidden filename?

	if ($action == "" || strpos($action, ".") !== false)
		/*$action = 'startup';*/return;

	if ( ( $where == "global" or count($whitelist) > 0 ) and !in_array($action, $whitelist) )
		return;

	# First check the program's assets directory; then check awebdesk.
	# Just in case we want to support assets overloading.

	$path = "";

	switch ($where) {
		case "public":
			$path = adesk_base("assets");
			break;

		case "admin":
			$path = adesk_admin("assets");
			break;

		case "global":
			$path = awebdesk("assets");
			break;

		default:
			return;
	}

	$file  = "$path/$action.php";
	$class = strtolower($action) . "_assets";

	if (is_file($file)) {
		require_once $file;

		if (class_exists($class)) {
			return new $class;
		}
	}

	return null;
}


function adesk_assets_find(&$smarty, $action, $admin = false) {
	// looking for which assets to load up
	$where = ( $admin ? 'admin' : 'public' );

	/*
		APPLICATION assets SWITCHER
	*/
	$obj = adesk_assets_switch($smarty, $where, $action);
	if ( is_object($obj) and method_exists($obj, 'process') ) return $obj;

	/*
		AC GLOBAL assets SWITCHER
	*/
	$whitelistIndex = 'adesk_assets_whitelist';
	if ( $admin ) $whitelistIndex .= '_admin';
	$whitelist = ( isset($GLOBALS[$whitelistIndex]) ? $GLOBALS[$whitelistIndex] : array() );
	$obj = adesk_assets_switch($smarty, 'global', $action, $whitelist);
	if ( is_object($obj) and method_exists($obj, 'process') ) return $obj;

	/*
		STARTUP FALLBACK assets
	*/
	$obj = adesk_assets_switch($smarty, $where, 'startup');
	if ( is_object($obj) and method_exists($obj, 'process') ) return $obj;

	die('assets not found. System halted.');
}

?>
