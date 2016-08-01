<?php

function adesk_str_htmlentities_num($str) {
	for ($out = "", $i = 0, $len = strlen($str); $i < $len; $i++) {
		$ord = ord(substr($str, $i, 1));
		if ($ord > 127)
			$out .= sprintf("&#%d;", $ord);
		else
			$out .= substr($str, $i, 1);
	}

	return $out;
}

function adesk_str_filter_dirty($str) {
    require_once(dirname(__FILE__).'/str_lists.php');
    return preg_replace($_SESSION['adesk_str_dirty_words'], $_SESSION['adesk_str_dirty_replacements'], $str);
}

function adesk_str_filter_shouting($str, $skip_first_tag = false) {
    if ($skip_first_tag && substr($str, 0, 1) == '<') {
        $str = strtolower($str);
        for ($i = 1; $i < strlen($str); $i++) {
            if (substr($str, $i, 1) == '>' && substr($str, $i+1, 1) >= 'a' && substr($str, $i+1, 1) <= 'z') {
                $str[$i+1] = ucfirst(substr($str, $i+1, 1));
                break;
            }
        }

        return $str;
    } else
        return ucfirst(strtolower($str));
}

function adesk_str_is_upper($str) {
    $len = strlen($str);

    for ($i = 0; $i < $len; $i++) {
        if (substr($str, $i, 1) >= 'a' && substr($str, $i, 1) <= 'z')
            return false;
    }

    return true;
}

function adesk_str_this_url() {
    if ( !function_exists('adesk_http_is_ssl') ) require_once(awebdesk_functions('http.php'));
    $proto = ( adesk_http_is_ssl() ? 'https' : 'http' );

    if (!isset($_SERVER['REQUEST_URI'])) {
        $_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'], 1) . '?' . $_SERVER['QUERY_STRING'];
    }

    return $proto . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
}

function adesk_str_nchars($ch, $num) {
    $out = '';

    while ($num--)
        $out .= $ch;

    return $out;
}

function adesk_str_shorten($text, $chars = 60) {
	if ( strlen($text) < $chars ) return $text;
	$cs = strtoupper(_i18n("utf-8"));
	if (function_exists('iconv_strlen'))
		$textLen = @iconv_strlen( ( $text != '' ? @iconv($cs, $cs . '//IGNORE', $text) : '' ), $cs);
	elseif ( function_exists('mb_strlen'))
		$textLen = mb_strlen($text, $cs);
	else {
		$textLen = strlen($text);

	}
    $text .= ' ';

	while ($chars > 0 && ord($text[$chars-1]) > 127)
		$chars--;

    $text = substr($text, 0, $chars);

    $lastSpacePos = strrpos($text, ' ');
    if ($lastSpacePos !== false)
        $text = substr($text, 0, $lastSpacePos);
    if ($textLen > strlen($text))
        $text .= '...';

    return $text;
}

function adesk_str_middleshorten($text, $front_chars = 15, $back_chars = 15) {
	$cs = strtoupper(_i18n("utf-8"));
	if ( function_exists('mb_strlen') )
		$textLen = mb_strlen($text, $cs);
	elseif ( function_exists('iconv_strlen') )
		$textLen = iconv_strlen( ( $text != '' ? iconv($cs, $cs . '//IGNORE', $text) : '' ), $cs);
	else
		$textLen = strlen($text);
	if ( $textLen < $front_chars + $back_chars ) return $text;
    $front = substr($text, 0, $front_chars);
    $back  = substr($text, $textLen - $back_chars, $back_chars);
    return $front . '...' . $back;
}

function adesk_str_filesize_friendly($size) {
	$count = 0;
	$format = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
	while( ( $size / 1024 ) > 1 && $count < 8 ) {
		$size = $size / 1024;
		$count++;
	}
	$decimals = (int)( $size < 10 );
	$return = number_format($size, $decimals, '.', ' ') . ' ' . $format[$count];
	return $return;
}

function adesk_str_unsigned_int($num) {
    return sprintf("%u", $num);
}

function adesk_str_unsigned_ip($addr) {
    return adesk_str_unsigned_int(ip2long($addr));
}

function adesk_str_ip_origin($addr) {
    if (defined('adesk_HAS_COUNTRY_DATA')) {
        $netorder = adesk_str_unsigned_ip($addr);
        $rs       = adesk_sql_query("SELECT `COUNTRY_NAME` FROM `#country_data` WHERE `IP_FROM` >= $netorder AND `IP_TO` <= $netorder");

        if (!$rs || adesk_sql_num_rows($rs) < 1)
            return "";

        $row      = adesk_sql_fetch_row($rs);
        return $row[0];
    }

    return "";
}

