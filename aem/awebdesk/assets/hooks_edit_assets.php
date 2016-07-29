<?php

require_once(awebdesk('functions/re.php'));

class Hooks_Edit_assets extends adesk_assets {

    function Hooks_Edit_assets() {
    }

    function process(&$smarty) {
       // privilege check
        $admin = absolute_admin_check();

        if (!$admin || $admin['id'] != 1)
            return adesk_smarty_noaccess($smarty);

        // assign template
        $smarty->assign('content_template', 'hooks_edit.tpl.htm');
        adesk_smarty_submitted($smarty, $this);

        if (isset($_GET["id"])) {
            $smarty->assign('hook', adesk_sql_select_row("
                SELECT
                    *
                FROM
                    `#hooks`
                WHERE
                    `id` = ".(int)$_GET["id"]
            ));
        }

        if ($smarty->get_template_vars('content_template') == 'hooks_edit.tpl.htm') {
            $smarty->assign('hlocations', adesk_hook_locations());
            $smarty->assign('hgroups', adesk_hook_groups());
        }
    }

    function formProcess(&$smarty) {
        if (!isset($_POST["mode2"]))
            return true;

        if (!adesk_re_is_integer($_POST["priority"]))
            return adesk_smarty_message($smarty, "You must enter a decimal number for the priority field.");

        $ary = array(
            "call" => $_POST["call"],
            "code"   => $_POST["code"],
            "description" => $_POST["description"],
            "groupid" => $_POST["groupid"],
            "priority" => $_POST["priority"],
            "status" => $_POST["status"],
        );

        switch ($_POST["mode2"]) {
            case 'update':
                adesk_sql_update("#hooks", $ary, "`id` = ".(int)$_POST["id"]);
                break;
            case 'insert':
                adesk_sql_insert("#hooks", $ary);
                break;
            default:
                break;
        }

        adesk_smarty_message($smarty, "Your changes have been saved successfully.");
        $ctx = adesk_hook_assets($smarty, "viewhooks");
        $ctx->process($smarty);
    }
}

?>
