<!-- banner_filter -->
<div class="BannersFilter">
	<div class="FilterRow">
		<div class="FilterLegend">##Campaign##</div>
		    {widget id="campaignid" class="FilterCampaign"}
		<div class="clear"></div>
	</div>

	<div class="FilterRow FilterRowOdd">
		<div class="FilterLegend">##Channel##</div>
       		{widget id="channel" class="FilterCampaign"}
		<div class="clear"></div>
	</div>
	
	<div class="FilterRow">
        <div class="FilterLegend">##Target url##</div>
        {widget id="destinationurl" class="FilterCampaign"}
        <div class="clear"></div>
    </div>
    
    <div class="FilterRow FilterRowOdd">
        <div class="FilterLegend">##Banner type##</div>
        {widget id="type"}
        <div class="clear"></div>
    </div>

	<div class="FilterRow FilterAdditionalData">
		<div class="FilterLegend">##Additional data##</div>
		{widget id="displaystats"}
		<div class="FilterLegend">##For date range##</div>
		{widget id="statsdate"}
		<div class="FilterLegend"></div>
        {widget id="show_with_stats_only"}
		<div class="clear"></div>
	</div>

	<div class="FilterRow FilterRowOdd">           
		<div class="FilterLegend">##Banner size##</div>
		{widget id="bannerSize"}
		<div class="clear"></div>
	</div>
            	
	<div class="FilterRow">
		<div class="FilterLegend">##Custom##</div>
		{widget id="custom"}
		<div class="clear"></div>
	</div>
                 
	<div style="clear: both;"></div>

</div>    
    
