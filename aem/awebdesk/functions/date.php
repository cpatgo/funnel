<?php

require_once dirname(__FILE__) . '/tz.php';

if ( !defined('adesk_DATE_MINUTE') ) define('adesk_DATE_MINUTE', 60);
if ( !defined('adesk_DATE_HOUR'  ) ) define('adesk_DATE_HOUR',   60 * 60);
if ( !defined('adesk_DATE_DAY'   ) ) define('adesk_DATE_DAY',    60 * 60 * 24);
if ( !defined('adesk_DATE_WEEK'  ) ) define('adesk_DATE_WEEK',   60 * 60 * 24 * 7);
if ( !defined('adesk_DATE_MONTH' ) ) define('adesk_DATE_MONTH',  round(60 * 60 * 60 * (365.0 / 12.0), 0));
if ( !defined('adesk_DATE_YEAR'  ) ) define('adesk_DATE_YEAR',   60 * 60 * 24 * 365);

function adesk_date_offset() {
	$ary = array(
		"t_offset" => 0,
		"t_offset_min" => 0,
		"t_offset_o" => "+"
	);

	if (isset($GLOBALS["admin"])) {
		$ary["t_offset"] = intval($GLOBALS["admin"]["t_offset"]);
		$ary["t_offset_min"] = isset($GLOBALS["admin"]["t_offset_min"]) ? intval($GLOBALS["admin"]["t_offset_min"]) : 0;
		$ary["t_offset_o"] = $GLOBALS["admin"]["t_offset_o"];
	}

	if ($ary["t_offset"] == 0) {
		require_once awebdesk_functions("site.php");
		$site = adesk_site_get();
		$ary["t_offset"] = $site["t_offset"];
		$ary["t_offset_min"] = isset($site["t_offset_min"]) ? intval($site["t_offset_min"]) : 0;
		$ary["t_offset_o"] = $site["t_offset_o"];
	}

	return $ary;
}

function adesk_date_offset_hour() {
	if (!isset($GLOBALS["site"]))
		return 0;

	if (isset($GLOBALS["site"]) && !isset($GLOBALS["site"]["t_offset"])) {
		$GLOBALS["site"]["t_offset"]     = 0;
		$GLOBALS["site"]["t_offset_min"] = 0;
		$GLOBALS["site"]["t_offset_o"]   = "+";
	}

	if (isset($GLOBALS["admin"]["t_offset"]) && ($GLOBALS["admin"]["t_offset"] != $GLOBALS["site"]["t_offset"] || $GLOBALS["admin"]["t_offset_o"] != $GLOBALS["site"]["t_offset_o"] || (isset($GLOBALS["admin"]["t_offset_min"]) && $GLOBALS["admin"]["t_offset_min"] != $GLOBALS["site"]["t_offset_min"]))) {
		$t_offset     = intval($GLOBALS["admin"]["t_offset"]);
		$t_offset_min = isset($GLOBALS["admin"]["t_offset_min"]) ? intval($GLOBALS["admin"]["t_offset_min"]) : 0;
		$t_offset_o   = $GLOBALS["admin"]["t_offset_o"];
	} elseif (isset($GLOBALS["site"]["t_offset"])) {
		$t_offset     = intval($GLOBALS["site"]["t_offset"]);
		$t_offset_min = isset($GLOBALS["site"]["t_offset_min"]) ? intval($GLOBALS["site"]["t_offset_min"]) : 0;
		$t_offset_o   = $GLOBALS["site"]["t_offset_o"];
	} else {
		# This is very unlikely to happen, unless neither $admin nor $site have been
		# created in the global scope.
		$t_offset     = 0;
		$t_offset_min = 0;
		$t_offset_o   = "+";
	}

	if ($t_offset_o == "-")
		$t_offset = -$t_offset;

	# Daylight savings time?  Add one hour.
	if (isset($GLOBALS["site"]["local_dst"])) {
		$site  = $GLOBALS["site"];
		$admin = $GLOBALS["admin"];
		$dst   = $site["local_dst"];

		if (isset($admin["local_dst"]) && $admin["local_dst"] != $site["local_dst"])
			$dst = $admin["local_dst"];

		if ($dst)
			$t_offset += 1;
	}

	$bias = 0.0;

	switch ($t_offset_min) {
		case 15:
			$bias = 0.25;
			break;
		case 30:
			$bias = 0.5;
			break;
		case 45:
			$bias = 0.75;
			break;
		default:
			break;
	}

	if ($t_offset > 0)
		return $t_offset + $bias;
	else
		return $t_offset - $bias;
}

