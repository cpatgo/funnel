<?php /* Smarty version 2.6.12, created on 2016-07-08 16:53:15
         compiled from list.form.optinoptout.inc.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'list.form.optinoptout.inc.htm', 6, false),)), $this); ?>
<div id="optinout" class="adesk_block" <?php if (count ( $this->_tpl_vars['optsetsList'] ) < 2): ?>style="display:none"<?php endif; ?>>
      <div id="optinoutchoose" class="adesk_block">
		<div class="h2_wrap_static">
		<br />

          <h5><?php echo ((is_array($_tmp="Choose an Email Confirmation Set for this list:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
		  <div class="adesk_blockquote">
            <select id="optinoutidField" name="optid" size="1" style="width:310px;">
<?php $_from = $this->_tpl_vars['optsetsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['o']):
?>
              <option value="<?php echo $this->_tpl_vars['o']['id']; ?>
"><?php echo $this->_tpl_vars['o']['name']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
            </select>
            <br />
<?php if ($this->_tpl_vars['admin']['pg_list_opt']): ?>
<?php if (false): ?>
        	<a href="desk.php?action=optinoptout" onclick="optinout_get($('optinoutidField').value);return false;"><?php echo ((is_array($_tmp='Manage')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
        	<a href="desk.php?action=optinoptout#form-0" onclick="optinout_get(0);return false;"><?php echo ((is_array($_tmp='Add New')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
<?php endif; ?>

                        <div id="optinoutnew" class="adesk_hidden">
              <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form.optinoptout.inc.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
              <input type="hidden" value="0" id="hiddenOptinId" />
              <br />
              <div>
                <input type="button" id="optinout_form_save" class="adesk_button_save" value="<?php echo ((is_array($_tmp='Save')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="optinout_set();" />
                <input type="button" id="optinout_form_cancel" class="adesk_button_back" value="<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="$('optinoutnew').className = 'adesk_hidden';" />
              </div>
            </div>
<?php endif; ?>
          </div>
        </div>
      </div>
    </div>