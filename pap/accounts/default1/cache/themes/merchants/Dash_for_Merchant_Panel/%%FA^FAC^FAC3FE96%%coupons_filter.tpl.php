<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:58
         compiled from coupons_filter.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'coupons_filter.tpl', 4, false),)), $this); ?>
<!--    coupons_filter  -->

  <div class="FilterRow">
      <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Status'), $this);?>
</div>
     <?php echo "<div id=\"status\"></div>"; ?>
     <div class="clear"></div>
  </div>
  <div class="FilterRow">
      <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Custom'), $this);?>
</div>
     <?php echo "<div id=\"custom\"></div>"; ?>
     <div class="clear"></div>
  </div>