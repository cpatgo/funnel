<?php

# Set this variable to whatever license we're using for maxmind.
if ( !isset($GLOBALS['maxmind_license']) ) $GLOBALS['maxmind_license'] = "";

# This is a really, really, rough approximation of the number of miles in a given degree.  It's 
# not meant to be realistic--just real-ish.  I generated it by figuring the distance between our 
# IP (38.104.242.98) and our mail server (74.86.0.34); we in Chicago, the server in Dallas.  The 
# distance as the crow flies is 12.8915494455 "degrees" (determined by adesk_maxmind_distance()).  Then 
# I took the flight distance from Chicago to Dallas, which is 802 miles, and divided the degrees 
# into the miles to figure out the miles per degree below.
$maxmind_degreemiles = 62.2112961201;

function adesk_maxmind_lookup($ip, $table = '#geoip') {
	$ip4 = ip2long($ip);

	$geoip = adesk_sql_select_row("SELECT *, (NOW() > `tstamp` + INTERVAL 3 DAY) AS `needupdate` FROM $table WHERE ip4 = '$ip'");

	if (!$geoip) {
		$rval  = adesk_maxmind_api($ip);
		$geoip = adesk_maxmind_insert($table, $rval);
		$geoip["needupdate"] = 0;
	} elseif ($geoip["needupdate"]) {
		$rval  = adesk_maxmind_api($ip);
		$geoip = adesk_maxmind_update($table, $rval, $geoip['id']);
		$geoip["needupdate"] = 0;
	}

	return $geoip;
}

function adesk_maxmind_insert($table, $data) {
	adesk_sql_insert($table, $data);
	$id = (int)adesk_sql_insert_id();
	return adesk_sql_select_row("SELECT * FROM $table WHERE id = '$id'");
}

function adesk_maxmind_update($table, $data, $id) {
	$id = (int)$id;
	adesk_sql_update($table, $data, "id = '$id'");
	return adesk_sql_select_row("SELECT * FROM $table WHERE id = '$id'");
}

function adesk_maxmind_api($ip) {
	if ( !$GLOBALS['maxmind_license'] ) return false;

	$url  = sprintf("http://geoip3.maxmind.com/b?l=%s&i=%s", $GLOBALS['maxmind_license'], $ip);
	$resp = adesk_http_get($url);

	if ($resp == "")
		return false;

	$expl = explode(",", $resp);

	if (count($expl) != 5)
		return false;

	# If you send a bad address to maxmind, you'll get the following latitude and longitude back 
	# (which profiles as US/TX/Dallas).
	if ($expl[3] == "32.782501" && $expl[4] == "-96.820702")
		return false;

	$ip = adesk_sql_escape($ip);
	return array(
		"=ip4"    => "INET_ATON('$ip')",
		"country" => $expl[0],
		"state"   => $expl[1],
		"city"    => $expl[2],
		"lat"     => $expl[3],
		"lon"     => $expl[4],
		"=tstamp" => "NOW()",
	);
}

function adesk_maxmind_distance($a, $b) {
	# Figure out the distance "as the crow flies" from point a to point b.  We do this by using 
	# the Pythagorean Theorem--essentially triangulation.  The theorem states that in a right 
	# triangle where the sides of the right-angle are a and b, we can calculate the length of c 
	# using the following equation: a^2 + b^2 = c^2.  We know a and b (latitude and longitude), 
	# so we simply follow through on the rest to determine the distance in degrees.
	$lat = $a["lat"] - $b["lat"];
	$lon = $a["lon"] - $b["lon"];

	$dist2 = pow($lat, 2) + pow($lon, 2);
	$dist  = sqrt($dist2);

	return $dist;
}

function adesk_maxmind_miles($a, $b) {
	global $maxmind_degreemiles;

	$dist = adesk_maxmind_distance($a, $b);
	$dist = $dist * $maxmind_degreemiles;

	return $dist;
}

?>
