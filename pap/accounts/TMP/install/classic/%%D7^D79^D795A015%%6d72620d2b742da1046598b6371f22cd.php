<?php /* Smarty version 2.6.18, created on 2016-06-29 11:48:55
         compiled from text://6d72620d2b742da1046598b6371f22cd */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'text://6d72620d2b742da1046598b6371f22cd', 2, false),array('modifier', 'escape', 'text://6d72620d2b742da1046598b6371f22cd', 2, false),array('modifier', 'currency', 'text://6d72620d2b742da1046598b6371f22cd', 4, false),)), $this); ?>
<font size="2">
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Dear'), $this);?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['lastname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
,</span><br/><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'new sale / lead was registered by our affiliate program with status:'), $this);?>
 <?php echo $this->_tpl_vars['status']; ?>
.</span><br/><br/><font size="4"><strong style="font-family: Arial;"><?php if ($this->_tpl_vars['rawtype'] == 'U'): ?><?php echo smarty_function_localize(array('str' => 'Recurring sale'), $this);?>
<?php else: ?><?php echo smarty_function_localize(array('str' => 'Sale'), $this);?>
<?php endif; ?> <?php echo smarty_function_localize(array('str' => 'details'), $this);?>
:</strong></font><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Total cost'), $this);?>
: </span><strong style="font-family: Arial;"><?php echo ((is_array($_tmp=$this->_tpl_vars['totalcost'])) ? $this->_run_mod_handler('currency', true, $_tmp) : smarty_modifier_currency($_tmp)); ?>
</strong><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Commission from this sale'), $this);?>
: </span><strong style="font-family: Arial;"><?php echo ((is_array($_tmp=$this->_tpl_vars['commission'])) ? $this->_run_mod_handler('currency', true, $_tmp) : smarty_modifier_currency($_tmp)); ?>
</strong><br/>
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
</strong><br/><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Sincerely'), $this);?>
,</span><br/><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Your Affiliate manager'), $this);?>
</span>
<br /><br />
<?php echo smarty_function_localize(array('str' => 'To disable these notifications, please follow the link below:'), $this);?>

<br />
<a href="<?php echo $this->_tpl_vars['unsubscribeLink']; ?>
"><?php echo $this->_tpl_vars['unsubscribeLink']; ?>
</a>
</font>