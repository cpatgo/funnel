<?php /* Smarty version 2.6.18, created on 2016-07-06 14:14:58
         compiled from payouts_to_affiliates_filter.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'payouts_to_affiliates_filter.tpl', 6, false),)), $this); ?>
<!-- payouts_to_affiliates_filter -->

			<div class="PayoutsByAffiliatesFilter">
		    
       			<fieldset class="Filter">
                    <legend><?php echo smarty_function_localize(array('str' => 'Payment date'), $this);?>
</legend>
                    <div class="Resize">
                        <?php echo "<div id=\"dateinserted\"></div>"; ?>
                    </div>
                </fieldset>

                <fieldset class="Filter">
                    <legend><?php echo smarty_function_localize(array('str' => 'Custom'), $this);?>
</legend>
                    <div class="Resize">
                        <?php echo "<div id=\"customData\"></div>"; ?>
                    </div>
                </fieldset>
       		</div>
        
       		<div style="clear: both;"></div>