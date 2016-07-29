<!-- stats_chart -->

<div class="StatsChartHolder">
<table class="StatsChart">
<tr>
  <td class="TextAlignLeft" width="30%">
    <div style="width:225px;">{widget id="LabelFromTo"}</div></td>
  <td width="70%" class="TextAlignRight">
      <table>
        <tr>
            <td class="TextAlignRight" width="60" nowrap>##Chart:##</td>
            <td class="TextAlignLeft" width="150">{widget id="ChartType"}</td>
            <td class="TextAlignRight" width="70" nowrap>##Group by:##</td>
            <td class="TextAlignLeft" width="160">{widget id="GroupBy"}</td>
            <td class="TextAlignRight" width="60" nowrap>##Data:##</td>
            <td width="140" class="TextAlignLeft StatsDataType">{widget id="DataType"}</td>
        </tr>
      </table>
  </td>
</tr>
<tr>
  <td colspan="2" class="TextAlignLeft">{widget id="Chart"}</td>
</tr>
</table>
</div>
