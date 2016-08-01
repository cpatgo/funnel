<?php

# How do zones work?  It's not enough that you pick your zone.  We need to compute
# where your MySQL server is in relation to

$tz_zone      = array();
$tz_zone_bdst = array();
$tz_zone_edst = array();

require_once dirname(__FILE__) . "/tz.init.php";

function tz_hours($offset) {
	return (int)abs(floor($offset));
}

function tz_minutes($offset, $hours) {
	return (int)(60 * (abs($offset) - $hours));
}

function tz_box() {
	# Return an array suitable for use in a dropdown via smarty

	global $tz_zone;

	$rval = array();
	foreach ($tz_zone as $zoneid => $offset) {
		$lhs = tz_hours($offset);
		$rhs = tz_minutes($offset, $lhs);
		$tmp = array(
			"zoneid"        => $zoneid,
			"offset"        => $offset,
			"offset_format" => ($offset > 0 ? "+" : "-") . sprintf("%02d%02d", $lhs, $rhs),
		);

		if ($tmp["offset_format"] == "-5000")
			$tmp["offset_format"] = "";

		$rval[] = $tmp;
	}

	return $rval;
}

function tz_php() {
	$diff = date("O");
	$sign = substr($diff, 0, 1);
	$hour = intval(_tz_skip_zeropad(substr($diff, 1, 2)));

	return ($sign == "-" ? -$hour : $hour);
}

function _tz_skip_zeropad($str) {
	if (strlen($str) > 1 && substr($str, 0, 1) == "0")
		return substr($str, 1, 1);
	else
		return $str;
}

function tz_mysql() {
	if (isset($_SESSION["_adesk_tz_mysql"]))
		return $_SESSION["_adesk_tz_mysql"];

	$diff = adesk_sql_select_one("SELECT TIMEDIFF(NOW(), UTC_TIMESTAMP())");
	$tmp  = explode(":", $diff);
	$diff = $tmp[0];
	$sign = "+";

	if (substr($diff, 0, 1) == "-") {
		$sign = "-";
		$diff = substr($diff, 1, 2);
	}

	$hour = intval(_tz_skip_zeropad($diff));

	$_SESSION["_adesk_tz_mysql"] = ($sign == "-" ? -$hour : $hour);
	return $_SESSION["_adesk_tz_mysql"];
}

function tz_offset($zoneid) {
	global $tz_zone;

	if (!isset($tz_zone[$zoneid]))
		return 0;

	$off_h = $tz_zone[$zoneid];
	$off_t = tz_mysql();

	# If MySQL (t) is west of the zone (h), then t <= h and we need a positive offset to reach h.
	# We do so by subtracting h - t; since h >= t, the result will always be positive.  If t is
	# east of h, then we need a negative offset to reach it.  In that case, h - t works again.
	# Since h < t in that case, h - t will always yield a negative.

	return $off_h - $off_t;
}

function tz_gmtoffset($zoneid) {
	global $tz_zone;

	$offset = $tz_zone[$zoneid];
	$lhs = tz_hours($offset);
	$rhs = tz_minutes($offset, $lhs);

	return ($offset > 0 ? "+" : "-") . sprintf("%02d%02d", $lhs, $rhs);
}

function tz_isdst($zoneid) {
	# Figure out if we're in daylight savings time or not.
	global $tz_zone;
	global $tz_zone_bdst;
	global $tz_zone_edst;

	if (!isset($tz_zone[$zoneid]) || !isset($tz_zone_bdst[$zoneid]) || !isset($tz_zone_edst[$zoneid]))
		return false;

	$bday = $tz_zone_bdst[$zoneid];
	$eday = $tz_zone_edst[$zoneid];

	$gd   = getdate();
	$dst  = 0;

	# If the day is later than bday, then DST has begun, so set dst = 1.
	# However, if the day is later than eday, then DST has begun AND ended, so set dst = 0.
	# If the day is neither later than bday nor eday, then clearly DST has not yet begun, and
	# dst's default (0) should be held.

	if ($gd["yday"] >= $bday)
		$dst = 1;

	if ($gd["yday"] >= $eday)
		$dst = 0;

	return $dst == 1;
}

function tz_checkdst($source = 'site') {
	if ($source == "site") {
		$ary   = $GLOBALS["site"];
		$table = "backend";
		$key   = "site";
		$id    = "1";
	} else {
		$ary   = $GLOBALS["admin"];
		$table = "user";
		$key   = "admin";
		$id    = $ary["id"];
	}

	$isdst = tz_isdst($ary["local_zoneid"]);

	if ($ary["local_dst"] != $isdst) {
		$up              = array();
		$up["local_dst"] = intval($isdst);

		# If this has changed, their offset has likely changed as well.
		$offset             = tz_offset($ary["local_zoneid"]);
		$up["t_offset_o"]   = ($offset >= 0 ? "+" : "-");
		$up["t_offset"]     = tz_hours($offset);
		$up["t_offset_min"] = tz_minutes($offset, $up["t_offset"]);

		adesk_sql_update("#$table", $up, "id = '$id'");
	}

	$GLOBALS[$key] = $ary;
}

?>
