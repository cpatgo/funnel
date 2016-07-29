<?php /* Smarty version 2.6.12, created on 2016-07-08 14:09:37
         compiled from inc.footer.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'inc.footer.htm', 6, false),)), $this); ?>

    
	
  <?php if ($this->_tpl_vars['admin']['brand_help']): ?>
		<div class="row padder"><div class="col-lg-12"> <div class=" text-center  padder" style="margin-left:60px;">
		  		 <p class=" text-center  clearfix text-muted"><b><?php echo ((is_array($_tmp='An email marketing tip for you')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</b> : <?php echo $this->_tpl_vars['tip']; ?>
 (<a href="https://docs.google.com/forms/d/1TrJfMHBWuAxZrBaabHvyB47-PXLFu4qy93jvBWT8DE8/viewform?usp=send_form" target="_blank"><?php echo ((is_array($_tmp='Software Feedbacks')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>)</p></div></div></div>
		  <?php endif; ?>