var campaign_list_str_confirm_enable = '{"Are you sure you wish to re-enable this Scheduled Campaign?"|alang|js}';
var campaign_list_str_confirm_disable = '{"Are you sure you wish to disable this Scheduled Campaign?"|alang|js}';
var campaign_list_str_confirm_run = '{"Are you sure you wish to run this Campaign?"|alang|js}';
var campaign_list_str_confirm_stop = '{"Are you sure you wish to stop this Campaign?  Once you stop a campaign you CANNOT resume it."|alang|js}';
var campaign_list_str_confirm_pause = '{"Are you sure you wish to pause this Campaign?"|alang|js}';
var campaign_list_str_confirm_resume = '{"Are you sure you wish to resume this Campaign?"|alang|js}';
var campaign_list_str_confirm_send = '{"Are you sure you wish to send this Campaign now?"|alang|js}';
var campaign_list_str_alert_scheduled = '{"This will turn off your scheduled campaign. You will have the option to resume/enable the scheduled campaign after doing this."|alang|js}';
var campaign_list_str_view_reports = '{"View Reports"|alang|js}';
var campaign_list_str_export_none = '{"You have selected no campaigns to export. Please select campaign(s) first."|alang|js}';
var campaign_list_str_completed = '{"Completed: "|alang|js}';
var campaign_list_str_stage = '{"Stage: "|alang|js}';
var campaign_list_str_stage_completed = '{"Campaign Completed!"|alang|js}';
var campaign_list_str_stage_cleanup = '{"Almost Done (Cleaning Up)"|alang|js}';
var campaign_list_str_stage_send = '{"Sending Emails"|alang|js}';
var campaign_list_str_stage_transfer = '{"Preparing Subscribers"|alang|js}';
var campaign_list_str_stage_winner = '{"Waiting for winner to continue sending"|alang|js}';
var campaign_list_str_recur = '{"This campaign is set to recur every %s."|alang|js}';
var campaign_list_str_recur_rss = '{"This campaign will check for new RSS feeds every %s."|alang|js}';
var campaign_list_str_sent_unsub = '{"This campaign will be sent %s after a person unsubscribes."|alang|js}';
var campaign_list_str_sent_sub = '{"This campaign will be sent %s after a person subscribes."|alang|js}';
var campaign_list_str_sent_match = '{"This campaign will be sent when the field %s matches the current date %s."|alang|js}';
var campaign_list_str_sendnow = '{"Send Now"|alang|js}';
var campaign_list_str_approvalnotice = '{"This campaign is currently awaiting approval. Some campaigns (especially for new accounts) may require approval. This allows us to ensure the best deliverability for all of our users. Approvals are typically handled within an hour."|alang|js}';

{jsvar var=$recur_intervals name=campaign_list_str_intervals}

var campaign_table = new ACTable();
var campaign_list_sort = "01D";
var campaign_list_offset = "0";
var campaign_list_filter = {jsvar var=$filterid};
var campaign_list_sort_discerned = false;

var campaign_reports = {jsvar var=$reportsOnly};

var canSend = {jsvar var=$canSendCampaign};

{literal}

campaign_table.setcol(0, function(row, td) {
	td.vAlign = 'top';
	return Builder.node("input", { type: "checkbox", name: "multi[]", value: row.id, onclick: "adesk_form_check_selection_none(this, $('acSelectAllCheckbox'), $('selectXPageAllBox'))" });
});

