<!-- banner_filter -->
<div class="BannersFilter">
	<div class="FilterRow FilterRowOdd">
		<div class="FilterLegend">##Campaign##</div>
       		{widget id="campaignid" class="CampaignId"}
		<div class="clear"></div>
	</div>

	<div class="FilterRow">
		<div class="FilterLegend">##Banner type##</div>
		{widget id="type"}
		<div class="clear"></div>
	</div>

	<div class="FilterRow FilterRowOdd">           
		<div class="FilterLegend">##Target url##</div>
		{widget id="destinationurl" class="TargetUrlFilter"}
		<div class="clear"></div>
	</div>

	<div class="FilterRow FilterCampaignStatus">
		<div class="FilterLegend">##Hidden banners##</div>
		{widget id="rstatus" class="FilterHidden"}
		<div class="CheckBoxContainer"><div class="Label"><div class="gwt-Label">##Show hidden banners##</div></div></div>
		<div class="clear"></div>
	</div>
	
	<div class="FilterRow FilterRowOdd FilterCampaignStatus">
        <div class="FilterLegend">##Hide banners##</div>
        {widget id="campaignstatus"}
        <div class="clear"></div>
    </div>

	<div style="clear: both;"></div>

</div>
