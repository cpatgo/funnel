<?php /* Smarty version 2.6.18, created on 2016-07-05 14:13:09
         compiled from home_edit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'home_edit.tpl', 7, false),)), $this); ?>
<!-- home_edit -->

<?php echo "<div id=\"WelcomeMessage\" class=\"WelcomeMessage\"></div>"; ?>

<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle"><?php echo smarty_function_localize(array('str' => 'Affiliate manager'), $this);?>
</div>
		<div class="FormFieldsetHeaderDescription"></div>
	</div>
  <?php echo "<div id=\"firstname\"></div>"; ?>
  <?php echo "<div id=\"lastname\"></div>"; ?>
  <?php echo "<div id=\"photo\"></div>"; ?>
  <?php echo "<div id=\"note\" class=\"WelcomeMessage\"></div>"; ?>
  <?php echo "<div id=\"DynamicFields\"></div>"; ?>
</div>

<?php echo "<div id=\"FormMessage\"></div>"; ?>
<?php echo "<div id=\"SaveButton\"></div>"; ?>