<!-- tracking_tab -->
<div class="TrackingSettingsForm">

  <div class="FormFieldset">
  	<div class="FormFieldsetHeader">
  		<div class="FormFieldsetHeaderTitle">##URLs##</div>
  		<div class="FormFieldsetHeaderDescription"></div>
  	</div>
    {widget id="mainSiteUrl" class="MainSiteUrl"}
    <div class="FormField MainSiteUrl">
        <div class="FormFieldLabel"><div class="Inliner"><div class="Label Inliner Label-mandatory">##Declined site URL##</div></div></div>
        <div class="FormFieldBigInline">{widget id="declineSiteUrl"}</div>
        <div class="Inliner">{widget id="checkStoppedCampaigns"}</div>
    </div>
    <div class="clear"></div>
  </div>

  <div class="FormFieldset">
    <div class="FormFieldsetHeader">
        <div class="FormFieldsetHeaderTitle">##CallBack tracking (server side)##</div>
        <div class="FormFieldsetHeaderDescription"></div>
    </div>
    {widget id="salesCallbackUrl" class="MainSiteUrl"}
    {widget id="isCallbackIgnoredForDeclinedCommission"}
  </div>

  <div class="FormFieldset">
  	<div class="FormFieldsetHeader">
  		<div class="FormFieldsetHeaderTitle">##Clicks##</div>
  		<div class="FormFieldsetHeaderDescription"></div>
  	</div>
    <div class="FormField">
        <div class="Inliner"><div class="Label">##Delete click records older than##</div></div>
        <div class="FormFieldSmallInline">{widget id="deleterawclicks"}</div><div class="Inliner"><div class="Label">##days##</div></div>
        <div class="Inliner">{widget id="deleteRawClicksInfo"}</div>
    </div>
    {widget id="deleterawclickshour"}
    <div class="clear"></div>
  </div>
  
  <div class="FormFieldset">
  	<div class="FormFieldsetHeader">
  		<div class="FormFieldsetHeaderTitle">##Statistics data##</div>
  		<div class="FormFieldsetHeaderDescription"></div>
  	</div>
    {widget id="salesStatsProcessorInterval" class="StatsProcessorInterval"}
    {widget id="impressions_hours_stats_max_days" class="StatsProcessorInterval"}
    <div class="clear"></div>
  </div>
  
  <div class="FormFieldset">
  	<div class="FormFieldsetHeader">
  		<div class="FormFieldsetHeaderTitle">##Tracking levels##</div>
  		<div class="FormFieldsetHeaderDescription"></div>
  	</div>
    {widget id="track_by_cookie"}
    {widget id="track_by_htmlstoragecookie"}
    {widget id="track_by_flashcookie"}
    {widget id="track_by_ip_ua"}
    {widget id="track_by_ip"}
    {widget id="ip_validity" class="IpValidity"}
    {widget id="ip_validity_format" class="Validity"}
    <div class="Line"></div>
    {widget id="save_unrefered_sale_lead" class="SaveUnrefered"}
    {widget id="default_affiliate" class="SaveUnrefered UnreferedPadding"}
    {widget id="save_unrefered_click" class="SaveUnrefered UnreferedPadding"}
    {widget id="ignored_referrer_urls_for_nonref_click" class="SaveUnrefered UnreferedClicksPadding"}
    <div class="Line"></div>
    {widget id="declined_affiliate"}
    <div class="Line"></div>
    {widget id="force_choosing_productid"}
    <div class="Line"></div>
    {widget id="deleteExpiredVisitors"}
    <div class="Line"></div>
    {widget id="allowComputeNegativeCommission"}
    <div class="Line"></div>
    {widget id="createChannelsAutomatically"}
  </div>
  
  <div class="FormFieldset">
  	<div class="FormFieldsetHeader">
  		<div class="FormFieldsetHeaderTitle">##Affiliate linking method##</div>
  		<div class="FormFieldsetHeaderDescription">##This will set the style of affiliate link URL that your affiliates will put to their pages. You can choose from the methods below.<br/>Note that some methods have different requirements##</div>
  	</div>
    {widget id="linking_method" class="LinkingMethod"}  
    <div class="Line"></div>
    <div class="HintText">##You can choose to support DirectLink linking as an addition to your standard affiliate links.<br/>The links chosen above will work, plus your affiliates will have option to use DirectLinks. All affiliate DirectLink URLs require merchant's approval.##</div>
    <div class="Line"></div>
    {widget id="support_direct_linking" class="SupportDirect"}
    {widget id="support_short_anchor_linking"}
  </div>

  <div class="clear"></div>
  {widget id="FormMessage"}
</div>
{widget id="SaveButton" }
