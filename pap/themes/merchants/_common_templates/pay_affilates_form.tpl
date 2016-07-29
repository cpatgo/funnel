<!-- pay_affilates_form -->

<fieldset>
<legend>##Notes about this payment##</legend>
		<div class="Inliner">##Merchant note##</div>
	<div class="clear"></div>
		<div class="PayAffiliatesTextArea">{widget id="paymentNote"}</div>
	<div class="clear"></div>
		<div class="Inliner">##Affiliate note (visible to affiliate)##</div>
	<div class="clear"></div>
		<div class="PayAffiliatesTextArea">{widget id="affiliateNote"}</div>
	<div class="clear"></div>
</fieldset>

<fieldset>
<legend>##Invoice date variables##</legend>
        <div class="Inliner">##Date from##</div>
    <div class="clear"></div>
        {widget id="dateFrom"}
    <div class="clear"></div>
        <div class="Inliner">##Date to##</div>
    <div class="clear"></div>
        {widget id="dateTo"}
    <div class="clear"></div>
</fieldset>

<fieldset>
<legend>##Payout information email##</legend>
	{widget id="send_payment_to_affiliate"}
	{widget id="send_generated_invoices_to_merchant"}
	{widget id="send_generated_invoices_to_affiliates"}
</fieldset>

<fieldset>
<legend>##MassPay export files##</legend>
##MassPay export files is moved to Payouts Overview / Reports > Payouts history##
</fieldset>

<div style="clear: both;"></div>
{widget id="sendButton"}
