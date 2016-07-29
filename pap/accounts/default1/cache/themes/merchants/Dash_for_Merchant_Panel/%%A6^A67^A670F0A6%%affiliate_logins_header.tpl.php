<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:46
         compiled from affiliate_logins_header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'affiliate_logins_header.tpl', 4, false),)), $this); ?>
<!-- affiliate_logins_header -->
<div class="FormFieldset">
    <div class="FormFieldsetHeader">
        <div class="FormFieldsetHeaderTitle"><?php echo smarty_function_localize(array('str' => 'General'), $this);?>
</div>
    </div>
    <div class="AffiliateLoginsHeader">
        <?php echo smarty_function_localize(array('str' => 'Last login:'), $this);?>
 <?php echo "<div id=\"lastlogin\"></div>"; ?>
    </div>
    <div class="AffiliateLoginsHeader">
        <?php echo smarty_function_localize(array('str' => 'Logins count:'), $this);?>
 <?php echo "<div id=\"loginscount\"></div>"; ?>
    </div>
    <div class="clear"></div>
</div>