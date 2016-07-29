<?php /* Smarty version 2.6.18, created on 2016-07-06 14:13:36
         compiled from campaign_scheduler.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'campaign_scheduler.tpl', 3, false),)), $this); ?>
<!--    campaign_scheduler     -->
<!--<fieldset>-->
<br><h3><?php echo smarty_function_localize(array('str' => 'Status Scheduler'), $this);?>
</h3>
<!--    <legend><?php echo smarty_function_localize(array('str' => 'Status Scheduler'), $this);?>
</legend>-->
    <?php echo smarty_function_localize(array('str' => 'Change status of campaign with custom rules'), $this);?>

    <?php echo "<div id=\"grid\"></div>"; ?>
<!--</fieldset>-->