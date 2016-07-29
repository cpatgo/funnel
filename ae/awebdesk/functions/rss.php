<?php

# This expects $rss to be an array with the following keys:
#
# 	title
# 	link
# 	description
# 	item
# 	pubDate [[optional]]
# 	language [[optional]]
#
# Where items is itself an array, with the following keys:
#
# 	title
# 	link
# 	description
# 	pubDate [[optional]]

function adesk_rss_str(&$rss) {
	$out  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$out .= "<rss version=\"2.0\">\n";

	# sanity check

	if (!isset($rss["item"]))
		$rss["item"] = array();
	if (!isset($rss["pubDate"]))
		$rss["pubDate"] = gmstrftime("%a, %d %m %Y %H:%M:%S GMT");
	if (!isset($rss["language"]))
		$rss["language"] = _i18n("utf-8");

	adesk_rss_escape($rss, "title");
	adesk_rss_escape($rss, "link");
	adesk_rss_escape($rss, "description");
	adesk_rss_escape($rss, "pubDate");
	adesk_rss_escape($rss, "language");

	$out .= "\t<channel>
			<title>$rss[title]</title>
			<link>$rss[link]</link>
			<description>$rss[description]</description>
			<pubDate>$rss[pubDate]</pubDate>
			<language>$rss[language]</language>
";

	foreach ($rss["item"] as $item) {
		$pubDate = '';
		adesk_rss_escape($item, "title");
		adesk_rss_escape($item, "link");
		adesk_rss_escape($item, "description");
		// add publish date if provided
		if (isset($item["pubDate"])) {
			adesk_rss_escape($item, "pubDate");
			$pubDate = "<pubDate>$item[pubDate]</pubDate>";
		}

		$out .= "
			<item>
				<title>$item[title]</title>
				<link>$item[link]</link>
				<description>$item[description]</description>
				$pubDate
			</item>
";
	}

	$out .= "\t</channel>\n</rss>";
	return $out;
}

function adesk_rss_echo(&$rss) {
	header("Content-Type: application/rss+xml");
	echo adesk_rss_str($rss);
	exit;
}

function adesk_rss_escape(&$ary, $key) {
	if (!isset($ary[$key]))
		$ary[$key] = "";
	else
		$ary[$key] = htmlspecialchars($ary[$key]);
}

function adesk_rss_fetch($url, $cacheAge = 3600, $freshOnly = false) {
	// find and initialize MagPieRSS class
	if ( !class_exists('MagpieRSS') ) {
		if ( !defined('MAGPIE_CACHE_DIR') ) define('MAGPIE_CACHE_DIR', adesk_cache_dir()); // /rss folder?
		if ( !defined('MAGPIE_CACHE_ON') ) define('MAGPIE_CACHE_ON', (bool)$cacheAge);
		//define('MAGPIE_CACHE_ON', 0);
		if ( MAGPIE_CACHE_ON and !defined('MAGPIE_CACHE_AGE') ) define('MAGPIE_CACHE_AGE', $cacheAge);
		if ( !defined('MAGPIE_CACHE_FRESH_ONLY') ) define('MAGPIE_CACHE_FRESH_ONLY', $freshOnly);
		if ( !defined('MAGPIE_OUTPUT_ENCODING') ) define('MAGPIE_OUTPUT_ENCODING', strtoupper(_i18n('utf-8')));
		require_once(awebdesk('xml_parser/rss_fetch.inc'));
	}
	// use MagpieRSS to fetch remote RSS file and parse it
	$r = array('url' => $url, 'rss' => fetch_rss($url), 'rss_error' => '');
	// if fetch_rss returned false, we encountered an error
	$r['rss_error'] = ( !$r['rss'] ? magpie_error() : '' );
	if ( !isset($r['rss']->channel) or count($r['rss']->channel) == 0 ) $r['rss'] = false;
	return $r;
}

function adesk_rss_useragent_set($string) {
	$GLOBALS['adesk_rss_useragent'] = $string;
}

function adesk_rss_useragent_unset() { unset($GLOBALS['adesk_rss_useragent']); }

?>
