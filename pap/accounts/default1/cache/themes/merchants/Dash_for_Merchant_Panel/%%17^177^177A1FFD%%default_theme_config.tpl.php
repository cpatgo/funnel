<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:58
         compiled from default_theme_config.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'default_theme_config.tpl', 2, false),)), $this); ?>
<!-- default_theme_config -->
<div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'Active theme'), $this);?>
</div>
<?php echo "<div id=\"selectedTheme\" class=\"ThemeActive\"></div>"; ?>
<div class="ClearBoth"></div>
<br/><br/>

<div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'Other available themes'), $this);?>
</div>
<div class="OtherThemesDescription"><?php echo smarty_function_localize(array('str' => 'You can choose from the themes below. Click on <strong>Select this theme</strong> to set it as a new default theme.'), $this);?>
</div>
<?php echo "<div id=\"otherThemes\"></div>"; ?>
<div class="ClearBoth"></div>