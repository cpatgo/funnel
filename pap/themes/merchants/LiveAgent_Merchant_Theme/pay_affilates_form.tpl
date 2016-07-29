<!-- pay_affilates_form -->

<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##Notes about this payment##</div>
		<div class="FormFieldsetHeaderDescription"></div>
	</div>
		<div class="Inliner">##Merchant note##</div>
	<div class="clear"></div>
		<div class="PayAffiliatesTextArea">{widget id="paymentNote"}</div>
	<div class="clear"></div>
		<div class="Inliner">##Affiliate note (visible to affiliate)##</div>
	<div class="clear"></div>
		<div class="PayAffiliatesTextArea">{widget id="affiliateNote"}</div>
	<div class="clear"></div>
</div>

<div class="FormFieldset">
    <div class="FormFieldsetHeader">
        <div class="FormFieldsetHeaderTitle">##Invoice date variables##</div>
        <div class="FormFieldsetHeaderDescription"></div>
    </div>
        <div class="Inliner">##Date from##</div>
    <div class="clear"></div>
        {widget id="dateFrom"}
    <div class="clear"></div>
        <div class="Inliner">##Date to##</div>
    <div class="clear"></div>
        {widget id="dateTo"}
    <div class="clear"></div>
</div>

<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##Payout information email##</div>
		<div class="FormFieldsetHeaderDescription"></div>
	</div>
	{widget id="send_payment_to_affiliate"}
	{widget id="send_generated_invoices_to_merchant"}
	{widget id="send_generated_invoices_to_affiliates"}
</div>

<div class="FormFieldset">
    <div class="FormFieldsetHeader">
        <div class="FormFieldsetHeaderTitle">##MassPay export files##</div>
        <div class="FormFieldsetHeaderDescription">##MassPay export files is moved to Payouts Overview / Reports > Payouts history##</div>
    </div>
</div>

{widget id="sendButton"}
<div class="clear"></div>
