<!-- direct_link_form -->
<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##Add / edit DirectLink URL##</div>
		<div class="FormFieldsetHeaderDescription"></div>
	</div>
  {widget id="url"}
  {widget id="note"}
</div>

<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##Additional tracking##</div>
		<div class="FormFieldsetHeaderDescription">##You can set that the click from this URL will belong to a selected channel, banner or campaign. If you don't select anything, the default campaign will be used.##</div>
	</div>
  {widget id="channelid"}
  
  <div class="Line"></div>
  {widget id="campaignid"}
  ##or##
  {widget id="bannerid"}
</div>

{widget id="FormMessage"}
{widget id="SaveButton"}
