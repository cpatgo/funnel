<?php /* Smarty version 2.6.18, created on 2016-07-06 14:14:34
         compiled from integration_methods_advanced.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'integration_methods_advanced.tpl', 21, false),)), $this); ?>
<!-- integration_methods_advanced -->
<div class="FloatLeft">
    <?php echo "<div id=\"action\"></div>"; ?>
    <?php echo "<div id=\"affiliate\"></div>"; ?>
    <?php echo "<div id=\"campaign\"></div>"; ?>
    <?php echo "<div id=\"status\"></div>"; ?>
    <?php echo "<div id=\"commission\"></div>"; ?>
    <?php echo "<div id=\"campaignNextTiers\"></div>"; ?>
    <?php echo "<div id=\"channel\"></div>"; ?>
    <?php echo "<div id=\"coupon\"></div>"; ?>
    <?php echo "<div id=\"currency\"></div>"; ?>
</div>
<div class="FloatLeft" style="margin-left: 30px;">
    <?php echo "<div id=\"data1\"></div>"; ?>
    <?php echo "<div id=\"data2\"></div>"; ?>
    <?php echo "<div id=\"data3\"></div>"; ?>
    <?php echo "<div id=\"data4\"></div>"; ?>
    <?php echo "<div id=\"data5\"></div>"; ?>
</div>
<div class="ClearLeft"></div>
<span class="Bold"><?php echo smarty_function_localize(array('str' => 'Notice: Advanced tracking options are not supported by all integration methods.'), $this);?>
</span>