var campaign_action_campaigns = {jsvar var=$campaigns};
var campaign_action_lists = {jsvar var=$lists};
var campaign_action_fields = {jsvar var=$fields};

var campaign_action_actioncount = 0;

var campaign_action_str_autoresponders = '{"Auto Responders"|alang|js}';
var campaign_action_str_firstname = '{"First Name"|alang|js}';
var campaign_action_str_lastname = '{"Last Name"|alang|js}';
var campaign_action_str_mailings = '{"Mailings"|alang|js}';
var campaign_action_str_send = '{"Send to"|alang|js}';
var campaign_action_str_subdatebased = '{"Subscriber date based"|alang|js}';
var campaign_action_str_subscribe = '{"Subscribe"|alang|js}';
var campaign_action_str_unsubscribe = '{"Unsubscribe"|alang|js}';
var campaign_action_str_update = '{"Update custom field"|alang|js}';
var campaign_spamcheck_str_scoreheader = '{"Your campaign scored %s on our spam filter tests."|alang|js}';
var campaign_spamcheck_str_msgheader = '{"Message #%s (%s): %s/%s"|alang|js}';
var campaign_spamcheck_str_noissues = '{"No issues found."|alang|js}';
var campaign_inboxpreview_str_problems_no = '{"Your email design looks great in all the major email clients!"|alang|js}';
var campaign_inboxpreview_str_problems_yes = '{"Your email design may look different in %s."|alang|js}';

var campaign_summary_str_sdate = '{"Subscription Date"|alang|js}';
var campaign_summary_str_cdate = '{"Creation Date"|alang|js}';
var campaign_summary_str_customfield = '{"Subscriber Field"|alang|js}';

var campaign_str_finish  = '{"Finish"|alang|js}';
var campaign_str_sendnow = '{"Send Now"|alang|js}';

var campaign_spamcheck_high = false;
{literal}

function campaign_summary_option(id, val) {
	var cat = "campaign_" + id;

	if ($(cat))
		$(cat).value = val;

	// Now figure out which div to show.
	$(cat + "_yes").hide();
	$(cat + "_no").hide();

	if (val == 1)
		$(cat + "_yes").show();
	else
		$(cat + "_no").show();

	campaign_different();
}

function campaign_summary_option_dual(id1, id2, val1, daddydiv) {
	var cat1 = "campaign_" + id1;
	var cat2 = "campaign_" + id2;
	var val2 = 0;

	if ($(cat1))
		$(cat1).value = val1;

	if ($(cat2))
		val2 = $(cat2).value;

	// Now figure out which div to show.
	$(cat1 + "_link_yes").hide();
	$(cat1 + "_link_no").hide();

	if (val1 == 1)
		$(cat1 + "_link_yes").show();
	else
		$(cat1 + "_link_no").show();

	// Now figure out what to do with master div.
	if ($("campaign_" + daddydiv + "_label_yes"))
		$("campaign_" + daddydiv + "_label_yes").hide();
	$("campaign_" + daddydiv + "_label_" + id1).hide();
	if ($("campaign_" + daddydiv + "_label_" + id2))
		$("campaign_" + daddydiv + "_label_" + id2).hide();
	$("campaign_" + daddydiv + "_label_no").hide();

	if (val1 == 1 && val2 == 1) {
		$("campaign_" + daddydiv + "_label_yes").show();
	} else if (val1 == 1 && val2 == 0) {
		$("campaign_" + daddydiv + "_label_" + id1).show();
	} else if (val1 == 0 && val2 == 1) {
		$("campaign_" + daddydiv + "_label_" + id2).show();
	} else { // 0, 0
		$("campaign_" + daddydiv + "_label_no").show();
	}

	campaign_different();
}

