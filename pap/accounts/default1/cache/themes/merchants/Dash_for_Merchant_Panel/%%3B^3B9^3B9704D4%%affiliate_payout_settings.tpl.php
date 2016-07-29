<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:46
         compiled from affiliate_payout_settings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'affiliate_payout_settings.tpl', 5, false),)), $this); ?>
<!-- affiliate_payout_settings -->
<table>
<tr><td valign="top">
  <div class="FormFieldset clear_margin_left">
    <div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'Payout method and data'), $this);?>
</div>
		<div class="pad_bottom">
		    <?php echo "<div id=\"payoutoptionid\"></div>"; ?>
		    <?php echo "<div id=\"payoutOptions\" class=\"PayoutOptions\"></div>"; ?>
		</div>
    <div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'Payout balances'), $this);?>
</div>
		<div class="pad_bottom">
            <?php echo "<div id=\"minimumPayoutOptions\"></div>"; ?>
            <?php echo "<div id=\"minimumpayout\"></div>"; ?>
		</div>
    <div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'Invoicing options'), $this);?>
</div>
		    <?php echo "<div id=\"invoicingNotSupported\"></div>"; ?>
		    <?php echo "<div id=\"applyVatInvoicing\"></div>"; ?>
		    <?php echo "<div id=\"vatPercentage\"></div>"; ?>
		    <?php echo "<div id=\"vatNumber\"></div>"; ?>
		    <?php echo "<div id=\"amountOfRegCapital\"></div>"; ?>
		    <?php echo "<div id=\"regNumber\"></div>"; ?>
  </div>
	</td><td valign="top">
    <div class="FormFieldset">
    <div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'Payout history'), $this);?>
</div>
       <?php echo "<div id=\"PayoutHistory\"></div>"; ?>
    </div>
	</td></tr>
</table>
<?php echo "<div id=\"FormMessage\"></div>"; ?>
<?php echo "<div id=\"SaveButton\"></div>"; ?>
<div class="clear"></div>