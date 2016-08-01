{include file="service.list.js"}
{include file="service.form.js"}

{jsvar var=$__ishosted name=service_ishosted}

var service_edit_no = '{"You do not have permission to edit this External Service."|alang|js}';
var service_delete_no = '{"You do not have permission to delete External Services."|alang|js}';

{literal}
function service_process(loc, hist) {
	if ( loc == '' ) {
		loc = 'list-' + service_list_sort + '-' + service_list_offset + '-' + service_list_filter;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden";
	$("form").className = "adesk_hidden";
	var func = null;
	try {
		var func = eval("service_process_" + args[0]);

	} catch (e) {
		if (typeof service_process_list == "function")
			service_process_list(args);
	}
	if (typeof func == "function")
		func(args);
}

function service_process_list(args) {
	if (args.length < 2)
		args = ["list", service_list_sort, service_list_offset, service_list_filter];

	service_list_sort = args[1];
	service_list_offset = args[2];
	service_list_filter = args[3];

	service_list_discern_sortclass();

	paginators[1].paginate(service_list_offset);
}

function service_process_form(args) {
	if (args.length < 2)
		args = ["form", "0"];

	var id = parseInt(args[1], 10);

	service_form_load(id);
}

function service_process_delete(args) {
	alert(service_delete_no);
	service_process_list(["list", "0", "0", "0"]);
	return;

	if (args.length < 2) {
		service_process_list(["list", "0"]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	service_delete_check(id);
}

function service_process_delete_multi(args) {
	$("list").className = "adesk_block";
	service_delete_check_multi();
}

function service_process_search(args) {
	$("list").className = "adesk_block";
	service_search_check();
}
{/literal}
