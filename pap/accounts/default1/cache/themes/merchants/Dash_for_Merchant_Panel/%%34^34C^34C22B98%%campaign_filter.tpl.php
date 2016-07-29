<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:46
         compiled from campaign_filter.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'campaign_filter.tpl', 4, false),)), $this); ?>
<!-- campaign_filter -->
<div class="CampaignsFilter">  
    <div class="FilterRow">
        <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Date created'), $this);?>
</div>
        <?php echo "<div id=\"dateinserted\"></div>"; ?>
		<div class="clear"></div>
    </div>
    <div class="FilterRow">
        <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Status'), $this);?>
</div>
        <?php echo "<div id=\"rstatus\"></div>"; ?>
        <div class="clear"></div>
    </div>
    <div class="FilterRow">
        <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Custom'), $this);?>
</div>
        <?php echo "<div id=\"custom\"></div>"; ?>
        <div class="clear"></div>
    </div>
</div>
<div style="clear: both;"></div>