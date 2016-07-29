<?php /* Smarty version 2.6.12, created on 2016-07-18 12:02:57
         compiled from optinoptout.form.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'optinoptout.form.htm', 8, false),)), $this); ?>
<div id="form" class="adesk_hidden">
  <form method="POST" onsubmit="optinoptout_form_save(optinoptout_form_id); return false">
    <input type="hidden" name="id" id="form_id" />
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form.optinoptout.inc.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

    <br />
    <div>
      <input type="button" id="form_submit" class="adesk_button_submit" value="<?php echo ((is_array($_tmp='Submit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="optinoptout_form_save(optinoptout_form_id)" />
      <input type="button" id="form_back" class="adesk_button_back" value="<?php echo ((is_array($_tmp='Back')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="window.history.go(-1)" />
    </div>
    <input type="submit" style="display:none"/>
  </form>
</div>