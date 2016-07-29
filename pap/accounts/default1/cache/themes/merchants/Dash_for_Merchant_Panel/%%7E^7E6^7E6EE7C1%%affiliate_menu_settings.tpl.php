<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:46
         compiled from affiliate_menu_settings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'affiliate_menu_settings.tpl', 3, false),)), $this); ?>
<!-- affiliate_menu_settings -->
<div class="AffiliateMenu">
  <div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'Affiliate menu'), $this);?>
</div>
  <?php echo smarty_function_localize(array('str' => 'You can modify affiliate menu by dragging it\'s items.'), $this);?>
<br/>
  <?php echo smarty_function_localize(array('str' => 'You can create max 2 level menu.'), $this);?>
<br/>
  <?php echo smarty_function_localize(array('str' => 'Items can be removed by dragging to Trash'), $this);?>

   <hr>
  <?php echo "<div id=\"menuTree\"></div>"; ?>
  <hr>
  <?php echo "<div id=\"Trash\"></div>"; ?>
  <?php echo "<div id=\"WarningMessage\"></div>"; ?>
  <?php echo "<div id=\"SaveButton\"></div>"; ?>
</div>
<div class="clear"/>