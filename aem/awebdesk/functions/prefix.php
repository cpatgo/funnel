<?php

$GLOBALS["adesk_prefixes"] = array();

//if (!isset($_SESSION["adesk_prefix_whitelist"]))
	$GLOBALS["adesk_prefix_whitelist"] = null;

function adesk_prefix_push($prefix) {
    global $adesk_prefixes;

	$adesk_prefixes[] = $prefix;
	adesk_prefix_update();
}

function adesk_prefix_pop() {
    global $adesk_prefixes;

    // array_pop returns null if there's nothing left in the array.

    $val = array_pop($adesk_prefixes);
	adesk_prefix_update();
    return ($val == null) ? "" : $val;
}

function adesk_prefix($str = "") {
    global $adesk_prefixes;
    return end($adesk_prefixes) . $str;
}

function adesk_prefix_first($str = "") {
    global $adesk_prefixes;
    return $adesk_prefixes[0] . $str;
}

function adesk_prefix_tables() {
	#$prefix = addcslashes(mysql_real_escape_string(adesk_prefix(), $GLOBALS['db_link']), '%_');
	#$sql = mysql_query("SHOW TABLES LIKE '$prefix%'", $GLOBALS['db_link']);
	$sql = mysql_query("SHOW TABLES", $GLOBALS['db_link']);
	if ( !$sql ) return array();
	$rval  = array();
	while ( $row = mysql_fetch_row($sql) ) {
		$rval[$row[0]] = $row[0];
	}
	return $rval;

	# old code

	# Get a list of tables which we might select from based on the install.sql file.

	$sql   = adesk_file_get(adesk_admin("sql/install.sql"));
	$lines = explode("\n", trim($sql));
	$rval  = array();

	foreach ($lines as $line) {
		$mat = array();
		if (preg_match('/^CREATE TABLE `([^`]+)`/', $line, $mat))
			$rval[$mat[1]] = $mat[1];
	}
	return $rval;
}

function adesk_prefix_check($mat) {
	if (isset($GLOBALS["adesk_prefix_whitelist"][adesk_prefix($mat[2])]))
		return $mat[1] . adesk_prefix($mat[2]);

	return $mat[1] . '#' . $mat[2];
}

function adesk_prefix_replace_cb($mat) {
	return $mat[1] . adesk_prefix($mat[2]);
}

function adesk_prefix_update() {
	if ( !isset($GLOBALS['db_link']) or !$GLOBALS['db_link'] ) return false;
	$GLOBALS["adesk_prefix_whitelist"] = adesk_prefix_tables();
	return true;
}

function adesk_prefix_replace($query) {
	if (!isset($GLOBALS["adesk_prefix_whitelist"]) || $GLOBALS["adesk_prefix_whitelist"] === null || !isset($GLOBALS["adesk_prefix_whitelist"][adesk_prefix('backend')]))
		adesk_prefix_update();

	$query = ltrim($query);

	if (preg_match('/^INSERT|SELECT|UPDATE|REPLACE/i', $query))
		return preg_replace_callback('/([ \t\r\n,`])#(\w+)/', 'adesk_prefix_check', $query);
	else {
		return preg_replace_callback('/([ \t\r\n,`])#(\w+)/', 'adesk_prefix_replace_cb', $query);
	}
}

if (isset($GLOBALS['adesk_prefix_use']))
    adesk_prefix_push($GLOBALS['adesk_prefix_use']);

?>
