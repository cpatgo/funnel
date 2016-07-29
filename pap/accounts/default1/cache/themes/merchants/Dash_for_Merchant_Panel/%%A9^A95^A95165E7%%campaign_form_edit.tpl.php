<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:58
         compiled from campaign_form_edit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'campaign_form_edit.tpl', 4, false),)), $this); ?>
<!-- campaign_form_edit -->

<div class="Details">
<div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'Details'), $this);?>
</div>
        <?php echo "<div id=\"name\"></div>"; ?>
        <?php echo "<div id=\"logourl\" class=\"CampaignLogo\"></div>"; ?>
        <?php echo "<div id=\"description\"></div>"; ?>
        <?php echo "<div id=\"longdescription\"></div>"; ?>
</div>

<div class="CampaignStatus">
<div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'Campaign status'), $this);?>
</div>
        <div class="CampaignFormEdit_CampaignStatus"><?php echo "<div id=\"rstatus\"></div>"; ?></div>
        <div class="clear"></div>
    <?php echo "<div id=\"campaignScheduler\"></div>"; ?>
</div>
<div class="AccountID">
<?php echo "<div id=\"accountid\"></div>"; ?>
</div>
<?php echo "<div id=\"rtype\"></div>"; ?>

<div class="CampaignCookies">
<div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'Cookies'), $this);?>
</div>
        <?php echo "<div id=\"cookielifetime\"></div>"; ?>
        <div class="Line"></div>
        <?php echo "<div id=\"overwritecookie\" class=\"OCookies\"></div>"; ?>
        <?php echo "<div id=\"overwrite_cookie_disabled\" class=\"OCookies\"></div>"; ?>
        <div class="Line"></div>
        <?php echo "<div id=\"delete_cookie\" class=\"OCookies\"></div>"; ?>
</div>

<div class="LinkingMethod">
<div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'Affiliate linking method'), $this);?>
</div>
        <?php echo smarty_function_localize(array('str' => 'You can choose the style of URL links specially for this campaign.'), $this);?>

        <?php echo "<div id=\"linkingmethod\" class=\"LinkingMethod\"></div>"; ?>
</div>

<div class="ProductId">
<div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'Product ID matching'), $this);?>
</div>
        <?php echo "<div id=\"productid\" class=\"CampaignProductId\"></div>"; ?>
</div>

<?php echo "<div id=\"campaignDetailsAdditionalForm\"></div>"; ?>
<?php echo "<div id=\"campaignDetailsFeaturesPlaceholder\"></div>"; ?>

<?php echo "<div id=\"FormMessage\"></div>"; ?><br/>
<?php echo "<div id=\"SaveButton\"></div>"; ?> <?php echo "<div id=\"NextButton\"></div>"; ?>

<div class="clear"></div>