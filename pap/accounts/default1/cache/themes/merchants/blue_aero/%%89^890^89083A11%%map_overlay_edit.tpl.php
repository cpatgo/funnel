<?php /* Smarty version 2.6.18, created on 2016-07-06 14:14:46
         compiled from map_overlay_edit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'map_overlay_edit.tpl', 3, false),)), $this); ?>
<!-- map_overlay_edit -->
<fieldset>
<legend><?php echo smarty_function_localize(array('str' => 'Map overlay settings'), $this);?>
</legend>
  <?php echo "<div id=\"totalRevenue\"></div>"; ?>
  <?php echo "<div id=\"commissions\"></div>"; ?>
  <?php echo "<div id=\"salesCount\"></div>"; ?>
  <?php echo "<div id=\"rawClicks\"></div>"; ?>
  <?php echo "<div id=\"rawImpressions\"></div>"; ?>
  <?php echo "<div id=\"FormMessage\"></div>"; ?>
  <?php echo "<div id=\"SaveButton\"></div>"; ?>
</fieldset>