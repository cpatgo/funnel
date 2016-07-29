<!--    import_panel    -->
<fieldset>
    <legend>##Import source##</legend>
    {widget id="delimiter"}
    {widget id="source" class="ImportRadioGroup"}
    {widget id="url"}
    {widget id="uploadFile"}
    {widget id="exportFilesGrid"} 
    {widget id="serverFile"}
</fieldset>
<fieldset>
    <legend>##Drop modules##</legend>
    ##Mark of any module will cause deleting of all data in that module, so if only update is needed marking of modules is not necessary.##
    {widget id="showDropModulesButton"}
    {widget id="importExportGrid"}
</fieldset>
    
{widget id="importButton"}
