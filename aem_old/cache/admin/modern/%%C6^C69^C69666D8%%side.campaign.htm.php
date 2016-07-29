<?php /* Smarty version 2.6.12, created on 2016-07-08 14:14:32
         compiled from side.campaign.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'side.campaign.htm', 3, false),)), $this); ?>
 
<?php if ($this->_tpl_vars['canSendCampaign']): ?>
<p><div class="text-center clearfix"><a href="desk.php?action=campaign_new" class="btn btn-info"> <i class="fa fa-plus-circle fa-sm"></i> <?php echo ((is_array($_tmp='Create Campaign')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></div></p>
<?php endif; ?>
 
 

<?php if ($this->_tpl_vars['admin']['pg_message_add'] || $this->_tpl_vars['admin']['pg_message_edit'] || $this->_tpl_vars['admin']['pg_message_delete'] || $this->_tpl_vars['admin']['pg_template_add'] || $this->_tpl_vars['admin']['pg_template_edit'] || $this->_tpl_vars['admin']['pg_template_delete']): ?>
 <header class="panel-heading bg bg-inverse">
          <?php echo ((is_array($_tmp='Other options')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

            </header>
  <div class="list-group list-normal m-b-none">
 
<?php if ($this->_tpl_vars['admin']['pg_message_add'] || $this->_tpl_vars['admin']['pg_message_edit'] || $this->_tpl_vars['admin']['pg_message_delete']): ?>
		 <a href="desk.php?action=campaign#list-01D-0-0" class="list-group-item first"> <i class="fa fa-desktop fa-sm"></i> <?php echo ((is_array($_tmp='View Campaigns')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a> 
<?php endif; ?>
<?php if ($this->_tpl_vars['admin']['pg_template_add'] || $this->_tpl_vars['admin']['pg_template_edit'] || $this->_tpl_vars['admin']['pg_template_delete']): ?>
		 <a href="desk.php?action=template#list-01-0-0" class="list-group-item first">  <i class="fa fa-credit-card fa-sm"></i> <?php echo ((is_array($_tmp='Email Templates')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a> 
<?php endif; ?>
 </div>
<?php endif; ?>
 

<?php if ($this->_tpl_vars['admin']['pg_template_add'] || $this->_tpl_vars['admin']['pg_template_edit'] || $this->_tpl_vars['admin']['pg_template_delete']): ?>
 <header class="panel-heading bg bg-inverse">
          <?php echo ((is_array($_tmp='Advanced options')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

            </header>
 
 <div class="list-group list-normal m-b-none">
		 <a href="desk.php?action=personalization#list-01-0-0" class="list-group-item first"> <i class="fa fa-th-large fa-sm"></i> <?php echo ((is_array($_tmp='Message Variables')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a> 
 	 </div>
<?php endif; ?>
 
 