function adesk_getCurrentDate() {
    if ( !defined('adesk_CURRENTDATE') ) {
		if (!defined('adesk_CURRENTDATETIME')) {
			require_once(dirname(__FILE__) . '/sql.php');
			$off = adesk_date_offset_hour();
			$hrs = tz_hours($off);
			$min = tz_minutes($off, $hrs);

			if ($off < 0) {
				$min = -$min;
				$hrs = -$hrs;
			}

			if ($min != 0)
				$sql = adesk_sql_query("SELECT NOW() + INTERVAL $hrs HOUR + INTERVAL $min MINUTE");
			else
				$sql = adesk_sql_query("SELECT NOW() + INTERVAL $hrs HOUR");

			list($now)   = mysql_fetch_row($sql);
		} else $now = adesk_CURRENTDATETIME;
		list($date) = explode(" ", $now);
        define('adesk_CURRENTDATE', $date);
    }
    return adesk_CURRENTDATE;
}

function adesk_getCurrentTime() {
    if ( !defined('adesk_CURRENTTIME') ) {
		if (!defined('adesk_CURRENTDATETIME')) {
			require_once(dirname(__FILE__) . '/sql.php');
			$off = adesk_date_offset_hour();
			$hrs = tz_hours($off);
			$min = tz_minutes($off, $hrs);

			if ($off < 0) {
				$min = -$min;
				$hrs = -$hrs;
			}

			if ($min != 0)
				$sql = adesk_sql_query("SELECT NOW() + INTERVAL $hrs HOUR + INTERVAL $min MINUTE");
			else
				$sql = adesk_sql_query("SELECT NOW() + INTERVAL $hrs HOUR");

			list($now)   = mysql_fetch_row($sql);
		} else $now = adesk_CURRENTDATETIME;
		list(,$time) = explode(" ", $now);
        define('adesk_CURRENTTIME', $time);
    }
    return adesk_CURRENTTIME;
}

function adesk_getCurrentDateTime() {
	if (!defined('adesk_CURRENTDATETIME')) {
		require_once(dirname(__FILE__) . '/sql.php');
		$off = adesk_date_offset_hour();
		$hrs = tz_hours($off);
		$min = tz_minutes($off, $hrs);

		if ($off < 0) {
			$min = -$min;
			$hrs = -$hrs;
		}

		if ($min != 0)
			$sql = adesk_sql_query("SELECT NOW() + INTERVAL $hrs HOUR + INTERVAL $min MINUTE");
		else
			$sql = adesk_sql_query("SELECT NOW() + INTERVAL $hrs HOUR");

		list($now) = mysql_fetch_row($sql);
		define('adesk_CURRENTDATETIME', $now);
		list($date,$time) = explode(" ", $now);
	    if ( !defined('adesk_CURRENTDATE') ) define('adesk_CURRENTDATE', $date);
	    if ( !defined('adesk_CURRENTTIME') ) define('adesk_CURRENTTIME', $time);
	}
	return adesk_CURRENTDATETIME;
}

function adesk_date_sql_timediff() {
    if (!isset($_SESSION['awebdesk_sql_timediff'])) {
		require_once(dirname(__FILE__) . '/sql.php');
        $sqltime = adesk_sql_select_one("UNIX_TIMESTAMP()");
        $_SESSION['awebdesk_sql_timediff'] = time() - $sqltime;
    }

    return $_SESSION['awebdesk_sql_timediff'];
}

