var campaign_message_str_noinfo = '{"Please enter something in the From Name and From Email fields before continuing."|alang|js}';
var campaign_message_str_nomail_from = '{"Please enter a valid email address in the From Email field before continuing."|alang|js}';
var campaign_message_str_nomail_reply2 = '{"Please enter a valid email address in the Reply To field before continuing."|alang|js}';
var campaign_message_str_nosubj = '{"Please enter something in the Subject field before continuing."|alang|js}';
var campaign_message_str_nobody = '{"Please enter something in the content area of your message before continuing."|alang|js}';

var campaign_message_str_toofewmessages = '{"You must have at least two messages in order to continue with your split-test campaign."|alang|js}';

var campaign_messagecount = 0;
{foreach from=$tabs item=e}
campaign_messagecount++;
{/foreach}

var message_obj = {jsvar var=$message};
{jsvar var=$message.text name=default_editor_value};

{literal}

function campaign_validate(aftersave) {
	if ( aftersave == "next" ) {
		if (campaign_messagecount < 2) {
			alert(campaign_message_str_toofewmessages);
			return false;
		}

		var text = $("messageEditor").value;
		if (text == "") {
			alert(campaign_message_str_nobody);
			return false;
		}
	}

	return true;
}

function campaign_changed() {
	if (adesk_form_value_get($("messageEditor")) != default_editor_value && !default_editor_value.match(/^fetch:/)) {
		return true;
	}

	return false;
}

function campaign_changed_safe() {
	default_editor_value = adesk_form_value_get($("messageEditor"));
}

// set onload
//adesk_dom_onload_hook(campaign_changed_safe);

function campaign_navigate(action, m, from) {
	$("campaign_post_action").value = action;
	$("campaign_post_m").value = m;
	$("campaign_post_from").value = from;

	campaign_safe();
	if (typeof campaign_fixpost == "function")
		campaign_fixpost();
	$("campaignform").submit();
}

function campaign_attachafile() {
	$("attachafile").hide();
	$("attachmentsBox").show();
}

// Personalization
function campaign_personalization_show(id) {
	$("personalize_subinfo").hide();
	$("personalize_message").hide();
	$("personalize_socmedia").hide();
	$("personalize_other").hide();

	$("subinfo_tab").className = "othertab";
	$("message_tab").className = "othertab";
	$("socmedia_tab").className = "othertab";
	$("other_tab").className = "othertab";

	switch (id) {
		case "personalize_subinfo":
			$("subinfo_tab").className = "currenttab"; break;
		case "personalize_message":
			$("message_tab").className = "currenttab"; break;
		case "personalize_socmedia":
			$("socmedia_tab").className = "currenttab"; break;
		case "personalize_other":
			$("other_tab").className = "currenttab"; break;
	}

	$(id).show();
}

function campaign_personalization_open() {
	$('personalize4').value = 'text';
	$('personalize2').value = 'messageEditor';

	$('message_personalize').toggle();
}

function campaign_personalize_build(val) {
	// what type of code to build
	var type = $('personalize4').value;
	// now handle custom (html?) cases
	var text = '';
	// only today tag should be reset
	if ( val.match( /^%TODAY[+-]\d+%$/ ) ) {
		val = '%TODAY*%';
	}
	if ( val == '%CONFIRMLINK%' ) {
		text = strConfirmLinkText;
	} else if ( val == '%UNSUBSCRIBELINK%' ) {
		text = strUnsubscribeText;
	} else if ( val == '%UPDATELINK%' ) {
		text = strSubscriberUpdateText;
	} else if ( val == '%WEBCOPY%' ) {
		text = strWebCopyText;
	} else if ( val == '%FORWARD2FRIEND%' ) {
		text = strForward2FriendText;
	} else if ( val == '%SOCIALSHARE%' ) {
		//text = strForward2FriendText; // don't prompt for anything, just use val
	} else if ( val == '%TODAY*%' ) {
		var entered = prompt(strEnterRange, '+1');
		if ( !entered ) return;
		if ( !entered.match( /^[-+]?\d+$/ ) ) {
			alert(strEnterRangeInvalid);
			return;
		}
		if ( !entered.match(/^[-+].*$/) ) {
			entered = '+' + entered;
		}
		val = '%TODAY' + entered + '%';
	}
	if ( type == 'html' && text != '' ) {
		entered = prompt(strEnterText, text);
		if ( !entered ) entered = text;
		val = '<a href="' + val + '">' + entered + '</a>';
	}
	return val;
}

function campaign_personalization_insert(value) {
	if ( value == '' ) {
		alert(strPersMissing);
		return;
	}
	// close the modal
	$('message_personalize').toggle();
	// build the code
	var code = campaign_personalize_build(value);
	if ( code == '' ) return;
	// push it into needed editor
	adesk_form_insert_cursor($("messageEditor"), code);
}

// Conditional
function campaign_conditional_open() {
	// set type
	$('conditional4').value = 'text';
	$('conditional2').value = 'messageEditor';
	// set data
	$('conditionalfield').value = '';
	$('conditionalcond' ).selectedIndex = 0;
	$('conditionalvalue').value = '';
	// open modal
	$('message_conditional').toggle();
}

