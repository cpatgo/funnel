<!--    payout_fields_panel     -->

<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##Payout method and data##</div>
		<div class="FormFieldsetHeaderDescription"></div>
	</div>
  {widget id="payoutoptionid" class="PayoutOptionId"}
  {widget id="payoutOptions"}
</div>

<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##Payout balances##</div>
		<div class="FormFieldsetHeaderDescription"></div>
	</div>
	{widget id="minimumPayoutOptions"}
	{widget id="minimumpayout"}
	<div class="clear"></div>


</div>

<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##Invoicing options##</div>
		<div class="FormFieldsetHeaderDescription"></div>
	</div>
	{widget id="invoicingNotSupported"}
	{widget id="applyVatInvoicing"}
	{widget id="vatPercentage"}
	{widget id="vatNumber"}
	{widget id="amountOfRegCapital"}
	{widget id="regNumber"}
</div>
