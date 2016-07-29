<!-- commission_edit_with_fixedcost -->
<div class="CommissionEditWithFixedCostTopExtensionPanel">
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
  {widget id="useFixedCost" class="FixedCostCommissions"}{widget id="fixedCostHelp"}
  {widget id="FixedCost"}	
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
