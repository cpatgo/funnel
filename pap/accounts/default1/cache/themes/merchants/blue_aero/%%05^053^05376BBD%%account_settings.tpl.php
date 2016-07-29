<?php /* Smarty version 2.6.18, created on 2016-07-06 14:13:02
         compiled from account_settings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'account_settings.tpl', 4, false),)), $this); ?>
<!-- account_settings -->

<fieldset>
    <legend><?php echo smarty_function_localize(array('str' => 'Pricing options'), $this);?>
</legend>
    <?php echo "<div id=\"applyVatInvoicing\"></div>"; ?>
    <?php echo "<div id=\"vatPercentage\"></div>"; ?>
</fieldset>
<?php echo "<div id=\"feePanel\"></div>"; ?>
<fieldset>
    <legend><?php echo smarty_function_localize(array('str' => 'Tracking Settings'), $this);?>
</legend>
    <?php echo "<div id=\"salesCallbackUrl\" class=\"MainSiteUrl\"></div>"; ?>
    <?php echo "<div id=\"isCallbackIgnoredForDeclinedCommission\"></div>"; ?>
    <div class="Line"></div>
    <?php echo "<div id=\"forceCampaignByProductId\"></div>"; ?>
</fieldset>

<?php echo "<div id=\"FormMessage\"></div>"; ?>
<?php echo "<div id=\"sendButton\"></div>"; ?>