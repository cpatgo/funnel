<!-- direct_links_screen -->
<div class="FormFieldset">
  ##Read more about DirectLinks## <a href='#189911-directlinks_explained' style="text-decoration: underline; font-weight: bold; color:#135fab">##here##</a>.
  <div>
    <div class="FloatLeft">##You don't need to enter each and every URL address of your pages, you can use star convention.##<br/>
  ##So for example pattern## <strong>*yoursite.com*</strong> ##will match:##<br/>
  www.yoursite.com<br/>
  subdomain.yoursite.com<br/>
  www.yoursite.com/something.html<br/>
  www.yoursite.com/dir/something.php?parameters<br/>
    </div>
    <div class="FloatLeft">
    
  <fieldset>
      <legend>##Test URL matching##</legend>
      
      <div class="HintText">##You can test if your pattern matches the given URL.##</div>
          
      <div class="Inliner">##Pattern##</div>
      {widget id="pattern" class="FormFieldBigInline FormFieldOnlyInput"}
      <div class="clear"></div>    
      <div class="Inliner">##Real url##</div>
      {widget id="realUrl" class="FormFieldBigInline FormFieldOnlyInput"}
       <div class="clear"></div>
      {widget id="checkButton"}
      {widget id="message"}
  </fieldset>
    </div>
  </div>
  <div class="clear"></div> 
</div>


<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle"></div>
		<div class="FormFieldsetHeaderDescription">##Links may not be changed. If you want to change link, you can delete the old link and create new, which must merchant again approve##</div>
	</div>
	{widget id="UrlsGrid"}
</div>
