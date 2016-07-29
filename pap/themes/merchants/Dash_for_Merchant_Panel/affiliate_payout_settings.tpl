<!-- affiliate_payout_settings -->
<table>
<tr><td valign="top">
  <div class="FormFieldset clear_margin_left">
    <div class="FormFieldsetSectionTitle">##Payout method and data##</div>
		<div class="pad_bottom">
		    {widget id="payoutoptionid"}
		    {widget id="payoutOptions" class="PayoutOptions"}
		</div>
    <div class="FormFieldsetSectionTitle">##Payout balances##</div>
		<div class="pad_bottom">
            {widget id="minimumPayoutOptions"}
            {widget id="minimumpayout"}
		</div>
    <div class="FormFieldsetSectionTitle">##Invoicing options##</div>
		    {widget id="invoicingNotSupported"}
		    {widget id="applyVatInvoicing"}
		    {widget id="vatPercentage"}
		    {widget id="vatNumber"}
		    {widget id="amountOfRegCapital"}
		    {widget id="regNumber"}
  </div>
	</td><td valign="top">
    <div class="FormFieldset">
    <div class="FormFieldsetSectionTitle">##Payout history##</div>
       {widget id="PayoutHistory"}
    </div>
	</td></tr>
</table>
{widget id="FormMessage"}
{widget id="SaveButton"}
<div class="clear"></div>
