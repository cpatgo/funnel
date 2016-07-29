<?php /* Smarty version 2.6.12, created on 2016-07-08 14:11:34
         compiled from group.inc.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'group.inc.htm', 2, false),array('modifier', 'js', 'group.inc.htm', 2, false),array('modifier', 'escape', 'group.inc.htm', 254, false),array('modifier', 'truncate', 'group.inc.htm', 254, false),array('modifier', 'help', 'group.inc.htm', 262, false),array('function', 'jsvar', 'group.inc.htm', 3, false),)), $this); ?>
<script type="text/javascript">
  var group_str_nosendmethod = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not specify a sending method for this group.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
	var __ishosted = <?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['__ishosted']), $this);?>
;

  <?php echo '
  function user_group_defaults_extended() {
		group_form_defaults_extended();
  }

  function group_form_defaults_extended() {
		$A($("group_lists").options).each(function(e) { e.selected = true; });
		if ( $("group_sendmethods") ) $A($("group_sendmethods").options).each(function(e) { e.selected = true; });

		if ( $("group_unsubscribelink_checkbox") ) $("group_unsubscribelink_checkbox").checked = false;
		$("group_optinconfirm_checkbox").checked = false;

		$("group_limit_mail").value = "";
		$("group_limit_mail_type").value = "month";
		$("group_limit_mail_checkbox").checked = false;
		$("group_div_limit_mail").style.display = "none";

		$("group_limit_subscriber").value = "";
		$("group_limit_subscriber_checkbox").checked = false;
		$("group_div_limit_subscriber").style.display = "none";

		$("group_limit_list").value = "";
		$("group_limit_list_checkbox").checked = false;
		$("group_div_limit_list").style.display = "none";

		$("group_limit_campaign").value = "";
		$("group_limit_campaign_type").value = "month";
		$("group_limit_campaign_checkbox").checked = false;
		$("group_div_limit_campaign").style.display = "none";

		if (!__ishosted) {
			$("group_limit_attachment").value = "";
			$("group_limit_attachment_checkbox").checked = false;
			$("group_div_limit_attachment").style.display = "none";
		}

		$("group_limit_user").value = "";
		$("group_limit_user_checkbox").checked = false;
		$("group_div_limit_user").style.display = "none";

		$("group_forcesenderinfo_checkbox").checked = false;

		$("group_req_approval_1st").value = 2;
		$("group_req_approval_notify").value = \'\';
		$("group_req_approval_checkbox").checked = false;
		$("group_div_req_approval").style.display = "none";

		$("group_pg_user_add").checked = true;
		$("group_pg_user_edit").checked = true;
		$("group_pg_user_delete").checked = true;
		$("group_pg_list_add").checked = true;
		$("group_pg_list_edit").checked = true;
		$("group_pg_list_delete").checked = true;
		$("group_pg_list_opt").checked = true;
		$("group_pg_list_headers").checked  = true;
		if ( $("group_pg_list_emailaccount") ) $("group_pg_list_emailaccount").checked = true;
		if ( $("group_pg_list_bounce") ) $("group_pg_list_bounce").checked = true;
		$("group_pg_message_add").checked = true;
		$("group_pg_message_edit").checked = true;
		$("group_pg_message_delete").checked = true;
		$("group_pg_message_send").checked = true;
		group_permission_display(\'group_pg_message_send\');
		$("group_pg_subscriber_add").checked = true;
		$("group_pg_subscriber_edit").checked = true;
		$("group_pg_subscriber_delete").checked = true;
		$("group_pg_subscriber_import").checked = true;
		$("group_pg_subscriber_export").checked = true;
		$("group_pg_subscriber_sync").checked = true;
		$("group_pg_subscriber_approve").checked = true;
		$("group_pg_subscriber_filters").checked = true;
		$("group_pg_subscriber_actions").checked = true;
		$("group_pg_subscriber_fields").checked  = true;
		$("group_pg_form_add").checked = true;
		$("group_pg_form_edit").checked = true;
		$("group_pg_form_delete").checked = true;
		$("group_pg_template_add").checked = true;
		$("group_pg_template_edit").checked = true;
		$("group_pg_template_delete").checked = true;
		$("group_pg_reports_campaign").checked = true;
		$("group_pg_reports_list").checked = true;
		$("group_pg_reports_user").checked = true;
		$("group_pg_reports_trend").checked = true;
  }

  function group_form_save_extended_check() {
	if ($("group_sendmethods") && $("group_sendmethods").value == "") {
	  // Granted, this is a multiple select.  But, if nothing is selected, this will be blank.
	  alert(group_str_nosendmethod);
	  return false;
	}

	return true;
  }

  function group_form_load_cb_extended(ary) {
		var tmp;

		tmp = ary.lists.toString().split(",");
		$A($("group_lists").options).each(function(e) { if (tmp.indexOf(e.value) > -1) e.selected = true; else e.selected = false; });

		if ( $("group_sendmethods") ) {
			tmp = ary.sendmethods.toString().split(",");
			$A($("group_sendmethods").options).each(function(e) { if (tmp.indexOf(e.value) > -1) e.selected = true; else e.selected = false; });
		}

		$("group_limit_mail").value = ary.limit_mail > 0 ? ary.limit_mail : "";
		$("group_limit_mail_type").value = ary.limit_mail_type;
		if (ary.limit_mail > 0) {
			$("group_limit_mail_checkbox").checked = true;
			$("group_div_limit_mail").style.display = "";
		}

		$("group_limit_subscriber").value = ary.limit_subscriber > 0 ? ary.limit_subscriber : "";
		if (ary.limit_subscriber > 0) {
			$("group_limit_subscriber_checkbox").checked = true;
			$("group_div_limit_subscriber").style.display = "";
		}

		$("group_limit_list").value = ary.limit_list > 0 ? ary.limit_list : "";
		if (ary.limit_list > 0) {
			$("group_limit_list_checkbox").checked = true;
			$("group_div_limit_list").style.display = "";
		}

		$("group_limit_campaign").value = ary.limit_campaign > 0 ? ary.limit_campaign : "";
		$("group_limit_campaign_type").value = ary.limit_campaign_type;
		if (ary.limit_campaign > 0) {
			$("group_limit_campaign_checkbox").checked = true;
			$("group_div_limit_campaign").style.display = "";
		}

		if (!__ishosted) {
			$("group_limit_attachment").value = ary.limit_attachment > -1 ? ary.limit_attachment : "";
			if (ary.limit_attachment > -1) {
				$("group_limit_attachment_checkbox").checked = true;
				$("group_div_limit_attachment").style.display = "";
			}
		}

		$("group_limit_user").value = ary.limit_user > 0 ? ary.limit_user : "";
		if (ary.limit_user > 0) {
			$("group_limit_user_checkbox").checked = true;
			$("group_div_limit_user").style.display = "";
		}

		$("group_forcesenderinfo_checkbox").checked = ( ary.forcesenderinfo == 1 );

		$("group_req_approval_1st").value = ( ary.req_approval_1st == 0 ? \'\' : ary.req_approval_1st );
		$("group_req_approval_notify").value = ary.req_approval_notify;
		$("group_req_approval_checkbox").checked = (ary.req_approval == 1);
		$("group_div_req_approval").style.display = ( ary.req_approval == 1 ? "" : "none" );

		if ( $("group_unsubscribelink_checkbox") ) $("group_unsubscribelink_checkbox").checked = (ary.unsubscribelink == 1);
		$("group_optinconfirm_checkbox").checked = (ary.optinconfirm == 1);

		$("group_pg_user_add").checked = (ary.pg_user_add == 1);
		$("group_pg_user_edit").checked = (ary.pg_user_edit == 1);
		$("group_pg_user_delete").checked = (ary.pg_user_delete == 1);
		$("group_pg_list_add").checked = (ary.pg_list_add == 1);
		$("group_pg_list_edit").checked = (ary.pg_list_edit == 1);
		$("group_pg_list_delete").checked = (ary.pg_list_delete == 1);
		$("group_pg_list_opt").checked = (ary.pg_list_opt == 1);
		$("group_pg_list_headers").checked = (ary.pg_list_headers == 1);
		if ( $("group_pg_list_emailaccount") ) $("group_pg_list_emailaccount").checked = (ary.pg_list_emailaccount == 1);
		if ( $("group_pg_list_bounce") ) $("group_pg_list_bounce").checked = (ary.pg_list_bounce == 1);
		$("group_pg_message_add").checked = (ary.pg_message_add == 1);
		$("group_pg_message_edit").checked = (ary.pg_message_edit == 1);
		$("group_pg_message_delete").checked = (ary.pg_message_delete == 1);
		$("group_pg_message_send").checked = (ary.pg_message_send == 1);
		group_permission_display(\'group_pg_message_send\');
		$("group_pg_subscriber_add").checked = (ary.pg_subscriber_add == 1);
		$("group_pg_subscriber_edit").checked = (ary.pg_subscriber_edit == 1);
		$("group_pg_subscriber_delete").checked = (ary.pg_subscriber_delete == 1);
		$("group_pg_subscriber_import").checked = (ary.pg_subscriber_import == 1);
		$("group_pg_subscriber_export").checked = (ary.pg_subscriber_export == 1);
		$("group_pg_subscriber_sync").checked = (ary.pg_subscriber_sync == 1);
		$("group_pg_subscriber_approve").checked = (ary.pg_subscriber_approve == 1);
		$("group_pg_subscriber_filters").checked = (ary.pg_subscriber_filters == 1);
		$("group_pg_subscriber_actions").checked = (ary.pg_subscriber_actions == 1);
		$("group_pg_subscriber_fields").checked = (ary.pg_subscriber_fields == 1);
		$("group_pg_form_add").checked = (ary.pg_form_add == 1);
		$("group_pg_form_edit").checked = (ary.pg_form_edit == 1);
		$("group_pg_form_delete").checked = (ary.pg_form_delete == 1);
		$("group_pg_template_add").checked = (ary.pg_template_add == 1);
		$("group_pg_template_edit").checked = (ary.pg_template_edit == 1);
		$("group_pg_template_delete").checked = (ary.pg_template_delete == 1);
		$("group_pg_reports_campaign").checked = (ary.pg_reports_campaign == 1);
		$("group_pg_reports_list").checked = (ary.pg_reports_list == 1);
		$("group_pg_reports_user").checked = (ary.pg_reports_user == 1);
		$("group_pg_reports_trend").checked = (ary.pg_reports_trend == 1);
  }

  function group_limitdisplay(id, val) {
	if (val)
	  $(id).style.display = "";
	else
	  $(id).style.display = "none";
  }

  function group_permission_display(id) {
		if (id == "group_pg_message_send") {
			if ( $("group_pg_message_send").checked == 1 ) {
				$("group_limit_mail_tr").className = "";
				$("group_limit_campaign_tr").className = "";
			}
			else {
				$("group_limit_mail_tr").className = "adesk_hidden";
				$("group_limit_mail_checkbox").checked = false;
				$("group_limit_campaign_tr").className = "adesk_hidden";
				$("group_limit_campaign_checkbox").checked = false;
			}
		}
  }

  function group_can_update() {
	return adesk_js_admin.pg_group_edit == 1;
  }

  function group_can_add() {
	return adesk_js_admin.pg_group_add == 1;
  }

  function group_can_delete() {
	return adesk_js_admin.pg_group_delete == 1;
  }

  function user_can_update() {
	return adesk_js_admin.pg_user_edit == 1;
  }

  function user_can_add() {
	return adesk_js_admin.pg_user_add == 1;
  }

  function user_can_delete() {
	return adesk_js_admin.pg_user_delete == 1;
  }

function adesk_group_row_options(ary, row) {
  return ary;
}

'; ?>

</script>
<tr valign="top">
  <td><?php echo ((is_array($_tmp='Lists')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
  <td>
	<select name="lists" id="group_lists" multiple size="5" style="width:99%;">
	  <?php $_from = $this->_tpl_vars['lists']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['l']):
?>
	  <option value="<?php echo $this->_tpl_vars['l']['id']; ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['l']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('truncate', true, $_tmp, 50) : smarty_modifier_truncate($_tmp, 50)); ?>
</option>
	  <?php endforeach; endif; unset($_from); ?>
	</select>
  <div>
    <?php echo ((is_array($_tmp="Select:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

    <a href="#" onclick="return adesk_form_select_multiple_all($('group_lists'));"><?php echo ((is_array($_tmp='All')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
    &middot;
    <a href="#" onclick="return adesk_form_select_multiple_none($('group_lists'));"><?php echo ((is_array($_tmp='None')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
    <?php echo ((is_array($_tmp="Notice: This action will be performed on each selected list! Hold CTRL to select multiple lists.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

  </div>
  </td>
</tr>
<?php if (! $this->_tpl_vars['__ishosted']): ?>
<tr valign="top">
  <td><?php echo ((is_array($_tmp='Sending methods')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
  <td>
	<select name="sendmethods[]" id="group_sendmethods" multiple size="5" style="width:99%;">
	  <?php $_from = $this->_tpl_vars['sendmethods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['m']):
?>
	  <option value="<?php echo $this->_tpl_vars['m']['id']; ?>
">
	  <?php if ($this->_tpl_vars['m']['type'] == 0): ?>
	  <?php echo ((is_array($_tmp="Default mail() method")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  <?php elseif ($this->_tpl_vars['m']['type'] == 3): ?>
	  <?php echo ((is_array($_tmp='Sendmail method')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  <?php else: ?>
	  <?php echo $this->_tpl_vars['m']['host']; ?>
 <?php echo ((is_array($_tmp="(SMTP)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  <?php endif; ?>
	  </option>
	  <?php endforeach; endif; unset($_from); ?>
	</select>
  <div>
    <?php echo ((is_array($_tmp="Select:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

    <a href="#" onclick="return adesk_form_select_multiple_all($('group_sendmethods'));"><?php echo ((is_array($_tmp='All')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
    &middot;
    <a href="#" onclick="return adesk_form_select_multiple_none($('group_sendmethods'));"><?php echo ((is_array($_tmp='None')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
    <?php echo ((is_array($_tmp="Notice: This action will be performed on each selected list! Hold CTRL to select multiple lists.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

  </div>
  </td>
</tr>
<?php endif; ?>
</table> 

<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="5" id="group_perms_box">
<tr>
  <td colspan="2"><h3><?php echo ((is_array($_tmp='Permissions')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3></td>
</tr>
<tr>
  <td><?php echo ((is_array($_tmp='Lists')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
  <td>
    <label><input type="checkbox" name="pg_list_add" id="group_pg_list_add" value="1" /><?php echo ((is_array($_tmp='Add')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
    <label><input type="checkbox" name="pg_list_edit" id="group_pg_list_edit" value="1" /><?php echo ((is_array($_tmp='Edit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
    <label><input type="checkbox" name="pg_list_delete" id="group_pg_list_delete" value="1" /><?php echo ((is_array($_tmp='Delete')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
  </td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>
	<label><input type="checkbox" name="pg_list_opt" id="group_pg_list_opt" value="1" /><?php echo ((is_array($_tmp='Manage Email Confirmation Sets')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
	<label><input type="checkbox" name="pg_list_headers" id="group_pg_list_headers" value="1" /><?php echo ((is_array($_tmp='Manage Custom Email Headers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
  </td>
</tr>
<?php if (! $this->_tpl_vars['__ishosted']): ?>
<tr>
  <td>&nbsp;</td>
  <td>
	<label><input type="checkbox" name="pg_list_emailaccount" id="group_pg_list_emailaccount" value="1" /><?php echo ((is_array($_tmp="Manage (Un)Subscribe By Email")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
	<label><input type="checkbox" name="pg_list_bounce" id="group_pg_list_bounce" value="1" /><?php echo ((is_array($_tmp='Manage Bounce Settings')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
  </td>
</tr>
<?php endif; ?>
<tr>
  <td><?php echo ((is_array($_tmp='Campaigns')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
  <td>
    <label><input type="checkbox" name="pg_message_add" id="group_pg_message_add" value="1" /><?php echo ((is_array($_tmp='Add')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
    <label><input type="checkbox" name="pg_message_edit" id="group_pg_message_edit" value="1" /><?php echo ((is_array($_tmp='Edit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
    <label><input type="checkbox" name="pg_message_delete" id="group_pg_message_delete" value="1" /><?php echo ((is_array($_tmp='Delete')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
    <label><input type="checkbox" name="pg_message_send" id="group_pg_message_send" value="1" onclick="group_permission_display('group_pg_message_send');" /><?php echo ((is_array($_tmp='Send')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
  </td>
</tr>
<tr>
  <td><?php echo ((is_array($_tmp='Subscribers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
  <td>
    <label><input type="checkbox" name="pg_subscriber_add" id="group_pg_subscriber_add" value="1" /><?php echo ((is_array($_tmp='Add')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
    <label><input type="checkbox" name="pg_subscriber_edit" id="group_pg_subscriber_edit" value="1" /><?php echo ((is_array($_tmp='Edit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
    <label><input type="checkbox" name="pg_subscriber_delete" id="group_pg_subscriber_delete" value="1" /><?php echo ((is_array($_tmp='Delete')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
    <label><input type="checkbox" name="pg_subscriber_import" id="group_pg_subscriber_import" value="1" /><?php echo ((is_array($_tmp='Import')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
    <label><input type="checkbox" name="pg_subscriber_export" id="group_pg_subscriber_export" value="1" /><?php echo ((is_array($_tmp='Export')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
    <label><input type="checkbox" name="pg_subscriber_sync" id="group_pg_subscriber_sync" value="1" /><?php echo ((is_array($_tmp='Sync')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
    <label><input type="checkbox" name="pg_subscriber_approve" id="group_pg_subscriber_approve" value="1" /><?php echo ((is_array($_tmp='Approve')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
  </td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>
    <label><input type="checkbox" name="pg_subscriber_filters" id="group_pg_subscriber_filters" value="1" /><?php echo ((is_array($_tmp='Subscriber List Segments')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
		<label><input type="checkbox" name="pg_subscriber_actions" id="group_pg_subscriber_actions" value="1" /><?php echo ((is_array($_tmp='Subscriber Actions')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
		<label><input type="checkbox" name="pg_subscriber_fields" id="group_pg_subscriber_fields" value="1" /><?php echo ((is_array($_tmp='Subscriber Fields')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
  </td>
</tr>

<tr>
  <td><?php echo ((is_array($_tmp='Subscription Forms')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
  <td>
	<label><input type="checkbox" name="pg_form_add" id="group_pg_form_add" value="1" /><?php echo ((is_array($_tmp='Add')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
	<label><input type="checkbox" name="pg_form_edit" id="group_pg_form_edit" value="1" /><?php echo ((is_array($_tmp='Edit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
	<label><input type="checkbox" name="pg_form_delete" id="group_pg_form_delete" value="1" /><?php echo ((is_array($_tmp='Delete')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
  </td>
</tr>
<tr>
  <td><?php echo ((is_array($_tmp='Templates')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
  <td>
  	<label><input type="checkbox" name="pg_template_add" id="group_pg_template_add" value="1" /><?php echo ((is_array($_tmp='Add')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
		<label><input type="checkbox" name="pg_template_edit" id="group_pg_template_edit" value="1" /><?php echo ((is_array($_tmp='Edit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
		<label><input type="checkbox" name="pg_template_delete" id="group_pg_template_delete" value="1" /><?php echo ((is_array($_tmp='Delete')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
  </td>
</tr>
<tr>
  <td><?php echo ((is_array($_tmp='Reports')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
  <td>
  	<label><input type="checkbox" name="pg_reports_campaign" id="group_pg_reports_campaign" value="1" /><?php echo ((is_array($_tmp='Campaign')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
	<label><input type="checkbox" name="pg_reports_list" id="group_pg_reports_list" value="1" /><?php echo ((is_array($_tmp='List')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
	<label><input type="checkbox" name="pg_reports_user" id="group_pg_reports_user" value="1" /><?php echo ((is_array($_tmp='User')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
	<label><input type="checkbox" name="pg_reports_trend" id="group_pg_reports_trend" value="1" /><?php echo ((is_array($_tmp='Trends')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
  </td>
</tr>
<tr>
  <td><?php echo ((is_array($_tmp='Users')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
  <td>
    <label><input type="checkbox" name="pg_user_add" id="group_pg_user_add" value="1" /><?php echo ((is_array($_tmp='Add')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
    <label><input type="checkbox" name="pg_user_edit" id="group_pg_user_edit" value="1" /><?php echo ((is_array($_tmp='Edit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
    <label><input type="checkbox" name="pg_user_delete" id="group_pg_user_delete" value="1" /><?php echo ((is_array($_tmp='Delete')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
  </td>
</tr>
<tr>
  <td colspan="2">
    <?php echo ((is_array($_tmp="Select:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

    <a href="#" onclick="return adesk_form_check_selection_element_all('group_perms_box', true);"><?php echo ((is_array($_tmp='All')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
    &middot;
    <a href="#" onclick="return adesk_form_check_selection_element_all('group_perms_box', false);"><?php echo ((is_array($_tmp='None')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
  </td>
</tr>
</table></div>

<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="5">
<tr>
  <td colspan="2"><h3><?php echo ((is_array($_tmp='Limits')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3></td>
</tr>
<tr id="group_limit_mail_tr">
  <td colspan="2">
	<input type="checkbox" name="group_limit_mail_checkbox" id="group_limit_mail_checkbox" onclick="group_limitdisplay('group_div_limit_mail', this.checked)" /> <label for="group_limit_mail_checkbox"><?php echo ((is_array($_tmp='Limit emails sent')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
	<div id="group_div_limit_mail" style="display:none; margin-left:40px;">
	  <input type="text" name="limit_mail" id="group_limit_mail" style="width:40px;">
	  <select name="limit_mail_type" id="group_limit_mail_type">
		<option value="day"><?php echo ((is_array($_tmp='per day')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		<option value="week"><?php echo ((is_array($_tmp='per week')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		<option value="month"><?php echo ((is_array($_tmp="per month (last 30 days)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		<option value="month1st"><?php echo ((is_array($_tmp="per calendar month (counting from the 1st)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		<option value="monthcdate"><?php echo ((is_array($_tmp="per calendar month (counting from the user's creation date)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		<option value="year"><?php echo ((is_array($_tmp='per year')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		<option value="ever"><?php echo ((is_array($_tmp='for all time')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
	  </select>
	</div>
  </td>
</tr>
<tr>
  <td colspan="2">
		<input type="checkbox" name="group_limit_subscriber_checkbox" id="group_limit_subscriber_checkbox" onclick="group_limitdisplay('group_div_limit_subscriber', this.checked)" /> <label for="group_limit_subscriber_checkbox"><?php echo ((is_array($_tmp='Limit subscribers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
		<div id="group_div_limit_subscriber" style="display:none; margin-left:40px;">
		  <input type="text" name="limit_subscriber" id="group_limit_subscriber" style="width:40px;">
		</div>
  </td>
</tr>
<tr>
  <td colspan="2">
		<input type="checkbox" name="group_limit_list_checkbox" id="group_limit_list_checkbox" onclick="group_limitdisplay('group_div_limit_list', this.checked)" /> <label for="group_limit_list_checkbox"><?php echo ((is_array($_tmp='Limit lists')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
		<div id="group_div_limit_list" style="display:none; margin-left:40px;">
		  <input type="text" name="limit_list" id="group_limit_list" style="width:40px;">
		</div>
  </td>
</tr>
<tr id="group_limit_campaign_tr">
  <td colspan="2">
		<input type="checkbox" name="group_limit_campaign_checkbox" id="group_limit_campaign_checkbox" onclick="group_limitdisplay('group_div_limit_campaign', this.checked)" /> <label for="group_limit_campaign_checkbox"><?php echo ((is_array($_tmp='Limit campaigns')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
		<div id="group_div_limit_campaign" style="display:none; margin-left:40px;">
		  <input type="text" name="limit_campaign" id="group_limit_campaign" style="width:40px;">
		  <select name="limit_campaign_type" id="group_limit_campaign_type">
			<option value="day"><?php echo ((is_array($_tmp='per day')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
			<option value="week"><?php echo ((is_array($_tmp='per week')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
			<option value="month"><?php echo ((is_array($_tmp="per month (last 30 days)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
			<option value="month1st"><?php echo ((is_array($_tmp="per calendar month (counting from the 1st)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
			<option value="monthcdate"><?php echo ((is_array($_tmp="per calendar month (counting from the user's creation date)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
			<option value="year"><?php echo ((is_array($_tmp='per year')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
			<option value="ever"><?php echo ((is_array($_tmp='for all time')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		  </select>
		</div>
  </td>
</tr>
<?php if (! $this->_tpl_vars['__ishosted']): ?>
<tr>
  <td colspan="2">
		<input type="checkbox" name="group_limit_attachment_checkbox" id="group_limit_attachment_checkbox" onclick="group_limitdisplay('group_div_limit_attachment', this.checked)" /> <label for="group_limit_attachment_checkbox"><?php echo ((is_array($_tmp='Limit attachments')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
		<div id="group_div_limit_attachment" style="display:none; margin-left:40px;">
		  <input type="text" name="limit_attachment" id="group_limit_attachment" style="width:40px;">
		  <?php echo ((is_array($_tmp='per message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		</div>
  </td>
</tr>
<?php endif; ?>
<tr>
  <td colspan="2">
		<input type="checkbox" name="group_limit_user_checkbox" id="group_limit_user_checkbox" onclick="group_limitdisplay('group_div_limit_user', this.checked)" /> <label for="group_limit_user_checkbox"><?php echo ((is_array($_tmp='Limit users')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
		<div id="group_div_limit_user" style="display:none; margin-left:40px;">
		  <input type="text" name="limit_user" id="group_limit_user" style="width:40px;">
		</div>
  </td>
</tr>
<?php if (! $this->_tpl_vars['__ishosted']): ?>
<tr>
  <td colspan="2">
  	<input type="checkbox" name="unsubscribelink" id="group_unsubscribelink_checkbox" /> <label for="group_unsubscribelink_checkbox"><?php echo ((is_array($_tmp='Require unsubscribe link on all outgoing emails')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
  </td>
</tr>
<?php endif; ?>
<tr>
  <td colspan="2">
  	<input type="checkbox" name="optinconfirm" id="group_optinconfirm_checkbox" /> <label for="group_optinconfirm_checkbox"><?php echo ((is_array($_tmp="Require opt-in email confirmation for all new subscribers")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
  </td>
</tr>

<tr <?php if ($this->_tpl_vars['__ishosted']): ?>style="display:none;"<?php endif; ?>>
  <td colspan="2">
  	<label>
      <input type="checkbox" name="req_approval" id="group_req_approval_checkbox" onclick="adesk_dom_toggle_display('group_div_req_approval', 'block');" />
      <?php echo ((is_array($_tmp='Require approval of campaigns before sending starts')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

    </label>
    <div id="group_div_req_approval" style="display:none;  margin-left:40px;">
      <div>
        <?php echo ((is_array($_tmp="For the first %s campaign(s).")) ? $this->_run_mod_handler('alang', true, $_tmp, '<input type="text" id="group_req_approval_1st" name="req_approval_1st" value="2" size="2" />') : smarty_modifier_alang($_tmp, '<input type="text" id="group_req_approval_1st" name="req_approval_1st" value="2" size="2" />')); ?>

      </div>
      <div>
        <?php echo ((is_array($_tmp="Send an email notification when a campaign requires approval:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
        <input type="text" id="group_req_approval_notify" name="req_approval_notify" value="<?php echo $this->_tpl_vars['admin']['email']; ?>
" size="30" />
      </div>
      <div>
      </div>
    </div>
  </td>
</tr>

<tr <?php if ($this->_tpl_vars['__ishosted']): ?>style="display:none;"<?php endif; ?>>
  <td colspan="2">
    <label>
      <input type="checkbox" name="forcesenderinfo" id="group_forcesenderinfo_checkbox" />
      <?php echo ((is_array($_tmp='Require senders contact information for all lists')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

    </label>
  </td>
</tr>

<!-- the end of the table is in /awebdesk/templates/group.form.htm -->