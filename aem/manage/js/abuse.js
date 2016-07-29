{include file="abuse.list.js"}

{literal}
function abuse_process(loc, hist) {
	if ( loc == '' ) {
		loc = 'list-' + abuse_list_sort + '-' + abuse_list_offset;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden";

	var func = null;
	try {
		var func = eval("abuse_process_" + args[0]);

	} catch (e) {
		if (typeof abuse_process_list == "function")
			abuse_process_list(args);
	}
	if (typeof func == "function")
		func(args);
}

function abuse_process_list(args) {
	if (args.length < 2)
		args = ["list", abuse_list_sort, abuse_list_offset];

	abuse_list_sort = args[1];
	abuse_list_offset = args[2];

	abuse_list_discern_sortclass();

	paginators[1].paginate(abuse_list_offset);
}

{/literal}
