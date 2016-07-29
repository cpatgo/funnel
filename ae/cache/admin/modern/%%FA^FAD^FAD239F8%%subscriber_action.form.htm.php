<?php /* Smarty version 2.6.12, created on 2016-07-18 12:03:31
         compiled from subscriber_action.form.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'subscriber_action.form.htm', 1, false),array('modifier', 'alang', 'subscriber_action.form.htm', 11, false),array('modifier', 'escape', 'subscriber_action.form.htm', 75, false),array('modifier', 'truncate', 'subscriber_action.form.htm', 75, false),)), $this); ?>
<?php if (! ((is_array($_tmp=@$this->_tpl_vars['fromcampaign'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))): ?>
<script type="text/javascript">
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "campaign_new.actions.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</script>
<?php endif; ?>

<div id="form" class="<?php echo ((is_array($_tmp=@$this->_tpl_vars['displayclass'])) ? $this->_run_mod_handler('default', true, $_tmp, 'adesk_hidden') : smarty_modifier_default($_tmp, 'adesk_hidden')); ?>
">
	<input type="hidden" name="id" id="form_id" value="">

	<?php if (! ((is_array($_tmp=@$this->_tpl_vars['fromcampaign'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))): ?>
	<?php echo ((is_array($_tmp="Name:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br>
	<input type="text" name="name" id="form_name"><br><br>
	<?php endif; ?>

	<?php if (((is_array($_tmp=@$this->_tpl_vars['fromcampaign'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))): ?>
	<input type="hidden" name="type" id="form_type_hidden">
	<input type="hidden" name="linkid_hidden" id="form_linkid_hidden">
	<?php else: ?>
	<?php echo ((is_array($_tmp="Select an action type:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	<br />
	<br />
	<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td width="40" align="right"><input name="type" type="radio" value="read" onclick="subscriber_action_form_actionclick(this.value)"/></td>
		<td width="10">&nbsp;</td>
		<td><?php echo ((is_array($_tmp="When subscriber reads/opens a campaign")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
	  </tr>
	  <tr>
		<td align="right"><input name="type" type="radio" value="link" onclick="subscriber_action_form_actionclick(this.value)" /></td>
		<td>&nbsp;</td>
		<td><?php echo ((is_array($_tmp='When subscriber clicks on a link')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
	  </tr>
	  <tr>
		<td align="right"><input name="type" type="radio" value="social" onclick="subscriber_action_form_actionclick(this.value)" /></td>
		<td>&nbsp;</td>
		<td><?php echo ((is_array($_tmp='When subscriber socially shares a campaign')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
	  </tr>
	  <tr>
		<td align="right"><input name="type" type="radio" value="forward" onclick="subscriber_action_form_actionclick(this.value)" /></td>
		<td>&nbsp;</td>
		<td><?php echo ((is_array($_tmp='When subscriber forwards a campaign')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
	  </tr>	
	  <tr>
		<td align="right"><input name="type" type="radio" value="subscribe" onclick="subscriber_action_form_actionclick(this.value)" /></td>
		<td>&nbsp;</td>
		<td><?php echo ((is_array($_tmp='When subscriber subscribes to a list')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
	  </tr>
	  <tr>
		<td align="right"><input name="type" type="radio" value="unsubscribe" onclick="subscriber_action_form_actionclick(this.value)" /></td>
		<td>&nbsp;</td>
		<td><?php echo ((is_array($_tmp='When subscriber unsubscribes from a list')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
	  </tr>
	</table></div>
	<br/>
	<?php endif; ?>
	<div id="div_dropdowns">
	  <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="0">
		<tr>
		  <td width="25">&nbsp;</td>
		  <td><span id="span_listlabel"><?php echo ((is_array($_tmp='List')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></td>
		  <td width="20">&nbsp;</td>
		  <td><span id="span_campaignlabel"><?php echo ((is_array($_tmp='Campaign')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></td>
		  <td width="20">&nbsp;</td>
		  <td>
			<span id="span_linklabel" style="display:none"><?php echo ((is_array($_tmp='Link')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
			<span id="span_sociallabel" style="display:none"><?php echo ((is_array($_tmp='Social Media')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
		  </td>
		</tr>
		<tr>
		  <td>&nbsp;</td>
		  <td>
			<span id="span_listselect">
			  <select name="listid" id="form_listid" onchange="subscriber_action_form_loadcampaigns(this.value)">
				<?php $_from = $this->_tpl_vars['listsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['l']):
?>
				<option value="<?php echo $this->_tpl_vars['l']['id']; ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['l']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('truncate', true, $_tmp, 50) : smarty_modifier_truncate($_tmp, 50)); ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
			  </select>
			</span>
		  </td>
		  <td>&nbsp;</td>
		  <td>
			<span id="span_campaignselect">
			  <select name="campaignid" id="form_campaignid" onchange="subscriber_action_form_loadlinks(this.value)">
				<option value="0"><?php echo ((is_array($_tmp='Any')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
			  </select>
			</span>
		  </td>
		  <td>&nbsp;</td>
		  <td>
			<span id="span_linkselect" style="display:none">
			  <select name="linkid" id="form_linkid">
				<option value="0"><?php echo ((is_array($_tmp='Any')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
			  </select>
			</span>
			<span id="span_socialselect" style="display:none">
			  <select name="social" id="form_social">
				<option value="facebook"><?php echo ((is_array($_tmp='Facebook')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
				<option value="twitter"><?php echo ((is_array($_tmp='Twitter')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
				<option value="digg"><?php echo ((is_array($_tmp='Digg')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
				<option value="delicious"><?php echo ((is_array($_tmp="del.icio.us")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
				<option value="greader"><?php echo ((is_array($_tmp='Google Reader')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
				<option value="reddit"><?php echo ((is_array($_tmp='Reddit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
				<option value="stumbleupon"><?php echo ((is_array($_tmp='StumbleUpon')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
			  </select>
			</span>
		  </td>
		</tr>
	  </table></div>
	</div>
	<p><?php echo ((is_array($_tmp="What should happen when this action takes place?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 </p>
	<div id="actionClonerDiv">
	  <div class="action_box">
		<select name="linkaction[]" onchange="campaign_action_changed(this.parentNode, true);" style="width:150px;">
		  <option value="subscribe" selected="selected"><?php echo ((is_array($_tmp='Subscribe to list')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		  <option value="unsubscribe"><?php echo ((is_array($_tmp='Unsubscribe from list')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		  <option value="send"><?php echo ((is_array($_tmp='Send campaign')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		  <option value="update"><?php echo ((is_array($_tmp='Update subscriber info')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		  <!--<option value="_other">other options???</option>-->
		</select>
		<select name="linkvalue1[]" size="1" onchange="campaign_action_changed(this.parentNode);"  style="width:200px;">
		  <?php $_from = $this->_tpl_vars['listsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['p']):
?>
		  <option value="<?php echo $this->_tpl_vars['p']['id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['p']['name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 50) : smarty_modifier_truncate($_tmp, 50)); ?>
</option>
		  <?php endforeach; endif; unset($_from); ?>
		</select>
		<select name="linkvalue2[]" size="1" onchange="campaign_action_changed(this.parentNode);"  style="width:200px;">
		  <optgroup label="<?php echo ((is_array($_tmp='Mailings')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
">
			<?php $_from = $this->_tpl_vars['campaigns']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['c']):
?>
			<?php if ($this->_tpl_vars['c']['type'] != 'responder' && $this->_tpl_vars['c']['type'] != 'reminder'): ?>
			<option value="<?php echo $this->_tpl_vars['c']['id']; ?>
"><?php echo $this->_tpl_vars['c']['name']; ?>
</option>
			<?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>
		  </optgroup>
		  <optgroup label="<?php echo ((is_array($_tmp='AutoResponders')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
">
			<?php $_from = $this->_tpl_vars['campaigns']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['c']):
?>
			<?php if ($this->_tpl_vars['c']['type'] == 'responder'): ?>
			<option value="<?php echo $this->_tpl_vars['c']['id']; ?>
"><?php echo $this->_tpl_vars['c']['name']; ?>
</option>
			<?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>
		  </optgroup>
		  <optgroup label="<?php echo ((is_array($_tmp='Subscriber Date Based')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
">
			<?php $_from = $this->_tpl_vars['campaigns']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['c']):
?>
			<?php if ($this->_tpl_vars['c']['type'] == 'reminder'): ?>
			<option value="<?php echo $this->_tpl_vars['c']['id']; ?>
"><?php echo $this->_tpl_vars['c']['name']; ?>
</option>
			<?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>
		  </optgroup>
		</select>
		<select name="linkvalue3[]" size="1" onchange="campaign_action_changed(this.parentNode);" style="width:200px;">
		  <option value="first_name"><?php echo ((is_array($_tmp='First Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		  <option value="last_name"><?php echo ((is_array($_tmp='Last Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		  <?php $_from = $this->_tpl_vars['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['f']):
?>
		  <option value="<?php echo $this->_tpl_vars['f']['id']; ?>
"><?php echo $this->_tpl_vars['f']['title']; ?>
</option>
		  <?php endforeach; endif; unset($_from); ?>
		</select>
		<input name="linkvalue4[]" type="text" value="" size="20" style="width:330px;" />
		<a href="#" onclick="if ($A($('actionClonerDiv').getElementsByTagName('div')).length > 1) remove_element(this.parentNode); return false"><img src="images/selection_delete-16-16.png" width="16" height="16" border="0" align="absmiddle" /></a>
	  </div>
	</div>

	<div style=" margin-top:10px; margin-bottom:10px;">
	  <a href="#" onclick="campaign_link_action_new();return false;" style="display:block; background:url(images/add2-16-16.png); background-repeat:no-repeat; background-position:left; padding-left:20px; padding-top:2px; padding-bottom:2px;"><?php echo ((is_array($_tmp='Add additional action')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
	</div>

	<input type="button" id="form_submit" class="adesk_button_submit" value="<?php echo ((is_array($_tmp='Submit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="subscriber_action_form_save(subscriber_action_form_id)" />
	<input type="button" id="form_back" class="adesk_button_back" value="<?php echo ((is_array($_tmp='Back')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="subscriber_action_form_back()" />

	<div id="subscriber_action_deleteall" style="margin-top: 10px; margin-bottom: 10px; display:none">
	  <?php if (((is_array($_tmp=@$this->_tpl_vars['fromcampaign'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0))): ?>
	  <a href="#" onclick="campaign_actions_deleteall(subscriber_action_form_id); return false" style="display:block; background:url(images/selection_delete-16-16.png); background-repeat:no-repeat; background-position:left; padding-left:20px; padding-top:2px; padding-bottom:2px;">
		<?php echo ((is_array($_tmp='Delete all actions associated with this link click or read')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  </a>
	  <?php endif; ?>
	</div>
</div>

<script type="text/javascript">
  <?php echo '
  campaign_action_init();
  window.setTimeout(function() {
	  subscriber_action_form_loadcampaigns($("form_listid").value);
	  }, 100);
'; ?>

</script>