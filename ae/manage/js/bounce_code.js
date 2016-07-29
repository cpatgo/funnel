{include file="bounce_code.list.js"}
{include file="bounce_code.form.js"}
{include file="bounce_code.delete.js"}
{include file="bounce_code.search.js"}

{literal}
function bounce_code_process(loc, hist) {
	if ( loc == '' ) {
		loc = 'list-' + bounce_code_list_sort + '-' + bounce_code_list_offset + '-' + bounce_code_list_filter;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden";
	$("form").className = "adesk_hidden";
	var func = null;
	try {
		var func = eval("bounce_code_process_" + args[0]);

	} catch (e) {
		if (typeof bounce_code_process_list == "function")
			bounce_code_process_list(args);
	}
	if (typeof func == "function")
		func(args);
}

function bounce_code_process_list(args) {
	if (args.length < 2)
		args = ["list", bounce_code_list_sort, bounce_code_list_offset, bounce_code_list_filter];

	bounce_code_list_sort = args[1];
	bounce_code_list_offset = args[2];
	bounce_code_list_filter = args[3];

	bounce_code_list_discern_sortclass();

	paginators[1].paginate(bounce_code_list_offset);
}

function bounce_code_process_form(args) {
	if (args.length < 2)
		args = ["form", "0"];

	var id = parseInt(args[1], 10);

	bounce_code_form_load(id);
}

function bounce_code_process_delete(args) {
	if (args.length < 2) {
		bounce_code_process_list(["list", "0"]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	bounce_code_delete_check(id);
}

function bounce_code_process_delete_multi(args) {
	$("list").className = "adesk_block";
	bounce_code_delete_check_multi();
}

function bounce_code_process_search(args) {
	$("list").className = "adesk_block";
	bounce_code_search_check();
}
{/literal}
