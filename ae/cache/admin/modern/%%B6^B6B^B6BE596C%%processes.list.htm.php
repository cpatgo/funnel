<?php /* Smarty version 2.6.12, created on 2016-07-08 14:41:08
         compiled from processes.list.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'processes.list.htm', 7, false),array('function', 'html_options', 'processes.list.htm', 8, false),array('function', 'adesk_headercol', 'processes.list.htm', 35, false),)), $this); ?>
<div id="list" class="adesk_hidden">
  <form action="desk.php?action=processes" method="GET" onsubmit="processes_list_search(); return false">
   <div class=" table-responsive"> <table cellspacing="0" cellpadding="0" width="100%" class="table table-striped m-b-none dataTable">
      <tr class="adesk_table_header_options">
        <td>
          <select name="action" id="JSActionManager" size="1" onchange="processes_list_search()">
            <option value=""><?php echo ((is_array($_tmp="Process Filter...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
            <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['actions']), $this);?>

          </select>
          <select name="status" id="JSStatusManager" size="1" onchange="processes_list_search()">
            <option value="active"><?php echo ((is_array($_tmp='Running')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
            <option value="stall"><?php echo ((is_array($_tmp='Stalled')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
            <option value="paused"><?php echo ((is_array($_tmp='Paused')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
            <option value="done"><?php echo ((is_array($_tmp='Completed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
            <option value=""><?php echo ((is_array($_tmp='All Statuses')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
          </select>
        </td>
        <td align="right">
          <div>
            <input type="text" name="qsearch" id="list_search" />
            <input type="button" value='<?php echo ((is_array($_tmp='Search')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="processes_list_search()" />
            <input type="button" value='<?php echo ((is_array($_tmp='Clear')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' id="list_clear" style="display:none" onclick="processes_list_clear()" />
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
          <td><?php echo smarty_function_adesk_headercol(array('action' => 'processes','id' => '01','label' => ((is_array($_tmp='Process Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
          <td width="150"><?php echo smarty_function_adesk_headercol(array('action' => 'processes','id' => '02','label' => ((is_array($_tmp='Last Update')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
          <td width="100"><?php echo ((is_array($_tmp='Progress')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
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
$this->_smarty_include(array('smarty_include_tpl_file' => "pagination.js.tpl.htm", 'smarty_include_vars' => array('tabelize' => 'processes_list_tabelize','paginate' => 'processes_list_paginate')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </div>
    <div id="loadingBar" class="adesk_hidden" style="background: url(../awebdesk/media/loader.gif); background-repeat: no-repeat; padding: 5px; padding-left: 20px; padding-top: 2px; color: #999999; font-size: 10px; margin: 5px">
      <?php echo ((is_array($_tmp="Loading. Please wait...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

    </div>
    <span id="selectXPageAllBox" class="adesk_hidden">
      <span class="adesk_hidden"><?php echo ((is_array($_tmp="All Processes are now selected.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
      <span class="adesk_hidden"><?php echo ((is_array($_tmp="All Processes on this page are now selected.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
      <a class="adesk_hidden" href="#" onclick="return adesk_form_check_selection_xpage(this.parentNode);"><?php echo ((is_array($_tmp="Click here to select all %s items.")) ? $this->_run_mod_handler('alang', true, $_tmp, '<span></span>') : smarty_modifier_alang($_tmp, '<span></span>')); ?>
</a>
    </span>
  </form>

  <br />
    <input type="button" id="list_delete_button" value="<?php echo ((is_array($_tmp='Delete Selected')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="adesk_ui_anchor_set('delete_multi')" />
  <br />
  <div style="margin: 10px 0; padding: 10px 0; border-top: 1px solid #ccc;">
    <input  id="list_spawn" type="checkbox" value="1" onclick="processes_list_spawn_toggle(this.checked);" <?php if ($this->_tpl_vars['spawn']): ?>checked="checked"<?php endif; ?> />
    <label for="list_spawn"><?php echo ((is_array($_tmp="Re-queue stalled processes automatically")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
  </div>
</div>