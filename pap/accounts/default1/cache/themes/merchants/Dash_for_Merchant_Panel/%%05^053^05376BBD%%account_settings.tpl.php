<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:46
         compiled from account_settings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'account_settings.tpl', 3, false),)), $this); ?>
<!-- account_settings -->

<div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'VAT options'), $this);?>
</div>
  <?php echo "<div id=\"applyVatInvoicing\"></div>"; ?>
  <?php echo "<div id=\"vatPercentage\"></div>"; ?>

<?php echo "<div id=\"feePanel\"></div>"; ?>

<div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'Tracking Settings'), $this);?>
</div>
  <?php echo "<div id=\"salesCallbackUrl\" class=\"MainSiteUrl\"></div>"; ?>
  <?php echo "<div id=\"isCallbackIgnoredForDeclinedCommission\"></div>"; ?>
  <div class="Line"></div>
  <?php echo "<div id=\"forceCampaignByProductId\"></div>"; ?>

<div class="clear"></div>
<?php echo "<div id=\"FormMessage\"></div>"; ?>
<?php echo "<div id=\"sendButton\"></div>"; ?>