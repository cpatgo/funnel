<!-- email_form -->
<div class="EmailForm">
<div class="Description">
##Text enclosed by \#\# is considered a language constant and can be translated to various languages.##
<a href="{$knowledgebaseUrl}542010-Using-language-constants-in-the-templates" target="_blank">##Read more details in our knowledgebase:##</a>
</div> 
<fieldset>
<legend>##Mail##</legend>
    {widget id="subject"}

	{widget id="body_html"}
	{widget id="body_text"}
	{widget id="customTextBodyControl" class="EmailForm" class="EmailFormControlTextBody"}
</fieldset>

{widget id="uploadPanel"}
</div>
{widget id="clearButton"}
{widget id="loadTemplateButton"}
