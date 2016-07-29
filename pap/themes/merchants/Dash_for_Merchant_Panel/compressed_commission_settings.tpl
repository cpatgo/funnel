<!--	compressed_commission_settings		-->


{widget id="PlacementOverviewGrid"}

<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##General settings##</div>
	</div>
    {widget id="processing"}
    {widget id="recurrence"}
    {widget id="recurrenceDay"}
    {widget id="compressedCommissionAddTransactions"}
</div>

{widget id="ruleConditions"}

<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##Action with transactions of affiliates, who didn't achieve conditions##</div>
	</div>
	{widget id="action"}
	{widget id="advancedActionFilterButton"}
    {widget id="actionDataConditionLabel"}
    <table>
        <tr>
            <td>{widget id="actionDataField" class="ConditionListBox"}</td>
            <td>{widget id="actionDataFieldEquation" class="ConditionListBox"}</td>
            <td>{widget id="actionDataFieldValue"}</td>
        </tr>
    </table>
</div>
<div class="pad_left pad_top">
	{widget id="formmessage"}
	{widget id="sendButton"}
	{widget id="cancelButton"}
	{widget id="placementOverviewButton"}
</div>
