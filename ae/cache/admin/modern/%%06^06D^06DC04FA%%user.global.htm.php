<?php /* Smarty version 2.6.12, created on 2016-07-08 14:11:34
         compiled from user.global.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'user.global.htm', 3, false),)), $this); ?>
<div id="global" align="center" class="adesk_modal" style="display:none">
  <div class="adesk_modal_inner_global_users" style="overflow: auto">
  	<h3 class="m-b"><?php echo ((is_array($_tmp='Global Users')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
	<div class=" table-responsive"><table width="100%" border="0" cellspacing="0" cellpadding="1" class="table table-striped m-b-none dataTable">
	  <thead>
		<tr class="adesk_table_header">
		  <td width="75"><?php echo ((is_array($_tmp='Options')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td><?php echo ((is_array($_tmp='Username')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td><?php echo ((is_array($_tmp='Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td><?php echo ((is_array($_tmp='Email')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		</tr>
	  </thead>
	  <tbody id="user_global_table">
	  </tbody>
	  <tfoot>
		<td align="left">
		  </div>
		</td>
		<td colspan="5" align="right">&nbsp;
		</td>
	  </tfoot>
	</table></div>
	<div style="float:right;"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "pagination.js.tpl.htm", 'smarty_include_vars' => array('paginator' => $this->_tpl_vars['global_paginator'],'tabelize' => 'user_global_tabelize','paginate' => 'user_global_list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
	<input type="button" value='<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick='adesk_dom_toggle_display("global", "block")' />
  </div>
</div>