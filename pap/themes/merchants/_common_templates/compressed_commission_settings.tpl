<!--	compressed_commission_settings		-->


{widget id="PlacementOverviewGrid"}

<fieldset>
    <legend>##General settings##</legend>
    {widget id="processing"}
    {widget id="recurrence"}
    {widget id="recurrenceDay"}
    {widget id="compressedCommissionAddTransactions"}
</fieldset>

{widget id="ruleConditions"}

<fieldset>
    <legend>##Action with transactions of affiliates, who didn't achieve conditions##</legend>
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
</fieldset>

{widget id="formmessage"}
{widget id="sendButton"}
{widget id="cancelButton"}
{widget id="placementOverviewButton"}

