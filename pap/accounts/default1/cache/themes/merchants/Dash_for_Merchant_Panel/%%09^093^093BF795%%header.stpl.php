<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:31
         compiled from header.stpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'header.stpl', 3, false),)), $this); ?>
<!-- header -->
<div class="LAHeader">
	<a class="LALogo" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['programName'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" href="<?php echo $this->_tpl_vars['baseUrl']; ?>
/"><img src="<?php echo ((is_array($_tmp=$this->_tpl_vars['programLogo'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="LALogoImage"/></a>
	<div style="position: absolute; right: 0px;"><?php echo $this->_tpl_vars['quChatButton']; ?>
</div>
	<div class="LACopyright"><?php echo $this->_tpl_vars['papCopyrightText']; ?>
<br /><?php echo ((is_array($_tmp=$this->_tpl_vars['papVersionText'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</div>
</div>