campaign_table.setcol(1, function(row, td) {
	td.vAlign = 'top';
	var mailing = adesk_array_has(['single', 'recurring', 'split', 'deskrss', 'text'], row.type);
	var newurl = sprintf("desk.php?action=campaign_new&campaignid=%d", row.id);
	//var useurl = sprintf("desk.php?action=campaign_use&campaignid=%d", row.id);
	var repurl = sprintf("desk.php?action=report_campaign&id=%d#general-01-0-0-0", row.id);
	var cont;

	switch (row.laststep) {
		case "type":
		default:
			cont = Builder.node("a", { href: sprintf("desk.php?action=campaign_new&id=%s", row.id), style: 'font-weight:bold;' }, jsOptionContinue);
			break;

		case "list":
			cont = Builder.node("a", { href: sprintf("desk.php?action=campaign_new_list&id=%s", row.id), style: 'font-weight:bold;' }, jsOptionContinue);
			break;

		case "template":
			cont = Builder.node("a", { href: sprintf("desk.php?action=campaign_new_template&id=%s", row.id), style: 'font-weight:bold;' }, jsOptionContinue);
			break;

		case "message":
			cont = Builder.node("a", { href: sprintf("desk.php?action=campaign_new_message&id=%s", row.id), style: 'font-weight:bold;' }, jsOptionContinue);
			break;

		case "text":
			cont = Builder.node("a", { href: sprintf("desk.php?action=campaign_new_text&id=%s", row.id), style: 'font-weight:bold;' }, jsOptionContinue);
			break;

		case "splitmessage":
			cont = Builder.node("a", { href: sprintf("desk.php?action=campaign_new_splitmessage&id=%s", row.id), style: 'font-weight:bold;' }, jsOptionContinue);
			break;

		case "splittext":
			cont = Builder.node("a", { href: sprintf("desk.php?action=campaign_new_splittext&id=%s", row.id), style: 'font-weight:bold;' }, jsOptionContinue);
			break;

		case "summary":
			cont = Builder.node("a", { href: sprintf("desk.php?action=campaign_new_summary&id=%s", row.id), style: 'font-weight:bold;' }, jsOptionContinue);
			break;

		case "finish":
			cont = Builder.node("a", { href: sprintf("desk.php?action=campaign_new_finish&id=%s", row.id), style: 'font-weight:bold;' }, jsOptionContinue);
			break;
	}
	var rsme = Builder.node("a", { href: '#', onclick: sprintf("return campaign_resume(%d);", row.id), id: 'campaign_list_resume_' + row.id }, jsOptionResume);
	var paus = Builder.node("a", { href: '#', onclick: sprintf("return campaign_pause(%d);", row.id), id: 'campaign_list_pause_' + row.id }, jsOptionPause);
	var stop = Builder.node("a", { href: '#', onclick: sprintf("return campaign_stop(%d);", row.id), id: 'campaign_list_stop_' + row.id }, jsOptionStop);
	var send = Builder.node("a", { href: '#', onclick: sprintf("return campaign_now(%d);", row.id), id: 'campaign_list_send_' + row.id }, campaign_list_str_sendnow);
	var enbl = Builder.node("a", { href: '#', onclick: sprintf("return campaign_enable(%d);", row.id), id: 'campaign_list_enable_' + row.id }, jsOptionEnable);
	var dsbl = Builder.node("a", { href: '#', onclick: sprintf("alert(campaign_list_str_alert_scheduled);return campaign_disable(%d);", row.id), id: 'campaign_list_disable_' + row.id }, jsOptionDisable);
	if ( adesk_array_has([1, 6], row.status) && adesk_array_has(['responder', 'reminder'], row.type) ) {
		var ruse = Builder.node("a", { href: newurl + '&use=1' }, jsOptionResendUse);
	//} else if ( row.status == 1 ) {
		//var ruse = Builder.node("a", { href: '#', onclick: sprintf("return campaign_edit_open(%d);", row.id) }, jsOptionResendUse);
	} else {
		var ruse = Builder.node("a", { href: '#', onclick: sprintf("if (%d) $('resend_filter').show(); else $('resend_filter').hide(); return campaign_reuse_open(%d);", row.canresend, row.id) }, jsOptionResendUse);
	}
	var view = Builder.node("a", { href: repurl }, ( campaign_reports ? campaign_list_str_view_reports : jsOptionReports ));
	var edit = Builder.node("a", { href: sprintf('desk.php?action=campaign_new&id=%s', row.id) }, jsOptionEdit);
	var dele = Builder.node("a", { href: sprintf("#delete-%d", row.id) }, jsOptionDelete);

	var ary = [];

	if ( !campaign_reports ) {

		// CONTINUE
		if ( row.status == 0 ) {
			if (adesk_js_admin.pg_message_edit) {
				ary.push(cont);
				ary.push(" ");
			}
		}

		// ENABLE
		if ( row.status == 6 ) {
			if (adesk_js_admin.pg_message_send) {
				ary.push(enbl);
				ary.push(" ");
			}
		}

		// DISABLE
		if ( row.status == 1 ) {
			if (adesk_js_admin.pg_message_send) {
				ary.push(dsbl);
				ary.push(" ");
			}
		}

		// EDIT
		if ( adesk_array_has([1, 3, 6], row.status) ) {
			if ( adesk_js_admin.pg_message_edit ) {
				ary.push(edit);
				ary.push(" ");
			}
		}

		// RESUME
		//if ( adesk_array_has([3, 4], row.status) ) {
		if ( row.status == 3 ) {
			if (adesk_js_admin.pg_message_send) {
				ary.push(rsme);
				ary.push(" ");
			}
		}

		// PAUSE
		if ( row.status == 2 ) {
			if (adesk_js_admin.pg_message_send) {
				ary.push(paus);
				ary.push(" ");
			}
		}

		// STOP
		if ( adesk_array_has([2, 3], row.status) ) {
			if (adesk_js_admin.pg_message_send) {
				ary.push(stop);
				ary.push(" ");
			}
		}

		// SEND
		if ( row.status == 1 ) {
			if ( adesk_array_has(['single', 'split', 'recurring', 'deskrss', 'text'], row.type) ) {
				if (adesk_js_admin.pg_message_send) {
					ary.push(send);
					ary.push(" ");
				}
			}
		}

		// REUSE
		//if ( adesk_array_has([1, 4, 5], row.status) ) {
		//if ( row.status != 0 && ruse ) {
		if ( !adesk_array_has([0, 7], row.status) && !( row.status == 1 && !adesk_array_has(['responder', 'reminder'], row.type) ) ) {
			// these were not sent yet
			if ( adesk_js_admin.pg_message_add || canSend ) {
				ary.push(ruse);
				ary.push(" ");
			}
		}

	}

	// REPORTS
	//if ( !adesk_array_has([0, 7], row.status) && !( row.status == 1 && !adesk_array_has(['responder', 'reminder'], row.type) ) ) {
	if ( row.status != 0 && !( adesk_array_has([1, 7], row.status) && !adesk_array_has(['responder', 'reminder'], row.type) ) ) {
		if (adesk_js_admin.pg_reports_campaign) {
			ary.push(view);
			ary.push(" ");
		}
	}

	if ( !campaign_reports ) {
		// DELETE
		if (adesk_js_admin.pg_message_delete) {
			ary.push(dele);
		}

		// add hiddens
		ary.push(Builder.node('input', { type: 'hidden', id: 'campaign_list_row_' + row.id, name: 'rowid', value: row.id }));
		ary.push(Builder.node('input', { type: 'hidden', id: 'campaign_list_row_type_' + row.status, name: 'rowtype', value: row.type }));
		ary.push(Builder.node('input', { type: 'hidden', id: 'campaign_list_row_status_' + row.status, name: 'rowstatus', value: row.status }));
		ary.push(Builder.node('input', { type: 'hidden', id: 'campaign_list_row_process_' + row.processid, name: 'rowprocess', value: row.processid }));
	}

	return Builder.node("div", { className: "adesk_table_row_options" }, ary);
});

