<?php
# auth_h.php

# Auth for hosted environments

function adesk_auth_hosted_login_md5($user, $pass, $acct, $remember = false) {
    if (!adesk_auth_isconnected())
        adesk_auth_connect();

    adesk_auth_define_cookie();

    $acct = intval($acct);

    $rs = mysql_query("
        SELECT
            *
        FROM
            `aweb_globalauth`
        WHERE
            `username` = '$user'
        AND `password` = '$pass'
        AND `acctid` IN ('0', '$acct')
    ", $GLOBALS["auth_db_link"]);

    $auth = mysql_fetch_assoc($rs);

    if ($auth == false)
        return adesk_auth_failed();

    $key = adesk_auth_format($auth, $auth['id']);
    $_SESSION["globalauth_".$key] = true;
    if (setcookie(adesk_AUTH_COOKIE, $key, ($remember ? time() + 1296000 : 0), "/"))
        $_COOKIE[adesk_AUTH_COOKIE] = $key;
    else
        unset($_COOKIE[adesk_AUTH_COOKIE]);
    return true;
}

function adesk_auth_hosted_login_plain($user, $pass, $acct, $remember = false) {
    return adesk_auth_hosted_login_md5($user, md5($pass), $acct, $remember);
}

# Function: adesk_auth_hosted_create
#
# Create a hosted account user following the convention that the hosted user's record in aweb_globalauth is related to 
# their parent user via the "acctid" column.  Otherwise the same as <adesk_auth_create>.

function adesk_auth_hosted_create($user, $pass, $fname, $lname, $email, $acct) {
    if (!adesk_auth_isconnected())
        adesk_auth_connect();

    $pass = md5($pass);
    mysql_query("
        INSERT INTO aweb_globalauth
            (username, password, first_name, last_name, email, acctid)
        VALUES
            ('$user', '$pass', '$fname', '$lname', '$email', '$acct')
    ", $GLOBALS["auth_db_link"]);

    return mysql_insert_id($GLOBALS["auth_db_link"]);
}

function adesk_auth_hosted_parent_absid($email) {
    $parentid = adesk_sql_select_one("SELECT `id` FROM `#internal_hosted` WHERE `account` = '".$email."'");

    if (!$parentid)
        return false;

    return adesk_sql_select_one("SELECT `absid` FROM `#admin` WHERE `id` = '$parentid'");
}    

function adesk_auth_hosted_record_user_email($user, $email) {
    $email  = adesk_sql_escape($email);
    $user   = adesk_sql_escape($user);
	$account = adesk_sql_escape(strval(adesk_http_param("account")));
    $lhs    = adesk_auth_hosted_parent_absid($account);

    if (!$lhs) {
		$id = adesk_sql_select_one("id", "aweb_globalauth", "`username` = '".$user."' AND `email` = '".$email."'");
	} else {
		$user_combo = sprintf("%d_%s", $lhs, $user);
		$id = adesk_sql_select_one("id", "aweb_globalauth", "(`username` = '$user_combo') AND `email` = '".$email."'");
	}

    if ($id == false)
        return null;

    return adesk_auth_record_id($id);
}

?>
