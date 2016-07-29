<!-- integration_methods -->
<fieldset>
    <legend>##Integration method##</legend>
    ##To setup tracking of sales choose one of the integration methods below.##<br/><br/>
    {widget id="IntegrationMethods" class="IntegrationMethods"}
    <div>{widget id="hashScriptNamesCheckBox"} ##Hash script file names (hashed scripts are hard to be blocked by AdBlock), mod_rewrite rules are used, defined in .htaccess file.##</div>
    <div class="clear"></div>
    <div>{widget id="UseHttps"} ##Use secure connection##</div>
    <div class="ClearLeft"></div>
    {widget id="AdvancedOptionsButton" class="FloatLeft"}
    {widget id="AdvancedOptionsPanel" class="ClearLeft"}
</fieldset>

<fieldset>
    <legend>##Integration steps##</legend>
    {widget id="IntegrationMethodBody"}
</fieldset>
