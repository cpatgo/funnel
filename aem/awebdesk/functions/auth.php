<?php
// auth.php

// Functions to authenticate users via the aweb_globalauth table, and to
// store auth credentials in cookies.

require_once dirname(__FILE__) . "/sql.php";
require_once dirname(__FILE__) . "/prefix.php";
require_once dirname(__FILE__) . "/http.php";
require_once dirname(__FILE__) . "/auth_h.php";

$authPath = dirname(dirname(dirname(__FILE__))) . "/manage";
if ( defined('adesk_KB_STANDALONE') and !adesk_KB_STANDALONE ) {
	$authPath = dirname(adesk_basedir()) . '/manage';
} elseif ( isset($GLOBALS['updaterStep']) and isset($GLOBALS['doUpdate']) ) {
	if ( $GLOBALS['subapp'] ) $authPath = dirname(adesk_basedir()) . '/manage';
}
require_once $authPath . '/config.inc.php';

if ( !defined("adesk_AUTH_USERP_PASSP") ) define("adesk_AUTH_USERP_PASSP", 0);
if ( !defined("adesk_AUTH_USERM_PASSP") ) define("adesk_AUTH_USERM_PASSP", 1);
if ( !defined("adesk_AUTH_USERM_PASSM") ) define("adesk_AUTH_USERM_PASSM", 2);

function _adesk_auth_var($name) {
    return adesk_http_param($name);
}

function adesk_auth_ok() {
	adesk_auth_define_cookie();
    $id = adesk_auth_id();

    if ($id < 0)
        return adesk_auth_failed();

    $cval = $_COOKIE[adesk_AUTH_COOKIE];
    if (adesk_auth_ok_session($cval))
        return true;

    // If we get to this point, we'll have to ask the sql server if this
    // user is ok.

    if (!($fmt = adesk_auth_has_userid($id)))
        return adesk_auth_failed();

    if ($fmt == $cval)
        return true;

    return adesk_auth_failed();
}

function adesk_auth_record_id($id = 0) {
    if (!adesk_auth_isconnected())
        adesk_auth_connect();

	$id = intval($id);
    $rs = mysql_query("SELECT *, NOW() AS a_now FROM `aweb_globalauth` WHERE `id` = ".$id, $GLOBALS["auth_db_link"]);
    if (mysql_num_rows($rs) > 0)
        $row = mysql_fetch_assoc($rs);
    else
        return null;

    if (!$row)
        return null;

    return $row;
}

function adesk_auth_record_username($username = '') {
    if (!adesk_auth_isconnected())
        adesk_auth_connect();

	$username = adesk_auth_escape($username);
    $rs = mysql_query("SELECT *, DATABASE(), NOW() AS a_now FROM `aweb_globalauth` WHERE `username` = '$username'", $GLOBALS["auth_db_link"]);
    if (mysql_num_rows($rs) > 0)
        $row = mysql_fetch_assoc($rs);
    else
        return null;

    if (!$row)
        return null;

    return $row;
}

function adesk_auth_record_email($email, $allrows = false) {
	if (!adesk_auth_isconnected())
	    adesk_auth_connect();

	$email = adesk_auth_escape($email);
	$rs = mysql_query("SELECT *, NOW() AS a_now FROM `aweb_globalauth` WHERE `email` = '".$email."'", $GLOBALS["auth_db_link"]);
	if (mysql_num_rows($rs) > 0) {
		if ($allrows) {
			$rval = array();

			while ($row = mysql_fetch_assoc($rs))
				$rval[] = $row;

			return $rval;
		} else
			$row = mysql_fetch_assoc($rs);
	} else
	    return null;

	if (!$row)
	    return null;

	return $row;
}

function adesk_auth_record_user_email($user, $email) {
	$user = adesk_auth_escape($user);
	$email = adesk_auth_escape($email);
    $id = adesk_sql_select_one("id", "aweb_globalauth", "`username` = '".$user."' AND `email` = '".$email."'", true);

    if ($id == false)
        return null;

    return adesk_auth_record_id($id);
}

function adesk_auth_record() {
    if (!adesk_auth_ok())
        return null;
    return adesk_auth_record_id(adesk_auth_id());
}