function adesk_date_sql_time() {
    return time() - adesk_date_sql_timediff();
}

# If $date is a date string, return it; otherwise, return blank.

function adesk_date_dcheck($date) {
	$date = trim($date);

	if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date))
		return $date;

	return "";
}

function adesk_date_tcheck($time) {
	$time = trim($time);

	if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $time))
		return $time;

	return "";
}

function adesk_date_dtcheck($dtime) {
	$dtime = trim($dtime);
	$parts = explode(" ", $dtime, 2);

	if (count($parts) != 2)
		return "";

	$date = adesk_date_dcheck($parts[0]);
	if ($date == "")
		return "";

	$time = adesk_date_tcheck($parts[1]);
	if ($time == "")
		return "";

	return $date . " " . $time;
}

function adesk_date_parse($data, $offset = 0) {
    if ( is_null($data) or $data == '' ) return $data;
    if ( $data == '' ) return $data;
	$data = trim($data);
    if ( preg_match('/^\d{14}$/', $data, $matches) ) {
        $year = substr($data, 0, 4);
        $month = substr($data, 4, 2);
        $day = substr($data, 6, 2);
        $hour = substr($data, 8, 2);
        $minute = substr($data, 10, 2);
        $second = substr($data, 12, 2);
        // if regular date/time field
    } elseif (preg_match('/^\d{4}[-\/]\d{2}[-\/]\d{2}( \d{2}:\d{2}(:\d{2})?)?$/', $data)) {
        // explode the string
        $datetime = explode(' ', $data);
        // time portion
        if ( !isset($datetime[1]) ) {
            // no time portion
            $hour = $minute = $second = 0;
        } else {
            // has time portion
            $ary = explode(':', $datetime[1]);
            $hour = $ary[0];
            $minute = $ary[1];

            if (count($ary) > 2)
                $second = $ary[2];
            else
                $second = 0;
        }
        // date portion
        if ( count(explode(':', $datetime[0])) == 3 ) {
            list($hour, $minute, $second) = explode(':', $datetime[0]);
            $year = date('Y');
            $month = date('m');
            $day = date('d');
        } else {
            if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $datetime[0]))
                list($day, $month, $year) = explode('/', $datetime[0]);
			elseif (preg_match('/^\d{4}\/\d{2}\/\d{2}$/', $datetime[0]))
				list($year, $month, $day) = explode('/', $datetime[0]);
			else
                list($year, $month, $day) = explode('-', $datetime[0]);
        }
    } else {
        return $data;
    }
    // support for offsets smaller than (-)1 that should be applied to minutes
    if ( $offset != 0 and abs($offset) < 1 ) {
    	$minute += $offset * 60;
    	$offset  = 0;
    }
    $tstamp = mktime((int)$hour + $offset, (int)$minute, (int)$second, (int)$month, (int)$day, (int)$year);
    return $tstamp;
}

function adesk_date_format($data, $format = '', $offset = 0) {
    if ( $format == '' ) {
   		// figure out the format
    	if ( isset($GLOBALS['site']['datetimeformat']) ) {
		    if ( preg_match('/^\d{4}-\d{2}-\d{2}$/', $data) ) {
		   		// date format
    			$format = $GLOBALS['site']['dateformat'];
		    } elseif ( preg_match('/^\d{2}:\d{2}:\d{2}$/', $data) ) {
		   		// time format
    			$format = $GLOBALS['site']['timeformat'];
		    } else {
		   		// datetime format
    			$format = $GLOBALS['site']['datetimeformat'];
		    }
    	} elseif ( isset($GLOBALS['site']['dfltdateformat']) ) {
    		$format = $GLOBALS['site']['dfltdateformat'];
    	} else {
    		$format = '%Y-%m-%d %H:%M:%S';
    	}
    }
    $format = _i18n($format);
	if (is_null($data) or strpos($data, "0000-00-00") === 0)
		return "-";

    $tstamp = adesk_date_parse($data, $offset);
    // if timestamp field
    if ( $tstamp == -1 ) return '-'; // $tstamp = 0;

    # If it's not numeric, then this isn't a format we recognize via adesk_date_parse(); so we'll return it unchanged.
    if (is_numeric($tstamp))
        return strftime($format, $tstamp);
    else
        return $tstamp;
}