function campaign_markreads(val) {
	campaign_summary_option("tracking", val ? 1 : 0);

	if (val) {
		$('campaign_trackreads_checkbox').checked = true;
		$('campaign_tracklinks_checkbox').checked = true;
		$('linksdiv').show();

		$('campaign_trackopts').show();
		$('campaign_tracking_yes').show();
		$('campaign_tracking_yes').className = 'campaign_summary_top';
		$('campaign_tracking_no').hide();
	}
	else {
		$('campaign_trackreads_checkbox').checked = false;
		$('campaign_tracklinks_checkbox').checked = false;
		$('linksdiv').hide();
		$('campaign_trackopts').hide();
		$('campaign_tracking_yes').hide();
		$('campaign_tracking_no').show();
		$('campaign_tracking_no').className = 'campaign_summary';
	}
}

function campaign_checktrackopts(from) {
	if (!$("campaign_tracklinks_checkbox").checked) {
		$("linksdiv").hide();
		campaign_linktrack_markall(false);
	} else {
		$("linksdiv").show();
		if (from == "link")
			campaign_linktrack_markall(true);
	}

	if (!$("campaign_trackreads_checkbox").checked && !$("campaign_tracklinks_checkbox").checked) {
		campaign_markreads(false);
	}
}

/*
// The emailtest, preview, etc. options
function campaign_open_emailtest() {
	// set split message box
	if ( campaign_obj.type == 'split' )
		$('testemailsplitbox').show();
	else
		$('testemailsplitbox').hide();
	// get message id
	if ( $('testemailsplit').selectedIndex != -1 ) {
		var msg = $('testemailsplit').value;
	} else {
		var msg = campaign_obj.messages[0].id;
	}
	// set format
	campaign_set_emailtest(msg);
	$('subscriberEmailTestField').focus(); // set focus to "To email" textbox (in Send test email modal), IE won't let you edit the field otherwise
}
*/

function campaign_set_emailtest(msg) {
	if ( !isNaN(parseInt(msg, 10)) ) {
		msg = parseInt(msg, 10);
		// find message
		for ( var i in campaign_obj.messages ) {
			var m = campaign_obj.messages[i];
			if ( typeof m != 'function' ) {
				if ( msg == m.id ) {
					msg = m;
					break;
				}
			}
		}
	}
	if ( typeof msg.format == 'undefined' ) return;
	// set format
	$('testemailtype').value = msg.format;
	// show format select?
	/*
	if (msg.format == 'mime') {
		$('testemailtype').show();
		$('testemailtypelabel').show();
	} else {
		$('testemailtype').hide();
		$('testemailtypelabel').hide();
	}
	*/
}

function campaign_send_emailtest() {
	var post = {};

	if(!post.testemail)
		post.testemail = document.getElementById('subscriberEmailTestField').value;

	// check for email validity
	var test_email = $('subscriberEmailTestField').value; // use DOM ID to grab value
	post.testemail = test_email; // reset post value (shows up as "undefined" in IE, for some reason)
	if ( !adesk_str_email(test_email) ) {
		alert(strEmailNotEmail);
		$('subscriberEmailTestField').focus();
		return;
	}
	// check if split
	if ( campaign_obj.type == 'split' ) {
		// check if any messages are selected
		if ( $('testemailsplit').selectedIndex == -1 ) {
			alert(campaign_nomessage_str);
			return;
		}
		post.testemailsplit = $('testemailsplit').value;
	} else {
		// assign the only message
		post.testemailsplit = campaign_obj.messages[0].id;
	}
	post.campaignid = campaign_obj.id;
	adesk_ui_api_call(jsSending, 60);
	adesk_ajax_handle_text = campaign_send_emailtest_cb_txt;
	adesk_ajax_post_cb("awebdeskapi.php", "campaign.campaign_send_emailtest", campaign_send_emailtest_cb, post);
}

function campaign_send_emailtest_cb_txt(txt) {
	adesk_ui_error_mailer(txt);
}

function campaign_send_emailtest_cb(xml) {
	// now reset the text handler
	adesk_ajax_handle_text = null;
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		// don't show any results here
	} else {
		adesk_error_show(ary.message);
	}
}


function campaign_open_preview() {
	var url = 'awebview.php?c=' + campaign_obj.id + '&m=' + campaign_obj.messages[0].id + '&s=0';
	var w = window.open(
		url,
		'previewcampaign',
		'width=650,height=500,toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,copyhistory=yes,resizable=yes'
	);
	if ( !w ) {
		alert('Popup could not be opened.');
	}
	if ( w.focus ) w.focus();
	return;
}

