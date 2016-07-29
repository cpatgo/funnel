<?php /* Smarty version 2.6.12, created on 2016-07-08 14:11:51
         compiled from settings.local.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'settings.local.htm', 2, false),array('modifier', 'capitalize', 'settings.local.htm', 18, false),array('modifier', 'adesk_isselected', 'settings.local.htm', 28, false),array('modifier', 'escape', 'settings.local.htm', 41, false),array('function', 'html_options', 'settings.local.htm', 13, false),)), $this); ?>
<div id="settings_local" style="margin-top: 10px">
<h5><?php echo ((is_array($_tmp='Localization')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
<div  class="adesk_blockquote">
  <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="5" width="100%">
	<tr>	
		<td colspan="2"> <div class="adesk_help_inline"><?php echo ((is_array($_tmp="This is the global default language setting.  In order to change your language setting go to the")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <a href="desk.php?action=account" class="button"><?php echo ((is_array($_tmp="your account page.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></div></td>
	
	</tr>
	<tr>
	  <td width="110" valign="top"><?php echo ((is_array($_tmp='Default Language')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
	  <td valign="top">
			<select name="lang" id="lang" style="width:170px;" <?php if (adesk_admin_ismaingroup ( )): ?>onchange="settings_local_lang_change('<?php echo $this->_tpl_vars['site']['lang']; ?>
', this.value);"<?php endif; ?>>
			  <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['languages'],'selected' => $this->_tpl_vars['site']['lang']), $this);?>

			</select>
			<div id="local_lang_old_div" style="display: none; margin: 10px 0;">
				<input type="checkbox" name="local_lang_old_check" id="local_lang_old_check" value="<?php echo $this->_tpl_vars['site']['lang']; ?>
" checked="checked" />
				<label for="local_lang_old_check"><?php echo ((is_array($_tmp='Update all users to use this new Language that currently have')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
				<span id="local_lang_old" style="font-weight: bold;"><?php echo ((is_array($_tmp=$this->_tpl_vars['site']['lang'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>
</span>
			</div>
		</td>
	</tr>
    	 
	<tr>
	  <td valign="top"><?php echo ((is_array($_tmp='Default Time Zone')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
	  <td>
			<select name="local_zoneid" <?php if (adesk_admin_ismaingroup ( )): ?>onchange="settings_local_timezone_change('<?php echo $this->_tpl_vars['site']['local_zoneid']; ?>
', this.value);"<?php endif; ?>>
			  <?php $_from = $this->_tpl_vars['zones']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['z']):
?>
			  	<option value="<?php echo $this->_tpl_vars['z']['zoneid']; ?>
" <?php echo ((is_array($_tmp=$this->_tpl_vars['z']['zoneid'])) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, $this->_tpl_vars['site']['local_zoneid']) : smarty_modifier_adesk_isselected($_tmp, $this->_tpl_vars['site']['local_zoneid'])); ?>
><?php echo $this->_tpl_vars['z']['zoneid']; ?>
 (GMT <?php echo $this->_tpl_vars['z']['offset_format']; ?>
)</option>
			  <?php endforeach; endif; unset($_from); ?>
			</select>
			<div id="local_zoneid_old_div" style="display: none; margin: 10px 0;">
				<input type="checkbox" name="local_zoneid_old_check" id="local_zoneid_old_check" value="<?php echo $this->_tpl_vars['site']['local_zoneid']; ?>
" checked="checked" />
				<label for="local_zoneid_old_check"><?php echo ((is_array($_tmp='Update all users to use this new Timezone that currently have')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
				<span id="local_zoneid_old" style="font-weight: bold;"><?php echo $this->_tpl_vars['site']['local_zoneid']; ?>
</span>
			</div>
		</td>
	</tr>
	<tr>
	  <td valign="top"><?php echo ((is_array($_tmp='Date Format')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
	  <td>
		<input type="text" name="dateformat" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['site']['dateformat'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		(<a href="#" onclick="adesk_dom_display_block('kbsettings_help_datetime'); return false"><?php echo ((is_array($_tmp="How do these formats work?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>)
		<div class="kbsettings_help_datetime" style="display:none; position:relative; border:1px solid #FEE996; background:#FFFFFF; width:275px; padding:10px;" id='kbsettings_help_datetime'>
		  <?php echo ((is_array($_tmp="Below are some examples of common date formats:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
		<br />

		<div style="float:right;">%m/%d/%Y</div>
		<div><?php echo ((is_array($_tmp="Month/Date/Year")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>

		<div style="float:right;">%Y-%m-%d</div>
		<div><?php echo ((is_array($_tmp="Year-Month-Date")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>

		<div style="float:right;">%A, %m %d, %Y</div>
		<div><?php echo ((is_array($_tmp="Full Day, Month Date, Year")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
		<br />


		<?php echo ((is_array($_tmp="And below here are some common time formats:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
		<br />



		<div style="float:right;">%H:%M:%S</div>
		<div><?php echo ((is_array($_tmp="24Hours:Minutes:Seconds (military time)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
		<div style="float:right;">%I:%M %p</div>
		<div><?php echo ((is_array($_tmp="12Hours:Minutes AM/PM")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>

		<br />

		  <a href="#" onclick="adesk_dom_display_none('kbsettings_help_datetime'); return false"><?php echo ((is_array($_tmp='Close')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>		</div>	  </td>
	</tr>
	<tr>
	  <td><?php echo ((is_array($_tmp='Time Format')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
	  <td>
		<input type="text" name="timeformat" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['site']['timeformat'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />	  </td>
	</tr>
	<tr>
	  <td><?php echo ((is_array($_tmp="Date + Time Format")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
	  <td><input type="text" name="datetimeformat" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['site']['datetimeformat'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
	</tr>
  </table></div>
 </div>
</div>