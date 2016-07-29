<?php /* Smarty version 2.6.18, created on 2016-07-06 14:13:13
         compiled from affiliate_tracking_options.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'affiliate_tracking_options.tpl', 7, false),)), $this); ?>
<!-- affiliate_tracking_options -->
<table class="AffiliateTrackingOptions">
<tr><td valign="top">
        <?php echo "<div id=\"cookiePanel\"></div>"; ?>
    </td><td valign="top">
        <fieldset>
            <legend><?php echo smarty_function_localize(array('str' => 'Test link'), $this);?>
</legend>
            <?php echo "<div id=\"TestLinkPanel\"></div>"; ?>
        </fieldset>
    </td></tr>
</table>

<fieldset>
    <legend><?php echo smarty_function_localize(array('str' => 'DirectLink URLs'), $this);?>
</legend>
    <?php echo "<div id=\"affiliateUrlsGrid\"></div>"; ?>
</fieldset>