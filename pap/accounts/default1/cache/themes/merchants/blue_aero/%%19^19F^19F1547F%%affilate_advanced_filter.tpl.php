<?php /* Smarty version 2.6.18, created on 2016-07-06 14:13:02
         compiled from affilate_advanced_filter.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'affilate_advanced_filter.tpl', 6, false),)), $this); ?>
<!-- affilate_advanced_filter -->

			<div class="AffiliatesFilter">
			
		    <fieldset class="Filter">       
            <legend><?php echo smarty_function_localize(array('str' => 'Date joined'), $this);?>
</legend>
            <div class="Resize">
            <?php echo "<div id=\"dateinserted\"></div>"; ?>
            </div>
       		</fieldset>
        
        	<fieldset class="Filter">
            <legend><?php echo smarty_function_localize(array('str' => 'Affiliate status'), $this);?>
</legend>
            <div class="Resize">
            <?php echo "<div id=\"rstatus\"></div>"; ?>
            </div>
       		</fieldset>

        	<fieldset class="Filter">
            <legend><?php echo smarty_function_localize(array('str' => 'Custom filter'), $this);?>
</legend>
            <div class="Resize">
            <?php echo "<div id=\"custom\"></div>"; ?>
            </div>
       		</fieldset>

       		</div>
        
      		<div style="clear: both;"></div>