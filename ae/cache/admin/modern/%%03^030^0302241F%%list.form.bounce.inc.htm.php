<?php /* Smarty version 2.6.12, created on 2016-07-08 16:53:15
         compiled from list.form.bounce.inc.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'list.form.bounce.inc.htm', 4, false),array('modifier', 'help', 'list.form.bounce.inc.htm', 16, false),)), $this); ?>
<div id="bounce" class="adesk_block">
	<div id="bouncechoose" class="adesk_block">
		<div class="h2_wrap_static">
			<h5><?php echo ((is_array($_tmp="Choose a Bounce Email Address:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
			<div class="h2_content">
				<select id="bounceidField" name="bounceid[]" size="5" multiple>
				<?php $_from = $this->_tpl_vars['bouncesList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['b']):
?>
				  <option value="<?php echo $this->_tpl_vars['b']['id']; ?>
"><?php if ($this->_tpl_vars['b']['type'] == 'none'):  echo ((is_array($_tmp='No bounce Management')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp));  else:  echo $this->_tpl_vars['b']['email'];  endif; ?></option>
				<?php endforeach; endif; unset($_from); ?>
				</select>
				<div>
				  <?php echo ((is_array($_tmp="Select:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				  <a href="#" onclick="return adesk_form_select_multiple_all($('bounceidField'));"><?php echo ((is_array($_tmp='All')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				  &middot;
				  <a href="#" onclick="return adesk_form_select_multiple_none($('bounceidField'));"><?php echo ((is_array($_tmp='None')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
				  <?php echo ((is_array($_tmp="Notice: Hold CTRL to select multiple lists.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

				</div>
			</div>
		</div>
	</div>
</div>