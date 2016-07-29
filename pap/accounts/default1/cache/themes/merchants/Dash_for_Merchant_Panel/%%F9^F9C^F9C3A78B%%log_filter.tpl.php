<?php /* Smarty version 2.6.18, created on 2016-07-05 14:13:09
         compiled from log_filter.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'log_filter.tpl', 6, false),)), $this); ?>
<!-- log_filter -->
    
<div class="LogFilter">
 		
  <div class="FilterRow">
      <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Date created'), $this);?>
</div>
     <?php echo "<div id=\"created\"></div>"; ?>
     <div class="clear"></div>
  </div>
  
  <div class="FilterRow">
      <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Time'), $this);?>
</div>
     <?php echo "<div id=\"time_created\"></div>"; ?><?php echo "<div id=\"time\"></div>"; ?>
     <div class="clear"></div>
  </div>
  
  <div class="FilterRow">
      <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Log level'), $this);?>
</div>
     <?php echo "<div id=\"level\"></div>"; ?>
     <div class="clear"></div>
  </div>
  
  <div class="FilterRow">
      <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Custom'), $this);?>
</div>
     <?php echo "<div id=\"custom\"></div>"; ?>
     <div class="clear"></div>
  </div>

</div>
        
    