<?php

class Plugins_Edit_assets extends adesk_assets {

    function Plugins_Edit_assets() {
    }

    function process(&$smarty) {
        // privilege check
        $admin = absolute_admin_check();

        if (!$admin || $admin['id'] != 1)
            return adesk_smarty_noaccess($smarty);
        

        $smarty->assign('content_template', 'plugins_edit.tpl.htm');
        adesk_smarty_submitted($smarty, $this);

        if (isset($_GET["id"]))
            $this->get($smarty, $_GET["id"]);
    }

    function get(&$smarty, $id) {
        $smarty->assign('group', adesk_sql_select_row("
            SELECT
                *
            FROM
                `#hooks_groups`
            WHERE
                `id` = ".(int)$id
        ));

        $smarty->assign('deps', adesk_sql_select_array("
            SELECT
                *
            FROM
                `#hooks_deps`
            WHERE
                `groupid` = ".(int)$id
        ));
    }

    function formProcess(&$smarty) {
        if (!isset($_POST["mode2"]))
            return true;

        $ary = array(
            "name"        => trim($_POST["name"]),
            "version"     => trim($_POST["version"]),
            "description" => $_POST["description"],
            "status"      => $_POST["status"],
            "install_pre" => $_POST["install_pre"],
            "install_post"=> $_POST["install_post"],
        );

        if ($ary["name"] == "")
            return adesk_smarty_message($smarty, "The \"Name\" field cannot be left blank.");

        if ($ary["version"] == "")
            return adesk_smarty_message($smarty, "The \"Version\" field cannot be left blank.");

        switch ($_POST["mode2"]) {
            case 'insert':
                $ary['=ctime'] = "NOW()";
                adesk_sql_insert("#hooks_groups", $ary);
                break;
            case 'update':
                adesk_sql_update("#hooks_groups", $ary, "`id` = ".(int)$_POST["id"]);
                break;
            default:
                break;
        }

        adesk_smarty_message($smarty, "Your changes have been saved successfully.");
        $ctx = adesk_hook_assets($smarty, "view");
        $ctx->process($smarty);

        return true;
    }
}

?>
