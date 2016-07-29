<!-- affiliate_details -->
<div class="ScreenHeader AffiliateViewHeader CustomViewHeader">
	<div class="AffiliatePhoto">{widget id="photo"}</div>
	<div class="CustomViewRightIcons">
	   {widget id="RefreshButton"}
	</div>
	<div class="ScreenTitle">{widget id="firstname"}&nbsp;{widget id="lastname"}</div>
	<div class="ScreenDescription">
		<div class="ScreenDescriptionLeft">
            <div><div class="FloatLeft">##User Id##:&nbsp;</div><div class="FloatLeft"><b>{widget id="userid"}</b></div></div>
            <br/>
            <div><div class="FloatLeft">##Username##:&nbsp;</div><div class="FloatLeft"><b>{widget id="username"}</b></div></div>
	        <br/>
            <div><div class="FloatLeft">##Status##:&nbsp;</div><div class="FloatLeft"><b>{widget id="rstatus"}</b></div></div>
		</div>
		<div class="ScreenDescriptionRight">
			{widget id="loginToAffiliatePanel"}<br/>
			{widget id="sendSignupConfirmationEmail"}<br/>
			{widget id="sendRequestNewPasswordEmail"}<br/>
            {widget id="sendMailToAffiliate"}<br/>
		</div>
	</div>
	<div class="clear"/>
</div>
