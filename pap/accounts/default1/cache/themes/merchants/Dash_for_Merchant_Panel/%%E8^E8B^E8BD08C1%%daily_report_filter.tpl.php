<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:58
         compiled from daily_report_filter.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'daily_report_filter.tpl', 5, false),)), $this); ?>
<!-- daily_report_filter -->

<div>
    <div class="FilterRow">
        <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Date'), $this);?>
</div>
        <?php echo "<div id=\"date\"></div>"; ?>
        <div class="clear"></div>
    </div>
    <div class="FilterRow">
        <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Campaign'), $this);?>
</div>
        <?php echo "<div id=\"campaignid\"></div>"; ?>
        <div class="clear"></div>
    </div>
    <div class="FilterRow">
        <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Status'), $this);?>
</div>
        <?php echo "<div id=\"rstatus\"></div>"; ?>
        <div class="clear"></div>
    </div>
</div>

<div style="clear: both;"></div>