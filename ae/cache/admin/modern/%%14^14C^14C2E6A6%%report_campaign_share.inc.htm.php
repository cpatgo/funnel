<?php /* Smarty version 2.6.12, created on 2016-07-08 14:48:21
         compiled from report_campaign_share.inc.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'report_campaign_share.inc.htm', 3, false),array('modifier', 'help', 'report_campaign_share.inc.htm', 36, false),)), $this); ?>
<div id="share" class="adesk_modal_delete" align="center" style="display: none">
  <div class="adesk_modal_inner">
    <h3 class="m-b"><?php echo ((is_array($_tmp='Share Reports')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
	<div>
	  <?php echo ((is_array($_tmp="Use this link to share reports for this campaign.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br>
	  <input type="text" id="share_link" style="width: 100%"></span><br><br>
	  <?php echo ((is_array($_tmp="Or email the share link to someone else.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br>
	</div>
	<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="1" cellpadding="1">
	  <tr>
		<td><?php echo ((is_array($_tmp='Recipient email address')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td><input type="text" name="addrto" id="share_addrto"></td>
	  </tr>
	  <tr>
		<td><?php echo ((is_array($_tmp='Recipient name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td><input type="text" name="nameto" id="share_nameto"></td>
	  </tr>
	  <tr>
		<td><?php echo ((is_array($_tmp='From email address')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td><input type="text" name="addrfrom" id="share_addrfrom"></td>
	  </tr>
	  <tr>
		<td><?php echo ((is_array($_tmp='From name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td><input type="text" name="namefrom" id="share_namefrom"></td>
	  </tr>
	  <tr>
		<td><?php echo ((is_array($_tmp='Subject')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td><input type="text" name="subject" id="share_subject"></td>
	  </tr>
	  <tr>
		<td><?php echo ((is_array($_tmp='Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td>
		  <textarea rows="8" style="width: 100%" name="message" id="share_message"><?php echo ((is_array($_tmp='Please view your mailing campaign reports at')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>


			%REPORTLINK%</textarea>
		  <?php echo ((is_array($_tmp="%REPORTLINK% is shorthand for the shared report link that you see above.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

		</td>
	  </tr>
	</table></div>
    <div>
      <input type="button" class="adesk_button_ok" value="<?php echo ((is_array($_tmp='OK')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="campaign_share(campaign_share_id)" />
      <input type="button" class="adesk_button_cancel" value="<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="adesk_dom_toggle_display('share', 'block'); adesk_ui_anchor_set(report_campaign_list_anchor())" />
    </div>
  </div>
</div>