function campaign_run_inboxpreview() {
	//adesk_ui_api_call(jsChecking, 60);
	//adesk_ajax_handle_text = campaign_inboxpreview_cb_txt;
	adesk_ajax_call_cb("awebdeskapi.php", "campaign.campaign_inboxpreview", campaign_inboxpreview_cb, campaign_obj.id);
}

function campaign_inboxpreview_cb(xml) {
	// now reset the text handler
	adesk_ajax_handle_text = null;
	var ary = adesk_dom_read_node(xml);
	//adesk_ui_api_callback();

	if (ary.succeeded == 1) {

		for ( var index = 0; index < ary.messages.length; index++ ) {
			var prob_clients = [];
			var issues = ary.messages[index].issues;

			for ( var i = 0; i < issues.length; i++ ) {
				prob_clients.push(issues[i].clientname);
			}

			if ( prob_clients.length > 0 ) {
				var prob_clients_str = '';
				if ( prob_clients.length == 1 ) {
					prob_clients_str = prob_clients[0];
				} else if ( prob_clients.length == 2 ) {
					prob_clients_str = prob_clients.join(' and ');
				} else {
					last_client = prob_clients.pop();
					prob_clients_str = prob_clients.join(', ') + ' and ' + last_client;
				}
				$('inboxpreview_result').innerHTML = sprintf(campaign_inboxpreview_str_problems_yes, prob_clients_str);
			} else {
				$('inboxpreview_result').innerHTML = campaign_inboxpreview_str_problems_no;
			}
		}

		//adesk_result_show(ary.message);
	} else {
		adesk_error_show(ary.message);
	}
}

function campaign_inboxpreview_cb_txt(txt) {
	adesk_ui_error_mailer(txt);
}

function campaign_open_inboxpreview() {
	var url = 'preview_client.php?c=' + campaign_obj.id + '&m=' + campaign_obj.messages[0].id + '&s=';
	var w = window.open(
		url,
		'previewclient',
		'width=900,height=600,toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,copyhistory=yes,resizable=no'
	);
	if ( !w ) {
		alert('Popup could not be opened.');
	}
	if ( w.focus ) w.focus();
	return;
}

function campaign_run_spamcheck() {
	//adesk_ui_api_call(jsChecking, 60);
	//adesk_ajax_handle_text = campaign_spamcheck_cb_txt;
	adesk_ajax_call_cb("awebdeskapi.php", "campaign.campaign_spamcheck", campaign_spamcheck_cb, campaign_obj.id);
}

