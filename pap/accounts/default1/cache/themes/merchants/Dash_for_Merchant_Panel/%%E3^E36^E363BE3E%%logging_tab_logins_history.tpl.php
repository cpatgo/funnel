<?php /* Smarty version 2.6.18, created on 2016-07-05 14:13:09
         compiled from logging_tab_logins_history.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'logging_tab_logins_history.tpl', 5, false),)), $this); ?>
<!-- logging_tab_logins_history -->

<div class="FormFieldset">
  <div class="FormFieldsetHeader">
      <div class="FormFieldsetHeaderTitle"><?php echo smarty_function_localize(array('str' => 'Delete logins history'), $this);?>
</div>
      <div class="FormFieldsetHeaderDescription"></div>
  </div>
  <div class="Inliner"><div class="Label"><?php echo smarty_function_localize(array('str' => 'Delete logins history older than'), $this);?>
</div></div>
  <div class="FormFieldSmallInline"><?php echo "<div id=\"deleteloginshistorydays\"></div>"; ?></div><div class="Inliner"><?php echo smarty_function_localize(array('str' => 'days'), $this);?>
</div>
  <div class="Inliner"><?php echo "<div id=\"helpAutoDeleteLoginsHistory\"></div>"; ?></div>
  <div class="clear"></div>
  <div class="FormFieldDescription"><?php echo smarty_function_localize(array('str' => 'Does not affect Last login date and Logins count'), $this);?>
</div>
</div>