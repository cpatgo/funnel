<!-- email_form -->
<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##Mail##</div>
		<div class="FormFieldsetHeaderDescription">
      ##Text enclosed by \#\# is considered a language constant and can be translated to various languages.##
      <a href="{$knowledgebaseUrl}542010-Using-language-constants-in-the-templates" target="_blank">##Read more details in our knowledgebase:##</a>
    </div>
	</div>
  {widget id="subject"}
	{widget id="body_html"}
	{widget id="body_text"}
	{widget id="customTextBodyControl" class="EmailForm" class="EmailFormControlTextBody"}
</div>

{widget id="uploadPanel"}
<div class="FormFieldset">
    {widget id="clearButton"}
    {widget id="loadTemplateButton"}
</div>
