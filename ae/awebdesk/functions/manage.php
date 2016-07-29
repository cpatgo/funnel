<?php
// manage.php

require_once dirname(__FILE__) . '/auth.php';
require_once dirname(__FILE__) . '/prefix.php';
require_once dirname(__FILE__) . '/session.php';
require_once dirname(__FILE__) . '/sql.php';
require_once dirname(__FILE__) . '/ihook.php';

$ihooks = adesk_admin('functions/ihooks.php');

if (file_exists($ihooks))
    require_once $ihooks;

// For adesk_admin_get() to work, two ihooks must be declared.  The first,
// adesk_admin_get_query, returns the result set of an adesk_sql_query call
// which should comprise all of the columns from #admin, #admin_p,
// whatever tables that you want to include in the array that will be
// returned by adesk_admin_get().  The second, adesk_admin_get_post, doesn't
// need to be declared; it's there to modify the array after everything
// else has been done and the function is finishing up.  Both of these
// features are necessary in TrioLive.

function adesk_admin_get() {
	if ( isset($GLOBALS['admin']) && isset($GLOBALS['admin']['username']) ) {
		if ($GLOBALS["admin"]["id"] != 0 && isset($GLOBALS['adesk_editor_rootpath']) && !isset($GLOBALS["_hosted_account"])) {
			$_SESSION["ACIsLoggedIn"] = true;
			$_SESSION["ACRootPath"]   = ( adesk_site_isstandalone() ? adesk_base() : dirname(adesk_base()) ) . '/' . $GLOBALS['adesk_editor_rootpath'];
		}

		return $GLOBALS['admin'];
	}
    if ( defined('AWEBP_USER_NOAUTH') and AWEBP_USER_NOAUTH ) {
	    if (!adesk_auth_isconnected())
	        adesk_auth_connect();

	    adesk_auth_define_cookie();

      $authenticated = adesk_auth_ok();
      if (!$authenticated) {
    		if (adesk_ihook_exists("adesk_admin_get_noauth"))
    			return adesk_ihook("adesk_admin_get_noauth");
    		else
    			return false;
      }
    }
    if (!isset($_SESSION[adesk_prefix("aweb_admin")]) || adesk_session_need_update()) {
        // Get the global authenticator to do authentication
        $authenticated = adesk_auth_ok();

        if( !$authenticated ) {
			if (adesk_ihook_exists("adesk_admin_get_noauth"))
				return adesk_ihook("adesk_admin_get_noauth");
			else
				return false;
        }

        // User is authenticated, fetch his local data
        $userId = adesk_auth_id();

        $r = adesk_admin_get_totally_unsafe($userId, 'absolute');
        if ( !$r ) {
			if (adesk_ihook_exists("adesk_admin_get_noauth"))
				return adesk_ihook("adesk_admin_get_noauth");
			else
				return false;
        }
        $_SESSION[adesk_prefix("aweb_admin")] = $r;
    } else {
        $r = $_SESSION[adesk_prefix("aweb_admin")];
    }
    $_SESSION['authenticated_username'] = $r['username'];

    return $r;
}

function adesk_admin_get_totally_unsafe($id, $method = 'local') {
	if ($method == 'absolute') {
		$sql = adesk_ihook('adesk_admin_get_query', $id);
	} else {
		# This is just in case a product is built without the local ihook--at least it'll
		# function.  In most cases with our apps, this is called with id=1, and it doesn't matter
		# then if we use the absolute query.
		if (adesk_ihook_exists("adesk_admin_get_query_local"))
			$sql = adesk_ihook('adesk_admin_get_query_local', $id);
		else
			$sql = adesk_ihook("adesk_admin_get_query", $id);
	}

    if ( !$sql ) return false;

    if ( mysql_num_rows($sql) == 0 ) return false;
    // fetch his info to return
    $r = mysql_fetch_assoc($sql);
	if ($method == 'absolute')
		$thisUser = adesk_auth_record_id($id);
	else
		$thisUser = adesk_auth_record_id($r['absid']);

    $r['username']   = $thisUser['username'];
    $r['first_name'] = $thisUser['first_name'];
    $r['last_name']  = $thisUser['last_name'];
    $r['email']      = $thisUser['email'];
    $r["fullname"]   = $thisUser['first_name'] . ' ' . $thisUser['last_name'];

    if (isset($thisUser['acctid']))
        $r['acctid'] = $thisUser['acctid'];
    // update his info
    adesk_admin_marktime("id = '$r[id]'");
    $r = adesk_ihook('adesk_admin_get_post', $r);

    $r['accessadmin'] = 1;

    return $r;
}

