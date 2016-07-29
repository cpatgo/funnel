<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:58
         compiled from banners_categories_tree.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'banners_categories_tree.tpl', 3, false),)), $this); ?>
<!-- banners_categories_tree -->

<div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'Categories'), $this);?>
</div>
<div class="FormFieldsetHeaderDescription"><?php echo smarty_function_localize(array('str' => 'You can modify banner categories by dragging them.'), $this);?>
<br/><?php echo smarty_function_localize(array('str' => 'Items can be removed by dragging to Trash'), $this);?>
</div>
<hr>
<?php echo "<div id=\"menuTree\"></div>"; ?>
<hr>
<?php echo "<div id=\"Trash\"></div>"; ?>
<?php echo "<div id=\"WarningMessage\"></div>"; ?>
<?php echo "<div id=\"SaveButton\"></div>"; ?>
<?php echo "<div id=\"NewButton\"></div>"; ?>
<div class="clear"/>