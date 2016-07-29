<?php /* Smarty version 2.6.18, created on 2016-07-05 14:13:09
         compiled from map_overlay_edit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'map_overlay_edit.tpl', 4, false),)), $this); ?>
<!-- map_overlay_edit -->
<div class="FormFieldset">
    <div class="FormFieldsetHeader">
        <div class="FormFieldsetHeaderTitle"><?php echo smarty_function_localize(array('str' => 'Map overlay settings'), $this);?>
</div>
    </div>
    <?php echo "<div id=\"totalRevenue\"></div>"; ?>
    <?php echo "<div id=\"commissions\"></div>"; ?>
    <?php echo "<div id=\"salesCount\"></div>"; ?>
    <?php echo "<div id=\"rawClicks\"></div>"; ?>
    <?php echo "<div id=\"rawImpressions\"></div>"; ?>

    <?php echo "<div id=\"FormMessage\"></div>"; ?>
    <?php echo "<div id=\"SaveButton\"></div>"; ?>
</div>