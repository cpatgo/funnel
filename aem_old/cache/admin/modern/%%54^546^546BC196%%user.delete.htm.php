<?php /* Smarty version 2.6.12, created on 2016-07-08 14:11:34
         compiled from user.delete.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'user.delete.htm', 15, false),)), $this); ?>
<?php if (isset ( $this->_tpl_vars['user_delete_js_file'] )): ?>
<script type="text/javascript">
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['user_delete_js_file'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</script>
<?php endif; ?>

<div id="delete" class="adesk_modal" align="center" style="display:none">
  <div class="adesk_modal_inner">
    <span id="delete_message"></span>
    <br />
	<div id="delete_extra"></div>
    <ul id="delete_list"></ul>
    <br />
	<div style="text-align: right">
	  <input type="button" class="adesk_button_ok" value='<?php echo ((is_array($_tmp='OK')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="user_delete(user_delete_id)">
	  <input type="button" class="adesk_button_cancel" value='<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="user_delete_check_cancel()">
	</div>
  </div>
</div>