// APPLIES OFFSET, BUT STAYS IN SQL FORMAT
function adesk_date_timeoffset($date, $offset = 0) {
	return adesk_date_format($date, '%Y-%m-%d %H:%M:%S', $offset);
}

function adesk_date_today() {
    $t  = time();
    $a  = getdate($t);
    $t -= ($a['hours'] * adesk_DATE_HOUR) - ($a['minutes'] * adesk_DATE_MINUTE) - $a['seconds'];
    unset($a);
    return $t;
}

function adesk_date_tstamp(&$ary) {
    return mktime(
        $ary['hours'],
        $ary['minutes'],
        $ary['seconds'],
        $ary['mon'],
        $ary['mday'],
        $ary['year']
    );
}

function adesk_date_array($time) {
    return getdate($time);
}

function adesk_date_parse_ymd($str) {
    if (!preg_match('/\d{4}-\d{2}-\d{2}/', $str))
        return 0;

    $ary = explode("-", $str);
    $now = adesk_date_array(time());
    $now['mon'] = $ary[1];
    $now['mday'] = $ary[2];
    $now['year'] = $ary[0];

    return adesk_date_tstamp($now);
}

function adesk_date_month_first($time) {
    $ary = adesk_date_array($time);
    $ary['mday'] = 1;
    $time = adesk_date_tstamp($ary);
    unset($ary);
    return $time;
}

function adesk_date_month_end($time) {
    $ary = adesk_date_array($time);
    $ary['mday'] = 1;
    $ary['mon']++;

    if ($ary['mon'] > 12) {
        $ary['mon'] = 1;
        $ary['year']++;
    }

    $time = adesk_date_tstamp($ary);
    unset($ary);

    return $time - adesk_DATE_DAY;
}

function adesk_date_month_next($time) {
    $ary = adesk_date_array($time);
    $ary['mon']++;

    if ($ary['mon'] > 12) {
        $ary['mon'] = 1;
        $ary['year']++;
    }

    $time = adesk_date_tstamp($ary);
    unset($ary);
    return $time;
}

function adesk_date_month_days($time) {
    $time = adesk_date_month_end($time);
    $ary  = adesk_date_array($time);
    $days = $ary['mday'];
    unset($ary);
    return $days;
}

function adesk_date_timespan($start, $end) {
    return adesk_date_timespan_array($end - $start);
}

// calculate a difference between two datetimes, return array
function adesk_date_timespan_array($span) {
	$span = abs((int)$span);
    // YEAR
    $return['year'] = 0;
    if ( $span > 31536000 ) {
        $return['year'] = intval(intval($span) / 31536000);
        $span = intval( intval($span) - ($return['year'] * 31536000) );
    }
    // MONTH
    $return['month'] = 0;
    if ( $span > 2592000 ) {
        $return['month'] = intval(intval($span) / 2592000);
        $span = intval( intval($span) - ($return['month'] * 2592000) );
    }
    // WEEK
    $return['week'] = 0;
    if ( $span > 604800 ) {
        $return['week'] = intval(intval($span) / 604800);
        $span = intval( intval($span) - ($return['week'] * 604800) );
    }
    // DAY
    $return['day'] = 0;
    if ( $span > 86400 ) {
        $return['day'] = intval(intval($span) / 86400);
        $span = intval( intval($span) - ($return['day'] * 86400) );
    }
    // HOUR
    $return['hour'] = 0;
    if ( $span > 3600 and $return['week'] == 0 ) {
        $return['hour'] = intval(intval($span) / 3600);
        $span = intval( intval($span) - ($return['hour'] * 3600) );
    }
    // MINUTE
    $return['minute'] = 0;
    if ( $span > 60 and $return['week'] == 0 ) {
        $return['minute'] = intval(intval($span) / 60);
        $span = intval( intval($span) - ($return['minute'] * 60) );
    }
    // SECOND
    $return['second'] = 0;
    if($span > 0) {
        $return['second'] = $span;
    }
    return $return;
}