function campaign_spamcheck_cb(xml) {
	// now reset the text handler
	adesk_ajax_handle_text = null;
	var ary = adesk_dom_read_node(xml);
	//adesk_ui_api_callback();

	if (ary.succeeded == 1) {

		var total_score  = 0;
		var total_maxres = 0;
		campaign_spamcheck_high = false;

		//var message_rules = {};

		var rel = $('spamcheck_table');

		for ( var index = 0; index < ary.messages.length; index++ ) {
			//message_rules[index] = ary.messages[index];

			if ( !ary.messages[index].succeeded ) {
				alert(ary.messages[index].message);
				continue;
			}

			// get the subject
			var subject = jsNotAvailable;
			var mid = ary.messages[index].mid;
			for ( var i in campaign_obj.messages ) {
				if ( typeof campaign_obj.messages[i].id == 'undefined' ) continue;
				if ( campaign_obj.messages[i].id != mid ) continue;
				subject = campaign_obj.messages[i].subject;
			}

			// get the score
			//var score = parseFloat(ary.messages[index].score);
			total_score  += parseFloat(ary.messages[index].score);
			total_maxres += parseFloat(ary.messages[index].max);
			// set the rules
			var mpart_alt_diff = null;
			var mime_html_only = null;
			var rules = 0;
			var finalrules = [];
			for ( var i = 0; i < ary.messages[index].rules.length; i++ ) {
				var r = ary.messages[index].rules[i];
				if ( r.score == '0.0' || r.score == '0.00' || r.score == '0' ) continue;
				r.score = parseFloat(r.score);
				if ( r.name == 'MPART_ALT_DIFF' ) {
					if ( !isNaN(parseInt(mime_html_only, 10)) ) {
						finalrules[mime_html_only].score += r.score;
						continue;
					}
					mpart_alt_diff = finalrules.length;
				} else if ( r.name == 'MIME_HTML_ONLY' ) {
					if ( !isNaN(parseInt(mpart_alt_diff, 10)) ) {
						finalrules[mpart_alt_diff].score += r.score;
						continue;
					}
					mime_html_only = finalrules.length;
				}
				finalrules.push(r);
				rules++;
			}

			var msgbox  = [];
			// build header
			if ( ary.messages.length > 1 ) {
				msgbox.push(Builder.node('strong', [ sprintf(campaign_spamcheck_str_msgheader, index+1, subject, ary.messages[index].score, ary.messages[index].max) ]));
				msgbox.push(Builder.node('br'));
			}
			if ( rules > 0 ) {
				// build the header
				var rulebox = [];

				// build table body
				for ( var i = 0; i < finalrules.length; i++ ) {
					var r = finalrules[i];
					rulebox.push(
						Builder.node(
							'tr',
							[
								Builder.node('td', { width: 25, title: r.name }, [ Builder._text(r.score) ]),
								Builder.node('td', { title: r.name }, [ Builder._text(r.descript) ]),
							]
						)
					);
				}

				// build table
				msgbox.push(Builder.node('table', { width: '100%', border: 0, cellpadding: 0, cellspacing: 0 }, rulebox));

				campaign_spamcheck_high = campaign_spamcheck_high || parseFloat(ary.messages[index].score) / parseFloat(ary.messages[index].max) >= .40;

			} else {
				msgbox.push(Builder.node('div', [ campaign_spamcheck_str_noissues ]));
			}
			rel.appendChild(Builder.node('div', { className: 'spamcheck_message', style: 'margin-top:10px;' }, msgbox));
		}

		// set the score
		var scorestring = total_score + '/' + total_maxres;
		//$('spamcheck_score').innerHTML = scorestring;
		$('spamcheck_result').innerHTML = sprintf(campaign_spamcheck_str_scoreheader, scorestring);
		// set the scene
		if ( total_score > 0 ) {
			$('spamcheck_details_link').show();
			//$('spamcheck_details').show();
			//$('campaign_spamcheck').className = 'campaign_summary_top';
		} else {
			$('spamcheck_details_link').hide();
			//$('spamcheck_details').hide();
			//$('campaign_spamcheck').className = 'campaign_summary';
		}

		if ( campaign_spamcheck_high ) {
			$('campaign_spamcheck').className = $('spamcheck_details').style.display == 'none' ? 'campaign_summary_red' : 'campaign_summary_red_top';
			$('spamcheck_details').className = 'campaign_summary_red_bottom';
		} else {
			$('campaign_spamcheck').className = $('spamcheck_details').style.display == 'none' ? 'campaign_summary' : 'campaign_summary_top';
			$('spamcheck_details').className = 'campaign_summary_bottom';
		}

		//adesk_result_show(ary.message);
	} else {
		adesk_error_show(ary.message);
	}
}

function campaign_spamcheck_cb_txt(txt) {
	adesk_ui_error_mailer(txt);
}

function campaign_spamcheck_toggle() {
	adesk_dom_toggle_display('spamcheck_details', 'block');
	adesk_dom_toggle_class(
		'campaign_spamcheck',
		campaign_spamcheck_high ? 'campaign_summary_red' : 'campaign_summary',
		campaign_spamcheck_high ? 'campaign_summary_red_top' : 'campaign_summary_top'
	);
}

function campaign_linktrack_markall(which) {
	if ( !$("linksdiv") ) return;

	var inputs = $("linksdiv").getElementsByTagName("input");

	for (var i = 0; i < inputs.length; i++) {
		if (inputs[i].name == "linktracked")
			inputs[i].checked = which;
	}
}

