<?php /* Smarty version 2.6.18, created on 2016-07-06 14:13:13
         compiled from affiliate_logins_header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'affiliate_logins_header.tpl', 3, false),)), $this); ?>
<!-- affiliate_logins_header -->
<fieldset>
    <legend><?php echo smarty_function_localize(array('str' => 'General'), $this);?>
</legend>
    <div class="AffiliateLoginsHeader">
        <?php echo smarty_function_localize(array('str' => 'Last login:'), $this);?>
 <?php echo "<div id=\"lastlogin\"></div>"; ?>
    </div>
    <div class="AffiliateLoginsHeader">
        <?php echo smarty_function_localize(array('str' => 'Logins count:'), $this);?>
 <?php echo "<div id=\"loginscount\"></div>"; ?>
    </div>
    <div class="clear"></div>
</fieldset>