var campaign_str_nosegment = '{"No segment"|alang|js}';
var campaign_nolist_str = '{"Please select at least one list before you continue"|alang|js}';

var campaign_list_subcount = 0;

var campaign_list_totals = {literal}{}{/literal};
{foreach from=$lists item=e}
campaign_list_totals[{$e.id}] = {$e.count};
{/foreach}

// Filter code
var filter_form_str_cant_insert = '{"You do not have permission to add filters."|alang|js}';
var filter_form_str_cant_update = '{"You do not have permission to edit filters."|alang|js}';
var filter_form_str_cant_find   = '{"Filter not found."|alang|js}';
var filter_form_str_edit        = '{"Edit"|alang|js}';
var filter_form_str_noname      = '{"You did not provide a name for this filter."|alang|js}';
var filter_form_str_groupn      = '{"Group %d"|alang|js}';
var filter_form_str_status      = '{"Status"|alang|js}';
var filter_form_str_is          = '{"Is"|alang|js}';
var filter_form_str_isnot       = '{"Is not"|alang|js}';
var filter_form_str_isoneof     = '{"Is one of"|alang|js}';
var filter_form_str_contains    = '{"Contains"|alang|js}';
var filter_form_str_doesntcontain = '{"Does not contain"|alang|js}';
var filter_form_str_greater     = '{"Greater than"|alang|js}';
var filter_form_str_less        = '{"Less than"|alang|js}';
var filter_form_str_greatereq   = '{"Greater than or equal to"|alang|js}';
var filter_form_str_lesseq      = '{"Less than or equal to"|alang|js}';
var filter_form_str_active      = '{"Active"|alang|js}';
var filter_form_str_notactive   = '{"Not active"|alang|js}';
var filter_form_str_nolists     = '{"You did not mark any lists that this filter would use."|alang|js}';
var filter_form_str_checked     = '{"Checked"|alang|js}';
var filter_form_str_unchecked   = '{"Unchecked"|alang|js}';

var filter_form_str_anylink     = '{"Any link"|alang|js}';
var filter_form_str_anylist     = '{"Any list"|alang|js}';
var filter_form_str_anycampaign = '{"Any campaign"|alang|js}';

var filter_form_str_subscribed  = '{"Subscribed"|alang|js}';
var filter_form_str_unsubscribed = '{"Unsubscribed"|alang|js}';
var filter_form_str_confirmed   = '{"Confirmed"|alang|js}';
var filter_form_str_unconfirmed = '{"Unconfirmed"|alang|js}';
var filter_form_str_bounced = '{"Bounced"|alang|js}';

var filter_form_str_facebook    = '{"Facebook"|alang|js}';
var filter_form_str_twitter     = '{"Twitter"|alang|js}';
var filter_form_str_digg        = '{"Digg"|alang|js}';
var filter_form_str_delicious   = '{"del.icio.us"|alang|js}';
var filter_form_str_greader     = '{"Google Reader"|alang|js}';
var filter_form_str_reddit      = '{"Reddit"|alang|js}';
var filter_form_str_stumbleupon = '{"StumbleUpon"|alang|js}';

var filter_form_str_noname      = '{"You must enter a name for this segment."|alang|js}';

{literal}

var customfields = {};

function campaign_filters() {
	var post = $("campaignform").serialize(true);

	if (typeof post.listid == "undefined") {
		adesk_dom_remove_children($("segments"));
		campaign_filters_default(true);
		$("segmentlink").hide();
		$("campaignfilterbox").hide();
		return;
	}

	$("segmentlink").show();

	var lists;

	if (typeof post.listid == "object")
		lists = post.listid.join(",");
	else
		lists = post.listid;

	adesk_ajax_call_cb("awebdeskapi.php", "filter.filter_listfilters", adesk_ajax_cb(campaign_filters_cb), lists);
	adesk_ajax_call_cb("awebdeskapi.php", "list.list_get_fields", adesk_ajax_cb(campaign_fields_cb), lists);
}

