<?php /* Smarty version 2.6.18, created on 2016-07-06 14:14:58
         compiled from payout_options_form.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'payout_options_form.tpl', 5, false),)), $this); ?>
<!-- payout_options_form -->

<div class="PayoutsOptionsForm">
    <fieldset>
        <legend><?php echo smarty_function_localize(array('str' => 'Default payout method for affiliates'), $this);?>
</legend>
        <div class="FormField">
            <div class="Label Inliner"><?php echo smarty_function_localize(array('str' => 'Default payout method'), $this);?>
</div>
            <div class="FormFieldInputContainer"><div class="FormFieldInput"><?php echo "<div id=\"defaultPayoutMethod\"></div>"; ?></div></div>
        </div>
        <div class="FormField" style="">
            <div class="CheckBoxInput"><?php echo "<div id=\"allowEditInAffiliatePanel\"></div>"; ?></div>
            <div class="CheckBoxLabelPart"><div class="CheckBoxLabel"><div class="Label" style=""><?php echo smarty_function_localize(array('str' => 'Allow edit payout options in affiliate panel (otherwise payout options are readonly for affiliates, include minimum payout option)'), $this);?>
</div></div></div>
            <div class="clear"></div>
        </div>
    </fieldset>
</div>

<?php echo "<div id=\"PayoutOptionsGrid\"></div>"; ?>
<?php echo "<div id=\"saveButton\"></div>"; ?>
<div class="clear"></div>