<!-- htaccess_panel -->

<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##SEO Links settings##</div>
		<div class="FormFieldsetHeaderDescription">##Here you can specify how your links will look like.<br/>The link format will be: http://www.yoursite.com/prefixAFFILIATEIDseparatorBANNERIDsuffix<br/>for example: http://www.yoursite.com/ref/11111111/22222222.html##</div>
	</div>
  {widget id="modrewrite_prefix"}
  {widget id="modrewrite_separator"}
  {widget id="modrewrite_suffix"}
  {widget id="regenerateButton"}
</div>

<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##.htaccess code##</div>
		<div class="FormFieldsetHeaderDescription">##For proper SEO links functionality, you have to make sure that your web server supports mod_rewrite and you have to create a .htaccess file to your web home directory, and copy & paste the code below to this file.<br/>If this file already exists, simply add the code below to the end.<br/>Make sure you backup this file before making any changes.##  </div>
	</div>
	{widget id="htaccess_code" class="HtaccessTextArea"}
</div>

{widget id="SaveButton"}
{widget id="CancelButton"}
