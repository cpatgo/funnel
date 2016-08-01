<?php
// emailclient.php

function adesk_emailclient_ident($useragent = "", $referer = "") {
	# Return a client/version string which looks like this: EmailClient <space> A.B
	# where A.B represents the first two version numbers.  We'll also use "A"
	# if that's all there is.

	$GLOBALS['adesk_emailclient_isdirty'] = false;

	if ($useragent == "") {
		if (!isset($_SERVER["HTTP_USER_AGENT"]))
			return "";
		$useragent = (string)$_SERVER["HTTP_USER_AGENT"];
	}

	if ($referer == "") {
		if (isset($_SERVER["HTTP_REFERER"]))
			$referer = trim((string)$_SERVER["HTTP_REFERER"]);
	}
	$urlarr = @parse_url($referer);
	$domain = ( isset($urlarr['host']) ? strtolower($urlarr['host']) : '' );

	foreach ($GLOBALS['adesk_emailclient_list'] as $ent) {
		if (strpos($useragent, $ent["sub"]) !== false) {
			$r = $ent["id"];

			if (isset($ent["ver"])) {
				$ver = adesk_emailclient_version($useragent, $ent["ver"]);
				if ( $ver ) $r .= " " . $ver;
			}

			return $r;
		} elseif (isset($ent["tld"]) && $domain != "") {
			// try to find it by referer (web based only)
			$tld = substr($domain, -1 * strlen($ent["tld"]));
			if ( $tld == $ent["tld"] ) {
				return $ent["id"];
			}
		}
	}

	// hack for M$
	if ( adesk_str_instr("MSIE", $useragent) && !$referer ) {
		$GLOBALS['adesk_emailclient_isdirty'] = true;
		return "Outlook 2000-2003";
	}

	return "";
}

function adesk_emailclient_version($str, $vs) {
	$pos  = strpos($str, $vs);
	$rval = "";

	if ($pos === false)
		return "";

	$pos += strlen($vs) + 1;
	$sub  = substr($str, $pos);
	$mat  = array();

	if (preg_match('/^\d+(\.\d+)?/', $sub, $mat))
		$rval = $mat[0];

	return $rval;
}

$GLOBALS['adesk_emailclient_list'] = array(
	// desktop email clients
	/*
	array(
		"id"  => "Outlook 2000",
		"sub" => "MSOffice",
		//"ver" => "MSIE",
	),
	array(
		"id"  => "Outlook 2002/XP",
		"sub" => "MSOffice",
		//"ver" => "MSIE",
	),
	array(
		"id"  => "Outlook 2003",
		"sub" => "MSOffice",
		//"ver" => "MSIE",
	),
	*/
	array(
		"id"  => "Outlook 2007",
		"sub" => "MSOffice 12",
		//"ver" => "MSIE",
	),
	array(
		"id"  => "Outlook 2010",
		"sub" => "Microsoft Outlook 14",
		//"ver" => "MSIE",
	),
	array(
		"id"  => "Lotus Notes 6.5",
		"sub" => "Lotus-Notes/6",
		//"ver" => "MSIE",
	),
	array(
		"id"  => "Lotus Notes 7",
		"sub" => "Lotus-Notes/7",
		//"ver" => "MSIE",
	),
	array(
		"id"  => "Lotus Notes 8",
		"sub" => "Lotus-Notes/8",
		//"ver" => "MSIE",
	),
	array(
		"id"  => "Thunderbird 2.0",
		"sub" => "Thunderbird/2",
		//"ver" => "Thunderbird",
	),
	array(
		"id"  => "Thunderbird 3.0",
		"sub" => "Thunderbird/3",
		//"ver" => "Thunderbird",
	),

	// desktop email clients
	array(
		"id"  => "Mobile Me",
		"sub" => "something that should never be matched",
		"tld" => ".me.com",
	),
	array(
		"id"  => "Yahoo! Mail",
		"sub" => "something that should never be matched",
		"tld" => "mail.yahoo.com",
	),
	array(
		"id"  => "AOL Mail",
		"sub" => "something that should never be matched",
		"tld" => "webmail.aol.com",
	),
	array(
		"id"  => "GMail",
		"sub" => "something that should never be matched",
		"tld" => "mail.google.com",
	),
	array(
		"id"  => "Windows Live Hotmail",
		"sub" => "something that should never be matched",
		"tld" => "mail.live.com",
	),

	// mobile email clients
	array(
		"id"  => "iPad",
		"sub" => "iPad",
		//"ver" => "iPhone OS",
	),
	array(
		"id"  => "iPhone",
		"sub" => "iPhone",
		"ver" => "iPhone OS",
	),
	array(
		"id"  => "Android",
		"sub" => "Android",
		"ver" => "Android",
	),
	array(
		"id"  => "Outlook Mobile",
		"sub" => "Windows CE",
		"ver" => "MSIE",
	),
	array(
		"id"  => "BlackBerry",
		"sub" => "BlackBerry",
		//"ver" => "PalmOS",
	),
	array(
		"id"  => "PalmOS",
		"sub" => "PalmOS",
		//"ver" => "PalmOS",
	),
	array(
		"id"  => "Eudora",
		"sub" => "EudoraWeb",
		//"ver" => "PalmOS",
		//"ver" => "EudoraWeb",
	),
	array(
		"id"  => "Kindle",
		"sub" => "Kindle",
		//"ver" => "Kindle",
	),
);

?>