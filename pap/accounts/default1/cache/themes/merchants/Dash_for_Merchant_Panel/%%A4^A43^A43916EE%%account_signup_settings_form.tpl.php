<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:46
         compiled from account_signup_settings_form.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'account_signup_settings_form.tpl', 4, false),)), $this); ?>
<!--	account_signup_settings_form	-->
<div class="FormFieldset">
    <div class="FormFieldsetHeader">
        <div class="FormFieldsetHeaderTitle"><?php echo smarty_function_localize(array('str' => 'Default merchant role'), $this);?>
</div>
        <div class="FormFieldsetHeaderDescription"></div>
    </div>
  <?php echo "<div id=\"default_role\"></div>"; ?>
</div>

<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle"><?php echo smarty_function_localize(array('str' => 'Account approval'), $this);?>
</div>
		<div class="FormFieldsetHeaderDescription"></div>
	</div>
  <?php echo "<div id=\"account_approval\"></div>"; ?>
</div>

<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle"><?php echo smarty_function_localize(array('str' => 'After signup'), $this);?>
</div>
		<div class="FormFieldsetHeaderDescription"></div>
	</div>
  <?php echo "<div id=\"account_post_signup_type\" class=\"SignUrl\"></div>"; ?>
</div>

<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle"><?php echo smarty_function_localize(array('str' => 'Default Merchant Agreement'), $this);?>
</div>
		<div class="FormFieldsetHeaderDescription"></div>
	</div>
  <div class="AccountDetailsAgreement">
    <?php echo "<div id=\"forceMerchantAgreementAcceptance\"></div>"; ?>
    <?php echo "<div id=\"merchant_agreement\"></div>"; ?>
  </div>  
</div>

<div class="FormFieldset">
    <div class="FormFieldsetHeader">
        <div class="FormFieldsetHeaderTitle"><?php echo smarty_function_localize(array('str' => 'Referral commission'), $this);?>
</div>
        <div class="FormFieldsetHeaderDescription"><?php echo smarty_function_localize(array('str' => 'With referral commissions you can motivate your current affiliates to recruite new network merchants for you.'), $this);?>
</div>
    </div>
    <?php echo "<div id=\"referralCommissionLogo\"></div>"; ?>
    <?php echo "<div id=\"is_account_referral_commission_enabled\"></div>"; ?>
    <?php echo "<div id=\"account_referral_commission_approval\" class=\"Approval\"></div>"; ?>
    <?php echo "<div id=\"account_referral_commission_value\"></div>"; ?>
</div>

<?php echo "<div id=\"privateCampaignSettings\"></div>"; ?>

<div class="pad_left pad_top">
<?php echo "<div id=\"formMessage\"></div>"; ?>
<?php echo "<div id=\"saveButton\"></div>"; ?>
</div>