<?php

# Block containers.
$GLOBALS["adesk_htmltext_patn_block"] = '#</div>|</p>|</td>|</li>#im';

# Line break.
$GLOBALS["adesk_htmltext_patn_br"] = '#<br[^>]*>#im';

# Horizontal line.
$GLOBALS["adesk_htmltext_patn_hr"] = '#<hr[^>]*>#im';

# Link.
$GLOBALS["adesk_htmltext_patn_a"] = '#<a([^>]+)>([^<]*)</a>#im';

# Emphasis.
$GLOBALS["adesk_htmltext_patn_em"] = array(
	'#<em[^>]*>([^<]+)</em>#im',
	'#<strong[^>]*>([^<]+)</strong>#im',
	'#<b[^>]*>([^<]+)</b>#im',
	'#<i[^>]*>([^<]+)</i>#im',
	'#<u[^>]*>([^<]+)</u>#im',
	'#<h1[^>]*>([^<]+)</h1>#im',
	'#<h2[^>]*>([^<]+)</h2>#im',
	'#<h3[^>]*>([^<]+)</h3>#im',
	'#<h4[^>]*>([^<]+)</h4>#im',
	'#<h5[^>]*>([^<]+)</h5>#im',
	'#<h6[^>]*>([^<]+)</h6>#im',
);

# Lists.
$GLOBALS["adesk_htmltext_patn_listitem"] = '#<li[^>]*>#im';

function adesk_htmltext_convert($html) {
  # Our earlier templates included links like "<a href='<web link>'>...</a>",
	# which throws off our patterns.  So here's a manual fix.
	$html = str_replace("<web link>", "web link", $html);

	# Don't bother with the head tag, if there is one.  At the very least,
	# it'll screw up our <li> patterns.
	$html = adesk_str_strip_tag($html, "head");

	# First try to revert known entities.
	$html = str_replace('&quot;', '"', $html);
	$html = str_replace('&amp;', '&', $html);
	$html = str_replace('&copy;', '©', $html);
	# Remove entities entirely.
	$html = preg_replace('/&[^ ;]+;/m', "", $html);

	# Throw away line breaks and extra spacing; behave like a real HTML
	# interpreter.
	$html = str_replace("\n", "", $html);
	$html = str_replace("\r", "", $html);
	$html = preg_replace('/[ \t]+/', ' ', $html);

	# Convert tags that we want to have some intelligent formatting.
	$html = preg_replace($GLOBALS["adesk_htmltext_patn_block"], "\n\n", $html);
	$html = preg_replace($GLOBALS["adesk_htmltext_patn_br"], "\n", $html);
	$html = preg_replace($GLOBALS["adesk_htmltext_patn_hr"], "\n" . str_repeat("_", 70) . "\n\n", $html);
	$html = preg_replace_callback($GLOBALS["adesk_htmltext_patn_em"], 'adesk_htmltext_emphasize', $html);
	$html = preg_replace($GLOBALS["adesk_htmltext_patn_listitem"], '* ', $html);

	# Strip out spans now, as well as images, in case they mess up our <A> pattern.
	$html = adesk_str_strip_tag_short($html, "span");
	$html = adesk_str_strip_tag_short($html, "/span");
	$html = adesk_str_strip_tag_short($html, "img");

	$html = preg_replace_callback($GLOBALS["adesk_htmltext_patn_a"], 'adesk_htmltext_link', $html);

	# Remove all the rest.
	$html = adesk_str_strip_tags($html);

	# Get rid of lead spacing as well as extra line breaks.
	$html = preg_replace('/^[ \t]+/m', "", $html);
	$html = preg_replace('/\n\n\n+/m', "\n\n", $html);

	# Wrap it somewhat sanely.
	$html = wordwrap($html, 72);

	return $html;
}

function adesk_htmltext_emphasize($match) {
	return strtoupper(trim($match[1]));
}

function adesk_htmltext_link($match) {
	$attrs = array();
	$href  = "";

	if (!isset($match[1]) || !isset($match[2]))
		return "";

	if (preg_match('/href="([^"]+)"/', $match[1], $attrs) || preg_match('/href=\'([^\']+)\'/', $match[1], $attrs)) {
		$href = $attrs[1];
	}

	$rval = trim($match[2]);

	if ($href != "" && $rval != "")
		$rval .= " ($href)";

	return $rval;
}

?>
