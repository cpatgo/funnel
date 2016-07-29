<?php /* Smarty version 2.6.12, created on 2016-07-08 14:14:43
         compiled from campaign_new.header.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'campaign_new.header.htm', 15, false),array('modifier', 'alang', 'campaign_new.header.htm', 25, false),array('function', 'jsvar', 'campaign_new.header.htm', 71, false),)), $this); ?>
<input type="hidden" name="aftersave" id="campaign_aftersave" value="next">
<input type="hidden" name="step" value="<?php echo $this->_tpl_vars['step']; ?>
">

<input type="hidden" name="id" id="form_id" value="<?php echo $this->_tpl_vars['campaignid']; ?>
" />
<input type="hidden" name="debugging" id="form_debugging" value="<?php echo $this->_tpl_vars['debugging']; ?>
" />


<div class="campaign_new_progress_container"  >
	<div id="campaign_new_progress" class="campaign_new_progress">

	</div>
</div>

<script type="text/javascript">
	campaign_header(<?php echo ((is_array($_tmp=@$this->_tpl_vars['highlight'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
, <?php echo ((is_array($_tmp=@$this->_tpl_vars['campaignid'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
);
</script>



 
<div id="campaign_send_warning" class="<?php if (! $this->_tpl_vars['canSendCampaign'] || ( $this->_tpl_vars['__ishosted'] && $this->_tpl_vars['maillimit'] > 0 && ( int ) $this->_tpl_vars['maillimitleft_raw'] < 0 )): ?>adesk_block<?php else: ?>adesk_hidden<?php endif; ?>">
	<div id="campaign_send_warning_campaign" class="<?php if (! $this->_tpl_vars['canSendCampaign']): ?>adesk_block<?php else: ?>adesk_hidden<?php endif; ?>">

		<?php if (! $this->_tpl_vars['admin']['pg_message_send']): ?>
		<?php echo ((is_array($_tmp="This campaign cannot be sent as it would exceed your currently allowed campaigns per day.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		<?php else: ?>
		<?php if ($this->_tpl_vars['admin']['abuseratio_overlimit']): ?>
		<?php echo ((is_array($_tmp="You are currently not able to send any new campaigns due to high abuse complaints.  Please contact your administrator with any questions.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		<?php elseif ($this->_tpl_vars['admin']['limit_campaign_type'] == 'day'): ?>
		<?php echo ((is_array($_tmp="This campaign cannot be sent as it would exceed your currently allowed campaigns per day.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		<?php elseif ($this->_tpl_vars['admin']['limit_campaign_type'] == 'week'): ?>
		<?php echo ((is_array($_tmp="This campaign cannot be sent as it would exceed your currently allowed campaigns per week.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		<?php elseif ($this->_tpl_vars['admin']['limit_campaign_type'] == 'month'): ?>
		<?php echo ((is_array($_tmp="This campaign cannot be sent as it would exceed your currently allowed campaigns per month.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		<?php elseif ($this->_tpl_vars['admin']['limit_campaign_type'] == 'month1st'): ?>
		<?php echo ((is_array($_tmp="This campaign cannot be sent as it would exceed your currently allowed campaigns per calendar month.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		<?php elseif ($this->_tpl_vars['admin']['limit_campaign_type'] == 'monthcdate'): ?>
		<?php echo ((is_array($_tmp="This campaign cannot be sent as it would exceed your currently allowed campaigns per calendar month.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		<?php elseif ($this->_tpl_vars['admin']['limit_campaign_type'] == 'year'): ?>
		<?php echo ((is_array($_tmp="This campaign cannot be sent as it would exceed your currently allowed campaigns per year.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		<?php elseif ($this->_tpl_vars['admin']['limit_campaign_type'] == 'ever'): ?>
		<?php echo ((is_array($_tmp="This campaign cannot be sent as it would exceed your currently allowed campaigns.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		<?php endif; ?>
		<?php endif; ?>
	</div>
	<div id="campaign_send_warning_mail" class="adesk_hidden">
		<?php if ($this->_tpl_vars['admin']['limit_mail_type'] == 'day'): ?>
		<?php echo ((is_array($_tmp="This campaign cannot be sent as it would exceed your currently allowed emails per day.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		<?php elseif ($this->_tpl_vars['admin']['limit_mail_type'] == 'week'): ?>
		<?php echo ((is_array($_tmp="This campaign cannot be sent as it would exceed your currently allowed emails per week.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		<?php elseif ($this->_tpl_vars['admin']['limit_mail_type'] == 'month'): ?>
		<?php echo ((is_array($_tmp="This campaign cannot be sent as it would exceed your currently allowed emails per month.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		<?php elseif ($this->_tpl_vars['admin']['limit_mail_type'] == 'month1st'): ?>
		<?php echo ((is_array($_tmp="This campaign cannot be sent as it would exceed your currently allowed emails per calendar month.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		<?php elseif ($this->_tpl_vars['admin']['limit_mail_type'] == 'monthcdate'): ?>
		<?php echo ((is_array($_tmp="This campaign cannot be sent as it would exceed your currently allowed emails per calendar month.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		<?php elseif ($this->_tpl_vars['admin']['limit_mail_type'] == 'year'): ?>
		<?php echo ((is_array($_tmp="This campaign cannot be sent as it would exceed your currently allowed emails per year.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		<?php elseif ($this->_tpl_vars['admin']['limit_mail_type'] == 'ever'): ?>
		<?php echo ((is_array($_tmp="This campaign cannot be sent as it would exceed your currently allowed emails.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		<?php endif; ?>
	</div>
	<div id="campaign_send_warning_subscriber" class="<?php if ($this->_tpl_vars['__ishosted'] && $this->_tpl_vars['maillimit'] > 0 && ( int ) $this->_tpl_vars['maillimitleft_raw'] < 0): ?>adesk_block<?php else: ?>adesk_hidden<?php endif; ?>">
		<?php echo ((is_array($_tmp="This campaign cannot be sent as you have exceeded your currently allowed subscribers for your account.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	</div>
</div>

<?php if ($this->_tpl_vars['formSubmitted']): ?>
<script>
	<?php if ($this->_tpl_vars['submitResult']['succeeded'] && $this->_tpl_vars['submitResult']['message']): ?>
adesk_result_show(<?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['submitResult']['message']), $this);?>
);
	<?php elseif ($this->_tpl_vars['submitResult']['message']): ?>
adesk_error_show(<?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['submitResult']['message']), $this);?>
);
	<?php endif; ?>
</script>
<?php endif; ?>

<?php if ($this->_tpl_vars['formSubmitted']): ?>
	<?php if ($this->_tpl_vars['submitResult']['succeeded'] && $this->_tpl_vars['submitResult']['message']): ?>
<div class="adesk_help_inline"><?php echo $this->_tpl_vars['submitResult']['message']; ?>
</div>
	<?php elseif ($this->_tpl_vars['submitResult']['message']): ?>
<div class="adesk_help_inline"><?php echo $this->_tpl_vars['submitResult']['message']; ?>
</div>
	<?php endif; ?>
<?php endif; ?>