campaign_table.setcol(2, function(row, td) {
	td.vAlign = 'top';
	var txt = Builder._text(campaign_type_strings[row.type]);
	var msg = '';
	var offset = '';
	if (row.recurring == "")
		row.recurring = "day1";

	//if ( adesk_array_has(row.type, ['single', 'split']) ) {
	if ( row.type == 'single' || row.type == 'split' || row.type == 'text' ) {
		return txt;
	} else if ( row.type == 'recurring' ) {
		offset = campaign_list_str_intervals[row.recurring].toLowerCase();
		msg = sprintf(campaign_list_str_recur, offset);
	} else if ( row.type == 'responder' ) {
		offset = ( row.responder_offset > 0 ? row.responder_offset + ' hour(s)' : '' );
		if ( row.responder_type == 'unsubscribe' ) {
			msg = sprintf(campaign_list_str_sent_unsub, offset);
		} else {
			msg = sprintf(campaign_list_str_sent_sub, offset);
		}
	} else if ( row.type == 'reminder' ) {
		var field = row.reminder_field;
		var field_name = row.reminder_field_name;
		offset = ( row.reminder_offset > 0 ? row.reminder_offset_sign + row.reminder_offset + row.reminder_offset_type : '' );
		msg = sprintf(campaign_list_str_sent_match, field_name, offset);
	} else if ( row.type == 'deskrss' ) {
		offset = campaign_list_str_intervals[row.deskrss_interval].toLowerCase();
		msg = sprintf(campaign_list_str_recur_rss, offset);
	}
	return Builder.node(
		'div',
		[
			Builder.node(
				'img',
				{
					src: 'images/information-small.gif',
					onmouseout: "adesk_tooltip_hide();",
					onmouseover: "adesk_tooltip_show('" + adesk_b64_encode(msg) + "', 150, '', true);",
					style: "float:left; padding-top:1px; padding-right:3px;"
				}
			),
			txt
		]
	);
})

