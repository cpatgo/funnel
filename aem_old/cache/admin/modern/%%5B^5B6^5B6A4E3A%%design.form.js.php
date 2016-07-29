<?php /* Smarty version 2.6.12, created on 2016-07-08 14:21:03
         compiled from design.form.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'design.form.js', 1, false),array('modifier', 'js', 'design.form.js', 1, false),array('function', 'jsvar', 'design.form.js', 8, false),)), $this); ?>
var design_form_str_cant_insert = '<?php echo ((is_array($_tmp=((is_array($_tmp='Please add a new admin group if you wish to have more Design Settings')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var design_form_str_cant_find   = '<?php echo ((is_array($_tmp=((is_array($_tmp="Design Settings not found.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var design_form_str_twitter1 = '<?php echo ((is_array($_tmp=((is_array($_tmp="If you remove the Twitter keys, the system will use the default source when sharing with Twitter. Is this okay?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var design_form_str_facebook1 = '<?php echo ((is_array($_tmp=((is_array($_tmp="If you remove the Facebook app keys, the system will use the default source when sharing with Facebook. Is this okay?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var design_demomode_str1 = '<?php echo ((is_array($_tmp=((is_array($_tmp="Warning! Once you save this form with this box checked, you will not be able to get out of demo mode through the mailing software.\n\nTo get out of demo mode, you will have to go to phpMyAdmin, click on the awebdesk_branding table, and set demo_mode=0 for the associated groupid row ")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var design_demomode_str2 = '<?php echo ((is_array($_tmp=((is_array($_tmp="This prevents your visitors from taking the mailing software out of demo mode while browsing around.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

<?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['admin_template_htm'],'name' => 'admin_template_htm'), $this);?>
;
<?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['public_template_htm'],'name' => 'public_template_htm'), $this);?>
;

var __ishosted = false;
<?php if ($this->_tpl_vars['__ishosted']): ?>
	__ishosted = true;
<?php endif; ?>

<?php echo '

var design_form_id = 0;

function design_form_defaults() {
	$("form_id").value = 0;

	$("header_text").checked = false;
	$("header_html").checked = false;
	$("footer_text").checked = false;
	$("footer_html").checked = false;
	$("header_text_div").className = "adesk_hidden";
	$("header_html_div").className = "adesk_hidden";
	$("footer_text_div").className = "adesk_hidden";
	$("footer_html_div").className = "adesk_hidden";

	$("logo_source").value = "url";
	$("design_upload_div").hide();
	$("design_url").value = adesk_js_site["p_link"] + "/manage/images/logo.gif";
	$("design_image").src = adesk_js_site["p_link"] + "/manage/images/logo.gif";

	$(\'admin_form_template_show\').checked = false;
	$(\'admin_form_template\').value = admin_template_htm;
	$(\'admin_box_template\').className = \'adesk_hidden\';
	$(\'admin_form_style_show\').checked = false;
	$(\'admin_form_style\').value = \'\';
	$(\'admin_box_style\').className = \'adesk_hidden\';

	$(\'public_form_template_show\').checked = false;
	$(\'public_form_template\').value = public_template_htm;
	$(\'public_box_template\').className = \'adesk_hidden\';
	$(\'public_form_style_show\').checked = false;
	$(\'public_form_style\').value = \'\';
	$(\'public_box_style\').className = \'adesk_hidden\';
	$(\'design_advanced_tbody\').hide();
}

function design_form_load(id) {
	design_form_defaults();
	design_form_id = id;

	if (id > 2) {
		adesk_ui_api_call(jsLoading);
		if ($("form_submit")) {
			$("form_submit").className = "adesk_button_update";
			$("form_submit").value = jsUpdate;
		}
		adesk_ajax_call_cb("awebdeskapi.php", "design.design_select_row", design_form_load_cb, id);
	} else {
		adesk_ui_anchor_set(design_list_anchor());
		alert(design_form_str_cant_insert);
		return;
	}
}

function design_form_load_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();
	if ( !ary.id ) {
		adesk_error_show(design_form_str_cant_find);
		adesk_ui_anchor_set(design_list_anchor());
		return;
	}
	design_form_id = ary.id;

	$("form_id").value = ary.id;

	$("site_name").value = ary.site_name;

	$(\'_attachments__iframe\').src = \'upload.php?action=design_upload&id=_attachments_&relid=\' + ary.id + \'&limit=1&name=_attachments_\';
	design_toggle_source(\'url\');
	$("design_url").value = ( ary.site_logo != \'\' ? ary.site_logo : adesk_js_site["p_link"] + "/manage/images/logo.gif" );
	$("design_image").src = $("design_url").value;

	if ( ary.id == 3 ) {
		$("design_logo_row").className = \'adesk_table_rowgroup\';
		$("design_image_div").show();
	} else {
		$("design_logo_row").className = \'adesk_hidden\';
		$("design_image_div").hide();
	}

	$("header_text").checked = ary.header_text;
	$("header_text_value").value = ary.header_text_value;
	$("header_html").checked = ary.header_html;
	adesk_form_value_set($("header_html_valueEditor"), ary.header_html_value);
	$("footer_text").checked = ary.footer_text;
	$("footer_text_value").value = ary.footer_text_value;
	$("footer_html").checked = ary.footer_html;
	adesk_form_value_set($("footer_html_valueEditor"), ary.footer_html_value);

	$("form").className = "adesk_block";

	design_toggle_editor("header", "text");
	design_toggle_editor("header", "html");
	design_toggle_editor("footer", "text");
	design_toggle_editor("footer", "html");

	$("copyright").checked = !ary.copyright;
	$("version").checked = !ary.version;
	$("license").checked = !ary.license;
	$("links").checked = !ary.links;
	$("help").checked = !ary.help;
	$("demo").checked = ary.demo;

	$(\'admin_form_template_show\').checked = ary.admin_template_htm != \'\';
	if ( ary.admin_template_htm != \'\' ) $(\'admin_form_template\').value = ary.admin_template_htm;
	$(\'admin_box_template\').className = ary.admin_template_htm != \'\' ? \'adesk_blockquote\' : \'adesk_hidden\';
	$(\'admin_form_style_show\').checked = ary.admin_template_css != \'\';
	$(\'admin_form_style\').value = ary.admin_template_css;
	$(\'admin_box_style\').className = ary.admin_template_css != \'\' ? \'adesk_blockquote\' : \'adesk_hidden\';

	$(\'public_form_template_show\').checked = ary.public_template_htm != \'\';
	if ( ary.public_template_htm != \'\' ) $(\'public_form_template\').value = ary.public_template_htm;
	$(\'public_box_template\').className = ary.public_template_htm != \'\' ? \'adesk_blockquote\' : \'adesk_hidden\';
	$(\'public_form_style_show\').checked = ary.public_template_css != \'\';
	$(\'public_form_style\').value = ary.public_template_css;
	$(\'public_box_style\').className = ary.public_template_css != \'\' ? \'adesk_blockquote\' : \'adesk_hidden\';

	$("form").className = "adesk_block";
}

function design_form_save(id) {
	var post = adesk_form_post($("form"));

	if (post.twitter_key == "" || post.twitter_secret == "") {
		if (!confirm(design_form_str_twitter1)) {
			return;
		}
	}

	if (post.facebook_id == "" || post.facebook_secret == "") {
		if (!confirm(design_form_str_facebook1)) {
			return;
		}
	}

	if ( typeof post.admin_template_show != \'undefined\' ) {
		if ( !post.admin_template.match(/%PAGECONTENT%/) ) {
			alert(desk_form_str_template_empty);
			$(\'admin_form_template\').focus();
			return;
		}
	}

	if ( typeof post.public_template_show != \'undefined\' ) {
		if ( !post.public_template.match(/%PAGECONTENT%/) ) {
			alert(desk_form_str_template_empty);
			$(\'public_form_template\').focus();
			return;
		}
	}

	adesk_ui_api_call(jsSaving);
	adesk_ajax_post_cb("awebdeskapi.php", "design.design_update_post", design_form_save_cb, post);
}

function design_form_save_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded && ary.succeeded == "1") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(design_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}
}

function design_toggle_source(source) {
	if (source == "upload") {
		$("design_upload_div").show();
		$("design_url_div").hide();
		$("design_image_div").hide();
	}
	else {
		$("design_upload_div").hide();
		$("design_url_div").show();
		$("design_image_div").show();
	}
}

function design_toggle_editor(section, type) {
	// If the checkbox is checked, show sub-div, otherwise hide
	if ( $(section + "_" + type).checked == true ) {
		$(section + "_" + type + "_div").className = "";
	}
	else {
		$(section + "_" + type + "_div").className = "adesk_hidden";
	}
}

function design_toggle_editor_content(section, type) {
	// If the checkbox is unchecked, clear editor contents
	if ( $(section + "_" + type).checked == false ) {
		if (type == "text") {
			$(section + "_" + type + "_value").value = "";
		}
		else {
			adesk_form_value_set($(section + "_" + type + "_valueEditor"), "");
		}
	}
}

function design_preview_url() {
	if ( adesk_str_is_url($("design_url").value) ) {
		$("design_image_div").show();
		$("design_image").src = $("design_url").value;
	}
}

function design_demomode_alert() {
	if ($("demo").checked) {
		alert(design_demomode_str1 + "(WHERE groupid=" + design_form_id + ")\\n\\n" + design_demomode_str2);
	}
}

function design_advanced_toggle() {
	if ( $(\'design_advanced_tbody\').style.display == \'none\' ) {
		$(\'design_advanced_tbody\').show();
	}
	else {
		$(\'design_advanced_tbody\').hide();
	}
}

'; ?>
