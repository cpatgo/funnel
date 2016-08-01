// complaint.js

var abuse_str_view_campaign = '{"View Campaign"|alang|js}';
var abuse_str_conf_reset = '{"This action will delete all abuse complaints!"|alang|js}\n\n{"Are you sure you wish to reset all abuse complaints for this group?"|alang|js}';
var abuse_str_change_number = '{"Abuse Ratio has to be between zero (0) and a hundred (100)."|alang|js}';
var abuse_str_notify_to_none = '{"No recipients have been selected."|alang|js}';
var abuse_str_notify_from_none = '{"From e-mail address is not valid. Please provide a valid one."|alang|js}';
var abuse_str_notify_subject_none = '{"Subject should not be empty. Please provide a subject."|alang|js}';
var abuse_str_notify_message_none = '{"Notification message should not be empty. Please provide a message."|alang|js}';

{jsvar name=abuse var=$abuse}

{literal}

function abuse_toggle(panel) {
	adesk_dom_toggle_class('infobox', 'adesk_block', 'adesk_hidden');
	adesk_dom_toggle_class(panel + 'box', 'adesk_block', 'adesk_hidden');
	return false;
}

function abuse_change() {
	return abuse_toggle('change');
}

function abuse_notify() {
	return abuse_toggle('notify');
}

function abuse_view() {
	// if open, close it and exit
	if ( $('viewbox').className != 'adesk_hidden' ) {
		abuse_toggle('view');
		return false;
	}

	// ajax call
	adesk_ui_api_call(jsLoading);
	adesk_ajax_call_cb(apipath, 'abuse.abuse_list', abuse_view_cb, abuse.id, abuse.hash);
	return false;
}

function abuse_view_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	var rel = $('abusesbox');
	adesk_dom_remove_children(rel);
	for ( var i = 0; i < ary.row.length; i++ ) {
		var row = ary.row[i];
		var nodes = [ ];
		nodes.push(Builder.node('div', [ Builder._text(row.rdate) ]));
		nodes.push(
			Builder.node(
				'div',
				[
					Builder.node(
						'a',
						{ href: plink + '/manage/desk.php?action=subscriber_view&id=' + row.subscriberid },
						[ Builder._text(row.email) ]
					)
				]
			)
		);
		nodes.push(
			Builder.node(
				'div',
				[
					Builder.node(
						'a',
						{ href: plink + '/manage/desk.php?action=report_campaign&id=' + row.campaignid },
						[ Builder._text(abuse_str_view_campaign) ]
					)
				]
			)
		);
		rel.appendChild(Builder.node('div', { className: 'abuse_row' }, nodes));
	}

	// open the list
	abuse_toggle('view');
}


function abuse_reset() {
	if ( !confirm(abuse_str_conf_reset) ) {
		return false;
	}
	// ajax call
	adesk_ui_api_call(jsResetting);
	adesk_ajax_call_cb(apipath, 'abuse.abuse_reset', abuse_reset_cb, abuse.id, abuse.hash);
	return false;
}

function abuse_reset_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded == 1) {
		adesk_result_show(ary.message);
		$("current_label").innerHTML = '0';
		$("abuses_label").innerHTML = '0';
		// remove the reset and view buttons
		$("abuse_button_view").style.display = "none";
		$("abuse_button_reset").style.display = "none";
		abuse_toggle('reset');
	} else {
		adesk_error_show(ary.message);
	}
}

function abuse_notify_send() {
	var post = adesk_form_post($('notifybox'));
	post.id = abuse.id;
	post.hash = abuse.hash;

	// form check
	if ( typeof post.to == 'undefined' ) {
		alert(abuse_str_notify_to_none);
		return false;
	}
	if ( !adesk_str_email(post.from_mail) ) {
		alert(abuse_str_notify_from_none);
		return false;
	}
	if ( post.subject == '' ) {
		alert(abuse_str_notify_subject_none);
		return false;
	}
	if ( post.message == '' ) {
		alert(abuse_str_notify_message_none);
		return false;
	}

	// ajax call
	adesk_ui_api_call(jsLoading);
	adesk_ajax_post_cb(apipath, 'abuse.abuse_notify', abuse_notify_send_cb, post);
	return false;
}

function abuse_notify_send_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded == 1) {
		adesk_result_show(ary.message);
		abuse_toggle('notify');
	} else {
		adesk_error_show(ary.message);
	}
}

function abuse_update() {
	var newval = parseInt($('group_abuseratio').value, 10);
	if ( isNaN(newval) || newval < 0 || newval > 100 ) {
		alert(abuse_str_change_number);
		$('group_abuseratio').focus();
		return false;
	}

	// ajax call
	adesk_ui_api_call(jsUpdating);
	adesk_ajax_call_cb(apipath, 'abuse.abuse_update', abuse_update_cb, abuse.id, abuse.hash, newval);
	return false;
}

function abuse_update_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded == 1) {
		adesk_result_show(ary.message);
		var newval = $('group_abuseratio').value;
		$('current_label').innerHTML = newval;
		// hide overlimit actions
		//if ( parseInt($('current_label').value, 10) > parseInt(newval, 10) ) {
			//
		//}
		abuse_toggle('change');
		abuse_toggle('update');
	} else {
		adesk_error_show(ary.message);
	}
}

{/literal}