function adesk_date_duration($from, $until, $type = null, $need_seconds = false) {
    return adesk_date_duration_span(adesk_date_timespan($from, $until), $type, $need_seconds);
}

// convert duration timestamp to human readable format
function adesk_date_duration_span($timeSpan, $type = null, $need_seconds = false) {
    if ($timeSpan == 0)
        return "0s";

    if (!is_array($timeSpan))
        $timeSpan = adesk_date_timespan_array($timeSpan);
    $val = '';
    if ( $type == 'day' ) {
        // convert difference to days
        if ( $timeSpan['year'] != 0 ) $timeSpan['day'] += $timeSpan['year'] * 365;
        if ( $timeSpan['month'] != 0 ) $timeSpan['day'] += $timeSpan['month'] * 30;
        if ( $timeSpan['week'] != 0 ) $timeSpan['day'] += $timeSpan['week'] * 7;
        //grabbing the days instead of weeks and everything...
        $val = $timeSpan['day'];
    } else {
        // we don't need seconds
        if (!$need_seconds) {
            if ($timeSpan['year'] > 0 || $timeSpan['month'] > 0 || $timeSpan['week'] > 0 || $timeSpan['day'] > 0 || $timeSpan['hour'] > 0 || $timeSpan['minute'] > 0)
                unset($timeSpan['second']);
        } else {
            if ($timeSpan['day'] > 0 || $timeSpan['week'] > 0 || $timeSpan['month'] > 0 || $timeSpan['year'] > 0)
                unset($timeSpan['second']);
        }

        foreach ( $timeSpan as $k => $v ) {
            if ( $v > 0 ) {
                if ($k == 'month')
                    $val .= $v . 'mo ';
                else
                    $val .= $v . substr($k, 0, 1) . ' ';
            }
        }
    }
    // and return it
    return $val;
}

function adesk_date_duration_parse($str) {
    $units = explode(" ", $str);
    $time  = 0;
    $seen_days = false;

    foreach ($units as $unit) {
        if (preg_match('/^(\d+)([ymwdhs]o?)$/', $unit, $mat)) {
            $u = intval($mat[1]);
            switch ($mat[2]) {
                case 's':
                    $time += $u;
                    break;
                case 'm':
                    $time += $u * adesk_DATE_MINUTE;
                    break;
                case 'mo':       // months -- kind of a hack
                    $time += $u * adesk_DATE_MONTH;
                    break;
                case 'h':
                    $time += $u * adesk_DATE_HOUR;
                    break;
                case 'd':
                    $time += $u * adesk_DATE_DAY;
                    break;
                case 'w':
                    $time += $u * adesk_DATE_WEEK;
                    break;
                case 'y':
                    $time += $u * adesk_DATE_YEAR;
                    break;
                default:
                    break;
            }
        }
    }

    return $time;
}

function adesk_date_reformat($tstamp, $format) {
	# Using a strftime format, re-format $tstamp.

	$time = strtotime($tstamp);

	# We couldn't parse it; return what was given.
	if ($time === false || $time === -1)
		return $tstamp;

	return strftime($format, $time);
}

function adesk_date_dayofweek($d, $short = false) {
	$format = ( $short ? '%a' : '%A' );
	return strftime($format, 86400 * ($d + 4));
}

function adesk_date_sqldiff($date1, $date2) {
	return adesk_date_parse($date1) - adesk_date_parse($date2);
}