// responder stuff.
function campaign_responder_switch(which) {
	if (which == "immed") {
		$("campaign_responder_inputs").hide();
		$("respondday").value = 0;
		$("respondhour").value = 0;
	} else {
		$("campaign_responder_inputs").show();
	}
}

// reminder stuff.
function campaign_reminder_compile() {
	// Take all of the different fields and compile the example string.
	var post = {};

	if ($("reminder_offset_sign").value == "=") {
		$("reminder_offset").hide();
		$("reminder_offset_type").hide();
		$("reminder_offset").value = 0;
	} else {
		$("reminder_offset").show();
		$("reminder_offset_type").show();
	}

	post.sign = $("reminder_offset_sign").value;
	post.offset = $("reminder_offset").value;
	post.type = $("reminder_offset_type").value;
	post.field = $("reminder_field").value;

	adesk_ajax_post_cb("awebdeskapi.php", "campaign.campaign_reminder_compile_post", adesk_ajax_cb(campaign_reminder_compile_cb), post);
}

function campaign_reminder_compile_cb(ary) {
	$("campaign_reminder_example").innerHTML = ary.compile;
}

function campaign_reminder_issystem() {
	if ( isNaN(parseInt($('reminder_field').value, 10)) ) {
		// is system field
		$('reminder_format').value = 'yyyy-mm-dd';
		$('reminder_format_nonsystem').hide();
		$('reminder_format').hide();
	} else {
		// custom field
		$('reminder_format_nonsystem').show();
		$('reminder_format').show();
	}
}

// Subscriber actions
function campaign_action_new(id, index, opt, v1, v2) {
	// Get the div, if it's there.
	var elemid = sprintf("link_actions_row_%s_%s", id, index);
	var elem   = $(elemid);

	if (!elem) {
		elem = Builder.node("div", { id: elemid });
		$("link_actions_div" + id).appendChild(elem);

		if (!elem) { // what??
			return;
		}
	}

	adesk_dom_remove_children(elem);
	var opts = [
		Builder.node("option", { value: "subscribe" }, campaign_action_str_subscribe),
		Builder.node("option", { value: "unsubscribe" }, campaign_action_str_unsubscribe),
		Builder.node("option", { value: "send" }, campaign_action_str_send),
		Builder.node("option", { value: "update" }, campaign_action_str_update)
	];

	var action = Builder.node("select", { name: "linkaction", onchange: sprintf("campaign_action_new(%s, %s, this.value, 0, 0)", id, index) }, opts);

	action.value = opt;
	elem.appendChild(action);

	switch (opt) {
		case "subscribe":
		case "unsubscribe":
		default:
			var opts = [];

			for (var i = 0; typeof campaign_action_lists[i] != "undefined"; i++) {
				opts.push(Builder.node("option", { value: campaign_action_lists[i].id }, campaign_action_lists[i].name));
			}

			var sel = Builder.node("select", { name: "linkvalue1" }, opts);
			if (v1 != 0)
				sel.value = v1;
			elem.appendChild(sel);

			elem.appendChild(Builder.node("input", { type: "hidden", name: "linkvalue2", value: "" }));
			elem.appendChild(Builder.node("input", { type: "hidden", name: "linkvalue3", value: "" }));
			elem.appendChild(Builder.node("input", { type: "hidden", name: "linkvalue4", value: "" }));
			break;

		case "send":
			var m_opts = [];	// Mailings
			var a_opts = [];	// Auto responders
			var s_opts = [];	// Subscriber date based

			for (var i = 0; typeof campaign_action_campaigns[i] != "undefined"; i++) {
				switch (campaign_action_campaigns[i].type) {
					case "single":
					default:
						m_opts.push(Builder.node("option", { value: campaign_action_campaigns[i].id }, campaign_action_campaigns[i].name));
						break;

					case "responder":
						a_opts.push(Builder.node("option", { value: campaign_action_campaigns[i].id }, campaign_action_campaigns[i].name));
						break;

					case "reminder":
						s_opts.push(Builder.node("option", { value: campaign_action_campaigns[i].id }, campaign_action_campaigns[i].name));
						break;
				}
			}

			var opts = [
				Builder.node("optgroup", { label: campaign_action_str_mailings }, m_opts),
				Builder.node("optgroup", { label: campaign_action_str_autoresponders }, a_opts),
				Builder.node("optgroup", { label: campaign_action_str_subdatebased }, s_opts),
			];

			elem.appendChild(Builder.node("input", { type: "hidden", name: "linkvalue1", value: "" }));

			var sel = Builder.node("select", { name: "linkvalue2" }, opts);
			if (v1 != 0)
				sel.value = v1;
			elem.appendChild(sel);

			elem.appendChild(Builder.node("input", { type: "hidden", name: "linkvalue3", value: "" }));
			elem.appendChild(Builder.node("input", { type: "hidden", name: "linkvalue4", value: "" }));
			break;

		case "update":
			var opts = [];

			// Add first & last name
			opts.push(Builder.node("option", { value: "first_name" }, campaign_action_str_firstname));
			opts.push(Builder.node("option", { value: "last_name" }, campaign_action_str_lastname));

			for (var i = 0; typeof campaign_action_fields[i] != "undefined"; i++) {
				opts.push(Builder.node("option", { value: campaign_action_fields[i].id }, campaign_action_fields[i].title));
			}

			elem.appendChild(Builder.node("input", { type: "hidden", name: "linkvalue1", value: "" }));
			elem.appendChild(Builder.node("input", { type: "hidden", name: "linkvalue2", value: "" }));

			var sel = Builder.node("select", { name: "linkvalue3" }, opts);
			if (v1 != 0)
				sel.value = v1;
			elem.appendChild(sel);

			var text = Builder.node("input", { name: "linkvalue4", type: "text" });
			if (v2 != 0)
				text.value = v2;
			elem.appendChild(text);
			break;
	}

	// Add a delete icon.
	elem.appendChild(Builder.node("span", { style: "margin-left: 10px", onclick: sprintf("campaign_action_delete(%s, %s)", id, index) }, [
		Builder.node("img", { src: "images/selection_delete-16-16.png" })
	]));
}

