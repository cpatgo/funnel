<?php /* Smarty version 2.6.18, created on 2016-06-29 11:48:55
         compiled from text://34dcc1e9c9ee38972f12de83c8539eb1 */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'text://34dcc1e9c9ee38972f12de83c8539eb1', 2, false),array('modifier', 'escape', 'text://34dcc1e9c9ee38972f12de83c8539eb1', 2, false),array('modifier', 'currency', 'text://34dcc1e9c9ee38972f12de83c8539eb1', 4, false),)), $this); ?>
<font style="font-family: Arial;" size="2">
<?php echo smarty_function_localize(array('str' => 'Dear'), $this);?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['lastname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
,<br/><br/>
<?php echo smarty_function_localize(array('str' => 'One of your sub-affiliates'), $this);?>
 </font><font style="font-family: Arial;" size="2"><?php echo smarty_function_localize(array('str' => 'made a sale/lead.'), $this);?>
<br/><br/>
<strong>.:<?php echo smarty_function_localize(array('str' => 'Sale/lead preview'), $this);?>
:.</strong><br/><?php echo smarty_function_localize(array('str' => 'Total cost'), $this);?>
: <strong><?php echo ((is_array($_tmp=$this->_tpl_vars['totalcost'])) ? $this->_run_mod_handler('currency', true, $_tmp) : smarty_modifier_currency($_tmp)); ?>
</strong><br/>
<?php echo smarty_function_localize(array('str' => 'Product ID'), $this);?>
: <strong><?php echo ((is_array($_tmp=$this->_tpl_vars['productid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</strong><br/><br/><?php echo smarty_function_localize(array('str' => 'Sincerely,'), $this);?>
<br/><br/>
<?php echo smarty_function_localize(array('str' => 'Your Affiliate manager'), $this);?>
<br/>
<br /><br />
<?php echo smarty_function_localize(array('str' => 'To disable these notifications, please follow the link below:'), $this);?>

<br />
<a href="<?php echo $this->_tpl_vars['unsubscribeLink']; ?>
"><?php echo $this->_tpl_vars['unsubscribeLink']; ?>
</a>
</font>