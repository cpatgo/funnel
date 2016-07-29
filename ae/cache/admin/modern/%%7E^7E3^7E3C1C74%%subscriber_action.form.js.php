<?php /* Smarty version 2.6.12, created on 2016-07-18 12:03:31
         compiled from subscriber_action.form.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'subscriber_action.form.js', 1, false),array('modifier', 'js', 'subscriber_action.form.js', 1, false),array('modifier', 'default', 'subscriber_action.form.js', 9, false),)), $this); ?>
var subscriber_action_form_str_cant_insert = '<?php echo ((is_array($_tmp=((is_array($_tmp='You do not have permission to add Subscription Rule')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_action_form_str_cant_update = '<?php echo ((is_array($_tmp=((is_array($_tmp='You do not have permission to edit Subscription Rule')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_action_form_str_cant_update = '<?php echo ((is_array($_tmp=((is_array($_tmp="Subscription Rule not found.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_action_form_str_any         = '<?php echo ((is_array($_tmp=((is_array($_tmp='Any')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_action_form_str_needname    = '<?php echo ((is_array($_tmp=((is_array($_tmp="You must enter a name before saving.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_action_form_str_needact     = '<?php echo ((is_array($_tmp=((is_array($_tmp="You must give at least one action before saving.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_action_form_str_actions     = '<?php echo ((is_array($_tmp=((is_array($_tmp="Action(s)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var subscriber_action_form_fromcampaign    = <?php echo ((is_array($_tmp=@$this->_tpl_vars['fromcampaign'])) ? $this->_run_mod_handler('default', true, $_tmp, 'false') : smarty_modifier_default($_tmp, 'false')); ?>
;
var subscriber_action_form_linkidx         = '';
var subscriber_action_form_linkid          = 0;

<?php echo '
var subscriber_action_form_id = 0;

function subscriber_action_form_defaults() {
	if ( $("form_id") ) {
		$("form_id").value = 0;
	}

	if ($("form_name"))
		$("form_name").value = \'\';
	$("form_listid").value = 0;

	if ($("form_campaignid"))
		$("form_campaignid").value = 0;

	$A(document.getElementsByTagName("input")).each(function(rad) {
			if (rad.type == "radio" && rad.name == "type" && rad.value == "read")
				rad.checked = true;
			else if (rad.type == "text" && rad.name.match(/^form_/))
				rad.value = "";
		});

	$A(document.getElementsByTagName("select")).each(function(sel) {
			if (sel.name.match(/^link(value|action)/))
				sel.selectedIndex = 0;

			if (sel.name.match(/^linkaction/))
				campaign_action_changed(sel.parentNode, true);
		});
}

function subscriber_action_form_loadcampaigns(listid) {
	adesk_ajax_call_cb("awebdeskapi.php", "campaign.campaign_selectdropdown_bylist", adesk_ajax_cb(subscriber_action_form_loadcampaigns_cb), listid);
}

function subscriber_action_form_loadcampaigns_cb(ary) {
	var oldid = $("form_campaignid").value;
	adesk_dom_remove_children($("form_campaignid"));

	$("form_campaignid").appendChild(Builder.node("option", { value: "0" }, subscriber_action_form_str_any));

	if(ary.row)
	{
		for (var i = 0; i < ary.row.length; i++) {
			$("form_campaignid").appendChild(Builder.node("option", { value: ary.row[i].id }, adesk_str_shorten(ary.row[i].name, 16)));
		}
	}

	$("form_campaignid").value = "0";

	/*
	if (oldid == 0 && ary.row.length > 0) {
		$("form_campaignid").value = ary.row[0].id;
		subscriber_action_form_loadlinks(ary.row[0].id);
	} else {
		$("form_campaignid").value = oldid;
		subscriber_action_form_loadlinks(oldid);
	}
	*/
}

function subscriber_action_form_loadlinks(campaignid) {
	adesk_ajax_call_cb("awebdeskapi.php", "link.link_selectdropdown_bycampaign", adesk_ajax_cb(subscriber_action_form_loadlinks_cb), campaignid);
}

function subscriber_action_form_loadlinks_cb(ary) {
	var oldid = $("form_linkid").value;
	adesk_dom_remove_children($("form_linkid"));

	$("form_linkid").appendChild(Builder.node("option", { value: "0" }, subscriber_action_form_str_any));

	if(ary.row)
	{
		for (var i = 0; i < ary.row.length; i++) {
			$("form_linkid").appendChild(Builder.node("option", { value: ary.row[i].id }, ary.row[i].link));
		}
	}

	$("form_linkid").value = oldid;
}

