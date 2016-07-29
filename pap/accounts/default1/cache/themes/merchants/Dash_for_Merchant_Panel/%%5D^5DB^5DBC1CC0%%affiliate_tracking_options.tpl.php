<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:46
         compiled from affiliate_tracking_options.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'affiliate_tracking_options.tpl', 4, false),)), $this); ?>
<!-- affiliate_tracking_options -->
<?php echo "<div id=\"cookiePanel\"></div>"; ?>

<div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'Test link'), $this);?>
</div>
	<div class="pad_bottom">
	<?php echo "<div id=\"TestLinkPanel\"></div>"; ?>
	</div>
<div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'DirectLink URLs'), $this);?>
</div>
	<div class="pad_bottom">
	<?php echo "<div id=\"affiliateUrlsGrid\"></div>"; ?>
	</div>