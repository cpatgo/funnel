<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:46
         compiled from account_notifications_form.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'account_notifications_form.tpl', 3, false),)), $this); ?>
<!--	account_notifications_form		-->

<div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'Actions'), $this);?>
</div>
<?php echo "<div id=\"acc_notif_on_new_account_signup_before_approval\"></div>"; ?>
<?php echo "<div id=\"account_notification_on_request_payment\"></div>"; ?>
<?php echo "<div id=\"account_notification_on_invoice_paid\"></div>"; ?>
<?php echo "<div id=\"account_notification_on_suspended\"></div>"; ?>
<?php echo "<div id=\"account_notification_on_declined\"></div>"; ?>

<?php echo "<div id=\"formMessage\"></div>"; ?>
<?php echo "<div id=\"saveButton\"></div>"; ?>