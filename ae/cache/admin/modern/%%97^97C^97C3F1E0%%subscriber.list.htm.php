<?php /* Smarty version 2.6.12, created on 2016-07-08 14:15:47
         compiled from subscriber.list.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'subscriber.list.htm', 4, false),array('modifier', 'default', 'subscriber.list.htm', 19, false),array('modifier', 'truncate', 'subscriber.list.htm', 32, false),array('modifier', 'adesk_isselected', 'subscriber.list.htm', 36, false),array('function', 'adesk_headercol', 'subscriber.list.htm', 66, false),)), $this); ?>
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

<?php if (((is_array($_tmp=@$this->_tpl_vars['segmentname'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')) != ''): ?>
<div style="text-align: center; font-size:13px;" class="adesk_help_inline">
  <b><?php echo ((is_array($_tmp='Showing subscribers that match segment')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 "<a href="desk.php?action=filter#form-<?php echo $this->_tpl_vars['segmentid']; ?>
"><?php echo $this->_tpl_vars['segmentname']; ?>
</a>"</b> (<a href="desk.php?action=subscriber"><?php echo ((is_array($_tmp='Show All Subscribers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>)
</div>
<?php endif; ?>

  <form action="desk.php?action=subscriber" method="GET" onsubmit="subscriber_list_search(); return false">
	<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  cellspacing="0" cellpadding="0" width="100%">
	  <tr class="adesk_table_header_options">
		<td>
		  <select name="listid" id="JSListManager" size="1" onchange="subscriber_list_search()">
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
		  <select name="status" id="JSStatusManager" size="1" onchange="subscriber_list_search()">
			<option value="1" <?php echo ((is_array($_tmp=1)) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, $this->_tpl_vars['statfilter']) : smarty_modifier_adesk_isselected($_tmp, $this->_tpl_vars['statfilter'])); ?>
><?php echo ((is_array($_tmp='Active')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
			<option value="0" <?php echo ((is_array($_tmp=0)) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, $this->_tpl_vars['statfilter']) : smarty_modifier_adesk_isselected($_tmp, $this->_tpl_vars['statfilter'])); ?>
><?php echo ((is_array($_tmp='Unconfirmed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
			<option value="2" <?php echo ((is_array($_tmp=2)) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, $this->_tpl_vars['statfilter']) : smarty_modifier_adesk_isselected($_tmp, $this->_tpl_vars['statfilter'])); ?>
><?php echo ((is_array($_tmp='Unsubscribed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
			<option value="3" <?php echo ((is_array($_tmp=3)) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, $this->_tpl_vars['statfilter']) : smarty_modifier_adesk_isselected($_tmp, $this->_tpl_vars['statfilter'])); ?>
><?php echo ((is_array($_tmp='Bounced')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
						<option value="" <?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('adesk_isselected', true, $_tmp, $this->_tpl_vars['statfilter']) : smarty_modifier_adesk_isselected($_tmp, $this->_tpl_vars['statfilter'])); ?>
>
			  <?php echo ((is_array($_tmp='All Subscribers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			</option>
		  </select>
		</td>
		<td align="right">
		  <div>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'autocomplete.inc.htm', 'smarty_include_vars' => array('fieldPrefix' => 'subscriber','fieldID' => 'list_search','fieldName' => 'qsearch','fieldValue' => $this->_tpl_vars['filter_content'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			<input type="button" value='<?php echo ((is_array($_tmp='Search')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="subscriber_list_search()" />
			<input type="button" value='<?php echo ((is_array($_tmp='Clear')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' id="list_clear" style="display:none" onclick="subscriber_list_clear();" />
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
		  <td style="width: 95px;"><?php echo ((is_array($_tmp='Options')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
		  <td><?php echo smarty_function_adesk_headercol(array('action' => 'subscriber','id' => '01','label' => ((is_array($_tmp='Email')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
		  <td><?php echo smarty_function_adesk_headercol(array('action' => 'subscriber','id' => '02','label' => ((is_array($_tmp='Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
		  <td width="70"><?php echo smarty_function_adesk_headercol(array('action' => 'subscriber','id' => '03','label' => ((is_array($_tmp='Date Added')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
		  <?php $_from = $this->_tpl_vars['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['f']):
?>
		  <?php if ($this->_tpl_vars['f']['show_in_list'] && ( $this->_tpl_vars['listfilter'] > 0 && ( $this->_tpl_vars['f']['relid'] == $this->_tpl_vars['listfilter'] || $this->_tpl_vars['f']['relid'] == 0 ) )): ?>
		  <td><?php echo $this->_tpl_vars['f']['title']; ?>
</td>
		  <?php endif; ?>
		  <?php endforeach; endif; unset($_from); ?>
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
$this->_smarty_include(array('smarty_include_tpl_file' => "pagination.js.tpl.htm", 'smarty_include_vars' => array('tabelize' => 'subscriber_list_tabelize','paginate' => 'subscriber_list_paginate','limitize' => 'subscriber_list_limitize')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</div>
	<div id="loadingBar" class="adesk_hidden" style="background: url(../awebdesk/media/loader.gif); background-repeat: no-repeat; padding: 5px; padding-left: 20px; padding-top: 2px; color: #999999; font-size: 10px; margin: 5px">
	  <?php echo ((is_array($_tmp="Loading. Please wait...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	</div>
	<span id="selectXPageAllBox" class="adesk_hidden">
	  <span class="adesk_hidden"><?php echo ((is_array($_tmp="All subscribers are now selected.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
	  <span class="adesk_hidden"><?php echo ((is_array($_tmp="All subscribers on this page are now selected.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
	  <a class="adesk_hidden" href="#" onclick="return adesk_form_check_selection_xpage(this.parentNode);"><?php echo ((is_array($_tmp="Click here to select all %s items.")) ? $this->_run_mod_handler('alang', true, $_tmp, '<span></span>') : smarty_modifier_alang($_tmp, '<span></span>')); ?>
</a>
	</span>
  </form>

  <br />
  <div>
	<?php if ($this->_tpl_vars['canAddSubscriber']): ?>
		<input type="button" value="<?php echo ((is_array($_tmp='Add')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="adesk_ui_anchor_set('form-0')" />
	<?php endif; ?>

	<?php if ($this->_tpl_vars['admin']['pg_subscriber_delete']): ?>
		<input type="button" id="list_delete_button" value="<?php echo ((is_array($_tmp='Delete Selected')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="adesk_ui_anchor_set('delete_multi')" />
	<?php endif; ?>
	&nbsp;&nbsp;&nbsp;
	<?php if ($this->_tpl_vars['canAddSubscriber'] && $this->_tpl_vars['statfilter'] == 0 && $this->_tpl_vars['listfilter'] > 0 && count ( $this->_tpl_vars['optins'] )): ?>
		<input type="button" id="list_optin_button" value="<?php echo ((is_array($_tmp='Send Email Reminder')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="adesk_ui_anchor_set('optin_multi')" />
	<?php endif; ?>
	&nbsp;&nbsp;&nbsp;
	<?php if ($this->_tpl_vars['canImportSubscriber']): ?>
		<input type="button" value="<?php echo ((is_array($_tmp='Import')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="window.location = 'desk.php?action=subscriber_import';" />
	<?php endif; ?>

	<?php if ($this->_tpl_vars['admin']['pg_subscriber_export']): ?>
		<input type="button" value="<?php echo ((is_array($_tmp="Export...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="subscriber_list_export();" />
		<span id="list_button_newlist" style="display:none">
		  <input type="button" value="<?php echo ((is_array($_tmp='Export to New List')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="window.location.href = '#exportlist-' + subscriber_list_filter.toString(); return false" />
		</span>
	<?php else: ?>
		<span id="list_button_newlist" style="display:none"></span>
	<?php endif; ?>



	<div id="exportOffer" class="adesk_hidden">
	  <div class="adesk_modal" align="center">
		<div class="adesk_modal_inner">
		  <h3 class="m-b"><?php echo ((is_array($_tmp='Export Subscribers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
		  <div>
			<?php echo ((is_array($_tmp="Format:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
			<select id="list_export_type" name="type" size="1" onchange="subscriber_list_exportformat(this.value)">
			  <option value="csv" selected><?php echo ((is_array($_tmp="Comma Seperated (CSV)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
			  <option value="xls"><?php echo ((is_array($_tmp="Microsoft Excel (XLS)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
			  <option value="xml"><?php echo ((is_array($_tmp='XML')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
			</select>
		  </div>
		  <div><br />

			<div><?php echo ((is_array($_tmp="Fields:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>

			<div class="subscriber_export_fieldlist">

			  <div>
				<label><input type="checkbox" name="fields[]" value="id" checked /> <?php echo ((is_array($_tmp="ID#")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
			  </div>
			  <div>
				<label><input type="checkbox" name="fields[]" value="email" checked /> <?php echo ((is_array($_tmp="E-mail")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
			  </div>
			  <div>
				<label><input type="checkbox" name="fields[]" value="listname" checked /> <?php echo ((is_array($_tmp='List Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
			  </div>
			  <div>
				<label><input type="checkbox" name="fields[]" value="first_name" checked /> <?php echo ((is_array($_tmp='First Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
			  </div>
			  <div>
				<label><input type="checkbox" name="fields[]" value="last_name" checked /> <?php echo ((is_array($_tmp='Last Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
			  </div>
			  <div>
				<label><input type="checkbox" name="fields[]" value="status" checked /> <?php echo ((is_array($_tmp='Status')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
			  </div>
			  <div>
				<label><input type="checkbox" name="fields[]" value="cdate" checked /> <?php echo ((is_array($_tmp='Date Added')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
			  </div>
			  <div>
				<label><input type="checkbox" name="fields[]" value="sdate" checked /> <?php echo ((is_array($_tmp='Date Subscribed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
			  </div>
			  <div>
				<label><input type="checkbox" name="fields[]" value="ip" checked /> <?php echo ((is_array($_tmp='IP Address')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
			  </div>
			  <div>
				<label><input type="checkbox" name="fields[]" value="ua" checked /> <?php echo ((is_array($_tmp='User Agent')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
			  </div>
			  <?php $_from = $this->_tpl_vars['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['f']):
?>
			  <div>
				<label><input type="checkbox" name="fields[]" value="<?php echo $this->_tpl_vars['f']['id']; ?>
" checked /> <?php echo $this->_tpl_vars['f']['title']; ?>
</label>
			  </div>
			  <?php endforeach; endif; unset($_from); ?>
			  <div id="exportFields"></div>
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
" onclick="subscriber_list_export_build();" class="adesk_button_ok" />
			<input type="button" value="<?php echo ((is_array($_tmp='Close')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="subscriber_list_export(); adesk_ui_anchor_set(subscriber_list_anchor())" />
		  </div>
		</div>
	  </div>
	</div>
  </div>
</div>