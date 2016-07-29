<?php /* Smarty version 2.6.18, created on 2016-07-06 14:15:09
         compiled from recaptcha_settings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'recaptcha_settings.tpl', 3, false),)), $this); ?>
<!--    recaptcha_settings     -->
<fieldset>
    <legend><?php echo smarty_function_localize(array('str' => 'Signup settings'), $this);?>
</legend>
    <br/>
    <?php echo "<div id=\"affiliate_signup_recaptcha\"></div>"; ?>
    <?php echo "<div id=\"account_signup_recaptcha\"></div>"; ?>
</fieldset>

<fieldset>
    <legend><?php echo smarty_function_localize(array('str' => 'Request new password settings'), $this);?>
</legend>
    <br/>
    <?php echo "<div id=\"request_new_password_recaptcha\"></div>"; ?>
</fieldset>
<?php echo "<div id=\"google_recaptcha_settings\"></div>"; ?>

<?php echo "<div id=\"FormMessage\"></div>"; ?>
<?php echo "<div id=\"save_button\"></div>"; ?>