// If we're logged in with our admin status cached in $_SESSION, then
// return true.  Otherwise, run the full check in adesk_admin() and return
// whether it was true or not.  $id is a specific id that we want to
// match, and if it's a (positive) non-zero number then we must do a
// full check.

function adesk_admin_isauth($id = 0) {
    if ($id < 1 && isset($_SESSION[adesk_prefix("aweb_admin")]) && $_SESSION[adesk_prefix("aweb_admin")]['id'])
        return true;

    $admin = adesk_admin_get();

    if ($id < 1 && isset($admin['id']) && $admin['id'] > 0)
        return true;
    else
        return $admin['id'] == $id && $id > 0;
}

function adesk_admin_isadmin() {
	$admin = adesk_admin_get();
	if ( !isset($admin['id']) ) return false;
	if ( $admin['id'] < 1 ) return false;
	if ( !isset($admin['p_admin']) ) return true;
	return ( $admin['p_admin'] == 1 );
}

function adesk_admin_ismain() {
	$admin = adesk_admin_get();
	return ( adesk_admin_isadmin() && adesk_admin_isauth(1) );
}

function adesk_admin_ismaingroup() {
	if ( !adesk_admin_isadmin() ) return false;
	$admin = adesk_admin_get();
	if ( isset($admin['groups']) ) return isset($admin['groups'][3]);
	return adesk_admin_ismain();
}

function adesk_admin_isuser() {
	$admin = adesk_admin_get();
	if ( !isset($admin['id']) ) return false;
	if ( $admin['id'] < 1 ) return false;
	if ( !isset($admin['p_admin']) ) return false;
	return ( $admin['p_admin'] != 1 );
}

function adesk_admin_isguest() {
	$admin = adesk_admin_get();
	if ( !isset($admin['id']) ) return true;
	if ( $admin['id'] == 0 ) return true;
	return false;
}


// Just return one of the attributes of the admin array.

function adesk_admin_attr($attr) {
    $key = adesk_prefix("aweb_admin");
    if (!isset($_SESSION[$key])) {
        $ary = adesk_admin_get();

        if (!$ary)
            return false;

        return $ary[$attr];
    }

    return $_SESSION[$key][$attr];
}

function adesk_admin_parent_of(&$admin) {
    if (isset($admin['parentid'])) {
        if ($admin['parentid'] == 0)
            return intval($admin['id']);
        else
            return intval($admin['parentid']);
    } else {
        return intval($admin['id']);
    }
}

function adesk_admin_list($parentid = 0, $fieldName = 'parentid') {
    $admin = adesk_admin_get();

    if (!$admin)
        return array();

    $allAdmins = array();

    $sql = adesk_ihook('adesk_admin_list_query', $admin);

    while( $row = mysql_fetch_assoc($sql) ) {
        if ($parentid > 0 && isset($row[$fieldName]) && $row[$fieldName] != $parentid && $row['id'] != $parentid)
            continue;

        // fetch user from global auth table
        $userObj = adesk_auth_record_id($row['absid']);
        if ( $userObj != null ) {
            $row['fullname'] = $row['firstname'] = $userObj['first_name'];
            $row['lastname'] = $userObj['last_name'];
            if ( $row['lastname'] != '' ) $row['fullname'] .= ' ' . $row['lastname'];

            $allAdmins[$row['id']] = adesk_sql_unescape_array($row);
        } else {
            $row['fullname'] = '';
            $row['lastname'] = '';
            $row['firstname'] = '';
        }
    }

    return $allAdmins;
}

