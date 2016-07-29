<?php /* Smarty version 2.6.18, created on 2016-07-06 14:13:02
         compiled from advanced_email_settings_form.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'advanced_email_settings_form.tpl', 3, false),)), $this); ?>
<!-- advanced_email_settings_form -->

<h3 class="TabDescription"><?php echo smarty_function_localize(array('str' => 'Advanced email settings'), $this);?>
</h3>
<br/>

<?php echo "<div id=\"user_agent\"></div>"; ?>


<h4><?php echo smarty_function_localize(array('str' => 'Delete old mails'), $this);?>
</h4>
<div class="Inliner"><div class="Label"><?php echo smarty_function_localize(array('str' => 'Delete mail records older than'), $this);?>
</div></div>
<div class="FormFieldMediumInline"><?php echo "<div id=\"deleteMailMonths\"></div>"; ?></div><div class="Inliner"><?php echo smarty_function_localize(array('str' => 'months'), $this);?>
</div>
<div class="Inliner"><?php echo "<div id=\"helpAutoDeleteMails\"></div>"; ?></div>
<div class="clear"></div>

<h4><?php echo smarty_function_localize(array('str' => 'Contact Us forms'), $this);?>
</h4>
<?php echo "<div id=\"useSystemFromEmailInContactUs\"></div>"; ?>
