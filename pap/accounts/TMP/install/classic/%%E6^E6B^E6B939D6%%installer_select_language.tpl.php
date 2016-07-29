<?php /* Smarty version 2.6.18, created on 2016-07-06 12:43:34
         compiled from installer_select_language.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'installer_select_language.tpl', 3, false),)), $this); ?>
<!-- installer_select_language -->
<div class="FormFieldset">
    <div class="FormFieldsetHeaderTitle"><?php echo smarty_function_localize(array('str' => 'Choose Language'), $this);?>
</div>
    <?php echo smarty_function_localize(array('str' => 'Please select the language to use during the Post Affiliate Pro installation steps'), $this);?>

    <?php echo "<div id=\"SelectLanguage\" class=\"LanguageListBox\"></div>"; ?>
    <div class="FormFieldsetDivider"></div>
	<div class="Note">
		<?php echo smarty_function_localize(array('str' => 'Note: If you need multilanguage support, you can add additional languages once application will be installed.'), $this);?>

	</div> 
	<div class="cleaner"></div>
</div>