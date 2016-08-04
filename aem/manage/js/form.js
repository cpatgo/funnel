{include file="form.list.js"}
{include file="form.form.js"}
{include file="form.view.js"}
{include file="form.delete.js"}
{include file="form.search.js"}
{include file="clipboard.min.js"}

var form_listfilter = {jsvar var=$listfilter};

adesk_editor_init_normal();

{literal}
function form_process(loc, hist) {
	if ( loc == '' ) {
		loc = 'list-' + form_list_sort + '-' + form_list_offset + '-' + form_list_filter;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden";
	$("form").className = "adesk_hidden";
	$("form_list_count").className = "adesk_hidden";
	$("view").className = "adesk_hidden";
	var func = null;
	try {
		var func = eval("form_process_" + args[0]);

	} catch (e) {
		if (typeof form_process_list == "function")
			form_process_list(args);
	}
	if (typeof func == "function")
		func(args);
}

function form_process_list(args) {
	if (args.length < 2)
		args = ["list", form_list_sort, form_list_offset, form_list_filter];

	form_list_sort = args[1];
	form_list_offset = args[2];
	form_list_filter = args[3];

	if ( form_listfilter > 0 ) $('JSListManager').value = form_listfilter;

	form_list_discern_sortclass();

	// set default tab shown on Integration page
	var form_other_tab = (adesk_js_site.general_public) ? 'public' : 'api';
	form_list_other_cycle(form_other_tab);

	paginators[1].paginate(form_list_offset);
}

function form_process_form(args) {
	if (args.length < 2)
		args = ["form", "0"];

	var id = parseInt(args[1], 10);

	form_form_load(id);
}

function form_process_view(args) {
	if (args.length < 2)
		args = ["view", "0"];

	var id = parseInt(args[1], 10);

	form_view_load(id, 'html');
}

function form_process_delete(args) {
	if (args.length < 2) {
		form_process_list(["list", "0"]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	form_delete_check(id);
}

function form_process_delete_multi(args) {
	$("list").className = "adesk_block";
	form_delete_check_multi();
}

function form_process_search(args) {
	$("list").className = "adesk_block";
	form_search_check();
}
{/literal}
