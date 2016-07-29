<?php /* Smarty version 2.6.12, created on 2016-07-08 16:22:26
         compiled from loginsource.form.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'loginsource.form.js', 1, false),array('modifier', 'js', 'loginsource.form.js', 1, false),)), $this); ?>
var loginsource_form_str_cant_insert = '<?php echo ((is_array($_tmp=((is_array($_tmp='You do not have permission to add login sources')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var loginsource_form_str_cant_update = '<?php echo ((is_array($_tmp=((is_array($_tmp='You do not have permission to edit login sources')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var loginsource_form_str_needgroup   = '<?php echo ((is_array($_tmp=((is_array($_tmp='You must assign at least one group for users to be imported into')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
<?php echo '
var loginsource_form_id = 0;

function loginsource_form_defaults() {
	$("form_id").value          = 0;
	$("form_enabled").checked   = true;
	$("form_name").innerHTML    = "";
	$("form_host").value        = "";
	$("form_port").value        = "";
	$("form_user").value        = "";
	$("form_pass").value        = "";
	$("form_dbname").value      = "";
	$("form_tableprefix").value = "";
	$("form_basedn").value      = "";
	$("form_loginusesdn").checked = false;
	$("form_loginattr").value   = "";
	$("form_binddn").value      = "";
	$("form_bindpw").value      = "";
	$("form_userattr").value    = "";

	if (typeof loginsource_defaults_extended == "function")
		loginsource_defaults_extended();

	$A($("form_groupset").getElementsByTagName("option")).each(function(item) { item.selected = false; });
}

function loginsource_form_load(id) {
	loginsource_form_defaults();
	loginsource_form_id = id;

	if (id > 0) {
		if (adesk_js_admin.id != 1) {
			adesk_ui_anchor_set(loginsource_list_anchor());
			alert(loginsource_form_str_cant_update);
			return;
		}

		adesk_ui_api_call(jsLoading);
		$("form_submit").className = "adesk_button_update";
		$("form_submit").value = jsUpdate;
		adesk_ajax_call_cb("awebdeskapi.php", "loginsource!adesk_loginsource_select_row", loginsource_form_load_cb, id);
	} else {
		adesk_ui_anchor_set(loginsource_list_anchor());
		alert(loginsource_form_str_cant_insert);
		return;
	}
}

function loginsource_form_load_cb(xml) {
	var ary = adesk_dom_read_node(xml, null);
	adesk_ui_api_callback();
	loginsource_form_id = ary.id;

	$("form_id").value          = ary.id;
	$("loginsourceid").innerHTML    = adesk_str_htmlescape(ary.id);
	$("form_name").innerHTML    = adesk_str_htmlescape(ary.ident);
	$("form_enabled").checked   = ary.enabled == 1;
	$("form_host").value        = ary.host;
	$("form_port").value        = ary.port;
	$("form_user").value        = ary.user;
	$("form_pass").value        = ary.pass;
	$("form_dbname").value      = ary.dbname;
	$("form_tableprefix").value = ary.tableprefix;
	$("form_amsproductid").value = ary.amsproductid;

	if (ary.vars.match(/,basedn/)) {
		$("form_basedn").value      = ary.basedn;
		$("form_loginusesdn").checked = (ary.loginusesdn == 1);
		$("form_loginattr").value   = ary.loginattr;
		$("form_binddn").value      = ary.binddn;
		$("form_bindpw").value      = ary.bindpw;
		$("form_userattr").value    = ary.userattr;
	} else if (ary.vars.match(/,ad/)) {
		$("form_ad_basedn").value      = ary.basedn;
		$("form_ad_admin_dn").value      = ary.binddn;
		$("form_ad_admin_pw").value      = ary.bindpw;
	}

	var gset = [];

	if (ary.groupset != "")
		gset = ary.groupset.toString().split(",");

	$A($("form_groupset").getElementsByTagName("option")).each(function(item) { item.selected = (gset.indexOf(item.value) > -1); });

	$("form_tbody_host").className        = "adesk_hidden";
	$("form_tbody_port").className        = "adesk_hidden";
	$("form_tbody_user").className        = "adesk_hidden";
	$("form_tbody_pass").className        = "adesk_hidden";
	$("form_tbody_dbname").className      = "adesk_hidden";
	$("form_tbody_tableprefix").className = "adesk_hidden";
	$("form_tbody_amsproductid").className = "adesk_hidden";
	$("form_tbody_basedn").className      = "adesk_hidden";
	$("form_tbody_ad").className          = "adesk_hidden";

	if (ary.vars != "") {
		var list = ary.vars.split(",");

		for (var i = 0; i < list.length; i++) {
			if ($("form_tbody_" + list[i]) !== null) {
				$("form_tbody_" + list[i]).className = "";
			}
		}
	}

	if (typeof loginsource_load_extended == "function")
		loginsource_load_extended(ary);

	$("form").className = "adesk_block";
}

function loginsource_form_save(id) {
	var post = adesk_form_post($("form"));

	if ($("form_groupset").value == "") {
		alert(loginsource_form_str_needgroup);
		return false;
	}

	adesk_ui_api_call(jsSaving);

	if (id > 0)
		adesk_ajax_post_cb("awebdeskapi.php", "loginsource!adesk_loginsource_update_post", loginsource_form_save_cb, post);
}

function loginsource_form_save_cb(xml) {
	var ary = adesk_dom_read_node(xml, null);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(loginsource_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}
}
'; ?>
