<?php /* Smarty version 2.6.12, created on 2016-07-08 14:19:16
         compiled from campaign.list.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'campaign.list.htm', 7, false),array('modifier', 'truncate', 'campaign.list.htm', 9, false),array('function', 'adesk_headercol', 'campaign.list.htm', 43, false),)), $this); ?>
<div id="list" class="adesk_hidden">
  <form action="desk.php?action=campaign<?php if ($this->_tpl_vars['reportsOnly']): ?>&reports=1<?php endif; ?>" method="GET" onsubmit="campaign_list_search(); return false">
    <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  cellspacing="0" cellpadding="0" width="100%">
      <tr class="adesk_table_header_options">
        <td>
          <select name="listid" id="JSListManager" size="1" onchange="campaign_list_search()">
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
          <select name="type" id="JSTypeManager" size="1" onchange="campaign_list_search()">
            <option value=""><?php echo ((is_array($_tmp="Campaign Type...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
<?php $_from = $this->_tpl_vars['types']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['t']):
?>
            <option value="<?php echo $this->_tpl_vars['k']; ?>
"><?php echo $this->_tpl_vars['t']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
            <!--<option value="special"><?php echo ((is_array($_tmp='special')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>-->
          </select>
          <select name="status" id="JSStatusManager" size="1" onchange="campaign_list_search()">
            <option value=""><?php echo ((is_array($_tmp="Status...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
<?php $_from = $this->_tpl_vars['statuses']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['s']):
?>
            <option value="<?php echo $this->_tpl_vars['k']; ?>
"><?php echo $this->_tpl_vars['s']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
          </select>
        </td>
        <td align="right">
          <div>
            <input type="text" name="qsearch" id="list_search" />
            <input type="button" value='<?php echo ((is_array($_tmp='Search')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="campaign_list_search()" />
            <input type="button" value='<?php echo ((is_array($_tmp='Clear')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' id="list_clear" style="display:none" onclick="campaign_list_clear()" />
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
          <td width="<?php if ($this->_tpl_vars['reportsOnly']): ?>100<?php else: ?>200<?php endif; ?>"><?php echo ((is_array($_tmp='Options')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
          <td width="120"><?php echo smarty_function_adesk_headercol(array('action' => 'campaign','id' => '02','label' => ((is_array($_tmp='Type')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
          <td width="50"><?php echo smarty_function_adesk_headercol(array('action' => 'campaign','id' => '03','label' => ((is_array($_tmp='Status')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
          <td><?php echo smarty_function_adesk_headercol(array('action' => 'campaign','id' => '04','label' => ((is_array($_tmp='Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
          <td width="100"><?php echo smarty_function_adesk_headercol(array('action' => 'campaign','id' => '05','label' => ((is_array($_tmp='First Sent Date')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
          <td width="100"><?php echo smarty_function_adesk_headercol(array('action' => 'campaign','id' => '01','label' => ((is_array($_tmp='Last Sent Date')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
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
$this->_smarty_include(array('smarty_include_tpl_file' => "pagination.js.tpl.htm", 'smarty_include_vars' => array('tabelize' => 'campaign_list_tabelize','paginate' => 'campaign_list_paginate')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </div>
    <div id="loadingBar" class="adesk_hidden" style="background: url(../awebdesk/media/loader.gif); background-repeat: no-repeat; padding: 5px; padding-left: 20px; padding-top: 2px; color: #999999; font-size: 10px; margin: 5px">
      <?php echo ((is_array($_tmp="Loading. Please wait...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

    </div>
    <span id="selectXPageAllBox" class="adesk_hidden">
      <span class="adesk_hidden"><?php echo ((is_array($_tmp="All campaigns are now selected.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
      <span class="adesk_hidden"><?php echo ((is_array($_tmp="All campaigns on this page are now selected.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
      <a class="adesk_hidden" href="#" onclick="return adesk_form_check_selection_xpage(this.parentNode);"><?php echo ((is_array($_tmp="Click here to select all %s items.")) ? $this->_run_mod_handler('alang', true, $_tmp, '<span></span>') : smarty_modifier_alang($_tmp, '<span></span>')); ?>
</a>
    </span>
  </form>

  <br />
  <?php if ($this->_tpl_vars['admin']['pg_message_add']): ?>
  	<input type="button" value="<?php echo ((is_array($_tmp='Create New')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="window.location = 'desk.php?action=campaign_new';" style="font-weight: bold;" />
  <?php endif; ?>
  <?php if ($this->_tpl_vars['admin']['pg_message_delete']): ?>
  	<input type="button" id="list_delete_button" value="<?php echo ((is_array($_tmp='Delete Selected')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="adesk_ui_anchor_set('delete_multi')" />
  <?php endif; ?>
  <?php if ($this->_tpl_vars['reportsOnly']): ?>
  	<input type="button" id="list_delete_button" value="<?php echo ((is_array($_tmp='Export Selected')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="campaign_export_open();" />
  <?php endif; ?>
</div>



<div id="list_reuse" class="adesk_modal" align="center" style="display:none;">
  <div class="adesk_modal_inner" align="left">
	<h3 class="m-b"><?php echo ((is_array($_tmp='Resend')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
	<div class="adesk_help_inline"><?php echo ((is_array($_tmp="Select how you would like to resend this campaign.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>

	<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="0" width="100%">
<?php if ($this->_tpl_vars['admin']['pg_message_add']): ?>
		<tr>
	      <td><input name="action" id="campaign_use_reuse" type="radio" value="reuse" checked="checked" /></td>
		  <td><label for="campaign_use_reuse"><?php echo ((is_array($_tmp='Create a new campaign based off of this campaign')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label></td>
	    </tr>
<?php endif; ?>
		<tbody id="resend_filter">
		  <?php if ($this->_tpl_vars['canSendCampaign']): ?>
		  <tr>
			<td><input name="action" id="campaign_use_newsub" type="radio" value="newsub" <?php if (! $this->_tpl_vars['admin']['pg_message_add']): ?>checked="checked"<?php endif; ?> /></td>
			<td><label for="campaign_use_newsub"><?php echo ((is_array($_tmp="Send to new subscribers (since this campaign was originally sent)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label></td>
		  </tr>
		  <tr>
			<td width="30"><input name="action" id="campaign_use_unread" type="radio" value="unread" /></td>
			<td><label for="campaign_use_unread"><?php echo ((is_array($_tmp="Send to subscribers who have not read/opened this campaign")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label></td>
		  </tr>
		  <?php endif; ?>
		</tbody>
  </table></div>
	<br />

    <div>
      <input type="hidden" id="campaign_use_id" name="id" value="" />
      <input type="button" value='<?php echo ((is_array($_tmp='Continue')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="campaign_reuse();" class="adesk_button_ok" />
      <input type="button" value='<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="adesk_dom_toggle_display('list_reuse', 'block');" />
    </div>
  </div>
</div>



<div id="list_edit" class="adesk_modal" align="center" style="display:none;">
  <div class="adesk_modal_inner" align="left">
	<h3 class="m-b"><?php echo ((is_array($_tmp='Edit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
	<div style="margin-bottom: 10px;"><?php echo ((is_array($_tmp="Select what would you like to edit:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>

	<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
	      <td><input name="action" id="campaign_edit_campaign" type="radio" value="campaign" checked="checked" onclick="$('campaign_edit_split_box').className='adesk_hidden';" /></td>
		  <td><label for="campaign_edit_campaign"><?php echo ((is_array($_tmp="Edit campaign settings (Using the campaign wizard)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label></td>
	    </tr>
		<tr>
	      <td><input name="action" id="campaign_edit_message" type="radio" value="message" onclick="if($('campaign_edit_split_field').getElementsByTagName('option').length > 1) $('campaign_edit_split_box').className='adesk_table_rowgroup';" /></td>
		  <td><label for="campaign_edit_message"><?php echo ((is_array($_tmp='Edit the message contents')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label></td>
	    </tr>
	<tbody id="campaign_edit_split_box" class="adesk_hidden">
		<tr>
	      <td>&nbsp;</td>
		  <td>
		    <select id="campaign_edit_split_field" name="messageid"></select>
		  </td>
	    </tr>
    </tbody>
  </table></div>
	<br />

    <div>
      <input type="hidden" id="campaign_edit_id" name="id" value="" />
      <input type="button" value='<?php echo ((is_array($_tmp='Continue')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="campaign_edit();" class="adesk_button_ok" />
      <input type="button" value='<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="adesk_dom_toggle_display('list_edit', 'block');" />
    </div>
  </div>
</div>


<?php if ($this->_tpl_vars['reportsOnly']): ?>

<div id="list_export" class="adesk_modal" align="center" style="display:none;">
  <div class="adesk_modal_inner" align="left">
	<h3 class="m-b"><?php echo ((is_array($_tmp='Export Campaign Reports')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>

	<div><?php echo ((is_array($_tmp="You have selected %s campaigns for exporting.")) ? $this->_run_mod_handler('alang', true, $_tmp, '<span id="list_export_count"></span>') : smarty_modifier_alang($_tmp, '<span id="list_export_count"></span>')); ?>
</div>

	<ul id="list_export_campaigns"></ul>

	<div style="margin-bottom: 10px;"><?php echo ((is_array($_tmp="Select what would you like to export:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>

		<div>
	  <label>
	    <input type="checkbox" value="open" name="reports[]" id="export_report_open" />
	    <?php echo ((is_array($_tmp='Opens')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  </label>
	</div>

	<div>
	  <label>
	    <input type="checkbox" value="link" name="reports[]" id="export_report_link" />
	    <?php echo ((is_array($_tmp='Links')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  </label>
	</div>

	<div style="margin-left: 20px;">
	  <label>
	    <input type="checkbox" value="click" name="reports[]" id="export_report_click" />
	    <?php echo ((is_array($_tmp='Clicks')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  </label>
	</div>

	<div>
	  <label>
	    <input type="checkbox" value="forward" name="reports[]" id="export_report_forward" />
	    <?php echo ((is_array($_tmp='Forwards')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  </label>
	</div>

	<div>
	  <label>
	    <input type="checkbox" value="bounce" name="reports[]" id="export_report_bounce" />
	    <?php echo ((is_array($_tmp='Bounces')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  </label>
	</div>

	<div>
	  <label>
	    <input type="checkbox" value="unsub" name="reports[]" id="export_report_unsub" />
	    <?php echo ((is_array($_tmp='Unsubscriptions')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  </label>
	</div>

	<div>
	  <label>
	    <input type="checkbox" value="update" name="reports[]" id="export_report_update" />
	    <?php echo ((is_array($_tmp='Updates')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  </label>
	</div>

	<br />

    <div>
      <input type="button" value='<?php echo ((is_array($_tmp='Export')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="campaign_export();" class="adesk_button_ok" />
      <input type="button" value='<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="adesk_dom_display_none('list_export');" />
    </div>
  </div>
</div>

<?php endif; ?>