function adesk_str_instr($needle, $haystack) {
	return ( strpos($haystack, $needle) !== false );
}

function adesk_str_clear_any_prefix($str) {
    return preg_replace('/^\d+_(.+)$/', '\1', $str);
}

function adesk_str_unescape_gpc($str) {
	return ( get_magic_quotes_gpc() ? stripslashes($str) : $str );
}

# This sounds broader than it is.  This function is designed to strip out our ansi
# codes we use in the makefile and replace it with an html (css) color.  To replace
# the normal ansi code, which is for bold, $opt should be an array containing a key
# called "bold" which has a color value (either a keyword, like red, or a css color
# hex code).

function adesk_str_ansi_replace($str, $opt = array()) {
	if (!isset($opt["bold"]))
		$opt["bold"] = "red";

	$str = str_replace("\033[1m", "<span style='color: $opt[bold]'>", $str);
	$str = str_replace("\033[0m", "</span>", $str);

	return $str;
}


function adesk_str_urlsafe($str) {
	// strip all tags first
	$str = adesk_str_strip_tags($str);
	// encode escaped octets
	$str = preg_replace('/%([a-fA-F0-9][a-fA-F0-9])/', '-=-$1-=-', $str);
	// remove percent signs
	$str = str_replace('%', '', $str);
	// decode found octets
	$str = preg_replace('/-=-([a-fA-F0-9][a-fA-F0-9])-=-/', '%$1', $str);
	// do your best to mask all weird chars
	$str = adesk_str_remove_accents($str);
	// if string is in utf8
	if ( adesk_utf_check($str) ) {
		// and we have multibyte function available
		if ( function_exists('mb_strtolower') ) {
			// pass it through
			$str = mb_strtolower($str, 'UTF-8');
		}
		// encode string for usage in url
		$str = adesk_utf_uri_encode($str, 200);
	}
	// paths should be lowercased
	$str = strtolower($str);
	// remove all entities,
	$str = preg_replace('/&.+?;/', '', $str);
	// harmfull chars,
	$str = preg_replace('/[^%a-z0-9 ._-]/', '', $str);
	// whitespaces
	$str = preg_replace('/\s+/', '-', $str);
	// and other...
	$str = preg_replace('/-+/', '-', $str);
	$str = trim($str, '-');
	// return clean string
	return $str;
}