function campaign_conditional_insert() {
	if ( $('conditionalfield').value == '' ) {
		alert(strPersMissing);
		$('conditionalfield').focus();
		return;
	}
	// close the modal
	$('message_conditional').toggle();
	// build the code
	var code = campaign_conditional_build();
	if ( code == '' ) return;
	// push it into needed editor
	adesk_form_insert_cursor($("messageEditor"), code);
}

function campaign_conditional_build() {
	// what type of code to build
	var type = "text";
	// what value to use
	var field = $('conditionalfield').value;
	var cond  = $('conditionalcond' ).value;
	var value = $('conditionalvalue').value;
	field = '$' + field.replace(/%/g, '').replace(/-/g, '_');
	value = value.replace(/%/g, '~PERCENT~');
	value = "'" + value.replace(/'/g, '\\\'') + "'";
	if ( cond.indexOf('CONTAINS') != -1 ) {
		var expr = 'in_string(' + value + ', ' + field + ')';
		if ( cond == 'DCONTAINS' ) expr = '!' + expr;
	} else {
		var expr = field + ' ' + cond + ' ' + value;
	}
	var code =
		'%IF ' + expr + '%\n' + editorConditionalText + '\n%ELSE%\n' + editorConditionalElseText + '\n%/IF%\n'
	;
	return code;
}

// ActiveRSS
function campaign_deskrss_loop_changed() {
	window.setTimeout(
		function() {
			adesk_ui_numbersonly($('deskrssloop'), true);
		},
		100
		);
}

function campaign_deskrss_open(type, insertObj) {
	if ( !insertObj ) insertObj = '';
	// set data
	$('deskrssurl').value = 'http://';
	$('deskrssloop').value = '10';
	$('deskrsspreviewbox').className = 'adesk_hidden';
	// open modal
	$('message_deskrss').toggle();
}

function campaign_deskrss_insert() {
	// close the modal
	$('message_deskrss').toggle();
	// build the code
	var code = campaign_deskrss_build();
	if ( code == '' ) return;
	// push it into needed editor
	adesk_form_insert_cursor($("messageEditor"), code);
}

function campaign_deskrss_preview() {
	// build the code
	var code = campaign_deskrss_build();
	if ( code == '' ) return;
	// push it into preview box
	$('deskrsspreview').value = code;
	$('deskrsspreviewbox').className = 'adesk_block';
}

function campaign_deskrss_build() {
	// what type of code to build
	var type = "text";
	// what url to fetch
	var url = $('deskrssurl').value;
	if ( !adesk_str_is_url(url) ) {
		alert(strURLNotURL);
		$('deskrssurl').focus();
		return '';
	}
	// how many to show
	if ( !adesk_ui_numbersonly($('deskrssloop')) ) {
		$('deskrssloop').value = 0;
	}
	var loop = $('deskrssloop').value;
	// what to show
	var show = 'ALL'; // ALL/NEW
	// what are line breaks
	var nl = ( type == 'html' ? '<br />\n' : '\n' );
	var code =
		'%RSS-FEED|URL:' + url + '|SHOW:' + show + '%\n\n' + // start feed section
		'%RSS:CHANNEL:TITLE%\n\n' + // print out title
		'%RSS-LOOP|LIMIT:' + loop + '%\n\n' + // start item section
		'%RSS:ITEM:DATE%\n' + // within a section
		'%RSS:ITEM:TITLE%\n' +
		'%RSS:ITEM:SUMMARY%\n' +
		'%RSS:ITEM:LINK%\n\n' +
		'%RSS-LOOP%\n\n' +
		'%RSS-FEED%\n' // end section
	;
	return code;
}

function adesk_editor_deskrss_click() {
	campaign_deskrss_open('text');
}

function campaign_fetch_open() {
	$('message_fetch').show();
}

function campaign_fetch_stop() {
	$("messageEditor").value = "";
	$("fetchurl").value = "http://";
	$("editorfetch").hide();
	$("editordiv").show();
}

function campaign_fetch_radiochoose(val) {
	if (val == "now")
		$("message_fetch_ok").value = campaign_fetch_str_insert;
	else
		$("message_fetch_ok").value = campaign_fetch_str_save;
}

function campaign_fetch_insert() {
	if ( !adesk_str_is_url($("fetchurl").value) ) {
		alert(strURLNotURL);
		$('fetchurl').focus();
		return;
	}

	$("fetchwhen").value = $("fetchnow").checked == 'now' ? 'now' : 'send';
	$("fetchwhat").value = $("fetchurl").value;

	if ($("fetchsend").checked) {
		$("messageEditor").value = "fetch:" + $("fetchurl").value;

		$("fetchhelplink").href = $("fetchurl").value;
		$("fetchhelplink").innerHTML = $("fetchurl").value;

		$("message_fetch").hide();
		$("editordiv").hide();
		$("editorfetch").show();
		return;
	}

	// If we get here, that means fetchnow was checked.
	adesk_ajax_call_cb("awebdeskapi.php", "message.message_fetch_url", adesk_ajax_cb(campaign_fetch_insert_cb), adesk_b64_encode($("fetchurl").value), "text");
}

function campaign_fetch_insert_cb(ary) {
	if (ary.data) {
		$("messageEditor").value = ary.data;
	}

	$("message_fetch").hide();
	$("editordiv").show();
	$("editorfetch").hide();
}

{/literal}
