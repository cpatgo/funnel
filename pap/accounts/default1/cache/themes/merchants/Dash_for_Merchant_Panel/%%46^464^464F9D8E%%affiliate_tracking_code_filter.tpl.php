<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:46
         compiled from affiliate_tracking_code_filter.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'affiliate_tracking_code_filter.tpl', 6, false),)), $this); ?>
<!-- affiliate_tracking_code_filter -->
    
<div class="AffiliateTrackingCodeFilter">      			          
           
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

   