function adesk_auth_sql($mode, $user, $pass) {
    $where = '0';
    switch ($mode) {
        case adesk_AUTH_USERP_PASSP:
            $where = "`username` = '".$user."' AND MD5(`password`) = '".$pass."'";
            break;
        case adesk_AUTH_USERM_PASSP:
            $where = "MD5(`username`) = '".$user."' AND MD5(`password`) = '".$pass."'";
            break;
        case adesk_AUTH_USERM_PASSM:
            $where = "MD5(`username`) = '".$user."' AND `password` = '".$pass."'";
            break;
        default:
            break;
    }

    return "
        SELECT
            `id`
        FROM
            `aweb_globalauth`
        WHERE " . $where;
}

function adesk_auth_define_cookie() {
    if (!defined("adesk_AUTH_COOKIE"))
        define("adesk_AUTH_COOKIE", adesk_prefix("aweb_globalauth_cookie"));
}

function adesk_auth_cookie_format($prefix) {
	return $prefix . "aweb_globalauth_cookie";
}

function adesk_auth_id($mode = adesk_AUTH_USERP_PASSP) {
    if (!adesk_auth_isconnected())
        adesk_auth_connect();

    adesk_auth_define_cookie();

    if (!isset($_COOKIE[adesk_AUTH_COOKIE])) {
        $user = adesk_auth_escape(_adesk_auth_var("user"));
        $pass = adesk_auth_escape(_adesk_auth_var("pass"));

        if (!$user || !$pass)
            return -1;

        $rs = mysql_query(adesk_auth_sql($mode, $user, $pass), $GLOBALS["auth_db_link"]);

        if ($row = mysql_fetch_assoc($rs)) {
            $_COOKIE[adesk_AUTH_COOKIE] = adesk_auth_format($row, $row['id']);
            return $row['id'];
        }
        return -1;
    }

    return intval(substr($_COOKIE[adesk_AUTH_COOKIE], 32));
}

function adesk_auth_format(&$row, $id) {
    if (!$row)
        return "";

    return md5("aweb_" . $row["username"]) . $id;
}

function adesk_auth_failed() {
	adesk_auth_define_cookie();
    @setcookie(adesk_AUTH_COOKIE, "", time() - 3600, "/");
    unset($_COOKIE[adesk_AUTH_COOKIE]);

    require_once dirname(__FILE__) . "/ihook.php";
    adesk_ihook('adesk_auth_logout_post');

    return false;
}

function adesk_auth_has_userid($id) {
    if (!adesk_auth_isconnected())
        adesk_auth_connect();

    $res = mysql_query("SELECT username, password FROM aweb_globalauth WHERE id = ".(int)$id, $GLOBALS["auth_db_link"]);
    $cnt = mysql_num_rows($res);

    if ($cnt == 1) {
        $row = mysql_fetch_assoc($res);
        $ret = adesk_auth_format($row, $id);
        unset($row);
    } else {
        $ret = false;
    }

    mysql_free_result($res);
    return $ret;
}

function adesk_auth_ok_session($cval) {
    $key = "globalauth_".$cval;

    if (!isset($_SESSION[$key]))
        return false;
    return $_SESSION[$key] ? true : false;
}

function adesk_auth_isconnected() {
    return isset($GLOBALS["auth_db_link"]);
}

function adesk_auth_connect() {
    if (!defined("AWEBP_AUTHDB_SERVER") || !defined("AWEBP_AUTHDB_USER") || !defined("AWEBP_AUTHDB_PASS"))
        return null;

    if (defined("AWEBP_DB_HOST") ) {
    	if ( AWEBP_DB_HOST == AWEBP_AUTHDB_SERVER && AWEBP_DB_USER == AWEBP_AUTHDB_USER && AWEBP_DB_PASS == AWEBP_AUTHDB_PASS && AWEBP_DB_NAME == AWEBP_AUTHDB_DB ) {
    		$GLOBALS['auth_db_link'] =& $GLOBALS['db_link'];
    		return $GLOBALS['auth_db_link'];
    	}
    }
    $GLOBALS["auth_db_link"] = adesk_auth_connect_args(AWEBP_AUTHDB_SERVER, AWEBP_AUTHDB_USER, AWEBP_AUTHDB_PASS, AWEBP_AUTHDB_DB);

	if (isset($GLOBALS["adesk_auth_utf8"]))
		mysql_query("SET NAMES 'utf8'", $GLOBALS["auth_db_link"]);
    return $GLOBALS["auth_db_link"];
}

