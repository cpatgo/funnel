<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:58
         compiled from banner_parameters_site.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'banner_parameters_site.tpl', 3, false),)), $this); ?>
<!-- banner_parameters_site -->
<div class="BannerSite">
  <div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'Replicated site URL'), $this);?>
</div>
    <?php echo "<div id=\"destinationurl\" class=\"DestinationUrl\"></div>"; ?>
    <div class="clear" style="height: 10px;"></div>
</div>

<?php echo "<div id=\"preview\"></div>"; ?>

<?php echo "<div id=\"files\"></div>"; ?>

<div class="BannerSite">
  <div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'Aditional options'), $this);?>
</div>
    <?php echo "<div id=\"encode\"></div>"; ?>
    <?php echo "<div id=\"defaultAffiliate\"></div>"; ?>
    <?php echo "<div id=\"addParamsToRequestPage\"></div>"; ?>
</div>