<?php /* Smarty version 2.6.18, created on 2016-07-06 14:13:25
         compiled from banner_parameters_site.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'banner_parameters_site.tpl', 3, false),)), $this); ?>
<!-- banner_parameters_site -->
<fieldset class="BannerSite">
<legend><?php echo smarty_function_localize(array('str' => 'Replicated site URL'), $this);?>
</legend>
    <?php echo "<div id=\"destinationurl\" class=\"DestinationUrl\"></div>"; ?>
    <div class="clear" style="height: 10px;"></div>
</fieldset>
<?php echo "<div id=\"preview\"></div>"; ?>

<?php echo "<div id=\"files\"></div>"; ?>

<fieldset class="BannerSite">
<legend><?php echo smarty_function_localize(array('str' => 'Aditional options'), $this);?>
</legend>
    <?php echo "<div id=\"encode\"></div>"; ?>
    <?php echo "<div id=\"defaultAffiliate\"></div>"; ?>
    <?php echo "<div id=\"addParamsToRequestPage\"></div>"; ?>
</fieldset>