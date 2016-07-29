var campaign_message_str_noinfo = '{"Please enter something in the From Name and From Email fields before continuing."|alang|js}';
var campaign_message_str_nomail_from = '{"Please enter a valid email address in the From Email field before continuing."|alang|js}';
var campaign_message_str_nomail_reply2 = '{"Please enter a valid email address in the Reply To field before continuing."|alang|js}';
var campaign_message_str_nosubj = '{"Please enter something in the Subject field before continuing."|alang|js}';
var campaign_message_str_nobody = '{"Please enter something in the content area of your message before continuing."|alang|js}';
var campaign_message_str_deskrss_nofeed = '{"You do not have RSS personalization tags included into your messsage, or you have more than one RSS feed referenced. Please make sure that you have one RSS feed placed into your message before continuing."|alang|js}';
var campaign_message_str_deskrss_noloop = '{"It seems like you haven't entered any LOOP tags to display your RSS feed items. Please add LOOP tags to your message before continuing."|alang|js}';
var campaign_message_str_deskrss_mismatch = '{"It seems like you haven't entered valid RSS personalization tags. Some tags seem to be missing, and your RSS feed would not be properly displayed. Please correct this before continuing."|alang|js}';

var campaign_fetch_str_insert = '{"Insert"|alang|js}';
var campaign_fetch_str_save = '{"Save"|alang|js}';

adesk_editor_init_word_object.plugins += ",fullpage";
{jsvar var=$message name=message_obj};
{jsvar var=$message.html name=default_editor_value};

{literal}

