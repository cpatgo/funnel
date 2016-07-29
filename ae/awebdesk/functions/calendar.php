<?php

function adesk_calendar_prepare_day($year, $month, $day) {

	$GLOBALS["adesk_calendar"]["day"]["mktime_display"] = mktime(0, 0, 0, intval($month), intval($day), $year);
	$GLOBALS["adesk_calendar"]["day"]["day_display"] = _a( date("l", $GLOBALS["adesk_calendar"]["day"]["mktime_display"]) );
	$GLOBALS["adesk_calendar"]["day"]["month_display"] = _a( date("F", $GLOBALS["adesk_calendar"]["day"]["mktime_display"]) );
	$GLOBALS["adesk_calendar"]["day"]["date_display"] = date("j", $GLOBALS["adesk_calendar"]["day"]["mktime_display"]);
	$GLOBALS["adesk_calendar"]["day"]["sql_date"] = $year . "-" . $month . "-" . $day;
}

function adesk_calendar_select_day($year, $month, $day) {

	adesk_calendar_prepare_day($year, $month, $day);

	$data = adesk_ihook('adesk_calendar_day', $GLOBALS["adesk_calendar"]["day"]["sql_date"]);

	$result = array();
	for ($hour = 0; $hour < 24; $hour++) {
		// populate with empty array to start
		$result[$hour] = array();
	}

	foreach ($data as $k => $v) {
		/* $data must look like:
		array(
			"events" => array(
				"by_hour" => array( "0" => array("events for hour 0"), "1" => array("events for hour 1"), etc... ),
				"all" => $events_for_day,
			),
			"tasks" => array(
				"by_hour" => array( "0" => array("events for hour 0"), "1" => array("events for hour 1"), etc... ),
				"all" => $events_for_day,
			),
			etc...
		);
		*/
		foreach ($v["by_hour"] as $hour => $data_display) {
			$result[$hour][$k] = $data_display;
		}
	}

	$result["day_view_dayname"] = $GLOBALS["adesk_calendar"]["day"]["day_display"] . ", " . $GLOBALS["adesk_calendar"]["day"]["month_display"] . " " . $GLOBALS["adesk_calendar"]["day"]["date_display"] . ", " . $year;

	return $result;
}

function adesk_calendar_prepare_month($year, $month) {

	$GLOBALS["adesk_calendar"]["month"]["mktime_display"] = mktime(0, 0, 0, $month, 1, $year);

	$GLOBALS["adesk_calendar"]["month"]["day_today"] = date("j", strtotime(adesk_CURRENTDATETIME));
	$GLOBALS["adesk_calendar"]["month"]["month_today"] = date("m", strtotime(adesk_CURRENTDATETIME));
	$GLOBALS["adesk_calendar"]["month"]["year_today"] = date("Y", strtotime(adesk_CURRENTDATETIME));

	$GLOBALS["adesk_calendar"]["month"]["istoday_month_year"] = ( date("Y", strtotime(adesk_CURRENTDATETIME)) == date("Y", $GLOBALS["adesk_calendar"]["month"]["mktime_display"]) && date("m", strtotime(adesk_CURRENTDATETIME)) == date("m", $GLOBALS["adesk_calendar"]["month"]["mktime_display"]) ) ? true : false;

	$GLOBALS["adesk_calendar"]["month"]["month_with_zero"] = date("m", $GLOBALS["adesk_calendar"]["month"]["mktime_display"]);
	$GLOBALS["adesk_calendar"]["month"]["month_without_zero"] = date("n", $GLOBALS["adesk_calendar"]["month"]["mktime_display"]);
	$GLOBALS["adesk_calendar"]["month"]["month_display"] = _a( date("F", $GLOBALS["adesk_calendar"]["month"]["mktime_display"]) );

	$GLOBALS["adesk_calendar"]["month"]["days_in_month"] = date("t", $GLOBALS["adesk_calendar"]["month"]["mktime_display"]);
	$GLOBALS["adesk_calendar"]["month"]["days_in_month_previous"] = date("t", mktime(0, 0, 0, $month - 1, date("d"), $year));

	$GLOBALS["adesk_calendar"]["month"]["first_day_in_month_text"] = date("l", mktime(0, 0, 0, $month, 1, $year));
	$GLOBALS["adesk_calendar"]["month"]["first_day_in_month_numeric"] = date("w", mktime(0, 0, 0, $month, 1, $year));
	$GLOBALS["adesk_calendar"]["month"]["last_day_in_month_numeric"] = date("w", mktime(0, 0, 0, $month, $GLOBALS["adesk_calendar"]["month"]["days_in_month"], $year));

	$GLOBALS["adesk_calendar"]["month"]["days_in_first_row"] = 7 - $GLOBALS["adesk_calendar"]["month"]["first_day_in_month_numeric"];
	$GLOBALS["adesk_calendar"]["month"]["remaining_days_sans_first_row"] = $GLOBALS["adesk_calendar"]["month"]["days_in_month"] - $GLOBALS["adesk_calendar"]["month"]["days_in_first_row"];

	$GLOBALS["adesk_calendar"]["month"]["tr_counter_max"] = ceil($GLOBALS["adesk_calendar"]["month"]["remaining_days_sans_first_row"] / 7) + 2;
}

