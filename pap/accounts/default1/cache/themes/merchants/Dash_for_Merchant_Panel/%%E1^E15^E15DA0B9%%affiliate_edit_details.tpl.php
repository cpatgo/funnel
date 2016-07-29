<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:46
         compiled from affiliate_edit_details.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'affiliate_edit_details.tpl', 5, false),)), $this); ?>
<!-- affiliate_edit_details -->

<div class="PersonalInfo">

<div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'Personal Info'), $this);?>
</div>
	<?php echo "<div id=\"username\"></div>"; ?>
	<?php echo "<div id=\"rpassword\"></div>"; ?>
	<?php echo "<div id=\"firstname\"></div>"; ?>
	<?php echo "<div id=\"lastname\"></div>"; ?>
	<?php echo "<div id=\"refid\"></div>"; ?>
	<?php echo "<div id=\"rstatus\"></div>"; ?>
	<?php echo "<div id=\"parentuserid\"></div>"; ?>
	<?php echo "<div id=\"note\"></div>"; ?>
	<div class="AddAffiliatePhoto"><?php echo "<div id=\"photo\"></div>"; ?></div>
	<?php echo "<div id=\"dontSendEmail\"></div>"; ?>
	<?php echo "<div id=\"createSignupReferralComm\"></div>"; ?>
	<div class="clear"></div>

<?php echo "<div id=\"DynamicFields1\"></div>"; ?>

<?php echo "<div id=\"DynamicFields2\"></div>"; ?>
<?php echo "<div id=\"FormMessage\"></div>"; ?>
<div class="clear"></div>
<?php echo "<div id=\"SaveButton\" class=\"PersonalSave\"></div>"; ?>
<div class="clear"></div>
</div>