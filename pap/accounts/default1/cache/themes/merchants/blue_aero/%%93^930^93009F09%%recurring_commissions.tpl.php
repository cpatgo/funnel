<?php /* Smarty version 2.6.18, created on 2016-07-06 14:15:09
         compiled from recurring_commissions.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'recurring_commissions.tpl', 3, false),)), $this); ?>
<!-- recurring_commissions -->
<fieldset>
<legend><?php echo smarty_function_localize(array('str' => 'Recurring Commissions'), $this);?>
</legend>
<?php echo "<div id=\"RecurrenceType\" class=\"RecurrenceType\"></div>"; ?>
<?php echo "<div id=\"recurrenceAfterDays\"></div>"; ?>
<?php echo "<div id=\"numberOfRecurrence\"></div>"; ?>
<?php echo "<div id=\"RecurringCommissionsPanel\"></div>"; ?>
</fieldset>