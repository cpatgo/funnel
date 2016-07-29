<?php /* Smarty version 2.6.18, created on 2016-07-06 14:14:46
         compiled from mass_pay_export.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'mass_pay_export.tpl', 4, false),)), $this); ?>
<!-- mass_pay_export -->

<fieldset>
<legend><?php echo smarty_function_localize(array('str' => 'MassPay export files'), $this);?>
</legend>
<?php echo smarty_function_localize(array('str' => 'Here you can download export files for all payouts grouped by payout option.'), $this);?>

<?php echo "<div id=\"filesList\" class=\"ExportFilesList\"></div>"; ?>
</fieldset>

<div style="clear: both;"></div>
<?php echo "<div id=\"sendButton\"></div>"; ?>