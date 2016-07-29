<?php /* Smarty version 2.6.12, created on 2016-07-08 15:27:03
         compiled from inc.headernav.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'adesk_link', 'inc.headernav.htm', 4, false),array('modifier', 'plang', 'inc.headernav.htm', 4, false),)), $this); ?>
				 
 <ul class="nav" data-spy="affix" data-offset-top="50">
 <?php if ($this->_tpl_vars['site']['general_public']): ?>
      <li><a href="<?php echo smarty_function_adesk_link(array('action' => 'subscribe'), $this);?>
"><i class="fa fa-plus-square fa-lg"></i> <span><?php echo ((is_array($_tmp='Subscribe')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
</span></a> </li>
      <li><a href="<?php echo smarty_function_adesk_link(array('action' => 'archive'), $this);?>
"><i class="fa fa-archive fa-lg"></i><span><span><?php echo ((is_array($_tmp='Archive')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
</span></a></li>
      <li><a href="<?php echo smarty_function_adesk_link(array('action' => 'account'), $this);?>
"><i class="fa fa-user fa-lg"></i><span><?php echo ((is_array($_tmp='Your Account')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
</span></a></li>
      <li><a href="<?php echo smarty_function_adesk_link(array('action' => 'unsubscribe'), $this);?>
"><i class="fa fa-minus-square fa-lg"></i><span><?php echo ((is_array($_tmp='Unsubscribe')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
</span></a></li>
      
<?php endif; ?>      
</ul>      