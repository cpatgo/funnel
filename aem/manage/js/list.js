var canAddList = {jsvar var=$canAddList};
var list_str_list_limit = '{"You have exceeded the number of allowed Lists"|alang|js}';

{include file="list.list.js"}
{include file="list.form.js"}
{include file="list.delete.js"}
{include file="list.copy.js"}
{include file="list.search.js"}


{literal}


// load HTML editor
tinyMCE.init({
	mode : "none",
	theme : "advanced",
	tab_focus : ":prev,:next"
	//onchange_callback : "editorContentChanged"
});


function list_process(loc, hist) {
	if ( loc == '') {
		loc = 'list-' + list_list_sort + '-' + list_list_offset + '-' + list_list_filter;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden";
	$("form").className = "adesk_hidden";
	$("list_list_count").className = "adesk_hidden";
	var func = null;
	try {
		func = eval("list_process_" + args[0]);
	} catch (e) {
		if (typeof list_process_list == "function")
			list_process_list(args);
	}
	if (typeof func == "function")
		func(args);
}

function list_process_list(args) {
	if (args.length < 4)
		args = ["list", list_list_sort, list_list_offset, list_list_filter];

	list_list_sort = args[1];
	list_list_offset = args[2];
	list_list_filter = args[3];

	list_list_discern_sortclass();
	requestedtab = 'general';

	paginators[1].paginate(list_list_offset);
}

function list_process_form(args) {
	if (args.length < 2)
		args = ["form", "0"];

	var id = parseInt(args[1], 10);

	list_form_load(id);
}

function list_process_delete(args) {
	if (args.length < 2) {
		list_process_list(["list", list_list_sort, list_list_offset, list_list_filter]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	list_delete_check(id);
}

function list_process_copy(args) {
	if (args.length < 2) {
		list_process_list(["list", list_list_sort, list_list_offset, list_list_filter]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	list_copy_check(id);
}

function list_process_delete_multi(args) {
	$("list").className = "adesk_block";
	list_delete_check_multi();
}

function list_process_search(args) {
	$("list").className = "adesk_block";
	list_search_check();
}
{/literal}
