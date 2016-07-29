<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:46
         compiled from affilate_advanced_filter.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'affilate_advanced_filter.tpl', 5, false),)), $this); ?>
<!-- affilate_advanced_filter -->

<div class="AffiliatesFilter">
	<div class="FilterRow">       
		<div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Date joined'), $this);?>
</div>
		<?php echo "<div id=\"dateinserted\"></div>"; ?>
		<div class="clear"></div>
	</div>
        
	<div class="FilterRow">       
		<div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Affiliate status'), $this);?>
</div>
		<?php echo "<div id=\"rstatus\"></div>"; ?>
		<div class="clear"></div>
	</div>

	<div class="FilterRow">       
		<div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Custom filter'), $this);?>
</div>
		<?php echo "<div id=\"custom\"></div>"; ?>
		<div class="clear"></div>
	</div>
</div>
<div style="clear: both;"></div>