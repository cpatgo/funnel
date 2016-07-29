<?php /* Smarty version 2.6.18, created on 2016-07-06 14:14:21
         compiled from google_recaptcha_settings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'google_recaptcha_settings.tpl', 3, false),)), $this); ?>
<!--    google_recaptcha_settings     -->
<fieldset>
    <legend><?php echo smarty_function_localize(array('str' => 'Google reCaptcha settings'), $this);?>
</legend>
    <br/>
    <?php echo "<div id=\"recaptcha_public_key\"></div>"; ?>
    <?php echo "<div id=\"recaptcha_private_key\"></div>"; ?>
    <?php echo "<div id=\"recaptcha_theme\"></div>"; ?>
    <?php echo "<div id=\"recaptcha_account_theme\"></div>"; ?>
</fieldset>