function adesk_auth_connect_args($server, $user, $pass, $dbname) {
    if (!defined("AWEBP_ENGINE_SERVER") || $GLOBALS['db_link_crc'] != $GLOBALS['auth_db_link_crc']) {
		$db = mysql_connect($server, $user, $pass, true);
		if (!$db) {
			if (isset($GLOBALS["_hosted_account"]))
				die("Error (1003): We're having some difficulty connecting to your database; we're already looking into the problem; please contact us if you have any concerns.");
			else
				die("Unable to connect to your authentication database; please ensure that the information held in /manage/config.inc.php is correct.");
		}
        mysql_select_db($dbname, $db) or die("Unable to select database $dbname after connecting to MySQL: " . adesk_auth_sql_error());
    } else {
        $db = $GLOBALS['db_link'];
    }
    return $db;
}

function adesk_auth_disconnect() {
    if (adesk_auth_isconnected())
        mysql_close($GLOBALS['auth_db_link']);
}

function adesk_auth_login_md5($user, $pass, $remember = false) {
    if (!adesk_auth_isconnected())
        adesk_auth_connect();

    adesk_auth_define_cookie();

	$user = adesk_auth_escape($user);
	$pass = adesk_auth_escape($pass);

	$rs = mysql_query("
		SELECT
			*
		FROM
			`aweb_globalauth`
		WHERE
			BINARY `username` = '$user'
		AND
			BINARY `password` = '$pass'
	", $GLOBALS["auth_db_link"]);

	$auth = mysql_fetch_assoc($rs);

	if ($auth == false)
		return adesk_auth_failed();

    $key = adesk_auth_format($auth, $auth['id']);
    $_SESSION["globalauth_".$key] = true;
    adesk_auth_set_cookie(adesk_AUTH_COOKIE, $key, $remember);

    require_once dirname(__FILE__) . "/ihook.php";
    adesk_ihook('adesk_auth_login_post', $remember);
    return true;
}

function adesk_auth_login_source($user, $pass, $remember = false) {
    if (!adesk_auth_isconnected())
        adesk_auth_connect();

    adesk_auth_define_cookie();

	if (!$GLOBALS["loginsource"]->auth($user, $pass)) {
		return adesk_auth_failed();
	}

	$auth = $GLOBALS["loginsource"]->c_info;

    $key = adesk_auth_format($auth, $auth['id']);
    $_SESSION["globalauth_".$key] = true;
    adesk_auth_set_cookie(adesk_AUTH_COOKIE, $key, $remember);

    require_once dirname(__FILE__) . "/ihook.php";
    adesk_ihook('adesk_auth_login_post', $remember);
    return true;
}

function adesk_auth_set_cookie($cookie, $key, $remember) {
    if (@setcookie($cookie, $key, ($remember ? time() + 1296000 : 0), "/"))
        $_COOKIE[$cookie] = $key;
    else
        unset($_COOKIE[$cookie]);
}

function adesk_auth_login_plain($user, $pass, $remember = false) {
	if (isset($GLOBALS["loginsource"]))
		return adesk_auth_login_source($user, $pass, $remember);

    return adesk_auth_login_md5($user, md5($pass), $remember);
}

function adesk_auth_logout() {
    adesk_auth_failed();
}

function adesk_auth_delete($id) {
	$id = intval($id);
    return adesk_sql_delete("aweb_globalauth", "`id` = ".$id, true);
}

function adesk_auth_create_array($ary, $encodePassword = true) {
	return adesk_auth_create($ary['username'], $ary['password'], $ary['first_name'], $ary['last_name'], $ary['email'], $encodePassword);
}

function adesk_auth_create($user, $pass, $fname, $lname, $email, $encodePassword = true) {
    if (!adesk_auth_isconnected())
        adesk_auth_connect();

    if ( $encodePassword ) $pass = md5($pass);

	$user = adesk_auth_escape($user);
	$fname = adesk_auth_escape($fname);
	$lname = adesk_auth_escape($lname);
	$email = adesk_auth_escape($email);

    mysql_query("
        INSERT INTO aweb_globalauth
            (username, password, first_name, last_name, email)
        VALUES
            ('$user', '$pass', '$fname', '$lname', '$email')
    ", $GLOBALS["auth_db_link"]);

	$newid = mysql_insert_id($GLOBALS["auth_db_link"]);

	if ($auth = adesk_auth_record_id($newid)) {
		adesk_auth_productset_add($newid);
	}

	return $newid;
}

function adesk_auth_product_update($authid, $set) {
	$set = adesk_auth_escape($set);
	$authid = (int)$authid;
	mysql_query("UPDATE aweb_globalauth SET productset = '$set' WHERE id = '$authid'", $GLOBALS["auth_db_link"]);
}

function adesk_auth_productset_add($authid) {
	if (!isset($GLOBALS["adesk_app_id"]))
		return;

	$auth = adesk_auth_record_id($authid);

	if (!$auth)
		return;

	if (!in_array("productset", array_keys($auth)))
		return;

	$set = adesk_auth_product_add($auth["productset"], $GLOBALS["adesk_app_id"]);
	adesk_auth_product_update($authid, $set);
}

function adesk_auth_productset_remove($authid) {
	if (!isset($GLOBALS["adesk_app_id"]))
		return;

	$auth = adesk_auth_record_id($authid);

	if (!$auth)
		return;

	if (!in_array("productset", array_keys($auth)))
		return;

	$set = adesk_auth_product_remove($auth["productset"], $GLOBALS["adesk_app_id"]);

	if ($set == "")
		adesk_auth_delete($authid);
	else
		adesk_auth_product_update($authid, $set);
}

function adesk_auth_product_add($set, $id) {
	if ($set == "")
		return $id;

	$ary = explode(",", $set);
	$ary[] = $id;

	return implode(",", $ary);
}

function adesk_auth_product_remove($set, $id) {
	if ($set == "")
		return "";

	$ary = explode(",", $set);
	if (!in_array($id, $ary))
		return $set;

	if (count($ary) == 1)
		return "";

	$key = array_search($id, $ary);
	unset($ary[$key]);

	return implode(",", $ary);
}

function adesk_auth_update(&$ary, $id) {
    $tmp = array();

    if (isset($ary['username']))
        $tmp['username'] = $ary['username'];
    if (isset($ary['password']))
        $tmp['password'] = $ary['password'];
    if (isset($ary['first_name']))
        $tmp['first_name'] = $ary['first_name'];
    if (isset($ary['last_name']))
        $tmp['last_name'] = $ary['last_name'];
    if (isset($ary['email']))
        $tmp['email'] = $ary['email'];

	if (isset($ary['sourceid'])) {
		
		
		
		
		$tmp['sourceid'] = intval($ary['sourceid']);
		
		//tweak to update users source
		if (isset($ary['new_product_id']))
        $tmp['sourceid'] = $ary['new_product_id'];
		
		
		$tmp['=sourceupdated'] = 'NOW()';
	}

	if ( count($tmp) == 0 ) return false;

	# This escapes for us
    $setstr = adesk_sql_set_str($tmp, true);

    if (!adesk_auth_isconnected())
        adesk_auth_connect();

    return mysql_query("
        UPDATE
            `aweb_globalauth`
        SET
            $setstr
        WHERE
            `id` = '$id'
    ", $GLOBALS["auth_db_link"]);
}

function adesk_auth_escape($string, $useInLike = false) {
	if (!adesk_auth_isconnected())
		adesk_auth_connect();
    if (is_array($string)) {
        adesk_auth_escape_array($string);
        return $string;
    }
    if (version_compare(phpversion(), "4.3.0") == "-1") {
        $string = mysql_escape_string($string);
    } else {
        $string = mysql_real_escape_string($string, $GLOBALS["auth_db_link"]);
    }
    if ( $useInLike ) $string = addcslashes($string, '%_');
    return $string;
}

function adesk_auth_escape_array(&$ary) {
    foreach ($ary as $key => $val) {
        if (is_array($val))
            adesk_auth_escape_array($ary[$key]);
        else
            $ary[$key] = adesk_auth_escape($val);
    }
    return $ary;
}

function adesk_auth_hash_encode($userID, $userName, $serial, $admin = false) {
	// this function should return hash of these 3 values
	$delimiter = '*|*';
	$order = array(6, 3, 1, 4, 2, 5);
	$original = $userID . $delimiter . $userName . $delimiter . md5($serial) . $delimiter . (int)$admin;
	$encoded = base64_encode($original);
	$length = strlen($encoded);
	$chunkSize = ceil($length / 6);
	$chunks = array();
	while ( strlen($encoded) > 0 ) {
		$chunks[] = substr($encoded, 0, $chunkSize);
		$encoded = substr($encoded, $chunkSize);
	}
	$hash = '';
	foreach ( $order as $o ) $hash .= $chunks[ $o - 1 ];
	return urlencode($hash);
}

function adesk_auth_hash_decode($hash) {
	// this function should return false if no match or userInfo array if user is found
	$delimiter = '*|*';
	$order = array(6, 3, 1, 4, 2, 5);
	$hash = urldecode($hash);
	$length = strlen($hash);
	$chunkSize = ceil($length / 6);
	$lastPart = $length - $chunkSize * 5;
	$chunks = array();
	// first deal with last part
	$chunks[] = substr($hash, 0, $lastPart);
	$hash = substr($hash, $lastPart);
	while ( strlen($hash) > 0 ) {
		$chunks[] = substr($hash, 0, $chunkSize);
		$hash = substr($hash, $chunkSize);
	}
	$encoded = '';
	for ( $i = 1; $i < 7; $i++ ) {
		$pos = array_search($i, $order);
		if ( !isset($chunks[$pos]) ) return false;
		$encoded .= $chunks[$pos];
		//echo $pos . ' => ' . $i . ', ';
	}
	if ( strlen($encoded) != $length ) return false;
	$original = base64_decode($encoded);
	$arr = explode($delimiter, $original);
	if ( !isset($arr[3]) ) return false;
	$table = ( $arr[3] == 1 ? 'admin' : 'users' );
	$sql = adesk_sql_query("
		SELECT
			auth.*,
			cfg.serial
		FROM
			#$table auth,
			#backend cfg
		WHERE
			auth.id = '$arr[0]'
		AND
			auth.user = '$arr[1]'
		AND
			MD5(cfg.serial) = '$arr[2]'
		AND
			cfg.rss = 1
	") or die(mysql_error($GLOBALS['db_link']));
	if ( mysql_num_rows($sql) != 1 ) return false;
	$userInfo = mysql_fetch_assoc($sql);
	unset($userInfo['serial']);
	$userInfo['admin'] = (bool)$arr[3];
	return $userInfo;
}

function adesk_auth_search($string, $format = '%%%s%%', $fields = array()) {
	$escaped = sprintf($format, adesk_auth_escape($string, true));
	if ( !is_array($fields) or !count($fields) ) $fields = array('username', 'first_name', 'last_name', 'email');
	$conds = array();
	foreach ( $fields as $v ) {
		$conds[] = "`$v` LIKE '$escaped'";
	}
	if ( !$conds ) $conds = array(1);
	$cond = implode(" OR ", $conds);
	$r = array();
	$query = "
		SELECT
			*
		FROM
			`aweb_globalauth`
		WHERE
			$cond
		ORDER BY
			`username`
	";
	if ( !adesk_auth_isconnected() ) {
		adesk_auth_connect();
	}
	$sql = mysql_query($query, $GLOBALS["auth_db_link"]);
	while ( $row = mysql_fetch_assoc($sql) ) {
		$r[$row['id']] = $row;
	}
	return $r;
}

function adesk_auth_sql_error() {
	return mysql_error($GLOBALS['auth_db_link']);
}

function adesk_auth_sql_error_number() {
	return mysql_errno($GLOBALS['auth_db_link']);
}

?>
