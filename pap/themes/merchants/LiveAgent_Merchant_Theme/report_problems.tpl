<!-- report_problems -->
<div class="FormFieldset">
  <p>
  ##You can resolve your problem faster as we can answer your ticket by searching for solution in our knowledgebase:## 
  <b><a href="{$postAffiliateProHelp}" target="_blank">##Click here to open Knowledgebase.##</a></b>
  </p>
  
  <p>
  ##Would you like to report bug ? Please check first, if bug was not resolved already. List of resolved bugs you can find in our change log.##
  <b><a href="{$qualityUnitChangeLog}" target="_blank">##Click here to open Change log.##</a></b>
  </p>
</div>

<div class="FormFieldset ReportProblems">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##Report problem##</div>
		<div class="FormFieldsetHeaderDescription"></div>
	</div>
  {widget id="email"}
  {widget id="subject"}
  {widget id="message" class="ReportProblemsMessage"}
  {widget id="FormMessage"}
  {widget id="SendButton"}
</div>
