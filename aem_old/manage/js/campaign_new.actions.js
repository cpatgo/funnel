var campaign_actions_str_linkclicked = '{"When this link is clicked..."|alang|js}';
var campaign_actions_str_readopen    = '{"When this campaign is opened..."|alang|js}';

var campaign_action_linkid = 0;

{literal}

function campaign_actions(msg, enclink, linkidx, linkid) {
	var link = adesk_b64_decode(enclink);

	switch (link) {
		case 'open':
			$("span_actionheader").innerHTML = campaign_actions_str_readopen;
			$("form_type_hidden").value = "read"; break;
		default:
			// link
			subscriber_action_form_linkid = linkid;
			$("span_actionheader").innerHTML = campaign_actions_str_linkclicked;
			$("form_type_hidden").value = "link"; break;
	}

	subscriber_action_form_linkidx = linkidx;
	subscriber_action_form_campaignload();

	$('linkmessageid').value = msg;
	// Seems to have been commented out in the template...
	/*
	$('readtext').className = ( link == 'open' ? 'adesk_inline' : 'adesk_hidden' );
	$('linktext').className = ( link != 'open' ? 'adesk_inline' : 'adesk_hidden' );
	$('linkurl').className = ( link != 'open' ? 'adesk_inline' : 'adesk_hidden' );
	$('linkurl').innerHTML = link;
	*/
	// reset number of actions to 1
	var wasactions = $('actionClonerDiv').getElementsByTagName('div').length;
	for ( var i = wasactions - 1; i > 0; i-- ) {
		// Leave the 1st alone
		remove_element($('actionClonerDiv').getElementsByTagName('div')[i]);
	}

	if (link == 'open') {
		$("subscriber_action_deleteall").show();
		// add them
		if (campaign_has_readactions()) {
			var readaction = campaign_obj.readactions[0];	// there can only be one
			var hasreadactions = false;
			for (var i in readaction.parts) {
				if (typeof i == "string") {
					hasreadactions = true;
					if ( parseInt(i, 10) > 0 ) clone_1st_div($('actionClonerDiv'));
					var a = readaction.parts[i];
					var rel = $('actionClonerDiv').getElementsByTagName('div')[i];
					// now populate selects/inputs
					var selects = rel.getElementsByTagName('select');
					var inputs = rel.getElementsByTagName('input');
					if (typeof a.act != "undefined") {
						alert("a.act = " + a.act);
						a.action = a.act;

						if (a.act == "update") {
							if (a.targetid > 0)
								a.value = sprintf("%d||%s", a.targetid, a.param);
							else
								a.value = sprintf("%s||%s", a.targetfield, a.param);
						} else {
							a.value = a.targetid;
						}
					}

					selects[0].value = a.action;
					if ( a.action == 'subscribe' || a.action == 'unsubscribe' ) {
						selects[1].value = a.value;
					} else if ( a.action == 'send' ) {
						selects[2].value = a.value;
					} else if ( a.action == 'update' ) {
						selects[3].value = a.value.split('||')[0];
						inputs[0].value = a.value.split('||', 2)[1];
					} else {
						alert('Unknown action: ' + a.action);
					}
					campaign_action_changed(rel);
				}
			}
		}
		// if none were added, set first one to default
		if ( !hasreadactions ) {
			var rel = $('actionClonerDiv').getElementsByTagName('div')[0];
			// now populate selects/inputs
			var selects = rel.getElementsByTagName('select');
			var inputs = rel.getElementsByTagName('input');
			selects[0].value = 'subscribe';
			selects[1].selectedIndex = 0;
			campaign_action_changed(rel, true);
		}
	} else {
		// find if there are any actions set earlier
		var linkidx = campaign_actions_find(msg, link);
		$("subscriber_action_deleteall").hide();
		if ( linkidx != -1 ) {
			$("subscriber_action_deleteall").show();
			// add them
			for ( var i = 0; i < campaign_obj.actions[linkidx].length; i++ ) {
				if ( i > 0 ) clone_1st_div($('actionClonerDiv'));
				var a = campaign_obj.actions[linkidx][i];
				var rel = $('actionClonerDiv').getElementsByTagName('div')[i];
				// now populate selects/inputs
				var selects = rel.getElementsByTagName('select');
				var inputs = rel.getElementsByTagName('input');
				if (typeof a.act != "undefined") {
					a.action = a.act;

					if (a.act == "update") {
						if (a.targetid > 0)
							a.value = sprintf("%d||%s", a.targetid, a.param);
						else
							a.value = sprintf("%s||%s", a.targetfield, a.param);
					} else {
						a.value = a.targetid;
					}
				}

				selects[0].value = a.action;
				if ( a.action == 'subscribe' || a.action == 'unsubscribe' ) {
					selects[1].value = a.value;
				} else if ( a.action == 'send' ) {
					selects[2].value = a.value;
				} else if ( a.action == 'update' ) {
					selects[3].value = a.value.split('||')[0];
					inputs[0].value = a.value.split('||', 2)[1];
				} else {
					alert('Unknown action: ' + a.action);
				}
				campaign_action_changed(rel);
			}
			// if none were added, set first one to default
			if ( campaign_obj.actions[linkidx].length == 0 ) {
				var rel = $('actionClonerDiv').getElementsByTagName('div')[0];
				// now populate selects/inputs
				var selects = rel.getElementsByTagName('select');
				var inputs = rel.getElementsByTagName('input');
				selects[0].value = 'subscribe';
				selects[1].selectedIndex = 0;
				campaign_action_changed(rel, true);
			}
		}
	}
	return false;
}

