<!-- payout_option_form -->

<div class="PayoutOptionsDetails">
    <div class="FormFieldset">
    	<div class="FormFieldsetHeader">
    		<div class="FormFieldsetHeaderTitle">##Payout option details##</div>
    		<div class="FormFieldsetHeaderDescription"></div>
    	</div>
    	{widget id="payoutOptionId"}
    	{widget id="name"}
    	{widget id="rstatus"}
    	{widget id="rorder"}
    </div>
    
    <div class="FormFieldset">
    	<div class="FormFieldsetHeader">
    		<div class="FormFieldsetHeaderTitle">##Fields##</div>
    		<div class="FormFieldsetHeaderDescription"></div>
    	</div>
    	{widget id="PayoutFieldsGrid"}
    </div>
    
    <div class="FormFieldset">
    	<div class="FormFieldsetHeader">
    		<div class="FormFieldsetHeaderTitle">##Mass pay export format##</div>
    		<div class="FormFieldsetHeaderDescription"></div>
    	</div>
    	##You can specify name and format of mass pay export file for this payout option.##
      ##When you pay your affiliates you will be able to download export file for each payout option.##<br>
      ##Format of this file consists of three parts: header, row and footer template.##
      ##Header is at the beginning of the file, row is generated for each affiliate that is going to be paid and footer is at the end of file.##
      ##In each of this templates you can use Smarty syntax and row template allows you also to use some other constants.##
      ##List of supported template constants is visible in the listbox above the row template text area.##
      <br>
      {widget id="data4"}
      {widget id="data1" class="RowTextArea"}
      {widget id="data2" class="RowTextAreaTemplateEdit"}
      {widget id="data3" class="RowTextAreaTemplateEdit"}
      {widget id="data5" class="RowTextAreaTemplateEdit"}
      {widget id="data6" class="RowTextAreaTemplateEdit"}
    </div>
     
    {widget id="FormMessage"}
    {widget id="SaveButton"}
</div>
