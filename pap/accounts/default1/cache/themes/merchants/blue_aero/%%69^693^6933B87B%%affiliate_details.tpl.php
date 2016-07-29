<?php /* Smarty version 2.6.18, created on 2016-07-06 14:13:02
         compiled from affiliate_details.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'affiliate_details.tpl', 10, false),)), $this); ?>
<!-- affiliate_details -->
<div class="ScreenHeader AffiliateViewHeader CustomViewHeader">
	<div class="AffiliatePhoto"><?php echo "<div id=\"photo\"></div>"; ?></div>
	<div class="CustomViewRightIcons">
	   <?php echo "<div id=\"RefreshButton\"></div>"; ?>
	</div>
	<div class="ScreenTitle"><?php echo "<div id=\"firstname\"></div>"; ?>&nbsp;<?php echo "<div id=\"lastname\"></div>"; ?></div>
	<div class="ScreenDescription">
		<div class="ScreenDescriptionLeft">
            <div><div class="FloatLeft"><?php echo smarty_function_localize(array('str' => 'User Id'), $this);?>
:&nbsp;</div><div class="FloatLeft"><b><?php echo "<div id=\"userid\"></div>"; ?></b></div></div>
            <br/>
            <div><div class="FloatLeft"><?php echo smarty_function_localize(array('str' => 'Username'), $this);?>
:&nbsp;</div><div class="FloatLeft"><b><?php echo "<div id=\"username\"></div>"; ?></b></div></div>
	        <br/>
            <div><div class="FloatLeft"><?php echo smarty_function_localize(array('str' => 'Status'), $this);?>
:&nbsp;</div><div class="FloatLeft"><b><?php echo "<div id=\"rstatus\"></div>"; ?></b></div></div>
		</div>
		<div class="ScreenDescriptionRight">
			<?php echo "<div id=\"loginToAffiliatePanel\"></div>"; ?><br/>
			<?php echo "<div id=\"sendSignupConfirmationEmail\"></div>"; ?><br/>
			<?php echo "<div id=\"sendRequestNewPasswordEmail\"></div>"; ?><br/>
            <?php echo "<div id=\"sendMailToAffiliate\"></div>"; ?><br/>
		</div>
	</div>
	<div class="clear"/>
</div>