campaign_table.setcol(3, function(row, td) {
	td.id = 'campaign_list_status_' + row.id;
	td.vAlign = 'top';
	var nodes = [];
	var txt = Builder._text(campaign_status_strings[row.status]);
	var msg = '';
	if ( row.status == 7 ) { // approval info
		msg = campaign_list_str_approvalnotice;
	} else if ( row.status == 6 ) { // disabled info
		//msg = 'This is a disabled campaign. Enable it to dispatch it.';
	}
	if ( msg != '' ) {
		nodes.push(
			Builder.node(
				'img',
				{
					src: 'images/information-small.gif',
					onmouseout: "adesk_tooltip_hide();",
					onmouseover: "adesk_tooltip_show('" + adesk_b64_encode(msg) + "', 150, '', true);",
					style: "float:left; padding-top:1px; padding-right:3px;"
				}
			)
		);
	}
	nodes.push(txt);
	return Builder.node('div', nodes);
});

campaign_table.setcol(4, function(row, td) {
	td.vAlign = 'top';
	var nameNode = Builder._text(row.name);
	if ( !adesk_array_has([2, 3], row.status) /*|| row.processid == 0*/ ) return nameNode;
	if ( row.status == 2 && row.infuture ) return nameNode;
	var progressBar = Builder.node('div', { id: 'campaign_list_progress' + row.id, className: 'adesk_progressbar' });
	/*if ( row.mail_cleanup == 1 ) {
		var stage = campaign_list_str_stage_completed;
	} else*/ if ( row.mail_send == 1 ) {
		var stage = campaign_list_str_stage_cleanup;
	} else if ( row.mail_transfer == 1 ) {
		if ( row.type == 'split' && row.split_type != 'even' && row.split_winner_awaiting == 1 ) {
			var stage = campaign_list_str_stage_winner;
		} else {
			var stage = campaign_list_str_stage_send;
		}
	} else {
		var stage = campaign_list_str_stage_transfer;
	}
	var rowNode = Builder.node(
		'div',
		[
			Builder.node('div', { className: 'campaignlisttitlebox' }, [ nameNode ]),
			Builder.node(
				'div',
				{ id: 'campaign_list_descript_' + row.id, className: 'campaignlistdescriptbox' },
				[
					Builder._text(campaign_list_str_completed),
					Builder.node('span', { id: 'campaign_list_stats_' + row.id }, [ Builder._text(row.send_amt + ' / ' + row.total_amt) ]),
					Builder.node('br'),
					Builder._text(campaign_list_str_stage),
					Builder.node('span', { id: 'campaign_list_stage_' + row.id }, [ Builder._text(stage) ])
				]
			)
		]
	);
	return Builder.node(
		'div',
		[
			Builder.node('div', { className: 'campaignlistprogressbox' }, [ progressBar ]),
			rowNode
		]
	);
});

campaign_table.setcol(5, function(row, td) {
	td.vAlign = 'top';
	var d = ( row.status != 0 && row.sdate && row.sdate != '0000-00-00 00:00:00' ? sql2date(row.sdate).format(datetimeformat) : jsNotAvailable );
	return Builder._text(d);
});

campaign_table.setcol(6, function(row, td) {
	td.id = 'campaign_list_ldate_' + row.id;
	td.vAlign = 'top';
	var d = ( row.ldate && row.ldate != '0000-00-00 00:00:00' && row.ldate != '9999-01-01 00:00:00' ? sql2date(row.ldate).format(datetimeformat) : jsNotAvailable );
	return Builder._text(d);
});

function campaign_list_anchor() {
	return sprintf("list-%s-%s-%s", campaign_list_sort, campaign_list_offset, campaign_list_filter);
}

