<?php
// utf.php

// These functions convert strings from their original charset to entity
// references (which depend on UTF-8) and back.

function adesk_utf_conv($from, $to, $str) {

    // If you don't append "//IGNORE" to the output charset, iconv will
    // just...stop...on any character that it can't translate from the
    // input character set.  Your other option is "//TRANSLIT", which
    // ostensibly tells it to try to find the next best fit, but in mine
    // and Milos' experience //IGNORE is the most consistent and
    // reliable method.

    $r = @iconv($from, $to . "//IGNORE", $str);

    if ( defined('ICONV_IMPL') and ICONV_IMPL == 'glibc' ) {
    	$badversion = ( defined('ICONV_VERSION') and ICONV_VERSION == '2.5' );
		if ( $badversion and strlen($r) != strlen($str) ) {
			# Try mb_convert_encoding, if it's there.
			if (function_exists("mb_convert_encoding")) {
				$r = @mb_convert_encoding($str, $to, $from);
				return $r;
			}
		}
    }

	# Let's hope for the best...
    return $r;
}

function adesk_utf_deepconv($from, $to, $mixed) {
	if (is_array($mixed)) {
		foreach ($mixed as $k => $v)
			$mixed[$k] = adesk_utf_deepconv($from, $to, $v);

		return $mixed;
	} elseif (is_string($mixed)) {
		return adesk_utf_conv($from, $to, $mixed);
	} elseif (is_object($mixed)) {
		$vars = get_class_vars(get_class($mixed));
		foreach ($vars as $var => $val) {
			if (is_string($var) && isset($mixed->$var) && $mixed->$var)
				$mixed->$var = adesk_utf_deepconv($from, $to, $mixed->$var);
		}
	}

	return $mixed;
}

// Given the number from a numeric entity (&#nnnn;), convert it to
// its UTF-8 representation and then convert that to whatever
// character set we're using.

function adesk_utf_unescape_code($code) {
    $str = "";

    if ($code < 128)
        $str = chr($code);

    elseif ($code < 1920)
        $str =
            chr(192 | (($code >> 6) & 63)) .
            chr(128 |  ($code       & 63));

    elseif ($code < 63488)
        $str =
            chr(224 |  ($code >> 12)) .
            chr(128 | (($code >> 6) & 63)) .
            chr(128 |  ($code       & 63));

    elseif ($code < 1048576)
        $str =
            chr(240 |  ($code >> 18)) .
            chr(128 | (($code >> 12) & 63)) .
            chr(128 | (($code >> 6)  & 63)) .
            chr(128 |  ($code        & 63));

    return adesk_utf_conv("UTF-8", strtoupper(_i18n("utf-8")), $str);
}

function adesk_utf_unescape($str) {
    return preg_replace('/&#([0-9]+);/e', 'adesk_utf_unescape_code(\\1)', $str);
}

// Given a character, convert it to its UTF-8 representation, and
// then break it down to the numeric html entity that corresponds to
// it.  The return is not the number itself, but rather the full
// entity form (unless it's plain 7-bit ASCII, in which case the
// character is returned unchanged).

function adesk_utf_escape_char($utf, $len) {
    switch ($len) {
    default:
    case 1:
        return $utf;

    case 2:
        $a = ord($utf[0]) & 31;
        $b = ord($utf[1]) & 63;
        return sprintf("&#%d;", ($a << 6) | $b);

    case 3:
        $a = ord($utf[0]) & 15;
        $b = ord($utf[1]) & 63;
        $c = ord($utf[2]) & 63;
        return sprintf("&#%d;", ($a << 12) | ($b << 6) | $c);

    case 4:
        $a = ord($utf[0]) & 7;
        $b = ord($utf[1]) & 63;
        $c = ord($utf[2]) & 63;
        $d = ord($utf[3]) & 63;
        return sprintf("&#%d;", ($a << 18) | ($b << 12) | ($c << 6) | $d);
    }

    return "";
}

