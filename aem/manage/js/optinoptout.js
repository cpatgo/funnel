{include file="optinoptout.list.js"}
{include file="optinoptout.form.js"}
{include file="optinoptout.delete.js"}
{include file="optinoptout.search.js"}

//adesk_editor_init_word_object.plugins += ",ota_personalize,ota_conditional,ota_template";
//adesk_editor_init_word_object.theme_advanced_buttons1_add += ",ota_personalize,ota_conditional,ota_template";
//adesk_editor_init_word_object.mode = "none";
//adesk_editor_init_word_object.elements = "messageEditor";
adesk_editor_init_word_object.language = _twoletterlangid;
adesk_editor_init_word();
adesk_editor_init_word_object.plugins += ",fullpage";

var optinoptout_listfilter = {jsvar var=$listfilter};

{literal}
function optinoptout_process(loc, hist) {
	if ( loc == '' ) {
		loc = 'list-' + optinoptout_list_sort + '-' + optinoptout_list_offset + '-' + optinoptout_list_filter;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden";
	$("form").className = "adesk_hidden";
	$("optinoptout_list_count").className = "adesk_hidden";
	var func = null;
	try {
		var func = eval("optinoptout_process_" + args[0]);

	} catch (e) {
		if (typeof optinoptout_process_list == "function")
			optinoptout_process_list(args);
	}
	if (typeof func == "function")
		func(args);
}

function optinoptout_process_list(args) {
	if (args.length < 2)
		args = ["list", optinoptout_list_sort, optinoptout_list_offset, optinoptout_list_filter];

	optinoptout_list_sort = args[1];
	optinoptout_list_offset = args[2];
	optinoptout_list_filter = args[3];

	if ( optinoptout_listfilter > 0 ) $('JSListManager').value = optinoptout_listfilter;

	optinoptout_list_discern_sortclass();

	paginators[1].paginate(optinoptout_list_offset);
}

function optinoptout_process_form(args) {
	if (args.length < 2)
		args = ["form", "0"];

	var id = parseInt(args[1], 10);

	optinoptout_form_load(id);
}

function optinoptout_process_delete(args) {
	if (args.length < 2) {
		optinoptout_process_list(["list", "0"]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	optinoptout_delete_check(id);
}

function optinoptout_process_delete_multi(args) {
	$("list").className = "adesk_block";
	optinoptout_delete_check_multi();
}

function optinoptout_process_search(args) {
	$("list").className = "adesk_block";
	optinoptout_search_check();
}

function optinoptout_ie_fix() {
	// focus on first input field - IE 8 & 9 was having trouble allowing editing any form field
	if ( $("optnameField") ) $("optnameField").select();
}
{/literal}