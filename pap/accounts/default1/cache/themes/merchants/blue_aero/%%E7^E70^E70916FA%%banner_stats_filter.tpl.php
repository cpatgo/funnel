<?php /* Smarty version 2.6.18, created on 2016-07-06 14:13:25
         compiled from banner_stats_filter.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'banner_stats_filter.tpl', 4, false),)), $this); ?>
<!-- banner_stats_filter -->
<div class="BannersFilter">
    <fieldset class="Filter">
        <legend><?php echo smarty_function_localize(array('str' => 'Campaign'), $this);?>
</legend>
        <div class="Resize">
	       <?php echo "<div id=\"campaignid\" class=\"CampaignId\"></div>"; ?>
	   </div>
    </fieldset>

    <fieldset class="Filter">
        <legend><?php echo smarty_function_localize(array('str' => 'Banner type'), $this);?>
</legend>
        <div class="Resize">
            <?php echo "<div id=\"type\"></div>"; ?>
        </div>
    </fieldset>

    <fieldset class="Filter">
        <legend><?php echo smarty_function_localize(array('str' => 'Target url'), $this);?>
</legend>
        <div class="Resize">
            <?php echo "<div id=\"destinationurl\" class=\"TargetUrlFilter\"></div>"; ?>
       </div>
    </fieldset>

    <fieldset class="Filter FilterCampaignStatus">
        <legend><?php echo smarty_function_localize(array('str' => 'Hidden banners'), $this);?>
</legend>
        <div class="Resize">
            <?php echo "<div id=\"rstatus\"></div>"; ?>
            <div class="CheckBoxContainer"><div class="Label"><div class="gwt-Label"><?php echo smarty_function_localize(array('str' => 'Show hidden banners'), $this);?>
</div></div></div>
        </div>
    </fieldset>

    <fieldset class="Filter FilterCampaignStatus">
        <legend><?php echo smarty_function_localize(array('str' => 'Hide banners'), $this);?>
</legend>
        <div class="Resize">
            <?php echo "<div id=\"campaignstatus\"></div>"; ?>
        </div>
    </fieldset>

    <fieldset class="Filter FilterDate">
        <legend><?php echo smarty_function_localize(array('str' => 'Statistics date range'), $this);?>
</legend>
        <div class="Resize">
            <?php echo "<div id=\"date\"></div>"; ?>
        </div>
    </fieldset>

    <fieldset class="Filter">
        <legend><?php echo smarty_function_localize(array('str' => 'Transaction status'), $this);?>
</legend>
        <div class="Resize">
            <?php echo "<div id=\"transactionstatus\"></div>"; ?>
        </div>
    </fieldset>
</div>

<div style="clear: both;"></div>