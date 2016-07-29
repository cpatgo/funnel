<?php /* Smarty version 2.6.18, created on 2016-07-06 14:14:35
         compiled from logging_tab.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'logging_tab.tpl', 6, false),)), $this); ?>
<!-- logging_tab -->
<div class="TabDescription">

<div class="Log">
<fieldset>
<legend><?php echo smarty_function_localize(array('str' => 'Log level'), $this);?>
</legend>
<?php echo "<div id=\"log_level\"></div>"; ?>
</fieldset>
</div>

<div class="Debug">
<fieldset>
<legend><?php echo smarty_function_localize(array('str' => 'Debug'), $this);?>
</legend>
<?php echo smarty_function_localize(array('str' => 'Debugging can be used for troubleshooting. You can choose actions where the system will use INFORMATION log level with debug messages. Otherwise is used general log level for all actions. You can use these messages to investigate the flow of commands, and to find out what is wrong. This way you can check what are the scripts doing and where exactly they fail. In production it should be turned off, because it generates multiple history records for each transaction and slows down the system.'), $this);?>

<?php echo "<div id=\"aditionalDescription\"></div>"; ?>
<?php echo "<div id=\"panelDebugSpecialSettings\"></div>"; ?>
</fieldset>
</div>

<fieldset>
    <legend><?php echo smarty_function_localize(array('str' => 'Delete old events'), $this);?>
</legend>
    <div class="Inliner"><div class="Label"><?php echo smarty_function_localize(array('str' => 'Delete event records older than'), $this);?>
</div></div>
    <div class="FormFieldSmallInline"><?php echo "<div id=\"deleteeventdays\"></div>"; ?></div><div class="Inliner"><?php echo smarty_function_localize(array('str' => 'days'), $this);?>
</div>
    <div class="Inliner"><?php echo "<div id=\"helpAutoDeleteEvents\"></div>"; ?></div>
    <div class="clear"></div>
    <div class="Inliner"><div class="Label"><?php echo smarty_function_localize(array('str' => 'Truncate all events if there are more than '), $this);?>
</div></div>
    <div class="FormFieldSmallInline"><?php echo "<div id=\"deleteeventrecords\"></div>"; ?></div><div class="Inliner"><?php echo smarty_function_localize(array('str' => 'records in logs table'), $this);?>
</div>
    <div class="Inliner"><?php echo "<div id=\"helpAutoDeleteEventsMaxRecordsCount\"></div>"; ?></div>
    <div class="clear"></div>
</fieldset>

<?php echo "<div id=\"panelLoginsHistorySettings\"></div>"; ?>

<?php echo "<div id=\"SaveButton\"></div>"; ?>
</div>
<div class="clear"></div>