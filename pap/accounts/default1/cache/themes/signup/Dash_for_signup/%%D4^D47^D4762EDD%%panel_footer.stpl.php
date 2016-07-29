<?php /* Smarty version 2.6.18, created on 2016-07-06 14:11:53
         compiled from panel_footer.stpl */ ?>
<!-- panel_footer -->
<?php $_from = $this->_tpl_vars['footerExtensions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['extension']):
?>
    <?php echo $this->_tpl_vars['extension']; ?>

<?php endforeach; endif; unset($_from); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'footer.stpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>