function campaign_fields_cb(ary) {
	var dropdowns = $$(".form_filter_cond_lhs");
	if (!dropdowns)
		return;

	for (var i = 0; i < dropdowns.length; i++) {
		// Standard fields.
		adesk_dom_remove_children(dropdowns[i]);

		dropdowns[i].appendChild(Builder.node("option", { value: "standard:email" }, {/literal}'{"Subscriber Field"|alang|js} - {"Email Address"|alang|js}'{literal}));
		dropdowns[i].appendChild(Builder.node("option", { value: "standard:first_name" }, {/literal}'{"Subscriber Field"|alang|js} - {"First Name"|alang|js}'{literal}));
		dropdowns[i].appendChild(Builder.node("option", { value: "standard:last_name" }, {/literal}'{"Subscriber Field"|alang|js} - {"Last Name"|alang|js}'{literal}));
		dropdowns[i].appendChild(Builder.node("option", { value: "standard:*fullname" }, {/literal}'{"Subscriber Field"|alang|js} - {"Full Name"|alang|js}'{literal}));
		dropdowns[i].appendChild(Builder.node("option", { value: "standard:*cdate" }, {/literal}'{"Subscriber Field"|alang|js} - {"Date Subscribed"|alang|js}'{literal}));
		dropdowns[i].appendChild(Builder.node("option", { value: "standard:*ctime" }, {/literal}'{"Subscriber Field"|alang|js} - {"Time Subscribed"|alang|js}'{literal}));
		dropdowns[i].appendChild(Builder.node("option", { value: "standard:*ip" }, {/literal}'{"Subscriber Field"|alang|js} - {"IP Address"|alang|js}'{literal}));
		dropdowns[i].appendChild(Builder.node("option", { value: "standard:*status" }, {/literal}'{"Subscriber Field"|alang|js} - {"Status"|alang|js}'{literal}));

		if (typeof ary.row != "undefined") {
			for (var j = 0; j < ary.row.length; j++) {
				dropdowns[i].appendChild(Builder.node("option", { value: sprintf("custom:%s", ary.row[j].id) }, {/literal}'{"Custom Field"|alang|js} - '{literal} + ary.row[j].title));
			}
		}

		dropdowns[i].appendChild(Builder.node("option", { value: "action:linkclicked" }, {/literal}'{"Action"|alang|js} - {"Has clicked on a link"|alang|js}'{literal}));
		dropdowns[i].appendChild(Builder.node("option", { value: "action:linknotclicked" }, {/literal}'{"Action"|alang|js} - {"Has not clicked on a link"|alang|js}'{literal}));
		dropdowns[i].appendChild(Builder.node("option", { value: "action:opened" }, {/literal}'{"Action"|alang|js} - {"Has opened/read"|alang|js}'{literal}));
		dropdowns[i].appendChild(Builder.node("option", { value: "action:notopened" }, {/literal}'{"Action"|alang|js} - {"Has not opened/read"|alang|js}'{literal}));
		dropdowns[i].appendChild(Builder.node("option", { value: "action:social" }, {/literal}'{"Action"|alang|js} - {"Has shared socially"|alang|js}'{literal}));
		dropdowns[i].appendChild(Builder.node("option", { value: "action:inlist" }, {/literal}'{"Action"|alang|js} - {"In list"|alang|js}'{literal}));
		dropdowns[i].appendChild(Builder.node("option", { value: "action:notinlist" }, {/literal}'{"Action"|alang|js} - {"Not in list"|alang|js}'{literal}));
		dropdowns[i].appendChild(Builder.node("option", { value: "action:forwarded" }, {/literal}'{"Action"|alang|js} - {"Has forwarded"|alang|js}'{literal}));
		dropdowns[i].appendChild(Builder.node("option", { value: "action:notforwarded" }, {/literal}'{"Action"|alang|js} - {"Has not forwarded"|alang|js}'{literal}));
	}
}

function campaign_lists() {
	// make sure it's a string, in case just one list is selected
	var lists = campaign_obj.listslist + '';
	var ary = lists.split(/-/);
	adesk_dom_boxset("listsList", ary);
}

function campaign_filters_default() {
	$("segments").appendChild(Builder.node("label", [
		Builder.node("input", { type: "radio", name: "filterid", value: "0", className: "filtersList" }),
		Builder.node("em", campaign_str_nosegment)
	]));

	adesk_dom_radioset("filtersList", "0");
}

function campaign_filters_cb(ary) {
	adesk_dom_remove_children($("segments"));

	var hasdefault = false;

	if (typeof ary.row != "undefined") {
		for (var i = 0; i < ary.row.length; i++) {
			$("segments").appendChild(Builder.node("label", [
				Builder.node("input", { type: "radio", name: "filterid", value: ary.row[i].id, className: "filtersList" }),
				Builder._text(ary.row[i].name)
			]));

			if (campaign_obj.filterid == ary.row[i].id) {
				hasdefault = true;
			}
		}
	}

	campaign_filters_default();

	adesk_dom_radioset("filtersList", campaign_obj.filterid.toString());

	if (hasdefault) {
		$("segmentlink").show();
		campaign_filter_show(true);
	}
}

function campaign_filter_set(filters) {
	// deal with filters for these lists
	var rel = $('filterDiv');
	var prevVal = rel.value;
	var filterFound = false;
	var prevFound = false;
	adesk_dom_remove_children(rel);
	if ( filters ) {
		for ( var i in filters ) {
			var f = filters[i];
			if ( typeof f != 'function' ) {
				if ( campaign_obj.filterid > 0 && parseInt(f.id, 10) == campaign_obj.filterid ) {
					filterFound = true;
				}
				if ( parseInt(f.id, 10) == parseInt(prevVal, 10) ) {
					prevFound = true;
				}
				var label = Builder.node("label");
				label.appendChild(Builder.node("input", { type: "radio", title: f.name, className: "filterField", name: "filterid", value: f.id }));
				label.appendChild(Builder._text(f.name));
				rel.appendChild(label);
				//rel.appendChild(Builder.node('option', { value: f.id }, [ Builder._text(f.name) ]));
			}
		}
	}
	// filter preselect
	if ( filterFound ) {
		adesk_dom_radioset("filterField", campaign_obj.filterid);
		$("segmentlink").show();
		$('usefilterbox').show();
		$('campaignfilterbox').show();
	} else {
		if ( prevFound ) {
			adesk_dom_radioset("filterField", prevVal);
			$("segmentlink").show();
			$('usefilterbox').show();
			$('campaignfilterbox').show();
		} else {
			adesk_dom_radioclear("filterField");
			$("segmentlink").hide();
			$('usefilterbox').hide();
			$('campaignfilterbox').hide();
		}
	}
}

function campaign_filter_toggle() {
	if ($("campaignfilterbox").style.display == "none")
		campaign_filter_show(true);
	else
		campaign_filter_show(false);
}

function campaign_filter_show(checked) {
	if (checked) {
		var ary = $$("input.filtersList");

		if (ary.length > 1) {
			$("segments").show();
			$("addsegmentdiv").show();
			$("filternew").hide();
		} else {
			$("segments").hide();
			$("addsegmentdiv").hide();
			$("filternew").show();
			$("filter_buttons").hide();

			var lhs = $$(".form_filter_cond_lhs");

			if (lhs.length < 2)
				filter_form_addgroup('and', true, 0);
		}

		$("campaignfilterbox").show();
	} else {
		$("segments").hide();
		$("addsegmentdiv").hide();
		$("filternew").hide();
		filter_form_cleargroups();
		adesk_dom_radioset("filtersList", "0");
		$("campaignfilterbox").hide();
	}
}

