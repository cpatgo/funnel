<!-- campaign_form_edit -->

<div class="Details">
<div class="FormFieldsetSectionTitle">##Details##</div>
        {widget id="name"}
        {widget id="logourl" class="CampaignLogo"}
        {widget id="description"}
        {widget id="longdescription"}
</div>

<div class="CampaignStatus">
<div class="FormFieldsetSectionTitle">##Campaign status##</div>
        <div class="CampaignFormEdit_CampaignStatus">{widget id="rstatus"}</div>
        <div class="clear"></div>
    {widget id="campaignScheduler"}
</div>

{widget id="accountid"}

{widget id="rtype"}

<div class="Cookies">
<div class="FormFieldsetSectionTitle">##Cookies##</div>
        {widget id="cookielifetime"}
        <div class="Line"></div>
        {widget id="overwritecookie" class="OCookies"}
        {widget id="overwrite_cookie_disabled" class="OCookies"}
        <div class="Line"></div>
        {widget id="delete_cookie" class="OCookies"}
</div>

<div class="LinkingMethod">
<div class="FormFieldsetSectionTitle">##Affiliate linking method##</div>
        ##You can choose the style of URL links specially for this campaign.##
        {widget id="linkingmethod" class="LinkingMethod"}
</div>

<div class="ProductId">
<div class="FormFieldsetSectionTitle">##Product ID matching##</div>
        {widget id="productid" class="CampaignProductId"}
</div>

{widget id="campaignDetailsAdditionalForm"}
{widget id="campaignDetailsFeaturesPlaceholder"}

{widget id="FormMessage"}<br/>
{widget id="SaveButton"} {widget id="NextButton"}

<div class="clear"></div>
