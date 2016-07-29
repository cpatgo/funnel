<!--    admin_panel     -->

<div class="AdminPanel">
	<div class="AdminLinks">
		{widget id="loginToMerchantPanel"}		
	</div>
	<div class="ClearBoth"></div>
  <div class="FormFieldset">
  	<div class="FormFieldsetHeader">
  		<div class="FormFieldsetHeaderTitle">##Merchant##</div>
  		<div class="FormFieldsetHeaderDescription"></div>
  	</div>
    {widget id="firstname"}
    {widget id="lastname"}
    {widget id="username"}
    {widget id="rpassword"}
    {widget id="retypepassword"}
    {widget id="roleid"}
    {widget id="accountid"}
    {widget id="photo" class="AdminPhoto"}
  </div>
  {widget id="FormMessage"}
  {widget id="sendButton"}
</div>