function campaign_message_fill(ary) {
	// deal with messages for these lists
	var rel = $('messageDiv');
	adesk_dom_remove_children(rel);
	//adesk_dom_remove_children(actions);
	if ( ary ) {
		// loop first time to add the message we are editing as first option (top) in select list
		for ( var i in ary ) {
			var m = ary[i];
			if ( typeof m != 'function' ) {
				// only add an option if it is the one we are editing - this would be the very first (top) option
				if (m.id == campaign_new_messagefilter) {
					var props = { value: m.id, id: 'message' + m.id };
					if ( parseInt(m.usedb4, 10) > 0 ) props.className = 'messageusedb4';
					var label = Builder.node("label");
					label.appendChild(Builder.node("input", { type: "checkbox", onclick: "campaign_message_change(this.value)", value: m.id, className: "messageField", id: "message" + m.id, name: "messageid[]" }));
					label.appendChild(Builder._text(" "));
					label.appendChild(Builder._text(strip_tags(m.subject, true)));
					$("messageDiv").appendChild(label);
				}
			}
		}
		// loop second time to add rest of options to select list
		for ( var i in ary ) {
			var m = ary[i];
			if ( typeof m != 'function' ) {
				// add everything besides message we are editing (that was already added above)
				if (m.id != campaign_new_messagefilter) {
					var props = { value: m.id, id: 'message' + m.id };
					if ( parseInt(m.usedb4, 10) > 0 ) props.className = 'messageusedb4';
					var label = Builder.node("label");
					label.appendChild(Builder.node("input", { type: "checkbox", onclick: "campaign_message_change(this.value)", value: m.id, className: "messageField", id: "message" + m.id, name: "messageid[]" }));
					label.appendChild(Builder._text(" "));
					label.appendChild(Builder._text(strip_tags(m.subject, true)));
					$("messageDiv").appendChild(label);
				}
			}
		}
	}
}

function campaign_step2_checknext() {
	var lists = adesk_dom_boxchoice("parentsList");
	// if there are any lists selected
	if ( lists.length > 0 ) {
		// start building an array of distinct filters
		filters = {};
		// for evey list
		for ( var i = 0; i < lists.length; i++ ) {
			var lid = lists[i];
			// if there are any filters for it
			if ( typeof list_filters[lid] != 'undefined' ) {
				// loop through all filters
				for ( var k in list_filters[lid] ) {
					var f = list_filters[lid][k];
					if ( typeof f != 'function' ) {
						// and add them
						filters[f.id] = f;
					}
				}
			}
		}
		// now when we have a final array, add it
		campaign_filter_set(filters);
	}
	return lists.length > 0;
}

function campaign_filter_create() {
	if ( $('filternew').style.display != 'none' ) {
		$('filternew').hide();
		if (adesk_dom_radiochoice("filtersList") != 0) {
			$("segmentlink").show();
			$("campaignfilterbox").show();
		} else {
			$("segmentlink").hide();
			$("campaignfilterbox").hide();
		}
		$("segments").show();
		$("addsegmentdiv").show();
		return;
	}
	else {
		$("segmentlink").hide();
		$("campaignfilterbox").hide();
	}
	if (typeof filter_form_defaults == "function")
		filter_form_defaults();

	$('filternew').show();
	$('step2next').disabled = true;
	$('form_filter_name').focus(); // put focus in the "Name This Segment" text field (IE has problems otherwise)
}

function campaign_filters_add() {
	$("segments").hide();
	$("addsegmentdiv").hide();
	$("filternew").show();
	$("filter_buttons").show();

	filter_form_cleargroups();
	filter_form_addgroup('and', true, 0);
}

function campaign_fixpost() {
	$$("input.listsList").each(function(e) {
		if (!e.name.match(/\[\]$/))
			e.name = e.name + "[]";
	});

	// We need to fix the names first.
	var rhsary = $$(".form_filter_cond_rhs_item");
	for (var i = 0; i < rhsary.length; i++) {
		if ( rhsary[i].name.indexOf('[') > -1 )
			rhsary[i].name = rhsary[i].name.substr(0, rhsary[i].name.indexOf('['));

		if (!rhsary[i].name.match(/\]$/))
			rhsary[i].name = rhsary[i].name + sprintf("[%d]", i);
	}

	adesk_dom_remove_children($("filter_group_condlen_div"));

	for (var i = 0; i < filter_form_condlen.length; i++) {
		if (typeof filter_form_condlen[i] != "undefined") {
			$("filter_group_condlen_div").appendChild(Builder.node("input", { type: "hidden", name: "filter_group_condlen[]", value: filter_form_condlen[i] }));
		}
	}
}

function campaign_list_countchecked() {
	var listcount = 0;

	$A($$("input.listsList")).each(function(e) { if (e.checked) listcount++; });
	return listcount;
}

function campaign_validate() {
	var listcount = campaign_list_countchecked();

	if (listcount == 0) {
		alert(campaign_nolist_str);
		return false;
	}

	// Figure out how many we're sending to (approximately).
	campaign_list_subcount = 0;
	$$(".listsList").each(function(e) { if (e.checked) campaign_list_subcount += campaign_list_totals[e.value]; });

	if (campaign_list_subcount == 0) {
		$("list_nobody").show();
		return false;
	}

	if ($("filternew").style.display != "none") {
		if ($("form_filter_name").value == "") {
			alert(filter_form_str_noname);
			return false;
		}
	}

	return true;
}