function adesk_str_remove_accents($string) {
	// if none found, return the string right away
	if ( !preg_match('/[\x80-\xff]/', $string) ) {
		return $string;
	}
	if ( adesk_utf_check($string) ) {
		// if string is in utf8
		$chars = array(
			// decompositions for Latin-1 Supplement
			chr(195) . chr(128) => 'A', chr(195) . chr(129) => 'A',
			chr(195) . chr(130) => 'A', chr(195) . chr(131) => 'A',
			chr(195) . chr(132) => 'A', chr(195) . chr(133) => 'A',
			chr(195) . chr(135) => 'C', chr(195) . chr(136) => 'E',
			chr(195) . chr(137) => 'E', chr(195) . chr(138) => 'E',
			chr(195) . chr(139) => 'E', chr(195) . chr(140) => 'I',
			chr(195) . chr(141) => 'I', chr(195) . chr(142) => 'I',
			chr(195) . chr(143) => 'I', chr(195) . chr(145) => 'N',
			chr(195) . chr(146) => 'O', chr(195) . chr(147) => 'O',
			chr(195) . chr(148) => 'O', chr(195) . chr(149) => 'O',
			chr(195) . chr(150) => 'O', chr(195) . chr(153) => 'U',
			chr(195) . chr(154) => 'U', chr(195) . chr(155) => 'U',
			chr(195) . chr(156) => 'U', chr(195) . chr(157) => 'Y',
			chr(195) . chr(159) => 's', chr(195) . chr(160) => 'a',
			chr(195) . chr(161) => 'a', chr(195) . chr(162) => 'a',
			chr(195) . chr(163) => 'a', chr(195) . chr(164) => 'a',
			chr(195) . chr(165) => 'a', chr(195) . chr(167) => 'c',
			chr(195) . chr(168) => 'e', chr(195) . chr(169) => 'e',
			chr(195) . chr(170) => 'e', chr(195) . chr(171) => 'e',
			chr(195) . chr(172) => 'i', chr(195) . chr(173) => 'i',
			chr(195) . chr(174) => 'i', chr(195) . chr(175) => 'i',
			chr(195) . chr(177) => 'n', chr(195) . chr(178) => 'o',
			chr(195) . chr(179) => 'o', chr(195) . chr(180) => 'o',
			chr(195) . chr(181) => 'o', chr(195) . chr(182) => 'o',
			chr(195) . chr(182) => 'o', chr(195) . chr(185) => 'u',
			chr(195) . chr(186) => 'u', chr(195) . chr(187) => 'u',
			chr(195) . chr(188) => 'u', chr(195) . chr(189) => 'y',
			chr(195) . chr(191) => 'y',
			// Decompositions for Latin Extended-A
			chr(196) . chr(128) => 'A', chr(196) . chr(129) => 'a',
			chr(196) . chr(130) => 'A', chr(196) . chr(131) => 'a',
			chr(196) . chr(132) => 'A', chr(196) . chr(133) => 'a',
			chr(196) . chr(134) => 'C', chr(196) . chr(135) => 'c',
			chr(196) . chr(136) => 'C', chr(196) . chr(137) => 'c',
			chr(196) . chr(138) => 'C', chr(196) . chr(139) => 'c',
			chr(196) . chr(140) => 'C', chr(196) . chr(141) => 'c',
			chr(196) . chr(142) => 'D', chr(196) . chr(143) => 'd',
			chr(196) . chr(144) => 'D', chr(196) . chr(145) => 'd',
			chr(196) . chr(146) => 'E', chr(196) . chr(147) => 'e',
			chr(196) . chr(148) => 'E', chr(196) . chr(149) => 'e',
			chr(196) . chr(150) => 'E', chr(196) . chr(151) => 'e',
			chr(196) . chr(152) => 'E', chr(196) . chr(153) => 'e',
			chr(196) . chr(154) => 'E', chr(196) . chr(155) => 'e',
			chr(196) . chr(156) => 'G', chr(196) . chr(157) => 'g',
			chr(196) . chr(158) => 'G', chr(196) . chr(159) => 'g',
			chr(196) . chr(160) => 'G', chr(196) . chr(161) => 'g',
			chr(196) . chr(162) => 'G', chr(196) . chr(163) => 'g',
			chr(196) . chr(164) => 'H', chr(196) . chr(165) => 'h',
			chr(196) . chr(166) => 'H', chr(196) . chr(167) => 'h',
			chr(196) . chr(168) => 'I', chr(196) . chr(169) => 'i',
			chr(196) . chr(170) => 'I', chr(196) . chr(171) => 'i',
			chr(196) . chr(172) => 'I', chr(196) . chr(173) => 'i',
			chr(196) . chr(174) => 'I', chr(196) . chr(175) => 'i',
			chr(196) . chr(176) => 'I', chr(196) . chr(177) => 'i',
			chr(196) . chr(178) => 'IJ',chr(196) . chr(179) => 'ij',
			chr(196) . chr(180) => 'J', chr(196) . chr(181) => 'j',
			chr(196) . chr(182) => 'K', chr(196) . chr(183) => 'k',
			chr(196) . chr(184) => 'k', chr(196) . chr(185) => 'L',
			chr(196) . chr(186) => 'l', chr(196) . chr(187) => 'L',
			chr(196) . chr(188) => 'l', chr(196) . chr(189) => 'L',
			chr(196) . chr(190) => 'l', chr(196) . chr(191) => 'L',
			chr(197) . chr(128) => 'l', chr(197) . chr(129) => 'L',
			chr(197) . chr(130) => 'l', chr(197) . chr(131) => 'N',
			chr(197) . chr(132) => 'n', chr(197) . chr(133) => 'N',
			chr(197) . chr(134) => 'n', chr(197) . chr(135) => 'N',
			chr(197) . chr(136) => 'n', chr(197) . chr(137) => 'N',
			chr(197) . chr(138) => 'n', chr(197) . chr(139) => 'N',
			chr(197) . chr(140) => 'O', chr(197) . chr(141) => 'o',
			chr(197) . chr(142) => 'O', chr(197) . chr(143) => 'o',
			chr(197) . chr(144) => 'O', chr(197) . chr(145) => 'o',
			chr(197) . chr(146) => 'OE',chr(197) . chr(147) => 'oe',
			chr(197) . chr(148) => 'R', chr(197) . chr(149) => 'r',
			chr(197) . chr(150) => 'R', chr(197) . chr(151) => 'r',
			chr(197) . chr(152) => 'R', chr(197) . chr(153) => 'r',
			chr(197) . chr(154) => 'S', chr(197) . chr(155) => 's',
			chr(197) . chr(156) => 'S', chr(197) . chr(157) => 's',
			chr(197) . chr(158) => 'S', chr(197) . chr(159) => 's',
			chr(197) . chr(160) => 'S', chr(197) . chr(161) => 's',
			chr(197) . chr(162) => 'T', chr(197) . chr(163) => 't',
			chr(197) . chr(164) => 'T', chr(197) . chr(165) => 't',
			chr(197) . chr(166) => 'T', chr(197) . chr(167) => 't',
			chr(197) . chr(168) => 'U', chr(197) . chr(169) => 'u',
			chr(197) . chr(170) => 'U', chr(197) . chr(171) => 'u',
			chr(197) . chr(172) => 'U', chr(197) . chr(173) => 'u',
			chr(197) . chr(174) => 'U', chr(197) . chr(175) => 'u',
			chr(197) . chr(176) => 'U', chr(197) . chr(177) => 'u',
			chr(197) . chr(178) => 'U', chr(197) . chr(179) => 'u',
			chr(197) . chr(180) => 'W', chr(197) . chr(181) => 'w',
			chr(197) . chr(182) => 'Y', chr(197) . chr(183) => 'y',
			chr(197) . chr(184) => 'Y', chr(197) . chr(185) => 'Z',
			chr(197) . chr(186) => 'z', chr(197) . chr(187) => 'Z',
			chr(197) . chr(188) => 'z', chr(197) . chr(189) => 'Z',
			chr(197) . chr(190) => 'z', chr(197) . chr(191) => 's',
			// EURO sign
			chr(226) . chr(130) . chr(172) => 'E',
			// GBP (Pound) sign
			chr(194) . chr(163) => ''
		);
		// do the replacements
		$string = strtr($string, $chars);
	} else {
		// assume it is ISO-8859-1 if not UTF-8
		$chars = array(
			'in' =>
				chr(128) . chr(131) . chr(138) . chr(142) . chr(154) . chr(158) . chr(159) .
				chr(162) . chr(165) . chr(181) . chr(192) . chr(193) . chr(194) . chr(195) .
				chr(196) . chr(197) . chr(199) . chr(200) . chr(201) . chr(202) . chr(203) .
				chr(204) . chr(205) . chr(206) . chr(207) . chr(209) . chr(210) . chr(211) .
				chr(212) . chr(213) . chr(214) . chr(216) . chr(217) . chr(218) . chr(219) .
				chr(220) . chr(221) . chr(224) . chr(225) . chr(226) . chr(227) . chr(228) .
				chr(229) . chr(231) . chr(232) . chr(233) . chr(234) . chr(235) . chr(236) .
				chr(237) . chr(238) . chr(239) . chr(241) . chr(242) . chr(243) . chr(244) .
				chr(245) . chr(246) . chr(248) . chr(249) . chr(250) . chr(251) . chr(252) .
				chr(253) . chr(255),
			'out' =>
				'EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy',
			'inin' =>
				array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254)),
			'outout' =>
				array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th')
		);
		// replace single characters
		$string = strtr($string, $chars['in'], $chars['out']);
		// replace double characters
		$string = str_replace($chars['inin'], $chars['outout'], $string);
	}
	// return a clean string
	return $string;
}

