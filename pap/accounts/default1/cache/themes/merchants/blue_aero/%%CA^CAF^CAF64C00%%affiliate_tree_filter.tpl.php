<?php /* Smarty version 2.6.18, created on 2016-07-06 14:13:14
         compiled from affiliate_tree_filter.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'affiliate_tree_filter.tpl', 5, false),)), $this); ?>
<!-- affiliate_tree_filter -->
<div>
    <div class="ColumnFieldset">
        <fieldset class="Filter">
            <legend><?php echo smarty_function_localize(array('str' => 'Affiliate Status'), $this);?>
</legend>
            <?php echo "<div id=\"rstatus\"></div>"; ?>
        </fieldset>
    </div>
    
    <div class="ColumnFieldset">
        <fieldset class="Filter">
            <legend><?php echo smarty_function_localize(array('str' => 'Search Affiliate'), $this);?>
</legend>
            <?php echo "<div id=\"affiliateFilter\"></div>"; ?>
            <?php echo "<div id=\"onlyTopAffiliatesFilter\"></div>"; ?>
        </fieldset>
    </div>
    <div class="clear"></div>
    <div class="Label"><?php echo smarty_function_localize(array('str' => 'Display maximally 200 affiliates for each node.'), $this);?>
</div>
</div>