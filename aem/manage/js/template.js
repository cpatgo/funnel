{include file="template.list.js"}
{include file="template.form.js"}
{include file="template.import.js"}
{include file="template.delete.js"}
{include file="template.search.js"}

var template_listfilter = {jsvar var=$listfilter};

{literal}
function template_process(loc, hist) {
	if ( loc == '' ) {
		loc = 'list-' + template_list_sort + '-' + template_list_offset + '-' + template_list_filter;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden";
	$("form").className = "adesk_hidden";
	$("template_list_count").className = "adesk_hidden";
	$("import").className = "adesk_hidden";
	var func = null;
	try {
		var func = eval("template_process_" + args[0]);

	} catch (e) {
		if (typeof template_process_list == "function")
			template_process_list(args);
	}
	if (typeof func == "function")
		func(args);
}

function template_process_list(args) {
	if (args.length < 2)
		args = ["list", template_list_sort, template_list_offset, template_list_filter];

	template_list_sort = args[1];
	template_list_offset = args[2];
	template_list_filter = args[3];

	if ( template_listfilter > 0 ) $('JSListManager').value = template_listfilter;

	template_list_discern_sortclass();

	paginators[1].paginate(template_list_offset);
}

function template_process_form(args) {
	if (args.length < 2)
		args = ["form", "0"];

	var id = parseInt(args[1], 10);

	template_form_load(id);
}

function template_process_import(args) {
	if (args.length < 1)
		args = ["import"];

	template_import_load();
}

function template_process_delete(args) {
	if (args.length < 2) {
		template_process_list(["list", "0"]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	template_delete_check(id);
}

function template_process_delete_multi(args) {
	$("list").className = "adesk_block";
	template_delete_check_multi();
}

function template_process_search(args) {
	$("list").className = "adesk_block";
	template_search_check();
}
{/literal}
