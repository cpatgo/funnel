<!-- cookies_tab -->

<div class="FormFieldset CookiesForm">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##Cookies privacy policy##</div>
		<div class="FormFieldsetHeaderDescription">##Cookie privacy policy influences if the tracking cookies will be blocked by browsers, so it si important to set it.<br/>You should set at least Compact P3P policy. If you don't want to generate it for your site, use the following string: NOI NID ADMa DEVa PSAa OUR BUS ONL UNI COM STA OTC##</div>
	</div>
	{widget id="url_to_p3p"}
  {widget id="p3p_policy_compact"}
</div>

<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##Tracking related settings##</div>
		<div class="FormFieldsetHeaderDescription"></div>
	</div>
  {widget id="cookie_domain" class="CookieDomain"}
  <div class="Line"></div>
  {widget id="overwrite_cookie" class="OverwriteCookie"}
  {widget id="overwrite_cookie_disabled" class="OverwriteCookie"}
  <div class="Line"></div>
  {widget id="delete_cookie" class="OverwriteCookie"}
</div>

{widget id="SaveButton"}
<div class="clear"></div>
