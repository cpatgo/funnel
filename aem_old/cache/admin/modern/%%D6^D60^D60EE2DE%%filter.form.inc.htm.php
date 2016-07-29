<?php /* Smarty version 2.6.12, created on 2016-07-27 12:32:21
         compiled from filter.form.inc.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'adesk_calendar', 'filter.form.inc.htm', 1, false),array('modifier', 'alang', 'filter.form.inc.htm', 5, false),array('modifier', 'truncate', 'filter.form.inc.htm', 13, false),array('modifier', 'escape', 'filter.form.inc.htm', 82, false),)), $this); ?>
<?php echo smarty_function_adesk_calendar(array('base' => ".."), $this);?>

<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="5" <?php if ($this->_tpl_vars['included']): ?>class="filter_add_edit" style="border-bottom:0px;"<?php endif; ?>>
  <?php if (! $this->_tpl_vars['included']): ?>
  <tr valign="top">
	<td><?php echo ((is_array($_tmp="Used in Lists:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
	<td>

		<div id="parentsList_div" class="adesk_checkboxlist">
			<?php $_from = $this->_tpl_vars['listsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['p']):
?>
				<div>
					<label>
						<input type="checkbox" id="p_<?php echo $this->_tpl_vars['p']['id']; ?>
" class="parentsList" name="listid[]" value="<?php echo $this->_tpl_vars['p']['id']; ?>
" <?php if (count ( $this->_tpl_vars['listsList'] ) == 1): ?>checked="checked"<?php endif; ?> />
						<?php echo ((is_array($_tmp=$this->_tpl_vars['p']['name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 50) : smarty_modifier_truncate($_tmp, 50)); ?>

					</label>
				</div>
			<?php endforeach; endif; unset($_from); ?>
		</div>
	  <div>
			<?php echo ((is_array($_tmp="Select:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			<a href="#" onclick="parents_box_select(1, 0); return false;"><?php echo ((is_array($_tmp='All')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			&middot;
			<a href="#" onclick="parents_box_select(0, 0); return false;"><?php echo ((is_array($_tmp='None')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
	  </div>

	</td>
  </tr>
  <tr>
  	<td colspan="2">&nbsp;</td>
  </tr>
  <?php endif; ?>
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
">
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

  <?php if (! $this->_tpl_vars['included']): ?>
  <div>
	<input type="button" id="form_submit" class="adesk_button_submit" value="<?php echo ((is_array($_tmp='Submit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="filter_form_save(filter_form_id, false)" />
	<input type="button" id="form_back" class="adesk_button_back" value="<?php echo ((is_array($_tmp='Back')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="window.history.go(-1)" />
  </div>
  <?php else: ?>
  <div>
	<input type="button" id="form_submit" class="adesk_button_save" value="<?php echo ((is_array($_tmp='Save')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="filter_form_save(filter_form_id, true);" />
	<input type="button" id="form_back" class="adesk_button_back" value="<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="campaign_filter_create();" />
	<input type="hidden" name="included" value="1" />
  </div>
  <?php endif; ?>
  <input type="submit" style="display:none"/>

</div>