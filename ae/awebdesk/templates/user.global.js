{literal}
var globaltable = new ACTable();

// Options
globaltable.setcol(0, function(row) {
	var imp = " ";
	var del = " ";

	// Check permissions
	
	imp = Builder.node("a", { href: "javascript:void(0)", onclick: sprintf("user_global_import(%d)", row.id) }, [ jsUserImport ]); 
	del = Builder.node("a", { href: "javascript:void(0)", onclick: sprintf("user_global_delete(%d)", row.id) }, [ jsUserDelete ]); 

	var ary = [];

	if (adesk_js_admin.pg_user_add) {
		ary.push(imp);
		ary.push(" ");
	}

	if (adesk_js_admin.pg_user_delete && typeof row.productset == "undefined") {
		ary.push(del);
	}

	return Builder.node("span", ary);
});

// Username
globaltable.setcol(1, function(row) {
	return row.username;
});

// Name
globaltable.setcol(2, function(row) {
	return row.first_name + " " + row.last_name;
});

// Email
globaltable.setcol(3, function(row) {
	return row.email;
});

function user_global_tabelize(rows, offset) {
	adesk_paginator_tabelize(globaltable, "user_global_table", rows, offset);
}

function user_global_list(offset) {
	offsetID = offset;

	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	// fetch new list
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, offset/*filters*/);
}

function user_global_import(id) {
	if (id < 1)
		return;

	adesk_ui_api_call(jsLoading);
	adesk_ajax_call_cb("awebdeskapi.php", "user!adesk_user_global_import", user_global_import_cb, id);
}

function user_global_import_cb(xml, text) {
	var ary = adesk_dom_read_node(xml, null);
	adesk_ui_api_callback();

	if (ary.succeeded) {
		adesk_result_show(ary.message);
		adesk_dom_toggle_display("global", "block");
		paginators[1].paginate(0);
		paginators[2].paginate(0);
	} else {
		adesk_error_show(ary.message);
	}
}

function user_global_delete(id) {
	if (id < 1)
		return;

	if (!window.confirm(jsUserDeleteGlobal))
		return;

	// I know it's weird, but we can actually use the same callback as the import function.

	adesk_ui_api_call(jsLoading);
	adesk_ajax_call_cb("awebdeskapi.php", "user!adesk_user_global_delete", user_global_import_cb, id);
}

{/literal}