// Filter code
var objProps = { value: 0 };
{/literal}
var filter_form_campaigns       = [
	Builder.node("option", objProps, filter_form_str_anycampaign)
];

{foreach from=$campaigns item=c}
objProps.value = {$c.id};
filter_form_campaigns.push(Builder.node('option', objProps, '{$c.name|js}'));

{/foreach}

{literal}

var filter_form_condlen = [];

var filter_form_id        = 0;
var filter_form_numgroups = 0;
var filter_form_numconds  = 0;

var filter_form_groupseq  = 0;

function filter_form_defaults() {
	if ($("form_filter_id"))
		$("form_filter_id").value    = 0;
	$("form_filter_name").value  = "";
	$("form_filter_logic").value = "and";

	// Only run this code if we're on the standalone filter screen.
	if (!$("step2next")) {

		var list_inputs = $('parentsList_div').getElementsByTagName('input');
		if ( filter_listfilter && typeof(filter_listfilter) == 'object' ) {
			for (var i in filter_listfilter) {
				if ( $('p_' + filter_listfilter[i]) ) $('p_' + filter_listfilter[i]).checked = true;
			}
		} else if ( filter_listfilter > 0 ) {
			if ( $('p_' + filter_listfilter) ) $('p_' + filter_listfilter).checked = true;
		} else {
			// check all lists by default
			for (var i = 0; i < list_inputs.length; i++) {
				list_inputs[i].checked = true;
			}
		}
	}
}

function filter_form_load(id) {
	filter_form_defaults();
	filter_form_id = id;

	if (id > 0) {
		if (adesk_js_admin.pg_subscriber_filters != 1) {
			adesk_ui_anchor_set(filter_list_anchor());
			alert(filter_form_str_cant_update);
			return;
		}

		filter_form_cleargroups();

		adesk_ui_api_call(jsLoading);
		$("form_submit").className = "adesk_button_update";
		$("form_submit").value = jsUpdate;
		adesk_ajax_call_cb("awebdeskapi.php", "filter.filter_select_row", filter_form_load_cb, id);
	} else {
		if (adesk_js_admin.pg_subscriber_filters != 1) {
			adesk_ui_anchor_set(filter_list_anchor());
			alert(filter_form_str_cant_insert);
			return;
		}

		filter_form_cleargroups();

		// Add one group and one condition by default.
		filter_form_addgroup("and", true, 0);

		$("form_submit").className = "adesk_button_add";
		$("form_submit").value = jsAdd;
		$("form").className = "adesk_block";
	}
}

function filter_form_load_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();
	if ( !ary.id ) {
		adesk_error_show(filter_form_str_cant_find);
		adesk_ui_anchor_set(filter_list_anchor());
		return;
	}
	filter_form_id = ary.id;

	ary.lists = ary.lists.toString().split(",");

	$("form_filter_id").value    = ary.id;
	$("form_filter_name").value  = ary.name;
	$("form_filter_logic").value = ary.logic;

	if (filter_form_numgroups > 0)
		filter_form_cleargroups();

	for (var i = 0; i < ary.groups.length; i++) {
		filter_form_addgroup(ary.groups[i].logic, false, i+1);
		for (var j = 0; j < ary.groups[i].conds.length; j++) {
			var lhs = sprintf("%s:%s", ary.groups[i].conds[j].type, ary.groups[i].conds[j].lhs);
			var op  = ary.groups[i].conds[j].op;
			var rhs = ary.groups[i].conds[j].rhs;
			filter_form_addcond(i+1, lhs, op, rhs);
		}
	}

	// lists
	var list_inputs = $('parentsList_div').getElementsByTagName('input');
	// uncheck all lists first
	for (var i = 0; i < list_inputs.length; i++) {
		list_inputs[i].checked = false;
	}
	// now check the ones pertaining to this form
	var lists = (ary.lists + '').split(',');
	for (var i = 0; i < lists.length; i++) {
		if ( $('p_' + lists[i]) ) $('p_' + lists[i]).checked = true;
	}

	$("form").className = "adesk_block";
}

function filter_form_save(id, redir) {
	redirect_to_campaign = redir;

	// Because of the name fix below, we need to check the filter name and
	var name       = $("form_filter_name").value;

	if ($("parentsList"))
		var parentlist = adesk_form_select_extract($("parentsList"));
	else
		var parentlist = adesk_dom_boxchoice("parentsList");

	if (name == "" || name.match(/^ +$/)) {
		alert(filter_form_str_noname);
		return false;
	}

	if (parentlist.length < 1) {
		alert(filter_form_str_nolists);
		return false;
	}

	// We need to fix the names first.
	var rhsary = $$(".form_filter_cond_rhs_item");
	for (var i = 0; i < rhsary.length; i++) {
		if ( rhsary[i].name.indexOf('[') > -1 ) rhsary[i].name = rhsary[i].name.substr(0, rhsary[i].name.indexOf('['));
		rhsary[i].name = rhsary[i].name + sprintf("[%d]", i);
	}

	if ( redir == true ) {
		var post = adesk_form_post($("filternew"));
		post.p = parentlist;
	} else {
		var post = adesk_form_post($("form"));
	}

	post.filter_group_condlen = filter_form_condlen;

	adesk_ui_api_call(jsSaving);

	if (id > 0)
		adesk_ajax_post_cb("awebdeskapi.php", "filter.filter_update_post", filter_form_save_cb, post);
	else
		adesk_ajax_post_cb("awebdeskapi.php", "filter.filter_insert_post", filter_form_save_cb, post);
}

