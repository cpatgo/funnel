{include file="bounce_management.list.js"}
{include file="bounce_management.form.js"}
{include file="bounce_management.delete.js"}
{include file="bounce_management.search.js"}
{include file="bounce_management.log.js"}

var bounce_management_listfilter = {jsvar var=$listfilter};

{literal}
function bounce_management_process(loc, hist) {
	if ( loc == '' ) {
		loc = 'list-' + bounce_management_list_sort + '-' + bounce_management_list_offset + '-' + bounce_management_list_filter;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden";
	$("form").className = "adesk_hidden";
	var func = null;
	try {
		var func = eval("bounce_management_process_" + args[0]);

	} catch (e) {
		if (typeof bounce_management_process_list == "function")
			bounce_management_process_list(args);
	}
	if (typeof func == "function")
		func(args);
}

function bounce_management_process_list(args) {
	if (args.length < 2)
		args = ["list", bounce_management_list_sort, bounce_management_list_offset, bounce_management_list_filter];

	bounce_management_list_sort = args[1];
	bounce_management_list_offset = args[2];
	bounce_management_list_filter = args[3];

	if ( bounce_management_listfilter > 0 ) $('JSListManager').value = bounce_management_listfilter;

	bounce_management_list_discern_sortclass();

	paginators[1].paginate(bounce_management_list_offset);
}

function bounce_management_process_form(args) {
	if (args.length < 2)
		args = ["form", "0"];

	var id = parseInt(args[1], 10);

	bounce_management_form_load(id);
}

function bounce_management_process_delete(args) {
	if (args.length < 2) {
		bounce_management_process_list(["list", "0"]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	bounce_management_delete_check(id);
}

function bounce_management_process_delete_multi(args) {
	$("list").className = "adesk_block";
	bounce_management_delete_check_multi();
}

function bounce_management_process_search(args) {
	$("list").className = "adesk_block";
	bounce_management_search_check();
}

function bounce_management_process_log(args) {
	if (args.length < 2) {
		cron_process_list(["list", bounce_management_list_sort, bounce_management_list_offset, bounce_management_list_filter]);
		return;
	}

	var id = parseInt(args[1], 10);

	bounce_management_log(id);
}
{/literal}