function campaign_link_action_new() {
	clone_1st_div($('actionClonerDiv'));
	campaign_action_init();
}

function campaign_action_init() {
	var index = $('actionClonerDiv').getElementsByTagName('div').length - 1;
	var rel = $('actionClonerDiv').getElementsByTagName('div')[index];
	// now populate selects/inputs
	var selects = rel.getElementsByTagName('select');
	var inputs = rel.getElementsByTagName('input');
	selects[0].value = 'subscribe';
	selects[1].selectedIndex = 0;
	$(selects[1]).show();
	$(selects[2]).hide();
	$(selects[3]).hide();
	$(inputs[0]).hide();
	campaign_action_changed(rel, true);
}

function campaign_action_changed(rel, add) {
	// now populate selects
	var selects = rel.getElementsByTagName('select');
	var inputs = rel.getElementsByTagName('input');
	// build action object first
	var a = {};
	a.action = selects[0].value;
	if ( a.action == 'subscribe' || a.action == 'unsubscribe' ) {
		a.value = selects[1].value;
	} else if ( a.action == 'send' ) {
		a.value = selects[2].value;
	} else if ( a.action == 'update' ) {
		a.value = selects[3].value + '||' + inputs[0].value;
	} else {
		alert('Unknown action: ' + a.action);
		a.value = '';
	}
	// list select
	if ( a.action == 'subscribe' || a.action == 'unsubscribe' ) {
		$(selects[1]).show();
		var options = selects[1].getElementsByTagName('option');
		if ( add ) selects[1].selectedIndex = 0;
	} else {
		$(selects[1]).hide();
	}
	// message select
	if ( a.action == 'send' ) {
		$(selects[2]).show();
		var options = selects[2].getElementsByTagName('option');
		for ( var j = 0; j < options.length; j++ ) {
			if ($('messageDiv') !== null) {
				if ( typeof campaign_obj != "undefined" && campaign_obj.type == 'split' ) {
					var messages = adesk_dom_boxchoice("messageField");
				} else {
					var messages = adesk_dom_boxchoice("messageField");
				}
				adesk_dom_hideif($(options[j]), adesk_array_has(messages, options[j].value));
			}
		}
		if ( add ) selects[2].selectedIndex = 0;
	} else {
		$(selects[2]).hide();
	}
	// subscriber select
	if ( a.action == 'update' ) {
		$(selects[3]).show();
		$(inputs[0]).show();
		if ( add ) {
			selects[3].selectedIndex = 0;
			inputs[0].value = '';
		}
	} else {
		$(selects[3]).hide();
		$(inputs[0]).hide();
	}
}

function campaign_actions_find(msg, link) {
	for ( var i = 0; i < campaign_obj.links.length; i++ ) {
		if ( campaign_obj.links[i] == link && typeof(campaign_obj.linkmessages[i]) != 'undefined' && campaign_obj.linkmessages[i] == msg ) {
			return i;
		}
	}
	return -1;
}

function campaign_actions_set(msg, link, actions) {
	var found = campaign_actions_find(msg, link);
	if ( found == -1 ) {
		found = campaign_obj.links.length;
		campaign_obj.links.push(link);
	}
	campaign_obj.actions[found] = actions;
	campaign_obj.linkmessages[found] = msg;
	return found;
}

function campaign_actions_save() {
	var link = ( $('linkurl').style.display == 'none' ? 'open' : $('linkurl').innerHTML );
	var actions = [];
	var rel = $('actionClonerDiv');
	var actionrows = rel.getElementsByTagName('div');
	for ( var i = 0; i < actionrows.length; i++ ) {
		var selects = actionrows[i].getElementsByTagName('select');
		var inputs = actionrows[i].getElementsByTagName('input');
		var a = { action: selects[0].value };
		if ( a.action == 'subscribe' || a.action == 'unsubscribe' ) {
			a.value = selects[1].value;
		} else if ( a.action == 'send' ) {
			a.value = selects[2].value;
		} else if ( a.action == 'update' ) {
			a.value = selects[3].value + '||' + inputs[0].value;
		} else {
			alert('Unknown action: ' + a.action);
			a.value = '';
		}
		actions.push(a);
	}
	var linkid = campaign_actions_set(parseInt($('linkmessageid').value, 10), link, actions);
	// now add it to page
	$('messagelinkactions' + ( link == 'open' ? 'open' : linkid )).innerHTML = sprintf(campaign_actionscnt_str, actions.length);
	// now turn the link on if its off and actions are added
	if ( !$(( link == 'open' ? 'trackreads' : 'messagelinktrack' + linkid )).checked && actions.length > 0 ) {
		$(( link == 'open' ? 'trackreads' : 'messagelinktrack' + linkid )).checked = true;
	}
	$('link_actions').toggle();
}

function campaign_actions_deleteall(id) {
	adesk_ajax_call_cb("awebdeskapi.php", "subscriber_action.subscriber_action_deleteparts", adesk_ajax_cb(campaign_actions_deleteall_cb), id);
}

function campaign_actions_deleteall_cb(ary) {
	if ( $("form_type_hidden") && $("form_type_hidden").value != "" ) {
		if ($("form_type_hidden").value == "read" && typeof campaign_actionid_readopen != "undefined") {
			$("messagelinkactionsopen").innerHTML = sprintf("%d %s", 0, subscriber_action_form_str_actions);
		}
		else if (subscriber_action_form_linkidx != '') {
			if (subscriber_action_form_linkidx > -1)
				$("messagelinkactions" + subscriber_action_form_linkidx).innerHTML = sprintf("%d %s", 0, subscriber_action_form_str_actions);
		}
	}

	subscriber_action_form_back();
}

{/literal}
