<?php /* Smarty version 2.6.18, created on 2016-07-05 14:13:09
         compiled from invoices_filter.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'invoices_filter.tpl', 5, false),)), $this); ?>
<!-- invoices_filter -->

<div class="LicensesFilter">
  <div class="FilterRow">
      <div class="FilterLegend"><?php echo smarty_function_localize(array('str' => 'Custom'), $this);?>
</div>
     <?php echo "<div id=\"custom\"></div>"; ?>
     <div class="clear"></div>
  </div>
</div>
        