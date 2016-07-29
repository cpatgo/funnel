<!-- integration_methods -->

<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##Integration method##</div>
		<div class="FormFieldsetHeaderDescription">##To setup tracking of sales choose one of the integration methods below.##</div>
	</div>
  {widget id="IntegrationMethods" class="IntegrationMethods"}
  <div>{widget id="hashScriptNamesCheckBox"} ##Hash script file names (hashed scripts are hard to be blocked by AdBlock), mod_rewrite rules are used, defined in .htaccess file.##</div>
  <div class="clear"></div>
  <div>{widget id="UseHttps"} ##Use secure connection##</div>
  <div class="ClearLeft"></div>
  {widget id="AdvancedOptionsButton" class="FloatLeft"}
  {widget id="AdvancedOptionsPanel" class="ClearLeft"}
  <div class="clear"></div>
</div>

<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##Integration steps##</div>
		<div class="FormFieldsetHeaderDescription"></div>
	</div>
	{widget id="IntegrationMethodBody"}
  <div class="clear"></div>
</div>
