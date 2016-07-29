<?php /* Smarty version 2.6.18, created on 2016-07-06 14:15:21
         compiled from stats_chart.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'stats_chart.tpl', 11, false),)), $this); ?>
<!-- stats_chart -->

<div class="StatsChartHolder">
<table class="StatsChart">
<tr>
  <td class="TextAlignLeft" width="30%">
    <div style="width:225px;"><?php echo "<div id=\"LabelFromTo\"></div>"; ?></div></td>
  <td width="70%" class="TextAlignRight">
      <table>
        <tr>
            <td class="TextAlignRight" width="60" nowrap><?php echo smarty_function_localize(array('str' => 'Chart:'), $this);?>
</td>
            <td class="TextAlignLeft" width="150"><?php echo "<div id=\"ChartType\"></div>"; ?></td>
            <td class="TextAlignRight" width="70" nowrap><?php echo smarty_function_localize(array('str' => 'Group by:'), $this);?>
</td>
            <td class="TextAlignLeft" width="160"><?php echo "<div id=\"GroupBy\"></div>"; ?></td>
            <td class="TextAlignRight" width="60" nowrap><?php echo smarty_function_localize(array('str' => 'Data:'), $this);?>
</td>
            <td width="140" class="TextAlignLeft StatsDataType"><?php echo "<div id=\"DataType\"></div>"; ?></td>
        </tr>
      </table>
  </td>
</tr>
<tr>
  <td colspan="2" class="TextAlignLeft"><?php echo "<div id=\"Chart\"></div>"; ?></td>
</tr>
</table>
</div>