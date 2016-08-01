{jsvar name=sendid var=$sid}

{include file="recipient.list.js"}
{include file="recipient.search.js"}

{literal}
function recipient_process(loc, hist) {
	if ( loc == '' ) {
		loc = 'list-' + recipient_list_sort + '-' + recipient_list_offset + '-' + recipient_list_filter;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden";
	var func = null;
	try {
		var func = eval("recipient_process_" + args[0]);

	} catch (e) {
		if (typeof recipient_process_list == "function")
			recipient_process_list(args);
	}
	if (typeof func == "function")
		func(args);
}

function recipient_process_list(args) {
	if (args.length < 2)
		args = ["list", recipient_list_sort, recipient_list_offset, recipient_list_filter];

	recipient_list_sort = args[1];
	recipient_list_offset = args[2];
	recipient_list_filter = args[3];

	recipient_list_discern_sortclass();

	paginators[1].paginate(recipient_list_offset);
}

function recipient_process_search(args) {
	$("list").className = "adesk_block";
	recipient_search_check();
}
{/literal}
