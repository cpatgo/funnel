<!--    rule_conditions_panel_full      -->

<fieldset>
    <legend>##Conditions##</legend>
    <table>
        <tr>
            <td width="45">##If##</td>
            <td width="170">{widget id="what" class="ConditionListBox"}</td>
            <td width="45">{widget id="tierLabel"}</td>
            <td>{widget id="tier" class="ConditionValue"}</td>
            <td></td>
        </tr>
        <tr>
            <td>##that are##</td>
            <td>{widget id="status" class="ConditionListBox"}</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>##in##</td>
            <td>{widget id="date" class="ConditionListBox"}</td>
            <td></td>
            <td>{widget id="since" class="ConditionValue"}</td>
            <td></td>
        </tr>
        <tr>
            <td>##is##</td>
            <td>{widget id="equation" class="ConditionListBox"}</td>
            <td></td>
            <td class="EquationColumn" >{widget id="equationvalue1" class="ConditionValue"}</td>
            <td class="EquationColumn" >{widget id="equationvalue2" class="ConditionValue"}</td>
        </tr>
        <tr>
            <td colspan="5">{widget id="computeallcampaigns"}</td>
        </tr>
    </table>
</fieldset>
