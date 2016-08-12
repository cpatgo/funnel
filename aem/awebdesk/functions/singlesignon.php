<?php
if(!session_id()) session_start();
// singlesignon.php

// single sign on functions

function adesk_sso_token_duration() {
	return 15; // minutes
}

function adesk_sso_token_generate($sso_addr, $sso_user, $sso_pass = '', $sso_duration = 15) {
	
	  /* Disabled for security reasons */
	return false;
	
	$user = false;
	$returnSelfIP = false;
	$sso_duration = (int)$sso_duration;
	if ( !$sso_duration ) $sso_duration = adesk_sso_token_duration();

	if ( !$sso_user ) {
		return adesk_ajax_api_result(false, _a("User not provided."));
	}

	if ( $sso_addr == '__self' ) {
		$sso_addr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
		$returnSelfIP = true;
	}
	if ( !adesk_str_is_ip($sso_addr) ) {
		return adesk_ajax_api_result(false, _a("IP Address not provided."));
	}

	if ( $GLOBALS['admin']['id'] != 1 and $sso_user == 'admin' ) {
		return adesk_ajax_api_result(false, _a("Invalid User provided."));
	}

	if ( $sso_pass and $sso_pass != md5('') ) {
		// log him out
		@adesk_auth_logout();
		$authenticated = adesk_auth_login_md5($sso_user, $sso_pass, false);
		if ( $authenticated ) {
			adesk_session_drop_cache();
			unset($GLOBALS['admin']);
			$user = $GLOBALS['admin'] = adesk_admin_get();
			$localID = $user['id'];
		}
	} else {
		$user = adesk_auth_record_username($sso_user);
		$user = adesk_ihook("adesk_admin_get_post", $user);
	}

	if ( !$user ) {
		return adesk_ajax_api_result(false, _a("User not found."));
	}
	$userid = $user['id'];
	if ( !$userid ) {
		return adesk_ajax_api_result(false, _a("Error with user record: User ID is 0."));
	}
	// Update database with current date/time for tracking of users last login
	$now = adesk_sql_select_one("SELECT NOW()");
	$end = adesk_sql_select_one("SELECT ADDDATE(NOW(), INTERVAL $sso_duration MINUTE)");
	$ipapp = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
	$token = "$userid||$sso_user||$sso_addr||$ipapp||$now";
	$hash = md5($token);

	$apphash = '';
	$apphashesc = adesk_sql_escape($apphash);

	adesk_sql_delete(
		"#sso_token",
		"userid = '$userid' AND userip = INET_ATON('$sso_addr') AND appip = INET_ATON('$ipapp') AND apphash = '$apphashesc'"
	);

	adesk_sql_delete("#sso_token", "edate < NOW()");

	$insert = array(
		'id' => 0,
		'token' => $hash,
		'cdate' => $now,
		'edate' => $end,
		'userid' => $userid,
		'=userip' => "INET_ATON('$sso_addr')",
		'=appip' => "INET_ATON('$ipapp')",
		'apphash' => $apphash,
	);
	adesk_sql_insert("#sso_token", $insert);

	$user['token'] = $hash;
	if ( $returnSelfIP ) $user['__self'] = $ipapp;

	return adesk_ajax_api_result(
		true,
		_a('User Found.'),
		$user
	);
}

function adesk_sso_token_eval($token) {
	$sso = trim((string)$token);
	$ssoesc = adesk_sql_escape($sso);
	$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
	$ipesc = adesk_sql_escape($ip);
	$user = adesk_sql_select_row("
		SELECT
			s.*,
			u.username
		FROM
			#user u,
			#sso_token s
		WHERE
			s.token = '$ssoesc'
		AND
			s.userip = INET_ATON('$ipesc')
		AND
			s.edate > NOW()
		AND
			s.userid = u.id
	");
	// first check if token is still valid
	if ( !$user ) return;
	$mask = $user['username'];
	$maskesc = adesk_auth_escape($mask);
	$pass2 = adesk_sql_select_one('password', 'aweb_globalauth', "`username` = '$maskesc'", true);

	// require all needed files
	require_once awebdesk_functions("loginsource.php");
	require_once awebdesk_classes("loginsource.php");
	adesk_loginsource_sync();
	$source = adesk_loginsource_determine($mask, $pass2, 1);
	if ($source !== false) {
		$GLOBALS["loginsource"] = new $source["_classname"]($source);
	} else {
		die("This should never happen.");
	}

	// log in as this user
	@adesk_auth_logout();
	unset($GLOBALS['admin']);
	adesk_session_drop_cache();
	$authenticated = adesk_auth_login_md5($mask, $pass2, false);
	if ( !$authenticated ) return;

	adesk_session_drop_cache();
	unset($GLOBALS['admin']);
	$GLOBALS['admin'] = adesk_admin_get();
	$localID = $GLOBALS['admin']['id'];
	$_SESSION['aem_uid'] = $localID;

	// application specific stuff
	require_once awebdesk_functions("tz.php");
	tz_checkdst("site");
	if (isset($GLOBALS['admin']["local_dst"]))
		tz_checkdst("admin");

	// Update database with current date/time for tracking of users last login
	adesk_sql_update_one("#user", "=last_login", "NOW()", "id = '$localID'");
	adesk_sql_update("#user", array("=ldate" => "NOW()", "=ltime" => "NOW()"), "id = '$localID'");
}

function adesk_sso_sameserver() {

	if (!isset($GLOBALS["loginsource"]) || !isset($GLOBALS["loginsource"]->c_info)) {
		return adesk_ajax_api_result(
			false,
			_a("User login invalid"),
			array(
				'id'       => 0,
				'absid'    => 0,
				'username' => "",
				'prfxs'    => "",
				'hash'     => "",
			)
		);
	}

	$auth = $GLOBALS["loginsource"]->c_info;
    $key = adesk_auth_format($auth, $auth['id']);

	$admin = adesk_admin_get();

	if ( !$admin['id'] ) {
		return adesk_ajax_api_result(false, _a('User not authenticated.'));
	}

	$prefixes = array(adesk_prefix());
	if ( adesk_site_issupporttrio3() ) $prefixes[] = 'kb_';

	return adesk_ajax_api_result(
		true,
		_a('User Logged In.'),
		array(
			'id' => $admin['id'],
			'absid' => $admin['absid'],
			'username' => $admin['username'],
			'prfxs' => implode('|', $prefixes),
			'hash' => $key,
		)
	);
}

?>
