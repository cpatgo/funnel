<!-- direct_links_screen -->
<div class="Dash_FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##Links##</div>
		<div class="FormFieldsetHeaderDescription">##Links may not be changed. If you want to change link, you can delete the old link and create new, which must merchant again approve##</div>
	</div>
	{widget id="UrlsGrid"}
</div>
<div class="Dash_FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle">##Usage##</div>
	</div>
	<p>
	##Read more about DirectLinks## <a href='#189911-directlinks_explained' style="text-decoration: underline; font-weight: bold; color:#135fab">##here##</a>.
	##You don't need to enter each and every URL address of your pages, you can use star convention.##<br/>
	##So for example pattern## <strong>*yoursite.com*</strong> ##will match:##<br />
	www.yoursite.com<br />
	subdomain.yoursite.com<br />
	www.yoursite.com/something.html<br />
	www.yoursite.com/dir/something.php?parameters<br />
	</p>
	  <fieldset>
			<legend>##Test URL matching##</legend>
			<div class="HintText">##You can test if your pattern matches the given URL.##</div>
			<table class="Dash_URLTable">
				<tbody>
					<tr>
						<td><div class="Inliner">##Pattern##</div></td><td>{widget id="pattern" class="FormFieldBigInline FormFieldOnlyInput"}</td>
					</tr>
					<tr>
						<td><div class="Inliner">##Real url##</div></td><td>{widget id="realUrl" class="FormFieldBigInline FormFieldOnlyInput"}</td>
					</tr>
					<tr>
						<td>{widget id="checkButton"}</td>
						<td>{widget id="message"}</td>
					</tr>
				</tbody>
			</table>				  
	  </fieldset>
</div>


