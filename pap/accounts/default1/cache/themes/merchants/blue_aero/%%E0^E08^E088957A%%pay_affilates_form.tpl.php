<?php /* Smarty version 2.6.18, created on 2016-07-06 14:14:58
         compiled from pay_affilates_form.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'pay_affilates_form.tpl', 4, false),)), $this); ?>
<!-- pay_affilates_form -->

<fieldset>
<legend><?php echo smarty_function_localize(array('str' => 'Notes about this payment'), $this);?>
</legend>
		<div class="Inliner"><?php echo smarty_function_localize(array('str' => 'Merchant note'), $this);?>
</div>
	<div class="clear"></div>
		<div class="PayAffiliatesTextArea"><?php echo "<div id=\"paymentNote\"></div>"; ?></div>
	<div class="clear"></div>
		<div class="Inliner"><?php echo smarty_function_localize(array('str' => 'Affiliate note (visible to affiliate)'), $this);?>
</div>
	<div class="clear"></div>
		<div class="PayAffiliatesTextArea"><?php echo "<div id=\"affiliateNote\"></div>"; ?></div>
	<div class="clear"></div>
</fieldset>

<fieldset>
<legend><?php echo smarty_function_localize(array('str' => 'Invoice date variables'), $this);?>
</legend>
        <div class="Inliner"><?php echo smarty_function_localize(array('str' => 'Date from'), $this);?>
</div>
    <div class="clear"></div>
        <?php echo "<div id=\"dateFrom\"></div>"; ?>
    <div class="clear"></div>
        <div class="Inliner"><?php echo smarty_function_localize(array('str' => 'Date to'), $this);?>
</div>
    <div class="clear"></div>
        <?php echo "<div id=\"dateTo\"></div>"; ?>
    <div class="clear"></div>
</fieldset>

<fieldset>
<legend><?php echo smarty_function_localize(array('str' => 'Payout information email'), $this);?>
</legend>
	<?php echo "<div id=\"send_payment_to_affiliate\"></div>"; ?>
	<?php echo "<div id=\"send_generated_invoices_to_merchant\"></div>"; ?>
	<?php echo "<div id=\"send_generated_invoices_to_affiliates\"></div>"; ?>
</fieldset>

<fieldset>
<legend><?php echo smarty_function_localize(array('str' => 'MassPay export files'), $this);?>
</legend>
<?php echo smarty_function_localize(array('str' => 'MassPay export files is moved to Payouts Overview / Reports > Payouts history'), $this);?>

</fieldset>

<div style="clear: both;"></div>
<?php echo "<div id=\"sendButton\"></div>"; ?>