function campaign_list_tabelize(rows, offset) {
	if (rows.length < 1) {
		/*
		if (!campaign_list_filter || campaign_list_filter == 0) {
			adesk_ui_api_callback();
			window.location = 'desk.php?action=campaign_new';
			return;
		}
		*/
		// We may have some trs left if we just deleted the last row.
		adesk_dom_remove_children($("list_table"));

		$("list_noresults").className = "adesk_block";
		if ($("list_delete_button") !== null)
			$("list_delete_button").className = "adesk_hidden";
		$("loadingBar").className = "adesk_hidden";
		adesk_ui_api_callback();
		return;
	}
	$("list_noresults").className = "adesk_hidden";
	if ($("list_delete_button") !== null)
		$("list_delete_button").className = "adesk_inline";
	// first remove old timers (progress bars)
	for ( var i in adesk_progress_bars ) {
		adesk_progressbar_unregister(i);
	}
	adesk_paginator_tabelize(campaign_table, "list_table", rows, offset);
	$("campaign_list_count").innerHTML = " (" + adesk_number_format(paginators[1].total, decimalDelim, commaDelim) + ")";
	$("campaign_list_count").className = "adesk_inline";
	if ( $('selectXPageAllBox') ) {
		var spans = $('selectXPageAllBox').getElementsByTagName('span');
		if ( spans.length > 2 ) {
			spans[2].innerHTML = adesk_number_format(paginators[1].total, decimalDelim, commaDelim);
		}
	}
	for ( i = 0; i < rows.length; i++ ) {
		if ( !adesk_array_has([2, 3], rows[i]['status']) ) continue;
		//if ( rows[i]['processid'] != 0 ) continue;
		var id = rows[i]['processid'];
		var divid = 'campaign_list_progress' + rows[i]['id'];
		var percentage = rows[i]['send_amt'] / rows[i]['total_amt'];
		var remaining  = rows[i]['total_amt'] - rows[i]['send_amt'];
		adesk_progressbar_register(
			divid,
			id,
			percentage * 100,
			( remaining > 0 && id != 0 ? 10 : 0 ),
			true,
			campaign_list_progress_ihook
		);
		if ( remaining == 0 ) {
			adesk_progressbar_unregister(divid);
		}
	}
	$("loadingBar").className = "adesk_hidden";
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function campaign_list_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (campaign_list_filter > 0)
		$("list_clear").style.display = "inline";
	else
		$("list_clear").style.display = "none";

	campaign_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(campaign_list_anchor());
	$("loadingBar").className = "adesk_block";

	if (!campaign_reports)
		adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, campaign_list_sort, campaign_list_offset, this.limit, campaign_list_filter);
	else
		adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, campaign_list_sort, campaign_list_offset, this.limit, campaign_list_filter, false, false, true);

	$("list").className = "adesk_block";
}

function campaign_list_clear() {
	campaign_list_sort = "01D";
	campaign_list_offset = "0";
	campaign_list_filter = "0";
	campaign_listfilter = null;
	$("JSListManager").value = 0;
	$("JSTypeManager").value = "";
	$("JSStatusManager").value = "";
	$("list_search").value = "";
	list_filters_update(0, 0, true);
	campaign_search_defaults();
	adesk_ui_anchor_set(campaign_list_anchor());
}

function campaign_list_search() {
	var post = adesk_form_post($("list"));
	campaign_listfilter = post.listid;
	list_filters_update(0, post.listid, false);
	adesk_ajax_post_cb("awebdeskapi.php", "campaign.campaign_filter_post", campaign_list_search_cb, post);
}

function campaign_list_search_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	campaign_list_filter = ary.filterid;
	adesk_ui_anchor_set(campaign_list_anchor());
}

function campaign_list_chsort(newSortId) {
	var oldSortId = ( campaign_list_sort.match(/D$/) ? campaign_list_sort.substr(0, 2) : campaign_list_sort );
	var oldSortObj = $('list_sorter' + oldSortId);
	var sortObj = $('list_sorter' + newSortId);
	// if sort column didn't change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( campaign_list_sort.match(/D$/) ) {
			// was DESC
			newSortId = campaign_list_sort.substr(0, 2);
			sortObj.className = 'adesk_sort_asc';
		} else {
			// was ASC
			newSortId = campaign_list_sort + 'D';
			sortObj.className = 'adesk_sort_desc';
		}
	} else {
		// remove old campaign_list_sort
		if ( oldSortObj ) oldSortObj.className = 'adesk_sort_other';
		// set sort field
		sortObj.className = 'adesk_sort_asc';
	}
	campaign_list_sort = newSortId;
	adesk_ui_api_call(jsSorting);
	adesk_ui_anchor_set(campaign_list_anchor());
	return false;
}

