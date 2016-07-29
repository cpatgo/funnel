<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:58
         compiled from clicks_list_filter_base.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'clicks_list_filter_base.tpl', 6, false),)), $this); ?>
<!-- clicks_list_filter_base -->

<div class="TransactionsRowFilter">
					
	<div class="FilterRow">       
		<div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Campaign'), $this);?>
</div>
		<?php echo "<div id=\"campaignid\"></div>"; ?>
		<div class="clear"></div>
	</div>                    		          		                    
        
	<div class="FilterRow">       
		<div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Date'), $this);?>
</div>
	        <?php echo "<div id=\"datetime\"></div>"; ?>
		<div class="clear"></div>
	</div>  

	<div class="FilterRow">       
		<div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Type'), $this);?>
</div>
	        <?php echo "<div id=\"rtype\"></div>"; ?>
		<div class="clear"></div>
	</div>  

	<div class="FilterRow">       
		<div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Custom'), $this);?>
</div>
	        <?php echo "<div id=\"custom\"></div>"; ?>
		<div class="clear"></div>
	</div>  
        
</div>
<div class="clear"></div>
    