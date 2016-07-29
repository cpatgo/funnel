<?php
# array.php

require_once dirname(__FILE__) . '/array_ini.php';

function adesk_array_has(&$ary) {
	$args = func_get_args();
	array_splice($args, 0, 1);

	foreach ($args as $arg) {
		if (!isset($ary[$arg]))
			return false;
	}

	return true;
}

function adesk_array_compact(&$ary, $sep) {
	$out = "";

	foreach ($ary as $key => $val) {
		$out .= "$key=$val" . $sep;
	}

	return $out;
}

function adesk_array_associate($ary, $key) {
	$rval = array();

	foreach ($ary as $item)
		$rval[$item[$key]] = $item;

	return $rval;
}

# Given a sequential array (0..N), comprised of associative arrays at each index, extract the associative
# index $key from each sequential index and append it to an array that will be returned.  So if you had:
#
#   array(
#       array('name' => 'one', 'val' => 1),
#       array('name' => 'two', 'val' => 2)
#   )
#
#   adesk_array_extract($ary, 'name') would return array('one', 'two'), while
#   adesk_array_extract($ary, 'val')  would return array(1, 2).

function adesk_array_extract(&$ary, $key) {
	$tmp = array();

	for ($i = 0; $i < count($ary); $i++)
		$tmp[] = $ary[$i][$key];

	return $tmp;
}

function adesk_array_order_asc(&$ary, $key, $key2 = '') {
	if ($key2 == '')
		$cb = create_function('$a, $b', "return strcmp(\$a['$key'], \$b['$key']);");
	else
		$cb = create_function('$a, $b', "\$r = strcmp(\$a['$key'], \$b['$key']); if (\$r == 0) return strcmp(\$a['$key2'], \$b['$key2']); return \$r;");
	usort($ary, $cb);
}

function adesk_array_order_desc(&$ary, $key, $key2 = '') {
	if ($key2 == '')
		$cb = create_function('$a, $b', "return strcmp(\$b['$key'], \$a['$key']);");
	else
		$cb = create_function('$a, $b', "\$r = strcmp(\$b['$key'], \$a['$key']); if (\$r == 0) return strcmp(\$b['$key2'], \$a['$key2']); return \$r;");
	usort($ary, $cb);
}

function adesk_array_limit(&$ary, $off, $count) {
	$len = count($ary);
	if ($len > $count) {
		if (($len - $count - $off) > 0)
			array_splice($ary, $off, $len - $count - $off);
		else
			array_splice($ary, $off);
	}
}

# This is a very lightweight CSV function.  I know we have a pear class for
# this, but I wanted something that could return a string rather than always
# writing to a file.

function adesk_array_csv(&$rows, $header = array(), $output = array()) {
	$out = "";

	$hlen = count($header);
	if ($hlen > 0) {
		$keys = array_keys($header);
		for ($i = 0; $i < $hlen; $i++) {
			$out .= sprintf('"%s"', str_replace('"', '""', $header[$keys[$i]]));
			if (($i+1) < $hlen)
				$out .= ',';
		}
		$out .= "\r\n";
	}

	$rlen = count($rows);
	if ($rlen > 0) {
		$olen = count($output);
		for ($i = 0; $i < $rlen; $i++) {
			if ($olen > 0)
				$keys =& $output;
			else
				$keys = array_keys($rows[$i]);

			$len = count($keys);
			for ($j = 0; $j < $len; $j++) {
				if (!is_array($rows[$i][$keys[$j]]))
					$out .= sprintf('"%s"', str_replace('"', '""', $rows[$i][$keys[$j]]));
				else
					$out .= '""';
				if (($j+1) < $len)
					$out .= ',';
			}
			$out .= "\r\n";
		}
	}

	return $out;
}

function adesk_array_parsecsv(&$ary, &$off, $str, $delim, $readlastline) {
	while (strpos($str, "\n", $off) !== false) {
		$ary[] = adesk_array_parsecsv_line($off, $str, $delim);
	}

	if ($off < strlen($str) && $readlastline) {
		# One more time...
		$ary[] = adesk_array_parsecsv_line($off, $str, $delim);
	}
}

function adesk_array_parsecsv_file(&$ary, $fp, $delim, $callback = null) {
	if (feof($fp))
		return;

	$prev = "";
	do {
		$off = 0;
		$str = $prev . fread($fp, 8192);
		$eof = feof($fp);
		adesk_array_parsecsv($ary, $off, $str, $delim, $eof);

		if ($callback !== null && function_exists($callback)) {
			$callback($ary, $fp, strlen($prev));
			$ary = null;
			$ary = array();
		}

		if (!$eof)
			$prev = substr($str, $off);

		$str = null;
	} while (!feof($fp));
}

