<?php

$GLOBALS["html_matched"] = array();

function html_savefix($source) {
	# Alter HTML in such a way as to be more friendly to email clients.

	global $html_matched;
	$rval = $source;

	if (strpos(strtolower($rval), "<body") === false) {
		$rval = "<body>" . $rval . "</body>";
	}
	
	# Strip out some tags.
	$rval = adesk_str_strip_tag($rval, "noscript");
	$rval = adesk_str_strip_tag($rval, "title");
	
	# float: left in images can cause problems in Microsoft Outlook 2007.
	$rval = preg_replace_callback('#<img [^>]+float:\s*left[^>]*>#im', 'html_fix_addalignleft', $rval);

	if (false && strpos(strtolower($rval), "img { display: block }") === false) {
		# Make sure we have display: block in img tags (mostly for Gmail).  
		# Only run this if we have never done so before.
		$rval = preg_replace_callback('#<body[^>]*>#im', 'html_fix_adddisplayblock', $rval);
	}

	return $rval;
}

function html_sendfix($source) {
	# Some last minute changes to HTML source that we don't necessarily want to commit to any saved version.
}

# Fix functions.
function html_fix_addalignleft($str) {
	if (preg_match('/align=[\'"]left/i', $str[0]))
		return $str[0];

	return preg_replace('/<img/i', "<img align='left'", $str[0]);
}

function html_fix_adddisplayblock($str) {
	return "<style> img { display: block } </style>" . $str[0];
}

?>