function adesk_str_htmlspecialchars($str, $allowamp = false) {
	# A version of htmlspecialchars that doesn't replace the euro and trademark symbols
	# (among perhaps others).

	if (!$allowamp)
		$str = str_replace('&', '&amp;', $str);
	$str = str_replace('"', '&quot;', $str);
	$str = str_replace("'", '&#039;', $str);
	$str = str_replace('<', '&lt;', $str);
	$str = str_replace('>', '&gt;', $str);

	return $str;
}

function adesk_str_preview($string) {
	return adesk_str_htmlspecialchars(
		adesk_str_shorten(
			trim(
				preg_replace('/(\015\012)|(\015)|(\012)|(\[-PAGE-\])/', '', adesk_str_strip_tags($string))
			),
			500
		), true
	);
}

function adesk_str_printable($str) {
	# Return a version of $str with all non-printable characters stripped out.

	return preg_replace('/[\00\01\02\03\04\05\06\07\010\013\014\016\017\020\021\022\023\024\025\026\027\030\031\032\033\034\035\036\037]/', '', $str);
}

function adesk_str_strip_tag($str, $tag) {
	preg_match_all("|<{$tag}[^>]*>(.*)</{$tag}>|iUs", $str, $matches);
	if ( isset($matches[0]) and count($matches[0]) > 0 ) {
		$str = str_replace($matches[0], '', $str);
	}
	return $str;
}

