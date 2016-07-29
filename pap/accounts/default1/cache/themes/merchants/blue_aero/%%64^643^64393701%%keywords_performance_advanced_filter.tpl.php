<?php /* Smarty version 2.6.18, created on 2016-07-06 14:14:34
         compiled from keywords_performance_advanced_filter.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'keywords_performance_advanced_filter.tpl', 4, false),)), $this); ?>
<!-- keywords_performance_advanced_filter -->
<div>
    <fieldset class="Filter">
        <legend><?php echo smarty_function_localize(array('str' => 'Date range'), $this);?>
</legend>
        <div class="Resize"><?php echo "<div id=\"dateinserted\"></div>"; ?></div>
    </fieldset>
    <fieldset class="Filter">
        <legend><?php echo smarty_function_localize(array('str' => 'Transaction Type'), $this);?>
</legend>
        <div class="Resize"><?php echo "<div id=\"rtype\"></div>"; ?></div>
    </fieldset>
    <fieldset class="Filter">
        <legend><?php echo smarty_function_localize(array('str' => 'Commission Type'), $this);?>
</legend>
        <div class="Resize"><?php echo "<div id=\"commtypeid\"></div>"; ?></div>
    </fieldset>
    <fieldset class="Filter">
        <legend><?php echo smarty_function_localize(array('str' => 'Keyword'), $this);?>
</legend>
        <div class="Resize"><?php echo "<div id=\"keyword_text\"></div>"; ?></div>
    </fieldset>
 </div>
<div style="clear: both;"></div>