function campaign_validate(aftersave) {
	if ( aftersave == "next" ) {
		if ($("campaign_fromname").value == "" || $("campaign_fromemail").value == "") {
			alert(campaign_message_str_noinfo);
			return false;
		}

		$("campaign_fromemail").value = adesk_str_trim($("campaign_fromemail").value);
		if (!adesk_str_email($("campaign_fromemail").value)) {
			alert(campaign_message_str_nomail_from);
			$("campaign_fromemail").focus();
			return false;
		}
		$("campaign_reply2").value = adesk_str_trim($("campaign_reply2").value);
		if ($("campaign_reply2").value != '' && !adesk_str_email($("campaign_reply2").value)) {
			alert(campaign_message_str_nomail_reply2);
			$("campaign_reply2").focus();
			return false;
		}

		if ($("fetchwhen").value == "now" && $("campaign_subject").value == "") {
			alert(campaign_message_str_nosubj);
			return false;
		}

		var html = adesk_form_value_get($("messageEditor"));
		if (strip_tags(html) == "" && !html.match(/<img/i) ) {
			alert(campaign_message_str_nobody);
			return false;
		}

		// deskrss checks
		if ( campaign_obj.type == 'deskrss' ) {
			if ( !adesk_str_is_url($("deskrss_url").value) ) {
				alert(strURLNotURL);
				$('deskrss_url').focus();
				return false;
			}
			// check if exactly one rss feed is found
			var occur_feed_opening = html.match(/%RSS-FEED\|/g);
			var occur_feed_closing = html.match(/%RSS-FEED%/g);
			var occur_loop_opening = html.match(/%RSS-LOOP\|/g);
			var occur_loop_closing = html.match(/%RSS-LOOP%/g);
			// check if ONLY ONE of feed opening and closing tags is found
			if ( !occur_feed_opening || !occur_feed_closing || occur_feed_opening.length != 1 || occur_feed_closing.length != 1 ) {
				alert(campaign_message_str_deskrss_nofeed);
				return false;
			}
			// check if NO loop tags are found
			if ( !occur_loop_opening || !occur_loop_closing ) {
				alert(campaign_message_str_deskrss_noloop);
				return false;
			}
			// check if any mismatched tags are found
			if ( occur_feed_opening.length != occur_feed_closing.length || occur_loop_opening.length != occur_loop_closing.length ) {
				alert(campaign_message_str_deskrss_mismatch);
				return false;
			}
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
adesk_dom_onload_hook(campaign_changed_safe);

function campaign_attachafile() {
	$("attachafile").hide();
	$("attachmentsBox").show();
}

function campaign_managetext(val) {
	$("campaign_managetextid").value = val;

	$("askmanagetext").hide();
	$("willmanagetext").hide();

	if (val)
		$("willmanagetext").show();
	else
		$("askmanagetext").show();
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
	$('personalize4').value = 'html';
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
	adesk_editor_insert($('personalize2').value, ( $('personalize4').value == 'html' ? nl2br(code) : code ));
}

// Conditional
function campaign_conditional_open() {
	// set type
	$('conditional4').value = 'html';
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
	if ( $('conditional4').value == 'html' ) {
		var ed = tinyMCE.activeEditor;
		ed.execCommand('mceInsertContent', false, nl2br(code));
	} else {
		adesk_form_insert_cursor($($('conditional2').value), code);
	}
}

function campaign_conditional_build() {
	// what type of code to build
	var type = $('conditional4').value;
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
function deskrss_add() {
	// what url to fetch
	var url = $('deskrss_url').value;
	if ( !adesk_str_is_url(url) ) {
		alert(strURLNotURL);
		$('deskrss_url').focus();
		return;
	}
	// how many to show
	if ( !adesk_ui_numbersonly($('deskrss_items')) ) {
		$('deskrss_items').value = 10;
	}

	$('deskrss_loading').show();

	// ajax call
	adesk_ajax_call_cb("awebdeskapi.php", "deskrss.deskrss_checkfeed", adesk_ajax_cb(deskrss_add_cb), adesk_b64_encode($("deskrss_url").value));
}

function deskrss_add_cb(ary) {

	$('deskrss_loading').hide();

	if ( ary.succeeded == 1 ) {

		// what url to fetch
		var url = $('deskrss_url').value;
		// how many items to loop through
		var loop = $('deskrss_items').value;
		// what to show
		var show = 'ALL'; // ALL/NEW

		var code = campaign_rss_build(url, show, loop);

		$('deskrss_preview').value = code;

		// flip the views
		$('deskrss_add').hide();
		$('deskrss_use').show();

		// push it into needed editor
		//adesk_editor_insert("messageEditor", nl2br(code));

		$('deskrss_preview').focus();
		$('deskrss_preview').select();
	} else {
		adesk_error_show(ary.message);
	}
}

function deskrss_reset() {
	$('deskrss_use').hide();
	$('deskrss_add').show();

	$('deskrss_url').value = 'http://';
	$('deskrss_items').value = 10;

	// try to take out the old one
	var content = adesk_form_value_get($('messageEditor'));
	content = content.replace(/%RSS-FEED\|.*%RSS-FEED%/, '');
	adesk_form_value_set($('messageEditor'), content);
}

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
	adesk_editor_insert("messageEditor", nl2br(code));
}

function campaign_deskrss_preview() {
	// build the code
	var code = campaign_deskrss_build();
	if ( code == '' ) return;
	code = nl2br(code);
	// push it into preview box
	$('deskrsspreview').value = code;
	$('deskrsspreviewbox').className = 'adesk_block';
}

function campaign_deskrss_build() {
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

	return campaign_rss_build(url, show, loop);
}

function campaign_rss_build(url, show, loop) {
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
	campaign_deskrss_open('html');
}

function campaign_fetch_open() {
	$('message_fetch').show();
}

function campaign_fetch_stop() {
	adesk_form_value_set($("messageEditor"), "");
	$("fetchurl").value = "http://";
	$("fetchwhat").value = "http://";
	$("fetchwhen").value = "now";
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

	$("fetchwhen").value = $("fetchnow").checked ? 'now' : 'send';
	$("fetchwhat").value = $("fetchurl").value;

	if ($("fetchsend").checked) {
		adesk_form_value_set($("messageEditor"), "fetch:" + $("fetchurl").value);

		$("fetchhelplink").href = $("fetchurl").value;
		$("fetchhelplink").innerHTML = $("fetchurl").value;

		$("message_fetch").hide();
		$("editordiv").hide();
		$("editorfetch").show();
		return;
	}

	// If we get here, that means fetchnow was checked.
	adesk_ajax_call_cb("awebdeskapi.php", "message.message_fetch_url", adesk_ajax_cb(campaign_fetch_insert_cb), adesk_b64_encode($("fetchurl").value), "html");
}

function campaign_fetch_insert_cb(ary) {
	if (ary.data) {
		adesk_form_value_set($("messageEditor"), ary.data);
	}

	$("message_fetch").hide();
	$("editordiv").show();
	$("editorfetch").hide();
}

// Editor functions
function campaign_message_toggle_editor(id, action, settings) {
	if ( action == adesk_editor_is(id + 'Editor') ) return false;
	adesk_editor_toggle(id + 'Editor', settings);
	$(id + 'EditorLinkOn').className  = ( action ? 'currenttab' : 'othertab' );
	$(id + 'EditorLinkOff').className = ( !action ? 'currenttab' : 'othertab' );
	if ( action != ( adesk_js_admin.htmleditor == 1 ) )
		$(id + 'EditorLinkDefault').show();
	else
		$(id + 'EditorLinkDefault').hide();
	/*
	if ( !$(id + 'Editor') ) tmpEditorContent = adesk_form_value_get($(id + '_form')); else // heavy hack!!!
	tmpEditorContent = adesk_form_value_get($(id + 'Editor'));
	*/
	return false;
}

function campaign_message_setdefaulteditor(id) {
	var isEditor = adesk_editor_is(id + 'Editor');
	if ( isEditor == ( adesk_js_admin.htmleditor == 1 ) ) return false;
	// send save command
	// save new admin limit remotelly
	adesk_ajax_call_cb('awebdeskapi.php', 'user.user_update_value', null, 'htmleditor', ( isEditor ? 1 : 0 ));
	$(id + 'EditorLinkDefault').hide();
	adesk_js_admin.htmleditor = ( isEditor ? 1 : 0 );
	return false;
}

{/literal}