function adesk_str_strip_tag_short($str, $tag) {
	preg_match_all("|(<{$tag}[^>]*>)|iUs", $str, $matches);
	if ( isset($matches[0]) and count($matches[0]) > 0 ) {
		$str = str_replace($matches[0], '', $str);
	}
	return $str;
}

function adesk_str_strip_malicious($str) {
	$str = adesk_str_strip_tag($str, "script");
	$str = adesk_str_strip_tag($str, "applet");
	$str = adesk_str_strip_tag($str, "embed");
	$str = adesk_str_strip_tag($str, "object");
	$str = adesk_str_strip_tag($str, "iframe");
	$str = adesk_str_strip_tag($str, "ilayer");
	$str = adesk_str_strip_tag($str, "layer");
	return $str;
}

function adesk_str_strip_tags($str) {
	if (is_array($str))
		return array_map("adesk_str_strip_tags", $str);

	$str = adesk_str_strip_malicious($str);
	$str = adesk_str_strip_tag($str, "title");
	$str = adesk_str_strip_tag($str, "style");

	// everything within <o:p> tags is removed with strip_tags()
	$str = preg_replace('/<(\/)?o:p>/i', '', $str);

	return trim(strip_tags($str));
}

function domain_exists( $email, $record = 'MX' ) {
	list( $user, $domain ) = explode( '@', $email );
	return checkdnsrr( $domain, $record );
}


function adesk_str_is_email($email) {
 
	
	return (bool)preg_match("/^[\+_a-z0-9-'&=]+(\.[\+_a-z0-9-']+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,17})$/i", $email);
	
 
	
	
}

function adesk_str_is_url($url) {
	return (bool)preg_match("/((http|https|ftp):\/\/|www)[a-z0-9\-\._]+\/?[a-z0-9_\.\-\?\+\/~=&#%;:\|,\[\]]*[a-z0-9\/=?&;%\[\]]{1}/si", $url);
}

function adesk_str_append_html($str1, $str2, $br = 1) {
	if ( $str2 != '' and $str1 != '' ) {
		// try to put it before last closing BODY tag
		$pos = strrpos(strtolower($str1), '</body>');
		if ( $pos !== false ) {
			$str1 =
				substr($str1, 0, $pos) .
				$str2 .
				substr($str1, $pos)
			;
		} else {
			if ( $br ) $str1 .= '<br />';
			$str1 .= $str2;
		}
	}
	return $str1;
}

function adesk_str_append_text($str1, $str2) {
	if ( $str2 != '' ) {
		if ( $str1 != '' ) $str1 .= "\n";
		$str1 .= $str2;
	}
	return $str1;
}

function adesk_str_prepend_html($str1, $str2, $br = 1) {
	if ( $str2 != '' and $str1 != '' ) {
		// try to put it after first opening BODY tag
		preg_match_all('/<body([^>]*)>/si', $str1, $matches);
		if ( isset($matches[0][0]) ) {
			$pos = strpos($str1, $matches[0][0]) + strlen($matches[0][0]);
			$str1 =
				substr($str1, 0, $pos) .
				$str2 .
				substr($str1, $pos);
		} else {
			$str1 = $str2 . ( $br ? '<br />' : '' ) . $str1;
		}
	}
	return $str1;
}

function adesk_str_prepend_text($str1, $str2) {
	if ( $str2 != '' ) {
		if ( $str1 != '' ) $str2 .= "\n";
		$str1 = $str2 . $str1;
	}
	return $str1;
}