function filter_form_save_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		if ( redirect_to_campaign ) { // if used within campaign page
			// add to dropdown
			var label = Builder.node("label");
			label.appendChild(Builder.node("input", { type: "radio", title: ary.name, name: "filterid", className: "filterField", value: ary.id }));
			label.appendChild(Builder._text(ary.name));
			$('filterDiv').appendChild(label);
			adesk_dom_radioset("filterField", ary.id);
			//$('filterField').appendChild(Builder.node('option', { value: ary.id, selected: true }, [ Builder._text(ary.name) ]));
			// hide add form
			$('filternew').hide();
			$('usefilterbox').show();
			// re-enable NEXT button
			$('step2next').disabled = false;
		} else {
			adesk_ui_anchor_set(filter_list_anchor());
		}
	} else {
		adesk_error_show(ary.message);
	}
}

function filter_form_cleargroups() {
	filter_form_numgroups = 0;
	filter_form_condlen   = [];
	adesk_dom_remove_children($("filter_groupcontainer"));
}

function filter_form_deletegroup(seq) {
	var divs = $("filter_groupcontainer").childNodes;
	var groupn = seq;

	if (filter_form_numgroups < 2)
		return;

	seq = (seq - 1) * 2;
	if (seq >= 0 && seq < (divs.length - 1)) {
		$("filter_groupcontainer").removeChild(divs[seq]);
		$("filter_groupcontainer").removeChild(divs[seq]);
		filter_form_numgroups--;
		filter_form_condlen[groupn] = 0;
	}

	for (var i = 0; i < filter_form_numgroups; i++) {
		// We use i+1 here to avoid choosing the example title div we use for cloning.
		var titlenum = $$("span.filter_group_title_number")[i+1];
		titlenum.innerHTML = (i+1).toString();
	}
}

function filter_form_addgroup(logic, withcond, groupn) {
	filter_form_numgroups++;
	filter_form_groupseq++;

	if (groupn == 0)
		groupn = filter_form_groupseq;
	var title = $$("div.filter_group_title")[0].cloneNode(true);
	var group = $$("div.filter_group")[0].cloneNode(true);

	var num   = $(title).getElementsBySelector("span.filter_group_title_number")[0];
	num.innerHTML = filter_form_numgroups.toString();

	var img   = $(title).getElementsBySelector("img.form_filter_group_delete")[0];
	img.onclick = function() {
		filter_form_deletegroup(groupn);
	};

	var glogic = $(group).getElementsBySelector(".form_filter_group_logic");

	if (glogic.length > 0) {
		glogic = glogic[0];
		glogic.value = logic;
	}

	var container = group.getElementsBySelector("tbody.form_filter_condcontainer")[0];
	container.id = "filter_condcontainer" + groupn.toString();

	var link = group.getElementsBySelector("a.filter_group_addcond")[0];
	link.onclick = function() {
		filter_form_addcond(groupn, "", "", "");
		return false;
	};

	$("filter_groupcontainer").appendChild(title);
	$("filter_groupcontainer").appendChild(group);

	if (withcond)
		filter_form_addcond(groupn, "", "", "");
}

function filter_form_addcond(groupn, deflhs, defop, defrhs) {
	filter_form_numconds++;

	var tbody = $("form_filter_examplecond").cloneNode(true);
	var tr    = tbody.getElementsByTagName("tr")[0];

	var lhs   = $(tr).getElementsBySelector('select.form_filter_cond_lhs')[0];
	var op    = $(tr).getElementsBySelector('select.form_filter_cond_op')[0];
	var rhs   = $(tr).getElementsBySelector('div.form_filter_cond_rhs')[0];
	var img   = $(tr).getElementsBySelector('img.form_filter_cond_delete')[0];

	tr.id     = "form_filter_cond"     + filter_form_numconds.toString();
	lhs.id    = "form_filter_cond_lhs" + filter_form_numconds.toString();
	rhs.id    = "form_filter_cond_rhs" + filter_form_numconds.toString();
	op.id     = "form_filter_cond_op"  + filter_form_numconds.toString();

	lhs.value = deflhs;

	// Build the operator section...
	var opts  = $A(filter_form_genop(lhs.value, filter_form_numconds, defrhs, defop));

	// Append each of the options returned from genop (they are always options).
	// Weird that we have to check for this below, but I've seen it in IE (of course)...
	opts.each(function(opt) { if (typeof opt == "object") op.appendChild(opt); });
	op.value  = defop != "" ? defop : "equal";

	var inst = filter_form_numconds;

	lhs.onchange = function() { filter_form_changelhs(inst); };
	op.onchange  = function() { filter_form_changeop(inst); };

	// Since the right-hand side is a div, we can append whatever gets returned
	// by genrhs without any further processing.
	var rhsobj = filter_form_genrhs(lhs.value, op.value, filter_form_numconds, defrhs);

	if (typeof rhsobj.value != "undefined")
		rhsobj.value = defrhs;
	else {
		// What's returned is probably a div; the real form element is deeper inside.
		var rhstarget = $(rhsobj).getElementsBySelector(".form_filter_cond_rhs_item")[0];
		rhstarget.value = defrhs;
	}

	rhs.appendChild(rhsobj);

	var container = $("filter_condcontainer" + groupn.toString());

	// Hook up the delete function.
	img.onclick = function() {
		if (container.getElementsByTagName("tr").length > 1) {
			$(container).removeChild($(tr.id));
			filter_form_condlen[groupn] = container.getElementsByTagName("tr").length;
		}
	};

	$(container).appendChild(tr);
	filter_form_condlen[groupn] = container.getElementsByTagName("tr").length;

	// If we don't force the changelhs function to run in this case, IE will show the wrong
	// dropdown for the op select when you add a new condition.
	if (defrhs === "" && !lhs.value.match(/inlist/))	// this also matches notinlist
		filter_form_changelhs(inst);
}

