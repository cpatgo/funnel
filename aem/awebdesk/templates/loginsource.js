{include file="loginsource.list.js"}
{include file="loginsource.form.js"}

{literal}
function loginsource_process(loc, hist) {
	if ( loc == '' ) {
		loc = 'list-' + loginsource_list_sort + '-' + loginsource_list_offset + '-' + loginsource_list_filter;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden";
	$("form").className = "adesk_hidden";
	var func = null;
	try {
		var func = eval("loginsource_process_" + args[0]);

	} catch (e) {
		if (typeof loginsource_process_list == "function")
			loginsource_process_list(args);
	}
	if (typeof func == "function")
		func(args);
}

function loginsource_process_list(args) {
	if (args.length < 2)
		args = ["list", loginsource_list_sort, loginsource_list_offset, loginsource_list_filter];

	loginsource_list_sort = args[1];
	loginsource_list_offset = args[2];
	loginsource_list_filter = args[3];

	loginsource_list_discern_sortclass();

	paginators[1].paginate(loginsource_list_offset);
}

function loginsource_process_form(args) {
	if (args.length < 2)
		args = ["form", "0"];

	var id = parseInt(args[1], 10);

	loginsource_form_load(id);
}

function loginsource_process_delete(args) {
	if (args.length < 2) {
		loginsource_process_list(["list", "0"]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	loginsource_delete_check(id);
}

function loginsource_process_delete_multi(args) {
	$("list").className = "adesk_block";
	loginsource_delete_check_multi();
}

function loginsource_process_search(args) {
	$("list").className = "adesk_block";
	loginsource_search_check();
}
{/literal}
