<!--	account_signup_settings_form	-->

<fieldset>
    <legend>##Default merchant role##</legend>
    {widget id="default_role"}
</fieldset>

<fieldset>
	<legend>##Account approval##</legend>
	{widget id="account_approval"}
</fieldset>

<fieldset>
	<legend>##After signup##</legend>
	{widget id="account_post_signup_type" class="SignUrl"}	
</fieldset>

<fieldset>
    <legend>##Default Merchant Agreement##</legend>
    <div class="AccountDetailsAgreement">
    {widget id="forceMerchantAgreementAcceptance"}
    {widget id="merchant_agreement"}
    </div>  
</fieldset>

<fieldset>
    <legend>##Referral commission##</legend>
    {widget id="referralCommissionLogo"}
    <div>##With referral commissions you can motivate your current affiliates to recruite new network merchants for you.##</div>
    {widget id="is_account_referral_commission_enabled"}
    {widget id="account_referral_commission_approval" class="Approval"}
    {widget id="account_referral_commission_value"}
</fieldset>

{widget id="privateCampaignSettings"}

{widget id="formMessage"}
{widget id="saveButton"}
