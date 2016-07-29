<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:58
         compiled from config_aff_panel.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'config_aff_panel.tpl', 4, false),)), $this); ?>
<!-- config_aff_panel -->
<div class="FormFieldset clear_margin_left margin_bottom">	
<table>
	<tr><td><?php echo smarty_function_localize(array('str' => 'URL to Affiliate Panel:'), $this);?>
</td><td><?php echo "<div id=\"AffiliatePanelUrl\"></div>"; ?></td></tr>

<tr><td><?php echo smarty_function_localize(array('str' => 'URL to Affiliate mini site:'), $this);?>
</td><td><?php echo "<div id=\"AffiliateSiteUrl\"></div>"; ?></td></tr>
</table>
<?php echo smarty_function_localize(array('str' => 'Note that informational mini site is optional, the files don\'t need to be there'), $this);?>

</div>

<?php echo "<div id=\"Tabs\"></div>"; ?>