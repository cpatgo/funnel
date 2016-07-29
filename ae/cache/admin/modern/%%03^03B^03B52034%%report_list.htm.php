<?php /* Smarty version 2.6.12, created on 2016-07-18 15:28:21
         compiled from report_list.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'adesk_calendar', 'report_list.htm', 2, false),array('function', 'adesk_js', 'report_list.htm', 3, false),array('function', 'adesk_amchart', 'report_list.htm', 93, false),array('function', 'adesk_headercol', 'report_list.htm', 136, false),array('modifier', 'alang', 'report_list.htm', 14, false),array('modifier', 'acpdate', 'report_list.htm', 40, false),)), $this); ?>
<?php echo smarty_function_adesk_calendar(array('base' => ".."), $this);?>

<?php echo smarty_function_adesk_js(array('lib' => "really/simplehistory.js"), $this);?>

<script type="text/javascript">
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "report_list.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "report_list.inc.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</script>

<?php if (! isset ( $_GET['print'] ) || $_GET['print'] == 0): ?>
<div style="float:right;">
  <div style="float:right; border:1px solid #CCCCCC; padding:5px; font-weight:bold; text-decoration:underline;">
	<a href="#" id="datetimelabel" onclick="adesk_dom_toggle_class('datetimefilter', 'adesk_block', 'adesk_hidden');return false;">
	  <?php if ($this->_tpl_vars['datefilter'] == 'today'): ?>
	  <?php echo ((is_array($_tmp='Today')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  <?php elseif ($this->_tpl_vars['datefilter'] == 'week'): ?>
	  <?php echo ((is_array($_tmp='This Week')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  <?php elseif ($this->_tpl_vars['datefilter'] == 'month'): ?>
	  <?php echo ((is_array($_tmp='This Month')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  <?php elseif ($this->_tpl_vars['datefilter'] == 'year'): ?>
	  <?php echo ((is_array($_tmp='This Year')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  <?php elseif ($this->_tpl_vars['datefilter'] == 'range'): ?>
	  <?php echo $this->_tpl_vars['datefrom']; ?>
-<?php echo $this->_tpl_vars['dateto']; ?>

	  	  <?php else: ?>
	  	  <?php echo ((is_array($_tmp='All Time')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  <?php endif; ?>
	</a>
	<div id="datetimefilter" class="adesk_hidden" style="position:absolute; background:#FFFFFF; border:1px solid #999999; padding:10px; margin-top:5px; margin-left:-6px;">
	  <select id="datetimeselect" name="datetimefilter" size="1" onchange="$('datetimerange').className=(this.value=='range'?'adesk_block':'adesk_hidden');">
		<option value="all"<?php if ($this->_tpl_vars['datefilter'] == 'all'): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp='All Time')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		<option value="today"<?php if ($this->_tpl_vars['datefilter'] == 'today'): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp='Today')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		<option value="week"<?php if ($this->_tpl_vars['datefilter'] == 'week'): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp='This Week')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		<option value="month"<?php if ($this->_tpl_vars['datefilter'] == 'month'): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp='This Month')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		<option value="year"<?php if ($this->_tpl_vars['datefilter'] == 'year'): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp='This Year')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		<option value="range"<?php if ($this->_tpl_vars['datefilter'] == 'range'): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp='Date Range')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
	  </select>
	  <div id="datetimerange" class="adesk_hidden" style="margin-top:10px;">
		<?php echo ((is_array($_tmp="From:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
		<input type="text" name="from" id="fromfilterField" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['datefrom'])) ? $this->_run_mod_handler('acpdate', true, $_tmp, $this->_tpl_vars['site']['dateformat']) : smarty_modifier_acpdate($_tmp, $this->_tpl_vars['site']['dateformat'])); ?>
" size="10" />
		<input id="fromfilterCalendar" type="button" value="<?php echo ((is_array($_tmp=" + ")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" />
		<br />
		<?php echo ((is_array($_tmp="To:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
		<input type="text" name="to" id="tofilterField" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['dateto'])) ? $this->_run_mod_handler('acpdate', true, $_tmp, $this->_tpl_vars['site']['dateformat']) : smarty_modifier_acpdate($_tmp, $this->_tpl_vars['site']['dateformat'])); ?>
" size="10" />
		<input id="tofilterCalendar" type="button" value="<?php echo ((is_array($_tmp=" + ")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" />
	  </div>
	  <div style="margin-top:10px;">
		<input name="Go" value="<?php echo ((is_array($_tmp='Go')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" type="button" onclick="report_list_filter_datetime();" />
	  </div>
	</div>
  </div>

  <div style="float:right; border:1px solid #ffffff; padding:5px;  margin-right:20px; font-weight:bold; text-decoration:underline;">
	<a href="#" onclick="report_list_export(); return false"><?php echo ((is_array($_tmp='Export')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
  </div>
  <div id="printbutton" style="float:right; padding:5px; font-weight:bold; text-decoration:underline; margin-right:10px;">
	<a href="#" onclick="report_list_print(); return false"><?php echo ((is_array($_tmp='Print')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
  </div>
</div>
<script>
  <?php echo '
  Calendar.setup({inputField: "fromfilterField", ifFormat: \'%Y/%m/%d\', button: "fromfilterCalendar", showsTime: false, timeFormat: "24"});
  Calendar.setup({inputField: "tofilterField", ifFormat: \'%Y/%m/%d\', button: "tofilterCalendar", showsTime: false, timeFormat: "24"});
  '; ?>

</script>
<?php endif; ?>

<?php if (! isset ( $_GET['print'] ) || $_GET['print'] == 0): ?>
<h3 class="m-b"><?php echo ((is_array($_tmp='List Sending Activity')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
<div style="margin-bottom:10px; margin-top:-4px; color:#999999;">
  <a href="desk.php?action=report_list"><?php echo ((is_array($_tmp='List Reports')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
  &gt;
  <a href="desk.php?action=report_list"><?php echo ((is_array($_tmp='Sending Activity')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
  <?php if ($this->_tpl_vars['list']): ?>
  &gt;
  <a href="desk.php?action=report_list&id=<?php echo $this->_tpl_vars['lid']; ?>
"><?php echo ((is_array($_tmp="List '%s'")) ? $this->_run_mod_handler('alang', true, $_tmp, $this->_tpl_vars['list']['name']) : smarty_modifier_alang($_tmp, $this->_tpl_vars['list']['name'])); ?>
</a>
  <?php endif; ?>
</div>
<?php endif; ?>

<div id="general" class="adesk_hidden">

  <div id="mainpanel">
	<div class="startup_box_container">
	  <div class="startup_box_title">
		<span id="general_subscribelabel" class="startup_selected"><a href="#" onclick="report_list_showdiv_general('chart_subscribed_bydate', 'general_subscribelabel');return false;"><?php echo ((is_array($_tmp='Subscribe Trend')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></span>
		<span id="general_unsubscribelabel"><a href="#" onclick="report_list_showdiv_general('chart_unsubscribed_bydate', 'general_unsubscribelabel');return false;"><?php echo ((is_array($_tmp='Unsubscribe Trend')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></span>
		<span id="general_readlabel"><a href="#" onclick="report_list_showdiv_general('chart_read_byhour', 'general_readlabel');return false;"><?php echo ((is_array($_tmp='Opens By Hour Trend')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></span>
	  </div>

	  <div class="startup_box_container_inner">
		<script type="text/javascript" src="../awebdesk/amline/swfobject.js"></script>
		<?php echo smarty_function_adesk_amchart(array('type' => 'line','divid' => 'chart_subscribed_bydate','location' => 'admin','url' => "graph.php?g=subscribed_bydate&id=".($this->_tpl_vars['lid'])."&mode=report_list&filterid=".($this->_tpl_vars['filterid']),'width' => "100%",'height' => '175','bgcolor' => "#FFFFFF"), $this);?>

		<?php echo smarty_function_adesk_amchart(array('type' => 'line','divid' => 'chart_unsubscribed_bydate','location' => 'admin','url' => "graph.php?g=unsubscribed_bydate&id=".($this->_tpl_vars['lid'])."&mode=report_list&filterid=".($this->_tpl_vars['filterid']),'width' => "100%",'height' => '175','bgcolor' => "#FFFFFF",'display' => false), $this);?>

		<?php echo smarty_function_adesk_amchart(array('type' => 'line','divid' => 'chart_read_byhour','location' => 'admin','url' => "graph.php?g=read_byhour&listid=".($this->_tpl_vars['lid'])."&mode=report_list&filterid=".($this->_tpl_vars['filterid']),'width' => "100%",'height' => '175','bgcolor' => "#FFFFFF",'display' => false), $this);?>

	  </div>

	</div>

  </div>

  <br />

  <input type="hidden" name="lid" value="<?php echo $this->_tpl_vars['lid']; ?>
" id="list_id" />

  <form action="desk.php?action=report_list<?php if ($this->_tpl_vars['list']): ?>&id=<?php echo $this->_tpl_vars['lid'];  endif; ?>" method="GET" onsubmit="report_list_list_search(); return false">
	<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  cellspacing="0" cellpadding="0" width="100%">
	  <tr class="adesk_table_header_options">
<?php if ($this->_tpl_vars['list']): ?>
		<td>
		  <input type="button" value='<?php echo ((is_array($_tmp="&laquo; Back")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="window.location.href='desk.php?action=report_list';" />
		</td>
<?php endif; ?>
		<td align="right">
		  <div>
			<input type="text" name="qsearch" id="list_search" />
			<input type="button" value='<?php echo ((is_array($_tmp='Search')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="report_list_list_search()" />
			<input type="button" value='<?php echo ((is_array($_tmp='Clear')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' id="list_clear" style="display:none" onclick="report_list_list_clear()" />
		  </div>
		</td>
	  </tr>
	</table></div>
	<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  width="100%" border="0" cellspacing="0" cellpadding="1">
	  <thead id="list_head">
		<tr class="adesk_table_header">
		  		  <td><?php echo smarty_function_adesk_headercol(array('action' => 'report_list','id' => '01','label' => ((is_array($_tmp='List')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
		  <td width="100" align="center"><?php echo smarty_function_adesk_headercol(array('action' => 'report_list','id' => '02','label' => ((is_array($_tmp='Confirmed Subscribers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
		  <td width="100" align="center"><?php echo smarty_function_adesk_headercol(array('action' => 'report_list','id' => '03','label' => ((is_array($_tmp='Unconfirmed Subscribers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
		  <td width="100" align="center"><?php echo smarty_function_adesk_headercol(array('action' => 'report_list','id' => '04','label' => ((is_array($_tmp='Unsubscribed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
		  <td width="100" align="center"><?php echo smarty_function_adesk_headercol(array('action' => 'report_list','id' => '05','label' => ((is_array($_tmp='Bounces')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
		  		  <td width="100" align="center"><?php echo smarty_function_adesk_headercol(array('action' => 'report_list','id' => '07','label' => ((is_array($_tmp='Total Emails Sent')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
		  <td width="100" align="center"><?php echo smarty_function_adesk_headercol(array('action' => 'report_list','id' => '08','label' => ((is_array($_tmp="Avg. Sent Per Day")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
		  		</tr>
	  </thead>
	  <tbody id="list_table">
	  </tbody>
	</table></div>
	<div id="list_noresults" class="adesk_hidden">
	  <div align="center"><?php echo ((is_array($_tmp="Nothing found.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
	</div>
	<div style="float:right">
	  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "pagination.js.tpl.htm", 'smarty_include_vars' => array('tabelize' => 'report_list_list_tabelize','paginate' => 'report_list_list_paginate')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</div>
	<div id="loadingBar" class="adesk_hidden" style="background: url(../awebdesk/media/loader.gif); background-repeat: no-repeat; padding: 5px; padding-left: 20px; padding-top: 2px; color: #999999; font-size: 10px; margin: 5px">
	  <?php echo ((is_array($_tmp="Loading. Please wait...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	</div>
  </form>

</div>

<script type="text/javascript">
  adesk_ui_rsh_init(report_list_process, true);
</script>