<?php

class Hooks_View_assets extends adesk_assets {

    function Hooks_View_assets() {
    }

    function process(&$smarty) {
        // privilege check
        $admin = absolute_admin_check();

        if (!$admin || $admin['id'] != 1)
            return adesk_smarty_noaccess($smarty);
        
        
        adesk_smarty_submitted($smarty, $this);

        $smarty->assign('hooks', adesk_sql_select_array("
            SELECT
                h.*,
                g.`name` AS `group`
            FROM
                `#hooks` h
            LEFT JOIN `#hooks_groups` g ON
                g.`id` = h.`groupid`
        "));

        $smarty->assign('content_template', 'hooks_view.tpl.htm');
    }
}

?>
