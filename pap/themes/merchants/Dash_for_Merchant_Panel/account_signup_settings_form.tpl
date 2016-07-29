<!--	account_signup_settings_form	-->
<div class="FormFieldset">
    <div class="FormFieldsetHeader">
        <div class="FormFieldsetHeaderTitle">##Default merchant role##</div>
        <div class="FormFieldsetHeaderDescription"></div>
    </div>
  {widget id="default_role"}
</div>

<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##Account approval##</div>
		<div class="FormFieldsetHeaderDescription"></div>
	</div>
  {widget id="account_approval"}
</div>

<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##After signup##</div>
		<div class="FormFieldsetHeaderDescription"></div>
	</div>
  {widget id="account_post_signup_type" class="SignUrl"}
</div>

<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##Default Merchant Agreement##</div>
		<div class="FormFieldsetHeaderDescription"></div>
	</div>
  <div class="AccountDetailsAgreement">
    {widget id="forceMerchantAgreementAcceptance"}
    {widget id="merchant_agreement"}
  </div>  
</div>

<div class="FormFieldset">
    <div class="FormFieldsetHeader">
        <div class="FormFieldsetHeaderTitle">##Referral commission##</div>
        <div class="FormFieldsetHeaderDescription">##With referral commissions you can motivate your current affiliates to recruite new network merchants for you.##</div>
    </div>
    {widget id="referralCommissionLogo"}
    {widget id="is_account_referral_commission_enabled"}
    {widget id="account_referral_commission_approval" class="Approval"}
    {widget id="account_referral_commission_value"}
</div>

{widget id="privateCampaignSettings"}

<div class="pad_left pad_top">
{widget id="formMessage"}
{widget id="saveButton"}
</div>
