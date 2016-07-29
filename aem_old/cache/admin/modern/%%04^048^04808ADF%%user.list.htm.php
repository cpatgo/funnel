<?php /* Smarty version 2.6.12, created on 2016-07-08 14:11:34
         compiled from user.list.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'user.list.htm', 9, false),array('modifier', 'escape', 'user.list.htm', 41, false),array('function', 'adesk_sortcol', 'user.list.htm', 66, false),)), $this); ?>
<?php $_from = $this->_tpl_vars['admin']['groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['g']):
?>
<?php if ($this->_tpl_vars['g'] == @adesk_GROUP_ADMIN): ?>
<?php $this->assign('showgroupdropdown', '1'); ?>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>

<div id="export" class="adesk_modal" style="display:none">
  <div class="adesk_modal_inner">
  	<h3 class="m-b"><?php echo ((is_array($_tmp='Export')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
	<input type="checkbox" id="export_user" name="export_user" value="1" checked/>  <?php echo ((is_array($_tmp='User')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br/>
	<input type="checkbox" id="export_name" name=export_name" value="1" checked/>  <?php echo ((is_array($_tmp='Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br/>
	<input type="checkbox" id="export_email" name="export_email" value="1" checked/> <?php echo ((is_array($_tmp='Email')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br/>
    <br/>
    <div>
      <input type="button" value='<?php echo ((is_array($_tmp='Export')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="user_export()" class="adesk_button_ok"/>
      <input type="button" value='<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="$('export').style.display = 'none'"/>
    </div>
  </div>
</div>

<div id="list" class="adesk_hidden">
  <?php if (isset ( $this->_tpl_vars['user_header_file'] )): ?>
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['user_header_file'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif; ?>

  <form action="desk.php?action=user" method="GET" onsubmit="user_list_search(); return false">
	<div class=" table-responsive"><table cellspacing="0" cellpadding="0" width="100%" class="table table-striped m-b-none dataTable">
	  <tr class="adesk_table_header_options">
		<td>
		  <div style="float:right">
			  <input type="text" name="qsearch" id="list_search" />
			  <input type="button" value='<?php echo ((is_array($_tmp='Search')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="user_list_search()" />
			  <input type="button" value='<?php echo ((is_array($_tmp='Clear')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' id="list_clear" style="display:none" onclick="user_list_clear()" />
			  &nbsp;<a href="#search" style="display:inline;font-size:10px"><?php echo ((is_array($_tmp='Advanced Search')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
		  </div>
		  <div>
			<?php if (isset ( $this->_tpl_vars['showgroupdropdown'] )): ?>
			<select name="search_group" id="list_search_group" onchange="if (typeof user_list_search_onchange_extended == 'function') user_list_search_onchange_extended(this.value)">
			  <option value="0"><?php echo ((is_array($_tmp='All Groups')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
			  <?php $_from = $this->_tpl_vars['fgroups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['g']):
?>
			  <option value="<?php echo $this->_tpl_vars['g']['id']; ?>
">-- <?php echo ((is_array($_tmp=$this->_tpl_vars['g']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</option>
			  <?php endforeach; endif; unset($_from); ?>
			  <?php if (isset ( $this->_tpl_vars['user_group_dropdown_include'] )): ?>
			  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['user_group_dropdown_include'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			  <?php endif; ?>
			</select>
			<?php if (isset ( $this->_tpl_vars['user_list_aftergroups'] )): ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['user_list_aftergroups'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			<?php endif; ?>
			<input type="button" value='<?php echo ((is_array($_tmp='Filter')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="user_list_search()" />
			<?php else: ?>
			<input name="search_group" id="list_search_group" type="hidden" value="0">
			<?php endif; ?>
		  </div>
		</td>
	  </tr>
	</table></div>
    <div class=" table-responsive">
	<table width="100%" border="0" cellspacing="0" cellpadding="1" class="table table-striped m-b-none dataTable">
	  <thead id="list_head">
		<tr class="adesk_table_header">
		  <td align="center" width="20">
			<input id="acSelectAllCheckbox" type="checkbox" value="multi[]" onclick="adesk_form_check_selection_all(this, $('selectXPageAllBox'))" />
		  </td>
		  <td width="60"><?php echo ((is_array($_tmp='Options')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td><?php echo smarty_function_adesk_sortcol(array('action' => 'user','id' => '01','label' => ((is_array($_tmp='User')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
		  <td><?php echo smarty_function_adesk_sortcol(array('action' => 'user','id' => '02','label' => ((is_array($_tmp='Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
		  <td><?php echo smarty_function_adesk_sortcol(array('action' => 'user','id' => '03','label' => ((is_array($_tmp='Email')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
		  <td><?php echo ((is_array($_tmp='Groups')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		</tr>
	  </thead>
	  <tbody id="list_table">
	  </tbody>
	  <tbody id="list_noresults" class="adesk_hidden">
		<tr>
		  <td colspan="4" align="center">
			<div><?php echo ((is_array($_tmp="Nothing found.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
		  </td>
		</tr>
	  </tbody>
	  <tfoot>
		<td colspan="4" align="left">
		  <div id="loadingBar" class="adesk_hidden" style="background:url(../awebdesk/media/loader.gif); background-repeat:no-repeat; padding:5px; padding-left:20px; padding-top:2px; color:#999999; font-size:10px; margin:5px;">
			<?php echo ((is_array($_tmp="Loading. Please wait...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		  </div>
          <span id="selectXPageAllBox" class="adesk_hidden">
            <span class="adesk_hidden"><?php echo ((is_array($_tmp="All items are now selected.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
            <span class="adesk_hidden"><?php echo ((is_array($_tmp="All items on this page are now selected.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
            <a class="adesk_hidden" href="#" onclick="return adesk_form_check_selection_xpage(this.parentNode);"><?php echo ((is_array($_tmp="Click here to select all items.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
          </span>
		</td>
		<td align="right">&nbsp;
		  
		</td>
	  </tfoot>
	</table>
</div>
  </form>

	<div style="float:right;"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "pagination.js.tpl.htm", 'smarty_include_vars' => array('tabelize' => 'user_list_tabelize','paginate' => 'user_list_paginate')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
	<?php if ($this->_tpl_vars['_user_can_add'] && ! $this->_tpl_vars['demoMode']): ?>
	<span id="list_addspan" <?php if (isset ( $this->_tpl_vars['site']['adminsLeft'] ) && $this->_tpl_vars['site']['adminsLeft'] < 1): ?>style="display:none"<?php endif; ?>>
	  <input type="button" value='<?php echo ((is_array($_tmp='Add User')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="if (typeof user_form_addbutton_extended == 'function') user_form_addbutton_extended(); else adesk_ui_anchor_set('form-0')">
	</span>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['_user_can_delete'] && ! $this->_tpl_vars['demoMode']): ?>
	<input type="button" value="<?php echo ((is_array($_tmp='Delete Selected')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="adesk_ui_anchor_set('delete_multi')" />
	<?php endif; ?>
	<input type="button" value='<?php echo ((is_array($_tmp='Export')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="$('export').style.display = ''" style="margin-left: 20px" />
	<span <?php if (isset ( $this->_tpl_vars['global_count'] ) && $this->_tpl_vars['global_count'] > 0): ?> style="display: inline" <?php else: ?> style="display:none" <?php endif; ?>>
	  <?php if ($this->_tpl_vars['_user_can_add']): ?>
	  &nbsp;&nbsp;
	  <input type="button" value="<?php echo ((is_array($_tmp='Import a Global User')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick='paginators[2].paginate(0); adesk_dom_toggle_display("global", "block")' />
	  <?php endif; ?>
	</span>
</div>