<?php /* Smarty version 2.6.18, created on 2016-06-29 11:48:56
         compiled from text://9713eb5ddb109c5e767e8ce8b6c249b8 */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'text://9713eb5ddb109c5e767e8ce8b6c249b8', 1, false),array('modifier', 'number', 'text://9713eb5ddb109c5e767e8ce8b6c249b8', 4, false),array('modifier', 'currency', 'text://9713eb5ddb109c5e767e8ce8b6c249b8', 8, false),)), $this); ?>
<?php echo smarty_function_localize(array('str' => 'Now is'), $this);?>
 <?php echo $this->_tpl_vars['date']; ?>
 <?php echo $this->_tpl_vars['time']; ?>
<br>
<?php echo smarty_function_localize(array('str' => 'Weekly report is generated for:'), $this);?>
 <span style="font-weight: bold;"><?php echo $this->_tpl_vars['dateFrom']; ?>
 </span>- <span style="font-weight: bold;"><?php echo $this->_tpl_vars['dateTo']; ?>
</span><br>
<br>
<?php echo smarty_function_localize(array('str' => 'Impressions:'), $this);?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['impressions']->count->all)) ? $this->_run_mod_handler('number', true, $_tmp) : smarty_modifier_number($_tmp)); ?>
<br>
<?php echo smarty_function_localize(array('str' => 'Clicks:'), $this);?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['clicks']->count->all)) ? $this->_run_mod_handler('number', true, $_tmp) : smarty_modifier_number($_tmp)); ?>
<br>
<br>
<?php echo smarty_function_localize(array('str' => 'Number of Sales:'), $this);?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['sales']->count->all)) ? $this->_run_mod_handler('number', true, $_tmp) : smarty_modifier_number($_tmp)); ?>
<br>
<?php echo smarty_function_localize(array('str' => 'Commissions per Sales:'), $this);?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['sales']->commission->all)) ? $this->_run_mod_handler('currency', true, $_tmp) : smarty_modifier_currency($_tmp)); ?>
<br>
<?php echo smarty_function_localize(array('str' => 'Totalcost of Sales:'), $this);?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['sales']->totalCost->all)) ? $this->_run_mod_handler('currency', true, $_tmp) : smarty_modifier_currency($_tmp)); ?>
<br>
<br>
<?php echo smarty_function_localize(array('str' => 'Number of Actions:'), $this);?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['actions']->count->all)) ? $this->_run_mod_handler('number', true, $_tmp) : smarty_modifier_number($_tmp)); ?>
<br>
<?php echo smarty_function_localize(array('str' => 'Commissions per Actions:'), $this);?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['actions']->commission->all)) ? $this->_run_mod_handler('currency', true, $_tmp) : smarty_modifier_currency($_tmp)); ?>
<br>
<?php echo smarty_function_localize(array('str' => 'Total cost of Actions:'), $this);?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['actions']->totalCost->all)) ? $this->_run_mod_handler('currency', true, $_tmp) : smarty_modifier_currency($_tmp)); ?>
<br>
<br>
<?php echo smarty_function_localize(array('str' => 'All Commissions:'), $this);?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['transactions']->commission->all)) ? $this->_run_mod_handler('currency', true, $_tmp) : smarty_modifier_currency($_tmp)); ?>
<br>
-----------------------------------<br>
<br>
<?php echo $this->_tpl_vars['commissionsList']->list; ?>
<br /><br />
<?php echo smarty_function_localize(array('str' => 'To disable these notifications, please follow the link below:'), $this);?>

<br />
<a href="<?php echo $this->_tpl_vars['unsubscribeLink']; ?>
"><?php echo $this->_tpl_vars['unsubscribeLink']; ?>
</a>