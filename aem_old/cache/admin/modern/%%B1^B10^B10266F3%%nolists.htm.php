<?php /* Smarty version 2.6.12, created on 2016-07-08 16:53:12
         compiled from nolists.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'nolists.htm', 4, false),)), $this); ?>
<h3 class="m-b"><?php echo $this->_tpl_vars['pageTitle']; ?>
</h3>

<div class="adesk_listing_empty">
  <?php echo ((is_array($_tmp="You currently do not have any lists.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <?php if ($this->_tpl_vars['canAddList']):  echo ((is_array($_tmp='Please')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <a href="desk.php?action=list#form-0"><?php echo ((is_array($_tmp='create a new list')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a> <?php echo ((is_array($_tmp="to continue.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp));  endif; ?>
</div>