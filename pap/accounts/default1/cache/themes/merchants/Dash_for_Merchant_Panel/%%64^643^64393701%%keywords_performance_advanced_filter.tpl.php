<?php /* Smarty version 2.6.18, created on 2016-07-05 14:13:09
         compiled from keywords_performance_advanced_filter.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'keywords_performance_advanced_filter.tpl', 4, false),)), $this); ?>
<!-- keywords_performance_advanced_filter -->
<div>
    <div class="FilterRow">
        <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Date range'), $this);?>
</div>
            <?php echo "<div id=\"dateinserted\"></div>"; ?>
        <div class="clear"></div>
    </div>
    <div class="FilterRow">
        <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Transaction Type'), $this);?>
</div>
        <?php echo "<div id=\"rtype\"></div>"; ?>
        <div class="clear"></div>
    </div>
    <div class="FilterRow">
        <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Commission Type'), $this);?>
</div>
        <?php echo "<div id=\"commtypeid\"></div>"; ?>
        <div class="clear"></div>
    </div>
    <div class="FilterRow">
        <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Keyword'), $this);?>
</div>
        <?php echo "<div id=\"keyword_text\"></div>"; ?>
        <div class="clear"></div>
    </div>
 </div>
<div style="clear: both;"></div>