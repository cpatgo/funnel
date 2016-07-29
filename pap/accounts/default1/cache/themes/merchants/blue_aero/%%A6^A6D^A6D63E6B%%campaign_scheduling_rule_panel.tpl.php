<?php /* Smarty version 2.6.18, created on 2016-07-06 14:13:36
         compiled from campaign_scheduling_rule_panel.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'campaign_scheduling_rule_panel.tpl', 15, false),)), $this); ?>
<!--    campaign_scheduling_rule_panel      -->

<div class="ScreenHeader RuleViewHeader">
    <div class="ScreenTitle">
        <?php echo "<div id=\"screenTitle\"></div>"; ?>
    </div>
    <div class="ScreenDescription">
       <?php echo "<div id=\"screenDescription\"></div>"; ?>
    </div>
    <div class="clear"/>
</div>


<fieldset class="EditRuleFieldset">
    <legend><?php echo smarty_function_localize(array('str' => 'Actions'), $this);?>
</legend>
    <table>
        <tr>
            <td class="EditRuleColumnFirst"><?php echo smarty_function_localize(array('str' => 'Change status of campaign to'), $this);?>
</td>
            <td class="EditRuleColumn"><?php echo "<div id=\"status_to\"></div>"; ?></td>
        </tr>
    </table>
	<?php echo "<div id=\"ruleConditions\"></div>"; ?>
</fieldset>


<?php echo "<div id=\"formmessage\"></div>"; ?>
<?php echo "<div id=\"sendButton\"></div>"; ?>
<?php echo "<div id=\"cancelButton\"></div>"; ?>