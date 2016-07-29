<!-- payout_options_form -->

<div class="PayoutsOptionsForm">
    <fieldset>
        <legend>##Default payout method for affiliates##</legend>
        <div class="FormField">
            <div class="Label Inliner">##Default payout method##</div>
            <div class="FormFieldInputContainer"><div class="FormFieldInput">{widget id="defaultPayoutMethod"}</div></div>
        </div>
        <div class="FormField" style="">
            <div class="CheckBoxInput">{widget id="allowEditInAffiliatePanel"}</div>
            <div class="CheckBoxLabelPart"><div class="CheckBoxLabel"><div class="Label" style="">##Allow edit payout options in affiliate panel (otherwise payout options are readonly for affiliates, include minimum payout option)##</div></div></div>
            <div class="clear"></div>
        </div>
    </fieldset>
</div>

{widget id="PayoutOptionsGrid"}
{widget id="saveButton"}
<div class="clear"></div>
