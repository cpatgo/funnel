<?php /* Smarty version 2.6.18, created on 2016-06-29 11:48:55
         compiled from text://c526ca795a67ce5af6fa0521327be780 */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'text://c526ca795a67ce5af6fa0521327be780', 2, false),array('modifier', 'escape', 'text://c526ca795a67ce5af6fa0521327be780', 3, false),array('modifier', 'currency', 'text://c526ca795a67ce5af6fa0521327be780', 7, false),)), $this); ?>
<font size="2">
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Dear merchant,'), $this);?>
</span><br/><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Affiliate'), $this);?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['lastname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo smarty_function_localize(array('str' => 'is requesting payment for unpaid commissions'), $this);?>
.</span><br/><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Affiliate name'), $this);?>
: <span style="font-weight: bold;"><?php echo ((is_array($_tmp=$this->_tpl_vars['firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['lastname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span></span><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Affiliate email'), $this);?>
: <span style="font-weight: bold;"><?php echo ((is_array($_tmp=$this->_tpl_vars['username'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span></span><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Affiliate ID'), $this);?>
: <span style="font-weight: bold;"><?php echo $this->_tpl_vars['userid']; ?>
</span></span><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Unpaid commissions (approved / pending):'), $this);?>
 <span style="font-weight: bold;"><?php echo ((is_array($_tmp=$this->_tpl_vars['totalCommisonsApprovedUnpaid'])) ? $this->_run_mod_handler('currency', true, $_tmp) : smarty_modifier_currency($_tmp)); ?>
 / <?php echo ((is_array($_tmp=$this->_tpl_vars['totalCommissionsPendingUnpaid'])) ? $this->_run_mod_handler('currency', true, $_tmp) : smarty_modifier_currency($_tmp)); ?>
</span></span><br/>
<br/>

<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Regards'), $this);?>
,</span><br/><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Your'), $this);?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['postAffiliatePro'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
.</span>
</font>