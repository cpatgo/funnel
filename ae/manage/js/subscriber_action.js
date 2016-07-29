{include file="subscriber_action.list.js"}
{include file="subscriber_action.form.js"}
{include file="subscriber_action.delete.js"}
{include file="subscriber_action.search.js"}

var subscriber_action_listfilter = {jsvar var=$listfilter};

{literal}
function subscriber_action_process(loc, hist) {
	if ( loc == '' ) {
		loc = 'list-' + subscriber_action_list_sort + '-' + subscriber_action_list_offset + '-' + subscriber_action_list_filter;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden";
	$("form").className = "adesk_hidden";
	$("subscriber_action_list_count").className = "adesk_hidden";
	var func = null;
	try {
		func = eval("subscriber_action_process_" + args[0]);
	} catch (e) {
		if (typeof subscriber_action_process_list == "function")
			subscriber_action_process_list(args);
	}
	if (typeof func == "function")
		func(args);
}

function subscriber_action_process_list(args) {
	if (args.length < 2)
		args = ["list", subscriber_action_list_sort, subscriber_action_list_offset, subscriber_action_list_filter];

	subscriber_action_list_sort = args[1];
	subscriber_action_list_offset = args[2];
	subscriber_action_list_filter = args[3];

	if ( subscriber_action_listfilter > 0 ) $('JSListManager').value = subscriber_action_listfilter;

	subscriber_action_list_discern_sortclass();

	paginators[1].paginate(subscriber_action_list_offset);
}

function subscriber_action_process_form(args) {
	if (args.length < 2)
		args = ["form", "0"];

	var id = parseInt(args[1], 10);

	subscriber_action_form_load(id);
}

function subscriber_action_process_delete(args) {
	if (args.length < 2) {
		subscriber_action_process_list(["list", "0"]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	subscriber_action_delete_check(id);
}

function subscriber_action_process_delete_multi(args) {
	$("list").className = "adesk_block";
	subscriber_action_delete_check_multi();
}

function subscriber_action_process_search(args) {
	$("list").className = "adesk_block";
	subscriber_action_search_check();
}


function subscriber_action_string(ary, longString) {
	if ( longString ) {
		var sa = ( ary.source_action == 'sub' ? strSubscriberRuleSourceSub : strSubscriberRuleSourceUnsub );
		var ta = ( ary.target_action == 'sub' ? strSubscriberRuleTargetSub : strSubscriberRuleTargetUnsub );
		return sprintf(strSubscriberRuleLong, ary.target_name, ary.target_action, ary.source_name, ary.source_action);
	} else {
		var sa = ( ary.source_action == 'sub' ? strSubscriberRuleSub : strSubscriberRuleUnsub );
		var ta = ( ary.target_action == 'sub' ? strSubscriberRuleSub : strSubscriberRuleUnsub );
		return ary.source_name + ": '" + sa + "' » " + ary.target_name + " '" + ta + "'";
	}
}


{/literal}