function adesk_array_parsecsv_line(&$off, $str, $delim) {
	$enclose   = "";
	$word      = "";
	$skipdelim = false;
	$line      = array();
	$enclosing = false;

	for ($i = $off, $len = strlen($str); $i < $len;) {
		$c = $str[$i++];

		switch ($c) {
			case "\\":
				# Short-circuit: if this is a backslash, immediately copy in the next
				# character to $word.  Beware if the backslash character is the last in $str.
				if ($i < $len)
					$c = $str[$i++];
				else
					$c = "";

				# But don't copy it if it's a CR or LF.  Ignore the CR, and end the line if it's LF.
				if ($c == "\r")
					break;

				if ($c == "\n") {
					$line[] = $word;
					$off    = $i;
					return $line;
				}

				$word .= $c;
				break;

			case "\r":
				# Throw away carriage returns if the next character is a line feed.
				if (isset($str[$i]) && $str[$i] == "\n")
					continue;

				# Otherwise treat it as a line feed.
				$line[] = $word;
				$off = $i;
				return $line;

			case "\n":
				$line[] = $word;
				$off    = $i;
				return $line;

			case $delim:
				if (!$skipdelim) {
					$line[] = $word;
					$word   = "";

					# Skip whitespace.
					while ($i < $len && ($str[$i] == " " || ($str[$i] == "\t" && $delim != "\t")))
						$i++;
				} else {
					$word .= $c;
				}

				break;

			case "'":
			case '"':
				if ($skipdelim && $enclose == $c) {
					if ($i < $len && $str[$i] == $enclose) {
						# Two quotes together: treat this like an escape.
						$word .= $c;
						$i++;
					} else {
						$enclose   = "";
						$skipdelim = false;
						$enclosing = false;

						# Skip whitespace.
						while ($i < $len && ($str[$i] == " " || ($str[$i] == "\t" && $delim != "\t")))
							$i++;
					}
				} elseif (!$skipdelim && (!$enclosing || $i == ($off + 1))) {
					$enclosing = true;
					$enclose   = $c;
					$skipdelim = true;
				} else {
					$word .= $c;
				}

				break;

			default:
				$word .= $c;
				break;
		}
	}

	$line[] = $word;
	$off    = $i;

	$word    = null;
	$i       = null;
	$enclose = null;
	return $line;
}

function adesk_array_unescape_gpc($arr) {
	if ( !is_array($arr) ) return $arr;
	foreach ( $arr as $k => $v ) {
		if ( is_object($v) ) $v = get_object_vars($v);
		if ( is_array($v) ) {
			$arr[$k] = adesk_array_unescape_gpc($v);
		} else {
			$arr[$k] = adesk_str_unescape_gpc($v);
		}
	}
	return $arr;
}

function adesk_array_first(&$ary) {
	# Return the "first" value of an array, if that's all you want.  Should work even in cases
	# where current() returns false.

	$keys = array_keys($ary);

	if (count($keys) > 0)
		return $ary[$keys[0]];
	else
		return false;
}

function adesk_array_keys_remove($arr, $remove = array()) {
	if (!$arr)
		return;

	foreach ( $arr as $k => $v ) {
		if ( in_array($k, $remove) ) {
			unset($arr[$k]);
		}
	}
	return $arr;
}

function adesk_array_keys_include($ary, $include = array()) {
	if (!$ary)
		return;

	foreach ($ary as $key => $val) {
		if (!in_array($key, $include))
			unset($ary[$key]);
	}

	return $ary;
}

function adesk_array_unique($arr = array(), $field = 'id') {
	$r = array();
	$tmp = array();
	foreach ( $arr as $v ) {
		if ( isset($v[$field]) ) {
			if ( !isset($tmp[$v[$field]]) ) {
				$tmp[$v[$field]] = $v[$field];
				$r[] = $v;
			}
		}
	}
	return $r;
}

function adesk_array_groupby($arr = array(), $field = 'id') {
	$r = array();
	foreach ( $arr as $v ) {
		if ( isset($v[$field]) ) {
			$r[$v[$field]][] = $v;
		}
	}
	return $r;
}

function adesk_array_diff($arr = array(), $field = 'id', $values = array()) {
	$r = array();
	foreach ( $arr as $v ) {
		if ( isset($v[$field]) ) {
			if ( !in_array($v[$field], $values) ) {
				$r[] = $v;
			}
		}
	}
	return $r;
}

function adesk_array_merge() {
	$args = func_get_args();
	$rval = array();

	foreach ($args as $arg) {
		$keys = array_keys($arg);
		foreach ($keys as $key) {
			$rval[$key] = $arg[$key];
		}
	}

	return $rval;
}

function adesk_array_print_table($arr, $props = 'width="100%"', $keyprops = 'width="100" valign="top" align="left"', $valprops = '') {
	echo "<table $props>\n";
	foreach ( $arr as $k => $v ) {
		echo "\t<tr>\n";
		echo "\t\t<th $keyprops>$k</th>\n";
		echo "\t\t<td $valprops>$v</td>\n";
		echo "\t</tr>\n";
	}
	echo "</table>\n";
}

function adesk_array_normalize($arr = array()) {
	$sum = array_sum($arr);
	foreach ( $arr as $k => $v ) {
		$arr[$k] = $sum ? (float)$v / $sum : 0;
	}
	return $arr;
}

function adesk_array_sort_strlen($arr) {
	$r = array();
	$map = array_map('strlen', $arr);
	$sum = array_sum($map);
	for ( $i = 0; $i <= $sum; $i++ ) {
		foreach ( $arr as $k => $v ) {
			if ( strlen($v) == $i ) $r[$k] = $v;
		}
	}
	return $r;
}

function adesk_array_sort_strlen_r($arr) {
	$r = array();
	$map = array_map('strlen', $arr);
	$sum = array_sum($map);
	for ( $i = $sum; $i > 0; $i-- ) {
		foreach ( $arr as $k => $v ) {
			if ( strlen($v) == $i ) $r[$k] = $v;
		}
	}
	return $r;
}

?>
