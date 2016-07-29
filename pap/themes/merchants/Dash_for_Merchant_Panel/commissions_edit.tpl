<!-- commissions_edit -->
<div class="CommissionEditTopExtensionPanel">
    {widget id="FeatureTopExtensionFormPanel"}
</div>

<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##Commission type settings##</div>
		<div class="FormFieldsetHeaderDescription"></div>
	</div>
  {widget id="code"}
  {widget id="name"}
  {widget id="approval" class="Approval"}
  {widget id="zeroorderscommission" class="ZeroOrdersCommissions"}
  {widget id="savezerocommission" class="ZeroOrdersCommissions"}
  {widget id="onlyUnique"}
</div>

<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##Commissions##</div>
		<div class="FormFieldsetHeaderDescription"></div>
	</div>
	{widget id="resetCommission"}
	{widget id="NormalCommissionValues"}
</div>

{widget id="FeatureExtensionFormPanel"}

{widget id="PluginExtensionFormPanel"}

{widget id="FormMessage"}
{widget id="SaveButton"} {widget id="CloseButton"}
