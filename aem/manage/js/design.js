{include file="design.list.js"}
{include file="design.form.js"}
{include file="design.search.js"}

{literal}
function design_process(loc, hist) {
	if ( loc == '' ) {
		loc = 'list-' + design_list_sort + '-' + design_list_offset + '-' + design_list_filter;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden";
	$("form").className = "adesk_hidden";
	var func = null;
	try {
		var func = eval("design_process_" + args[0]);

	} catch (e) {
		if (typeof design_process_list == "function")
			design_process_list(args);
	}
	if (typeof func == "function")
		func(args);
}

function design_process_list(args) {
	if (args.length < 2)
		args = ["list", design_list_sort, design_list_offset, design_list_filter];

	design_list_sort = args[1];
	design_list_offset = args[2];
	design_list_filter = args[3];

	design_list_discern_sortclass();

	paginators[1].paginate(design_list_offset);
}

function design_process_form(args) {
	if (args.length < 2)
		args = ["form", "0"];

	var id = parseInt(args[1], 10);

	design_form_load(id);
}

function design_process_search(args) {
	$("list").className = "adesk_block";
	design_search_check();
}
{/literal}
