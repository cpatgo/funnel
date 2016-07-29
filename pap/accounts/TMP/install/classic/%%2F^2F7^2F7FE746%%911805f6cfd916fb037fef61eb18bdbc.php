<?php /* Smarty version 2.6.18, created on 2016-06-29 11:48:56
         compiled from text://911805f6cfd916fb037fef61eb18bdbc */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'text://911805f6cfd916fb037fef61eb18bdbc', 2, false),array('function', 'localize', 'text://911805f6cfd916fb037fef61eb18bdbc', 40, false),)), $this); ?>
<font size="2">
<span style="font-family: Arial;">Dear <?php echo ((is_array($_tmp=$this->_tpl_vars['firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['lastname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span> <br/><br/>

<?php if (! empty ( $this->_tpl_vars['directlinks_approved'] )): ?>
<span style="font-family: Arial;">These DirectLinks have been approved:</span><br/>
<?php $_from = $this->_tpl_vars['directlinks_approved']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['approvedLink']):
?>
    <span style="font-family: Arial;"><?php echo ((is_array($_tmp=$this->_tpl_vars['approvedLink'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span><br/>
<?php endforeach; endif; unset($_from); ?>
<br/><br/>
<?php endif; ?>

<?php if (! empty ( $this->_tpl_vars['directlinks_declined'] )): ?>
<span style="font-family: Arial;">These DirectLinks have been declined:</span><br/>
<?php $_from = $this->_tpl_vars['directlinks_declined']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['declinedLink']):
?>
    <span style="font-family: Arial;"><?php echo ((is_array($_tmp=$this->_tpl_vars['declinedLink'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span><br/>
<?php endforeach; endif; unset($_from); ?>
<br/><br/>
<?php endif; ?>

<?php if (! empty ( $this->_tpl_vars['directlinks_pending'] )): ?>
<span style="font-family: Arial;">These DirectLinks are pending:</span><br/>
<?php $_from = $this->_tpl_vars['directlinks_pending']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pendingLink']):
?>
    <span style="font-family: Arial;"><?php echo ((is_array($_tmp=$this->_tpl_vars['pendingLink'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span><br/>
<?php endforeach; endif; unset($_from); ?>
<br/><br/>
<?php endif; ?>

<?php if (! empty ( $this->_tpl_vars['directlinks_deleted'] )): ?>
<span style="font-family: Arial;">These DirectLinks have been deleted:</span><br/>
<?php $_from = $this->_tpl_vars['directlinks_deleted']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['deletedLink']):
?>
    <span style="font-family: Arial;"><?php echo ((is_array($_tmp=$this->_tpl_vars['deletedLink'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span><br/>
<?php endforeach; endif; unset($_from); ?>
<br/><br/>
<?php endif; ?>

<br />
<span style="font-family: Arial;">Sincerely,</span><br/><br/>
<span style="font-family: Arial;">Your Affiliate manager</span><br/>
<br /><br />
<?php echo smarty_function_localize(array('str' => 'To disable these notifications, please follow the link below:'), $this);?>

<br />
<a href="<?php echo $this->_tpl_vars['unsubscribeLink']; ?>
"><?php echo $this->_tpl_vars['unsubscribeLink']; ?>
</a>
</font>