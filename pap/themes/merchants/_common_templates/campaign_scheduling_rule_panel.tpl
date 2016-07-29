<!--    campaign_scheduling_rule_panel      -->

<div class="ScreenHeader RuleViewHeader">
    <div class="ScreenTitle">
        {widget id="screenTitle"}
    </div>
    <div class="ScreenDescription">
       {widget id="screenDescription"}
    </div>
    <div class="clear"/>
</div>


<fieldset class="EditRuleFieldset">
    <legend>##Actions##</legend>
    <table>
        <tr>
            <td class="EditRuleColumnFirst">##Change status of campaign to##</td>
            <td class="EditRuleColumn">{widget id="status_to"}</td>
        </tr>
    </table>
	{widget id="ruleConditions"}
</fieldset>


{widget id="formmessage"}
{widget id="sendButton"}
{widget id="cancelButton"}