function campaign_list_discern_sortclass() {
	if (campaign_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("list_sorter%s", campaign_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (campaign_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	campaign_list_sort_discerned = true;
}

function campaign_list_progress_ihook(ary) {
	// rebuild this list
	//paginators[1].paginate(campaign_list_offset);
	//return;
	/*
	var rel = $('campaign_list_view_stats' + ary.id);
	if ( rel ) rel.innerHTML = ary.completed + ' / ' + ary.total;
	var rel = $('campaign_list_view_status' + ary.id);
	if ( rel ) rel.innerHTML = process_status(ary);//campaign_status_strings[row.status]
	*/
	var rel = $('campaign_list_row_process_' + ary.id);
	if ( !rel ) return;
	// extract vars
	var vars = adesk_form_post(rel.parentNode);
	// set the progress text
	if ( $('campaign_list_stats_' + vars.rowid) ) {
		$('campaign_list_stats_' + vars.rowid).innerHTML = ary.completed + ' / ' + ary.total;
	}
	if ( ary.completed < ary.total ) {
		// still running
		//
	} else {
		// completed, switch statuses
		//if ( !adesk_array_has([2, 3], rows[i]['status']) ) continue;
		var statusString = campaign_status_strings[ ( adesk_array_has(['responder', 'reminder'], vars.rowtype) ? 1 : 5 ) ];
		if ( $('campaign_list_status_' + vars.rowid) ) {
			$('campaign_list_status_' + vars.rowid).innerHTML = statusString;
		}
		if ( $('campaign_list_stage_' + vars.rowid) ) {
			$('campaign_list_stage_' + vars.rowid).innerHTML = statusString;
		}
		// hide resume/pause/stop buttons
		if ( $('campaign_list_pause_' + vars.rowid) ) $('campaign_list_pause_' + vars.rowid).className = 'adesk_hidden';
		if ( $('campaign_list_resume_' + vars.rowid) ) $('campaign_list_resume_' + vars.rowid).className = 'adesk_hidden';
		if ( $('campaign_list_stop_' + vars.rowid) ) $('campaign_list_stop_' + vars.rowid).className = 'adesk_hidden';
	}
	if ( $('campaign_list_ldate_' + vars.rowid) && ary.ldate && ary.ldate != '0000-00-00 00:00:00' ) {
		$('campaign_list_ldate_' + vars.rowid).innerHTML = sql2date(ary.ldate).format(datetimeformat);
	}
}

function campaign_reuse_open(id) {
	adesk_dom_toggle_display('list_reuse', 'block');
	$('campaign_use_id').value = id;
	$('campaign_use_reuse').checked = true;
	return false;
}

function campaign_reuse() {
	adesk_ui_api_call(jsLoading);
	var form = adesk_form_post('list_reuse');
	// redirect
	var action = ( form.action == 'reuse' ? 'campaign_new' : 'campaign_use' );
	var go2 = 'desk.php?action=' + action + '&copyid=' + form.id;
	go2 += ( form.action == 'reuse' ? '' : '&filter=' + form.action );
	//if ( form.action != 'reuse' ) go2 += '&filter=' + form.action;
	window.location.href = go2;
}
/*
function campaign_reuse_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();
	alert('2do ajax callback');
}
*/

function campaign_edit_open(id) {
	$('campaign_edit_id').value = id;
	$('campaign_edit_campaign').checked = true;
	$('campaign_edit_split_box').className = 'adesk_hidden';
	adesk_ajax_call_cb("awebdeskapi.php", "campaign.campaign_messages", campaign_edit_open_cb, id, 1);
	return false;
}

function campaign_edit_open_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();
	var rel = $('campaign_edit_split_field');
	adesk_dom_remove_children(rel);
	if ( ary.row && ary.row.length && ary.row.length > 0 ) {
		for ( var i = 0; i < ary.row.length; i++ ) {
			var m = ary.row[i];
			rel.appendChild(Builder.node('option', { value: m.id }, [ Builder._text(strip_tags(m.subject, true)) ]));
		}
		rel.selectedIndex = 0;
		/*
		if ( ary.row.length > 1 ) {
			$('campaign_edit_split_box').className = 'adesk_table_rowgroup';
		}
		*/
	}
	// show the edit modal
	adesk_dom_toggle_display('list_edit', 'block');
}

function campaign_edit() {
	adesk_ui_api_call(jsLoading);
	var form = adesk_form_post('list_edit');
	// redirect
	if ( form.action == 'campaign' ) {
		var go2 = 'desk.php?action=campaign_new&campaignid=' + form.id;
	} else if ( form.action == 'message' ) {
		var go2 = 'desk.php?action=message#form-' + form.messageid;
	}
	window.location.href = go2;
}

function campaign_resume(id) {
	return campaign_list_status(id, 2);
}

function campaign_pause(id) {
	return campaign_list_status(id, 3);
}

function campaign_stop(id) {
	return campaign_list_status(id, 4);
}

function campaign_run(id) {
	return campaign_list_status(id, 1);
}

function campaign_enable(id) {
	return campaign_list_status(id, 1, true);
}

function campaign_disable(id) {
	return campaign_list_status(id, 6);
}

function campaign_list_status(id, status, enable) {
	if ( status == 6 ) {
		if ( !confirm(campaign_list_str_confirm_disable) ) return false;
		adesk_ui_api_call(jsDisabling);
	} else if ( status == 4 ) {
		if ( !confirm(campaign_list_str_confirm_stop) ) return false;
		adesk_ui_api_call(jsStopping);
	} else if ( status == 3 ) {
		if ( !confirm(campaign_list_str_confirm_pause) ) return false;
		adesk_ui_api_call(jsPausing);
	} else if ( status == 2 ) {
		if ( !confirm(campaign_list_str_confirm_resume) ) return false;
		adesk_ui_api_call(jsResuming);
	} else {
		if ( !enable ) {
			if ( !confirm(campaign_list_str_confirm_run) ) return false;
		} else {
			if ( !confirm(campaign_list_str_confirm_enable) ) return false;
		}
		adesk_ui_api_call(jsStarting);
	}
	//var action = ( status == 2 ? "campaign.campaign_run" : "campaign.campaign_status" );
	adesk_ajax_call_cb("awebdeskapi.php", /*action*/"campaign.campaign_status", campaign_list_trigger_cb, id, status);
	return false;
}

function campaign_list_trigger_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		// rebuild this list
		paginators[1].paginate(campaign_list_offset);
		// start the process pickup tool just in case it should be picked up
		adesk_ajax_call_url('process.php', null, null);
	} else {
		adesk_error_show(ary.message);
	}
}

