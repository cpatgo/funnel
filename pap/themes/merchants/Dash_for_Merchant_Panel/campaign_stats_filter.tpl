<!-- campaign_stats_filter -->
<div class="CampaignsFilter">  
    <div class="FilterRow">
        <div class="FilterLegend">##Date created##</div>
        {widget id="dateinserted"}
		<div class="clear"></div>
    </div>
    <div class="FilterRow">
        <div class="FilterLegend">##Status##</div>
        {widget id="rstatus"}
        <div class="clear"></div>
    </div>
    {widget id="userid"}
    <div class="FilterRow">
        <div class="FilterLegend">##Statistics date range##</div>
        <div class="Resize">
            {widget id="statsdaterange"}
        </div>
    </div>
    <div class="FilterRow">
        <div class="FilterLegend">##Transaction status##</div>
        <div class="Resize">
            {widget id="transactionstatus"}
        </div>
    </div>
    <div class="FilterRow">
        <div class="FilterLegend">##Custom##</div>
        {widget id="custom"}
        <div class="clear"></div>
    </div>
</div>
<div style="clear: both;"></div>