function adesk_admin_lookup($user, $email, $type = 'admin', $action = 'account_lookup') {
	$GLOBALS['_assets_post_result'] = false;
    // assume there is an error
    $message = _a("User information invalid.");

    if (function_exists('ishosted') && ishosted()) {
        $globalUser = adesk_auth_hosted_record_user_email($user, $email);
        if ($globalUser === null)
            return $message;
    } else {
        $globalUser = adesk_auth_record_user_email($user, $email);
        if ($globalUser === null)
            return $message;
    }

    $absid = $globalUser['id'];

    // fetch user info
    $table = ( adesk_site_ismodern() ? 'user' : 'admin' );
    $log = adesk_sql_query("SELECT * FROM `#$table` WHERE `absid` = '$absid'");
    $log_num = adesk_sql_num_rows($log);

    // user not found
    if ( !$log_num ) return $message;

    // user found
	$GLOBALS['_assets_post_result'] = true;
    // fetch him
    $user_info = adesk_sql_fetch_assoc($log);
    $user_info['username'] = $globalUser['username'];
    $user_info['password'] = $globalUser['password'];
    $user_info['first_name'] = $globalUser['first_name'];
    $user_info['last_name'] = $globalUser['last_name'];
    $user_info['email'] = $globalUser['email'];
    $user_info['full_name'] = implode(' ', array(trim($user_info['first_name']), trim($user_info['last_name'])));
    // link to admin home
	if ($type == 'admin')
		$site_link = adesk_site_alink();
	else
		$site_link = adesk_site_plink();
    $s1 = base64_encode($user_info['password']);
    $s2 = base64_encode($user_info['username']);
    $s3 = $user_info['absid'] + 728134;
    $string = urlencode($s1 . $s2);
    // link to change pass
    $site_link .= "/index.php?action=$action&r=$s3&r2=$string";
    $site       = adesk_site_get();
    $subject    = _a('Password reset request.');
    // call smarty to make an e-mail body
    $smarty = new adesk_Smarty( adesk_site_isvisualedit() ? 'public' : 'admin', true);
    // assign link to template
    $smarty->assign('site', $site);
    $smarty->assign('user', $user_info);
    $smarty->assign('site_link', $site_link);
    $text = $smarty->fetch('account_lookup.tpl.txt');
    require_once awebdesk_functions('mail.php');
    if (isset($site['sname'])) {
        adesk_mail_send('text', $site['sname'], $site['awebdesk_from'], $text, $subject, $email, $user_info['full_name']);
    }
    else {
    		$email_from = ( isset($site['emfrom']) ) ? $site['emfrom'] : $email;
        adesk_mail_send('text', _a("Password Lookup"), $email_from, $text, $subject, $email, $user_info['full_name']);
    }
    $message = _a("Please check your email to confirm your password resetting.");
    return $message;
}

function adesk_admin_resetpass($r, $r2, $section = 'admin') {
    // assume there is an error
    $message = _a("User information invalid.");
    // escape posted data
    $id = (int)$r - 728134;
    // try to find a user
    $user_info = adesk_auth_record_id($id);
    // user found
    if ( $user_info ) {
        // additional check
        $s1 = base64_encode($user_info['password']);
        $s2 = base64_encode($user_info['username']);
        $string = $s1 . $s2;
        // additional check passed

        if ( $string == $r2 ) {
            //define new pass
            $token = md5(time() . $_SERVER['REMOTE_ADDR']);
            $pass = substr($token, 0, 7);
            $passec = md5($pass);
			mysql_query("UPDATE aweb_globalauth SET password = '$passec' WHERE id = '$user_info[id]'", $GLOBALS["auth_db_link"]);
	        $user_info['full_name'] = implode(' ', array(trim($user_info['first_name']), trim($user_info['last_name'])));
            // prepare to send e-mail
            $email = $user_info['email'];
            // If they request password reset from admin or public side, direct them back to the appropriate section
            $site_link = ($section == 'admin') ? adesk_site_alink() : adesk_site_plink();
            $subject = _a('Your new password.');
            $site = adesk_site_get();
            // prepare Smarty for mail body
            $smarty = new adesk_Smarty(adesk_site_isvisualedit() ? 'public' : 'admin', true);
            // assign link to template
            $smarty->assign('site', $site);
            $smarty->assign('user', $user_info);
            $smarty->assign('site_link', $site_link);
            // assign new password
            $smarty->assign('pass', $pass);
            $text = $smarty->fetch('account_newpass.tpl.txt');
            require_once awebdesk_functions('mail.php');
            if (isset($site['sname']))
                adesk_mail_send('text', $site['sname'], $site['awebdesk_from'], $text, $subject, $email, $user_info['full_name']);
            else
                adesk_mail_send('text', _a("Password Reset"), $email, $text, $subject, $email, $user_info['full_name']);
            $message = _a("Password Reset. Please check your email for the new password.");
        }
    }
    return $message;
}

