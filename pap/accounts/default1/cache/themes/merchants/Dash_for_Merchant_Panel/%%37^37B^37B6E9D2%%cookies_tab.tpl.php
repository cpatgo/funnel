<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:58
         compiled from cookies_tab.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'cookies_tab.tpl', 5, false),)), $this); ?>
<!-- cookies_tab -->

<div class="FormFieldset CookiesForm">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle"><?php echo smarty_function_localize(array('str' => 'Cookies privacy policy'), $this);?>
</div>
		<div class="FormFieldsetHeaderDescription"><?php echo smarty_function_localize(array('str' => 'Cookie privacy policy influences if the tracking cookies will be blocked by browsers, so it si important to set it.<br/>You should set at least Compact P3P policy. If you don\'t want to generate it for your site, use the following string: NOI NID ADMa DEVa PSAa OUR BUS ONL UNI COM STA OTC'), $this);?>
</div>
	</div>
	<?php echo "<div id=\"url_to_p3p\"></div>"; ?>
  <?php echo "<div id=\"p3p_policy_compact\"></div>"; ?>
</div>

<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle"><?php echo smarty_function_localize(array('str' => 'Tracking related settings'), $this);?>
</div>
		<div class="FormFieldsetHeaderDescription"></div>
	</div>
  <?php echo "<div id=\"cookie_domain\" class=\"CookieDomain\"></div>"; ?>
  <div class="Line"></div>
  <?php echo "<div id=\"overwrite_cookie\" class=\"OverwriteCookie\"></div>"; ?>
  <?php echo "<div id=\"overwrite_cookie_disabled\" class=\"OverwriteCookie\"></div>"; ?>
  <div class="Line"></div>
  <?php echo "<div id=\"delete_cookie\" class=\"OverwriteCookie\"></div>"; ?>
</div>
<div class="pad_left pad_top">
<?php echo "<div id=\"SaveButton\"></div>"; ?>
</div>
<div class="clear"></div>