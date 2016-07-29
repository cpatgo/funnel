<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:58
         compiled from country_select_panel.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'country_select_panel.tpl', 4, false),)), $this); ?>
<!-- country_select_panel -->
<div class="FormFieldset">
    <div class="FormFieldsetHeader">
        <div class="FormFieldsetHeaderTitle"><?php echo smarty_function_localize(array('str' => 'Country settings'), $this);?>
</div>
    </div>
<?php echo "<div id=\"locationTypeCheckbox\"></div>"; ?>
<?php echo "<div id=\"countryMultiSelect\"></div>"; ?>
<?php echo "<div id=\"cityArea\"></div>"; ?>
</div>