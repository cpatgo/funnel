<?php /* Smarty version 2.6.12, created on 2016-07-08 16:20:57
         compiled from message.tpl.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'js', 'message.tpl.htm', 18, false),)), $this); ?>

<?php if (isset ( $this->_tpl_vars['error_mesg'] ) && $this->_tpl_vars['error_mesg'] != '' && ! isset ( $this->_tpl_vars['resultMessage'] )): ?>
<?php $this->assign('resultMessage', $this->_tpl_vars['error_mesg']); ?>
<?php endif; ?>

<?php if (isset ( $_POST['info'] )): ?>
<?php $this->assign('resultMessage', $_POST['info']); ?>
<?php endif; ?>
<?php if (isset ( $_GET['info'] )): ?>
<?php $this->assign('resultMessage', $_GET['info']); ?>
<?php endif; ?>

<?php if (isset ( $this->_tpl_vars['resultMessage'] )): ?>
<script>
<?php if (isset ( $this->_tpl_vars['resultStatus'] )): ?>
<?php if ($this->_tpl_vars['resultStatus']): ?>adesk_result_show<?php else: ?>adesk_error_show<?php endif; ?>('<?php echo ((is_array($_tmp=$this->_tpl_vars['resultMessage'])) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
');
<?php else: ?>
adesk_result_show('<?php echo ((is_array($_tmp=$this->_tpl_vars['resultMessage'])) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
');
<?php endif; ?>
</script>
<?php endif; ?>