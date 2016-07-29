<?php /* Smarty version 2.6.12, created on 2016-07-28 11:05:46
         compiled from exclusion.form.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'exclusion.form.htm', 5, false),array('modifier', 'truncate', 'exclusion.form.htm', 33, false),)), $this); ?>
<div id="form" class="adesk_hidden">
  <form method="POST" onsubmit="exclusion_form_save(exclusion_form_id); return false">
    <input type="hidden" name="id" id="form_id" />
	<div>
	  <h5><?php echo ((is_array($_tmp='Exclude address that')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
	  <div class="adesk_blockquote">
		<select id="matchtype" name="matchtype">
		  <option value="exact"><?php echo ((is_array($_tmp='exactly matches')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		  <option value="begin"><?php echo ((is_array($_tmp='begins with')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		  <option value="end"><?php echo ((is_array($_tmp='ends with')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		</select>
		<input type="text" name="address" id="form_address">
	  </div>
	</div>

	<h5><?php echo ((is_array($_tmp='This exclusion applies to')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>

	<div class="adesk_blockquote">
	  <div class="adesk_radiolist" id="targetdiv">
		<?php if (adesk_admin_ismain ( )): ?>
		<input type="radio" class="target_field" name="target" id="allradio" value="all" onchange="$('listbox').hide()"> <?php echo ((is_array($_tmp='All lists')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br>
		<?php endif; ?>
		<input type="radio" class="target_field" name="target" value="several" onchange="$('listbox').show()"> <?php echo ((is_array($_tmp='One or more specific lists')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  </div>

	  <div id="listbox" style="display:none; margin-top: 15px">
		<h5><?php echo ((is_array($_tmp='Lists')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
		<div class="adesk_blockquote">
		  <div class="adesk_checkboxlist">
			<?php $_from = $this->_tpl_vars['listsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['p']):
?>
			<label>
			  <input type="checkbox" value="<?php echo $this->_tpl_vars['p']['id']; ?>
" name="p" class="listid_field">
			  <?php echo ((is_array($_tmp=$this->_tpl_vars['p']['name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 50) : smarty_modifier_truncate($_tmp, 50)); ?>

			</label>
			<?php endforeach; endif; unset($_from); ?>
		  </div>
		  <div>
			<?php echo ((is_array($_tmp="Select:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			<a href="#" onclick="$$('.listid_field').each(function(e) <?php echo '{ e.checked = true; }'; ?>
); return false"><?php echo ((is_array($_tmp='All')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			&middot;
			<a href="#" onclick="adesk_dom_boxclear('listid_field'); return false"><?php echo ((is_array($_tmp='None')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
		  </div>
		</div>
	  </div>
	</div>

    <br />
    <div>
      <input type="button" id="form_submit" class="adesk_button_submit" value="<?php echo ((is_array($_tmp='Add')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="exclusion_form_save(exclusion_form_id)" />
      <input type="button" id="form_back" class="adesk_button_back" value="<?php echo ((is_array($_tmp='Back')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="window.history.go(-1)" />
    </div>
    <input type="submit" style="display:none"/>
  </form>
</div>