<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:46
         compiled from affiliate_tracking_cookiepanel.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'affiliate_tracking_cookiepanel.tpl', 2, false),)), $this); ?>
<!-- affiliate_tracking_cookiepanel -->
<div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'Cookie settings'), $this);?>
</div>
<div class="FormFieldsetHeaderDescription"><?php echo smarty_function_localize(array('str' => 'This setting can override the default configuration from campaign'), $this);?>
</div>
<div class="pad_top pad_bottom">
  <?php echo "<div id=\"overwriteCookie\"></div>"; ?>
  <?php echo "<div id=\"overwriteAfterDays\"></div>"; ?>
  <?php echo "<div id=\"FormMessage\"></div>"; ?>
  <?php echo "<div id=\"SaveButton\"></div>"; ?>
</div>