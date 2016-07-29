<!--    import_panel    -->
<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##Import source##</div>
		<div class="FormFieldsetHeaderDescription"></div>
	</div>
  {widget id="delimiter"}
  {widget id="source" class="ImportRadioGroup"}
  {widget id="url"}
  {widget id="uploadFile"}
  {widget id="exportFilesGrid"} 
  {widget id="serverFile"}
</div>

<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##Drop modules##</div>
		<div class="FormFieldsetHeaderDescription">##Mark of any module will cause deleting of all data in that module, so if only update is needed marking of modules is not necessary.##</div>
		{widget id="showDropModulesButton"}
	</div>
  {widget id="importExportGrid"}
</div>

{widget id="importButton"}
