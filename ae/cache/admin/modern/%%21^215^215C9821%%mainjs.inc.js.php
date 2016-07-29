<?php /* Smarty version 2.6.12, created on 2016-07-08 14:06:09
         compiled from mainjs.inc.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'jsvar', 'mainjs.inc.js', 1, false),)), $this); ?>
<?php echo smarty_function_jsvar(array('name' => 'adesk_js_admin','var' => $this->_tpl_vars['jsAdmin']), $this);?>

<?php echo smarty_function_jsvar(array('name' => 'adesk_js_site','var' => $this->_tpl_vars['jsSite']), $this);?>


adesk_editor_init_mid_object.content_css = adesk_js_site.p_link + adesk_editor_init_mid_object.content_css;