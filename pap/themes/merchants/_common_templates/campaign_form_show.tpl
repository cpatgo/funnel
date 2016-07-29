<!-- campaign_form_show -->
<div class="ScreenHeader CampaignViewHeader">
	<div class="CampaignLogo">{widget id="logo"}</div>
	{widget id="RefreshButton"}
	<div class="ScreenTitle">{widget id="name"}</div>
	<div class="ScreenDescription">
        {widget id="description"}
        <br/>
        <div><div class="FloatLeft">##Campaign Id##:&nbsp;</div><div class="FloatLeft"><b>{widget id="campaignid"}</b></div></div>
        <br/>
	    ##Campaign is ## <b>{widget id="rstatus"}</b>
	</div>
	<div class="clear"/>
</div>
