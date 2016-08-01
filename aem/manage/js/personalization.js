{include file="personalization.list.js"}
{include file="personalization.form.js"}
{include file="personalization.delete.js"}
{include file="personalization.search.js"}

var personalization_listfilter = {jsvar var=$listfilter};

{literal}
function personalization_process(loc, hist) {
	if ( loc == '' ) {
		loc = 'list-' + personalization_list_sort + '-' + personalization_list_offset + '-' + personalization_list_filter;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden";
	$("form").className = "adesk_hidden";
	var func = null;
	try {
		var func = eval("personalization_process_" + args[0]);

	} catch (e) {
		if (typeof personalization_process_list == "function")
			personalization_process_list(args);
	}
	if (typeof func == "function")
		func(args);
}

function personalization_process_list(args) {
	if (args.length < 2)
		args = ["list", personalization_list_sort, personalization_list_offset, personalization_list_filter];

	personalization_list_sort = args[1];
	personalization_list_offset = args[2];
	personalization_list_filter = args[3];

	if ( personalization_listfilter > 0 ) $('JSListManager').value = personalization_listfilter;

	personalization_list_discern_sortclass();

	paginators[1].paginate(personalization_list_offset);
}

function personalization_process_form(args) {
	if (args.length < 2)
		args = ["form", "0"];

	var id = parseInt(args[1], 10);

	personalization_form_load(id);
}

function personalization_process_delete(args) {
	if (args.length < 2) {
		personalization_process_list(["list", "0"]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	personalization_delete_check(id);
}

function personalization_process_delete_multi(args) {
	$("list").className = "adesk_block";
	personalization_delete_check_multi();
}

function personalization_process_search(args) {
	$("list").className = "adesk_block";
	personalization_search_check();
}
{/literal}
