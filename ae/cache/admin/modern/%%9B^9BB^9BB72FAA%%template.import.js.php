<?php /* Smarty version 2.6.12, created on 2016-07-08 14:47:32
         compiled from template.import.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'template.import.js', 1, false),array('modifier', 'js', 'template.import.js', 1, false),)), $this); ?>
var template_import_str_cant_import = '<?php echo ((is_array($_tmp=((is_array($_tmp='You do not have permission to import templates')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var template_import_str_stock = '<?php echo ((is_array($_tmp=((is_array($_tmp='All missing global stock templates have been imported')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
//var template_form_str_cant_update = '<?php echo ((is_array($_tmp=((is_array($_tmp='You do not have permission to edit Templates')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
//var template_form_str_cant_find   = '<?php echo ((is_array($_tmp=((is_array($_tmp="Template not found.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

<?php echo '

function template_import_defaults() {
	$("nameImportField").value = \'\';
	//$("fileImportField").value = \'\';

	adesk_dom_remove_children($("template_import_upload_td"));
	var upload_stuff = $("template_import_upload_div").cloneNode(true);
	upload_stuff.id = "template_import_upload_div2";
	$("template_import_upload_td").appendChild(upload_stuff);
	$("template_import_upload_div2").className = "";

	if ( template_listfilter && typeof(template_listfilter) == \'object\' ) {
		adesk_form_select_multiple($(\'parentsList2\'), template_listfilter);
	} else if ( template_listfilter > 0 ) {
		$(\'parentsList2\').value = template_listfilter;
	} else {
		adesk_form_select_multiple_all($(\'parentsList2\'));
	}

	if ( $(\'template_scope_specific2\') ) $(\'template_scope_specific2\').checked = true;
	$(\'template_import_lists\').show();
}

function template_import_load() {
	if (adesk_js_admin.pg_template_add != 1) {
		adesk_ui_anchor_set(template_list_anchor());
		alert(template_import_str_cant_import);
		return;
	}
	template_import_defaults();
	$("import").className = "adesk_block";
}

function template_import_save() {
	var post = adesk_form_post($("import"));
	adesk_ui_api_call(jsImporting);

	adesk_ajax_post_cb("awebdeskapi.php", "template.template_import_post", template_import_save_cb, post);
}

function template_import_save_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(template_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}
}

function template_import_lists_toggle_scope(value) {
	if (value == \'all\') {
		$(\'template_import_lists\').hide();
	}
	else {
		$(\'template_import_lists\').show();
	}
}

function template_import_stock() {
	adesk_ui_api_call(jsLoading);
	adesk_ajax_call_cb("awebdeskapi.php", "em.import_files", template_import_stock_cb, "template", "xml", "null", 0);
}

function template_import_stock_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();
	adesk_result_show(template_import_str_stock);
	//adesk_error_show(ary.message);
}

'; ?>
