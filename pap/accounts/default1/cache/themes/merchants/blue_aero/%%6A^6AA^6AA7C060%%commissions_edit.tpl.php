<?php /* Smarty version 2.6.18, created on 2016-07-06 14:13:47
         compiled from commissions_edit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'commissions_edit.tpl', 6, false),)), $this); ?>
<!-- commissions_edit -->
<div class="CommissionEditTopExtensionPanel">
    <?php echo "<div id=\"FeatureTopExtensionFormPanel\"></div>"; ?>
</div>
<fieldset>
<legend><?php echo smarty_function_localize(array('str' => 'Commission type settings'), $this);?>
</legend>
    <?php echo "<div id=\"code\"></div>"; ?>
    <?php echo "<div id=\"name\"></div>"; ?>
    <?php echo "<div id=\"approval\" class=\"Approval\"></div>"; ?>
    <?php echo "<div id=\"zeroorderscommission\" class=\"ZeroOrdersCommissions\"></div>"; ?>
    <?php echo "<div id=\"savezerocommission\" class=\"ZeroOrdersCommissions\"></div>"; ?>
    <?php echo "<div id=\"onlyUnique\"></div>"; ?>
</fieldset>

<fieldset>
<legend><?php echo smarty_function_localize(array('str' => 'Commissions'), $this);?>
</legend>
<?php echo "<div id=\"resetCommission\"></div>"; ?>
<?php echo "<div id=\"NormalCommissionValues\"></div>"; ?>
</fieldset>

<?php echo "<div id=\"FeatureExtensionFormPanel\"></div>"; ?>

<?php echo "<div id=\"PluginExtensionFormPanel\"></div>"; ?>

<?php echo "<div id=\"FormMessage\"></div>"; ?>
<?php echo "<div id=\"SaveButton\"></div>"; ?> <?php echo "<div id=\"CloseButton\"></div>"; ?>