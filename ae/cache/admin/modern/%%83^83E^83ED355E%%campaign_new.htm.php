<?php /* Smarty version 2.6.12, created on 2016-07-08 14:14:43
         compiled from campaign_new.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'campaign_new.htm', 2, false),array('modifier', 'default', 'campaign_new.htm', 14, false),array('modifier', 'escape', 'campaign_new.htm', 26, false),)), $this); ?>
<?php if ($this->_tpl_vars['hosted_down4'] != 'nobody'): ?>
<?php echo ((is_array($_tmp="Due to your account status, you are unable to send any campaigns.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

<a href="desk.php"><?php echo ((is_array($_tmp="Return to the Dashboard.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
<?php else: ?>

<script type="text/javascript">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "campaign_new.sharedv64.js", 'smarty_include_vars' => array('step' => 'type')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "campaign_new.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</script>

<form id="campaignform" method="POST" action="desk.php" onsubmit="campaign_save('next'); return false">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "campaign_new.header.htm", 'smarty_include_vars' => array('step' => 'type','highlight' => 0)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<input type="hidden" name="action" value="campaign_new">
	<input type="hidden" name="debug" value="<?php echo ((is_array($_tmp=@$_GET['debug'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
">

	<div class="h2_wrap_static">
		<div class="h2_content">

			<h5>
				<?php echo ((is_array($_tmp='Name Your Campaign')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			</h5><div class="line"></div>
			<div class="campaign_help">
				<?php echo ((is_array($_tmp="Enter a name to help you remember what this campaign is all about.  Only you will see the campaign name.  Your subscribers will not see this.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			</div>
			<div class="campaign_input">
				<input type="text" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['campaign']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" id="campaign_name" name="name" onkeyup="campaign_different()" style="font-weight:bold; font-size:14px; padding: 2px; width:250px;" />
			</div>
		</div>
	</div>

	<br />

	<div class="h2_wrap_static">
		<h5><?php echo ((is_array($_tmp='Choose Your Campaign Type')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
		<div class="campaign_input border_top_5" style=" margin-bottom:20px;">
			<input type="hidden" id="campaign_type" name="type" value="<?php echo $this->_tpl_vars['campaign']['type']; ?>
" />

			<div class="border_5"  style="  padding:10px;  ">
				<div id="campaign_type_single" onclick="return campaign_type_set('single'); campaign_different();" style="cursor: pointer" <?php if ($this->_tpl_vars['campaign']['type'] == 'single'): ?>class="selected"<?php endif; ?>>
					<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  cellpadding="0" cellspacing="2" border="0">
						<tr>
							<td><input type="radio" id="campaign_type_single_radio" <?php if ($this->_tpl_vars['campaign']['type'] == 'single' || $this->_tpl_vars['campaign']['type'] == 'recurring'): ?>checked<?php endif; ?>></td>
							<td class="campaign_types_head" style="font-weight:bold;"><?php echo ((is_array($_tmp='Regular Campaign')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td class="campaign_types_desc"><?php echo ((is_array($_tmp="Send a regular, one-time  email. Your email can contain links, images, special formatting, and more. This is the most common campaign type.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
						</tr>
					</table></div>
				</div>
			</div>
			<div class="border_5"  style=" padding:0 10px 10px 10px;">
				<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td width="400" valign="top">



							<div id="campaign_type_responder" onclick="return campaign_type_set('responder'); campaign_different();" style="cursor: pointer" <?php if ($this->_tpl_vars['campaign']['type'] == 'responder'): ?>class="selected"<?php endif; ?>>
								<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  cellpadding="0" cellspacing="2" border="0">
									<tr>
										<td><input type="radio" id="campaign_type_responder_radio" <?php if ($this->_tpl_vars['campaign']['type'] == 'responder'): ?>checked<?php endif; ?>></td>
										<td class="campaign_types_head"><?php echo ((is_array($_tmp='Auto Responder Campaign')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td class="campaign_types_desc"><?php echo ((is_array($_tmp="Create an automated campaign that will be sent a certain number of hours or days after someone subscribes.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
									</tr>
								</table></div>
							</div>
							<div id="campaign_type_deskrss" onclick="return campaign_type_set('deskrss'); campaign_different();" style="cursor: pointer; margin-top:10px;" <?php if ($this->_tpl_vars['campaign']['type'] == 'deskrss'): ?>class="selected"<?php endif; ?>>
								<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  cellpadding="0" cellspacing="2" border="0">
									<tr>
										<td><input type="radio" id="campaign_type_deskrss_radio" <?php if ($this->_tpl_vars['campaign']['type'] == 'deskrss'): ?>checked<?php endif; ?>></td>
										<td class="campaign_types_head"><?php echo ((is_array($_tmp='RSS Campaign')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td class="campaign_types_desc"><?php echo ((is_array($_tmp="Create a campaign that will send whenever an RSS feed is updated. Such as having an update send whenever you update your blog.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
									</tr>
								</table></div>
							</div>
							<div id="campaign_type_reminder" onclick="return campaign_type_set('reminder'); campaign_different();" style="cursor: pointer; margin-top:10px;" <?php if ($this->_tpl_vars['campaign']['type'] == 'reminder'): ?>class="selected"<?php endif; ?>>
								<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  cellpadding="0" cellspacing="2" border="0">
									<tr>
										<td class="campaign_types_head"><input type="radio" id="campaign_type_reminder_radio" <?php if ($this->_tpl_vars['campaign']['type'] == 'reminder'): ?>checked<?php endif; ?>></td>
										<td class="campaign_types_head"><?php echo ((is_array($_tmp='Subscriber Date Based Campaign')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td class="campaign_types_desc"><?php echo ((is_array($_tmp="Create a campaign based around a certain subscriber date. Can be used for birthday emails, anniversary emails, contract reminders, and more.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
									</tr>
								</table></div>
							</div>

						</td>
						<td width="25" valign="top">&nbsp;</td>
						<td width="400" valign="top">


							<div id="campaign_type_split" onclick="return campaign_type_set('split'); campaign_different();" style="cursor: pointer" <?php if ($this->_tpl_vars['campaign']['type'] == 'split'): ?>class="selected"<?php endif; ?>>
								<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  cellpadding="0" cellspacing="2" border="0">
									<tr>
										<td class="campaign_types_head"><input type="radio" id="campaign_type_split_radio" <?php if ($this->_tpl_vars['campaign']['type'] == 'split'): ?>checked<?php endif; ?>></td>
										<td class="campaign_types_head"><?php echo ((is_array($_tmp='Split Testing Campaign')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td class="campaign_types_desc"><?php echo ((is_array($_tmp="Test variations of your email campaign to see which is best. You will be able to setup multple emails that will send out.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
									</tr>
								</table></div>
							</div>

							<div id="campaign_type_text" onclick="return campaign_type_set('text'); campaign_different();" style="cursor: pointer; margin-top:10px;" <?php if ($this->_tpl_vars['campaign']['type'] == 'text'): ?>class="selected"<?php endif; ?>>
								<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  cellpadding="0" cellspacing="2" border="0">
									<tr>
										<td><input type="radio" id="campaign_type_text_radio" <?php if ($this->_tpl_vars['campaign']['type'] == 'text'): ?>checked<?php endif; ?>></td>
										<td class="campaign_types_head"><?php echo ((is_array($_tmp="Text-Only Campaign")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td class="campaign_types_desc"><?php echo ((is_array($_tmp="Send a one-time text-only email. For most cases we suggest sending a regular campaign as that would include both HTML and text versions.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
									</tr>
								</table></div>
							</div>
						</td>
					</tr>
				</table></div>
			</div>
    </div>
			<div style="float:right;">
				<input value='<?php echo ((is_array($_tmp="Save & Exit")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' type="button" onclick="campaign_save('exit')" style="font-size:14px;" />
				<input value='<?php echo ((is_array($_tmp='Save')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' type="button" onclick="campaign_save('nothing')" style="font-size:14px">
			</div>
			<input type="button" value="<?php echo ((is_array($_tmp='Next')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="campaign_save('next')" style="font-weight:bold; font-size:14px;" />

		
	</div>

	<script type="text/javascript">
		campaign_save_auto_runagain();
		</script>
	</form>
	<?php endif; ?>