function filter_form_changelhs(seq) {
	var lhs = $("form_filter_cond_lhs" + seq.toString());
	var op  = $("form_filter_cond_op"  + seq.toString());
	var rhs = $("form_filter_cond_rhs" + seq.toString());

	var oldop    = op.value;
	var hasoldop = false;

	adesk_dom_remove_children(op);

	var opts = $A(filter_form_genop(lhs.value, seq, 0, 0));

	opts.each(function(opt) { op.appendChild(opt); if (opt.value == oldop) hasoldop = true; });
	if (hasoldop)
		op.value = oldop;
	else if (opts.length > 0)
		op.value = opts[0].value;

	var rhselems = rhs.getElementsBySelector(".form_filter_cond_rhs_item");
	var newrhs = filter_form_genrhs(lhs.value, op.value, seq, "");

	var oldrhs = null;

	if (rhselems.length > 0)
		oldrhs = rhs.getElementsBySelector(".form_filter_cond_rhs_item")[0];

 	if ($("form_filter_cond_rhs_hour" + seq.toString()) !== null || oldrhs === null || oldrhs.tagName != newrhs.tagName || (typeof oldrhs.type != "undefined" && typeof newrhs.type != "undefined" && oldrhs.type != newrhs.type)) {
		adesk_dom_remove_children(rhs);
		rhs.appendChild(newrhs);
	}
}

function filter_form_changeop(seq) {
	var lhs = $("form_filter_cond_lhs" + seq.toString());
	var op  = $("form_filter_cond_op"  + seq.toString());
	var rhs = $("form_filter_cond_rhs" + seq.toString());

	var oldrhs = rhs.getElementsBySelector(".form_filter_cond_rhs_item")[0];
	var newrhs = filter_form_genrhs(lhs.value, op.value, seq, "");

	if (oldrhs.tagName != newrhs.tagName) {
		adesk_dom_remove_children(rhs);
		rhs.appendChild(newrhs);
	}
}

function filter_form_isfieldcheckbox(name) {
	return name.match(/[0-9]+/)
		&& typeof customfields[name] != "undefined"
		&& customfields[name].type == 3;
}

function filter_form_genop(lhs, seq, def, defop) {
	var tmp = lhs.split(":");
	var name = tmp[1];
	var type = tmp[0];

	if (type == "standard" || type == "custom") {
		if (name == "*status") {
			return [
					Builder.node("option", { value: "equal" }, filter_form_str_is),
					Builder.node("option", { value: "notequal" }, filter_form_str_isnot)
				];
		} else if (filter_form_isfieldcheckbox(name)) {
			return [
				Builder.node("option", { value: "equal" }, filter_form_str_is),
			];
		} else {
			if (type == "standard") {
				return [
						Builder.node("option", { value: "equal" }, filter_form_str_is),
						Builder.node("option", { value: "notequal" }, filter_form_str_isnot),
						Builder.node("option", { value: "like" }, filter_form_str_contains),
						Builder.node("option", { value: "notlike" }, filter_form_str_doesntcontain),
						Builder.node("option", { value: "greater" }, filter_form_str_greater),
						Builder.node("option", { value: "less" }, filter_form_str_less),
						Builder.node("option", { value: "greatereq" }, filter_form_str_greatereq),
						Builder.node("option", { value: "lesseq" }, filter_form_str_lesseq)
					];
			} else {
				return [
						Builder.node("option", { value: "equal" }, filter_form_str_is),
						Builder.node("option", { value: "notequal" }, filter_form_str_isnot),
						Builder.node("option", { value: "oneof" }, filter_form_str_isoneof),
						Builder.node("option", { value: "like" }, filter_form_str_contains),
						Builder.node("option", { value: "notlike" }, filter_form_str_doesntcontain),
						Builder.node("option", { value: "greater" }, filter_form_str_greater),
						Builder.node("option", { value: "less" }, filter_form_str_less),
						Builder.node("option", { value: "greatereq" }, filter_form_str_greatereq),
						Builder.node("option", { value: "lesseq" }, filter_form_str_lesseq)
					];
			}
		}
	} else {
		var rval = [];

		if (name == "inlist" || name == "notinlist") {
			adesk_ajax_call_cb("awebdeskapi.php", "filter.filter_lists", adesk_ajax_cb(filter_oplists_cb), seq, defop);
			return Builder.node("select", { className: "form_filter_cond_op_item" }, []);
		} else {
			// We have to make clones of each node, or else should we try to remove
			// them as children later, we would remove all instances of these options
			// from our conditions.
			for (var i = 0; i < filter_form_campaigns.length; i++)
				rval.push(filter_form_campaigns[i].cloneNode(true));
		}

		return rval;
	}
}

function filter_form_seqname(name, seq) {
	return sprintf("%s[%d]", name, seq);
}

