<?php

// can't call hookcalls.php here because SESSION will just get overwritten when session_start() is called later
//require_once(adesk_admin('functions/hookcalls.php'));

require_once(awebdesk('functions/sql.php'));
require_once(awebdesk('functions/xml.php'));

// Execute PHP code in a set of hooks marked with $call, in order of
// their priority from highest to lowest.  Calls with identical
// priorities will execute in an undefined order.

function adesk_hook($call) {
    $res = adesk_sql_query("
        SELECT
            h.`code`,
            g.`status`
        FROM
            `#hooks` h,
            `#hooks_groups` g
        WHERE
            h.`call` = '".$call."'
        AND g.`id` = h.`groupid`
        ORDER BY h.`priority` DESC
    ");

    while ($row = adesk_sql_fetch_assoc($res)) {
        if ($row['status'] == 'enabled')
            eval(stripslashes($row['code']));
    }
}

function adesk_hook_conf($groupid, $key) {
    if (!isset($_SESSION['_adesk_hooks']) || !isset($_SESSION['_adesk_hooks'][$groupid]) || !isset($_SESSION['_adesk_hooks'][$groupid]['key:'.$key])) {
        if (!adesk_hook_enabled($groupid))
            return null;

        $res = adesk_sql_query("
            SELECT
                `key`,
                `value`
            FROM
                `#hooks_config`
            WHERE
                `groupid` = ".(int)$groupid
        );

        while ($row = adesk_sql_fetch_assoc($res))
            $_SESSION['_adesk_hooks'][$groupid]['key:'.$row['key']] = $row['value'];
    }

    return $_SESSION['_adesk_hooks'][$groupid]['key:'.$key];
}

function adesk_hook_conf_sys($key) {
    return adesk_hook_conf(0, $key);
}

function adesk_hook_group($groupid) {
    return adesk_sql_select_array("
        SELECT
            *
        FROM
            `#hooks_groups`
        WHERE
            `id` = ".(int)$groupid."
        AND `status` = 'enabled'
    ");
}

function adesk_hook_group_enabled($groupid) {
    return adesk_sql_select_one("status", "#hooks_groups", "`id` = ".intval($groupid)) == 'enabled';
}

function adesk_hook_sys_enabled() {
    return adesk_hook_conf_sys("status") == "enabled";
}

function adesk_hook_locations() {
    require_once(adesk_admin('functions/hookcalls.php'));
    return $_SESSION['_adesk_hook_calls'];
}

function adesk_hook_groups() {
    $ret = array("0" => "---");
    $ary = adesk_sql_select_array("
        SELECT
            `id`,
            `name`
        FROM
            `#hooks_groups`
    ");

    foreach ($ary as $row)
        $ret[$row['id']] = $row['name'];

    return $ret;
}

function adesk_hook_group_hooks($groupid) {
    return adesk_sql_select_list("SELECT id FROM `#hooks` WHERE `groupid` = ".(int)$groupid);
}

function adesk_hook_export($hookid) {
    $ary = adesk_sql_select_row("
        SELECT
            *
        FROM
            `#hooks`
        WHERE
            `id` = ".(int)$hookid
    );

    unset($ary['id']);
    $ary['code'] = base64_encode(stripslashes($ary['code']));

    return adesk_xml_write($ary, "", "hook");
}

function adesk_hook_import(&$ary) {
    if (!isset($ary['hook']))
        return false;

    $hook = $ary['hook'];
    if (!isset($hook['groupid'])
        || !isset($hook['description'])
        || !isset($hook['priority'])
        || !isset($hook['status'])
        || !isset($hook['code'])
        || !isset($hook['call']))
            return false;

    $hook['groupid'] = (int)$hook['groupid'];
    $hook['priority'] = (int)$hook['priority'];
    $hook['code'] = base64_decode($hook['code']);

    adesk_sql_insert("#hooks", $hook);
}

function adesk_hook_import_dep(&$ary) {
    if (!isset($ary['dep']))
        return false;

    $dep = $ary['dep'];

    if (!isset($dep['groupid'])
        || !isset($dep['deptype'])
        || !isset($dep['from'])
        || !isset($dep['to']))
            return false;

    $dep['groupid'] = (int)$dep['groupid'];

    adesk_sql_insert("#hooks_deps", $dep);
}

function adesk_hook_export_plugin($groupid) {
    $groupid = (int)$groupid;
    $grp   = adesk_sql_select_row("SELECT * FROM `#hooks_groups` WHERE `id` = ".$groupid);
    $deps  = adesk_sql_select_array("SELECT * FROM `#hooks_deps` WHERE `groupid` = ".$groupid);
    $hooks = adesk_sql_select_array("SELECT * FROM `#hooks` WHERE `groupid` = ".$groupid);

    $grp['install_pre'] = base64_encode(stripslashes($grp['install_pre']));
    $grp['install_post'] = base64_encode(stripslashes($grp['install_post']));
    for ($i = 0; $i < count($deps); $i++) {
        unset($deps[$i]['id']);
        $grp["dep-".$i] = $deps[$i];
    }

    for ($i = 0; $i < count($hooks); $i++) {
        unset($hooks[$i]['id']);
        $hooks[$i]['code'] = base64_encode(stripslashes($hooks[$i]['code']));
        $grp["hook-".$i] = $hooks[$i];
    }

    return adesk_xml_write($grp,"","plugin");
}

function adesk_hook_install_plugin($xml) {
    global $smarty;
    $top = adesk_xml_read($xml);

    if (!is_array($top))
        return false;

    if (isset($top["plugin"])) {
        $ary = $top["plugin"];
        $grp = array(
            "name"         => $ary["name"],
            "version"      => $ary["version"],
            "description"  => $ary["description"],
            "status"       => $ary["status"],
            "=ctime"       => "NOW()",
            "install_pre"  => base64_decode($ary["install_pre"]),
            "install_post" => base64_decode($ary["install_post"]),
        );

        adesk_sql_insert("#hooks_groups", $grp);
        $group_id = adesk_sql_insert_id();
        for ($i = 0;; $i++) {
            $key = "hook-" . $i;

            if (!isset($ary[$key]))
                break;

            $ary[$key]['groupid'] = $group_id;
            $hook = array("hook" => $ary[$key]);
            adesk_hook_import($hook);
        }

        eval($grp["install_pre"]);
        return true;
    } else {
        return false;
    }
}

function adesk_hook_uninstall_plugin($groupid) {
    $groupid = (int)$groupid;
    $ary = adesk_sql_select_row("SELECT * FROM `#hooks_groups` WHERE `id` = ".$groupid);

    if (!$ary)
        return;

    eval($ary["install_post"]);

    adesk_sql_delete("#hooks", "`groupid` = ".$groupid);
    adesk_sql_delete("#hooks_deps", "`groupid` = ".$groupid);
    adesk_sql_delete("#hooks_config", "`groupid` = ".$groupid);
    adesk_sql_delete("#hooks_groups", "`id` = ".$groupid);
}


function adesk_hook_assets(&$smarty, $mode) {
    if (!is_array($smarty->template_dir))
        $smarty->template_dir = array($smarty->template_dir, $smarty->_globalPath . '/templates');
    $smarty->assign('pageTitle', _a("Plugins"));

    if ($mode == "")
        $mode = "view";

    switch ($mode) {
            // plugins
        case 'view':
            require(awebdesk('assets/plugins_view_assets.php'));
            return new Plugins_View_assets();
        case 'add':
        case 'edit':
            require(awebdesk('assets/plugins_edit_assets.php'));
            return new Plugins_Edit_assets();
        case 'delete':
            if (isset($_GET["id"])) {
                $gid = intval($_GET["id"]);
				adesk_hook_uninstall_plugin($gid);
                $hookids = adesk_hook_group_hooks($gid);
                adesk_sql_delete("#hooks", "IN ('".implode($hookids)."')");
                adesk_sql_delete("#hooks_groups", "`id` = ".$gid);
                adesk_smarty_message($smarty, "The plugin has been successfully deleted.");
            }
            return adesk_hook_assets($smarty, "view");
        case 'import':
            $xml = file_get_contents($_FILES['file']['tmp_name']);
            $result = adesk_hook_install_plugin($xml);
            adesk_smarty_message($smarty, ($result ? "Your plugin was successfully installed." : "Could not import file because important data was missing."));
            return adesk_hook_assets($smarty, "view");
        case 'export':
            require(awebdesk('assets/plugins_export_assets.php'));
            return new Plugins_Export_assets();
            // dependancies
        case 'deldep':
        case 'adddep':
        case 'editdep':
            require(awebdesk('assets/deps_edit_assets.php'));
            return new Deps_Edit_assets();
            // hooks
        case 'importhook':
            $xml = file_get_contents($_FILES['file']['tmp_name']);
            $ary = adesk_xml_read($xml);
            adesk_hook_import($ary);
            return adesk_hook_assets($smarty, "viewhooks");
        case 'exporthook':
            require(awebdesk('assets/hooks_export_assets.php'));
            return new Hooks_Export_assets();
        case 'addhook':
        case 'edithook':
            require(awebdesk('assets/hooks_edit_assets.php'));
            return new Hooks_Edit_assets();
        case 'delhook':
            if (isset($_GET["id"])) {
                adesk_sql_delete("#hooks", "`id` = ".(int)$_GET["id"]);
                adesk_smarty_message($smarty, "Your hook has been successfully deleted.");
            }
            return adesk_hook_assets($smarty, "viewhooks");
        case 'viewhooks':
        default:
            require(awebdesk('assets/hooks_view_assets.php'));
            return new Hooks_View_assets();
    }
}

?>
