<?php /* Smarty version 2.6.18, created on 2016-07-06 14:14:09
         compiled from email_settings_tab.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'email_settings_tab.tpl', 3, false),)), $this); ?>
<!-- email_settings_tab -->

<h3 class="TabDescription"><?php echo smarty_function_localize(array('str' => 'Email settings'), $this);?>
</h3>
<br/>

<?php echo "<div id=\"email\"></div>"; ?>
<?php echo "<div id=\"send_test_mail_to\"></div>"; ?>
<?php echo "<div id=\"spfInfoPanel\"></div>"; ?>
<?php echo "<div id=\"warningMessage\"></div>"; ?>
<?php echo "<div id=\"form_message\"></div>"; ?>
<?php echo "<div id=\"form_message_sendform\"></div>"; ?>
<br/>
<?php echo "<div id=\"save_button\"></div>"; ?>
<?php echo "<div id=\"send_mail_button\"></div>"; ?>

<div class="clear"></div>