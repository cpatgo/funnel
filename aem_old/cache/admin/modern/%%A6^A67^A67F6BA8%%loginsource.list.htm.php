<?php /* Smarty version 2.6.12, created on 2016-07-08 16:22:26
         compiled from loginsource.list.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'loginsource.list.htm', 6, false),array('function', 'adesk_headercol', 'loginsource.list.htm', 7, false),)), $this); ?>
<div id="list" class="adesk_hidden">
  <form action="desk.php?action=loginsource" method="GET" onsubmit="loginsource_list_search(); return false">
  <div class=" table-responsive">  <table width="100%" border="0" cellspacing="0" cellpadding="1" class="table table-striped m-b-none dataTable">
      <thead id="list_head">
        <tr class="adesk_table_header">
          <td width="50"><?php echo ((is_array($_tmp='Options')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td width="75"><?php echo smarty_function_adesk_headercol(array('action' => 'loginsource','id' => '02','label' => ((is_array($_tmp='Order')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
		  <td><?php echo smarty_function_adesk_headercol(array('action' => 'loginsource','id' => '01','label' => ((is_array($_tmp='Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
          <td><?php echo smarty_function_adesk_headercol(array('id' => '01','label' => ((is_array($_tmp='Id')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
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
    </table></div>
    <div style="float:right">
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "pagination.js.tpl.htm", 'smarty_include_vars' => array('tabelize' => 'loginsource_list_tabelize','paginate' => 'loginsource_list_paginate')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </div>
    <div id="loadingBar" class="adesk_block" style="background: url(../awebdesk/media/loader.gif); background-repeat: no-repeat; padding: 5px; padding-left: 20px; padding-top: 2px; color: #999999; font-size: 10px; margin: 5px">
      <?php echo ((is_array($_tmp="Loading. Please wait...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

    </div>
    <span id="selectXPageAllBox" class="adesk_hidden">
      <span class="adesk_hidden"><?php echo ((is_array($_tmp="All login sources are now selected.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
      <span class="adesk_hidden"><?php echo ((is_array($_tmp="All login sources on this page are now selected.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
      <a class="adesk_hidden" href="#" onclick="return adesk_form_check_selection_xpage(this.parentNode);"><?php echo ((is_array($_tmp="Click here to select all %s items.")) ? $this->_run_mod_handler('alang', true, $_tmp, '<span></span>') : smarty_modifier_alang($_tmp, '<span></span>')); ?>
</a>
    </span>
  </form>

  <br />
</div>