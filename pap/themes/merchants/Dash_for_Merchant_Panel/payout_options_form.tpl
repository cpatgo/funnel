<!-- payout_options_form -->

<div class="FormFieldset PayoutsOptionsForm">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##Default payout method for affiliates##</div>
		<div class="FormFieldsetHeaderDescription"></div>
	</div>
	<div class="FormField">
        <div class="Label Inliner">##Default payout method##</div>
        <div class="FormFieldInputContainer"><div class="FormFieldInput">{widget id="defaultPayoutMethod"}</div></div>
    </div>
    <div class="FormField" style="">
        <div class="CheckBoxInput">{widget id="allowEditInAffiliatePanel"}</div>
        <div class="CheckBoxLabelPart"><div class="CheckBoxLabel"><div class="Label" style="">##Allow edit payout options in affiliate panel (otherwise payout options are readonly for affiliates, include minimum payout option)##</div></div></div>
        <div class="clear"></div>
    </div>
</div>

<div class="FormFieldset">
	{widget id="PayoutOptionsGrid"}
</div>

<div class="pad_left pad_top">
{widget id="saveButton"}
</div>

<div class="clear"></div>
