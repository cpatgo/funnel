<?php /* Smarty version 2.6.18, created on 2016-07-06 14:13:36
         compiled from campaign_filter.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'campaign_filter.tpl', 5, false),)), $this); ?>
<!-- campaign_filter -->
    
<div class="CampaignsFilter">
    <fieldset class="Filter">
    <legend><?php echo smarty_function_localize(array('str' => 'Date created'), $this);?>
</legend>
    <div class="Resize">
        <?php echo "<div id=\"dateinserted\"></div>"; ?>
    </div>
    </fieldset>
</div>
<div class="CampaignsFilter">
    <fieldset class="Filter">
    <legend><?php echo smarty_function_localize(array('str' => 'Status'), $this);?>
</legend>
    <div class="Resize">
        <?php echo "<div id=\"rstatus\"></div>"; ?>
    </div>
    </fieldset>
</div>
<div class="CampaignsFilter">
    <fieldset class="Filter">
    <legend><?php echo smarty_function_localize(array('str' => 'Custom'), $this);?>
</legend>
    <div class="Resize">
        <?php echo "<div id=\"custom\"></div>"; ?>
    </div>
    </fieldset>
</div>

<div style="clear: both;"></div>