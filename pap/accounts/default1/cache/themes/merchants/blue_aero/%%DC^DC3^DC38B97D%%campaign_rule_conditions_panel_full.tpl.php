<?php /* Smarty version 2.6.18, created on 2016-07-06 14:13:36
         compiled from campaign_rule_conditions_panel_full.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'campaign_rule_conditions_panel_full.tpl', 5, false),)), $this); ?>
<!--    campaign_rule_conditions_panel_full      -->

    <table>
        <tr>
            <td class="EditRuleColumnFirst"><?php echo smarty_function_localize(array('str' => 'with status'), $this);?>
</td>
            <td class="EditRuleColumn"><?php echo "<div id=\"status\"></div>"; ?></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td class="EditRuleColumnFirst"><?php echo smarty_function_localize(array('str' => 'in'), $this);?>
</td>
            <td class="EditRuleColumn"><?php echo "<div id=\"stats_date\"></div>"; ?></td>
            <td class="EditRuleColumn"><?php echo "<div id=\"stats_since\"></div>"; ?></td>
            <td class="EditRuleColumn"></td>
        </tr>
        <tr>
            <td class="EditRuleColumnFirst"><?php echo smarty_function_localize(array('str' => 'is'), $this);?>
</td>
            <td class="EditRuleColumn"><?php echo "<div id=\"equation\"></div>"; ?></td>
            <td class="EditRuleColumn" style="margin-bottom:4px"><?php echo "<div id=\"equationvalue1\"></div>"; ?></td>
            <td class="EditRuleColumn" style="margin-bottom:4px"><?php echo "<div id=\"equationvalue2\"></div>"; ?></td>
        </tr>
    </table>