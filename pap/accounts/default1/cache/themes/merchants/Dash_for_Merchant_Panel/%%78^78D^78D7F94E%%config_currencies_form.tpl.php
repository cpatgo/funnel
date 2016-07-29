<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:58
         compiled from config_currencies_form.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'config_currencies_form.tpl', 4, false),)), $this); ?>
<!-- config_currencies_form -->
<div class="FormFieldset DefaultCurrency">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle"><?php echo smarty_function_localize(array('str' => 'Default currency'), $this);?>
</div>
	</div>
  <?php echo "<div id=\"name\"></div>"; ?>
  <?php echo "<div id=\"symbol\"></div>"; ?>
  <?php echo "<div id=\"cprecision\" class=\"Precision\"></div>"; ?>
  <?php echo "<div id=\"wheredisplay\" class=\"WhereDisplay\"></div>"; ?>
  <?php echo "<div id=\"multiple_currencies\" class=\"Multiple\"></div>"; ?>
  <?php echo "<div id=\"multipleCurrency_exchange_rate_updater_enabled\"></div>"; ?>
  <?php echo "<div id=\"nextUpdateDate\"></div>"; ?>
</div>

<?php echo "<div id=\"multiple_currencies_panel\"></div>"; ?>