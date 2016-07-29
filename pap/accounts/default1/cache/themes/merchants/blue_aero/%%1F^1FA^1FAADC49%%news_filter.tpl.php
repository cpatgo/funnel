<?php /* Smarty version 2.6.18, created on 2016-07-06 14:14:47
         compiled from news_filter.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'news_filter.tpl', 5, false),)), $this); ?>
<!-- news_filter -->
    
<div class="NewsFilter">
	<fieldset class="Filter">
    <legend><?php echo smarty_function_localize(array('str' => 'Date inserted'), $this);?>
</legend>
    <div class="Resize">
        <?php echo "<div id=\"dateinserted\"></div>"; ?>
    </div>
	</fieldset>
	<fieldset class="Filter">
    <legend><?php echo smarty_function_localize(array('str' => 'Type'), $this);?>
</legend>
    <div class="Resize">
        <?php echo "<div id=\"rtype\"></div>"; ?>
    </div>
    </fieldset>
</div>

<div style="clear: both;"></div>