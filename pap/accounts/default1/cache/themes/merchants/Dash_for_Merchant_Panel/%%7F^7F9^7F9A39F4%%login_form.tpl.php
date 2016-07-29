<?php /* Smarty version 2.6.18, created on 2016-07-05 14:13:09
         compiled from login_form.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'login_form.tpl', 17, false),)), $this); ?>
<!-- login_form -->

<div class="LoginMain">
  <div class="LoginMainIn">
			<?php echo "<div id=\"username\"></div>"; ?>
			<?php echo "<div id=\"password\"></div>"; ?>
      <?php echo "<div id=\"language\" class=\"LanguageSelector\"></div>"; ?>
			<div class="CheckBoxContainer"><?php echo "<div id=\"rememberMeInput\"></div>"; ?> <?php echo "<div id=\"rememberMeLabel\"></div>"; ?></div>
			<div class="clear"></div>
			<?php echo "<div id=\"FormMessage\"></div>"; ?>
			<div class="clear"></div>
			<?php echo "<div id=\"LoginButton\"></div>"; ?>
			<?php echo "<div id=\"ForgottenPasswordLink\"></div>"; ?>
			<div class="clear"></div>
						
    	<div class="SupportBrowsers">
      		<div class="SupportStrap"><?php echo smarty_function_localize(array('str' => 'Best viewed in:'), $this);?>
</div>
	      	<a class="Firefox" title="Firefox"></a>
	      	<a class="Ie" title="Internet Explorer"></a>
      		<a class="Safari" title="Safari"></a>
	      	<a class="Opera" title="Opera"></a>
      		<a class="Chrome" title="Google Chrome"></a>	
		<div class="clear"></div>
    	</div>
	<div class="clear"></div>
    </div>		
</div>