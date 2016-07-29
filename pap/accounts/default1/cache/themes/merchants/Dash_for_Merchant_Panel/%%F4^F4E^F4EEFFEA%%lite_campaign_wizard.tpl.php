<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:59
         compiled from lite_campaign_wizard.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'lite_campaign_wizard.tpl', 3, false),)), $this); ?>
<!-- lite_campaign_wizard -->
<div class="pad_top pad_left">
<?php echo smarty_function_localize(array('str' => 'Campaign wizard will help you create new campaign'), $this);?>

</div>
<?php echo "<div id=\"CampaignShortDetails\"></div>"; ?>
<div class="pad_left">
<?php echo "<div id=\"TabPanel\"></div>"; ?>

<?php echo "<div id=\"PreviousButton\"></div>"; ?> <?php echo "<div id=\"NextButton\"></div>"; ?>
</div>