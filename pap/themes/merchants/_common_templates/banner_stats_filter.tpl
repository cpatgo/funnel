<!-- banner_stats_filter -->
<div class="BannersFilter">
    <fieldset class="Filter">
        <legend>##Campaign##</legend>
        <div class="Resize">
	       {widget id="campaignid" class="CampaignId"}
	   </div>
    </fieldset>

    <fieldset class="Filter">
        <legend>##Banner type##</legend>
        <div class="Resize">
            {widget id="type"}
        </div>
    </fieldset>

    <fieldset class="Filter">
        <legend>##Target url##</legend>
        <div class="Resize">
            {widget id="destinationurl" class="TargetUrlFilter"}
       </div>
    </fieldset>

    <fieldset class="Filter FilterCampaignStatus">
        <legend>##Hidden banners##</legend>
        <div class="Resize">
            {widget id="rstatus"}
            <div class="CheckBoxContainer"><div class="Label"><div class="gwt-Label">##Show hidden banners##</div></div></div>
        </div>
    </fieldset>

    <fieldset class="Filter FilterCampaignStatus">
        <legend>##Hide banners##</legend>
        <div class="Resize">
            {widget id="campaignstatus"}
        </div>
    </fieldset>

    <fieldset class="Filter FilterDate">
        <legend>##Statistics date range##</legend>
        <div class="Resize">
            {widget id="date"}
        </div>
    </fieldset>

    <fieldset class="Filter">
        <legend>##Transaction status##</legend>
        <div class="Resize">
            {widget id="transactionstatus"}
        </div>
    </fieldset>
</div>

<div style="clear: both;"></div>
