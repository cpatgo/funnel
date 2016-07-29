<?php /* Smarty version 2.6.12, created on 2016-07-08 14:20:31
         compiled from subscriber_view.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'jsvar', 'subscriber_view.js', 4, false),array('modifier', 'alang', 'subscriber_view.js', 14, false),array('modifier', 'js', 'subscriber_view.js', 14, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "subscriber_view.log.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php echo smarty_function_jsvar(array('name' => 'subscriber_view_id','var' => $this->_tpl_vars['subscriber']['id']), $this);?>

<?php echo smarty_function_jsvar(array('name' => 'subscriber_view_email','var' => $this->_tpl_vars['subscriber']['email']), $this);?>

<?php echo smarty_function_jsvar(array('name' => 'subscriber_view_hash','var' => $this->_tpl_vars['subscriber']['hash']), $this);?>


var subscriber_view_panel = "general";
var subscriber_view_list = "0";
var subscriber_view_sort = "03D";
var subscriber_view_offset = "0";
var subscriber_view_sort_discerned = false;

var subscriber_view_str_firstname = '<?php echo ((is_array($_tmp=((is_array($_tmp='First name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_view_str_lastname  = '<?php echo ((is_array($_tmp=((is_array($_tmp='Last name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_view_str_status = '<?php echo ((is_array($_tmp=((is_array($_tmp='Status')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_view_str_unsubscribed = '<?php echo ((is_array($_tmp=((is_array($_tmp='Unsubscribed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_view_str_unconfirmed = '<?php echo ((is_array($_tmp=((is_array($_tmp='Unconfirmed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_view_str_subscribed = '<?php echo ((is_array($_tmp=((is_array($_tmp='Subscribed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_view_str_bounced = '<?php echo ((is_array($_tmp=((is_array($_tmp='Bounced')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var subscriber_view_str_email_invalid = '<?php echo ((is_array($_tmp=((is_array($_tmp="Email address you have entered does not seem to be valid.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

<?php echo '

function subscriber_view_process(loc, hist) {
	if ( loc == \'\' ) loc = \'log-03D-0-0\';
	var args = loc.split("-");

	subscriber_view_process_log(args);
}

function subscriber_view_anchor(newAnchor) {
	return sprintf("%s-%s-%s-%s", newAnchor, subscriber_view_sort, subscriber_view_offset, subscriber_view_list);
}

function subscriber_view_tabelize(rows, offset) {
	if (rows.length < 1) {
		// We may have some trs left if we just deleted the last row.
		adesk_dom_remove_children($(subscriber_view_panel + "_table"));

		$(subscriber_view_panel + "_noresults").className = "adesk_block";
		$(subscriber_view_panel + "LoadingBar").className = "adesk_hidden";
		adesk_ui_api_callback();
		return;
	}
	$(subscriber_view_panel + "_noresults").className = "adesk_hidden";
	if ( subscriber_view_panel == \'mailing\' ) {
		var t = mailing_table;
	} else if ( subscriber_view_panel == \'responder\' ) {
		var t = responder_table;
	} else if ( subscriber_view_panel == \'log\' ) {
		var t = log_table;
	}
	adesk_paginator_tabelize(t, subscriber_view_panel + "_table", rows, offset);
	$(subscriber_view_panel + "LoadingBar").className = "adesk_hidden";
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function subscriber_view_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	subscriber_view_offset = parseInt(offset, 10);

	if ( $(subscriber_view_panel + "ListManager") ) {
		$(subscriber_view_panel + "ListManager").value = subscriber_view_list;
	}

	adesk_ui_anchor_set(subscriber_view_anchor(subscriber_view_panel));
	$(subscriber_view_panel + "LoadingBar").className = "adesk_block";
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, subscriber_view_panel, subscriber_view_id, subscriber_view_sort, subscriber_view_offset, this.limit, $("logListManager").value);
}

function subscriber_view_limitize(limit) {
	// save new admin limit locally
	adesk_js_admin.messages_per_page = limit;
	// save new admin limit remotelly
	adesk_ajax_call_cb(\'awebdeskapi.php\', \'user.user_update_value\', null, \'messages_per_page\', limit);
	// set new limit
	this.limit = limit;
	// fetch new list
	this.paginate(this.offset);
}


function subscriber_view_list_chsort(newSortId) {
	var oldSortId = ( subscriber_view_sort.match(/D$/) ? subscriber_view_sort.substr(0, 2) : subscriber_view_sort );
	var oldSortObj = $(subscriber_view_panel + \'_sorter\' + oldSortId);
	var sortObj = $(subscriber_view_panel + \'_sorter\' + newSortId);
	// if sort column didn\'t change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( subscriber_view_sort.match(/D$/) ) {
			// was DESC
			newSortId = subscriber_view_sort.substr(0, 2);
			sortObj.className = \'adesk_sort_asc\';
		} else {
			// was ASC
			newSortId = subscriber_view_sort + \'D\';
			sortObj.className = \'adesk_sort_desc\';
		}
	} else {
		// remove old subscriber_view_sort
		if ( oldSortObj ) oldSortObj.className = \'adesk_sort_other\';
		// set sort field
		sortObj.className = \'adesk_sort_asc\';
	}
	subscriber_view_sort = newSortId;
	adesk_ui_api_call(jsSorting);
	adesk_ui_anchor_set(subscriber_view_anchor(subscriber_view_panel));
	return false;
}

function subscriber_view_discern_sortclass() {
	if (subscriber_view_sort_discerned)
		return;

	var elems = $(subscriber_view_panel + "_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf(subscriber_view_panel + "_sorter%s", subscriber_view_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (subscriber_view_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	subscriber_view_sort_discerned = true;
}


function subscriber_view_filter(value) {
	subscriber_view_list = value;
	adesk_ui_api_call(jsFiltering);
	adesk_ui_anchor_set(subscriber_view_anchor(subscriber_view_panel));
}

function subscriber_view_history_back() {
	var b = browser_ident();

	if (b == "Explorer 7" || b == "Explorer 8" || b.substr(0,6) == "Safari") {
		window.history.go(-2);
	}
	else {
		window.history.go(-1);
	}
}

function subscriber_view_lists(listid) {
	if (listid > 0)
		adesk_ajax_call_cb("awebdeskapi.php", "subscriber.subscriber_view_lists", adesk_ajax_cb(subscriber_view_lists_cb), subscriber_view_id, listid);
	else
		adesk_ajax_call_cb("awebdeskapi.php", "subscriber.subscriber_view_lists", adesk_ajax_cb(subscriber_view_lists_cb), subscriber_view_id, $("listid").value);
}

function subscriber_view_lists_cb(ary) {
	adesk_dom_remove_children($("listdiv"));

	var opts = [];
	var c    = 0;

	for (var i = 0; i < ary.row.length; i++) {
		if (ary.listid == 0)
			ary.listid = ary.row[i].listid;
		if (ary.row[i].status == 2) {
			var val = Builder.node("option", { value: ary.row[i].listid }, [ ary.row[i].name + " (" + subscriber_view_str_unsubscribed + ")" ]);
			val.style.color = "#999";
			opts.push(val);
		} else if (ary.row[i].status == 3) {
			var val = Builder.node("option", { value: ary.row[i].listid }, [ ary.row[i].name + " (" + subscriber_view_str_bounced + ")" ]);
			val.style.color = \'#999\';
			opts.push(val);
		} else {
			opts.push(Builder.node("option", { value: ary.row[i].listid }, [ ary.row[i].name ]));
			c++;
		}
	}

	var sel = Builder.node("select", { id: "listid", onchange: "subscriber_view_load_fields(0)" }, opts);
	$("listdiv").appendChild(sel);
	$("listid").value = ary.listid;
	$("listcount").innerHTML = c;

	if (!ary.available)
		$("subscribelink").hide();
	else
		$("subscribelink").show();

	subscriber_view_load_fields(0);
}

function subscriber_view_unlists() {
	adesk_ajax_call_cb("awebdeskapi.php", "subscriber.subscriber_view_unlists", adesk_ajax_cb(subscriber_view_unlists_cb), subscriber_view_id);
}

function subscriber_view_unlists_cb(ary) {
	adesk_dom_remove_children($("listmodaldiv"));

	var opts = [];

	for (var i = 0; i < ary.row.length; i++) {
		opts.push(Builder.node("option", { value: ary.row[i].id }, [ ary.row[i].name ]));
	}

	var sel = Builder.node("select", { id: "newlistid" }, opts);
	$("listmodaldiv").appendChild(sel);
	$("listmodal").show();
}

function subscriber_view_subscribe() {
	adesk_ajax_call_cb("awebdeskapi.php", "subscriber.subscriber_view_subscribe", adesk_ajax_cb(subscriber_view_subscribe_cb), subscriber_view_id, $("newlistid").value, $("listid").value);
}

function subscriber_view_subscribe_cb(ary) {
	$("listmodal").hide();
	subscriber_view_lists($("newlistid").value);
}

function subscriber_view_unsubscribe() {
	adesk_ajax_call_cb("awebdeskapi.php", "subscriber.subscriber_view_unsubscribe", adesk_ajax_cb(subscriber_view_unsubscribe_cb), subscriber_view_id, $("listid").value);
}

function subscriber_view_unsubscribe_cb(ary) {
	if (ary.deleted == 1) {
		window.location = "desk.php?action=subscriber";
	} else {
		subscriber_view_lists(0);
	}
}

function subscriber_view_status(statusid) {
	switch (parseInt(statusid, 10)) {
		default:
		case 0:
			return subscriber_view_str_unconfirmed;
		case 1:
			return subscriber_view_str_subscribed;
		case 2:
			return Builder.node("span", { style: "color: red" }, subscriber_view_str_unsubscribed);
		case 3:
			return Builder.node("span", { style: "color: silver" }, subscriber_view_str_bounced);
	}
}

function subscriber_view_status_dropdown(statusid) {
	var opts = [
		Builder.node("option", { value: "0" }, subscriber_view_str_unconfirmed),
		Builder.node("option", { value: "1" }, subscriber_view_str_subscribed)
	];

	if (adesk_js_admin.pg_subscriber_delete){
		opts.push(Builder.node("option", { value: "2" }, subscriber_view_str_unsubscribed));
		opts.push(Builder.node("option", { value: "3" }, subscriber_view_str_bounced));
	}

	return Builder.node("select", { id: "details_status", name: "status" }, opts);
}

function subscriber_view_load_fields(editable) {
	adesk_ui_api_call(jsLoading);
	adesk_ajax_call_cb("awebdeskapi.php", "subscriber.subscriber_select_fields", adesk_ajax_cb(subscriber_view_load_fields_cb), subscriber_view_id, $("listid").value, editable);
}

function subscriber_view_load_fields_cb(ary) {
	adesk_ui_api_callback();
	var parentDiv = \'details_fields\';
	adesk_dom_remove_children($(parentDiv));

	$("details_listname").innerHTML = ary.listname;
	$("subscribedate").innerHTML = ary.sdate;
	$("subscriberip").innerHTML = ary.ip4 != \'0.0.0.0\' ? ary.ip4 : ary.ip;

	if (ary.status == 2) {
		$("unsubscribedate").innerHTML = ary.udate;
		$("unsubscribebox").show();
		$("details_fields_unsubscribelink").hide();
	} else {
		$("unsubscribebox").hide();
		$("details_fields_unsubscribelink").show();
	}

	var trs = [];

	if (ary.editable) {
		trs.push(Builder.node("tr", [
				Builder.node("td", { valign: "top" }, subscriber_view_str_status + ":"),
				Builder.node("td", { valign: "top", width: "10" }, " "),
				Builder.node("td", { valign: "top" }, subscriber_view_status_dropdown(ary.status)),
			]));
		trs.push(Builder.node("tr", [
				Builder.node("td", { valign: "top" }, subscriber_view_str_firstname + ":"),
				Builder.node("td", { valign: "top", width: "10" }, " "),
				Builder.node("td", { valign: "top" }, Builder.node("input", { type: "text", id: "details_first_name", onKeyUp: "if (window.event && window.event.keyCode) custom_field_text_onkeyup(window.event.keyCode)", value: ary.first_name }))
			]));
		trs.push(Builder.node("tr", [
				Builder.node("td", { valign: "top" }, subscriber_view_str_lastname + ":"),
				Builder.node("td", { valign: "top", width: "10" }, " "),
				Builder.node("td", { valign: "top" }, Builder.node("input", { type: "text", id: "details_last_name", onKeyUp: "if (window.event && window.event.keyCode) custom_field_text_onkeyup(window.event.keyCode)", value: ary.last_name }))
			]));
	} else {
		trs.push(Builder.node("tr", [
				Builder.node("td", { valign: "top" }, subscriber_view_str_status + ":"),
				Builder.node("td", { valign: "top", width: "10" }, " "),
				Builder.node("td", { valign: "top" }, subscriber_view_status(ary.status)),
			]));
		trs.push(Builder.node("tr", [
				Builder.node("td", { valign: "top" }, subscriber_view_str_firstname + ":"),
				Builder.node("td", { valign: "top", width: "10" }, " "),
				Builder.node("td", { valign: "top" }, ary.first_name)
			]));
		trs.push(Builder.node("tr", [
				Builder.node("td", { valign: "top" }, subscriber_view_str_lastname + ":"),
				Builder.node("td", { valign: "top", width: "10" }, " "),
				Builder.node("td", { valign: "top" }, ary.last_name)
			]));
	}

	if (typeof ary.row != "undefined") {
		for (var i = 0; i < ary.row.length; i++) {
			var rval = adesk_custom_fields_cons(ary.row[i], true);

			if (rval) {
				if (ary.editable) {
					trs.push(Builder.node("tr", [
						Builder.node("td", { valign: "top" }, adesk_custom_fields_title(ary.row[i], true) + ":"),
						Builder.node("td", { valign: "top", width: "10" }, " "),
						Builder.node("td", { valign: "top" }, [ adesk_custom_fields_bubble(rval, ary.row[i]) ]),
					]));
				} else {
					if (ary.row[i].type == 3) {
						if (ary.row[i].val == "checked")
							trs.push(Builder.node("tr", [
								Builder.node("td", { valign: "top" }, adesk_custom_fields_title(ary.row[i], true)),
								Builder.node("td", { valign: "top", width: "10" }, " "),
								Builder.node("td", { valign: "top" }, " ")
							]));
					} else {
						trs.push(Builder.node("tr", [
							Builder.node("td", { valign: "top" }, adesk_custom_fields_title(ary.row[i], true) + ":"),
							Builder.node("td", { valign: "top", width: "10" }, " "),
							Builder.node("td", { valign: "top" }, [ adesk_custom_fields_bubble(Builder._text(ary.row[i].val.toString().replace(/\\|\\|$/, \'\')), ary.row[i]) ])
						]));
					}
				}
			}
		}
	}

	$(parentDiv).appendChild(Builder.node("table", Builder.node("tbody", trs)));

	if ($("details_status"))
		$("details_status").value = ary.status;

	if (ary.editable) {
		$(parentDiv + "_updatebutton").show();
		$(parentDiv + "_editlink").hide();
	} else {
		$(parentDiv + "_updatebutton").hide();
		$(parentDiv + "_editlink").show();
	}
}

function subscriber_view_save_fields() {
	var post = adesk_form_post("details_fields");

	post.id         = subscriber_view_id;
	post.listid     = $("listid").value;
	post.first_name = $("details_first_name").value;
	post.last_name  = $("details_last_name").value;

	adesk_ui_api_call(jsSaving);
	adesk_ajax_post_cb("awebdeskapi.php", "subscriber.subscriber_update_fields", adesk_ajax_cb(subscriber_view_save_fields_cb), post);
}

function subscriber_view_save_fields_cb(ary) {
	adesk_ui_api_callback();
	subscriber_view_lists($("listid").value);
}

function subscriber_view_process_log(args) {
	subscriber_view_panel = args[0];
	subscriber_view_sort = args[1];
	subscriber_view_offset = parseInt(args[2], 10);
	//subscriber_view_list = $("listid").value;
	subscriber_view_log_load(subscriber_view_offset);
}

function custom_field_text_onkeyup(code) {
	if (code == 13)
		subscriber_view_save_fields();
}

function subscriber_email_update() {
	var id = subscriber_view_id;
	var email = $(\'subscriber_email_field\').value;

	if ( email == subscriber_view_email ) {
		$(\'subscriber_email_form\').hide();
		$(\'subscriber_email_label\').show();
		return;
	}

	if ( !adesk_str_email(email) ) {
		alert(subscriber_view_str_email_invalid);
		$(\'subscriber_email_field\').focus();
		return;
	}

	adesk_ui_api_call(jsSaving);
	adesk_ajax_call_cb("awebdeskapi.php", "subscriber.subscriber_update_email", adesk_ajax_cb(subscriber_email_update_cb), id, email);
}

function subscriber_email_update_cb(ary) {
	adesk_ui_api_callback();

	if ( ary.succeeded == 1 ) {
		adesk_result_show(ary.message);
		subscriber_view_email = $(\'subscriber_email_field\').value;
		$(\'subscriber_email_title\').innerHTML = subscriber_view_email;
		if ( $(\'emaillabel1\') ) $(\'emaillabel1\').innerHTML = subscriber_view_email;
		$(\'subscriber_email_form\').hide();
		$(\'subscriber_email_label\').show();
	} else {
		adesk_error_show(ary.message);
	}
}
'; ?>
