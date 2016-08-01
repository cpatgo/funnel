{include file="header.list.js"}
{include file="header.form.js"}
{include file="header.delete.js"}
{include file="header.search.js"}

var header_listfilter = {jsvar var=$listfilter};

{literal}
function header_process(loc, hist) {
	if ( loc == '' ) {
		loc = 'list-' + header_list_sort + '-' + header_list_offset + '-' + header_list_filter;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden";
	$("form").className = "adesk_hidden";
	var func = null;
	try {
		func = eval("header_process_" + args[0]);
	} catch (e) {
		if (typeof header_process_list == "function")
			header_process_list(args);
	}
	if (typeof func == "function")
		func(args);
}

function header_process_list(args) {
	if (args.length < 2)
		args = ["list", header_list_sort, header_list_offset, header_list_filter];

	header_list_sort = args[1];
	header_list_offset = args[2];
	header_list_filter = args[3];

	if ( header_listfilter > 0 ) $('JSListManager').value = header_listfilter;

	header_list_discern_sortclass();

	paginators[1].paginate(header_list_offset);
}

function header_process_form(args) {
	if (args.length < 2)
		args = ["form", "0"];

	var id = parseInt(args[1], 10);

	header_form_load(id);
}

function header_process_delete(args) {
	if (args.length < 2) {
		header_process_list(["list", "0"]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	header_delete_check(id);
}

function header_process_delete_multi(args) {
	$("list").className = "adesk_block";
	header_delete_check_multi();
}

function header_process_search(args) {
	$("list").className = "adesk_block";
	header_search_check();
}
{/literal}
