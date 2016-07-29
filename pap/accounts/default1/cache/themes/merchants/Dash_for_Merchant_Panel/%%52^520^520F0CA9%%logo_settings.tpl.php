<?php /* Smarty version 2.6.18, created on 2016-07-05 14:13:09
         compiled from logo_settings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'logo_settings.tpl', 2, false),)), $this); ?>
<!-- logo_settings -->
<div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'Logo and program name'), $this);?>
</div>
<div class="FormFieldsetHeaderDescription"><?php echo smarty_function_localize(array('str' => 'You can change the logo and name of the program. The logo appears in Affiliate panel and in Signup Form.'), $this);?>
</div>
<?php echo "<div id=\"programLogo\"></div>"; ?>
<div class="Line"></div>
<?php echo "<div id=\"programName\"></div>"; ?>
<div class="Line"></div>
<?php echo "<div id=\"FormPanelExtensionFields\"></div>"; ?>
<div class="clear"></div>

<?php echo "<div id=\"FormMessage\"></div>"; ?>
<?php echo "<div id=\"SaveButton\"></div>"; ?>
<div class="clear"></div>