function subscriber_action_form_loadparts(ary) {
	var divs = $A($$(".action_box"));
	var div;
	var i;

	for (i = 1; i < divs.length; i++) {
		$("actionClonerDiv").removeChild(divs[i]);
	}

	for (i = 0; i < ary.row.length; i++) {
		if (i > 0)
			clone_1st_div($(\'actionClonerDiv\'));
		divs = $A($$(".action_box"));
		div = divs[divs.length - 1];

		$A(div.getElementsByTagName("select")).each(function(a) { if (a.name.match(/^linkaction/)) a.value = ary.row[i].act; });
		$A(div.getElementsByTagName("select")).each(function(a) { if (a.name.match(/^linkvalue/)) $(a).hide(); });
		$A(div.getElementsByTagName("input")).each(function(a) { if (a.name.match(/^linkvalue/))  $(a).hide(); });

		switch (ary.row[i].act) {
			case "subscribe":
			case "unsubscribe":
				$A(div.getElementsByTagName("select")).each(function(a) { if (a.name.match(/^linkvalue1/)) a.value = ary.row[i].targetid; });
				$A(div.getElementsByTagName("select")).each(function(a) { if (a.name.match(/^linkvalue1/)) $(a).show(); });
				break;

			case "send":
				$A(div.getElementsByTagName("select")).each(function(a) { if (a.name.match(/^linkvalue2/)) a.value = ary.row[i].targetid; });
				$A(div.getElementsByTagName("select")).each(function(a) { if (a.name.match(/^linkvalue2/)) $(a).show(); });
				break;

			case "update":
				$A(div.getElementsByTagName("select")).each(function(a) { if (a.name.match(/^linkvalue3/)) a.value = (ary.row[i].targetfield) ? ary.row[i].targetfield : ary.row[i].targetid; });
				$A(div.getElementsByTagName("select")).each(function(a) { if (a.name.match(/^linkvalue3/)) $(a).show(); });
				$A(div.getElementsByTagName("input")).each(function(a) { if (a.name.match(/^linkvalue4/)) a.value = ary.row[i].param; });
				$A(div.getElementsByTagName("input")).each(function(a) { if (a.name.match(/^linkvalue4/)) $(a).show(); });
				break;
		}
	}
}

function subscriber_action_form_campaignload() {
	var id = 0;

	// decide if we are in a campaign process or not...
	if ( $("form_type_hidden") && $("form_type_hidden").value != \'\' ) {
		switch ($("form_type_hidden").value) {
			case \'read\':
				id = campaign_actionid_readopen;
				$("span_listselect").style.display     = "none";
				$("span_campaignselect").style.display = "none";
				$("span_linkselect").style.display     = "none";
				$("span_listlabel").style.display     = "none";
				$("span_campaignlabel").style.display = "none";
				$("span_linklabel").style.display     = "none";
				$("span_sociallabel").hide();
				$("span_socialselect").hide();
				$("div_dropdowns").style.display      = "none";
				break;
			case \'link\':
				id = $("linkaction" + subscriber_action_form_linkidx).value;
				$("span_listselect").style.display     = "none";
				$("span_campaignselect").style.display = "none";
				$("span_linkselect").style.display     = "none";
				$("span_listlabel").style.display     = "none";
				$("span_campaignlabel").style.display = "none";
				$("span_linklabel").style.display     = "none";
				$("span_sociallabel").hide();
				$("span_socialselect").hide();
				$("div_dropdowns").style.display      = "none";
				break;
			case \'social\':
				break;
			default:
				break;
		}
	}

	adesk_dom_toggle_display("link_actions", "block");
	subscriber_action_form_load(id);
}

function subscriber_action_form_load(id) {
	subscriber_action_form_defaults();
	subscriber_action_form_id = id;

	if (id > 0) {
		if (adesk_js_admin.pg_subscriber_add != 1 || adesk_js_admin.pg_subscriber_delete != 1) {
			adesk_ui_anchor_set(subscriber_action_list_anchor());
			alert(subscriber_action_form_str_cant_update);
			return;
		}

		adesk_ui_api_call(jsLoading);
		$("form_submit").className = "adesk_button_update";
		$("form_submit").value = jsUpdate;
		adesk_ajax_call_cb("awebdeskapi.php", "subscriber_action.subscriber_action_select_row", subscriber_action_form_load_cb, id);
	} else {
		if (adesk_js_admin.pg_subscriber_add != 1 || adesk_js_admin.pg_subscriber_delete != 1) {
			adesk_ui_anchor_set(subscriber_action_list_anchor());
			alert(subscriber_action_form_str_cant_insert);
			return;
		}

		if ( $("form_submit") ) {
			$("form_submit").className = "adesk_button_add";
			$("form_submit").value = jsAdd;
		}
		$("form").className = "adesk_block";
	}
}

function subscriber_action_form_load_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();
	if ( !ary.id ) {
		adesk_error_show(subscriber_action_form_str_cant_find);
		adesk_ui_anchor_set(subscriber_action_list_anchor());
		return;
	}

	subscriber_action_form_id = ary.id;

	$("form_id").value   = ary.id;
	if ($("form_name"))
		$("form_name").value = ary.name;

	// We need to go through the list of inputs to see which are for the type.
	$A(document.getElementsByTagName("input")).each(function(rad) {
			if (rad.type == "radio" && rad.name == "type" && rad.value == ary.type)
				rad.checked = true;
		});

	subscriber_action_form_actionclick(ary.type);

	subscriber_action_form_loadcampaigns_cb(ary.campaigns[0]);
	subscriber_action_form_loadlinks_cb(ary.links[0]);
	subscriber_action_form_loadparts(ary.parts[0]);

	$("form_listid").value = ary.listid;
	$("form_campaignid").value = ary.campaignid;
	$("form_linkid").value = ary.linkid;
	$("form_social").value = ary.socmedia;

	$("form").className  = "adesk_block";
}

function subscriber_action_form_back() {
	if (subscriber_action_form_fromcampaign)
		adesk_dom_toggle_display(\'link_actions\', \'block\');
	else
		window.history.go(-1);
}

function subscriber_action_form_save(id) {
	var post = adesk_form_post($("form"));
	adesk_ui_api_call(jsSaving);

	if (post.name == "") {
		alert(subscriber_action_form_str_needname);
		return;
	}

	if (typeof post.linkaction == "undefined") {
		alert(subscriber_action_form_str_needact);
		return;
	}

	post.id = id;

	if (id > 0)
		adesk_ajax_post_cb("awebdeskapi.php", "subscriber_action.subscriber_action_update_post", subscriber_action_form_save_cb, post);
	else
		adesk_ajax_post_cb("awebdeskapi.php", "subscriber_action.subscriber_action_insert_post", subscriber_action_form_save_cb, post);

	if (subscriber_action_form_fromcampaign)
		adesk_dom_toggle_display(\'link_actions\', \'block\');
}

function subscriber_action_form_save_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		if (!subscriber_action_form_fromcampaign)
			adesk_ui_anchor_set(subscriber_action_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}

	if ( $("form_type_hidden") && $("form_type_hidden").value != "" ) {
		if ($("form_type_hidden").value == "read" && typeof campaign_actionid_readopen != "undefined") {
			campaign_actionid_readopen = ary.id;
			$("messagelinkactionsopen").innerHTML = sprintf("%d %s", ary.actions, subscriber_action_form_str_actions);
		}
		else if (subscriber_action_form_linkidx != \'\') {
			$("linkaction" + subscriber_action_form_linkidx).value = ary.id;
			if (subscriber_action_form_linkidx > -1)
				$("messagelinkactions" + subscriber_action_form_linkidx).innerHTML = sprintf("%d %s", ary.actions, subscriber_action_form_str_actions);
		}
	}
}

function subscriber_action_form_actionclick(val) {
	$("span_campaignlabel").style.display  = "none";
	$("span_campaignselect").style.display = "none";
	$("span_linklabel").style.display      = "none";
	$("span_linkselect").style.display     = "none";
	$("span_sociallabel").hide();
	$("span_socialselect").hide();
	switch (val) {
		case "read":
		case "forward":
			$("span_campaignlabel").style.display  = "";
			$("span_campaignselect").style.display = "";
			break;
		case "social":
			$("span_campaignlabel").style.display  = "";
			$("span_campaignselect").style.display = "";
			$("span_sociallabel").show();
			$("span_socialselect").show();
			break;
		case "link":
			$("span_campaignlabel").style.display  = "";
			$("span_campaignselect").style.display = "";
			$("span_linklabel").style.display      = "";
			$("span_linkselect").style.display     = "";
			break;
		case "subscribe":
			break;
		case "unsubscribe":
			break;
		default:
			break;
	}
}

'; ?>
