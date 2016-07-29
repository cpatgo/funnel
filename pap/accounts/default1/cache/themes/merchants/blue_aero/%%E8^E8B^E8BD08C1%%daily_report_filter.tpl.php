<?php /* Smarty version 2.6.18, created on 2016-07-06 14:13:58
         compiled from daily_report_filter.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'daily_report_filter.tpl', 4, false),)), $this); ?>
<!-- daily_report_filter -->
<div>
    <fieldset class="Filter">
        <legend><?php echo smarty_function_localize(array('str' => 'Date'), $this);?>
</legend>
        <div class="Resize"><?php echo "<div id=\"date\"></div>"; ?></div>
    </fieldset>
    <fieldset class="Filter">
        <legend><?php echo smarty_function_localize(array('str' => 'Campaign'), $this);?>
</legend>
        <div class="Resize"><?php echo "<div id=\"campaignid\"></div>"; ?></div>
    </fieldset>
    <fieldset class="Filter">
        <legend><?php echo smarty_function_localize(array('str' => 'Status'), $this);?>
</legend>
        <div class="Resize"><?php echo "<div id=\"rstatus\"></div>"; ?></div>
    </fieldset>
</div>
<div style="clear: both;"></div>