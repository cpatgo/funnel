<?php
 
// get complete Admin info by ID
function user_get($aid, $justUsername = false) {
	$cond = ( is_array($aid) ? "IN ('" . implode("', '", $aid) . "')" : "= '$aid'" );
	$what = ( $justUsername ? 'absid' : '*' );
	$sql = adesk_sql_query("SELECT $what FROM #user WHERE id $cond");
	if ( mysql_num_rows($sql) == 0 ) return false;
	if ( !is_array($aid) ) {
		$r = mysql_fetch_assoc($sql);
		$absUser = adesk_auth_record_id($r['absid']);
		return ( $justUsername ? $absUser['username'] : array_merge($absUser, $r) );
	} else {
		$r = array();
		while ( $row = mysql_fetch_assoc($sql) ) {
			$absUser = adesk_auth_record_id($row['absid']);
			$r[$row['id']] = ( $justUsername ? $absUser['username'] : array_merge($absUser, $row) );
		}
		return $r;
	}
}

// extract adminID from string
function user_extract_ids($str, $implode = false) {
	$arr = explode(' ,', $str);
	$users = array();
	foreach ( $arr as $v ) {
		$v = trim(str_replace(',', '', $v));
		if ( (int)$v != 0 ) $users[] = (int)$v;
	}
	if ( count($users) > 0 ) {
		$a = user_get($users, true);
		if ( $a ) return ( $implode ? implode(', ', $a) : $a );
	}
	return ( $implode ? '' : array() );
}

function user_get_groups($id) {
	if ($id = (int)$id)
		return adesk_sql_select_box_array("SELECT groupid, groupid FROM #user_group WHERE userid = '$id'");
	else
		return array(1 => 1);//adesk_sql_select_box_array("SELECT groupid, groupid FROM #user_group WHERE id = 1");
}

function user_search($entered, $format = '%%%s%%') {
	$r = adesk_auth_search($entered, $format);
	// now filter those that are not users of this app
	// ... 2do
	return $r;
}

function user_update_value($column, $value) {
	$whitelist = array(
		'!!!offset!!!',
		'lists_per_page',
		'default_dashboard',
		'default_mobdashboard',
		'messages_per_page',
		'subscribers_per_page',
		'htmleditor',
		'lang',
	);
	$admin = adesk_admin_get();
	if ( !in_array($column, $whitelist) ) return adesk_ajax_api_result(0, _a('Unknown Command.'));
	if ( !isset($admin[$column]) ) return adesk_ajax_api_result(0, _a('Invalid Command.'));
	if ( $value == $admin[$column] ) {
		return adesk_ajax_api_result(1, _a("Your preference has been saved."));
	}
	if ( adesk_admin_isauth() ) {
		if ( $column == '!!!offset!!!' ) {
			// break $value into two here
			$values = explode(',', $value);
			// update two columns here :-(
			$r = adesk_sql_update(
				'#user',
				array(
					't_offset' => $values[0],
					't_offset_o' => $values[1]
				),
				"`id` = '$admin[id]'"
			);
		} else {
			$r = adesk_sql_update_one('#user', $column, $value, "`id` = '$admin[id]'");
		}
	} else {
		// guests have cookies
		$r = @setcookie('adesk_' . $column, $value, time() + 60 * 60 * 24 * 365, '/');
		$_COOKIE['adesk_' . $column] = $value;
	}
	return adesk_ajax_api_result(
		$r,
		( $r ? _a("Your preference has been saved.") : _a("Your preference has not been saved!  " . mysql_error()) )
	);
}

function user_rebuild_permissions($id) {
	/* this rebuilding sucks :)
	by Sandeep on 28 June, 2012
	
	 $id = intval($id);

	adesk_sql_query("DELETE FROM #user_p WHERE userid = '$id'");

	$gset     = adesk_sql_select_list("SELECT groupid FROM #user_group WHERE userid = '$id'");
	$gset_str = implode("','", $gset);
	$rset     = adesk_sql_select_list("SELECT DISTINCT(listid) FROM #list_group WHERE groupid IN ('$gset_str')");
	$perms    = user_get_groups($id);

	foreach ($rset as $relid) {
		$ary = array(
			"listid" => $relid,
			"userid"     => $id,
		);

		$rgroups  = list_get_groups($relid);

		foreach ($rgroups as $rgroup) {
			if (isset($perms[$rgroup["id"]])) {
				foreach ($rgroup as $key => $val) {
					if (substr($key, 0, 2) == "p_") {
						if (isset($ary[$key]) && $ary[$key])
							continue;

						$ary[$key] = $id == 1 ? 1 : $val;
					}
				}
			}
		}

		adesk_sql_insert("#user_p", $ary);
	} */
}

