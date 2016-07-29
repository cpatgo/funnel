<!-- financial_overview -->
<div class="Dash_FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##FinancialOverview##</div>
		<div class="FormFieldsetHeaderDescription">##FinancialOverviewDescription##</div>
	</div>
	##You have## <strong>{widget id="approvedCommissions"}</strong>  ##approved unpaid commissions##
	<br />
	##and## <strong>{widget id="pendingCommissions"}</strong>  ##commissions waiting for approval by merchant##
	<br />
	<br />
	{widget id="paymentRequestButton"}
    {widget id="requestSentLabel"}
    <br />
    <div class="InlineBlock">
        {widget id="noMethodErrorMessage"}
    </div>
    {widget id="paymentDetailsLink"}
</div>
