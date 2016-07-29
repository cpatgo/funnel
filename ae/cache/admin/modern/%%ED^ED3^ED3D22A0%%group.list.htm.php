<?php /* Smarty version 2.6.12, created on 2016-07-08 14:11:45
         compiled from group.list.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'group.list.htm', 8, false),array('function', 'adesk_sortcol', 'group.list.htm', 22, false),)), $this); ?>
<div id="list" class="adesk_hidden">
  <form action="desk.php?action=group" method="GET" onsubmit="group_list_search(); return false">
   <div class=" table-responsive"> <table cellspacing="0" cellpadding="0" width="100%" class="table table-striped m-b-none dataTable">
      <tr class="adesk_table_header_options">
        <td align="right">
          <div>
            <input type="text" name="qsearch" id="list_search" />
            <input type="button" value='<?php echo ((is_array($_tmp='Search')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="group_list_search()" />
            <input type="button" value='<?php echo ((is_array($_tmp='Clear')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' id="list_clear" style="display:none" onclick="group_list_clear()" />
            &nbsp;<a href="#search" style="display:inline;font-size:10px"><?php echo ((is_array($_tmp='Advanced Search')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
          </div>
        </td>
      </tr>
    </table></div>
    <div class=" table-responsive"> <table width="100%" border="0" cellspacing="0" cellpadding="1" class="table table-striped m-b-none dataTable">
      <thead id="list_head">
        <tr class="adesk_table_header">
          <td align="center" width="20">
            <input id="acSelectAllCheckbox" type="checkbox" value="multi[]" onclick="adesk_form_check_selection_all(this, $('selectXPageAllBox'))" />
          </td>
          <td width="50"><?php echo ((is_array($_tmp='Options')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td><?php echo smarty_function_adesk_sortcol(array('action' => 'group','id' => '01','label' => ((is_array($_tmp='Title')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
		</tr>
      </thead>
      <tbody id="list_table">
      </tbody>
      <tbody id="list_noresults" class="adesk_hidden">
        <tr>
          <td colspan="2" align="center">
            <div><?php echo ((is_array($_tmp="Nothing found.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
          </td>
        </tr>
      </tbody>
      <tfoot>
        <td colspan="2" align="left">
          <div id="loadingBar" class="adesk_hidden" style="background: url(../awebdesk/media/loader.gif); background-repeat: no-repeat; padding: 5px; padding-left: 20px; padding-top: 2px; color: #999999; font-size: 10px; margin: 5px">
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
	  <div style="float:right;"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "pagination.js.tpl.htm", 'smarty_include_vars' => array('tabelize' => 'group_list_tabelize','paginate' => 'group_list_paginate')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>

  </form>


  <?php if ($this->_tpl_vars['_group_can_add'] && ! $this->_tpl_vars['demoMode']): ?>
  <input type="button" value="<?php echo ((is_array($_tmp='Add')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="adesk_ui_anchor_set('form-0')" />
  <?php endif; ?>
  <?php if ($this->_tpl_vars['_group_can_delete'] && ! $this->_tpl_vars['demoMode']): ?>
  <input type="button" value="<?php echo ((is_array($_tmp='Delete Selected')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="adesk_ui_anchor_set('delete_multi')" />
  <?php endif; ?>
</div>