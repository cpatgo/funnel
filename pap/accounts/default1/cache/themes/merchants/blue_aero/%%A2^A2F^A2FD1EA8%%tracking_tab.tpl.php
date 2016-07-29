<?php /* Smarty version 2.6.18, created on 2016-07-06 14:15:21
         compiled from tracking_tab.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'tracking_tab.tpl', 6, false),)), $this); ?>
<!-- tracking_tab -->

<div class="TrackingSettingsForm">

<fieldset>
    <legend><?php echo smarty_function_localize(array('str' => 'URLs'), $this);?>
</legend>
    <?php echo "<div id=\"mainSiteUrl\" class=\"MainSiteUrl\"></div>"; ?>
    <div class="FormField MainSiteUrl">
        <div class="FormFieldLabel"><div class="Inliner"><div class="Label Inliner Label-mandatory"><?php echo smarty_function_localize(array('str' => 'Declined site URL'), $this);?>
</div></div></div>
        <div class="FormFieldBigInline"><?php echo "<div id=\"declineSiteUrl\"></div>"; ?></div>
        <div class="Inliner"><?php echo "<div id=\"checkStoppedCampaigns\"></div>"; ?></div>
    </div>
    <div class="clear"></div>
</fieldset>

<fieldset>
    <legend><?php echo smarty_function_localize(array('str' => 'CallBack tracking (server side)'), $this);?>
</legend>
    <?php echo "<div id=\"salesCallbackUrl\" class=\"MainSiteUrl\"></div>"; ?>
    <?php echo "<div id=\"isCallbackIgnoredForDeclinedCommission\"></div>"; ?>
</fieldset>

<fieldset>
    <legend><?php echo smarty_function_localize(array('str' => 'Clicks'), $this);?>
</legend>
    <div class="FormField">
        <div class="Inliner"><div class="Label"><?php echo smarty_function_localize(array('str' => 'Delete click records older than'), $this);?>
</div></div>
        <div class="FormFieldSmallInline"><?php echo "<div id=\"deleterawclicks\"></div>"; ?></div><div class="Inliner"><div class="Label"><?php echo smarty_function_localize(array('str' => 'days'), $this);?>
</div></div>
        <div class="Inliner"><?php echo "<div id=\"deleteRawClicksInfo\"></div>"; ?></div>
    </div>
    <?php echo "<div id=\"deleterawclickshour\"></div>"; ?>
    <div class="clear"></div>
</fieldset>

<fieldset>
    <legend><?php echo smarty_function_localize(array('str' => 'Statistics data'), $this);?>
</legend>
    <?php echo "<div id=\"salesStatsProcessorInterval\" class=\"StatsProcessorInterval\"></div>"; ?>
    <?php echo "<div id=\"impressions_clicks_hours_stats_max_days\" class=\"StatsProcessorInterval\"></div>"; ?>
    <div class="clear"></div>
</fieldset>

<fieldset>
    <legend><?php echo smarty_function_localize(array('str' => 'Tracking levels'), $this);?>
</legend>
    <?php echo "<div id=\"track_by_cookie\"></div>"; ?>
    <?php echo "<div id=\"track_by_htmlstoragecookie\"></div>"; ?>
    <?php echo "<div id=\"track_by_flashcookie\"></div>"; ?>
    <?php echo "<div id=\"track_by_ip_ua\"></div>"; ?>
    <?php echo "<div id=\"track_by_ip\"></div>"; ?>
    <?php echo "<div id=\"ip_validity\" class=\"IpValidity\"></div>"; ?>
    <?php echo "<div id=\"ip_validity_format\" class=\"Validity\"></div>"; ?>
    <div class="Line"></div>
    <?php echo "<div id=\"save_unrefered_sale_lead\" class=\"SaveUnrefered\"></div>"; ?>
    <?php echo "<div id=\"default_affiliate\" class=\"SaveUnrefered UnreferedPadding\"></div>"; ?>
    <?php echo "<div id=\"save_unrefered_click\" class=\"SaveUnrefered UnreferedPadding\"></div>"; ?>
    <?php echo "<div id=\"ignored_referrer_urls_for_nonref_click\" class=\"SaveUnrefered UnreferedClicksPadding\"></div>"; ?>
    <div class="Line"></div>
    <?php echo "<div id=\"declined_affiliate\"></div>"; ?>
    <div class="Line"></div>
    <?php echo "<div id=\"force_choosing_productid\"></div>"; ?>
    <div class="Line"></div>
    <?php echo "<div id=\"deleteExpiredVisitors\"></div>"; ?>
    <div class="Line"></div>
    <?php echo "<div id=\"allowComputeNegativeCommission\"></div>"; ?>
    <div class="Line"></div>
    <?php echo "<div id=\"createChannelsAutomatically\"></div>"; ?>
</fieldset>

<fieldset>
    <legend><?php echo smarty_function_localize(array('str' => 'Affiliate linking method'), $this);?>
</legend>
    <?php echo smarty_function_localize(array('str' => 'This will set the style of affiliate link URL that your affiliates will put to their pages. You can choose from the methods below.<br/>Note that some methods have different requirements'), $this);?>

    
    <?php echo "<div id=\"linking_method\" class=\"LinkingMethod\"></div>"; ?>
    
    
    <div class="Line"></div>
    <div class="HintText"><?php echo smarty_function_localize(array('str' => 'You can choose to support DirectLink linking as an addition to your standard affiliate links.<br/>The links chosen above will work, plus your affiliates will have option to use DirectLinks. All affiliate DirectLink URLs require merchant\'s approval.'), $this);?>
</div>
    <div class="Line"></div>
    
    <?php echo "<div id=\"support_direct_linking\" class=\"SupportDirect\"></div>"; ?>
    
    <?php echo "<div id=\"support_short_anchor_linking\"></div>"; ?>
</fieldset>
<?php echo "<div id=\"FormMessage\"></div>"; ?>
</div>
<?php echo "<div id=\"SaveButton\"></div>"; ?>

<div class="clear"></div>