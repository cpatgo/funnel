<?php /* Smarty version 2.6.18, created on 2016-06-29 11:48:56
         compiled from text://eda3435d7845982aa114eca4b9eea42f */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'text://eda3435d7845982aa114eca4b9eea42f', 2, false),array('modifier', 'escape', 'text://eda3435d7845982aa114eca4b9eea42f', 5, false),array('modifier', 'currency', 'text://eda3435d7845982aa114eca4b9eea42f', 10, false),)), $this); ?>
<font size="2">
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Dear merchant,'), $this);?>
</span><br/><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Status of sale / lead was changed to:'), $this);?>
 <?php echo $this->_tpl_vars['status']; ?>
.</span><br/><br/>
<font size="4"><strong style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Sale details'), $this);?>
:</strong></font><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Affiliate'), $this);?>
: </span><strong style="font-family: Arial;"><?php echo ((is_array($_tmp=$this->_tpl_vars['firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['lastname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
,</strong><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Order ID'), $this);?>
: </span><strong style="font-family: Arial;"><?php echo ((is_array($_tmp=$this->_tpl_vars['orderid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</strong><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Product ID'), $this);?>
: </span><strong style="font-family: Arial;"><?php echo ((is_array($_tmp=$this->_tpl_vars['productid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</strong><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'IP address'), $this);?>
: </span><strong style="font-family: Arial;"><?php echo ((is_array($_tmp=$this->_tpl_vars['ip'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</strong><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Referrer Url'), $this);?>
: </span><strong style="font-family: Arial;"><?php echo ((is_array($_tmp=$this->_tpl_vars['refererurl'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</strong><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Commission from this sale'), $this);?>
: </span><strong style="font-family: Arial;"><?php echo ((is_array($_tmp=$this->_tpl_vars['commission'])) ? $this->_run_mod_handler('currency', true, $_tmp) : smarty_modifier_currency($_tmp)); ?>
</strong><br/><br/>
</font>