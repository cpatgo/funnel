<!-- template_form -->
<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##Template##</div>
		<div class="FormFieldsetHeaderDescription"></div>
	</div>
  <table>
      <tr><td>
          <table width="100%">
              <tr><td>
                  <div class="FormField">
                          <div class="FormFieldLabel"><div class="Label Inliner Label-mandatory">##Theme##</div></div>
                          <div class="FormFieldInputContainer">{widget id="theme"}</div>
                          <div class="clear"></div>
                  </div>
              </td></tr>
              <tr><td>
                  {widget id="templatename"}</td></tr>
              <tr><td valign="top">
                  <div class="EditGettingStartedContent">
              	    {widget id="templatecontent"}
                  </div>
              </td></tr>
          </table>
      </td></tr>
      <tr><td>
          <div class="ScreenSettingsSave">
              {widget id="FormMessage"}
          </div>
      </td></tr>
      <tr><td>
          <div class="ScreenSettingsSave">
              <table class="TemplateFormNavigation">
                  <tbody>
                      <tr><td>{widget id="SaveButton"}</td>
                          <td>{widget id="CancelButton"}</td></tr>
                  </tbody>
              </table>
          </div>
      </td></tr>
  </table>
</div>
