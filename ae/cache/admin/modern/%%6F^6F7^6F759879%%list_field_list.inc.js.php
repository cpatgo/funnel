<?php /* Smarty version 2.6.12, created on 2016-07-08 17:09:38
         compiled from list_field_list.inc.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'jsvar', 'list_field_list.inc.js', 1, false),)), $this); ?>
<?php echo smarty_function_jsvar(array('name' => 'relid','var' => $this->_tpl_vars['relid']), $this);?>


<?php echo '
function update_order(ary) {
    var ids     = "";
    var orders  = "";

    for (var i = 0; i < ary.length; i++) {
        ids     += ary[i].toString();
        orders  += i.toString();

        if (i < ary.length - 1) {
            ids     += ",";
            orders  += ",";
        }
    }

    adesk_ajax_call_cb(\'awebdeskapi.php\', \'list.list_field_order\', cb_update_order, relid, ids, orders);
}

function cb_update_order(res, xml) {
    document.getElementById(\'save_order\').disabled = true;
}
'; ?>
