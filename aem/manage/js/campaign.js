{include file="campaign.list.js"}
{include file="campaign.delete.js"}
{include file="campaign.search.js"}

var campaign_listfilter = {jsvar var=$listfilter};

{literal}
function campaign_process(loc, hist) {
	if ( loc == '' ) {
		loc = 'list-' + campaign_list_sort + '-' + campaign_list_offset + '-' + campaign_list_filter;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden";
	$("campaign_list_count").className = "adesk_hidden";
	var func = null;
	try {
		var func = eval("campaign_process_" + args[0]);

	} catch (e) {
		if (typeof campaign_process_list == "function")
			campaign_process_list(args);
	}
	if (typeof func == "function")
		func(args);
}

function campaign_process_list(args) {
	if (args.length < 2)
		args = ["list", campaign_list_sort, campaign_list_offset, campaign_list_filter];

	campaign_list_sort = args[1];
	campaign_list_offset = args[2];
	campaign_list_filter = args[3];

	if ( campaign_listfilter > 0 ) $('JSListManager').value = campaign_listfilter;

	campaign_list_discern_sortclass();

	paginators[1].paginate(campaign_list_offset);
}

function campaign_process_delete(args) {
	if (args.length < 2) {
		campaign_process_list(["list", "0"]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	campaign_delete_check(id);
}

function campaign_process_share(args) {
	if (args.length < 2) {
		campaign_process_list(["list", "0"]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	campaign_share_check(id);
}

function campaign_process_delete_multi(args) {
	$("list").className = "adesk_block";
	campaign_delete_check_multi();
}

function campaign_process_search(args) {
	$("list").className = "adesk_block";
	campaign_search_check();
}

{/literal}
