<?php /* Smarty version 2.6.18, created on 2016-07-05 14:13:09
         compiled from download_auto_reg_link_form.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'download_auto_reg_link_form.tpl', 2, false),)), $this); ?>
<!-- download_auto_reg_link_form -->
<div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'Affiliate link and banner code forms'), $this);?>
</div>
<div class="TabDescription">
<?php echo smarty_function_localize(array('str' => 'HTML/PHP codes below you can use for generating affiliate link or banner code for your new affiliates. Just copy & paste the code below to a HTML/PHP page.'), $this);?>

<br/>
<?php echo smarty_function_localize(array('str' => 'NOTE: First two parts are customizable, but contains PHP parts, so it have to be inserted into PHP page and you need to have enabled url fopen wrapper (allow_url_fopen = On) on your server.'), $this);?>

<br/>
<?php echo smarty_function_localize(array('str' => 'If you cannot use PHP code on your HTML pages, you can use iframe codes below.'), $this);?>

</div>

<h3><?php echo smarty_function_localize(array('str' => 'Get your affiliate link form'), $this);?>
</h3>
<?php echo smarty_function_localize(array('str' => 'Copy and paste the code below to your web page'), $this);?>

<?php echo "<div id=\"formSource\" class=\"FormSource\"></div>"; ?>

<br/>
<?php echo smarty_function_localize(array('str' => 'Preview'), $this);?>

<hr>
<?php echo "<div id=\"formPreview\"></div>"; ?>