function campaign_now(id) {
	if ( !confirm(campaign_list_str_confirm_send) ) return false;
	adesk_ui_api_call(jsStarting);
	adesk_ajax_call_cb("awebdeskapi.php", "campaign.campaign_now", campaign_now_cb, id);
	return false;
}

function campaign_now_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		// rebuild this list
		paginators[1].paginate(campaign_list_offset);
		// start the process pickup tool just in case it should be picked up
		adesk_ajax_call_url('process.php', null, null);
	} else {
		adesk_error_show(ary.message);
		// rebuild this list
		paginators[1].paginate(campaign_list_offset);
	}
}

function campaign_export_open() {
	var ids = adesk_form_check_selection_get($("list_table"), "multi[]");
	if ( ids.length == 0 ) {
		alert(campaign_list_str_export_none);
		return;
	}
	adesk_dom_display_block('list_export');
	adesk_form_check_selection_element_all('list_export', true);
	$('list_export_count').innerHTML = ids.length;
}

function campaign_export() {
	var ids = adesk_form_check_selection_get($("list_table"), "multi[]");
	var frm = adesk_form_post($("list_export"));
	var exporturl = 'exportreport.php?ids=' + ids.join(',') + '&reports=' + frm.reports.join(',');
	//prompt('export?', exporturl);
	window.location.href = exporturl;
	adesk_dom_display_none('list_export');
}

{/literal}
