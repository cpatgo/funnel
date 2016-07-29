<?php /* Smarty version 2.6.18, created on 2016-07-05 14:13:09
         compiled from map_overlay.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'map_overlay.tpl', 4, false),)), $this); ?>
<!-- map_overlay -->
<div class="FormFieldset">
<?php echo "<div id=\"filter\"></div>"; ?>
<div class="MapOverlayRegion"><div class="MapOverlayRegionLabel"><?php echo smarty_function_localize(array('str' => 'Region:'), $this);?>
</div><?php echo "<div id=\"zoom\"></div>"; ?></div>
<?php echo "<div id=\"map\" class=\"MapOverlayMaps\"></div>"; ?>
<?php echo "<div id=\"grid\"></div>"; ?>
</div>