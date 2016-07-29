<!-- payout_invoice_format -->

<div class="FormFieldset PayoutsInvoiceSettingsForm">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##Invoicing settings##</div>
		<div class="FormFieldsetHeaderDescription"></div>
	</div>
  {widget id="generate_invoices"}
  {widget id="invoice_bcc_recipient"}
</div>

<div class="FormFieldset PayoutsInvoiceForm">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##Payout invoice##</div>
		<div class="FormFieldsetHeaderDescription">##HTML format of the invoice.##
##You can use Smarty syntax in this template and the constants from the list below.##</div>
	</div>
  <div class="InvoiceEditor">
      {widget id="payoutInvoice"}
  </div>
  <div class="FormFieldLabel"><div class="Inliner">##Payout preview##</div></div>
  <div class="FormFieldInputContainer">
      <div class="FormFieldInput">{widget id="userid"}</div>
      <div class="FormFieldHelp">{widget id="previewInvoiceHelp"}</div>
      <div>{widget id="previewInvoice"}</div>
      {widget id="formPanel"}
      <div class="FormFieldDescription">##By clicking Preview invoice you can see how the invoice will look like for the specified affiliate.##</div>
  </div>
  <div class="clear"/></div>
</div>

{widget id="SaveButton"}
<div class="clear"></div>
