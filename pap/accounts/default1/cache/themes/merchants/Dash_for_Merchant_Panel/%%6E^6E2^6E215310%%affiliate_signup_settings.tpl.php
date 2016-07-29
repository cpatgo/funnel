<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:46
         compiled from affiliate_signup_settings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'affiliate_signup_settings.tpl', 4, false),)), $this); ?>
<!-- affiliate_signup_settings -->

<div class="TabDescription">
<h3><?php echo smarty_function_localize(array('str' => 'Affiliate signup'), $this);?>
</h3>
<?php echo smarty_function_localize(array('str' => 'General configuration of your signup form. Write the Terms & Conditions of your affiliate program, choose if to display payout option and what to do after signup.'), $this);?>

</div>

<div class="AffiliateSignupForm">

    <div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'General'), $this);?>
</div>
    <?php echo "<div id=\"signup_form_enabled\"></div>"; ?>
    
    <div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'Affiliate approval'), $this);?>
</div>
    <?php echo "<div id=\"affiliate_approval\"></div>"; ?>
    
    <div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'Assign non-referred affiliate to'), $this);?>
</div>
        <?php echo "<div id=\"assignAffiliateTo\"></div>"; ?>
    
    <div class="AffiliateSignupAfter">
      <div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'After signup'), $this);?>
</div>
      <?php echo smarty_function_localize(array('str' => 'What to do after signup?'), $this);?>

      <?php echo "<div id=\"postSignupType\" class=\"SignUrl\"></div>"; ?>
    </div>
    
    <div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'Terms & conditions'), $this);?>
</div>
      <?php echo smarty_function_localize(array('str' => 'Set up Terms & conditions for your affiliate program'), $this);?>
 
      <?php echo "<div id=\"forceTermsAcceptance\"></div>"; ?>
      <div class="Line"></div>
      <?php echo "<div id=\"termsAndConditions\" class=\"TermsAndConditions\"></div>"; ?>
    
    <div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'Payout option'), $this);?>
</div>
    <?php echo smarty_function_localize(array('str' => 'Check if you want to display payout options in your signup form'), $this);?>
 
    <?php echo "<div id=\"includePayoutOptions\"></div>"; ?>
    <div class="Line"></div>
    <?php echo "<div id=\"forcePayoutOption\"></div>"; ?>
    <div class="Line"></div>
    <?php echo "<div id=\"payoutOptions\"></div>"; ?>
    
    <?php echo "<div id=\"reCatpchaSettings\"></div>"; ?>
    
    <?php echo "<div id=\"forcedMatrix\"></div>"; ?>
        
    <?php echo "<div id=\"FormMessage\"></div>"; ?>
    <?php echo "<div id=\"SaveButton\"></div>"; ?>
<div class="clear"></div>