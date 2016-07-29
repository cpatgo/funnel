<!-- affiliate_signup_settings -->

<div class="TabDescription">
<h3>##Affiliate signup##</h3>
##General configuration of your signup form. Write the Terms & Conditions of your affiliate program, choose if to display payout option and what to do after signup.##
</div>

<div class="AffiliateSignupForm">

    <div class="FormFieldsetSectionTitle">##General##</div>
    {widget id="signup_form_enabled"}
    
    <div class="FormFieldsetSectionTitle">##Affiliate approval##</div>
    {widget id="affiliate_approval"}
    
    <div class="FormFieldsetSectionTitle">##Assign non-referred affiliate to##</div>
        {widget id="assignAffiliateTo"}
    
    <div class="AffiliateSignupAfter">
      <div class="FormFieldsetSectionTitle">##After signup##</div>
      ##What to do after signup?##
      {widget id="postSignupType" class="SignUrl"}
    </div>
    
    <div class="FormFieldsetSectionTitle">##Terms & conditions##</div>
      ##Set up Terms & conditions for your affiliate program## 
      {widget id="forceTermsAcceptance"}
      <div class="Line"></div>
      {widget id="termsAndConditions" class="TermsAndConditions"}
    
    <div class="FormFieldsetSectionTitle">##Payout option##</div>
    ##Check if you want to display payout options in your signup form## 
    {widget id="includePayoutOptions"}
    <div class="Line"></div>
    {widget id="forcePayoutOption"}
    <div class="Line"></div>
    {widget id="payoutOptions"}
    
    {widget id="reCatpchaSettings"}
    
    {widget id="forcedMatrix"}
        
    {widget id="FormMessage"}
    {widget id="SaveButton"}
<div class="clear"></div>
