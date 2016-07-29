<?php /* Smarty version 2.6.12, created on 2016-07-08 14:18:25
         compiled from sync.list.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'sync.list.htm', 11, false),)), $this); ?>
<div id="syncListPanel" class="adesk_block">


<form action="desk.php?action=sync&mode=deletes" method="POST">
<div class=" table-responsive"><table width="100%"  border="0" cellspacing="0" cellpadding="0" class="table table-striped m-b-none dataTable">
  <thead>
  <tr class="adesk_table_header">
    <td align="center" width="20">
      <input id="acSelectAllCheckbox" type="checkbox" value="multi[]" onchange="adesk_form_check_all(this);" />
    </td>
    <td width="140"><?php echo ((is_array($_tmp='Options')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></td>
    <td align="center"><a href="#" id="sorter01" onclick="return sync_sort('01');" class="<?php if ($this->_tpl_vars['syncsort'] == '01'): ?>adesk_sort_asc<?php elseif ($this->_tpl_vars['syncsort'] == '01D'): ?>adesk_sort_desc<?php else: ?>adesk_sort_other<?php endif; ?>"><?php echo ((is_array($_tmp='Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></td>
    <td align="center"><a href="#" id="sorter02" onclick="return sync_sort('02');" class="<?php if ($this->_tpl_vars['syncsort'] == '02'): ?>adesk_sort_asc<?php elseif ($this->_tpl_vars['syncsort'] == '02D'): ?>adesk_sort_desc<?php else: ?>adesk_sort_other<?php endif; ?>"><?php echo ((is_array($_tmp='Database')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></td>
    <td align="center" width="100"><a href="#" id="sorter03" onclick="return sync_sort('03');" class="<?php if ($this->_tpl_vars['syncsort'] == '03'): ?>adesk_sort_asc<?php elseif ($this->_tpl_vars['syncsort'] == '03D'): ?>adesk_sort_desc<?php else: ?>adesk_sort_other<?php endif; ?>"><?php echo ((is_array($_tmp='Last Ran')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></td>
  </tr>
  </thead>
  <tfoot>
    <td colspan="5" align="left">
      <div id="loadingBar" class="adesk_block" style="background:url(../awebdesk/media/loader.gif); background-repeat:no-repeat; padding:5px; padding-left:20px; padding-top:2px; color:#999999; font-size:10px; margin:5px;">
        <?php echo ((is_array($_tmp="Loading. Please wait...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

      </div>
    </td>
  </tfoot>
  <tbody id="syncsTable">
  </tbody>
  <tr id="syncsNoResults" class="adesk_hidden">
    <td colspan="5" align="center">
      <div><?php echo ((is_array($_tmp="No syncs found.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
      <div>
        <a href="desk.php?action=sync#add-0-1"><?php echo ((is_array($_tmp="Do you wish to add one?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
      </div>
    </td>
  </tr>
</table>
</div>


<div class="bottom_nav_options">
<?php if (! $this->_tpl_vars['demoMode']): ?>

    <input type="button" value="<?php echo ((is_array($_tmp='Add Sync')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" style="float: right;" onclick="adesk_ui_anchor_set('add-0-1');" />
	<input type="button" value="<?php echo ((is_array($_tmp='Delete Selected')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="sync_delete_multiple();" />

<?php else: ?>
	<span class="demoDisabled2"><?php echo ((is_array($_tmp='Disabled in demo')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
<?php endif; ?>

</div>
</form>

</div>