var report_campaign_list_mode = "general";
var report_campaign_list_sort = "01";
var report_campaign_list_offset = "0";
var report_campaign_list_filter = "0";
var report_campaign_list_linkid = "0";
var report_campaign_list_hash   = '{$hash}';
var report_campaign_list_print  = '{$smarty.get.print|default:0}';
var report_campaign_message_showoverlay = false;
var report_campaign_message_showsource = false;

var report_campaign_message_str_showoverlay = '{"Show Overlay"|alang|js}';
var report_campaign_message_str_hideoverlay = '{"Hide Overlay"|alang|js}';

var report_campaign_message_str_showsource = '{"Show Source"|alang|js}';
var report_campaign_message_str_hidesource = '{"Hide Source"|alang|js}';

{literal}
function report_campaign_process(loc, hist) {
	if ( loc == '' ) {
		loc = 'general-' + report_campaign_list_sort + '-' + report_campaign_list_offset + '-' + report_campaign_list_filter + '-' + report_campaign_list_linkid;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	if ( args[0] != 'share' && args[0] != 'social' ) {
		$("general").className = "adesk_hidden";
		$("message").className = "adesk_hidden";
		$("open").className    = "adesk_hidden";
		$("link").className    = "adesk_hidden";
		$("linkinfo").className = "adesk_hidden";
		$("forward").className = "adesk_hidden";
		$("bounce").className  = "adesk_hidden";
		$("unsub").className   = "adesk_hidden";
		$("unopen").className  = "adesk_hidden";
		$("update").className  = "adesk_hidden";
		$("socialsharing").className  = "adesk_hidden";

		$("main_tab_general").className = "othertab";
		$("main_tab_message").className = "othertab";
		$("main_tab_open").className    = "othertab";
		$("main_tab_link").className    = "othertab";
		$("main_tab_forward").className = "othertab";
		$("main_tab_bounce").className  = "othertab";
		$("main_tab_unsub").className   = "othertab";
		$("main_tab_update").className  = "othertab";
		$("main_tab_socialsharing").className = "othertab";

		if ($("exportbutton"))
			$("exportbutton").style.display = "";
	}

	report_campaign_totals();

	var func = null;
	try {
		var func = eval("report_campaign_process_" + args[0]);

	} catch (e) {
		if (typeof report_campaign_process_list == "function")
			report_campaign_process_general(args);
	}
	if (typeof func == "function")
		func(args);
}

function report_campaign_process_general(args) {
	report_campaign_list_mode = "general";
	if (args.length < 2) {
		adesk_ui_anchor_set(report_campaign_list_anchor());
		return;
	}

	report_campaign_list_sort = args[1];
	report_campaign_list_offset = args[2];
	report_campaign_list_filter = args[3];
	report_campaign_list_linkid = args[4];

	if ($("exportbutton"))
		$("exportbutton").style.display = "none";

	$("general").className = "adesk_block";
	$("main_tab_general").className = "currenttab";
}

function report_campaign_process_share(args) {
	if (args.length < 1) {
		adesk_ui_anchor_set(report_campaign_list_anchor());
		return;
	}

	campaign_share_check(report_campaign_id);
}

function report_campaign_process_social(args) {
	if (args.length < 1) {
		adesk_ui_anchor_set(report_campaign_list_anchor());
		return;
	}

	campaign_social_toggle();
}

function report_campaign_process_socialsharing(args) {
	report_campaign_list_mode = "socialsharing";
	if (args.length < 1) {
		adesk_ui_anchor_set(report_campaign_list_anchor());
		return;
	}

	report_campaign_list_sort = args[1];
	report_campaign_list_offset = args[2];
	report_campaign_list_filter = args[3];
	report_campaign_list_linkid = args[4];

	// smarty var is set for this - checks for curl
	if (!socialsharing_enabled) {
		$('socialsharing_notenabled').show();
	}
	else {
		$('socialsharing_notenabled').hide();
	}

	//paginators[9].paginate(report_campaign_list_offset, 'twitter');
	$("socialsharing").className = "adesk_block";
	// hide the <table> row that says "# of people shared your campaign that are not subscribers"
	$("socialsharing_table_facebook_external").hide();
	// adjust the language of the text
	$("facebook_external_total_people").show();
	$("facebook_external_total_person").hide();
	$("facebook_external_total_are").show();
	$("facebook_external_total_is").hide();
	$("main_tab_socialsharing").className = "currenttab";

	socialsharing_totals();

	// filter drop-down
	$('socialsharing_filter_source').value = 'all';
}

function report_campaign_process_message(args) {
	report_campaign_list_mode = "message";
	if (args.length < 2) {
		adesk_ui_anchor_set(report_campaign_list_anchor());
		return;
	}

	report_campaign_list_sort = args[1];
	report_campaign_list_offset = args[2];
	report_campaign_list_filter = args[3];
	report_campaign_list_linkid = args[4];

	if ($("exportbutton"))
		$("exportbutton").style.display = "none";
	$("message").className          = "adesk_block";
	$("main_tab_message").className = "currenttab";
}

function report_campaign_list_anchor() {
	return sprintf("%s-%s-%s-%s-%s", report_campaign_list_mode, report_campaign_list_sort, report_campaign_list_offset, report_campaign_list_filter, report_campaign_list_linkid);
}

function report_campaign_process_open(args) {
	report_campaign_list_mode = "open";
	if (args.length < 2) {
		adesk_ui_anchor_set(report_campaign_list_anchor());
		return;
	}

	report_campaign_list_sort = args[1];
	report_campaign_list_offset = args[2];
	report_campaign_list_filter = args[3];
	report_campaign_list_linkid = args[4];

	if (report_campaign_list_print==1) {
		paginators[1].limit = 1000000000;
		$("paginatorBox1").style.display = "none";
	}

	paginators[1].paginate(report_campaign_list_offset);
	$("open").className = "adesk_block";
	if ($("open_opened"))
		$("open_opened").value = "opened";
	$("main_tab_open").className = "currenttab";
}

function report_campaign_process_unopen(args) {
	report_campaign_list_mode = "unopen";
	if (args.length < 2) {
		adesk_ui_anchor_set(report_campaign_list_anchor());
		return;
	}

	if (report_campaign_list_print==1) {
		paginators[5].limit = 1000000000;
		$("paginatorBox5").style.display = "none";
	}

	report_campaign_list_sort = args[1];
	report_campaign_list_offset = args[2];
	report_campaign_list_filter = args[3];
	report_campaign_list_linkid = args[4];

	paginators[5].paginate(report_campaign_list_offset);
	$("unopen").className = "adesk_block";
	$("unopen_opened").value = "unopened";
	$("main_tab_open").className = "currenttab";
}

function report_campaign_process_link(args) {
	report_campaign_list_mode = "link";
	if (args.length < 2) {
		adesk_ui_anchor_set(report_campaign_list_anchor());
		return;
	}

	report_campaign_list_sort = args[1];
	report_campaign_list_offset = args[2];
	report_campaign_list_filter = args[3];
	report_campaign_list_linkid = args[4];

	if (report_campaign_list_print==1) {
		paginators[6].limit = 1000000000;
		$("paginatorBox6").style.display = "none";
	}

	paginators[6].paginate(report_campaign_list_offset);
	$("link").className = "adesk_block";
	$("main_tab_link").className = "currenttab";
}

function report_campaign_process_linkinfo(args) {
	report_campaign_list_mode = "linkinfo";
	if (args.length < 2) {
		adesk_ui_anchor_set(report_campaign_list_anchor());
		return;
	}

	report_campaign_list_sort = args[1];
	report_campaign_list_offset = args[2];
	report_campaign_list_filter = args[3];
	report_campaign_list_linkid = args[4];

	if (report_campaign_list_print==1) {
		paginators[7].limit = 1000000000;
		$("paginatorBox7").style.display = "none";
	}

	paginators[7].paginate(report_campaign_list_offset);
	$("linkinfo").className = "adesk_block";
	$("main_tab_link").className = "currenttab";
}

function report_campaign_process_forward(args) {
	report_campaign_list_mode = "forward";
	if (args.length < 2) {
		adesk_ui_anchor_set(report_campaign_list_anchor());
		return;
	}

	report_campaign_list_sort = args[1];
	report_campaign_list_offset = args[2];
	report_campaign_list_filter = args[3];

	if (report_campaign_list_print==1) {
		paginators[2].limit = 1000000000;
		$("paginatorBox2").style.display = "none";
	}

	paginators[2].paginate(report_campaign_list_offset);
	$("forward").className = "adesk_block";
	$("main_tab_forward").className = "currenttab";
}

function report_campaign_process_update(args) {
	report_campaign_list_mode = "update";
	if (args.length < 2) {
		adesk_ui_anchor_set(report_campaign_list_anchor());
		return;
	}

	report_campaign_list_sort = args[1];
	report_campaign_list_offset = args[2];
	report_campaign_list_filter = args[3];

	if (report_campaign_list_print==1) {
		paginators[8].limit = 1000000000;
		$("paginatorBox2").style.display = "none";
	}

	paginators[8].paginate(report_campaign_list_offset);
	$("update").className = "adesk_block";
	$("main_tab_update").className = "currenttab";
}

function report_campaign_process_bounce(args) {
	report_campaign_list_mode = "bounce";

	if (args.length < 2) {
		adesk_ui_anchor_set(report_campaign_list_anchor());
		return;
	}

	report_campaign_list_sort = args[1];
	report_campaign_list_offset = args[2];
	report_campaign_list_filter = args[3];
	report_campaign_list_linkid = args[4];

	if (report_campaign_list_print==1) {
		paginators[3].limit = 1000000000;
		$("paginatorBox3").style.display = "none";
	}

	paginators[3].paginate(report_campaign_list_offset);
	$("bounce").className = "adesk_block";
	$("main_tab_bounce").className = "currenttab";
}

function report_campaign_process_unsub(args) {
	report_campaign_list_mode = "unsub";

	if (args.length < 2) {
		adesk_ui_anchor_set(report_campaign_list_anchor());
		return;
	}

	report_campaign_list_sort = args[1];
	report_campaign_list_offset = args[2];
	report_campaign_list_filter = args[3];
	report_campaign_list_linkid = args[4];

	if (report_campaign_list_print==1) {
		paginators[4].limit = 1000000000;
		$("paginatorBox4").style.display = "none";
	}

	paginators[4].paginate(report_campaign_list_offset);
	$("unsub").className = "adesk_block";
	$("main_tab_unsub").className = "currenttab";
}

function report_campaign_showdiv_general(id, label) {
	$("chart_read_bydate").style.display = "none";
	$("chart_read_byhour").style.display = "none";
	$("chart_link_bydate").style.display = "none";
	$("chart_link_byhour").style.display = "none";
	$("general_readlabel_date").className = "";
	$("general_readlabel_hour").className = "";
	$("general_linklabel_date").className = "";
	$("general_linklabel_hour").className = "";

	$(id).style.display = "";
	$(label).className  = "startup_selected";
}

function report_campaign_totals() {
	adesk_ajax_call_cb("awebdeskapi.php?hash=" + report_campaign_list_hash, "campaign.campaign_select_totals", adesk_ajax_cb(report_campaign_totals_cb), report_campaign_id, $("messageid").value);
}

function report_campaign_totals_cb(ary) {
	var success_sent = ary.send_amt - ary.totalbounces;
	// "Pending approval"
	if (ary.status == 7) success_sent = 0;
	if ( ary.total_amt < success_sent ) {
		// if recipients is less than "successfully sent," make these two equal (doesn't make sense otherwise)
		var total_recipients = success_sent;
	}
	else {
		var total_recipients = ary.total_amt;
	}
	$("general_total_t").innerHTML   = total_recipients;
	$("general_success_t").innerHTML = success_sent;

	// Responders never have a send_amt > 0; their total_amt is incremented as more responders
	// are sent.
	if (typeof ary.type != "undefined" && ary.type == "responder")
		$("general_success_t").innerHTML = ary.total_amt - ary.totalbounces;

	$("general_open_t").innerHTML    = ary.uniqueopens;
	$("general_link_t").innerHTML    = ary.subscriberclicks;
	$("general_unsub_t").innerHTML   = ary.unsubscribes;
	$("general_forward_t").innerHTML = ary.forwards;
	$("general_bounce_t").innerHTML  = ary.totalbounces;
	$("general_update_t").innerHTML  = ary.updates;
	$("general_open_p").innerHTML    = sprintf("%.2f%%", ary.total_amt > 0 ? 100 * ary.uniqueopens / ary.total_amt : 0);
	$("general_link_p").innerHTML    = sprintf("%.2f%%", ary.total_amt > 0 ? 100 * ary.subscriberclicks / ary.total_amt : 0);
	$("general_unsub_p").innerHTML   = sprintf("%.2f%%", ary.total_amt > 0 ? 100 * ary.unsubscribes / ary.total_amt : 0);
	$("general_forward_p").innerHTML = sprintf("%.2f%%", ary.total_amt > 0 ? 100 * ary.forwards / ary.total_amt : 0);
	$("general_update_p").innerHTML  = sprintf("%.2f%%", ary.total_amt > 0 ? 100 * ary.updates / ary.total_amt : 0);
	$("general_bounce_p").innerHTML  = sprintf("%.2f%%", ary.total_amt > 0 ? 100 * ary.totalbounces / ary.total_amt : 0);

	if(ary.ldate != "")
		$("general_ldate_t").innerHTML   = sql2date(ary.ldate).format(datetimeformat);
	else
		$("general_ldate_t").innerHTML   = "--";

	$("general_type_t").innerHTML  = campaign_type_strings[ary.type];
	$("general_status_t").innerHTML  = campaign_status_strings[ary.status];

	// Now update the tab counts.
	var uniqueopens = adesk_number_format(ary.uniqueopens, ".", ",");
	var uniquelinkclicks = adesk_number_format(ary.uniquelinkclicks, ".", ",");
	var forwards = adesk_number_format(ary.forwards, ".", ",");
	var totalbounces = adesk_number_format(ary.totalbounces, ".", ",");
	var unsubscribes = adesk_number_format(ary.unsubscribes, ".", ",");
	var updates = adesk_number_format(ary.updates, ".", ",");
	var socialshares = adesk_number_format(ary.socialshares, ".", ",");
	$("count_tab_open").innerHTML    = sprintf("(%s)", uniqueopens);
	$("count_tab_link").innerHTML    = sprintf("(%s)", uniquelinkclicks);
	$("count_tab_forward").innerHTML = sprintf("(%s)", forwards);
	$("count_tab_bounce").innerHTML  = sprintf("(%s)", totalbounces);
	$("count_tab_unsub").innerHTML   = sprintf("(%s)", unsubscribes);
	$("count_tab_update").innerHTML  = sprintf("(%s)", updates);
	$("count_tab_socialsharing").innerHTML  = sprintf("(%s)", socialshares);
}

function report_campaign_export() {
	var url = window.location.href.replace(/#.*$/, "");
	url += sprintf("&export=%s&linkid=%d&filterid=%d", report_campaign_list_mode, report_campaign_list_linkid, report_campaign_list_filter);

	window.location.href = url;
}

function report_campaign_print() {
	var url = window.location.href.replace(/\?/, "?print=1&");
	window.open(url);
}

function report_campaign_message_overlay(messageid) {
	if ( $("message_htmliframe_" + messageid) ) {
		var src = $("message_htmliframe_" + messageid).src;
	} else {
		var src = $("message_textiframe_" + messageid).src;
	}

	if (!src.match(/overlay=/))
		src = src + "&overlay=0";

	if (!report_campaign_message_showoverlay) {
		src = src.replace(/overlay=0/, "overlay=1");
		$("message_showoverlay_" + messageid).innerHTML = report_campaign_message_str_hideoverlay;
		report_campaign_message_showoverlay = true;
	} else {
		src = src.replace(/overlay=1/, "overlay=0");
		$("message_showoverlay_" + messageid).innerHTML = report_campaign_message_str_showoverlay;
		report_campaign_message_showoverlay = false;
	}

	if ( $("message_htmliframe_" + messageid) ) {
		$("message_htmliframe_" + messageid).src = src;
	} else {
		$("message_textiframe_" + messageid).src = src;
	}
}

function report_campaign_message_source(messageid) {
	if ( $("message_htmliframe_" + messageid) ) {
		var src = $("message_htmliframe_" + messageid).src;
	} else {
		var src = $("message_textiframe_" + messageid).src;
	}

	if (!src.match(/source=/))
		src = src + "&source=0";

	if (!report_campaign_message_showsource) {
		src = src.replace(/source=0/, "source=1");
		$("message_showsource_" + messageid).innerHTML = report_campaign_message_str_hidesource;
		report_campaign_message_showsource = true;
	} else {
		src = src.replace(/source=1/, "source=0");
		$("message_showsource_" + messageid).innerHTML = report_campaign_message_str_showsource;
		report_campaign_message_showsource = false;
	}

	if ( $("message_htmliframe_" + messageid) ) {
		$("message_htmliframe_" + messageid).src = src;
	} else {
		$("message_textiframe_" + messageid).src = src;
	}
}

function report_campaign_message_spamcheck(messageid) {
	adesk_dom_toggle_display('send_test_spam', 'block');
	$('spamloader').className = 'adesk_block';
	$('spamresult').className = 'adesk_hidden';
	$('spamform').className = 'adesk_hidden';
	adesk_ui_api_call(jsChecking, 60);
	adesk_ajax_handle_text = spamcheck_emailcheck_cb_txt;
	adesk_ajax_call_cb("awebdeskapi.php", "report_campaign.report_campaign_spamcheck", spamcheck_emailcheck_cb, report_campaign_id, messageid);
}

function report_campaign_messagefilter(messageid) {
	// The graphs
	refresh_chart_read_bydate("messageid=" + messageid);
	refresh_chart_read_byhour("messageid=" + messageid);
	refresh_chart_link_bydate("messageid=" + messageid);
	refresh_chart_link_byhour("messageid=" + messageid);
	refresh_chart_open_pie("messageid=" + messageid);

	var url = window.location.href.split("#");
	if (url.length > 1) {
		report_campaign_process(url[1], null);
	}
}

{/literal}

{include file="report_campaign_open.inc.js"}
{include file="report_campaign_forward.inc.js"}
{include file="report_campaign_bounce.inc.js"}
{include file="report_campaign_unsub.inc.js"}
{include file="report_campaign_unopen.inc.js"}
{include file="report_campaign_link.inc.js"}
{include file="report_campaign_linkinfo.inc.js"}
{include file="report_campaign_share.inc.js"}
{include file="report_campaign_social.inc.js"}
{include file="report_campaign_update.inc.js"}
{include file="report_campaign_socialsharing.inc.js"}
