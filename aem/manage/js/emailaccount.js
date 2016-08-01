{include file="emailaccount.list.js"}
{include file="emailaccount.form.js"}
{include file="emailaccount.delete.js"}
{include file="emailaccount.search.js"}
{include file="emailaccount.log.js"}

var emailaccount_listfilter = {jsvar var=$listfilter};

{literal}
function emailaccount_process(loc, hist) {
	if ( loc == '' ) {
		loc = 'list-' + emailaccount_list_sort + '-' + emailaccount_list_offset + '-' + emailaccount_list_filter;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden";
	$("form").className = "adesk_hidden";
	var func = null;
	try {
		func = eval("emailaccount_process_" + args[0]);
	} catch (e) {
		if (typeof emailaccount_process_list == "function")
			emailaccount_process_list(args);
	}
	if (typeof func == "function")
		func(args);
}

function emailaccount_process_list(args) {
	if (args.length < 2)
		args = ["list", emailaccount_list_sort, emailaccount_list_offset, emailaccount_list_filter];

	emailaccount_list_sort = args[1];
	emailaccount_list_offset = args[2];
	emailaccount_list_filter = args[3];

	if ( emailaccount_listfilter > 0 ) $('JSListManager').value = emailaccount_listfilter;

	emailaccount_list_discern_sortclass();

	paginators[1].paginate(emailaccount_list_offset);
}

function emailaccount_process_form(args) {
	if (args.length < 2)
		args = ["form", "0"];

	var id = parseInt(args[1], 10);

	emailaccount_form_load(id);
}

function emailaccount_process_delete(args) {
	if (args.length < 2) {
		emailaccount_process_list(["list", "0"]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	emailaccount_delete_check(id);
}

function emailaccount_process_delete_multi(args) {
	$("list").className = "adesk_block";
	emailaccount_delete_check_multi();
}

function emailaccount_process_search(args) {
	$("list").className = "adesk_block";
	emailaccount_search_check();
}

function emailaccount_process_log(args) {
	if (args.length < 2) {
		cron_process_list(["list", emailaccount_list_sort, emailaccount_list_offset, emailaccount_list_filter]);
		return;
	}

	var id = parseInt(args[1], 10);

	emailaccount_log(id);
}
{/literal}
