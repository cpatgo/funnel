<?php /* Smarty version 2.6.12, created on 2016-07-08 16:50:11
         compiled from noaccess.tpl.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'noaccess.tpl.htm', 1, false),)), $this); ?>
<div class="warning"><?php echo ((is_array($_tmp="You do not have needed privileges to access this page.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>

<p>
<input type="button" class="adesk_button_back" value="<?php echo ((is_array($_tmp='Back')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="window.history.go(-1);">
<input type="button" class="adesk_button_home" value="<?php echo ((is_array($_tmp='Home')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="top.location = 'desk.php';">
</p>