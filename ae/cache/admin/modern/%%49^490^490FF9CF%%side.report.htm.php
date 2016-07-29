<?php /* Smarty version 2.6.12, created on 2016-07-13 11:54:25
         compiled from side.report.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'side.report.htm', 4, false),)), $this); ?>
<p>&nbsp;</p>
<?php if ($this->_tpl_vars['admin']['pg_reports_campaign'] || $this->_tpl_vars['admin']['pg_reports_list'] || $this->_tpl_vars['admin']['pg_reports_user']): ?>
<header class="panel-heading bg bg-inverse">
         <?php echo ((is_array($_tmp='Reports')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

            </header>
  <div class="list-group list-normal m-b-none">
<?php if ($this->_tpl_vars['admin']['pg_reports_campaign']): ?>
		 <a href="desk.php?action=campaign&reports=1#list-01-0-0"  class="list-group-item"> <i class="fa fa-bar-chart-o fa-sm"></i> <?php echo ((is_array($_tmp='Campaign Reports')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a> 
<?php endif; ?>
<?php if ($this->_tpl_vars['admin']['pg_reports_list']): ?>
		 <a href="desk.php?action=report_list#general-01-0-0" class="list-group-item"> <i class="fa fa-indent fa-sm"></i> <?php echo ((is_array($_tmp='List Reports')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a> 
<?php endif; ?>
<?php if ($this->_tpl_vars['admin']['pg_reports_user']): ?>
		 <a href="desk.php?action=report_user#general-01-0-0" class="list-group-item"> <i class="fa fa-user-md fa-sm"></i> <?php echo ((is_array($_tmp='User Reports')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a> 
<?php endif; ?>

 </div>
<?php endif; ?>

 
<?php if ($this->_tpl_vars['admin']['pg_reports_trend']): ?>
<header class="panel-heading bg bg-inverse">
       <?php echo ((is_array($_tmp='Trend Reporting')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

            </header>
  <div class="list-group list-normal m-b-none">
 
 
	 <a href="desk.php?action=report_trend_read#general-01-0-0" class="list-group-item"> <i class="fa fa-signal fa-sm"></i> <?php echo ((is_array($_tmp='Read Trends')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a> 
		 <a href="desk.php?action=report_trend_client#general-01-0-0" class="list-group-item"> <i class="fa fa-globe fa-sm"></i> <?php echo ((is_array($_tmp='Email Client Trends')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a> 
 </div>
<?php endif; ?>
 