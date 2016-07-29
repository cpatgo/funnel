<?php /* Smarty version 2.6.18, created on 2016-07-06 14:14:09
         compiled from email_settings_spf.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'email_settings_spf.tpl', 3, false),)), $this); ?>
<!-- email_settings_spf -->
<div class="SpfSettingsInfo">
    <div class="SpfSettingsDescription"><?php echo smarty_function_localize(array('str' => 'Setup SPF record in your domain settings. You need it to be sure, that your replies won\'t be recognized as spam. Please add one of these two lines to TXT record of your domain. We recommend you to use:'), $this);?>
</div>
    <div class="SpfSettingsCode">v=spf1 redirect=_spf.postaffiliatepro.com</div>
    <div class="SpfSettingsDescription"><?php echo smarty_function_localize(array('str' => 'If you need multiple SPF mechanisms, you can also add include mechanism to your existing record.'), $this);?>
</div>
    <div class="SpfSettingsCode">v=spf1 ... your records ... include:_spf.postaffiliatepro.com -all</div>
    <div class="SpfSettingsDescription"><?php echo smarty_function_localize(array('str' => 'Note: Be sure you have only one DNS record. For more information check our knowledge base:'), $this);?>
 <a href="https://support.qualityunit.com/250549-Mail-account" target="_blank">support.qualityunit.com/250549-Mail-account</a></div>
    <?php echo "<div id=\"warningMessage\" class=\"SpfSettingsWarning\"></div>"; ?>
</div>