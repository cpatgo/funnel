<?php /* Smarty version 2.6.18, created on 2016-07-06 14:13:01
         compiled from account_fee_form.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'account_fee_form.tpl', 4, false),)), $this); ?>
<!-- account_fee_form -->

<fieldset>
    <legend><?php echo smarty_function_localize(array('str' => 'Fee options'), $this);?>
</legend>
    <?php echo "<div id=\"fixedFeeCheckbox\"></div>"; ?>
    <?php echo "<div id=\"fixedFeeValue\"></div>"; ?>
    <?php echo "<div id=\"percentageCheckBox\"></div>"; ?>
    <?php echo "<div id=\"percentageValue\"></div>"; ?>
</fieldset>
