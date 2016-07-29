<?php /* Smarty version 2.6.12, created on 2016-07-08 14:48:21
         compiled from report_campaign_forward.inc.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'report_campaign_forward.inc.htm', 2, false),array('function', 'adesk_headercol', 'report_campaign_forward.inc.htm', 46, false),)), $this); ?>
<div id="forward" class="adesk_hidden">
  <div class="adesk_help_inline"><?php echo ((is_array($_tmp="Forwards are only tracked when your subscribers forward the email by clicking on a Forward link you put in your email message.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>

  <div class="startup_box_container">
	<div class="startup_box_container_inner">
	  <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="0">
		<tr>
		  <td style="font-size:19px;"><span id="forward_total_t">0</span></td>
		  <td width="10">&nbsp;</td>
		  <td><?php echo ((is_array($_tmp='Total Forwards')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <span id="forward_total_p" style="color:#999999;">(0.00%)</span></td>
		  <td width="15">&nbsp;</td>
		  <td style="font-size:19px;"><span id="forward_unique_t">0</span></td>
		  <td width="10">&nbsp;</td>
		  <td><?php echo ((is_array($_tmp='Unique Forwards')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <span id="forward_unique_p" style="color:#999999;">(0.00%)</span> </td>
		  <td width="15">&nbsp;</td>
		  <td style="font-size:19px;"><span id="forward_didnt_t">0</span></td>
		  <td width="10">&nbsp;</td>
		  <td><?php echo ((is_array($_tmp="Didn't Forward")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <span id="forward_didnt_p" style="color:#999999;">(0.00%)</span></td>
		  <td width="15">&nbsp;</td>
		  <td style="font-size:19px;"><span id="forward_avg">0.00</span></td>
		  <td width="10">&nbsp;</td>
		  <td><?php echo ((is_array($_tmp="Avg. Recipients Per Forward")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		</tr>
	  </table></div>
	</div>
  </div>
  <br />

  <form action="desk.php?action=report_campaign" method="GET" onsubmit="forward_list_search(); return false">
	<?php if (isset ( $_GET['print'] ) && $_GET['print'] == 1): ?> <div style="display:none"> <?php endif; ?>
	  <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  cellspacing="0" cellpadding="0" width="100%">
		<tr class="adesk_table_header_options">
		  <td align="right">
			<div>
			  <input type="text" name="qsearch" id="forward_search" />
			  <input type="button" value='<?php echo ((is_array($_tmp='Search')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="forward_list_search()" />
			  <input type="button" value='<?php echo ((is_array($_tmp='Clear')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' id="forward_clear" style="display:none" onclick="forward_list_clear()" />
			</div>
		  </td>
		</tr>
	  </table></div>
	  <?php if (isset ( $_GET['print'] ) && $_GET['print'] == 1): ?> </div> <?php endif; ?>
	<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  width="100%" border="0" cellspacing="0" cellpadding="1">
	  <thead id="forward_head">
		<tr class="adesk_table_header">
		  <td><?php echo smarty_function_adesk_headercol(array('action' => 'forward','idprefix' => 'forward_list_sorter','id' => '01','label' => ((is_array($_tmp='Email')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
		  <td><?php echo smarty_function_adesk_headercol(array('action' => 'forward','idprefix' => 'forward_list_sorter','id' => '02','label' => ((is_array($_tmp='Date')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
		  <td><?php echo smarty_function_adesk_headercol(array('action' => 'forward','idprefix' => 'forward_list_sorter','id' => '03','label' => ((is_array($_tmp="# Times")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
		  <td><?php echo ((is_array($_tmp='Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		</tr>
	  </thead>
	  <tbody id="forward_table">
	  </tbody>
	</table></div>
	<div id="forward_noresults" class="adesk_hidden">
	  <div align="center"><?php echo ((is_array($_tmp="Nothing found.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
	</div>
	<div style="float:right">
	  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "pagination.js.tpl.htm", 'smarty_include_vars' => array('paginator' => $this->_tpl_vars['paginator_forward'],'tabelize' => 'forward_tabelize','paginate' => 'forward_paginate')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</div>
	<div id="forward_loadingBar" class="adesk_hidden" style="background: url(../awebdesk/media/loader.gif); background-repeat: no-repeat; padding: 5px; padding-left: 20px; padding-top: 2px; color: #999999; font-size: 10px; margin: 5px">
	  <?php echo ((is_array($_tmp="Loading. Please wait...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	</div>
  </form>
</div>