function adesk_date_month_datein_forward($monthstartdate, $today) {
	list($year, $month, $day) = explode('-', $monthstartdate);
	if ( !(int)$year or (int)$year > date('Y') ) {
		require_once(dirname(__FILE__) . '/sql.php');
		return array('from' => adesk_sql_select_one("SELECT DATE(SUBDATE(NOW(), INTERVAL 1 MONTH))"), 'to' => adesk_CURRENTDATE);
		//return array('from' => adesk_CURRENTDATE, 'to' => adesk_sql_select_one("SELECT DATE(ADDDATE(NOW(), INTERVAL 1 MONTH))"));
	}
	$from = $monthstartdate;
	$to = date("Y-m-d", mktime(0, 0, 0, $month + 1, $day, $year));
	if ( $today >= $from and $today < $to ) {
		return array('from' => $from, 'to' => $to);
	}
	return adesk_date_month_datein_forward($to, $today);
}

function adesk_date_month_datein_back($monthenddate, $today) {
	list($year, $month, $day) = explode('-', $monthenddate);
	if ( !(int)$year or (int)$year < date('Y') ) {
		require_once(dirname(__FILE__) . '/sql.php');
		return array('from' => adesk_sql_select_one("SELECT DATE(SUBDATE(NOW(), INTERVAL 1 MONTH))"), 'to' => adesk_CURRENTDATE);
	}
	$to = $monthenddate;
	$from = date("Y-m-d", mktime(0, 0, 0, $month - 1, $day, $year));
	if ( $today >= $from and $today < $to ) return array('from' => $from, 'to' => $to);
	return adesk_date_month_datein_back($from, $today);
}

function adesk_date_timeago($startDate, $endDate = null, $howMany = 0, $ago = true) {
	if ( !$endDate ) $endDate = adesk_getCurrentDateTime();
	$timeSpan = adesk_date_sqldiff($endDate, $startDate);
	$spanArr = adesk_date_timespan_array($timeSpan);
	$spanClean = array();
	$lastVal = '';
	foreach ( $spanArr as $k => $v ) {
		if ( $v > 0 ) {
			$spanClean[$k] = $v;
			$lastVal = $k;
		}
	}
	// if large values are present
	if ( isset($spanClean['day']) or isset($spanClean['week']) or isset($spanClean['month']) or isset($spanClean['year']) ) {
		// take out small values
		if ( isset($spanClean['hour']) ) unset($spanClean['hour']);
		if ( isset($spanClean['minute']) ) unset($spanClean['minute']);
		if ( isset($spanClean['second']) ) unset($spanClean['second']);
	}
	// if anything is there next to seconds, take seconds out
	if ( count($spanClean) > 1 and isset($spanClean['second']) ) unset($spanClean['second']);
	// if nothing is there, then it's 0 seconds
	if ( !$spanClean ) $spanClean = array('second' => 0);

	// singular and plural strings legend
	$legend  = array(
		'year'   => _a("year"),
		'month'  => _a("month"),
		'week'   => _a("week"),
		'day'    => _a("day"),
		'hour'   => _a("hour"),
		'minute' => _a("minute"),
		'second' => _a("second"),
	);
	$legends = array(
		'year'   => _a("years"),
		'month'  => _a("months"),
		'week'   => _a("weeks"),
		'day'    => _a("days"),
		'hour'   => _a("hours"),
		'minute' => _a("minutes"),
		'second' => _a("seconds"),
	);

	// construct an array of final values
	$i = 0;
	$arr = array();
	foreach ( $spanClean as $k => $v ) {
		$i++;
		if ( $howMany and $i > $howMany ) break;

		$plural = substr("$v", -1, 1) != "1";
		$str = $v . " " . ( $plural ? $legends[$k] : $legend[$k] );

		$arr[] = $str;
	}
	// if more than one, put "and" in between the last and other ones
	if ( count($arr) > 1 ) {
		$lastVal = array_pop($arr);
		$final = sprintf(_a("%s and %s"), implode(", ", $arr), $lastVal);
	} else {
		$final = $arr[0];
	}

	// if returning the string without "ago" part
	if ( !$ago ) return $final;

	// contruct a final string
	return sprintf(_a("%s ago"), $final);
}

?>
