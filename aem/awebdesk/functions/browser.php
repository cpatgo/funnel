<?php

# The browser identification code was inspired by the example set forth at quirksmode.org here:
#   http://www.quirksmode.org/js/detect.html
#
# There are several differences in implementation, however.

function adesk_browser_ident($str = "") {
	# Return a browser/version string which looks like this: Browser <space> A.B
	# where A.B represents the first two version numbers.  We'll also use "A"
	# if that's all there is.
	global $adesk_browser_list;

	if ($str == "") {
		if (!isset($_SERVER["HTTP_USER_AGENT"]))
			return "Unknown";
		$str = $_SERVER["HTTP_USER_AGENT"];
	}

	foreach ($adesk_browser_list as $ent) {
		if (strpos($str, $ent["sub"]) !== false) {
			if (isset($ent["ver"]))
				$vs = $ent["ver"];
			else
				$vs = $ent["id"];

			return $ent["id"] . " " . adesk_browser_version($str, $vs);
		}
	}

	return "Unknown";
}

function adesk_browser_version($str, $vs) {
	$pos  = strpos($str, $vs);
	$rval = "";

	if ($pos === false)
		return "";

	$pos += strlen($vs) ;
	$sub  = substr($str, $pos);
	$mat  = array();

	if (preg_match('/^\d+(\.\d+)?/', $sub, $mat))
		$rval = $mat[0];

	return $rval;
}

function adesk_browser_os($str = "") {
	# Return our best guess as to the user's operating system.
	global $adesk_browser_list_os;

	if ($str == "") {
		if (!isset($_SERVER["HTTP_USER_AGENT"]))
			return "Unknown";
		$str = $_SERVER["HTTP_USER_AGENT"];
	}

	foreach ($adesk_browser_list_os as $ent) {
		if (strpos($str, $ent["sub"]) !== false)
			return $ent["id"];
	}

	return "Unknown";
}

function adesk_browser_ie_compat($ua = null) {
	if ( is_null($ua) ) {
		if ( !isset($_SERVER['HTTP_USER_AGENT']) ) return false;
		$ua = $_SERVER['HTTP_USER_AGENT'];
	}
	return preg_match('/MSIE 7\.0/i', $ua) && preg_match('/Trident\/4\.0/i', $ua);
}

$adesk_browser_list = array(
	array(
		"sub" => "MSIE",
		"id"  => "IE",
		"ver" => "MSIE",
	),
	array(
		"sub" => "Firefox",
		"id"  => "Firefox",
	),
	array(
		"sub" => "Safari",
		"id"  => "Safari",
		"ver" => "Version",
	),
	array(
		"sub" => "Opera",
		"id"  => "Opera",
	),
	array(
		"sub" => "Gecko",
		"id"  => "Mozilla",
		"ver" => "rv",
	),
	array(
		"sub" => "Chrome",
		"id"  => "Chrome",
	),
	array(
		"sub" => "OmniWeb",
		"id"  => "OmniWeb",
		# The slash is included because OmniWeb's version strings look like
		# OmniWeb/v123.45, giving us one extra character we need to skip
		# before we get to the version.
		"ver" => "OmniWeb/",
	),
	array(
		"sub" => "iCab",
		"id"  => "iCab",
	),
	array(
		"sub" => "Konqueror",
		"id"  => "Konqueror",
	),
	array(
		"sub" => "Camino",
		"id"  => "Camino",
	),
	array(
		"sub" => "Netscape",
		"id"  => "Netscape",
	),
	array(
		"sub" => "Mozilla",
		"id"  => "Netscape",
		"ver" => "Mozilla",
	),
);

$adesk_browser_list_os = array(
	array(
		"sub" => "Windows NT 5.1",
		"id"  => "WinXP",
	),
	array(
		"sub" => "Windows NT 6.0",
		"id"  => "WinVista",
	),
	array(
		"sub" => "Windows NT 5.3",
		"id"  => "Win2003",
	),
	array(
		"sub" => "Macintosh",
		"id"  => "MacOSX",
	),
	array(
		"sub" => "Linux",
		"id"  => "Linux",
	),
	array(
		"sub" => "Windows NT 5.0",
		"id"  => "Win2000",
	),
	array(
		"sub" => "iPhone",
		"id"  => "iPhone",
	),
	array(
		"sub" => "Windows 98",
		"id"  => "Win98",
	),
	array(
		"sub" => "Win98",
		"id"  => "Win98",
	),
	array(
		"sub" => "FreeBSD",
		"id"  => "FreeBSD",
	),
	array(
		"sub" => "Windows 95",
		"id"  => "Win95",
	),
	array(
		"sub" => "Win95",
		"id"  => "Win95",
	),
	array(
		"sub" => "WinNT4.0",
		"id"  => "WinNT",
	),
	array(
		"sub" => "Windows NT 4.0",
		"id"  => "WinNT",
	),
	array(
		"sub" => "Win 9x 4.90",
		"id"  => "WinME",
	),
	array(
		"sub" => "Windows ME",
		"id"  => "WinME",
	),
);

?>
