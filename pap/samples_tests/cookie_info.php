<?php
include('./header.php');
?>
<div class="c1_MainBox c1_SamplesMainbox">
	<div class="c1_MainBoxContainer">
		<h1>Sample sale / lead tracking</h1>
		<p>this page simulates your order confirmation or "thank you for order" page.</p>
		<a class="c1_sButton" href="./">Back to Samples & tests home</a>
	</div>
</div>
<div class="c1_Wrapper">
	<div class="c1_WrapperContainer">

<div class="c1_WideSampleBox">
<h2>Cookies information</h2>
<p>
If somebody from this computer clicked on affiliate link before, the tracking cookie should be registered.
</p>
<fieldset>
<legend>Full cookie</legend>

Value: <input type="text" name="full_cookie_info" value="" id="fullCookieInfoId" size="30" readonly>
<p>full cookie value has format AFFILIATEID_CAMPAIGNID_CHANNEL - the channel part is optional.</p>
</fieldset>

<fieldset>
<legend>Affiliate ID part of the cookie</legend>
Value: <input type="text" name="aff_cookie_info" value="" id="affCookieInfoId" readonly>
<p>this is the first part of the cookie above, and it contains ID of affiliate in our system.</p>
</fieldset>

<fieldset>
<legend>Campaign ID from visitoraffiliate cookie</legend>
Value: <input type="text" name="aff_cookie_info" value="" id="campaignCookieInfoId" readonly>
<p>this is the campaign ID loaded from the cookie above.</p>
</fieldset>

<fieldset>
<legend>Link with cookie information</legend>

Link with added cookie info:
<a href="<?php echo $urlPart?>/payment.php?amount=120&product=P1&order=123" id="affCookieLinkId">see destination url of this link</a>
</fieldset>

<fieldset>
<legend>Link with affiliate id</legend>
Link with added affiliate id:
<a href="<?php echo $urlPart?>/payment.php?amount=120&product=P1&order=123" id="affLinkId">see destination url of this link</a>
</fieldset>
<br/>
<input type="button" onClick="history.go(0)" value="Refresh">
</div>
<script id="pap_x2s6df8d" src="../scripts/salejs.php" type="text/javascript">
</script>

<script type="text/javascript">
//use this if you need to track also click
//PostAffTracker.track();
</script>

<script type="text/javascript">
//PostAffTracker.setAccountId('default1');  //use this line for PAN account, set here your account Id instead of default1
//if you are using 'click tracking code' on same page with 'write affiliate to link/custom field', place these functions below tracking code.
PostAffTracker.writeCookieToCustomField('fullCookieInfoId');
PostAffTracker.writeAffiliateToCustomField('affCookieInfoId');
PostAffTracker.writeCampaignToCustomField('campaignCookieInfoId');
PostAffTracker.writeCookieToLink('affCookieLinkId', 'papCookie');
PostAffTracker.writeAffiliateToLink('affLinkId', 'a_aid');
</script>




</div></div>
<?php
include('./footer.php');
?>
