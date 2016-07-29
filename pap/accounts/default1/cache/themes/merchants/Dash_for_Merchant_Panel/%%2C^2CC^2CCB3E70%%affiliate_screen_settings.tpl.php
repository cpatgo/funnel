<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:46
         compiled from affiliate_screen_settings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'affiliate_screen_settings.tpl', 4, false),)), $this); ?>
<!-- affiliate_screen_settings -->
<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle"><?php echo smarty_function_localize(array('str' => 'Header'), $this);?>
</div>
		<div class="FormFieldsetHeaderDescription"></div>
	</div>
  <table>
      <tr><td>
          <?php echo "<div id=\"HeaderEdit\"></div>"; ?> 
          <div class="clear"></div>
      </td></tr>
      <tr><td>
          <div class="ScreenSettingsSave">
              <?php echo "<div id=\"FormMessage\"></div>"; ?>
          </div>
      </td></tr>
      <tr><td>
          <div class="ScreenSettingsSave">
              <?php echo "<div id=\"SaveButton\"></div>"; ?>
          </div>
      </td></tr>
  </table>
</div>

<?php echo "<div id=\"EditContent\"></div>"; ?>