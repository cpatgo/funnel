<?php /* Smarty version 2.6.18, created on 2016-07-06 14:11:53
         compiled from login_form.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'login_form.tpl', 6, false),)), $this); ?>
<!-- login_form -->
<div class="c1_Wrapper c1_WrapperContact">
	<h1 class="c1_single"><?php echo "<div id=\"panelName\"></div>"; ?></h1>
	<div class="c1_WrapperContainer">
		<div class="c1_WindowIn">
			<div class="WindowHeaderTitle"><?php echo smarty_function_localize(array('str' => 'Login'), $this);?>
</div>
			<div class="WindowContent">
			<?php echo "<div id=\"username\"></div>"; ?>
			<?php echo "<div id=\"password\"></div>"; ?>
			<?php echo "<div id=\"language\"></div>"; ?>
			<div class="CheckBoxContainer"><?php echo "<div id=\"rememberMeInput\"></div>"; ?> <?php echo "<div id=\"rememberMeLabel\"></div>"; ?></div>
			<div class="clear"></div>
			<?php echo "<div id=\"FormMessage\"></div>"; ?>
			<div class="clear"></div>
			<?php echo "<div id=\"LoginButton\"></div>"; ?>
			<?php echo "<div id=\"ForgottenPasswordLink\"></div>"; ?>
			<div class="clear"></div>
			</div>
		</div>
		<?php echo "<div id=\"login_annotation\" class=\"c1_Annotation\"></div>"; ?>
	</div>
</div>