{include file="exclusion.list.js"}
{include file="exclusion.form.js"}
{include file="exclusion.delete.js"}
{include file="exclusion.search.js"}

var exclusion_listfilter = {jsvar var=$listfilter};

{literal}
function exclusion_process(loc, hist) {
	if ( loc == '' ) {
		loc = 'list-' + exclusion_list_sort + '-' + exclusion_list_offset + '-' + exclusion_list_filter;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden";
	$("form").className = "adesk_hidden";
	$("exclusion_list_count").className = "adesk_hidden";
	var func = null;
	try {
		var func = eval("exclusion_process_" + args[0]);

	} catch (e) {
		if (typeof exclusion_process_list == "function")
			exclusion_process_list(args);
	}
	if (typeof func == "function")
		func(args);
}

function exclusion_process_list(args) {
	if (args.length < 2)
		args = ["list", exclusion_list_sort, exclusion_list_offset, exclusion_list_filter];

	exclusion_list_sort = args[1];
	exclusion_list_offset = args[2];
	exclusion_list_filter = args[3];

	if ( exclusion_listfilter > 0 ) $('JSListManager').value = exclusion_listfilter;

	exclusion_list_discern_sortclass();

	paginators[1].paginate(exclusion_list_offset);
}

function exclusion_process_form(args) {
	if (args.length < 2)
		args = ["form", "0"];

	var id = parseInt(args[1], 10);

	exclusion_form_load(id);
}

function exclusion_process_delete(args) {
	if (args.length < 2) {
		exclusion_process_list(["list", "0"]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	exclusion_delete_check(id);
}

function exclusion_process_delete_multi(args) {
	$("list").className = "adesk_block";
	exclusion_delete_check_multi();
}

function exclusion_process_search(args) {
	$("list").className = "adesk_block";
	exclusion_search_check();
}
{/literal}
