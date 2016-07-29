<?php /* Smarty version 2.6.18, created on 2016-07-05 14:13:09
         compiled from feature_settings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'feature_settings.tpl', 6, false),)), $this); ?>
<!-- feature_settings -->
<div class="FormFieldset">
<?php echo "<div id=\"ApplicationDetail\"></div>"; ?>
<?php echo "<div id=\"BuyButtonTop\"></div>"; ?>
<div class="clear"></div>
<div class="Inliner"><?php echo smarty_function_localize(array('str' => 'Search'), $this);?>
:</div><div class="Inliner"><?php echo "<div id=\"searchTextBox\"></div>"; ?></div>
<div class="clear"></div>

<?php echo "<div id=\"PanelActive\" class=\"PanelActivePlugins\"></div>"; ?>
<?php echo "<div id=\"PanelInactive\" class=\"PanelInactivePlugins\"></div>"; ?>
<div class="clear"></div>
<?php echo "<div id=\"BuyButtonBottom\"></div>"; ?>
</div>