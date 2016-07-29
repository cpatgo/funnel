<?php /* Smarty version 2.6.18, created on 2016-07-06 12:43:40
         compiled from update_check_requirements.stpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'update_check_requirements.stpl', 3, false),array('function', 'server_widget', 'update_check_requirements.stpl', 7, false),)), $this); ?>
<!-- installer_welcome -->
<p>
<?php echo smarty_function_localize(array('str' => 'Please review your system settings, before starting update all requirements must be fulfilled.'), $this);?>

</p>


<?php echo smarty_function_server_widget(array('class' => 'Pap_Install_Ui_CheckRequirements'), $this);?>

<?php echo smarty_function_server_widget(array('class' => 'Pap_Install_Ui_RecommendedSettings'), $this);?>


<?php echo "<div id=\"Check\"></div>"; ?>