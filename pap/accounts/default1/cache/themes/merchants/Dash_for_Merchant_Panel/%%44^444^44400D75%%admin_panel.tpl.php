<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:46
         compiled from admin_panel.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'admin_panel.tpl', 10, false),)), $this); ?>
<!--    admin_panel     -->

<div class="AdminPanel">
	<div class="AdminLinks">
		<?php echo "<div id=\"loginToMerchantPanel\"></div>"; ?>		
	</div>
	<div class="ClearBoth"></div>
  <div class="FormFieldset">
  	<div class="FormFieldsetHeader">
  		<div class="FormFieldsetHeaderTitle"><?php echo smarty_function_localize(array('str' => 'Merchant'), $this);?>
</div>
  		<div class="FormFieldsetHeaderDescription"></div>
  	</div>
    <?php echo "<div id=\"firstname\"></div>"; ?>
    <?php echo "<div id=\"lastname\"></div>"; ?>
    <?php echo "<div id=\"username\"></div>"; ?>
    <?php echo "<div id=\"rpassword\"></div>"; ?>
    <?php echo "<div id=\"retypepassword\"></div>"; ?>
    <?php echo "<div id=\"roleid\"></div>"; ?>
    <?php echo "<div id=\"accountid\"></div>"; ?>
    <?php echo "<div id=\"photo\" class=\"AdminPhoto\"></div>"; ?>
  </div>
  <?php echo "<div id=\"FormMessage\"></div>"; ?>
  <?php echo "<div id=\"sendButton\"></div>"; ?>
</div>