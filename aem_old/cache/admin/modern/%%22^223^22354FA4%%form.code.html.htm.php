<?php /* Smarty version 2.6.12, created on 2016-07-13 16:06:17
         compiled from form.code.html.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'form.code.html.htm', 5, false),array('modifier', 'count', 'form.code.html.htm', 26, false),array('modifier', 'adesk_field_title', 'form.code.html.htm', 34, false),array('modifier', 'i18n', 'form.code.html.htm', 95, false),array('function', 'adesk_field_html', 'form.code.html.htm', 31, false),)), $this); ?>
<form method="post" action="<?php echo $this->_tpl_vars['site']['p_link']; ?>
/surround.php">

<table>
	<tr>
		<td><?php echo ((is_array($_tmp="Email:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td><input name="email" value="" type="text" /></td>
	</tr>


<?php if ($this->_tpl_vars['form']['ask4fname'] || $this->_tpl_vars['form']['require_name']): ?>
	<tr>
		<td><?php echo ((is_array($_tmp="First Name:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td><input name="first_name" value="" type="text" /></td>
	</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['form']['ask4lname'] || $this->_tpl_vars['form']['require_name']): ?>
	<tr>
		<td><?php echo ((is_array($_tmp="Last Name:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td><input name="last_name" value="" type="text" /></td>
	</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['form']['type'] != 'unsubscribe'): ?>

	<?php if (count($this->_tpl_vars['form']['fieldsarray']) > 0): ?>

		<?php $_from = $this->_tpl_vars['form']['fieldsarray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field']):
?>

			<?php if ($this->_tpl_vars['field']['type'] == 6): ?>
	<?php echo smarty_function_adesk_field_html(array('field' => $this->_tpl_vars['field']), $this);?>

			<?php else: ?>
	<tr>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['field']['title'])) ? $this->_run_mod_handler('adesk_field_title', true, $_tmp, $this->_tpl_vars['field']['type']) : smarty_modifier_adesk_field_title($_tmp, $this->_tpl_vars['field']['type'])); ?>
</td>
		<td><?php echo smarty_function_adesk_field_html(array('field' => $this->_tpl_vars['field'],'nobubbles' => 1), $this);?>
</td>
	</tr>
			<?php endif; ?>

		<?php endforeach; endif; unset($_from); ?>

	<?php else: ?>

		<input type="hidden" name="field[]" />

	<?php endif; ?>

<?php endif; ?>

<?php if ($this->_tpl_vars['site']['gd'] && $this->_tpl_vars['form']['captcha']): ?>
	<tr>
		<td valign="top"><?php echo ((is_array($_tmp='Verify')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		<td>
			<img border="1" align="middle" src="<?php echo $this->_tpl_vars['site']['p_link']; ?>
/awebdesk/scripts/imgrand.php" /><br />
			<input type="text" name="imgverify" id="imgverify" />
			<div style="font-size:10px; color:#999999;"><?php echo ((is_array($_tmp='Enter the text as it appears on the image')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
		</td>
	</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['form']['allowselection']): ?>
	<tr>
		<td>&nbsp;</td>
		<td>
			<?php echo ((is_array($_tmp="Select Lists:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
<?php endif; ?>

	<?php $_from = $this->_tpl_vars['form']['lists']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['l']):
?>
		<?php if ($this->_tpl_vars['form']['allowselection']): ?>
			<label ><input type="checkbox" name="nlbox[]" value="<?php echo $this->_tpl_vars['l']['id']; ?>
" checked="checked" /><?php echo $this->_tpl_vars['l']['name']; ?>
</label><br />
		<?php else: ?>
			<input type="hidden" name="nlbox[]" value="<?php echo $this->_tpl_vars['l']['id']; ?>
" />
		<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>

	<?php if ($this->_tpl_vars['form']['allowselection']): ?>
		</td>
	</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['form']['type'] == 'both'): ?>
	<tr>
		<td>&nbsp;</td>
		<td>
			<label><input type="radio" name="funcml" value="add" checked="checked" /><?php echo ((is_array($_tmp='Subscribe')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label><br />
			<label><input type="radio" name="funcml" value="unsub2" /><?php echo ((is_array($_tmp='Unsubscribe')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
		</td>
	</tr>
<?php else: ?>
<input type="hidden" name="funcml" value="<?php if ($this->_tpl_vars['form']['type'] == 'subscribe'): ?>add<?php else: ?>unsub2<?php endif; ?>" />
<?php endif; ?>
	<tr>
		<td>&nbsp;</td>
		<td>
		<input type="hidden" name="p" value="<?php echo $this->_tpl_vars['form']['id']; ?>
" />
		<input type="hidden" name="_charset" value="<?php echo ((is_array($_tmp='utf-8')) ? $this->_run_mod_handler('i18n', true, $_tmp) : smarty_modifier_i18n($_tmp)); ?>
" />
		<input type="submit" value="<?php echo ((is_array($_tmp='Submit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" />

		<?php if ($this->_tpl_vars['site']['brand_links']): ?><div style="font-size:10px; margin-top:10px; color:#999999;"><a href="http://www.awebdesk.com/" title="email marketing" style="color:#666666;"><?php echo $this->_tpl_vars['site']['acpow']; ?>
</a> by AwebDesk</div><?php endif; ?>

		</td>
	</tr>
</table></div>

</form>