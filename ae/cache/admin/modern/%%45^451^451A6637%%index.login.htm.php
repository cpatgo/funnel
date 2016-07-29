<?php /* Smarty version 2.6.12, created on 2016-07-08 14:06:08
         compiled from index.login.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'index.login.htm', 4, false),array('modifier', 'js', 'index.login.htm', 4, false),array('modifier', 'escape', 'index.login.htm', 31, false),)), $this); ?>
<?php if ($this->_tpl_vars['error_mesg'] != ''): ?>
<script>
<?php if ($this->_tpl_vars['error_mesg'] == 'timeout'): ?>
adesk_error_show('<?php echo ((is_array($_tmp=((is_array($_tmp="Timeout occurred. Please login again.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
');
<?php elseif ($this->_tpl_vars['error_mesg'] == 'invalidlogin'): ?>
adesk_error_show('<?php echo ((is_array($_tmp=((is_array($_tmp="Invalid Login Information Provided. Please try again.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
');
<?php elseif ($this->_tpl_vars['error_mesg'] == 'logout'): ?>
adesk_result_show('<?php echo ((is_array($_tmp=((is_array($_tmp="You have been logged out.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
');
<?php endif; ?>
</script>
<?php endif; ?>

<form action="login.php" method="post" name="log_user" id="log_user" style="margin:0px;">
	<div><?php echo ((is_array($_tmp='Username')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
	<input name="user" type="text" id="user" style="width:97%;" tabindex="1" />
	<div style="margin-top:14px;"><?php echo ((is_array($_tmp='Password')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 </div>
	<input name="pass" type="password" id="pass" style="width:97%;" tabindex="2" />

	<div style="margin-top:14px;">
		<div style="float:right; padding-top:8px;">
			<label>
				<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  cellpadding="1" cellspacing="0" border="0" class="adesk_login_box_remember_me">
					<tr>
						<td><input name="rm" type="checkbox" id="rm" value="1" tabindex="3" /></td>
						<td><?php echo ((is_array($_tmp='Remember me')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
					</tr>
				</table></div>
			</label>
		</div>
	<input type="submit" class="adesk_button_login" value="<?php echo ((is_array($_tmp='Login')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" tabindex="4" />
	<input name="idt" type="hidden" id="idt" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['idt'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />
	</div>
</form>

