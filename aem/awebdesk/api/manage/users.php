<?php

require_once awebdesk_functions('sql.php');
require_once awebdesk_functions('auth.php');

function users_select($type, $parentid, $id = 0) {
    $id = intval($id);

    if (!adesk_auth_isconnected())
        adesk_auth_connect();

    if ($parentid > 0) {
        $mine = adesk_sql_select_list($q = "
            SELECT
                `absid`
            FROM
                `#admin`
            WHERE
                `parentid` = '$parentid'
            OR  `id`       = '$parentid'
		");
    } elseif ($parentid == 0) {
        $mine = array();
    } else {
        $mine = adesk_sql_select_list("
            SELECT
                `absid`
            FROM
                `#admin`
        ");
    }

    $ary = array();

    switch ($type) {
        case 'one':
            $rs = mysql_query("
                SELECT
                    *
                FROM
                    `aweb_globalauth`
                WHERE
                    `id` = '$id'
                AND `id` IN ('".implode("','", $mine)."')
			", $GLOBALS['auth_db_link']);

            if ($row = mysql_fetch_assoc($rs)) {
                return $row;
            }
            break;

        case 'list':
            $rs = mysql_query("
                SELECT
                    auth.*
                FROM
                    `aweb_globalauth` auth
                WHERE
                    `id` IN ('".implode("','", $mine)."')
                ", $GLOBALS['auth_db_link']);

            while ($row = mysql_fetch_assoc($rs)) {
                $row['parentid'] = intval(adesk_sql_select_one("SELECT parentid FROM #admin WHERE absid = '$row[id]'"));
                $ary[] = $row;
            }

            return $ary;

        case 'list_global':
            $all = adesk_sql_select_list("SELECT `absid` FROM `#admin`");
            $rs  = mysql_query("
                SELECT
                    *
                FROM
                    `aweb_globalauth`
                WHERE
                    `id` NOT IN ('".implode("','", $all)."')
                ", $GLOBALS['auth_db_link']);

            while ($row = mysql_fetch_assoc($rs))
                $ary[] = $row;

            return $ary;

        default:
            break;
    }

    return array();
}

function users_select_count($parentid) {
    $parentid = intval($parentid);
    if ($parentid > 0) {
        return adesk_sql_select_one("
            SELECT
                COUNT(*)
            FROM
                `#admin`
            WHERE
                `parentid` = '$parentid'
            OR  `id`       = '$parentid'
        ");
    } else {
        return 0;
    }
}

function users_import($absid, $parentid = 0) {
    $user = adesk_auth_record_id($absid);

    require_once awebdesk_smarty_plugins('modifier.adesk_clear_prefix.php');

    $ary = array(
        "user"  => smarty_modifier_adesk_clear_prefix($user['username'], 'num'),
        "absid" => $absid,
        "parentid" => $parentid,
    );

    if ($parentid == 0)
        $ary["parentid"] = adesk_admin_parent_of($GLOBALS["admin"]);
    elseif ($parentid == -1)
        unset($ary["parentid"]);

    adesk_sql_insert("#admin", $ary);

    $ret = array(
        'id' => adesk_sql_insert_id()
    );

    adesk_ihook('adesk_users_assets_import', $ret['id'], $user);
    return $ret;
}

function users_update($absid, $parentid = 0) {
    $absid  = intval($absid);
    $user   = adesk_auth_record_id($absid);

    require_once awebdesk_smarty_plugins('modifier.adesk_clear_prefix.php');

    $ary = array(
        "user"      => smarty_modifier_adesk_clear_prefix($user['username'], 'num'),
        "parentid"  => $parentid,
    );

    if ($parentid == 0)
        $ary["parentid"] = $GLOBALS["admin"]["id"];
    elseif ($parentid == -1)
        unset($ary["parentid"]);

    adesk_sql_update("#admin", $ary, "`absid` = '$absid' AND `parentid` != '0'");
    $adminid = adesk_sql_select_one("id", "#admin", "`absid` = '$absid'");
    adesk_ihook('adesk_users_assets_update', $adminid);
}

function users_delete($absid) {
    $absid = intval($absid);
    $id    = adesk_sql_select_one("id", "#admin", "`absid` = '$absid'");
    adesk_sql_delete("#admin", "`id` = '$id'");
    adesk_ihook('adesk_users_assets_delete', $id, $absid);

    if (ishosted())
        adesk_sql_delete("aweb_globalauth", "`id` = '$absid'");

    return $id;
}

?>
