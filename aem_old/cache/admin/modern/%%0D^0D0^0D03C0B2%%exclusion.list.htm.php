<?php /* Smarty version 2.6.12, created on 2016-07-28 11:05:46
         compiled from exclusion.list.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'exclusion.list.htm', 7, false),array('modifier', 'truncate', 'exclusion.list.htm', 9, false),array('function', 'adesk_headercol', 'exclusion.list.htm', 30, false),)), $this); ?>
<div id="list" class="adesk_hidden">
  <form action="desk.php?action=exclusion" method="GET" onsubmit="exclusion_list_search(); return false">
    <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  cellspacing="0" cellpadding="0" width="100%">
      <tr class="adesk_table_header_options">
        <td>
    <select name="listid" id="JSListManager" size="1" onchange="exclusion_list_search()">
      <option value="0"><?php echo ((is_array($_tmp="List Filter...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
<?php $_from = $this->_tpl_vars['listsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['p']):
?>
      <option value="<?php echo $this->_tpl_vars['p']['id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['p']['name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 50) : smarty_modifier_truncate($_tmp, 50)); ?>
</option>
<?php endforeach; endif; unset($_from); ?>
    </select>
        </td>
        <td align="right">
          <div>
            <input type="text" name="qsearch" id="list_search" />
            <input type="button" value='<?php echo ((is_array($_tmp='Search')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="exclusion_list_search()" />
            <input type="button" value='<?php echo ((is_array($_tmp='Clear')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' id="list_clear" style="display:none" onclick="exclusion_list_clear()" />
            &nbsp;<a href="#search" style="display:inline;font-size:10px"><?php echo ((is_array($_tmp='Advanced Search')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
          </div>
        </td>
      </tr>
    </table></div>
    <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  width="100%" border="0" cellspacing="0" cellpadding="1">
      <thead id="list_head">
        <tr class="adesk_table_header">
          <td align="center" width="20">
            <input id="acSelectAllCheckbox" type="checkbox" value="multi[]" onclick="adesk_form_check_selection_all(this, $('selectXPageAllBox'))" />
          </td>
          <td width="50"><?php echo ((is_array($_tmp='Options')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
          <td><?php echo smarty_function_adesk_headercol(array('action' => 'exclusion','id' => '01','label' => ((is_array($_tmp='Email Matching Pattern')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
          <td width="50"><?php echo smarty_function_adesk_headercol(array('action' => 'exclusion','id' => '02','label' => ((is_array($_tmp="List(s)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
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
$this->_smarty_include(array('smarty_include_tpl_file' => "pagination.js.tpl.htm", 'smarty_include_vars' => array('tabelize' => 'exclusion_list_tabelize','paginate' => 'exclusion_list_paginate')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </div>
    <div id="loadingBar" class="adesk_hidden" style="background: url(../awebdesk/media/loader.gif); background-repeat: no-repeat; padding: 5px; padding-left: 20px; padding-top: 2px; color: #999999; font-size: 10px; margin: 5px">
      <?php echo ((is_array($_tmp="Loading. Please wait...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

    </div>
    <span id="selectXPageAllBox" class="adesk_hidden">
      <span class="adesk_hidden"><?php echo ((is_array($_tmp="All Exclusion Patterns are now selected.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
      <span class="adesk_hidden"><?php echo ((is_array($_tmp="All Exclusion Patterns on this page are now selected.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
      <a class="adesk_hidden" href="#" onclick="return adesk_form_check_selection_xpage(this.parentNode);"><?php echo ((is_array($_tmp="Click here to select all %s items.")) ? $this->_run_mod_handler('alang', true, $_tmp, '<span></span>') : smarty_modifier_alang($_tmp, '<span></span>')); ?>
</a>
    </span>
  </form>

  <br />
  <div>
<?php if ($this->_tpl_vars['admin']['pg_list_add']): ?>
    <input type="button" value="<?php echo ((is_array($_tmp='Add')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="adesk_ui_anchor_set('form-0')" />
<?php endif; ?>
<?php if ($this->_tpl_vars['admin']['pg_list_delete']): ?>
    <input type="button" id="list_delete_button" value="<?php echo ((is_array($_tmp='Delete Selected')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="adesk_ui_anchor_set('delete_multi')" />
<?php endif; ?>
	&nbsp;&nbsp;&nbsp;
    <input type="button" value="<?php echo ((is_array($_tmp="Export...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="exclusion_list_export();" />

	<div id="exportOffer" class="adesk_hidden">
	  <div class="adesk_modal" align="center">
		<div class="adesk_modal_inner">
		  <h3 class="m-b"><?php echo ((is_array($_tmp='Export Exclusion List')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
		  <div>
			<?php echo ((is_array($_tmp="Format:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
			<select id="list_export_type" name="type" size="1" onchange="exclusion_list_exportformat(this.value)">
			  <option value="csv" selected><?php echo ((is_array($_tmp='CSV')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
			  <option value="xls"><?php echo ((is_array($_tmp='XLS')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
			  <option value="xml"><?php echo ((is_array($_tmp='XML')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
			</select>
		  </div>
		  <br />
		  <div>
			<div><?php echo ((is_array($_tmp="Fields:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
			<div class="exclusion_export_fieldlist">

			  <div>
				<label><input type="checkbox" name="fields[]" value="id" checked /> <?php echo ((is_array($_tmp="ID#")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
			  </div>
			  <div>
				<label><input type="checkbox" name="fields[]" value="pattern" checked /> <?php echo ((is_array($_tmp="E-mail Pattern")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
			  </div>
			</div>
			<br clear="left" />
		  </div>
		  <br />
		  <div>
			<?php echo ((is_array($_tmp="How Many:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
			<select name="what" id="exportOfferWhat" size="1">
			  <option value="page"><?php echo ((is_array($_tmp='This Page Only')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
			  <option value="list" selected id="exportOfferAllPages"><?php echo ((is_array($_tmp='All Pages')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
			</select>
		  </div>
		  <br />

		  <div>
			<input type="button" value="<?php echo ((is_array($_tmp='Export')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="exclusion_list_export_build();" class="adesk_button_ok" />
			<input type="button" value="<?php echo ((is_array($_tmp='Close')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="exclusion_list_export(); adesk_ui_anchor_set(exclusion_list_anchor())" />
		  </div>
		</div>
	  </div>
	</div>
  </div>
</div>