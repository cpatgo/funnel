<!--    rule_conditions_panel      -->

<fieldset>
    <legend>##Conditions##</legend>
    <table>
        <tr>
            <td>##If##</td>
            <td width="170">{widget id="what" class="ConditionListBox"}</td>
            <td width="45">##that are##</td>
            <td>{widget id="status" class="ConditionListBox"}</td>
            <td></td>
        </tr>
        <tr>
            <td>##is##</td>
            <td>{widget id="equation" class="ConditionListBox"}</td>
            <td></td>
            <td>{widget id="equationvalue1" class="ConditionValue"}</td>
            <td>{widget id="equationvalue2" class="ConditionValue"}</td>
        </tr>
        <tr>
            <td colspan="5">
                <fieldset class="OptionalCondition">
                <legend>##Optional##</legend>
                <table>
                    <tr>
                        <td colspan="1">##And##</td>
                        <td>{widget id="dataField" class="ConditionListBox"}</td>
                        <td>{widget id="dataFieldEquation" class="ConditionListBox"}</td>
                        <td>{widget id="dataFieldValue"}</td>
                    </tr>
                </table>
                </fieldset>
            </td>
        </tr>
        <tr>
            <td colspan="5">##in time period of recurrence##</td>
        </tr>
    </table>
</fieldset>
