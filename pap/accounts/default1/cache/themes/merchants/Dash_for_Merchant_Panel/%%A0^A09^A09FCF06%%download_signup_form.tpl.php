<?php /* Smarty version 2.6.18, created on 2016-07-05 14:13:09
         compiled from download_signup_form.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'download_signup_form.tpl', 3, false),)), $this); ?>
<!-- download_signup_form -->
<div class="TabDescription">
<h3><?php echo smarty_function_localize(array('str' => 'HTML Signup Form'), $this);?>
</h3>
<?php echo smarty_function_localize(array('str' => 'If you don\'t want to use the standard signup form, you can use pure HTML signup form. Just copy & paste the code below to a HTML page and you have fully customizable signup form.'), $this);?>

<br/>
<?php echo smarty_function_localize(array('str' => 'Note! Using HTML signup form instead of standard signup form is advisable only on special circumstances, this form does not use advanced checking that is implemented in normal signup form.'), $this);?>

</div>

<div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'Signup Form HTML'), $this);?>
</div>
<?php echo smarty_function_localize(array('str' => 'Copy and paste the code below to your web page'), $this);?>

<?php echo "<div id=\"formSource\" class=\"FormSource\"></div>"; ?>


<br/>
<?php echo smarty_function_localize(array('str' => 'Form preview'), $this);?>

<hr>
<?php echo "<div id=\"formPreview\"></div>"; ?>