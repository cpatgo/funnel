<?php /* Smarty version 2.6.12, created on 2016-07-08 14:11:45
         compiled from group.form.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'group.form.htm', 3, false),)), $this); ?>
<div id="form" class="adesk_hidden">
  <div id="form_admin_limitations" style="display:none" class="adesk_help_inline">
	<?php echo ((is_array($_tmp='Any limitations or permissions below will apply to all users in the admin group besides user "admin".  User "admin" will not be limited.')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

  </div>
  <form method="POST" onsubmit="group_form_save(group_form_id); return false">
    <input type="hidden" name="id" id="form_id" />
    <table border="0" cellspacing="0" cellpadding="5">
	  <tr>
		<td><?php echo ((is_array($_tmp='Title')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td><input type="text" name="title" id="form_title" style="width:99%;"/></td>
	  </tr>
	  <tr>
		<td valign="top"><?php echo ((is_array($_tmp='Description')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td><textarea name="descript" id="form_descript" style="width:99%;"></textarea></td>
	  </tr>
	  <?php if (isset ( $this->_tpl_vars['group_file'] )): ?>
	  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['group_file'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	  <?php endif; ?>
    </table>

    <br />
    <div>
	  <?php if (! $this->_tpl_vars['demoMode']): ?>
      <input type="button" id="form_submit" class="adesk_button_submit" value="<?php echo ((is_array($_tmp='Submit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="group_form_save(group_form_id)" />
	  <?php else: ?>
	  <span class="demoDisabled2"><?php echo ((is_array($_tmp='Disabled in demo')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
	  <?php endif; ?>
      <input type="button" id="form_back" class="adesk_button_back" value="<?php echo ((is_array($_tmp='Back')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="window.history.go(-1)" />
    </div>
    <input type="submit" style="display:none"/>
  </form>
</div>