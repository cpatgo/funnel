<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:46
         compiled from add_affiliate_to_group.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'add_affiliate_to_group.tpl', 5, false),)), $this); ?>
<!--    add_affiliate_to_group  -->

<div class="ScreenHeader CommissionGroupViewHeader">
    <div class="ScreenTitle">
        <?php echo smarty_function_localize(array('str' => 'Add affiliate to group'), $this);?>

    </div>
    <div class="ScreenDescription">
    </div>
    <div class="clear"></div>
</div>

<div class="FormFieldset AddAffiliateToGroup">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle"><?php echo smarty_function_localize(array('str' => 'Affiliate'), $this);?>
</div>
		<div class="FormFieldsetHeaderDescription"></div>
	</div>
  <?php echo "<div id=\"userid\"></div>"; ?>
  <?php echo "<div id=\"rstatus\"></div>"; ?>
  <?php echo "<div id=\"note\" class=\"AddAffiliateToGroupNote\"></div>"; ?>
  <?php echo "<div id=\"commissiongroupid\"></div>"; ?>
  <?php echo "<div id=\"campaignid\"></div>"; ?>
</div>

<?php echo "<div id=\"addButton\"></div>"; ?>