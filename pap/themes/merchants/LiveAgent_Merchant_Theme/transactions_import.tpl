<!-- transactions_import -->
<div class="FormFieldset">
  <h3>##Available fields for affiliate data##</h3>
  ##Choose which fields you want to use to store data for your affiliates. Some fields are mandatory, and you have up to 25 optional fields for which you can decide what information they will keep, if they will be optional, mandatory, or not displayed at all. These fields will be displayed in affiliate signup form and affiliate profile editation.##
</div>

<div class="TransactionImport">
  <div class="FormFieldset">
  	<div class="FormFieldsetHeader">
  		<div class="FormFieldsetHeaderTitle">##Import file format##</div>
  		<div class="FormFieldsetHeaderDescription"></div>
  	</div>
  	{widget id="fields"}
    {widget id="addButton"}
  </div>

  <div class="FormFieldset">
  	<div class="FormFieldsetHeader">
  		<div class="FormFieldsetHeaderTitle">##Import file##</div>
  		<div class="FormFieldsetHeaderDescription"></div>
  	</div>
    {widget id="delimiter"}
    {widget id="source" class="ImportRadioGroup"}
    {widget id="url"}
    {widget id="uploadFile"}
    {widget id="exportFilesGrid"} 
    {widget id="serverFile"}
    {widget id="skipFirstRow"}
    {widget id="transactionType"}
  </div>

  <div class="FormFieldset">
  	<div class="FormFieldsetHeader">
  		<div class="FormFieldsetHeaderTitle">##Transaction import settings##</div>
  		<div class="FormFieldsetHeaderDescription"></div>
  	</div>
    {widget id="computeAtomaticaly"}
    {widget id="matchTransaction"}
    {widget id="matchTransactionStatus"}
    {widget id="transactionStatus"}
  </div>

  {widget id="importButton"}
</div>