function campaign_action_delete(id, index) {
	var elemid = sprintf("link_actions_row_%s_%s", id, index);
	var containerid = sprintf("link_actions_div%s", id);

	$(containerid).removeChild($(elemid));
}

function campaign_action_save(id) {
	var elemid = sprintf("link_actions%s", id);

	var post = adesk_form_post_alt(elemid);

	adesk_ajax_post_cb("awebdeskapi.php", "campaign.campaign_save_action", adesk_ajax_cb(campaign_action_save_cb), post);
	$(elemid).hide();
}

function campaign_action_save_cb(ary) {
	var elemid = sprintf("actioncount%s", ary.linkid);

	if ($(elemid))
		$(elemid).innerHTML = ary.count;
}

function campaign_action_load(id) {
	adesk_ajax_call_cb("awebdeskapi.php", "campaign.campaign_load_action", adesk_ajax_cb(campaign_action_load_cb), id, campaign_obj.id);
}

function campaign_action_load_cb(ary) {
	var elemid = sprintf("link_actions%s", ary.linkid);
	var divid  = sprintf("link_actions_div%s", ary.linkid);

	adesk_dom_remove_children($(divid));

	for (var i = 0; i < ary.parts.length; i++) {
		var v1 = 0;
		var v2 = 0;
		var action = ary.parts[i].act;

		if (action == "update") {
			if (ary.parts[i].targetfield != "")
				v1 = ary.parts[i].targetfield;
			else
				v1 = ary.parts[i].targetid;

			v2 = ary.parts[i].param;
		} else {
			v1 = ary.parts[i].targetid;
		}

		campaign_action_new(ary.linkid, campaign_action_actioncount++, action, v1, v2);
	}

	if (ary.parts.length == 0) {
		// Well... add some default then.
		campaign_action_new(ary.linkid, campaign_action_actioncount++, "subscribe", 0, 0);
	}

	$(elemid).show();
}

function campaign_action_hide(id) {
	var containerid = sprintf("link_actions%s", id);
	$(containerid).hide();
}

{/literal}
