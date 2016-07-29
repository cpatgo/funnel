<?php /* Smarty version 2.6.18, created on 2016-07-06 11:31:20
         compiled from transaction_list_filter_base.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'transaction_list_filter_base.tpl', 6, false),)), $this); ?>
<!-- transaction_list_filter_base -->

<div class="TransactionsFilter">

	<div class="FilterRow">
		<div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Campaign'), $this);?>
</div>
		<?php echo "<div id=\"campaignid\"></div>"; ?> 
		<div class="clear"></div>
	</div>

	<div class="FilterRow">
		<div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Date created'), $this);?>
</div>
		<?php echo "<div id=\"dateinserted\"></div>"; ?>
		<div class="clear"></div>
	</div>

	<div class="FilterRow">
		<div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Order ID'), $this);?>
</div>
       		<?php echo "<div id=\"orderId\"></div>"; ?>
		<?php echo smarty_function_localize(array('str' => 'You can input multiple order IDs separated either by new line or comma, use \'%\' for like search'), $this);?>

		<div class="clear"></div>
	</div>

	<div class="FilterRow">
		<div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Status'), $this);?>
</div>
		<?php echo "<div id=\"rstatus\"></div>"; ?>
		<div class="clear"></div>
	</div>

	<div class="FilterRow">
        <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Type'), $this);?>
</div>
        <?php echo "<div id=\"rtype\"></div>"; ?>
        <div class="clear"></div>
    </div>

    <div class="FilterRow">       
        <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Tracked by'), $this);?>
</div>
        <?php echo "<div id=\"trackmethod\"></div>"; ?>
        <div class="clear"></div>
    </div>

    <div class="FilterRow">
        <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Payout status'), $this);?>
</div>
        <?php echo "<div id=\"payoutstatus\"></div>"; ?>
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