function adesk_str_clean_word($str, $html = false) {
	$badWordChars = array(
		'\xe2\x80\x98', // single quote opening
		'\xe2\x80\x99', // single quote closing
		'\xe2\x80\x9c', // double quote opening
		'\xe2\x80\x9d', // double quote closing
		'\xe2\x80\x93', // long dash
		'\xe2\x80\x94', // long dash
		'\xe2\x80\xa6', // ellipsis
		'\xe2\x80\xa2'  // dot used for bullet points
	);
	$fixedWordChars = array(
		'&#8216;',
		'&#8217;',
		'&#8220;',
		'&#8221;',
		'&ndash;',
		'&mdash;',
		'&#8230;',
		'&#8226;',
	);
	$str = str_replace($badWordChars, $fixedWordChars, $str);
	if ( !$html ) {
		$fixedWordCharsTXT = array(
			'\'',
			'\'',
			'"',
			'"',
			'-',
			'-',
			'...',
			'*'
		);
		return str_replace($fixedWordChars, $fixedWordCharsTXT, $str);
	}
	return $str;
}


function adesk_str_escape_csv($string, $wrapper) {
    return preg_replace('/(\r\n|\r|\n)/', '\r\n', str_replace($wrapper, $wrapper . $wrapper, $string));
}

function adesk_str_noipv6($str) {
	$str = str_replace("::ffff:", "", $str);
	$str = str_replace("::1", "", $str);
	return $str;
}

function adesk_str_fixtinymce($content) {
	$plink   = str_replace('/', "\\/", adesk_site_plink());
	$content = preg_replace('/a href="' . $plink . '[^#]+#(\w+)"/i', 'a href="#\1"', $content);

	return $content;
}

function adesk_str_is_ip($str) {
	return preg_match('/^\d+\.\d+\.\d+\.\d+$/', $str);
}

function adesk_str_is_datetime($str) {
	return preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', (string)$str);
}

function adesk_str_is_date($str) {
	return preg_match('/^\d{4}-\d{2}-\d{2}$/', (string)$str);
}

function adesk_str_is_time($str) {
	return preg_match('/^\d{2}:\d{2}:\d{2}$/', (string)$str);
}

function adesk_str_align_center($str, $wordwrap = 60) {
	$str = wordwrap($str, $wordwrap, "\n", true);
	$arr = explode("\n", $str);
	foreach ( $arr as $k => $v ) {
		$arr[$k] = str_pad($v, $wordwrap, ' ', STR_PAD_BOTH);
	}
	return implode("\n", $arr);
}

function adesk_str_emaillist($str) {
	$r = array();
	$arr = explode(',', $str);
	foreach ( $arr as $email ) {
		$email = trim($email);
		if ( adesk_str_is_email($email) ) $r[] = $email;
	}
	return implode(",", $r);
}

function adesk_str_unserialize_preg_replace_cb($matches) {
	return "s:" . strlen($matches[2]) . ":\"$matches[2]\";";
}

function adesk_str_unserialize($str) {
	$out = preg_replace_callback( '!s:(\d+):"([^"]*?)";!s', 'adesk_str_unserialize_preg_replace_cb', $str);
	return @unserialize($out);
}

function adesk_str_preg_link($quote = '') {
	switch ( $quote ) {
		case '"':
			/* DOUBLE QUOTES */
			$pattern =
			$urlPatternDouble =
				'/((href=\"http|href=\"https|href=\"ftp):\/\/|www)' . // line 1
				'[a-z0-9\-\._]+\/?[a-z0-9_\.\-\?\+\/~=&#%!@;:\|,\[\]]*' . // line 2
				'[a-z0-9_\.\-\/=?&;%!@\[\]#]{1}/si' // line 3
			;
			break;
		case "'":
			/* SINGLE QUOTES */
			$pattern =
			$urlPatternSingle =
				"/((href=\'http|href=\'https|href=\'ftp):\/\/|www)" . // line 1
				"[a-z0-9\-\._]+\/?[a-z0-9_\.\-\?\+\/~=&#%!@;:\|,\[\]]*" . // line 2
				"[a-z0-9_\.\-\/=?&;%!@\[\]#]{1}/si" // line 3
			;
			break;

		case '':
		default:
			/* URL ONLY */
			$pattern =
			$urlPatternNone =
				"/((http|https|ftp):\/\/|www)" . // line 1
				"[a-z0-9\-\._]+\/?[a-z0-9_\.\-\?\+\/~=&@#!%;:\|,\[\]]*" . // line 2
				"[a-z0-9_\.\-\/=?&@;%!\[\]#]{1}/si" // line 3
			;

	}

	return $pattern;
}

?>