<?php /* Smarty version 2.6.12, created on 2016-07-08 14:23:07
         compiled from campaign_new_summary.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'campaign_new_summary.htm', 2, false),array('modifier', 'count', 'campaign_new_summary.htm', 37, false),array('modifier', 'numformat', 'campaign_new_summary.htm', 48, false),array('modifier', 'truncate', 'campaign_new_summary.htm', 78, false),array('modifier', 'adesk_ischecked', 'campaign_new_summary.htm', 138, false),array('modifier', 'adesk_isselected', 'campaign_new_summary.htm', 292, false),array('function', 'adesk_calendar', 'campaign_new_summary.htm', 9, false),)), $this); ?>
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

<?php echo smarty_function_adesk_calendar(array('base' => ".."), $this);?>

<script type="text/javascript">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "campaign_new.shared.js", 'smarty_include_vars' => array('step' => 'summary')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "campaign_new_summary.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error.mailer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<form id="campaignform" method="POST" action="desk.php" onsubmit="return false">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "campaign_new.header.htm", 'smarty_include_vars' => array('step' => 'summary','highlight' => 3)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<input type="hidden" name="action" value="campaign_new_summary" />
	<input type="hidden" id="campaign_public" name="public" value="<?php echo $this->_tpl_vars['campaign']['public']; ?>
" />
	<input type="hidden" id="campaign_autopost" name="autopost" value="<?php if ($this->_tpl_vars['campaign']['tweet'] || $this->_tpl_vars['campaign']['facebook']): ?>1<?php else: ?>0<?php endif; ?>" />
	<input type="hidden" id="campaign_tweet" name="tweet" value="<?php echo $this->_tpl_vars['campaign']['tweet']; ?>
" />
	<input type="hidden" id="campaign_facebook" name="facebook" value="<?php echo $this->_tpl_vars['campaign']['facebook']; ?>
" />
	<input type="hidden" id="campaign_tracking" name="tracking" value="<?php if ($this->_tpl_vars['campaign']['trackreads'] || $this->_tpl_vars['campaign']['tracklinks'] != 'none'): ?>1<?php else: ?>0<?php endif; ?>" />
	<input type="hidden" id="campaign_schedule" name="schedule" value="<?php echo $this->_tpl_vars['campaign']['schedule']; ?>
" />
	<input type="hidden" id="campaign_responder_existing" name="responder_existing" value="<?php echo $this->_tpl_vars['campaign']['responder_existing']; ?>
">
	<input type="hidden" id="campaign_willrecur" name="willrecur" value="<?php echo $this->_tpl_vars['campaign']['willrecur']; ?>
">

	<h5><?php echo ((is_array($_tmp="Summary &amp; Options")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>

	<div class="h2_wrap_static">
		<div class="campaign_summary">
			<div class="campaign_summary_options">
				<a href="desk.php?action=campaign_new_list&id=<?php echo $this->_tpl_vars['campaign']['id']; ?>
"><?php echo ((is_array($_tmp='edit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			</div>
			<div>
				<?php if (count($this->_tpl_vars['campaign']['lists']) > 1): ?>
					<?php echo ((is_array($_tmp='Sending to lists')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				<?php else: ?>
					<?php echo ((is_array($_tmp='Sending to list')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				<?php endif; ?>
				<strong><?php echo $this->_tpl_vars['listnames']; ?>
</strong>
				<?php if ($this->_tpl_vars['segmentname'] != ""): ?>
				<?php echo ((is_array($_tmp='with segment')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				<strong><?php echo $this->_tpl_vars['segmentname']; ?>
</strong>
				<?php endif; ?>
				<?php if ($this->_tpl_vars['campaign']['type'] != 'responder'): ?>
				<?php echo ((is_array($_tmp="(A total of")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <strong><?php echo ((is_array($_tmp=$this->_tpl_vars['subtotal'])) ? $this->_run_mod_handler('numformat', true, $_tmp, 0, '.', ',') : smarty_modifier_numformat($_tmp, 0, '.', ',')); ?>
</strong> <?php echo ((is_array($_tmp="subscribers)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				<?php endif; ?>
			</div>
		</div>

		<div class="campaign_summary">
			<div class="campaign_summary_options">
				<?php if ($this->_tpl_vars['campaign']['type'] == 'split'): ?>
				<a href="desk.php?action=campaign_new_splitmessage&id=<?php echo $this->_tpl_vars['campaign']['id']; ?>
"><?php echo ((is_array($_tmp='edit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				<?php elseif ($this->_tpl_vars['campaign']['type'] == 'text'): ?>
				<a href="desk.php?action=campaign_new_text&id=<?php echo $this->_tpl_vars['campaign']['id']; ?>
"><?php echo ((is_array($_tmp='edit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				<?php else: ?>
				<a href="desk.php?action=campaign_new_message&id=<?php echo $this->_tpl_vars['campaign']['id']; ?>
"><?php echo ((is_array($_tmp='edit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>

				<?php endif; ?>
			</div>
			<div class="campaign_summary_options" style="margin-right:10px;">
				<a href="#" onclick="campaign_open_preview(); return false"><?php echo ((is_array($_tmp='preview')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			</div>
			<div>
				<?php if ($this->_tpl_vars['campaign']['type'] == 'split'): ?>
					<?php echo ((is_array($_tmp='The message subjects will be')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				<?php else: ?>
					<?php echo ((is_array($_tmp='The message subject will be')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				<?php endif; ?>
				<strong>
					<?php $_from = $this->_tpl_vars['messages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['msgloop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['msgloop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['e']):
        $this->_foreach['msgloop']['iteration']++;
?>
					<?php if ($this->_tpl_vars['e']['subject'] == "" && ( $this->_tpl_vars['e']['htmlfetch'] == 'send' || $this->_tpl_vars['e']['textfetch'] == 'send' )): ?>
					<?php echo ((is_array($_tmp='Determined by page title when fetched')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

					<?php else: ?>
					<?php echo ((is_array($_tmp=$this->_tpl_vars['e']['subject'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 75) : smarty_modifier_truncate($_tmp, 75));  if (! ($this->_foreach['msgloop']['iteration'] == $this->_foreach['msgloop']['total'])): ?>,<?php endif; ?>
					<?php endif; ?>
					<?php endforeach; endif; unset($_from); ?>
				</strong>
			</div>
		</div>

		<div id="campaign_tracking_yes" class="campaign_summary" style=" <?php if (( ! $this->_tpl_vars['campaign']['trackreads'] && $this->_tpl_vars['campaign']['tracklinks'] == 'none' )): ?>display:none<?php endif; ?>">
			<div class="campaign_summary_options">
				<a href="#" onclick="adesk_dom_toggle_display('campaign_trackopts', 'block');adesk_dom_toggle_class('campaign_tracking_yes', 'campaign_summary', 'campaign_summary_top');return false"><?php echo ((is_array($_tmp='options')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			</div>
			<div class="campaign_summary_options" style="margin-right:10px;">
				<a href="#" onclick="campaign_markreads(false); campaign_different(); return false"><?php echo ((is_array($_tmp='disable')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			</div>
			<div>
				<?php echo ((is_array($_tmp="Reads (opens) and/or link clicks will be tracked")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			</div>
		</div>

		<div id="campaign_trackopts" style="display: none;" class="campaign_summary_bottom">
			<div <?php if ($this->_tpl_vars['campaign']['type'] == 'text'): ?>style="display:none"<?php endif; ?>>
				<label>
					<input type="checkbox" name="trackreads" id="campaign_trackreads_checkbox" value="1" onchange="campaign_checktrackopts()" <?php if ($this->_tpl_vars['campaign']['trackreads']): ?>checked="checked"<?php endif; ?> />
					<?php echo ((is_array($_tmp="Enable Read/Open Tracking")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				</label>
				(<a href="#" onclick="campaign_action_load(0); return false"><span id="actioncount0"><?php echo $this->_tpl_vars['readactioncount']; ?>
</span> <?php echo ((is_array($_tmp='Actions')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>)
			</div>

			<div <?php if ($this->_tpl_vars['linkcount'] == 0): ?>style="display:none"<?php endif; ?>>
				<div>
					<label>
						<input type="checkbox" name="tracklinks" id="campaign_tracklinks_checkbox" onchange="campaign_checktrackopts()" <?php if ($this->_tpl_vars['campaign']['tracklinks'] != 'none' && $this->_tpl_vars['linkcount'] > 0): ?>checked="checked"<?php endif; ?> />
						<?php echo ((is_array($_tmp='Enable Link Tracking')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

					</label>
				</div>

				<div id="linksdiv" style="margin-left:25px; <?php if ($this->_tpl_vars['campaign']['tracklinks'] == 'none' || $this->_tpl_vars['linkcount'] == 0): ?>display:none;<?php endif; ?>">
					<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  cellpadding="5" cellspacing="0" border="0" class="campaign_new_links">
						<tr>
							<td align="center"><input id="linksselectall" name="linksselectall" type="checkbox" value="1" checked="checked" onclick="adesk_form_check_selection_element_all('tlinkshtmllist', this.checked);" /></td>
							<td colspan="2"><?php echo ((is_array($_tmp="Link/URL")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
							<td width="70"><?php echo ((is_array($_tmp='Actions')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
							<td><?php echo ((is_array($_tmp="Short Name (Optional - For Reports Only)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
						</tr>

						<tbody id="tlinkshtmllist">
							<?php $_from = $this->_tpl_vars['messages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['m']):
?>
							<?php if (count ( $this->_tpl_vars['messages'] ) > 1 && count ( $this->_tpl_vars['m']['links'] ) > 0): ?>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td><strong><?php echo $this->_tpl_vars['m']['subject']; ?>
</strong></td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<?php endif; ?>
							<?php $_from = $this->_tpl_vars['m']['links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['e']):
?>
							<tr>
								<td>
									<input type="hidden" name="linkid[<?php echo $this->_tpl_vars['e']['id']; ?>
]" value="<?php echo $this->_tpl_vars['e']['id']; ?>
" />
									<input type="checkbox" name="linktracked[<?php echo $this->_tpl_vars['e']['id']; ?>
]" value="1" <?php echo ((is_array($_tmp=$this->_tpl_vars['e']['tracked'])) ? $this->_run_mod_handler('adesk_ischecked', true, $_tmp) : smarty_modifier_adesk_ischecked($_tmp)); ?>
 />
								</td>
								<td>
									<a href="<?php echo $this->_tpl_vars['e']['link']; ?>
" target="_blank"><img src="images/windows-16-16.png" border="0" /></a>
								</td>
								<td><?php echo $this->_tpl_vars['e']['link']; ?>
</td>
								<td>
									<a href="#" onclick="campaign_action_load(<?php echo $this->_tpl_vars['e']['id']; ?>
); return false"><span id="actioncount<?php echo $this->_tpl_vars['e']['id']; ?>
"><?php echo $this->_tpl_vars['e']['actioncount']; ?>
</span> <?php echo ((is_array($_tmp="Action(s)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
								</td>
								<td>
									<input type="text" name="linkname[<?php echo $this->_tpl_vars['e']['id']; ?>
]" value="<?php echo $this->_tpl_vars['e']['name']; ?>
" />
								</td>
							</tr>
							<?php endforeach; endif; unset($_from); ?>
							<?php endforeach; endif; unset($_from); ?>
						</tbody>
					</table></div>
				</div>
			</div>

			<?php if ($this->_tpl_vars['showgread']): ?>
			<div>
				<label>
					<input type="checkbox" id="trackreadsanalytics" name="trackreadsanalytics" value="1" <?php echo ((is_array($_tmp=$this->_tpl_vars['campaign']['trackreadsanalytics'])) ? $this->_run_mod_handler('adesk_ischecked', true, $_tmp) : smarty_modifier_adesk_ischecked($_tmp)); ?>
 />
					<?php echo ((is_array($_tmp="Enable Google Analytics Read/Open Tracking")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				</label>
			</div>
			<?php endif; ?>

			<?php if ($this->_tpl_vars['showglink'] && $this->_tpl_vars['linkcount'] > 0): ?>
			<div>
				<label>
					<input type="checkbox" id="tracklinksanalytics" name="tracklinksanalytics" value="1" <?php echo ((is_array($_tmp=$this->_tpl_vars['campaign']['tracklinksanalytics'])) ? $this->_run_mod_handler('adesk_ischecked', true, $_tmp) : smarty_modifier_adesk_ischecked($_tmp)); ?>
 />
					<?php echo ((is_array($_tmp='Enable Google Analytics Link Tracking')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				</label>
			</div>
			<?php endif; ?>
		</div>

		<div id="campaign_tracking_no" class="campaign_summary" style=" <?php if ($this->_tpl_vars['campaign']['trackreads'] || ( $this->_tpl_vars['campaign']['tracklinks'] != 'none' && $this->_tpl_vars['linkcount'] > 0 )): ?>display:none<?php endif; ?>">
			<div class="campaign_summary_options">
				<a href="#" onclick="campaign_markreads(true); campaign_different(); return false"><?php echo ((is_array($_tmp='enable')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			</div>
			<div>
				<?php echo ((is_array($_tmp="Reads (opens) and link clicks will not be tracked")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			</div>
		</div>

		<?php if ($this->_tpl_vars['isShareable'] && ( $this->_tpl_vars['isTweetable'] || $this->_tpl_vars['isFacebookable'] )): ?>
		<div id="campaign_autopost" class="campaign_summary">
			<div class="campaign_summary_options">
				<?php if ($this->_tpl_vars['isTweetable']): ?>
				<a href="#" id="campaign_tweet_link_yes" onclick="campaign_summary_option_dual('tweet', 'facebook', 0, 'autopost'); campaign_different(); return false" style="<?php if (! $this->_tpl_vars['campaign']['tweet']): ?>display:none;<?php endif; ?>"><?php echo ((is_array($_tmp='disable twitter')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				<a href="#" id="campaign_tweet_link_no" onclick="campaign_summary_option_dual('tweet', 'facebook', 1, 'autopost'); campaign_different(); return false" style="<?php if ($this->_tpl_vars['campaign']['tweet']): ?>display:none;<?php endif; ?>"><?php echo ((is_array($_tmp='enable twitter')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				<?php endif; ?>
				<?php if ($this->_tpl_vars['isTweetable'] && $this->_tpl_vars['isFacebookable']): ?>
				&nbsp;
				<?php endif; ?>
				<?php if ($this->_tpl_vars['isFacebookable']): ?>
				<a href="#" id="campaign_facebook_link_yes" onclick="campaign_summary_option_dual('facebook', 'tweet', 0, 'autopost'); campaign_different(); return false" style="<?php if (! $this->_tpl_vars['campaign']['facebook']): ?>display:none;<?php endif; ?>"><?php echo ((is_array($_tmp='disable facebook')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				<a href="#" id="campaign_facebook_link_no" onclick="campaign_summary_option_dual('facebook', 'tweet', 1, 'autopost'); campaign_different(); return false" style="<?php if ($this->_tpl_vars['campaign']['facebook']): ?>display:none;<?php endif; ?>"><?php echo ((is_array($_tmp='enable facebook')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				<?php endif; ?>
			</div>
			<?php if ($this->_tpl_vars['isTweetable'] && $this->_tpl_vars['isFacebookable']): ?>
			<div id="campaign_autopost_label_yes" style="<?php if (! ( $this->_tpl_vars['campaign']['tweet'] && $this->_tpl_vars['campaign']['facebook'] )): ?>display:none;<?php endif; ?>">
				<?php echo ((is_array($_tmp="This campaign will automatically be posted to Twitter &amp; Facebook")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			</div>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['isTweetable']): ?>
			<div id="campaign_autopost_label_tweet" style="<?php if (! ( $this->_tpl_vars['campaign']['tweet'] && ! $this->_tpl_vars['campaign']['facebook'] )): ?>display:none;<?php endif; ?>">
				<?php echo ((is_array($_tmp='This campaign will automatically be posted to Twitter')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			</div>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['isFacebookable']): ?>
			<div id="campaign_autopost_label_facebook" style="<?php if (! ( ! $this->_tpl_vars['campaign']['tweet'] && $this->_tpl_vars['campaign']['facebook'] )): ?>display:none;<?php endif; ?>">
				<?php echo ((is_array($_tmp='This campaign will automatically be posted to Facebook')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			</div>
			<?php endif; ?>
			<div id="campaign_autopost_label_no" style="<?php if ($this->_tpl_vars['campaign']['tweet'] || $this->_tpl_vars['campaign']['facebook']): ?>display:none;<?php endif; ?>">
				<?php if ($this->_tpl_vars['isTweetable'] && $this->_tpl_vars['isFacebookable']): ?>
				<?php echo ((is_array($_tmp="This campaign will NOT be posted to Twitter &amp; Facebook")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				<?php elseif ($this->_tpl_vars['isTweetable']): ?>
				<?php echo ((is_array($_tmp='This campaign will NOT be posted to Twitter')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				<?php elseif ($this->_tpl_vars['isFacebookable']): ?>
				<?php echo ((is_array($_tmp='This campaign will NOT be posted to Facebook')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				<?php endif; ?>
			</div>
		</div>
		<?php endif; ?>

		<?php if ($this->_tpl_vars['isForPublic']): ?>
		<div id="campaign_public_yes" class="campaign_summary" style="<?php if (! $this->_tpl_vars['campaign']['public']): ?>display:none<?php endif; ?>">
			<div class="campaign_summary_options">
				<a href="#" onclick="campaign_summary_option('public', 0); campaign_different(); return false"><?php echo ((is_array($_tmp='make private')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			</div>
			<div>
				<?php echo ((is_array($_tmp="This campaign will be shown in your public archive.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			</div>
		</div>

		<div id="campaign_public_no" class="campaign_summary" style="<?php if ($this->_tpl_vars['campaign']['public']): ?>display:none<?php endif; ?>">
			<div class="campaign_summary_options">
				<a href="#" onclick="campaign_summary_option('public', 1); campaign_different(); return false"><?php echo ((is_array($_tmp='make public')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			</div>
			<div>
				<?php echo ((is_array($_tmp="This campaign will not be shown in your public archive.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			</div>
		</div>
		<?php endif; ?>


		<div id="campaign_inboxpreview" class="campaign_summary">
			<div class="campaign_summary_options">
				<a href="#" onclick="campaign_open_inboxpreview(); return false"><?php echo ((is_array($_tmp='view inbox preview')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			</div>
			<div id="inboxpreview_result"><?php echo ((is_array($_tmp="Checking your email design...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
		</div>
		<script>campaign_run_inboxpreview();</script>

<!-- In future coming this feature more advanced AwebDesk Admin -->
 
		
		
		
				<div id="campaign_spamcheck" class="campaign_summary">
			<div id="spamcheck_details_link" class="campaign_summary_options">
				<a href="#" onclick="campaign_spamcheck_toggle(); return false"><?php echo ((is_array($_tmp='view details')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			</div>
			<div id="spamcheck_result"><?php echo ((is_array($_tmp="Checking your email against spam filters...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
		</div>
		<div id="spamcheck_details" class="campaign_summary_bottom" style="display:none;">
			<div><?php echo ((is_array($_tmp="Things that could cause your message to be flagged as spam:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
			<div id="spamcheck_table"></div>
		</div>
		<script>campaign_run_spamcheck();</script>
		
		
		
		
		
		
		
		
		
		
		
		 
<!-- --> 

		<?php if ($this->_tpl_vars['campaign']['type'] == 'deskrss'): ?>
		<div id="campaign_deskrss" class="campaign_summary">
			<?php echo ((is_array($_tmp="This campaign will check for new posts (and will send if new posts are found) every")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			<select name="deskrss_interval" id="deskrss_interval" onchange="campaign_different();">
				<?php $_from = $this->_tpl_vars['recur_intervals']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
				<option id="deskrss_interval_option_<?php echo $this->_tpl_vars['k']; ?>
" value="<?php echo $this->_tpl_vars['k']; ?>
" <?php echo ((is_array($_tmp=$this->_tpl_vars['campaign']['deskrss_interval'])) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, $this->_tpl_vars['k']) : smarty_modifier_adesk_isselected($_tmp, $this->_tpl_vars['k'])); ?>
><?php echo $this->_tpl_vars['v']; ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
			</select>
		</div>
		<?php endif; ?>


		<div id="campaign_schedule_div" <?php if ($this->_tpl_vars['campaign']['type'] == 'reminder' || $this->_tpl_vars['campaign']['type'] == 'responder'): ?>style="display:none"<?php endif; ?>>
			<div id="campaign_schedule_no" class="campaign_summary_green" style="<?php if ($this->_tpl_vars['campaign']['schedule']): ?>display:none<?php endif; ?>">
				<div class="campaign_summary_options">
					<a href="#" onclick="campaign_summary_option('schedule', 1); $('nextbutton').value = campaign_str_finish; campaign_different(); return false"><?php echo ((is_array($_tmp='schedule')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				</div>
				<div>
					<?php echo ((is_array($_tmp="This campaign will send immediately.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				</div>
			</div>

			<div id="campaign_schedule_yes" class="campaign_summary_green" style="<?php if (! $this->_tpl_vars['campaign']['schedule']): ?>display:none<?php endif; ?>">
				<div class="campaign_summary_options">
					<a href="#" onclick="campaign_summary_option('schedule', 0); $('nextbutton').value = campaign_str_sendnow; campaign_different(); return false"><?php echo ((is_array($_tmp='send immediately')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				</div>
				<div>
					<?php echo ((is_array($_tmp='This campaign will be sent out on')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

					<input type="text" id="campaign_scheduledate" name="scheduledate">
					<input id="campaign_scheduledateCalendar" type="button" value="<?php echo ((is_array($_tmp=" + ")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" />
					<script>
						<?php echo '
						Calendar.setup({inputField: "campaign_scheduledate", ifFormat: \'%Y/%m/%d\', button: "campaign_scheduledateCalendar", showsTime: false, timeFormat: "24"});
						'; ?>

					</script>
					<?php echo ((is_array($_tmp='at the time of')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

					<select id="campaign_schedulehour" name="schedulehour">
						<?php $_from = $this->_tpl_vars['hours']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['e']):
?>
						<option value="<?php echo $this->_tpl_vars['e']; ?>
"><?php echo $this->_tpl_vars['e']; ?>
</option>
						<?php endforeach; endif; unset($_from); ?>
					</select>
					:
					<select id="campaign_scheduleminute" name="scheduleminute">
						<?php $_from = $this->_tpl_vars['minutes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['e']):
?>
						<option value="<?php echo $this->_tpl_vars['e']; ?>
"><?php echo $this->_tpl_vars['e']; ?>
</option>
						<?php endforeach; endif; unset($_from); ?>
					</select>

					(<?php echo $this->_tpl_vars['admin']['local_zoneid']; ?>
, GMT <?php echo $this->_tpl_vars['tzoffset']; ?>
)
				</div>
			</div>
		</div>

		<div id="campaign_willrecur_div" <?php if (( $this->_tpl_vars['campaign']['type'] != 'single' && $this->_tpl_vars['campaign']['type'] != 'recurring' ) || ( ! $this->_tpl_vars['hasfetch'] && ! $this->_tpl_vars['hasrss'] )): ?>style="display:none"<?php endif; ?>>
			<div id="campaign_willrecur_no" class="campaign_summary" style="<?php if ($this->_tpl_vars['campaign']['willrecur']): ?>display:none<?php endif; ?>">
				<div class="campaign_summary_options">
					<a href="#" onclick="campaign_summary_option('willrecur', 1); campaign_different(); return false"><?php echo ((is_array($_tmp='enable')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				</div>
				<div>
					<?php echo ((is_array($_tmp="This campaign will not automatically recur.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				</div>
			</div>

			<div id="campaign_willrecur_yes" class="campaign_summary" style="<?php if (! $this->_tpl_vars['campaign']['willrecur']): ?>display:none<?php endif; ?>">
				<div class="campaign_summary_options">
					<a href="#" onclick="campaign_summary_option('willrecur', 0); campaign_different(); return false"><?php echo ((is_array($_tmp='disable')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				</div>
				<div>
					<?php echo ((is_array($_tmp='This campaign will automatically recur every')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

					<select id="campaign_recurring" name="recurring">
						<option value="day1"><?php echo ((is_array($_tmp='Every Day')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
						<option value="day2"><?php echo ((is_array($_tmp='Every Other Day')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
						<option value="week1"><?php echo ((is_array($_tmp='Every Week')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
						<option value="week2"><?php echo ((is_array($_tmp='Every Other Week')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
						<option value="month1"><?php echo ((is_array($_tmp='Every Month')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
						<option value="month2"><?php echo ((is_array($_tmp='Every Other Month')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
						<option value="quarter1"><?php echo ((is_array($_tmp='Every Quarter')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
						<option value="quarter2"><?php echo ((is_array($_tmp='Every Other Quarter')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
						<option value="year1"><?php echo ((is_array($_tmp='Every Year')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
						<option value="year2"><?php echo ((is_array($_tmp='Every Other Year')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
					</select>
				</div>
			</div>
		</div>

		<div id="campaign_date_based_options" <?php if ($this->_tpl_vars['campaign']['type'] != 'reminder'): ?>style="display:none"<?php endif; ?>>
			<div class="campaign_summary_green_top">
				<div>
					<div>
						<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td style="font-size:12px;"><?php echo ((is_array($_tmp='Will send when the subscribers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
								<td>
									<select name="reminder_field" id="reminder_field" onchange="campaign_reminder_compile();campaign_reminder_issystem();">
										<optgroup label="<?php echo ((is_array($_tmp='Standard Subscriber Fields')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
">
											<option value="sdate"><?php echo ((is_array($_tmp='Subscription Date')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
											<option value="cdate"><?php echo ((is_array($_tmp='Creation Date')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
										</optgroup>
										<optgroup label="<?php echo ((is_array($_tmp='Global Subscriber Fields')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
">
											<?php $_from = $this->_tpl_vars['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['f']):
?>
											<option value="<?php echo $this->_tpl_vars['f']['id']; ?>
"><?php echo $this->_tpl_vars['f']['title']; ?>
</option>
											<?php endforeach; endif; unset($_from); ?>
										</optgroup>
										<optgroup label="<?php echo ((is_array($_tmp='List-Related Fields')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
"></optgroup>
									</select>
								</td>
								<td><span id="reminder_format_nonsystem" style="display:none;"><?php echo ((is_array($_tmp='using the format')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></td>
								<td>
									<select name="reminder_format" id="reminder_format" style="display:none;">
										<option value="yyyy-mm-dd"><?php echo ((is_array($_tmp="yyyy-mm-dd")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
										<option value="yyyy/mm/dd"><?php echo ((is_array($_tmp="yyyy/mm/dd")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
										<option value="yyyymmdd"><?php echo ((is_array($_tmp='yyyymmdd')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
										<option value="mm/dd/yyyy"><?php echo ((is_array($_tmp="mm/dd/yyyy")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
										<option value="dd/mm/yyyy"><?php echo ((is_array($_tmp="dd/mm/yyyy")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
										<option value="dd.mm.yyyy"><?php echo ((is_array($_tmp="dd.mm.yyyy")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
									</select>
								</td>
								<td style="font-size:12px;"><?php echo ((is_array($_tmp='matches the current')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
								<td>
									<select name="reminder_type" id="reminder_type" onchange="campaign_reminder_compile()">
										<option value="month_day"><?php echo ((is_array($_tmp="month & day")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
										<option value="year_month_day"><?php echo ((is_array($_tmp="year, month & day")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
									</select>
								</td>
								<td>
									<select name="reminder_offset_sign" id="reminder_offset_sign" onchange="campaign_reminder_compile()">
										<option value="="><?php echo 'exactly'; ?>
</option>
										<option value="-"><?php echo 'minus'; ?>
</option>
										<option value="+"><?php echo 'plus'; ?>
</option>
									</select>
								</td>
								<td>
									<input name="reminder_offset" id="reminder_offset" type="text" value="0" size="3" onkeyup="campaign_reminder_compile();" />
									<select name="reminder_offset_type" id="reminder_offset_type" size="1" onchange="campaign_reminder_compile()">
										<option value="day"><?php echo ((is_array($_tmp='Days')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
										<option value="week"><?php echo ((is_array($_tmp='Weeks')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
										<option value="month"><?php echo ((is_array($_tmp='Months')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
										<option value="year"><?php echo ((is_array($_tmp='Years')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
									</select>
								</td>
							</tr>
						</table></div>
					</div>
				</div>
			</div>
			<div id="campaign_reminder_example" class="campaign_summary_green_bottom" style="font-style:italic; text-align:center;">
				<?php echo $this->_tpl_vars['reminder_example']; ?>

			</div>
		</div>

		<div id="campaign_responder_options" <?php if ($this->_tpl_vars['campaign']['type'] != 'responder'): ?>style="display:none"<?php endif; ?>>
			<div id="campaign_responder_existing_no" class="campaign_summary" <?php if ($this->_tpl_vars['campaign']['responder_existing']): ?>style="display:none"<?php endif; ?>>
				<div class="campaign_summary_options">
					<a href="#" onclick="campaign_summary_option('responder_existing', 1); campaign_different(); return false"><?php echo ((is_array($_tmp='send to existing subscribers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				</div>
				<div>
					<?php echo ((is_array($_tmp="Existing %s subscribers will not receive this auto responder campaign.")) ? $this->_run_mod_handler('alang', true, $_tmp, $this->_tpl_vars['subtotal']) : smarty_modifier_alang($_tmp, $this->_tpl_vars['subtotal'])); ?>

				</div>
			</div>

			<div id="campaign_responder_existing_yes" class="campaign_summary" <?php if (! $this->_tpl_vars['campaign']['responder_existing']): ?>style="display:none"<?php endif; ?>>
				<div class="campaign_summary_options">
					<a href="#" onclick="campaign_summary_option('responder_existing', 0); campaign_different(); return false"><?php echo ((is_array($_tmp="don't send to existing subscribers")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				</div>
				<div>
					<?php echo ((is_array($_tmp="Any existing subscriber may receive this auto responder campaign.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				</div>
			</div>

			<div class="campaign_summary_green">
				<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td><?php echo ((is_array($_tmp='This auto responder will send')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
&nbsp;</td>
						<td>
							<select id="campaign_responder_timeframe" onchange="campaign_responder_switch(this.value)">
								<option value="immed"><?php echo ((is_array($_tmp='immediately')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
								<option value="specific"><?php echo ((is_array($_tmp='after a specific timeframe')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
							</select>
						</td>
						<td>
							<span id="campaign_responder_inputs">
								&nbsp;(
								<input name="respondday" id="respondday" type="text" value="0" size="5" style="width:20px;">
								<?php echo ((is_array($_tmp='days')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

								&amp;
								<input name="respondhour" id="respondhour" type="text" value="0" size="5" style="width:20px;">
								<?php echo ((is_array($_tmp='hours')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

								) &nbsp;
							</span>
						</td>
						<td><?php echo ((is_array($_tmp='when a subscriber subscribes')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
.</td>
					</tr>
				</table></div>
			</div>
		</div>

		<h2 style="margin-top:20px;"><?php echo ((is_array($_tmp='Send a test email')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>

		<div class="campaign_summary">

			<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>
						<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'autocomplete.inc.htm', 'smarty_include_vars' => array('fieldPrefix' => 'subscriber','fieldID' => 'subscriberEmailTestField','fieldName' => 'testemail','fieldSize' => '25','fieldValue' => $this->_tpl_vars['admin']['email'],'fieldStyle' => 'width:250px;')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
						<script>$('subscriberEmailTestField').onkeypress = adesk_ui_stopkey_enter;</script>

						<select name="testemailtype" id="testemailtype" size="1" style="display:none;">
							<option value="mime"><?php echo ((is_array($_tmp='HTML and TEXT')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
							<option value="html"><?php echo ((is_array($_tmp='HTML')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
							<option value="text"><?php echo ((is_array($_tmp='Text')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
						</select>

						<select name="testemailsplit" id="testemailsplit" size="1" <?php if ($this->_tpl_vars['campaign']['type'] != 'split'): ?>style="display:none"<?php endif; ?> onchange="campaign_set_emailtest(this.value);">
							<?php $_from = $this->_tpl_vars['messages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['e']):
?>
							<option value="<?php echo $this->_tpl_vars['e']['id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['e']['subject'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 50) : smarty_modifier_truncate($_tmp, 50)); ?>
</option>
							<?php endforeach; endif; unset($_from); ?>
						</select>
						<input type="button" value='<?php echo ((is_array($_tmp='Send Test Email')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp));  if ($this->_tpl_vars['demoMode']): ?> (Disabled)<?php endif; ?>' onclick="campaign_send_emailtest();" class="adesk_button_ok" <?php if ($this->_tpl_vars['demoMode']): ?>disabled="disabled"<?php endif; ?> />
					</td>
				</tr>
			</table></div>
		</div>




	</div>

	<br />

	<div>
		<div style="float:right;">
			<input value='<?php echo ((is_array($_tmp="Save & Exit")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' type="button" onclick="campaign_save('exit')" style="font-size:14px;" />
			<input value='<?php echo ((is_array($_tmp='Save')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' type="button" onclick="campaign_save('nothing')" style="font-size:14px;" />
		</div>
		<input value="<?php echo ((is_array($_tmp='Back')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" type="button" onclick="campaign_save('back')" style="font-size:14px;" />
		<input id="nextbutton" value="<?php if (in_array ( $this->_tpl_vars['campaign']['type'] , array ( 'responder' , 'reminder' , 'deskrss' ) ) || $this->_tpl_vars['campaign']['schedule']):  echo ((is_array($_tmp='Finish')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp));  else:  echo ((is_array($_tmp='Send Now')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp));  endif; ?>" type="button" onclick="campaign_save('next')" style="font-weight:bold; font-size:14px;" />
	</div>

	<script type="text/javascript">
		campaign_save_auto_runagain();
		</script>
	</form>

	<?php $_from = $this->_tpl_vars['messages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['m']):
?>
	<?php $_from = $this->_tpl_vars['m']['links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['e']):
?>
	<div id="link_actions<?php echo $this->_tpl_vars['e']['id']; ?>
" class="adesk_modal" align="center" style="display:none;">
		<div class="adesk_modal_inner" align="left">
			<form action="desk.php" method="POST">
				<h3 class="m-b"><?php echo ((is_array($_tmp="When this link is clicked...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
				<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['e']['actionid']; ?>
">
				<input type="hidden" name="campaignid" value="<?php echo $this->_tpl_vars['campaign']['id']; ?>
">
				<input type="hidden" name="linkid" value="<?php echo $this->_tpl_vars['e']['id']; ?>
">
				<input type="hidden" name="type" value="link">
				<p><?php echo ((is_array($_tmp="What should happen when this action takes place?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</p>

				<div id="link_actions_div<?php echo $this->_tpl_vars['e']['id']; ?>
"></div>

				<div style=" margin-top:10px; margin-bottom:10px;">
					<a href="#" onclick="campaign_action_new(<?php echo $this->_tpl_vars['e']['id']; ?>
, campaign_action_actioncount++, 'subscribe', 0, 0); return false;" style="display:block; background:url(images/add2-16-16.png); background-repeat:no-repeat; background-position:left; padding-left:20px; padding-top:2px; padding-bottom:2px;"><?php echo ((is_array($_tmp='Add additional action')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				</div>

				<input type="button" id="form_submit" class="adesk_button_ok" value="<?php echo ((is_array($_tmp='Save')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="campaign_action_save(<?php echo $this->_tpl_vars['e']['id']; ?>
)">
				<input type="button" class="adesk_button_cancel" value='<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="campaign_action_hide(<?php echo $this->_tpl_vars['e']['id']; ?>
)">
			</form>
		</div>
	</div>
	<?php endforeach; endif; unset($_from); ?>
	<?php endforeach; endif; unset($_from); ?>

	<div id="link_actions0" class="adesk_modal" align="center" style="display:none;">
		<div class="adesk_modal_inner" align="left">
			<form action="desk.php" method="POST">
				<h3 class="m-b"><?php echo ((is_array($_tmp="When this message is opened...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
				<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['readactionid']; ?>
">
				<input type="hidden" name="campaignid" value="<?php echo $this->_tpl_vars['campaign']['id']; ?>
">
				<input type="hidden" name="linkid" value="0">
				<input type="hidden" name="type" value="link">
				<p><?php echo ((is_array($_tmp="What should happen when this action takes place?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</p>

				<div id="link_actions_div0"></div>

				<div style=" margin-top:10px; margin-bottom:10px;">
					<a href="#" onclick="campaign_action_new(0, campaign_action_actioncount++, 'subscribe', 0, 0); return false;" style="display:block; background:url(images/add2-16-16.png); background-repeat:no-repeat; background-position:left; padding-left:20px; padding-top:2px; padding-bottom:2px;"><?php echo ((is_array($_tmp='Add additional action')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				</div>

				<input type="button" id="form_submit" class="adesk_button_ok" value="<?php echo ((is_array($_tmp='Save')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="campaign_action_save(0)">
				<input type="button" class="adesk_button_cancel" value='<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="campaign_action_hide(0)">
			</form>
		</div>
	</div>

	<script type="text/javascript">
		$("campaign_scheduledate").value = '<?php echo $this->_tpl_vars['currentdate']; ?>
';
		$("campaign_schedulehour").value = '<?php echo $this->_tpl_vars['currenthour']; ?>
';
		$("campaign_scheduleminute").value = '<?php echo $this->_tpl_vars['currentminute']; ?>
';

		var respondday = Math.floor(<?php echo $this->_tpl_vars['campaign']['responder_offset']; ?>
 / 24);
		var respondhour = <?php echo $this->_tpl_vars['campaign']['responder_offset']; ?>
 % 24;

		$("respondday").value = respondday;
		$("respondhour").value = respondhour;

<?php echo '
if (respondday == 0 && respondhour == 0) {
	$("campaign_responder_timeframe").value = "immed";
} else {
	$("campaign_responder_timeframe").value = "specific";
}
'; ?>


campaign_responder_switch($("campaign_responder_timeframe").value);

$("reminder_field").value = '<?php echo $this->_tpl_vars['campaign']['reminder_field']; ?>
';
$("reminder_format").value = '<?php echo $this->_tpl_vars['campaign']['reminder_format']; ?>
';
$("reminder_type").value = '<?php echo $this->_tpl_vars['campaign']['reminder_type']; ?>
';
$("reminder_offset").value = '<?php echo $this->_tpl_vars['campaign']['reminder_offset']; ?>
';
$("reminder_offset_type").value = '<?php echo $this->_tpl_vars['campaign']['reminder_offset_type']; ?>
';
$("reminder_offset_sign").value = '<?php echo $this->_tpl_vars['campaign']['reminder_offset_sign']; ?>
';

if ($("reminder_offset").value == 0 && $("reminder_offset_sign").value == "+")
$("reminder_offset_sign").value = "=";

$("campaign_recurring").value = '<?php echo $this->_tpl_vars['campaign']['recurring']; ?>
';

campaign_reminder_compile();
</script>
<?php endif; ?>