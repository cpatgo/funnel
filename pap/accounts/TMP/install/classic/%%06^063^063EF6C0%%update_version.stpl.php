<?php /* Smarty version 2.6.18, created on 2016-07-06 12:43:58
         compiled from update_version.stpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'update_version.stpl', 2, false),)), $this); ?>
<!-- update_version -->
<p><?php echo smarty_function_localize(array('str' => 'You are going to update'), $this);?>
 <?php echo $this->_tpl_vars['applicationName']; ?>
 <?php echo smarty_function_localize(array('str' => 'from version'), $this);?>
 
<?php echo $this->_tpl_vars['installedVersion']; ?>
(GPF:<?php echo $this->_tpl_vars['gpfInstalledVersion']; ?>
) <?php echo smarty_function_localize(array('str' => 'to'), $this);?>
 <?php echo $this->_tpl_vars['newVersion']; ?>
(GPF:<?php echo $this->_tpl_vars['gpfNewVersion']; ?>
).</p>
<div><?php echo smarty_function_localize(array('str' => 'NOTE: Make sure that you backup your database and application files.'), $this);?>
</div>

<?php echo "<div id=\"Progress\"></div>"; ?>