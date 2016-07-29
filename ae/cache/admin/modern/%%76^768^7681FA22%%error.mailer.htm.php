<?php /* Smarty version 2.6.12, created on 2016-07-08 14:11:51
         compiled from error.mailer.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'error.mailer.htm', 3, false),)), $this); ?>
<div id="error_mailer" class="adesk_modal" align="center" style="display:none;">
  <div class="adesk_modal_inner" align="left">
    <h1 style="color: #800;"><?php echo ((is_array($_tmp='Sending Error')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>

    <div class="adesk_help_inline" style="font-size:12px;" align="center">
      <?php echo ((is_array($_tmp="There was a critical error while trying to send an email.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
    </div>
    <br />

	<div style="position:absolute; background:#009900; height:30px; margin-left:-10px;">&nbsp;</div>
	 <strong><?php echo ((is_array($_tmp="Recommended Action:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</strong><br />
      <a href="desk.php?action=settings#mailsending"><?php echo ((is_array($_tmp='Update and verify your mail settings')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a><br />
	  <br />
    <div id="error_mailer_message_box" class="adesk_hidden">
      <div style="font-weight:bold;"><?php echo ((is_array($_tmp="Error Message:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
      <div id="error_mailer_message" style="color:#999999; padding:4px; background:#FFFFFF; border:1px solid #CCCCCC;"></div>
    </div>
    <br />

    <div>
	  <strong><?php echo ((is_array($_tmp="Cause:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</strong><br />
      <?php echo ((is_array($_tmp="This type of error usually indicates that there was a problem during sending the email.  This could be due to incorrect mail settings and/or an error with your mail server.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

    </div>
    <br />

    <div>
      <input type="button" value='<?php echo ((is_array($_tmp='Close')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="adesk_dom_toggle_display('error_mailer', 'block');" class="adesk_button_ok" />
    </div>
  </div>
</div>