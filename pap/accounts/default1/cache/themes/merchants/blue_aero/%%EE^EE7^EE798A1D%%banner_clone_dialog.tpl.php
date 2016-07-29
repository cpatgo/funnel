<?php /* Smarty version 2.6.18, created on 2016-07-06 14:13:14
         compiled from banner_clone_dialog.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'banner_clone_dialog.tpl', 2, false),)), $this); ?>
<!-- banner_clone_dialog -->
<div class="FormFieldLabel"><?php echo smarty_function_localize(array('str' => 'Banner name'), $this);?>
</div>
<div class="Inliner"><?php echo "<div id=\"prefix\"></div>"; ?></div>
<div class="Inliner"><?php echo "<div id=\"bannerName\"></div>"; ?></div>
<div class="Inliner"><?php echo "<div id=\"suffix\"></div>"; ?></div>

<?php echo "<div id=\"Campaign\"></div>"; ?>
<?php echo "<div id=\"URL\"></div>"; ?>
<?php echo "<div id=\"OkButton\"></div>"; ?>
<?php echo "<div id=\"CancelButton\"></div>"; ?>
<div class="clear"></div>