function adesk_calendar_select_month($year = "now", $month = "now", $size = "normal", $filterids = "", $post_type = null) {

	if ( $year == "now" ) $year = date("Y", strtotime(adesk_CURRENTDATETIME));
	if ( $month == "now" ) $month = date("m", strtotime(adesk_CURRENTDATETIME));

	adesk_calendar_prepare_month($year, $month);

	/*
	$date_template = $year . '-' . str_pad($month, 2, 0, STR_PAD_LEFT) . '-';
	$from = $date_template . '01';
	$to = ( $month == 12 ? ($year + 1) . '-01-01' : $year . '-' . str_pad($month + 1, 2, 0, STR_PAD_LEFT) . '-01' );

	$_POST["from"] = $from;
	$_POST["to"] = $to;

	if ( file_exists(adesk_admin('functions/calendar.php')) ) require_once(adesk_admin('functions/calendar.php'));

	if ($filterid != 0) {
		$filterid_value = $filterid;
	}
	elseif (function_exists('calendar_filter_post')) {
		$filterid = calendar_filter_post();
		$filterid_value = $filterid["filterid"];
	}
	else {
		$filterid = 0;
		$filterid_value = 0;
	}
	*/

	// fetch data to display in the calendar
	$data = adesk_ihook('adesk_calendar_month', $year . "-" . $month, $filterids, $post_type);

	if ($month == 1) {
		$prev_month = 12;
		$prev_year = $year - 1;
	}
	else {
		$prev_month = $month - 1;
		$prev_year = $year;
	}

	if ($month == 12) {
		$next_month = 1;
		$next_year = $year + 1;
	}
	else {
		$next_month = $month + 1;
		$next_year = $year;
	}

	$result = array();

	$result["calendar_size"] = $size;
	$result["month_with_zero"] = $GLOBALS["adesk_calendar"]["month"]["month_with_zero"];
	$result["month_without_zero"] = $GLOBALS["adesk_calendar"]["month"]["month_without_zero"];
	$result["month_display"] = $GLOBALS["adesk_calendar"]["month"]["month_display"];
	$result["year"] = $year;
	$result["previous_link_year"] = $prev_year;
	$result["previous_link_month"] = $prev_month;
	$result["next_link_year"] = $next_year;
	$result["next_link_month"] = $next_month;
	$result["filterid_event"] = $data["events"]["filterid"];
	$result["filterid_task"] = $data["tasks"]["filterid"];
	$result["filterid_ticket"] = $data["tickets"]["filterid"];

	// Previous month days.
	// If it's not 0, then there are previous month days to display.
	// 0 = Sun, which would be the top left spot on the calendar - the very first spot.
	if ($GLOBALS["adesk_calendar"]["month"]["first_day_in_month_numeric"] != 0) {

		// IE: 31 - 5 = 26. (31 days in previous month, minus current month's first day value 0-6) always gives us the top left #1 cell's date.
		$first_array_item = $GLOBALS["adesk_calendar"]["month"]["days_in_month_previous"] - ($GLOBALS["adesk_calendar"]["month"]["first_day_in_month_numeric"] - 1);

		// IE: Loop through range 26-31, which are the previous month days that will show up on the current month's calendar.
		// Insert those days into the array.
		for ($i = $first_array_item; $i <= $GLOBALS["adesk_calendar"]["month"]["days_in_month_previous"]; $i++) {

			$day_array = array(
				"year" => $prev_year,
				"month" => $prev_month,
				"day" => $i,
			);

			foreach ($data as $k => $v) {
				$day_array[$k] = array();
			}

			$result["cells"][] = $day_array;
		}
	}

	// Current month days.
	// Loop through current month's number of days and insert into array.
	for ($i = 1; $i <= $GLOBALS["adesk_calendar"]["month"]["days_in_month"]; $i++) {

		$day_array = array(
			"today" => ($i == $GLOBALS["adesk_calendar"]["month"]["day_today"] && $month == $GLOBALS["adesk_calendar"]["month"]["month_today"] && $year == $GLOBALS["adesk_calendar"]["month"]["year_today"]) ? 1 : 0,
			"year" => $year,
			"month" => $month,
			"day" => $i,
		);

		foreach ($data as $k => $v) {
			$day_array[$k] = $v["data"][$i];
		}

		$result["cells"][] = $day_array;
	}

	// Next month days.
	// If it's not 6, which would indicate that the last day is a Saturday, and would fall in the last <td> of a row,
	// meaning we would not have to display the next month's dates.
	if ($GLOBALS["adesk_calendar"]["month"]["last_day_in_month_numeric"] != 6) {

		// IE: 7 - (0 + 1) = 6.
		$last_array_item = 7 - ($GLOBALS["adesk_calendar"]["month"]["last_day_in_month_numeric"] + 1);

		// IE: Loop through range 1-6, which are the next month days that will show up on the current month's calendar.
		for ($i = 1; $i <= $last_array_item; $i++) {

			$day_array = array(
				"year" => $next_year,
				"month" => $next_month,
				"day" => $i,
			);

			foreach ($data as $k => $v) {
				$day_array[$k] = array();
			}

			$result["cells"][] = $day_array;
		}
	}

	return $result;
}

