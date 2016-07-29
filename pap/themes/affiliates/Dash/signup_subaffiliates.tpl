<!-- signup_subaffiliates -->
<div class="Dash_FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##SubSignupOverview##</div>
		<div class="FormFieldsetHeaderDescription">##SubSignupOverviewDescription##</div>
	</div>
	{widget id="signupLink"}
</div>

<div class="Dash_FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##SubSignupDownloadForms##</div>
		<div class="FormFieldsetHeaderDescription">##SubSignupDownloadFormsDescription##</div>
	</div>
  {widget id="downloadJoinForm"}
  {widget id="downloadLoginForm"}
</div>

<div class="Dash_FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##SubSignupStats##</div>
		<div class="FormFieldsetHeaderDescription">##Number of your direct subaffiliates:## {widget id="numberOfSubaffiliates"}</div>
	</div>
  <table>
  <tr>
    <td align="center">{widget id="SubaffiliateSaleStats"}</td>
    <td align="center">{widget id="SubaffiliatesTree"}</td>
  </tr>
  </table> 	
</div>
