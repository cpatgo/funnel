<?php /* Smarty version 2.6.12, created on 2016-07-18 12:18:01
         compiled from new_subscriber_alert.txt */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'plang', 'new_subscriber_alert.txt', 1, false),array('modifier', 'acpdate', 'new_subscriber_alert.txt', 11, false),)), $this); ?>
<?php echo ((is_array($_tmp="A new subscriber has been added to your list.")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>


<?php echo ((is_array($_tmp="Subscribed to lists:")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>

<?php $_from = $this->_tpl_vars['lists']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
<?php echo $this->_tpl_vars['list']['name']; ?>

<?php endforeach; endif; unset($_from); ?>

<?php echo ((is_array($_tmp='Email')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
: <?php echo $this->_tpl_vars['subscriber']['email']; ?>

<?php echo ((is_array($_tmp='Name')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
: <?php echo $this->_tpl_vars['subscriber']['first_name']; ?>
 <?php echo $this->_tpl_vars['subscriber']['last_name']; ?>

<?php echo ((is_array($_tmp='IP')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
: <?php echo $this->_tpl_vars['subscriber']['ip']; ?>

<?php echo ((is_array($_tmp="Date/Time")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
: <?php echo ((is_array($_tmp=$this->_tpl_vars['subscriber']['cdate'])) ? $this->_run_mod_handler('acpdate', true, $_tmp, "%m/%d/%Y %H:%M") : smarty_modifier_acpdate($_tmp, "%m/%d/%Y %H:%M")); ?>


<?php $_from = $this->_tpl_vars['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
<?php echo $this->_tpl_vars['field']['title']; ?>
: <?php echo $this->_tpl_vars['field']['val']; ?>

<?php endforeach; endif; unset($_from); ?>