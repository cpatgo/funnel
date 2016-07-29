<?php /* Smarty version 2.6.18, created on 2016-07-06 14:13:13
         compiled from affiliate_tracking_cookiepanel.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'affiliate_tracking_cookiepanel.tpl', 3, false),)), $this); ?>
<!-- affiliate_tracking_cookiepanel -->
<fieldset>
    <legend><?php echo smarty_function_localize(array('str' => 'Cookie settings'), $this);?>
</legend>
    <div class="HintText"><?php echo smarty_function_localize(array('str' => 'This setting can override the default configuration from campaign'), $this);?>
</div> 
    <?php echo "<div id=\"overwriteCookie\"></div>"; ?>
    <?php echo "<div id=\"overwriteAfterDays\"></div>"; ?>
    <?php echo "<div id=\"FormMessage\"></div>"; ?>
    <?php echo "<div id=\"SaveButton\"></div>"; ?>
</fieldset>