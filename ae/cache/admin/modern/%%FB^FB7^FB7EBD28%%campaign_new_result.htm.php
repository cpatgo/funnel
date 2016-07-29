<?php /* Smarty version 2.6.12, created on 2016-07-08 14:48:10
         compiled from campaign_new_result.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'campaign_new_result.htm', 2, false),array('modifier', 'acpdate', 'campaign_new_result.htm', 98, false),)), $this); ?>
<?php if ($this->_tpl_vars['hosted_down4'] != 'nobody'): ?>
<?php echo ((is_array($_tmp="Due to your account status, you are unable to send any campaigns.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

<a href="desk.php"><?php echo ((is_array($_tmp="Return to the Dashboard.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
<?php elseif ($this->_tpl_vars['pastlimit']): ?>
<?php echo ((is_array($_tmp="Sending to this list would put you past your limit of allowed emails.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

<a href="desk.php?action=campaign_new_list&id=<?php echo $this->_tpl_vars['campaignid']; ?>
"><?php echo ((is_array($_tmp="Please choose another list.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
<?php else: ?>

<script type="text/javascript">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "campaign_new.shared.js", 'smarty_include_vars' => array('step' => 'result')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "campaign_new_result.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</script>

<form id="campaignform" method="POST" action="desk.php" onsubmit="return false">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "campaign_new.header.htm", 'smarty_include_vars' => array('step' => 'result','highlight' => 4)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<input type="hidden" name="action" value="campaign_new_result">

  <div class="h2_wrap_static">
	<div class="h2_content">

<?php if (! $this->_tpl_vars['admin']['send_approved']): ?>

	  <div class="final_submitted" style="padding:10px; border:3px solid #E4F4C3; background:#F2FFD8; font-size:14px; margin-bottom:20px;">
		<h2 style="font-weight:bold; color:#006600;">
		<?php echo ((is_array($_tmp="Your email campaign has been saved and is currently awaiting approval to be sent!")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		</h5><div class="line"></div>


		<?php echo ((is_array($_tmp="Approval typically only takes a couple minutes to an hour. So your campaign should be sending shortly.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>


		<br /><br />



		<?php echo ((is_array($_tmp="We do manual approvals on certain campaigns (especially for new users) to ensure that email deliverability is as high as possible for all of our users.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <?php echo ((is_array($_tmp="This allows us to ensure the top return for your email marketing efforts.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	<br /><br />
		<?php echo ((is_array($_tmp="Once we approve your campaign it will start sending immediately.  We apologize for any inconvenience and would like to once again remind you that this is to ensure the top email deliverability for all users.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  </div>

<?php endif; ?>

<?php if ($this->_tpl_vars['finalstatus'] == 'sent'): ?>

	  <div class="final_sent">

<?php if ($this->_tpl_vars['__ishosted']): ?>

		<div id="approvalqueue" style="padding:10px; border:3px solid #E4F4C3; background:#F2FFD8; font-size:14px; margin-bottom:20px;">
		  <div id="approvalqueue_waiting">
			<h2 style="font-weight:bold; color:#006600;"><?php echo ((is_array($_tmp="Your campaign is currently being processed.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
			<?php echo ((is_array($_tmp="We are gathering all the details and processing your campaign.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			<?php echo ((is_array($_tmp="You can leave this page at any time.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			<?php echo ((is_array($_tmp="If we need to verify anything (before sending begins) we will send you an email.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

<?php if (adesk_admin_ismaingroup ( )): ?>
			<?php echo ((is_array($_tmp="(The address that we will email is %s)")) ? $this->_run_mod_handler('alang', true, $_tmp, $this->_tpl_vars['hostedaccount']['email']) : smarty_modifier_alang($_tmp, $this->_tpl_vars['hostedaccount']['email'])); ?>

<?php endif; ?>
			<div align="center">
			  <img src="images/loadingbar.gif">
			</div>
		  </div>

		  <div id="approvalqueue_sending" style="display:none">
			<h2 style="font-weight:bold; color:#006600;"><?php echo ((is_array($_tmp="Your email campaign is now being sent!")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 </h5><div class="line"></div>
			<?php echo ((is_array($_tmp="You can view its progress in the Campaigns section.  View the Reports section to see live reactions and analysis.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		  </div>

		  <div id="approvalqueue_pending" style="display:none">
			<h2 style="font-weight:bold; color:#006600;"><?php echo ((is_array($_tmp="Your email campaign has been saved and is currently awaiting approval to be sent!")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 </h5><div class="line"></div>
			<?php echo ((is_array($_tmp="Approval typically only takes a couple minutes to an hour. So your campaign should be sending shortly.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			<br /><br />
			<?php echo ((is_array($_tmp="We do manual approvals on certain campaigns (especially for new users) to ensure that email deliverability is as high as possible for all of our users.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <?php echo ((is_array($_tmp="This allows us to ensure the top return for your email marketing efforts.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			<br /><br />
			<?php echo ((is_array($_tmp="Once we approve your campaign it will start sending immediately.  We apologize for any inconvenience and would like to once again remind you that this is to ensure the top email deliverability for all users.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		  </div>

		  <div id="approvalqueue_moreinfo" style="display:none">
			<h2 style="font-weight:bold; color:#006600;"><?php echo ((is_array($_tmp="We're afraid that we need more information from you before we can approve this campaign.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
			<?php echo ((is_array($_tmp="Please check your account email address for more details.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

<?php if (adesk_admin_ismaingroup ( )): ?>
			<?php echo ((is_array($_tmp="(%s)")) ? $this->_run_mod_handler('alang', true, $_tmp, $this->_tpl_vars['hostedaccount']['email']) : smarty_modifier_alang($_tmp, $this->_tpl_vars['hostedaccount']['email'])); ?>

<?php endif; ?>
		  </div>
		  <div id="approvalqueue_declined" style="display:none">
			<h2 style="font-weight:bold; color:#006600;"><?php echo ((is_array($_tmp="We're afraid that your campaign has been declined.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
			<?php echo ((is_array($_tmp="Please check your account email address for more details.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		  </div>
		</div>
<?php else: ?>
			<?php echo ((is_array($_tmp="Your campaign has been initiated. The sending process has started.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

<?php endif; ?>
	  </div>

<?php elseif ($this->_tpl_vars['finalstatus'] == 'scheduled'): ?>

	  <div class="final_scheduled">
		<?php echo ((is_array($_tmp="Your campaign has been scheduled. The sending process will start at:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		<?php if ($this->_tpl_vars['campaign']['sdate'] == ""): ?>
		<strong><?php echo ((is_array($_tmp=@adesk_CURRENTDATETIME)) ? $this->_run_mod_handler('acpdate', true, $_tmp, $this->_tpl_vars['site']['datetimeformat']) : smarty_modifier_acpdate($_tmp, $this->_tpl_vars['site']['datetimeformat'])); ?>
</strong>
		<?php else: ?>
		<strong><?php echo ((is_array($_tmp=$this->_tpl_vars['campaign']['sdate'])) ? $this->_run_mod_handler('acpdate', true, $_tmp, $this->_tpl_vars['site']['datetimeformat']) : smarty_modifier_acpdate($_tmp, $this->_tpl_vars['site']['datetimeformat'])); ?>
</strong>
		<?php endif; ?>
	  </div>

<?php elseif ($this->_tpl_vars['finalstatus'] == 'finished'): ?>

	  <div class="final_finished"><?php echo ((is_array($_tmp="Your campaign has been completed.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>

<?php endif; ?>

	</div>

  </div>

  <br clear="all" />

  <div>
	<input value="<?php echo ((is_array($_tmp='Return To Dashboard')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" type="button" onclick="window.location.href='desk.php';" style="font-size:14px;" />
	<input value="<?php echo ((is_array($_tmp='Create Another Campaign')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" type="button" onclick="window.location.href='desk.php?action=campaign_new';" style="font-size:14px;" />
	<input value="<?php echo ((is_array($_tmp='View Campaigns')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" type="button" onclick="window.location.href='desk.php?action=campaign';" style="font-weight:bold; font-size:14px;" />
<?php if (! in_array ( $this->_tpl_vars['campaign']['type'] , array ( 'responder' , 'reminder' , 'special' ) )): ?>
	<input value="<?php echo ((is_array($_tmp='View Report')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" type="button" id="reportbutton" onclick="window.location.href='desk.php?action=report_campaign&id=<?php echo $this->_tpl_vars['campaignid']; ?>
#general-01-0-0';" style="font-size:14px;" />
<?php endif; ?>
  </div>

	<script type="text/javascript">
		//campaign_save_auto_runagain();
		<?php if ($this->_tpl_vars['finalstatus'] == 'sent' && $this->_tpl_vars['__ishosted']): ?>campaign_hosted_checkapproval();<?php endif; ?>
	</script>
</form>
<?php endif; ?>