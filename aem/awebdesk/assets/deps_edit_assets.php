<?php

class Deps_Edit_assets extends adesk_assets {

    function Deps_Edit_assets() {
    }

    function process(&$smarty) {
        // privilege check

        $admin = absolute_admin_check();

        if (!$admin || $admin['id'] != 1)
            return adesk_smarty_noaccess($smarty);
        
        $smarty->assign('content_template', 'deps_edit.tpl.htm');
        adesk_smarty_submitted($smarty, $this);

        if (isset($_GET["id"]) && isset($_GET["mode"])) {
            switch ($_GET["mode"]) {
                case 'deldep':
                    adesk_sql_delete("hooks_deps", "`id` = ".(int)$_GET["id"]);
                    break;
                case 'editdep':
                    $this->get($smarty, $_GET["id"]);
                    break;
                default:
                    break;
            }
        }
    }

    function get(&$smarty, $id) {
        $dep = adesk_sql_select_row("
            SELECT
                *
            FROM
                `#hooks_deps`
            WHERE
                `id` = ".$id
        );
        $smarty->assign('dep', $dep);
    }

    function formProcess(&$smarty) {
        if (!isset($_POST["mode2"]) || !isset($_POST["groupid"]) || !isset($_POST["id"]))
            return true;

        $this->get($smarty, $_POST["id"]);

        $pat = '/\d*\.\d+.*/';
        if (!preg_match($pat, $_POST["from"]))
            return adesk_smarty_message($smarty, "The \"From Version\" field (".$_POST["from"].") was not formatted correctly.");

        if (!preg_match($pat, $_POST["to"]))
            return adesk_smarty_message($smarty, "The \"To Version\" field (".$_POST["to"].") was not formatted correctly.");

        $ary = array(
            "deptype" => $_POST["deptype"],
            "from"    => $_POST["from"],
            "to"      => $_POST["to"],
            "groupid" => $_POST["groupid"],
        );

        switch ($_POST["mode2"]) {
            default:
                break;
            case 'insert':
                adesk_sql_insert("#hooks_deps", $ary);
                break;
            case 'update':
                adesk_sql_update("#hooks_deps", $ary, "`id` = ".(int)$_POST["id"]);
                break;
        }

        $_GET["id"] = $_POST["groupid"];    // So edit knows what to look at
        $_GET["mode"] = "edit";
        $_POST = array();
        adesk_smarty_load_get($smarty);
        adesk_smarty_message($smarty, "Your changes have been saved successfully.");
        $ctx = adesk_hook_assets($smarty, $_GET["mode"]);
        $ctx->process($smarty);

        return true;
    }
}
?>
