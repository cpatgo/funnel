<?php /* Smarty version 2.6.18, created on 2016-07-06 14:13:47
         compiled from config_language_and_date_form.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'config_language_and_date_form.tpl', 3, false),)), $this); ?>
<!-- config_language_and_date_form -->
<fieldset>
    <legend><?php echo smarty_function_localize(array('str' => 'Language'), $this);?>
</legend>
    <?php echo "<div id=\"default_language\"></div>"; ?>
    <?php echo "<div id=\"choosing_language\"></div>"; ?>
</fieldset>
<fieldset>
    <?php echo "<div id=\"date_format\"></div>"; ?>
</fieldset>