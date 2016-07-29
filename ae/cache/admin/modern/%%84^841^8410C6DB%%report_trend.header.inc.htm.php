<?php /* Smarty version 2.6.12, created on 2016-07-13 11:54:42
         compiled from report_trend.header.inc.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'report_trend.header.inc.htm', 4, false),)), $this); ?>
<?php if (! isset ( $this->_tpl_vars['reportpage'] )):  $this->assign('reportpage', 'read');  endif; ?>
<?php if ($this->_tpl_vars['list']): ?>
<div style="color:#999999;float:right;margin-top: 6px;">
  <a href="desk.php?action=<?php if ($this->_tpl_vars['reportpage'] == 'read'): ?>report_trend_read<?php else: ?>report_trend_client<?php endif; ?>" style="color:#999999;"><?php if ($this->_tpl_vars['reportpage'] == 'read'):  echo ((is_array($_tmp='Read Trends')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp));  else:  echo ((is_array($_tmp='Email Client Trends')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp));  endif; ?></a>
  &raquo;
  <a href="desk.php?action=<?php if ($this->_tpl_vars['reportpage'] == 'read'): ?>report_trend_read<?php else: ?>report_trend_client<?php if ($this->_tpl_vars['list']): ?>_list<?php endif;  endif;  if ($this->_tpl_vars['list']): ?>&id=<?php echo $this->_tpl_vars['lid'];  endif; ?>" style="color:#999999;"><?php echo ((is_array($_tmp="List '%s'")) ? $this->_run_mod_handler('alang', true, $_tmp, $this->_tpl_vars['list']['name']) : smarty_modifier_alang($_tmp, $this->_tpl_vars['list']['name'])); ?>
</a>
</div>
<?php endif; ?>
<div id="reportheader" style="margin-bottom:10px;">
	<ul class="navlist">
		<li class="<?php if ($this->_tpl_vars['reportpage'] == 'read'): ?>currenttab<?php else: ?>othertab<?php endif; ?>"><a href="desk.php?action=report_trend_read<?php if ($this->_tpl_vars['lid']): ?>&id=<?php echo $this->_tpl_vars['lid'];  endif; ?>"><?php echo ((is_array($_tmp='Read Trends')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
		<li class="<?php if ($this->_tpl_vars['reportpage'] == 'client'): ?>currenttab<?php else: ?>othertab<?php endif; ?>"><a href="desk.php?action=report_trend_client<?php if ($this->_tpl_vars['lid']): ?>_list&id=<?php echo $this->_tpl_vars['lid'];  endif; ?>"><?php echo ((is_array($_tmp='Email Client Trends')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
	</ul>
</div>