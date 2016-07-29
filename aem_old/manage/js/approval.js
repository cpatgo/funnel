{include file="approval.list.js"}
{* include file="approval.view.js" *}
{* include file="approval.delete.js" *}
{* include file="approval.search.js" *}

{literal}
function approval_process(loc, hist) {
	if ( loc == '' ) {
		loc = 'list-' + approval_list_sort + '-' + approval_list_offset;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden";
	//$("form").className = "adesk_hidden";
	var func = null;
	try {
		var func = eval("approval_process_" + args[0]);

	} catch (e) {
		if (typeof approval_process_list == "function")
			approval_process_list(args);
	}
	if (typeof func == "function")
		func(args);
}

function approval_process_list(args) {
	if (args.length < 2)
		args = ["list", approval_list_sort, approval_list_offset];

	approval_list_sort = args[1];
	approval_list_offset = args[2];

	approval_list_discern_sortclass();

	paginators[1].paginate(approval_list_offset);
}

{/literal}
