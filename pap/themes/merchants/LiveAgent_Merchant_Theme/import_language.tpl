<!-- import_language -->

<div class="SystemLanguages">
  <div class="FormFieldset">
  	<div class="FormFieldsetHeader">
  		<div class="FormFieldsetHeaderTitle">##Available languages##</div>
  		<div class="FormFieldsetHeaderDescription">##To make it easier for you, some languages are already included in the distribution##<br/>
##To import new language to the system, click on "Import" icon next to language that you would like to import.##<br/>
##Warning: If language with the same language code (e.g. [en-US]) already exists, translations and language metadata will be overwritten with the values loaded from imported language!!.##</div>
  	</div>
  	{widget id="SystemLanguagesGrid"}
  </div>

  <div class="FormFieldset">
  	<div class="FormFieldsetHeader">
  		<div class="FormFieldsetHeaderTitle">##Upload custom language##</div>
  		<div class="FormFieldsetHeaderDescription">##Warning: If language with the same language code (e.g. [en-US]) already exists, translations and language metadata will be overwritten with the values from the uploaded language!##</div>
  	</div>
  	{widget id="CustomLanguageUpload"}
    {widget id="uploadedLanguageFileForm"}
  </div>
</div>

{widget id="CloseButton"}
