{include file="filter.list.js"}
{include file="filter.form.js"}
{include file="filter.delete.js"}
{include file="filter.search.js"}

var filter_listfilter = {jsvar var=$listfilter};

{literal}
function filter_process(loc, hist) {
	if ( loc == '' ) {
		loc = 'list-' + filter_list_sort + '-' + filter_list_offset + '-' + filter_list_filter;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden_ie";
	$("form").className = "adesk_hidden_ie";
	$("filter_list_count").className = "adesk_hidden_ie";
	var func = null;
	try {
		var func = eval("filter_process_" + args[0]);

	} catch (e) {
		if (typeof filter_process_list == "function")
			filter_process_list(args);
	}
	if (typeof func == "function")
		func(args);
}

function filter_process_list(args) {
	if (args.length < 2)
		args = ["list", filter_list_sort, filter_list_offset, filter_list_filter];

	filter_list_sort = args[1];
	filter_list_offset = args[2];
	filter_list_filter = args[3];

	if ( filter_listfilter > 0 ) $('JSListManager').value = filter_listfilter;

	filter_list_discern_sortclass();

	paginators[1].paginate(filter_list_offset);
}

function filter_process_form(args) {
	if (args.length < 2)
		args = ["form", "0"];

	var id = parseInt(args[1], 10);

	filter_form_load(id);
}

function filter_process_delete(args) {
	if (args.length < 2) {
		filter_process_list(["list", "0"]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	filter_delete_check(id);
}

function filter_process_delete_multi(args) {
	$("list").className = "adesk_block";
	filter_delete_check_multi();
}

function filter_process_search(args) {
	$("list").className = "adesk_block";
	filter_search_check();
}
{/literal}
