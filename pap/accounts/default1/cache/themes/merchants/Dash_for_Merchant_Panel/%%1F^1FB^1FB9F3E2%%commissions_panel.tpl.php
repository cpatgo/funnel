<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:58
         compiled from commissions_panel.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'commissions_panel.tpl', 2, false),)), $this); ?>
<!--    commissions_panel   -->
<div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'Commission groups'), $this);?>
</div>
<?php echo smarty_function_localize(array('str' => 'Here you can manage all user commission groups by clicking on commission group column.'), $this);?>

<?php echo "<div id=\"Search\"></div>"; ?>
<?php echo "<div id=\"Grid\"></div>"; ?>