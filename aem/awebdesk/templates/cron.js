{include file="cron.list.js"}
{include file="cron.form.js"}
{include file="cron.delete.js"}
{include file="cron.search.js"}
{include file="cron.log.js"}

{literal}
function cron_process(loc, hist) {
	if ( loc == '' ) {
		loc = 'list-' + cron_list_sort + '-' + cron_list_offset + '-' + cron_list_filter;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden";
	$("form").className = "adesk_hidden";
	var func = null;
	try {
		var func = eval("cron_process_" + args[0]);

	} catch (e) {
		if (typeof cron_process_list == "function")
			cron_process_list(args);
	}
	if (typeof func == "function")
		func(args);
}

function cron_process_list(args) {
	if (args.length < 2)
		args = ["list", cron_list_sort, cron_list_offset, cron_list_filter];

	cron_list_sort = args[1];
	cron_list_offset = args[2];
	cron_list_filter = args[3];

	cron_list_discern_sortclass();

	paginators[1].paginate(cron_list_offset);
}

function cron_process_form(args) {
	if (args.length < 2)
		args = ["form", "0"];

	var id = parseInt(args[1], 10);

	cron_form_load(id);
}

function cron_process_delete(args) {
	if (args.length < 2) {
		cron_process_list(["list", "0"]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	cron_delete_check(id);
}

function cron_process_delete_multi(args) {
	$("list").className = "adesk_block";
	cron_delete_check_multi();
}

function cron_process_search(args) {
	$("list").className = "adesk_block";
	cron_search_check();
}

function cron_process_log(args) {
	if (args.length < 2) {
		cron_process_list(["list", cron_list_sort, cron_list_offset, cron_list_filter]);
		return;
	}

	var id = parseInt(args[1], 10);

	cron_log(id);
}
{/literal}
