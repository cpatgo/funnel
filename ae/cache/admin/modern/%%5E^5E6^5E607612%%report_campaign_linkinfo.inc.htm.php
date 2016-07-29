<?php /* Smarty version 2.6.12, created on 2016-07-08 14:48:21
         compiled from report_campaign_linkinfo.inc.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'report_campaign_linkinfo.inc.htm', 8, false),array('function', 'adesk_headercol', 'report_campaign_linkinfo.inc.htm', 49, false),)), $this); ?>
<div id="linkinfo" class="adesk_hidden">
  <div class="startup_box_container">
	<div class="startup_box_container_inner">
	  <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="0">
		<tr>
		  <td style="font-size:19px;"><span id="linkinfo_total_t">0</span></td>
		  <td width="10">&nbsp;</td>
		  <td><?php echo ((is_array($_tmp='Total Clicks')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td width="35">&nbsp;</td>
		  <td style="font-size:19px;"><span id="linkinfo_unique_t">0</span></td>
		  <td width="10">&nbsp;</td>
		  <td><?php echo ((is_array($_tmp='Unique Clicks')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td width="35">&nbsp;</td>
		  <td style="font-size:19px;"><span id="linkinfo_didnt_t">0</span></td>
		  <td width="10">&nbsp;</td>
		  <td><?php echo ((is_array($_tmp="Didn't Click")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <span id="linkinfo_didnt_p" style="color:#999999;">(0.00%)</span></td>
		  <td width="35">&nbsp;</td>
		  <td style="font-size:19px;"><span id="linkinfo_avg">0.00</span></td>
		  <td width="10">&nbsp;</td>
		  <td><?php echo ((is_array($_tmp="Avg. Clicks")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		</tr>
	  </table></div>
	</div>
  </div>
  <br />

  <div id="linkinfo_paginator">
	<form action="desk.php?action=report_campaign" method="GET" onsubmit="linkinfo_list_search(); return false">
	  <?php if (isset ( $_GET['print'] ) && $_GET['print'] == 1): ?> <div style="display:none"> <?php endif; ?>
		<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  cellspacing="0" cellpadding="0" width="100%">
		  <tr class="adesk_table_header_options">
			<td>
			  <span id="linkinfo_name"></span>
			  <a href="#" onclick="report_campaign_list_mode = 'link'; report_campaign_list_offset = link_offset_was; adesk_ui_anchor_set(report_campaign_list_anchor()); return false">&laquo; <?php echo ((is_array($_tmp='Back')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
			</td>
			<td align="right">
			  <div>
				<input type="text" name="qsearch" id="linkinfo_search" />
				<input type="button" value='<?php echo ((is_array($_tmp='Search')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="linkinfo_list_search()" />
				<input type="button" value='<?php echo ((is_array($_tmp='Clear')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' id="linkinfo_clear" style="display:none" onclick="linkinfo_list_clear()" />
			  </div>
			</td>
		  </tr>
		</table></div>
		<?php if (isset ( $_GET['print'] ) && $_GET['print'] == 1): ?> </div> <?php endif; ?>
	  <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  width="100%" border="0" cellspacing="0" cellpadding="1">
		<thead id="linkinfo_head">
		  <tr class="adesk_table_header">
			<td><?php echo smarty_function_adesk_headercol(array('action' => 'linkinfo','idprefix' => 'linkinfo_list_sorter','id' => '01','label' => ((is_array($_tmp='Email')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
			<td><?php echo smarty_function_adesk_headercol(array('action' => 'linkinfo','idprefix' => 'linkinfo_list_sorter','id' => '02','label' => ((is_array($_tmp='Date')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
			<td><?php echo smarty_function_adesk_headercol(array('action' => 'linkinfo','idprefix' => 'linkinfo_list_sorter','id' => '03','label' => ((is_array($_tmp="# Times")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
		  </tr>
		</thead>
		<tbody id="linkinfo_table">
		</tbody>
	  </table></div>
	  <div id="linkinfo_noresults" class="adesk_hidden">
		<div align="center"><?php echo ((is_array($_tmp="Nothing found.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
	  </div>
	  <div style="float:right">
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "pagination.js.tpl.htm", 'smarty_include_vars' => array('paginator' => $this->_tpl_vars['paginator_linkinfo'],'tabelize' => 'linkinfo_tabelize','paginate' => 'linkinfo_paginate')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	  </div>
	  <div id="linkinfo_loadingBar" class="adesk_hidden" style="background: url(../awebdesk/media/loader.gif); background-repeat: no-repeat; padding: 5px; padding-left: 20px; padding-top: 2px; color: #999999; font-size: 10px; margin: 5px">
		<?php echo ((is_array($_tmp="Loading. Please wait...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  </div>
	</form>
  </div>
</div>