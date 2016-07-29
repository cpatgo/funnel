<?php /* Smarty version 2.6.18, created on 2016-07-06 11:31:27
         compiled from theme_settings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'theme_settings.tpl', 2, false),)), $this); ?>
<!-- theme_settings.tpl -->
<div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'Selected theme'), $this);?>
</div>
<?php echo "<div id=\"selectedTheme\" class=\"ThemeActive\"></div>"; ?>
<div class="ClearBoth"></div>
<br/><br/>

<div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'Other themes'), $this);?>
</div>
<?php echo "<div id=\"otherThemes\"></div>"; ?>
<div class="ClearBoth"></div>