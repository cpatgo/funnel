<?php /* Smarty version 2.6.18, created on 2016-06-29 11:48:56
         compiled from text://e78eb13edc5c89c4094aaeed47241923 */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'text://e78eb13edc5c89c4094aaeed47241923', 2, false),array('modifier', 'escape', 'text://e78eb13edc5c89c4094aaeed47241923', 3, false),)), $this); ?>
<font size="2">
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Dear merchant'), $this);?>
,</span><br/><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Affiliate'), $this);?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['lastname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo smarty_function_localize(array('str' => 'joined your campaign'), $this);?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['campaignname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
.</span><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'This affiliate is waiting for your approval.'), $this);?>
</span><br/><br/>

<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'If you want to APPROVE affiliate to join camapign click here'), $this);?>
: </span><br/>
<span style="font-family: Arial;"><a href="<?php echo $this->_tpl_vars['affiliate_join_campaign_approve_link']; ?>
"><?php echo $this->_tpl_vars['affiliate_join_campaign_approve_link']; ?>
</a></span>
<br/><br/>

<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'If you want to DECLINE affiliate to join camapign click here'), $this);?>
: </span><br/>
<span style="font-family: Arial;"><a href="<?php echo $this->_tpl_vars['affiliate_join_campaign_decline_link']; ?>
"><?php echo $this->_tpl_vars['affiliate_join_campaign_decline_link']; ?>
</a></span>
<br/><br/>

<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Regards'), $this);?>
,</span><br/><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Your'), $this);?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['postAffiliatePro'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
.</span>
</font>