<?php

function smarty_function_adesk_modes($params, &$smarty) {
    $tvars = $smarty->get_template_vars();

    if (!isset($tvars['get']['mode']) || isset($tvars['_adesk_modes_use_default'])) {
        $mode = $params['default'];
    } else {
        $mode = $tvars['get']['mode'];
    }

    $smarty->assign('mode', $mode);

    if ($mode == 'edit' || $mode == 'update' || ($mode == 'insert' && !( isset($smarty->_tpl_vars['resultStatus']) && !$smarty->_tpl_vars['resultStatus']))) {
        $smarty->assign('mode_submit', _a("Update"));
        $smarty->assign('mode_future', "update");
    } elseif ($mode == 'add' || $mode == 'insert') {
        $smarty->assign('mode_submit', _a("Create"));
        $smarty->assign('mode_future', "insert");
    }
}

?>
