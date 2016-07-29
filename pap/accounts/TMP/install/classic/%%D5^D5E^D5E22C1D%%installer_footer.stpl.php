<?php /* Smarty version 2.6.18, created on 2016-07-06 12:43:34
         compiled from installer_footer.stpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'installer_footer.stpl', 4, false),)), $this); ?>
<!-- installer_footer -->
<div class="cleaner"></div>
<div class="Footer">
	<div class="Copyright">&copy; 2004-<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y") : smarty_modifier_date_format($_tmp, "%Y")); ?>
 QualityUnit.com - Post Affiliate Pro version <?php echo "<div id=\"VersionFooter\" class=\"Inliner\"></div>"; ?><div>
</div>