function filter_form_genrhs(lhs, op, seq, def) {
	var tmp = lhs.split(":");
	var name = tmp[1];
	var type = tmp[0];

	if (type == "standard" || type == "custom") {
		switch (name) {
			case "*status":
				// I can't seem to return this directly in firefox 3 -- firebug is
				// throwing some arcane sort of error.
				var x =
					Builder.node("select", { className: "form_filter_cond_rhs_item", name: "filter_group_cond_rhs" }, [
						Builder.node("option", { value: "1" }, filter_form_str_subscribed),
						Builder.node("option", { value: "2" }, filter_form_str_unsubscribed),
						Builder.node("option", { value: "0" }, filter_form_str_unconfirmed),
						Builder.node("option", { value: "3" }, filter_form_str_bounced)
					]);

				return x;
			case "*cdate":
				var x =
					Builder.node("div", [
						Builder.node("input", { type: "text", className: "form_filter_cond_rhs_item", name: "filter_group_cond_rhs", id: "form_filter_cond_rhs_date" + seq.toString() }),
						" ",
						Builder.node("input", { type: "button", id: "form_filter_cond_rhs_datebutton" + seq.toString(), value: filter_form_str_edit })
					]);
				window.setTimeout(function() { Calendar.setup({inputField: "form_filter_cond_rhs_date" + seq.toString(), ifFormat: '%Y/%m/%d', button: "form_filter_cond_rhs_datebutton" + seq.toString(), showsTime: false}); }, 100);
				return x;
			case "*ctime":
				var x =
					Builder.node("div", [
						Builder.node("select", { id: "form_filter_cond_rhs_hour" + seq.toString(), onchange: sprintf("window.setTimeout('filter_form_settime(\"%s\")', 100)", seq) }, [
							Builder.node("option", { value: "00" }, "00"),
							Builder.node("option", { value: "01" }, "01"),
							Builder.node("option", { value: "02" }, "02"),
							Builder.node("option", { value: "03" }, "03"),
							Builder.node("option", { value: "04" }, "04"),
							Builder.node("option", { value: "05" }, "05"),
							Builder.node("option", { value: "06" }, "06"),
							Builder.node("option", { value: "07" }, "07"),
							Builder.node("option", { value: "08" }, "08"),
							Builder.node("option", { value: "09" }, "09"),
							Builder.node("option", { value: "10" }, "10"),
							Builder.node("option", { value: "11" }, "11"),
							Builder.node("option", { value: "12" }, "12"),
							Builder.node("option", { value: "13" }, "13"),
							Builder.node("option", { value: "14" }, "14"),
							Builder.node("option", { value: "15" }, "15"),
							Builder.node("option", { value: "16" }, "16"),
							Builder.node("option", { value: "17" }, "17"),
							Builder.node("option", { value: "18" }, "18"),
							Builder.node("option", { value: "19" }, "19"),
							Builder.node("option", { value: "20" }, "20"),
							Builder.node("option", { value: "21" }, "21"),
							Builder.node("option", { value: "22" }, "22"),
							Builder.node("option", { value: "23" }, "23"),
						]),
						" : ",
						Builder.node("select", { id: "form_filter_cond_rhs_min" + seq.toString(), onchange: sprintf("window.setTimeout('filter_form_settime(\"%s\")', 100)", seq) }, [
							Builder.node("option", { value: "00" }, "00"),
							Builder.node("option", { value: "01" }, "01"),
							Builder.node("option", { value: "02" }, "02"),
							Builder.node("option", { value: "03" }, "03"),
							Builder.node("option", { value: "04" }, "04"),
							Builder.node("option", { value: "05" }, "05"),
							Builder.node("option", { value: "06" }, "06"),
							Builder.node("option", { value: "07" }, "07"),
							Builder.node("option", { value: "08" }, "08"),
							Builder.node("option", { value: "09" }, "09"),
							Builder.node("option", { value: "10" }, "10"),
							Builder.node("option", { value: "11" }, "11"),
							Builder.node("option", { value: "12" }, "12"),
							Builder.node("option", { value: "13" }, "13"),
							Builder.node("option", { value: "14" }, "14"),
							Builder.node("option", { value: "15" }, "15"),
							Builder.node("option", { value: "16" }, "16"),
							Builder.node("option", { value: "17" }, "17"),
							Builder.node("option", { value: "18" }, "18"),
							Builder.node("option", { value: "19" }, "19"),
							Builder.node("option", { value: "20" }, "20"),
							Builder.node("option", { value: "21" }, "21"),
							Builder.node("option", { value: "22" }, "22"),
							Builder.node("option", { value: "23" }, "23"),
							Builder.node("option", { value: "24" }, "24"),
							Builder.node("option", { value: "25" }, "25"),
							Builder.node("option", { value: "26" }, "26"),
							Builder.node("option", { value: "27" }, "27"),
							Builder.node("option", { value: "28" }, "28"),
							Builder.node("option", { value: "29" }, "29"),
							Builder.node("option", { value: "30" }, "30"),
							Builder.node("option", { value: "31" }, "31"),
							Builder.node("option", { value: "32" }, "32"),
							Builder.node("option", { value: "33" }, "33"),
							Builder.node("option", { value: "34" }, "34"),
							Builder.node("option", { value: "35" }, "35"),
							Builder.node("option", { value: "36" }, "36"),
							Builder.node("option", { value: "37" }, "37"),
							Builder.node("option", { value: "38" }, "38"),
							Builder.node("option", { value: "39" }, "39"),
							Builder.node("option", { value: "40" }, "40"),
							Builder.node("option", { value: "41" }, "41"),
							Builder.node("option", { value: "42" }, "42"),
							Builder.node("option", { value: "43" }, "43"),
							Builder.node("option", { value: "44" }, "44"),
							Builder.node("option", { value: "45" }, "45"),
							Builder.node("option", { value: "46" }, "46"),
							Builder.node("option", { value: "47" }, "47"),
							Builder.node("option", { value: "48" }, "48"),
							Builder.node("option", { value: "49" }, "49"),
							Builder.node("option", { value: "50" }, "50"),
							Builder.node("option", { value: "51" }, "51"),
							Builder.node("option", { value: "52" }, "52"),
							Builder.node("option", { value: "53" }, "53"),
							Builder.node("option", { value: "54" }, "54"),
							Builder.node("option", { value: "55" }, "55"),
							Builder.node("option", { value: "56" }, "56"),
							Builder.node("option", { value: "57" }, "57"),
							Builder.node("option", { value: "58" }, "58"),
							Builder.node("option", { value: "59" }, "59"),
						]),
						Builder.node("input", { type: "hidden", id: "form_filter_cond_rhs_time" + seq.toString(), className: "form_filter_cond_rhs_item", name: "filter_group_cond_rhs", onchange: sprintf("filter_form_reflecttime('%s', this.value)", seq) }, "")
					]);

				x.childNodes[0].value = "00";
				x.childNodes[2].value = "00";

				if (def.match(/\d{2}:\d{2}/)) {
					def = def.split(":");
					x.childNodes[0].value = def[0];
					x.childNodes[2].value = def[1];
				}
				return x;
			default:
				var x;
				if (name.match(/[0-9]+/) && typeof customfields[name] != "undefined" && customfields[name].type == 3) {
					// it's a custom field...check if it's a checkbox.
					x = Builder.node("div", [
							Builder.node("select", { className: "form_filter_cond_rhs_item", name: "filter_group_cond_rhs" }, [
								Builder.node("option", { value: "checked" }, filter_form_str_checked),
								Builder.node("option", { value: "unchecked" }, filter_form_str_unchecked),
							])
						]);

					x.childNodes[0].value = "checked";
				} else {
					x = Builder.node("div", [
							Builder.node("input", { className: "form_filter_cond_rhs_item", name: "filter_group_cond_rhs" }, "")
						]);
				}
				return x;
		}
	} else {
		return filter_form_genrhs_action(name, type, op, seq, def);
	}
}

