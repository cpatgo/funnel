<?php

function permission($key) {
	return isset($GLOBALS["admin"][$key]) && $GLOBALS["admin"][$key] == 1;
}

// if admin is not null, it has to be previously fetched with
// adesk_admin_get_totally_unsafe($id) to ensure ihooks ran and all permissions are set for him
function withinlimits($key, $currentCnt = null, $admin = null) {
	if ( is_null($admin) ) $admin = $GLOBALS['admin'];
	// if limit doesn't exist
	if ( !isset($admin['limit_' . $key]) ) return false;
	// fetch current count if missing
	if ( is_null($currentCnt) ) $currentCnt = limit_count($admin, $key, $overall = false);
	// if limit is zero (0), then it's unlimited
	if ( !$admin['limit_' . $key] ) {
		if ( isset($GLOBALS['_hosted_account']) ) {
			return withinhostedlimits($key, $currentCnt, $admin);
		}
		return true;
	}
	// compare counts
	if ( $currentCnt <= $admin['limit_' . $key] ) {
		if ( isset($GLOBALS['_hosted_account']) ) {
			return withinhostedlimits($key, $currentCnt, $admin);
		}
		return true;
	}
	return false;
}

// hosted (master) limit check
function withinhostedlimits($key, $currentCnt = null, $admin = null) {
	if ( !in_array($key, array('mail', 'subscriber')) ) return true;
	if ( $key == 'mail' ) {
		// max emails for hosted check
		//if ( !$admin['limit_mail'] or $GLOBALS['_hosted_limit_mail'] < $admin['limit_mail'] ) {
			$admin['limit_mail'] = $GLOBALS['_hosted_limit_mail'];
			// now fix the currentCnt
			#$diff = $currentCnt - $admin['emails_sent'];
			#$paymentDate = $_SESSION[$GLOBALS["domain"]]["last_payment_date"];
			#$range = adesk_date_month_datein_forward($paymentDate, adesk_CURRENTDATE);
			#$interval = "tstamp BETWEEN '$range[from]' AND '$range[to]'";
			#$mailcnt = (int)adesk_sql_select_one("=SUM(amt)", "#campaign_count", $interval);
			#$currentCnt = $mailcnt + $diff;
			$currentCnt = (int)adesk_sql_select_one("SELECT sentemails FROM #backend");
		//}
	} elseif ( $key == 'subscriber' ) {
		// max subscribers for hosted check
		if ( !$admin['limit_subscriber'] or $GLOBALS['_hosted_limit_sub'] < $admin['limit_subscriber'] ) {
			$admin['limit_subscriber'] = $GLOBALS['_hosted_limit_sub'];
		}
	}
	return $admin['limit_' . $key] < 0 || $currentCnt <= $admin['limit_' . $key];
}

function withindeletelimits() {
	if (!isset($GLOBALS["_hosted_account"]) || !isset($GLOBALS["domain"]) || !isset($_SESSION[$GLOBALS["domain"]]))
		return true;

	$deletedsubs = (int)adesk_sql_select_one("SELECT deletedsubs FROM #backend");
	$limitsub    = (int)$_SESSION[$GLOBALS["domain"]]["awebdesk_limit_sub"];

	if ($limitsub < 0)
		return true;

	return $deletedsubs <= (1 * $limitsub);
}

