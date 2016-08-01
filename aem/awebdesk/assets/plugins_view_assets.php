<?php

class Plugins_View_assets extends adesk_assets {

    function Plugins_View_assets() {
    }

    function process(&$smarty) {
        // privilege check
        $admin = absolute_admin_check();

        if (!$admin || $admin['id'] != 1)
            return adesk_smarty_noaccess($smarty);
        
        
        adesk_smarty_submitted($smarty, $this);

        $smarty->assign('groups', adesk_sql_select_array("
            SELECT
                *
            FROM
                `#hooks_groups`
        "));

        $smarty->assign('content_template', 'plugins_view.tpl.htm');
    }
}

?>
