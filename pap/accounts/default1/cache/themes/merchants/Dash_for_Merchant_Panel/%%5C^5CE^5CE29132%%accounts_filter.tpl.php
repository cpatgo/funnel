<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:46
         compiled from accounts_filter.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'accounts_filter.tpl', 4, false),)), $this); ?>
<!--	accounts_filter		-->

<div class="FilterRow">
    <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Account ballance till'), $this);?>
</div>
   <?php echo "<div id=\"datetime\"></div>"; ?>
   <div class="clear"></div>
</div>

<div class="FilterRow">
    <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Account status'), $this);?>
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

<div class="FilterRow">
    <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Statistics date range'), $this);?>
</div>
   <?php echo "<div id=\"statsdaterange\"></div>"; ?>
   <div class="clear"></div>
</div>