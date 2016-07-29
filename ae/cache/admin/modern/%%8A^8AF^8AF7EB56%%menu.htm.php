<?php /* Smarty version 2.6.12, created on 2016-07-08 14:09:37
         compiled from menu.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'menu.htm', 6, false),)), $this); ?>
 <?php if (! $this->_tpl_vars['is_campaign_new']): ?>

 
    

 <li><a href="desk.php"><i class="fa fa-dashboard fa-lg"></i><span><?php echo ((is_array($_tmp='Dashboard')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></span></span></li>


	<?php if ($this->_tpl_vars['admin']['pg_subscriber_add'] || $this->_tpl_vars['admin']['pg_subscriber_edit'] || $this->_tpl_vars['admin']['pg_subscriber_delete']): ?>
 <li><a href="desk.php?action=subscriber#list-01-0-0"><i class="fa fa-users fa-lg"></i><span> <?php echo ((is_array($_tmp='Subscribers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></a></li>	<?php endif; ?>

	<?php if ($this->_tpl_vars['admin']['pg_message_add'] || $this->_tpl_vars['admin']['pg_message_edit'] || $this->_tpl_vars['admin']['pg_message_delete'] || $this->_tpl_vars['admin']['pg_message_send']): ?>
	 <li><a href="desk.php?action=campaign#list-01D-0-0"><i class="fa fa-envelope fa-lg"></i><span><?php echo ((is_array($_tmp='Campaigns')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></a></li>
	<?php endif; ?>

	 <?php if ($this->_tpl_vars['admin']['pg_list_add'] || $this->_tpl_vars['admin']['pg_list_edit'] || $this->_tpl_vars['admin']['pg_list_delete'] || $this->_tpl_vars['admin']['pg_subscriber_filters'] || $this->_tpl_vars['admin']['pg_subscriber_fields'] || $this->_tpl_vars['admin']['pg_subscriber_actions'] || $this->_tpl_vars['admin']['pg_list_headers'] || $this->_tpl_vars['admin']['pg_list_emailaccount'] || $this->_tpl_vars['admin']['pg_list_opt'] || $this->_tpl_vars['admin']['pg_list_bounce']): ?>
 <li><a href="desk.php?action=list#list-01-0-0"><i class="fa fa-bars fa-lg"></i><span><?php echo ((is_array($_tmp='Lists')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></a></li>
	<?php endif; ?>
    
<?php if ($this->_tpl_vars['admin']['pg_reports_campaign'] || $this->_tpl_vars['admin']['pg_reports_list'] || $this->_tpl_vars['admin']['pg_reports_user'] || $this->_tpl_vars['admin']['pg_reports_trend']): ?>   
<li class="dropdown-submenu">
        <a href="#"><i class="fa   fa-bar-chart-o fa-lg"></i><span><?php echo ((is_array($_tmp='Reports')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></a>
        <ul class="dropdown-menu">
	<?php if ($this->_tpl_vars['admin']['pg_reports_campaign']): ?>
 <li><a href="desk.php?action=campaign&reports=1#list-01D-0-0"><i class="fa   fa-bar-chart-o fa-sm"></i>&nbsp;<span><?php echo ((is_array($_tmp='Campaign Reports')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></a></li>
 <?php endif; ?>
	<?php if ($this->_tpl_vars['admin']['pg_reports_list']): ?>
 <li><a href="desk.php?action=report_list#general-01-0-0"><i class="fa  fa-indent fa-sm"></i>&nbsp;<span><?php echo ((is_array($_tmp='List Reports')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></a></li>
	<?php endif; ?>
    <?php if ($this->_tpl_vars['admin']['pg_reports_user']): ?>
	 <li><a href="desk.php?action=report_user#general-01-0-0"><i class="fa   fa-user-md fa-sm"></i>&nbsp;<span><?php echo ((is_array($_tmp='User Reports')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></a></li>
	<?php endif; ?>
    <?php if ($this->_tpl_vars['admin']['pg_reports_trend']): ?>
 <li><a href="desk.php?action=report_trend_read#general-01-0-0"><i class="fa   fa-signal fa-sm"></i>&nbsp;<span><?php echo ((is_array($_tmp='Trend Reports')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></span></li> 
	<?php endif; ?>
 </ul>
 </li>
<?php endif; ?> 

 
<?php else: ?>
 <a href="desk.php" style="z-index:999;"><i class="fa fa-dashboard fa-lg"></i><span><?php echo ((is_array($_tmp='Exit To Dashboard')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a> 
	 
<?php endif; ?>

 
 
 
 
 
 
 