function filter_form_settime(seq) {
	var hr = $("form_filter_cond_rhs_hour" + seq.toString());
	var mn = $("form_filter_cond_rhs_min"  + seq.toString());
	var tm = $("form_filter_cond_rhs_time" + seq.toString());

	tm.value = sprintf("%02s:%02s:00", hr.value, mn.value);
}

function filter_form_reflecttime(seq, val) {
	var hr = $("form_filter_cond_rhs_hour" + seq.toString());
	var mn = $("form_filter_cond_rhs_min"  + seq.toString());

	var ary = val.split(":");
	hr.value = ary[0];
	mn.value = ary[1];
}

function filter_form_genrhs_action(name, type, op, seq, def) {
	switch (name) {
		case "linkclicked":
		case "linknotclicked":
			// We need to call out and find which links we need to show.  Op in
			// this case will be the campaign id, and seq is the sequential number
			// for the rhs dropdown to place them into.
			adesk_ajax_call_cb("awebdeskapi.php", "filter.filter_links", adesk_ajax_cb(filter_links_cb), op, seq, def);
			return Builder.node("input", { className: "form_filter_cond_rhs_item", value: "", type: "hidden" });

		case "social":
			return Builder.node("select", { className: "form_filter_cond_rhs_item", name: "filter_group_cond_rhs" }, [
				Builder.node("option", { value: "facebook" }, filter_form_str_facebook),
				Builder.node("option", { value: "twitter" }, filter_form_str_twitter),
				Builder.node("option", { value: "digg" }, filter_form_str_digg),
				Builder.node("option", { value: "delicious" }, filter_form_str_delicious),
				Builder.node("option", { value: "greader" }, filter_form_str_greader),
				Builder.node("option", { value: "reddit" }, filter_form_str_reddit),
				Builder.node("option", { value: "stumbleupon" }, filter_form_str_stumbleupon)
			]);

		default:
			return Builder.node("input", { type: "hidden", className: "form_filter_cond_rhs_item", value: "", type: "hidden", name: "filter_group_cond_rhs" });
	}
}

function filter_oplists_cb(ary) {
	var firstvalue = "";
	if (ary.lists.length > 0) {
		var sel = $("form_filter_cond_op" + ary.seq.toString());
		if(!sel) return;
		adesk_dom_remove_children(sel);
		for (var i = 0; i < ary.lists.length; i++) {
			if (firstvalue == "")
				firstvalue = ary.lists[i].id;
			sel.appendChild(
				Builder.node("option", { value: ary.lists[i].id }, ary.lists[i].name)
			);
		}

		sel.value = firstvalue;

		if (ary.def != "")
			sel.value = ary.def;
	}
}

function filter_lists_cb(ary) {
	var id = "form_filter_cond_rhs" + ary.seq.toString();
	if (ary.lists.length > 0) {
		var sel = Builder.node("select", { className: "form_filter_cond_rhs_item", name: "filter_group_cond_rhs", style: "width: 200px" });
		sel.appendChild(Builder.node("option", { value: "0" }, filter_form_str_anylist));
		for (var i = 0; i < ary.lists.length; i++) {
			sel.appendChild(
				Builder.node("option", { value: ary.lists[i].id }, ary.lists[i].name)
			);
		}

		sel.value = "0";

		if (ary.def != "")
			sel.value = ary.def;
		adesk_dom_remove_children($(id));
		$(id).appendChild(sel);
	}
}

function filter_links_cb(ary) {
	var id = "form_filter_cond_rhs" + ary.seq.toString();
	if (ary.links.length > 0) {
		var sel = Builder.node("select", { className: "form_filter_cond_rhs_item", name: "filter_group_cond_rhs", style: "width: 200px" });
		sel.appendChild(Builder.node("option", { value: "0" }, filter_form_str_anylink));
		for (var i = 0; i < ary.links.length; i++) {
			sel.appendChild(
				Builder.node("option", { value: ary.links[i].id }, ary.links[i].link)
			);
		}

		sel.value = "0";

		if (ary.def != "")
			sel.value = ary.def;
		adesk_dom_remove_children($(id));
		$(id).appendChild(sel);
	}
}

{/literal}
