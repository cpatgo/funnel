<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:58
         compiled from country_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'country_list.tpl', 5, false),)), $this); ?>
<!--    country_list    -->

<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle"><?php echo smarty_function_localize(array('str' => 'Countries'), $this);?>
</div>
	</div>
	<?php echo "<div id=\"grid\"></div>"; ?>

</div>