// Checking to see if the user/list is past their limit for amt of emails they are able to send.
function limit_count($admin, $key = 'mail', $overall = false) {
	if ( !isset($admin['limit_' . $key]) ) return 0;
	if ( !in_array($key, array('campaign', 'mail')) ) return limit_count_simple($admin, $key);
	if ( !isset($admin['limit_' . $key . '_type']) ) return limit_count_simple($admin, $key);
	// these are the fields we're looking for to get counts from
	$count = ( $key == 'mail' ? 'SUM(c.amt)' : 'COUNT(DISTINCT(c.id))' );
	//$fieldLimit = $admin['limit_' . $key];
	$fieldLimitType = $admin['limit_' . $key . '_type'];
	$cond = '';
	// getting date -- need to be SQL calls; adesk_getCurrentDateTime() needs $admin to exist
	// globally, and this code is run prior to $admin existing.
	$tDate = adesk_sql_select_one("SELECT CURRENT_DATE()");
	$tTime = adesk_sql_select_one("SELECT CURRENT_TIME()");
	$tDateTime = adesk_sql_select_one("SELECT NOW()");
	if ( $fieldLimitType == 'monthcdate' and !$admin['sdate'] ) {
		$fieldLimitType = 'month';
	}
	// exploding the date
	list($year,  $month,   $day)     = explode('-', $tDate);
	list($hours, $minutes, $seconds) = explode(':', $tTime);
	// finding the cutoff date based off the duration
	if ( !( $fieldLimitType == 'ever' or $overall ) ) {
		if ( $fieldLimitType == 'day' ) {
			$msgCutoff = date('Y-m-d', mktime($hours, $minutes, $seconds, $month, $day - 1, $year));
		} elseif ( $fieldLimitType == 'week' ) {
			$msgCutoff = date('Y-m-d', mktime($hours, $minutes, $seconds, $month, $day - 7, $year));
		} elseif ( $fieldLimitType == 'month' ) {
			$msgCutoff = date('Y-m-d', mktime($hours, $minutes, $seconds, $month - 1, $day, $year));
		} elseif ( $fieldLimitType == 'month1st' ) {
			$msgCutoff = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year));
		} elseif ( $fieldLimitType == 'monthcdate' ) {
			// get day
			$tmp = explode(' ', $admin['sdate']);
			$tmp = explode('-', $tmp[0]);
			$cday = ( isset($tmp[2]) ? $tmp[2] : 1 );
			// get month
			if ( "$year-$month-$cday" >= $tDate ) {
				$month--;
			}
			$msgCutoff = date('Y-m-d', mktime(0, 0, 0, $month, $cday, $year));
		}
		$msgCutoff .= ' ' . ( $fieldLimitType == 'month1st' ? '00:00:00' : $tTime );
		$cond = "AND c.tstamp >= '$msgCutoff'";
	}
	$groupslist = implode("', '", $admin['groups']);
	$adminslist = "SELECT userid FROM #user_group WHERE groupid IN ('$groupslist')";
	//$admin22 = adesk_admin_get();
$uid22 = $admin['id'];
	if($uid22 == 1)
	
	{
	$query = "
		SELECT
			$count
		FROM
			#campaign_count c
		WHERE
			c.userid IN ($adminslist)
		AND
			c.amt > 0
		$cond
	";
	}
	else
	
	{
		$query = "
		SELECT
			$count
		FROM
			#campaign_count c
		WHERE
			c.userid = $uid22
		AND
			c.amt > 0
		$cond
	";
	
	}
	
	return (int)adesk_sql_select_one($query);
}

function limit_count_simple($admin, $key = 'subscriber') {
	if ( !in_array($key, array('subscriber', 'list', 'user')) ) return 0;
	if ( !isset($admin['limit_' . $key]) ) return 0;
	$cond = '';
	$groupslist = implode("', '", $admin['groups']);
	switch ( $key ) {

		case 'user':
			return (int)adesk_sql_select_one("SELECT COUNT(userid) FROM #user_group WHERE groupid IN ('$groupslist')");
			break;
		case 'list':
			$table = 'list';
			$count = 'COUNT(t.id)';
			$cond .= "AND t.id IN ( SELECT listid FROM #list_group WHERE groupid IN ('$groupslist') )";
			break;
		case 'subscriber':
		default:

			$lists = list_get_all(false,true);
			$listids = '';
			foreach($lists as $var){
				if($listids == ''){ $listids = $var["id"]; }
				else{ $listids = $listids.','.$var["id"]; }
			}
			if ( !$listids ) $listids = "''";

			$table = 'subscriber_list';
			$count = 'COUNT(DISTINCT(t.subscriberid))';
			if ( $admin['id'] != 1 ) $cond .= "AND t.listid IN ($listids)";

			break;
	}
	$query = "
		SELECT
			$count
		FROM
			#$table t
		WHERE
		1
		$cond
	";
	return (int)adesk_sql_select_one($query);
}

?>