function adesk_utf_codelen($byte) {
    if (($byte & 240) == 240)               // 11110bbb
        return 4;

    if (($byte & 224) == 224)               // 1110bbbb
        return 3;

    if (($byte & 192) == 192)               // 110bbbbb
        return 2;

    return 1;                               // must be ASCII
}


function adesk_utf_escape($str, $force = false) {
    $cnv = adesk_utf_conv(strtoupper(_i18n("utf-8")), "UTF-8", $str);

    if ($cnv == $str && !$force)
        return $str;

    $len = strlen($cnv);
    $out = "";
    $off = 1;

    for ($i = 0; $i < $len; $i += $off) {
        $off  = adesk_utf_codelen(ord($cnv[$i]));
        $out .= adesk_utf_escape_char(substr($cnv, $i, $off), $off);
    }

    return $out;
}

function adesk_utf_recode($str, $cset) {
	if ( $str == "" ) return $str;
	if ($cset == "")
		$cset = "utf-8";
	if (function_exists("iconv")) {
		return (string)@iconv(strtoupper($cset), strtoupper(_i18n("utf-8"))."//IGNORE", $str);
	}
	return $str;
}

/**
 * This function checks if the string appears to be utf8 or not
 *
 * @param string $str any string
 * @return boolean is string in utf8 or not
 */
function adesk_utf_check($str) {
	$len = strlen($str);
	for ( $i = 0; $i < $len; $i++ ) {
		if ( ord($str[$i]) < 0x80 ) {
			// do nothing if 0bbbbbbb
			continue;
		} elseif ( ( ord($str[$i]) & 0xE0 ) == 0xC0 ) {
			// 110bbbbb
			$n = 1;
		} elseif ( ( ord($str[$i]) & 0xF0 ) == 0xE0 ) {
			// 1110bbbb
			$n = 2;
		} elseif ( ( ord($str[$i]) & 0xF8 ) == 0xF0 ) {
			// 11110bbb
			$n = 3;
		} elseif ( ( ord($str[$i]) & 0xFC ) == 0xF8 ) {
			// 111110bb
			$n = 4;
		} elseif ( ( ord($str[$i]) & 0xFE ) == 0xFC ) {
			// 1111110b
			$n = 5;
		} else {
			// it does not match any model
			return false;
		}
		// loop through found bytes offset
		for ( $j = 0; $j < $n; $j++ ) {
			if ( ( ++$i == $len ) || ( ( ord($str[$i]) & 0xC0 ) != 0x80 ) ) {
				return false;
			}
		}
	}
	// it is utf8 string, nothing bad found
	return true;
}


function adesk_utf_uri_encode($str, $length = 0) {
	// define needed vars
	$unicode = '';
	$values = array();
	$octets = 1;
	// loop through string
	$len = strlen($str);
	for ( $i = 0; $i < $len; $i++ ) {
		$value = ord($str[$i]);
		if ( $value < 128 ) {
			// if regular char
			if ( $length and ( strlen($unicode) + 1 > $length ) ) {
				break;
			}
			$unicode .= chr($value);
		} else {
			// where is it?
			if ( count($values) == 0 ) $octets = ( $value < 224 ? 2 : 3 );
			$values[] = $value;
			if ( $length and ( strlen($unicode) + $octets * 3 > $length ) ) {
				break;
			}
			// when found all parts, combine them
			if ( count($values) == $octets ) {
				$unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]);
				if ( $octets == 3 ) $unicode .= '%' . dechex($values[2]);
				$values = array();
				$octets = 1;
			}
		}
	}
	return $unicode;
}


function truncate_utf8($string, $length, $wordSafe = false, $dots = '...') {
	if ( $length > strlen($string) ) {
		return $string;
	}
	if ( $wordSafe ) {
		$lastSpace = strrpos($string, ' ');
		if ( $lastSpace !== false ) $length = $lastSpace;
	}
	if ( ord($string[$length]) < 0x80 || ord($string[$length]) >= 0xC0 ) {
		return substr($string, 0, $length) . $dots;
	}
	while ( --$length >= 0 && ord($string[$length]) >= 0x80 && ord($string[$length]) < 0xC0 ) {}
	return substr($string, 0, $length) . $dots;
}

?>
