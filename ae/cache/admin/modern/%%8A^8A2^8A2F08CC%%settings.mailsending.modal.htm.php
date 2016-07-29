<?php /* Smarty version 2.6.12, created on 2016-07-08 14:11:51
         compiled from settings.mailsending.modal.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'settings.mailsending.modal.htm', 5, false),)), $this); ?>
<div id="mailer_form" class="adesk_modal" style="display:none">
  <div class="adesk_modal_inner">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "settings.mailsending.form.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<br />
	<input type="button" value='<?php echo ((is_array($_tmp='Save')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="mailer_save();" />
	<input type="button" value='<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="$('mailer_form').hide()" />

<hr />
<div style="background-color: #ffffff; border: 1px solid #cccccc; margin: 5px; padding: 10px; font-size:10px;">
	<em><?php echo ((is_array($_tmp="Estimated Sending Speeds:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</em><br />

	<?php echo ((is_array($_tmp="Max. of emails per hour:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <span id="eph" class="changeableContent"></span><br />
	<?php echo ((is_array($_tmp="Max. of emails per minute:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <span id="epm" class="changeableContent"></span><br />
	<?php echo ((is_array($_tmp="Max. of emails per second:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <span id="eps" class="changeableContent"></span><br />
	<?php echo ((is_array($_tmp='An email every ')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <span id="spe" class="changeableContent"></span> <?php echo ((is_array($_tmp='seconds')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

</div>

  </div>
</div>

<div id="mailer_test" class="adesk_modal" style="display:none">
  <div class="adesk_modal_inner">
    <h5><?php echo ((is_array($_tmp='Send Test Email')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
    <div class="adesk_help_inline"><?php echo ((is_array($_tmp="You can use this to test this connection settings. Enter the destination email address, and the system will attemp to send it an email.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
    <div>
      <?php echo ((is_array($_tmp="Send a test email to this email address:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
	  <input id="testEmailField" type="input" size="30" value="<?php echo $this->_tpl_vars['admin']['email']; ?>
" onFocus="this.select();" />
    </div>
	<br />
<?php if (! $this->_tpl_vars['demoMode']): ?>
	<input id="testEmailButton" class="adesk_button_test" type="button" value="<?php echo ((is_array($_tmp='Send Test Email')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onClick="mailer_send();" />
<?php else: ?>
	<span class="demoDisabled"><?php echo ((is_array($_tmp='Disabled in demo')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
<?php endif; ?>
	<input type="button" value='<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="$('mailer_test').hide()" />
	<input type="hidden" value="" id="mailer_test_id" />
  </div>
</div>

<div id="mailer_delete" class="adesk_modal" style="display:none">
  <div class="adesk_modal_inner">
    <h5><?php echo ((is_array($_tmp='Delete Mail Connection')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
    <div class="adesk_help_inline"><?php echo ((is_array($_tmp="Are you sure you wish to delete this Mail Connection?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
    <span id="deleteEmailMsg"></span>
	<br />
<?php if (! $this->_tpl_vars['demoMode']): ?>
	<input id="deleteEmailButton" class="adesk_button_test" type="button" value="<?php echo ((is_array($_tmp='Delete This Mail Connection')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onClick="mailer_remove();" />
<?php else: ?>
	<span class="demoDisabled"><?php echo ((is_array($_tmp='Disabled in demo')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
<?php endif; ?>
	<input type="button" value='<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="$('mailer_delete').hide()" />
	<input type="hidden" value="" id="mailer_delete_id" />
  </div>
</div>