function calendar_ical($events = array(), $todos = array()) {
	global $site, $admin;
	// define the exit string
	$str  = "BEGIN:VCALENDAR\n";
	$str .= "PRODID:-//AwebDesk Inc.//$GLOBALS[adesk_app_name]//" . strtoupper(_i18n("en") . "\n");
	$str .= "VERSION:2.0\n";
	$str .= "CALSCALE:GREGORIAN\n";
	$str .= "METHOD:PUBLISH\n";
	$str .= "X-WR-TIMEZONE:UTC\n";
	$str .= "X-WR-CALDESC:$site[site_name]\n";
	if ( $events ) $str .= calendar_ical_events($events);
	if ( $todos  ) $str .= calendar_ical_todos($todos);
	$str .= "END:VCALENDAR\n";
	return $str;
}

function calendar_ical_events($events = array()) {
	global $site, $admin;
	$str = "";
	foreach ( $events as $event ) {
		$cdate = strtotime($event['cdate']);
		$udate = strtotime($event['udate']);
		$sdate = strtotime($event['sdate']);
		$edate = strtotime($event['edate']);
		$dtstart = date('Ymd', $sdate);
		$dtend = date('Ymd', $edate);
		$dtstamp = calendar_ical_tstamp($cdate);
		$lastmod = calendar_ical_tstamp($udate);
		$str .= "BEGIN:VEVENT\n";
		$str .= "DTSTART;VALUE=DATE:$dtstart\n";
		$str .= "DTEND;VALUE=DATE:$dtend\n";
		$str .= "DTSTAMP:$dtstamp\n";
		//$str .= "UID:h@fbd0f989d6a476309526aa2bfd239f8882e78d94@google.com\n";
		//$str .= "ATTENDEE;CUTYPE=INDIVIDUAL;ROLE=REQ-PARTICIPANT;PARTSTAT=ACCEPTED;CN=US Holidays;X-NUM-GUESTS=0:mailto:en.usa#holiday@group.v.calendar.google.com\n";
		$str .= "CLASS:PUBLIC\n";
		$str .= "CREATED:$dtstamp\n";
		$str .= "LAST-MODIFIED:$lastmod\n";
		$str .= "SEQUENCE:1\n";
		$str .= "STATUS:CONFIRMED\n";
		$str .= "SUMMARY:$event[name]\n";
		$str .= "TRANSP:OPAQUE\n";
		$str .= "END:VEVENT\n";
	}
	return $str;
}

function calendar_ical_todos($todos = array()) {
	global $site, $admin;
	$str = "";
	foreach ( $todos as $todo ) {
		$sdate = strtotime($todo['sdate']);
		$str .= "BEGIN:VTODO\n";
		$str .= "";
		$str .= "END:VTODO\n";
	}
	return $str;
}

function calendar_ical_tstamp($uStamp = 0, $tzone = 0.0) {
	$uStampUTC = $uStamp + ($tzone * 3600);
	$stamp  = date("Ymd\THis\Z", $uStampUTC);
	return $stamp;
}

function calendar_ical_date($date) {
	return calendar_ical_tstamp(strtotime($date));
}


?>