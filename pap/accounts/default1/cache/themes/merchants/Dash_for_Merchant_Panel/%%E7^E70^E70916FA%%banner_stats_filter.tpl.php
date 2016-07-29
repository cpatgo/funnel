<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:58
         compiled from banner_stats_filter.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'banner_stats_filter.tpl', 4, false),)), $this); ?>
<!-- banner_stats_filter -->
<div class="BannersFilter">
    <div class="FilterRow FilterRowOdd">
        <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Campaign'), $this);?>
</div>
        <?php echo "<div id=\"campaignid\" class=\"CampaignId\"></div>"; ?>
        <div class="clear"></div>
    </div>

    <div class="FilterRow">
        <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Banner type'), $this);?>
</div>
        <?php echo "<div id=\"type\"></div>"; ?>
        <div class="clear"></div>
    </div>

    <div class="FilterRow FilterRowOdd">
        <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Target url'), $this);?>
</div>
        <?php echo "<div id=\"destinationurl\" class=\"TargetUrlFilter\"></div>"; ?>
        <div class="clear"></div>
    </div>

    <div class="FilterRow FilterCampaignStatus">
        <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Hidden banners'), $this);?>
</div>
        <?php echo "<div id=\"rstatus\" class=\"FilterHidden\"></div>"; ?>
        <div class="CheckBoxContainer"><div class="Label"><div class="gwt-Label"><?php echo smarty_function_localize(array('str' => 'Show hidden banners'), $this);?>
</div></div></div>
        <div class="clear"></div>
    </div>

    <div class="FilterRow FilterRowOdd FilterCampaignStatus">
        <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Hide banners'), $this);?>
</div>
        <?php echo "<div id=\"campaignstatus\"></div>"; ?>
        <div class="clear"></div>
    </div>

    <div class="FilterRow">
        <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Statistics date range'), $this);?>
</div>
        <?php echo "<div id=\"date\"></div>"; ?>
        <div class="clear"></div>
    </div>

    <div class="FilterRow">
        <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Transaction status'), $this);?>
</div>
        <?php echo "<div id=\"transactionstatus\"></div>"; ?>
        <div class="clear"></div>
    </div>

    <div style="clear: both;"></div>

</div>