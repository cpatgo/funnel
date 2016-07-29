<!-- financial_overview -->
<div class="OverviewDataBox">
	<div class="OverviewDataBoxContent">
        <div class="OverviewHeader"><strong>##FinancialOverview##</strong></div>
        <div class="OverviewInnerBox">
            ##FinancialOverviewDescription##
            <br /><br />
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
    </div>
</div>
