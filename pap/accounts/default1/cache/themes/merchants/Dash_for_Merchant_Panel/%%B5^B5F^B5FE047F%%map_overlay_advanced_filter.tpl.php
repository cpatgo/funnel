<?php /* Smarty version 2.6.18, created on 2016-07-05 14:13:09
         compiled from map_overlay_advanced_filter.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'map_overlay_advanced_filter.tpl', 4, false),)), $this); ?>
<!-- map_overlay_advanced_filter -->
<div>
	<div class="FilterRow">       
		<div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Statistics date range'), $this);?>
</div>
		<?php echo "<div id=\"datetime\"></div>"; ?>
		<div class="clear"></div>
	</div>	 

	<div class="FilterRow">       
		<div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Campaign'), $this);?>
</div>
		<?php echo "<div id=\"campaignid\"></div>"; ?>
		<div class="clear"></div>
	</div>	
   <div class="FilterRow">       
        <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Banner'), $this);?>
</div>
        <?php echo "<div id=\"bannerid\"></div>"; ?>
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