function user_requires_senderinfo($userid) {
	$userid = (int)$userid;
	return (int)adesk_sql_select_one("
		SELECT
			COUNT(*)
		FROM
			#group_limit g
		WHERE
			g.forcesenderinfo = 1
		AND
			g.groupid IN (SELECT u.groupid FROM #user_group u WHERE u.userid = '$userid')
	");
}

/*

	NOT USED (yet)

*/

function user_calendar($year, $month, $width, $height, $type = 'm') {
	// define array to return for calendar
	$r = array();
	// calendar is per user
	$adminID = $GLOBALS['admin']['id'];
	// check if year is in range
	if ( $year < 1971 or $year > 2037 ) {
		$r['year'] = false;
		return $r;
	}
	// fix month if neccessary
	if ( $month < 1 ) {
		$month = 12;
		$year--;
	}
	if ( $month > 12 ) {
		$month = 1;
		$year++;
	}
	// calendar type (monthly or yearly)
	if ( $type != 'y' ) $type = 'm';
	// assign all vars to final array
	$r['year'] = $year;
	$r['month'] = $month;
	$r['width'] = $width;
	$r['height'] = $height;
	$r['type'] = $type;
	// today is...
	$r['thisYear'] = date('Y');
	$r['thisMonth'] = date('n');
	$r['thisDay'] = date('j');
	// start creating vars neccessary for calendar
	// title
	$r['title'] = _a(date('F', mktime(0, 0, 0, $month, 1, $year)));
	// total days in this month
	$r['totalDays'] = date('j', mktime(0, 0, 0, $month + 1, 0, $year));
	// how much spaces do we leave before the first in month
	$r['firstSpaces'] = date('w', mktime(0, 0, 0, $month, 1, $year));
	// how much spaces do we have after the last in month
	$r['lastSpaces'] = 6 - date('w', mktime(0, 0, 0, $month, $r['totalDays'], $year));
	// loop through all days in months
	$r['days'] = array();
	for ( $i = 1; $i <= $r['totalDays']; $i++ ) {
		// asssign array for this day
		$r['days'][$i] = array();
		$r['days'][$i]['day'] = $i;
		// this is what day in week?
		$r['days'][$i]['inWeek'] = date('w', mktime(0, 0, 0, $month, $i, $year));
		// add leading zero for output if needed
		$r['days'][$i]['currentDay'] = ( $i < 10 ? 0 : '' ) . $i;
		// define current day timestamp for database checks
		$r['days'][$i]['tstamp'] = (int)($year . str_pad($month, 2, 0, STR_PAD_LEFT) . $r['days'][$i]['currentDay']);
		$r['days'][$i]['title'] = '';
		$r['days'][$i]['link'] = false;
		// do we need a todo image?
		$r['days'][$i]['todo'] = false;
	}
	// look which day has some entryes/todos
	// time span
	$dateTemplate = $year . '-' . str_pad($month, 2, 0, STR_PAD_LEFT) . '-';
	$from = $dateTemplate . '01';
	$to = ( $month == 12 ? ($year + 1) . '-01-01' : $year . '-' . str_pad($month + 1, 2, 0, STR_PAD_LEFT) . '-01' );
	// todos
	$todo_sql = adesk_sql_query("
		SELECT
			date, title
		FROM w_todo
		WHERE
			date >= '$from'
		AND
			date < '$to'
		AND
			close != 1
		AND
			(
			type = '0'
		OR
			user_id LIKE '%, $adminID ,%'
		OR
			user_cre_id = '$adminID'
			)
	");
	while ( $row = mysql_fetch_assoc($todo_sql) ) {
		$day = (int)substr($row['date'], -2);
		$r['days'][$day]['title'] = smart_unescape($row['title']);
		$r['days'][$day]['link'] = true;
		$r['days'][$day]['todo'] = ( $type == 'm' );
	}
	// entries (so it can override todos since it is more important)
	$entry_sql = adesk_sql_query("
		SELECT
			sdate, edate, title
		FROM w_calendar
		WHERE
		(
			(sdate >= '$from' AND sdate < '$to')
				OR
			(edate >= '$from' AND edate < '$to')
				OR
			(sdate < '$from' AND edate >= '$to')
		)
		AND
			(
			type = '0'
		OR
			user_id LIKE '%, $adminID ,%'
		OR
			user_cre_id = '$adminID'
			)
	");
	while ( $row = mysql_fetch_assoc($entry_sql) ) {
		$row['title'] = smart_unescape($row['title']);
		// if enddate is not set
		if ( $row['edate'] == '0000-00-00' ) {
			$day = (int)substr($row['sdate'], -2);
			$r['days'][$day]['title'] = $row['title'];
			$r['days'][$day]['link'] = true;
			$r['days'][$day]['todo'] = false;
		// enddate is set, do magic here
		} else {
			// prepare timestamps
			$row['ststamp'] = (int)str_replace('-', '', $row['sdate']);
			$row['etstamp'] = (int)str_replace('-', '', $row['edate']);
			// loop through days
			foreach ( $r['days'] as $day => $info ) {
				if ( $info['tstamp'] <= $row['etstamp'] and $info['tstamp'] >= $row['ststamp'] ) {
					$r['days'][$day]['title'] = $row['title'];
					$r['days'][$day]['link'] = true;
					$r['days'][$day]['todo'] = false;
				}
			}
		}
	}
	return $r;
}

?>
