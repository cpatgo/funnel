<?php /* Smarty version 2.6.12, created on 2016-07-08 14:15:31
         compiled from campaign_new_list.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'campaign_new_list.htm', 2, false),array('modifier', 'truncate', 'campaign_new_list.htm', 41, false),array('modifier', 'escape', 'campaign_new_list.htm', 134, false),array('function', 'adesk_calendar', 'campaign_new_list.htm', 81, false),)), $this); ?>
<?php if ($this->_tpl_vars['hosted_down4'] != 'nobody'): ?>
<?php echo ((is_array($_tmp="Due to your account status, you are unable to send any campaigns.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

<a href="desk.php"><?php echo ((is_array($_tmp="Return to the Dashboard.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
<?php else: ?>

<div id="list_nobody" class="adesk_modal" align="center" <?php if (! isset ( $_GET['nobody'] )): ?>style="display:none;"<?php endif; ?>>
	<div class="adesk_modal_inner" align="left" style="width: 500px">
		<?php echo ((is_array($_tmp="None of the lists you've chosen will send to any subscribers.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		<?php if (isset ( $_GET['nobody'] )):  echo ((is_array($_tmp="It is probable that the list segment you've chosen to use will not match anybody with these lists.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp));  endif; ?>
		<?php echo ((is_array($_tmp="What do you want to do?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>


		<div style="margin-top: 15px">
			<input type="button" value="<?php echo ((is_array($_tmp='Add a subscriber')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="campaign_safe(); window.location.href = 'desk.php?action=subscriber#form-0'">
			<input type="button" value="<?php echo ((is_array($_tmp='Import subscribers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="campaign_safe(); window.location.href = 'desk.php?action=subscriber_import'">
			<input type="button" value="<?php echo ((is_array($_tmp='Choose something else')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="$('list_nobody').hide()">
		</div>
	</div>
</div>

<script type="text/javascript">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "campaign_new.shared.js", 'smarty_include_vars' => array('step' => 'list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "campaign_new_list.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</script>

<form id="campaignform" method="POST" action="desk.php" onsubmit="return false">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "campaign_new.header.htm", 'smarty_include_vars' => array('step' => 'list','highlight' => 1)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<input type="hidden" name="action" value="campaign_new_list">

	<div id="filter_group_condlen_div" style="display:none">
	</div>

	<div class="h2_wrap_static">
		<h5><?php echo ((is_array($_tmp="Select the list(s) to send to...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
		<div class="">

			<div class="campaign_input border_top_5" style="padding:5px;">
				<div style="padding:10px; padding-bottom:1px; background:#FFFFFF; font-size:14px; display:block;" class="border_5">
					<?php $_from = $this->_tpl_vars['lists']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['e']):
?>
					 
						<div style="float:left; margin-top:-2px; margin-right:5px;"><input type="checkbox" name="listid"  value="<?php echo $this->_tpl_vars['e']['id']; ?>
" class="listsList" onclick="campaign_filters(); campaign_different();"></div>
						<div style="margin-bottom:10px; display:block;"><?php echo ((is_array($_tmp=$this->_tpl_vars['e']['name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 100) : smarty_modifier_truncate($_tmp, 100)); ?>
 <span class="text-muted">(<?php echo $this->_tpl_vars['e']['count']; ?>
 <?php echo ((is_array($_tmp='subscribers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
)</span></div>
					 
					<?php endforeach; endif; unset($_from); ?>
				</div>
			</div>

			
			<div id="segmentlink" style="display:none; margin-left:23px; margin-right:10px; background:#f5f4ef; padding:6px; padding-bottom:8px; color:#9a9993; padding-left:10px; font-size:12px;"><span onclick="campaign_filter_toggle(); campaign_different();" style="text-decoration:underline; cursor: pointer"><?php echo ((is_array($_tmp="Segment my selected list(s)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span> <span style="color:#9a9993;">- <?php echo ((is_array($_tmp="Allows you to filter your campaign to subscribers who match certain conditions.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></div>

			<div id="campaignfilterbox" class="border_bottom_5" style="border: 3px solid #f5f4ef; border-top:0px; padding: 10px; margin-left:23px; margin-right:10px; display:none">


				<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  cellpadding="0" cellspacing="0" border="0" style="font-size:12px;">
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>
							<div id="segments" class="adesk_checkboxlist" style="max-height: 250px; width: 550px; display:none">
							</div>

							<div id="addsegmentdiv" style="display:none">
								<a href="#" onclick="campaign_filters_add(); campaign_different(); return false"><?php echo ((is_array($_tmp='Use a new segment')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
							</div>

							<div id="usefilterbox" style="display:none">
								<br /><?php echo ((is_array($_tmp="Select an existing / past segment:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
								<br>
								<div id="filterDiv" class="adesk_checkboxlist">
								</div>
								<input type="button" value="<?php echo ((is_array($_tmp='Create a new segment')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="campaign_filter_create();" style="margin:0px; border:1px solid #AAB7C3; background:#EEF0E9; border-top:0px;" />
							</div>
							<div id="filternew" style="display:none; ">
								<?php echo smarty_function_adesk_calendar(array('base' => ".."), $this);?>

								<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="5" class="filter_add_edit" style="border-bottom:0px;">
									<tr>
										<td><?php echo ((is_array($_tmp="Name This Segment:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
										<td><input type="text" name="filter_name" id="form_filter_name"></td>
									</tr>

									<tr>
										<td><?php echo ((is_array($_tmp="Match Type:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
										<td>
											<select name="filter_logic" id="form_filter_logic">
												<option value="and"><?php echo ((is_array($_tmp='Subscribers who match all of the following groups')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
												<option value="or" ><?php echo ((is_array($_tmp='Subscribers who match any of the following groups')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
											</select>
										</td>
									</tr>
								</table></div>

								<div class="filter_add_edit" id="filter_form">

									<br />
									<br />
									<div style="display:none">
										<div class="filter_group_title"><div style="float:right;"><img class="form_filter_group_delete" src="images/selection_delete-16-16.png" width="16" height="16" /></div><?php echo ((is_array($_tmp='Group')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <span class="filter_group_title_number"></span></div>
										<div class="filter_group" id="test">
											<select name="filter_group_logic[]" class="form_filter_group_logic">
												<option value="and"><?php echo ((is_array($_tmp='Subscribers who match all these conditions')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
												<option value="or" ><?php echo ((is_array($_tmp='Subscribers who match any of these conditions')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
											</select>
											<br />
											<br />
											<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="0">
												<tbody class="form_filter_condcontainer"></tbody>
											</table></div>
											<div style="margin-top:8px;"><a href="#" class="filter_group_addcond" style="display:block; background:url(images/add2-16-16.png); background-repeat:no-repeat; background-position:left; padding-left:20px; padding-top:2px; padding-bottom:2px;"><?php echo ((is_array($_tmp='Add another condition')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></div>
										</div>
										<table>
											<tbody id="form_filter_examplecond">
												<tr>
													<td>
														<select name="filter_group_cond_lhs[]" style="width:160px;" class="form_filter_cond_lhs">
															<optgroup label="<?php echo ((is_array($_tmp='Subscriber Details')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
">
																<option value="standard:email"><?php echo ((is_array($_tmp='Email Address')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
																<option value="standard:first_name"><?php echo ((is_array($_tmp='First Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
																<option value="standard:last_name"><?php echo ((is_array($_tmp='Last Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
																<option value="standard:*fullname"><?php echo ((is_array($_tmp='Full Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
																<option value="standard:*cdate"><?php echo ((is_array($_tmp='Date Subscribed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
																<option value="standard:*ctime"><?php echo ((is_array($_tmp='Time Subscribed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
																<option value="standard:*ip"><?php echo ((is_array($_tmp='IP Address')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
																<option value="standard:*status"><?php echo ((is_array($_tmp='Status')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
															</optgroup>
															<optgroup label="<?php echo ((is_array($_tmp='Custom Fields')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" class="form_filter_cond_lhs_fields">
																<?php $_from = $this->_tpl_vars['filter_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['c']):
?>
																<option value="custom:<?php echo $this->_tpl_vars['c']['id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['c']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</option>
																<?php endforeach; endif; unset($_from); ?>
															</optgroup>
															<optgroup label="<?php echo ((is_array($_tmp='Actions')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
">
																<option value="action:linkclicked"><?php echo ((is_array($_tmp='Has clicked on a link')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
																<option value="action:linknotclicked"><?php echo ((is_array($_tmp='Has not clicked on a link')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
																<option value="action:opened"><?php echo ((is_array($_tmp="Has opened/read")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
																<option value="action:notopened"><?php echo ((is_array($_tmp="Has not opened/read")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
																<option value="action:social"><?php echo ((is_array($_tmp='Has shared socially')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
																<option value="action:inlist"><?php echo ((is_array($_tmp='In list')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
																<option value="action:notinlist"><?php echo ((is_array($_tmp='Not in list')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
																<option value="action:forwarded"><?php echo ((is_array($_tmp='Has forwarded')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
																<option value="action:notforwarded"><?php echo ((is_array($_tmp='Has not forwarded')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
															</optgroup>
														</select>
													</td>
													<td>
														<select name="filter_group_cond_op[]" class="form_filter_cond_op" style="width: 200px">
														</select>
													</td>
													<td>
														<div class="form_filter_cond_rhs">
														</div>
													</td>
													<td width="5">&nbsp;</td>
													<td><img src="images/selection_delete-16-16.png" width="16" height="16" class="form_filter_cond_delete" /></td>
												</tr>
											</tbody>
										</table></div>
									</div>
									<div id="filter_groupcontainer"></div>

									<div class="filter_group_options">
										<a href="#" style="color:#999999;" onclick="filter_form_addgroup('and', true, 0); return false"><?php echo ((is_array($_tmp='Add another group of conditions')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></div>
									<br clear="left" />
									<br />

									<div id="filter_buttons" style="display:none">
										<input type="button" id="form_back" class="adesk_button_back" value="<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="campaign_filter_create();" />
										<input type="hidden" name="included" value="1" />
									</div>
									<input type="submit" style="display:none"/>

								</div>
							</div>
						</td>
					</tr>
				</table></div>

			</div>
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
		<input value="<?php echo ((is_array($_tmp='Next')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" type="button" onclick="campaign_save('next')" style="font-weight:bold; font-size:14px;" />
	</div>

	<div id="nosubscribersmodal" class="adesk_modal" align="center" style="display:none;">
		<div class="adesk_modal_inner" align="left">
			<h3 class="m-b"><?php echo ((is_array($_tmp="There are no subscribers in your selected list(s)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>

			<div class="adesk_help_inline"><?php echo ((is_array($_tmp="In order to send a campaign you will need to add a subscriber to your selected list.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>

			<br />

			<div>
				<input type="button" value='<?php echo ((is_array($_tmp='Add Subscriber')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="window.location.href='desk.php?action=subscriber#form-0';" style="font-weight:bold;" />
				<input type="button" value='<?php echo ((is_array($_tmp='Import Subscribers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="window.location.href='desk.php?action=subscriber_import';" />
				<input type="button" value='<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="adesk_dom_toggle_display('nosubscribersmodal', 'block');" />
			</div>
		</div>
	</div>

	<script type="text/javascript">
		campaign_save_auto_runagain();
		campaign_lists();
		campaign_filters();
		</script>
	</form>
	<?php endif; ?>