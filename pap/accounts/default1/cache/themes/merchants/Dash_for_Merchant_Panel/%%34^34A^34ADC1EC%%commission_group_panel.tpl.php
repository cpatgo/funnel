<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:58
         compiled from commission_group_panel.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'commission_group_panel.tpl', 9, false),)), $this); ?>
<!--    commission_group_panel  -->

<?php echo "<div id=\"campaignDetails\"></div>"; ?>
<div class="pad_left">
	<?php echo "<div id=\"backButton\"></div>"; ?>
</div>
<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle"><?php echo smarty_function_localize(array('str' => 'Commission group'), $this);?>
</div>
		<div class="FormFieldsetHeaderDescription"></div>
	</div>
  <?php echo "<div id=\"name\"></div>"; ?>
  <?php echo "<div id=\"priority\"></div>"; ?>
  <?php echo "<div id=\"cookielifetime\"></div>"; ?>
  <br/>
  <?php echo "<div id=\"tabPanel\"></div>"; ?>
</div>     
<div class="pad_left pad_top">
	<?php echo "<div id=\"saveButton\"></div>"; ?>
</div>