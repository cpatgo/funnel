<?php /* Smarty version 2.6.18, created on 2016-07-06 14:14:58
         compiled from payouts_balance_form.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'payouts_balance_form.tpl', 4, false),)), $this); ?>
<!-- payouts_balance_form -->
<div class="PayoutsBalanceForm">
    <fieldset>
        <div class="HintText"><?php echo smarty_function_localize(array('str' => 'Apply Default payout balance for existing affiliates:'), $this);?>
</div>
        <div class="Line"></div>
        <?php echo "<div id=\"applyToAffiliates\"></div>"; ?>
    </fieldset>
</div>

<?php echo "<div id=\"submitButton\"></div>"; ?>
<?php echo "<div id=\"cancelButton\"></div>"; ?>
<div class="clear"></div>