function adesk_admin_failures($ip) {
    $s_date = date("Y-m-d");
    $s_time = date("H:i:s");
    list($year, $month, $day) = explode("-", $s_date);
    list($hours, $minutes, $seconds) = explode(":", $s_time);
    $time_cutoff = date("H:i:s", mktime($hours, $minutes - 5, $seconds, $month, $day, $year));

    $sql = adesk_sql_query("SELECT * FROM `#admin_b_log` WHERE `ip` = '$ip' AND `date` = '$s_date' AND `time` >= '$time_cutoff'");

    if (!$sql)
        die(mysql_error());

    return adesk_sql_num_rows($sql);
}

function adesk_admin_marktime($where) {
	if ( adesk_site_isknowledgebuilder() || adesk_site_issupporttrio3() ) {
	    $ary = array(
	        '=last_login' => 'NOW()',
	    );
	} elseif ( adesk_site_isAEM5() ) {
	    $ary = array(
	        '=last_login' => 'NOW()',
	        '=ldate' => 'CURDATE()',
	        '=ltime' => 'CURTIME()',
	    );
	} else {
	    $ary = array(
	        '=ldate' => 'CURDATE()',
	        '=ltime' => 'CURTIME()',
	    );
	}

	$table = ( adesk_site_isknowledgebuilder() || adesk_site_isAEM5() || adesk_site_issupporttrio3() ? '#user' : '#admin' );
    adesk_sql_update($table, $ary, $where);

    $absids = adesk_sql_select_list("SELECT absid FROM $table WHERE $where");
    if ( $absids ) {
    	$absidslist = implode("', '", $absids);
		mysql_query("UPDATE aweb_globalauth SET last_login = NOW() WHERE id IN ('$absidslist')", $GLOBALS['auth_db_link']);

    }
}

function adesk_admin_logfailure($user, $pass, $ip, $host) {
    $ary = array(
        'user' => $user,
        'pass' => $pass,
        'ip'   => $ip,
        'host' => $host,
        '=time' => 'CURTIME()',
        '=date' => 'CURDATE()',
    );

    adesk_sql_insert("#admin_b_log", $ary);
}

function adesk_admin_check_details($user, $pass) {
    $encpass = md5($pass);
    $log_num = adesk_sql_select_one("COUNT(*) FROM #admin WHERE user = '$user' AND pass = '$encpass'");

    if ( $log_num == 1 ) {
        adesk_admin_marktime("user = '$user'");
        return true;
    }

    return false;
}

function adesk_admin_create($absid, $user, $email, $parentid = 0) {
    $site = adesk_site_get();
    $ary = array(
        'absid'       => $absid,
        'lang'        => $site['lang'],
        '=date_added' => 'CURDATE()',
        'parentid'    => $parentid,
        'user'        => $user,
        'email'       => $email,
    );

    adesk_sql_insert("#admin", $ary);

    $ary = array(
        'id' => adesk_sql_insert_id(),
    );

    adesk_sql_insert("#admin_p", $ary);
}

function adesk_admin_has_absid($absid) {
    $absid = intval($absid);
    $count = adesk_sql_select_one("SELECT COUNT(*) FROM `#admin` WHERE `absid` = '$absid'");

    return $count > 0;
}

function adesk_admin_set($authId) {
	$auth = adesk_auth_record_id($authId);
    $key = adesk_auth_format($auth, $auth['id']);
    $_SESSION["globalauth_".$key] = true;
    adesk_auth_set_cookie(adesk_AUTH_COOKIE, $key, 0);
    $_SESSION[adesk_prefix("aweb_admin")] = adesk_admin_get_totally_unsafe($authId, 'absolute');
    return $_SESSION[adesk_prefix("aweb_admin")];
}

?>