<?php /* Smarty version 2.6.18, created on 2016-07-06 14:13:36
         compiled from campaign_rule_conditions_panel.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'campaign_rule_conditions_panel.tpl', 5, false),)), $this); ?>
<!--    campaign_rule_conditions_panel      -->

    <table>
        <tr>
            <td class="EditRuleColumnFirst"><?php echo smarty_function_localize(array('str' => 'If'), $this);?>
</td>
            <td class="EditRuleColumn"><?php echo "<div id=\"stats_what\"></div>"; ?></td>
        </tr>
    </table>
    <?php echo "<div id=\"timeConditions\"></div>"; ?>
    <?php echo "<div id=\"statisticsConditions\"></div>"; ?>