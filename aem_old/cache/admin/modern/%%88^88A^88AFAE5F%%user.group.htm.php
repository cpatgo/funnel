<?php /* Smarty version 2.6.12, created on 2016-07-08 14:11:34
         compiled from user.group.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'user.group.htm', 3, false),)), $this); ?>
<div id="group" class="adesk_modal" align="center" style="display:none; overflow: auto">
  <div class="adesk_modal_inner_groups">
  	<h3 class="m-b"><?php echo ((is_array($_tmp='Add a new group')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
	<table border="0" cellspacing="1" cellpadding="1">
	  <tr>
		<td><?php echo ((is_array($_tmp='Group Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td><input type="text" name="title" id="user_form_group_name"></td>
	  </tr>
	  <tr>
		<td><?php echo ((is_array($_tmp='Group Description')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td><textarea name="descript" id="user_form_group_descript"></textarea></td>
	  </tr>
	  <?php if (isset ( $this->_tpl_vars['group_file'] )): ?>
	  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['group_file'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	  <?php endif; ?>
	</table>
	<br>

	<div>
	  <input type="button" onclick="user_group_save()" value='<?php echo ((is_array($_tmp='Add Group')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
'>
	  <input type="button" onclick="adesk_dom_toggle_display('group', 'block')" value='<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
'>
	</div>
	<br />

  </div>
</div>