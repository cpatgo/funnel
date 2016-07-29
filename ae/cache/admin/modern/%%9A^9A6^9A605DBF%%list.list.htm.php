<?php /* Smarty version 2.6.12, created on 2016-07-08 16:53:15
         compiled from list.list.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'list.list.htm', 4, false),array('function', 'adesk_headercol', 'list.list.htm', 40, false),)), $this); ?>
<div id="sublimit" class="adesk_modal" style="display:none">
  <div class="adesk_modal_inner">
	<?php if ($this->_tpl_vars['__ishosted']): ?>
	<h3 class="m-b"><?php echo ((is_array($_tmp="Unable to delete at this time...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>

	<p><?php echo ((is_array($_tmp="We have noticed a substantial amount of deletions taking place--well over two times your subscriber limit, to be precise.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</p>

	<p><?php echo ((is_array($_tmp="This type of importing and deleting subscribers automatically flags your account as a potential sending risk.  You should never need to delete and re-import your subscribers multiple times within a given month.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</p>

	<p><?php echo ((is_array($_tmp="This deletion limit will be reduced with your next billing cycle--but if a lot of importing/deleting occurs in the near future it could subject your account to suspension.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</p>

	<input type="button" onclick="$('sublimit').hide()" value='<?php echo ((is_array($_tmp='OK')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' class="adesk_button_ok">
	<?php endif; ?>
  </div>
</div>

<div id="list" class="adesk_hidden">
  <h3 class="m-b"><?php echo ((is_array($_tmp='Lists')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<span id="list_list_count" class="adesk_hidden"></span></h3>

  <form action="desk.php?action=list" method="GET" onsubmit="list_list_search(); return false">
    <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  cellspacing="0" cellpadding="0" width="100%">
      <tr class="adesk_table_header_options">
        <td align="right">
          <div>
            <input type="text" name="qsearch" id="list_search" />
            <input type="button" value='<?php echo ((is_array($_tmp='Search')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="list_list_search()" />
            <input type="button" value='<?php echo ((is_array($_tmp='Clear')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' id="list_clear" style="display:none" onclick="list_list_clear(); " />
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
          <td style="width: 170px;"><?php echo ((is_array($_tmp='Options')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td><?php echo smarty_function_adesk_headercol(array('action' => 'list','id' => '01','label' => ((is_array($_tmp='List Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
		  <td width="80" align="center"><?php echo smarty_function_adesk_headercol(array('action' => 'list','id' => '02','label' => ((is_array($_tmp='Subscribers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
		  <td width="80" align="center"><?php echo smarty_function_adesk_headercol(array('action' => 'list','id' => '03','label' => ((is_array($_tmp='Campaigns Sent')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
		  <td width="80" align="center"><?php echo smarty_function_adesk_headercol(array('action' => 'list','id' => '04','label' => ((is_array($_tmp='Emails Sent')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
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
$this->_smarty_include(array('smarty_include_tpl_file' => "pagination.js.tpl.htm", 'smarty_include_vars' => array('tabelize' => 'list_list_tabelize','paginate' => 'list_list_paginate','limitize' => 'list_list_limitize')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </div>
    <div id="loadingBar" class="adesk_hidden" style="background: url(../awebdesk/media/loader.gif); background-repeat: no-repeat; padding: 5px; padding-left: 20px; padding-top: 2px; color: #999999; font-size: 10px; margin: 5px">
      <?php echo ((is_array($_tmp="Loading. Please wait...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

    </div>
    <span id="selectXPageAllBox" class="adesk_hidden">
      <span class="adesk_hidden"><?php echo ((is_array($_tmp="All items are now selected.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
      <span class="adesk_hidden"><?php echo ((is_array($_tmp="All items on this page are now selected.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
      <a class="adesk_hidden" href="#" onclick="return adesk_form_check_selection_xpage(this.parentNode);"><?php echo ((is_array($_tmp="Click here to select all %s items.")) ? $this->_run_mod_handler('alang', true, $_tmp, '<span></span>') : smarty_modifier_alang($_tmp, '<span></span>')); ?>
</a>
    </span>
  </form>

  <br />
  <?php if ($this->_tpl_vars['canAddList']): ?>
  <input type="button" value="<?php echo ((is_array($_tmp='Add')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="adesk_ui_anchor_set('form-0')" />
  <?php endif; ?>
  <?php if ($this->_tpl_vars['admin']['pg_list_delete']): ?>
  <input type="button" id="list_delete_button" value="<?php echo ((is_array($_tmp='Delete Selected')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="adesk_ui_anchor_set('delete_multi')" />
  <?php endif; ?>



  <div id="send_test_email" class="adesk_modal" align="center" style="display:none;">
  <div class="adesk_modal_inner" align="left">
	<h3 class="m-b"><?php echo ((is_array($_tmp="Copy list:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 LISTNAMEHERE</h3>

  <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td width="100"><?php echo ((is_array($_tmp='New list name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
      <td><input name="name" id="name" type="text"></td>
    </tr>
    <tr>

      <td><?php echo ((is_array($_tmp='Default from email')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
      <td><input name="email" id="email" type="text"></td>
    </tr>
	<tr>
	<td>&nbsp;</td>
	</tr>
    <tr>
      <td>
      </td><td>
		      <input name="clone_settings" id="clone_settings" value="1" checked="checked" type="checkbox"><?php echo ((is_array($_tmp='Copy List Settings')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		      <br><input name="clone_settings" id="clone_settings" value="1" checked="checked" type="checkbox"><?php echo ((is_array($_tmp='Copy Subscriber Fields')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		      <br><input name="clone_autoresponders" id="clone_autoresponders" value="1" checked="checked" type="checkbox"><?php echo ((is_array($_tmp='Copy Messages')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		      <br><input name="clone_templates" id="clone_templates" value="1" checked="checked" type="checkbox"><?php echo ((is_array($_tmp='Copy Templates')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

              <br><input name="clone_cheaders" id="clone_cheaders" value="1" checked="checked" type="checkbox"><?php echo ((is_array($_tmp='Copy Email Headers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			  <br />
			  <br><input name="clone_users" id="clone_users" value="1" checked="checked" type="checkbox"><?php echo ((is_array($_tmp='Copy Subscribers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

      </td>
    </tr>

  </table></div>



	<br />
    <div>
      <input type="button" value='<?php echo ((is_array($_tmp='Copy List')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="campaign_actions_save();" class="adesk_button_ok" />
      <input type="button" value='<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="adesk_dom_toggle_display('link_actions', 'block');" />

    </div>
</div>
</div>


</div>