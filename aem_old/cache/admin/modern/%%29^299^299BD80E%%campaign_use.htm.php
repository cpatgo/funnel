<?php /* Smarty version 2.6.12, created on 2016-07-08 15:20:02
         compiled from campaign_use.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'adesk_calendar', 'campaign_use.htm', 5, false),array('modifier', 'alang', 'campaign_use.htm', 16, false),array('modifier', 'acpdate', 'campaign_use.htm', 96, false),)), $this); ?>
<?php if (isset ( $this->_tpl_vars['campaign'] )): ?>


<?php echo smarty_function_adesk_calendar(array('base' => ".."), $this);?>

<script type="text/javascript">
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "campaign_use.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</script>

<?php if ($this->_tpl_vars['formSubmitted']): ?>

<div class="adesk_help_inline"><?php echo $this->_tpl_vars['submitResult']['message']; ?>
</div>

<?php if ($this->_tpl_vars['submitResult']['succeeded']): ?>

<input value="<?php echo ((is_array($_tmp='View Campaigns')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" type="button" onclick="window.location = 'desk.php?action=campaign';" style="font-size:14px;" />

<?php else: ?>

<input value="<?php echo ((is_array($_tmp='Back')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" type="button" onclick="window.history.go(-1);" style="font-size:14px;" />

<?php endif; ?>

<?php else: ?>

<h3 class="m-b"><?php echo ((is_array($_tmp='Reuse an Existing Campaign')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>

<?php if (! $this->_tpl_vars['campaign']): ?>

<div class="warning">
	<?php echo ((is_array($_tmp="Campaign not found.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	<a href="desk.php?action=campaign"><?php echo ((is_array($_tmp="Find a campaign you wish to reuse here.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
</div>

<?php else: ?>

<form id="campaignform" method="POST" onsubmit="return form_check();">
	<input type="hidden" name="id" id="form_id" value="<?php echo $this->_tpl_vars['campaign']['id']; ?>
" />

	<div class="h2_wrap_static">
		<h5><?php echo ((is_array($_tmp='Summary')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
		<div class="h2_content">
		  <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="26" valign="top"><img src="images/checkbox-16-16.png" width="16" height="16" /></td>
              <td width="125" valign="top" style="font-weight:bold;"><?php echo ((is_array($_tmp='Campaign Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
              <td>
                <span id="summary_campaign_label_box" class="adesk_inline">
                  <span id="summary_campaign_label"><?php echo $this->_tpl_vars['campaign']['name']; ?>
</span>
                  <a href="#" onclick="campaignname();return false;"><?php echo ((is_array($_tmp="(change)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
                </span>
                <span id="summary_campaign_input_box" style="display:none">
                  <input type="text" id="summary_campaign_input" name="campaign_name" value="<?php echo $this->_tpl_vars['campaign']['name']; ?>
" size="25" />
                  <input type="button" value="<?php echo ((is_array($_tmp='Set')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="campaignname();" />
                </span>
              </td>
            </tr>
            <tr>
              <td width="26" valign="top"><img src="images/checkbox-16-16.png" width="16" height="16" /></td>
              <td width="125" valign="top" style="font-weight:bold;"><?php echo ((is_array($_tmp="List(s)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
              <td id="summary_lists">
<?php $_from = $this->_tpl_vars['campaign']['lists']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['l']):
?>
                <?php echo $this->_tpl_vars['l']['name']; ?>
<br />
<?php endforeach; endif; unset($_from); ?>
              </td>
            </tr>
            <tr>
              <td width="26" valign="top"><img src="images/checkbox-16-16.png" width="16" height="16" /></td>
              <td width="125" valign="top" style="font-weight:bold;"><?php echo ((is_array($_tmp='Filter')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
              <td id="summary_filter">
<?php if ($this->_tpl_vars['campaign']['filterid']): ?>
                <?php echo $this->_tpl_vars['campaign']['filter']['name']; ?>

<?php else: ?>
                <?php echo ((is_array($_tmp='None')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

<?php endif; ?>
              </td>
            </tr>
            <tr>
              <td width="26" valign="top"><img src="images/checkbox-16-16.png" width="16" height="16" /></td>
              <td width="125" valign="top" style="font-weight:bold;"><?php echo ((is_array($_tmp="Message(s)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
              <td id="summary_messages">
<?php $_from = $this->_tpl_vars['campaign']['messages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['m']):
?>
                <span onmouseover='adesk_tooltip_show("\"<?php echo $this->_tpl_vars['m']['fromname']; ?>
\" <<?php echo $this->_tpl_vars['m']['fromemail']; ?>
>", 250, "");' onmouseout="adesk_tooltip_hide();">
                  <?php echo $this->_tpl_vars['m']['subject']; ?>

                </span>
                <br />
<?php endforeach; endif; unset($_from); ?>
              </td>
            </tr>
            <tr>
              <td width="26" valign="top"><img src="images/checkbox-16-16.png" width="16" height="16" /></td>
              <td width="125" valign="top" style="font-weight:bold;"><?php echo ((is_array($_tmp='Will Send')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
              <td id="summary_schedule">
<?php if ($this->_tpl_vars['campaign']['type'] == 'single' || $this->_tpl_vars['campaign']['type'] == 'split' || $this->_tpl_vars['campaign']['type'] == 'deskrss'): ?>
	<?php if ($this->_tpl_vars['campaign']['sdate'] > adesk_CURRENTDATETIME): ?>
                <?php echo ((is_array($_tmp=$this->_tpl_vars['campaign']['sdate'])) ? $this->_run_mod_handler('acpdate', true, $_tmp, $this->_tpl_vars['site']['dateformat']) : smarty_modifier_acpdate($_tmp, $this->_tpl_vars['site']['dateformat'])); ?>
 <?php echo ((is_array($_tmp='at')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['campaign']['sdate'])) ? $this->_run_mod_handler('acpdate', true, $_tmp, $this->_tpl_vars['site']['timeformat']) : smarty_modifier_acpdate($_tmp, $this->_tpl_vars['site']['timeformat'])); ?>

	<?php else: ?>
                <?php echo ((is_array($_tmp='Immediately')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	<?php endif; ?>
<?php elseif ($this->_tpl_vars['campaign']['type'] == 'recurring'): ?>
                <?php echo ((is_array($_tmp='Immediately')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

                <?php echo ((is_array($_tmp="(Since this mailing is a recurring one, this new mailing will be set as 'single').")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

<?php else: ?>
                <?php echo ((is_array($_tmp="This campaign will be sent to every subscriber individually based on their subscription date/time.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

<?php endif; ?>
              </td>
            </tr>
            <tr>
              <td width="26" valign="top"><img src="images/checkbox-16-16.png" width="16" height="16" /></td>
              <td width="125" valign="top" style="font-weight:bold;"><?php echo ((is_array($_tmp='Recipients')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
              <td id="summary_recipients"><?php echo $this->_tpl_vars['total']; ?>
</td>
            </tr>
          </table></div>
		</div>
	</div>

	<br />

<?php if ($this->_tpl_vars['total'] > 0): ?>
	<?php if ($this->_tpl_vars['mode'] == 'unread'): ?>
	<div class="adesk_help_inline"><?php echo ((is_array($_tmp="New campaign will be sent to subscribers who have not read/opened this campaign")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
	<?php elseif ($this->_tpl_vars['mode'] == 'newsub'): ?>
	<div class="adesk_help_inline"><?php echo ((is_array($_tmp="New campaign will be sent to new subscribers (since this campaign was originally sent)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
	<?php endif; ?>
<?php else: ?>
	<?php if ($this->_tpl_vars['mode'] == 'unread'): ?>
	<div class="adesk_help_inline" style="color: red;"><?php echo ((is_array($_tmp="There are no subscribers who have not read this campaign.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
	<?php elseif ($this->_tpl_vars['mode'] == 'newsub'): ?>
	<div class="adesk_help_inline" style="color: red;"><?php echo ((is_array($_tmp="There are no new subscribers to resend this campaign to.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
	<?php endif; ?>
<?php endif; ?>

	<br />

	<div>
		<input type="hidden" name="filter" value="<?php echo $this->_tpl_vars['filter']; ?>
" />
		<input value="<?php echo ((is_array($_tmp='Back')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" type="button" onclick="window.history.go(-1);" style="font-size:14px;" />
		<?php if ($this->_tpl_vars['total'] > 0): ?>
			<?php if ($this->_tpl_vars['admin']['pg_message_send']): ?>
				<input value="<?php echo ((is_array($_tmp='Send Now')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" type="submit" style="font-weight:bold; font-size:14px;" />
			<?php endif; ?>
		<?php endif; ?>
	</div>

</form>

<?php endif; ?>

<?php endif; ?>

<?php else: ?>

	<div>This campaign can not be reused because it contains a filter that uses OR pattern.</div>

<?php endif; ?>