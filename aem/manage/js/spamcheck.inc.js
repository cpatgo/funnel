{* var spamcheck_mode = {jsvar var=$mode}; *}
{* var spamcheck = {jsvar var=$spamcheck}; *}

{literal}

function spamcheck_open() {
	/*
	if ( !spamcheck || spamcheck == '' ) {
		alert('Improper usage.')
		return false;
	}
	*/
	adesk_dom_toggle_display('send_test_spam', 'block');
	$('spamloader').className = 'adesk_hidden';
	$('spamresult').className = 'adesk_hidden';
	$('spamform').className = 'adesk_block';
	// show message select?
	$('spamcheckemailsplitbox').className = ( typeof campaign_obj != 'undefined' && campaign_obj.type == 'split' ? 'adesk_table_rowgroup' : 'adesk_hidden' );
	// set format
	if ( $('spamcheckemailsplit').selectedIndex != -1 ) {
		var msg = $('spamcheckemailsplit').value;
	} else if ( typeof campaign_obj != 'undefined' ) {
		var msg = campaign_obj.messages[0].id;
	} else {
		//var msg = form_id;
		var msg = 0;
	}
	spamcheck_set(msg);
	$('subscriberEmailCheckField').focus(); // set focus to "To email" textbox (in Send test email modal), IE won't let you edit the field otherwise
}

function spamcheck_set(msg) {
	if ( !isNaN(parseInt(msg, 10)) ) {
		msg = parseInt(msg, 10);
		if ( msg == 0 ) {
			// in message page, get the form contents instead
			var msg = message_form_post(false);
		} else {
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
	}
	if ( typeof msg.format == 'undefined' ) return;
	// set format
	$('spamcheckemailtype').value = msg.format;
	// show format select?
	$('spamcheckemailtype').className = ( msg.format == 'mime' ? 'adesk_inline' : 'adesk_hidden' );
	$('spamcheckemailtypelabel').className = ( msg.format == 'mime' ? 'adesk_inline' : 'adesk_hidden' );
}

function spamcheck_emailcheck() {
	var post = {};

	// check for email validity
	var spamcheck_email = $('subscriberEmailCheckField').value; // use DOM ID to grab value
	post.spamcheckemail = spamcheck_email; // reset post value (shows up as "undefined" in IE, for some reason)
	if ( !adesk_str_email(spamcheck_email) ) {
		alert(strEmailNotEmail);
		$('subscriberEmailCheckField').focus();
		return;
	}
	// check if split
	if ( typeof campaign_obj != 'undefined' ) {
		if ( campaign_obj.type == 'split' ) {
			// check if any messages are selected
			if ( $('spamcheckemailsplit').selectedIndex == -1 ) {
				alert(campaign_nomessage_str);
				return;
			}
		} else {
			// assign the only message
			post.spamcheckemailsplit = campaign_obj.messages[0].id;
			//post.spamcheckemailsplit = $('messageField').value;
		}
	}
	post.campaignid = campaign_obj.id;

	$('spamloader').className = 'adesk_block';
	$('spamresult').className = 'adesk_hidden';
	$('spamform').className = 'adesk_hidden';
	adesk_ui_api_call(jsChecking, 60);
	adesk_ajax_handle_text = spamcheck_emailcheck_cb_txt;
	if ( typeof campaign_obj != 'undefined' ) {
		adesk_ajax_post_cb("awebdeskapi.php", "campaign.campaign_spam_emailcheck", spamcheck_emailcheck_cb, post);
	} else {
		adesk_ajax_post_cb("awebdeskapi.php", "message.message_spam_emailcheck", spamcheck_emailcheck_cb, post);
	}
}

function spamcheck_emailcheck_cb_txt(txt) {
	adesk_ui_error_mailer(txt, 'send_test_spam');
}

function spamcheck_emailcheck_cb(xml) {
	// now reset the text handler
	adesk_ajax_handle_text = null;
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded == 1) {
		adesk_result_show(ary.message);
		// get the score
		//var score = parseFloat(ary.score);
		// set the rules
		var mpart_alt_diff = null;
		var mime_html_only = null;
		var rules = 0;
		var finalrules = [];
		for ( var i = 0; i < ary.rules.length; i++ ) {
			var r = ary.rules[i];
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
		var rel = $('emailcheck_rules');
		adesk_dom_remove_children(rel);
		for ( var i = 0; i < finalrules.length; i++ ) {
			var r = finalrules[i];
			rel.appendChild(
				Builder.node(
					'tr',
					[
						Builder.node('td', { width: 25, title: r.name }, [ Builder._text(r.score) ]),
						Builder.node('td', { title: r.name }, [ Builder._text(r.descript) ]),
					]
				)
			);
		}
		// set the score
		$('emailcheck_score').innerHTML = ary.score + '/' + ary.max;
		// set the scene
		$('emailcheck_table').className = ( rules > 0 ? 'adesk_block' : 'adesk_hidden' );
		$('spamloader').className = 'adesk_hidden';
		$('spamresult').className = 'adesk_block';
		$('spamform').className = 'adesk_hidden';
	} else {
		adesk_error_show(ary.message);
	}
}

{/literal}
