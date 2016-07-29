<?php /* Smarty version 2.6.18, created on 2016-07-06 14:14:58
         compiled from plugins_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'plugins_list.tpl', 2, false),)), $this); ?>
<!-- plugins_list -->
<div class="Inliner"><?php echo smarty_function_localize(array('str' => 'Search'), $this);?>
:</div><div class="Inliner"><?php echo "<div id=\"searchTextBox\"></div>"; ?></div>
<div class="clear"></div>
<fieldset>
<legend><?php echo smarty_function_localize(array('str' => 'Currently active plugins'), $this);?>
</legend>
<?php echo "<div id=\"PanelActive\" class=\"PanelActivePlugins\"></div>"; ?>
</fieldset>

<div class="clear"></div>

<fieldset>
<legend><?php echo smarty_function_localize(array('str' => 'Inactive plugins'), $this);?>
</legend>
<?php echo "<div id=\"PanelInactive\" class=\"PanelInactivePlugins\"></div>"; ?>
</fieldset>

<div class="clear"></div>