var campaign_message_str_noinfo = '{"Please enter something in the From Name and From Email fields before continuing."|alang|js}';
var campaign_message_str_nomail_from = '{"Please enter a valid email address in the From Email field before continuing."|alang|js}';
var campaign_message_str_nomail_reply2 = '{"Please enter a valid email address in the Reply To field before continuing."|alang|js}';
var campaign_message_str_nosubj = '{"Please enter something in the Subject field before continuing."|alang|js}';
var campaign_message_str_nobody = '{"Please enter something in the content area of your message before continuing."|alang|js}';

var campaign_message_str_toofewmessages = '{"You must have at least two messages in order to continue with your split-test campaign."|alang|js}';
//var campaign_message_str_toomanymessages = '{"You have too many messages for a winner to be selected; you must delete some of your messages to continue."|alang|js}';
var campaign_message_str_reallydelete = '{"Are you sure you want to delete this message?"|alang|js}';

var campaign_fetch_str_insert = '{"Insert"|alang|js}';
var campaign_fetch_str_save = '{"Save"|alang|js}';

var campaign_split_str_notnumber   = '{"Only numbers between 0 and 100 are allowed."|alang|js}';
var campaign_split_str_notinrange  = '{"You can not enter zero (0), nor a number greater than a ninety nine (99)."|alang|js}';
var campaign_split_str_overhundred = '{"The total of all messages must be under 100, so that the winner message can be sent to rest."|alang|js}';

adesk_editor_init_word_object.plugins += ",fullpage";
{jsvar var=$message name=message_obj};
{jsvar var=$message.html name=default_editor_value};

var campaign_messagecount = 0;
{foreach from=$tabs item=e}
campaign_messagecount++;
{/foreach}

{literal}

var post_fixed = false;
function campaign_fixpost() {
	if ( post_fixed ) return;
	post_fixed = true;
	$$(".splitratioclass").each(function(e) { e.name = e.name + "[]"; });
	$$(".splitmessageidclass").each(function(e) { e.name = e.name + "[]"; });
}

function campaign_navigate(action, m, from) {
	$("campaign_post_action").value = action;
	$("campaign_post_m").value = m;
	$("campaign_post_from").value = from;

	campaign_safe();
	if (typeof campaign_fixpost == "function")
		campaign_fixpost();
	$("campaignform").submit();
}

function campaign_validate(aftersave) {
	if ( aftersave == "next" ) {
		if (campaign_messagecount < 2) {
			alert(campaign_message_str_toofewmessages);
			return false;
		}

		if ($("splittypewinner").checked) {
			var messages = $$(".splitratioclass");
			for (var i = 0, total = 0; i < messages.length; i++) {
				message_value = parseInt(messages[i].value, 10);
				if ( isNaN(message_value) ) {
					alert(campaign_split_str_notnumber);
					return false;
				} else if ( !message_value || message_value > 99 ) {
					alert(campaign_split_str_notinrange);
					return false;
				}
				total += message_value;
			}

			if (total >= 100) {
				alert(campaign_split_str_overhundred);
				return false;
			}
		}

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

	$("fetchwhen").value = $("fetchnow").checked == 'now' ? 'now' : 'send';
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

// Split work


function message_split_sumup() {
	var total = 0;
	var rel = $('messagesplitlist');
	var inputs = rel.getElementsByTagName('input');
	for ( var i = 0; i < inputs.length; i++ ) {
		if ( inputs[i].type == 'hidden' ) continue;
		var perc = parseInt(inputs[i].value, 10);
		if ( perc == 0 ) return -1;
		total += perc;
	}alert(total);
	return total;
}

function message_split_recalculate_input(obj) {
	if ( !adesk_ui_numbersonly(obj) ) {
		alert(campaign_split_str_notnumber);
		obj.focus();
		return;
	}
	var splitsum = message_split_sumup();
	if ( splitsum == -1 ) {
		alert(campaign_split_str_notinrange);
		obj.focus();
		return;
	}
	if ( splitsum < 1 || splitsum > 99 ) {
		alert(campaign_split_str_overhundred);
		obj.focus();
		return;
	}

	message_split_recalculate();
}

function message_split_recalculate() {
	total = 0;
	// all good, go through all and set bars
	var rel = $('messagesplitlist');
	var inputs = rel.getElementsByTagName('input');
	for ( var i = 0; i < inputs.length; i++ ) {
		if ( inputs[i].type == 'hidden' ) continue;
		var perc = parseInt(inputs[i].value, 10);
		//var id = parseInt(inputs[i].name.replace('splitratio[').replace(']'), 10);
		var offset = Math.round(total * 3);
		var width = Math.round(perc * 3);
		// update bar(s)
		var tr = inputs[i].parentNode.parentNode;
		var td = tr.getElementsByTagName('td')[2];
		var bar = td.getElementsByTagName('div')[1];
		bar.style.width = width + 'px';
		bar.style.marginLeft = offset + 'px';
		total += perc;
	}
	message_winner_set(perc, total);
}

function message_winner_set(perc, total) {
	var perc = 100 - total;
	var offset = Math.round(total * 3);
	var width = Math.round(perc * 3);
	$('winnerratio').value = perc;
	$('winnerbar').style.width = width + 'px';
	$('winnerbar').style.marginLeft = offset + 'px';
}


function campaign_split_type(val) {
	var total = $$(".splitratioclass").length;

	if (val == "winner") {
		$("splittypewinnerbox").show();
		$("winneronly").show();
		$("splitwinnertyperead").checked = true;
		$$(".splitratioclass").each(function(e) { e.disabled = false; if (total > 6) e.value = 5; else e.value = 10; });

		if (total > 6)
			$("winnerratio").value = Math.max(100 - (total * 5), 0);
		else
			$("winnerratio").value = 100 - (total * 10);
	} else {
		$("splittypewinnerbox").hide();
		$("winneronly").hide();
		if (total > 0) {
			var loparcel = Math.floor(100 / total);
			var hiparcel = Math.ceil(100 / total);
			$$(".splitratioclass").each(function(e) { e.disabled = true; --total; if (total > 0) e.value = loparcel; else e.value = hiparcel;});
		}
	}
}
/*
function campaign_split_saneval(val) {
	val = parseInt(val, 10);

	if (val == NaN)
		return 0;

	if (val < 0)
		return 0;

	if (val > 100)
		return 100;

	return val;
}

function campaign_split_update(id, val) {
	if ($("splittypewinner").checked) {
		campaign_split_update_winner(id, val);
		return;
	}

	var ourbar = sprintf("splitbar%s", id);
	var ourinput = sprintf("splitratio%s", id);
	var inputs = $$(".splitratioclass");
	var allotment = 100;
	var total;
	var parcel = 0;

	// Fix the input if they put in some weirdo number.
	val = campaign_split_saneval(val);
	$(ourinput).value = val;
	$(ourbar).style.width = sprintf("%spx", val * 3);
	allotment -= val;

	// Parcel should be the number we spread evenly to all other inputs.
	// Remember val can't be > 100, so allotment can never be < 0.
	if (allotment == 0)
		parcel = 0;
	else
		parcel = Math.floor(allotment / (inputs.length - 1));

	for (var i = 0; i < inputs.length; i++) {
		var match = inputs[i].id.match(/splitratio(\d+)/);

		if (!match)
			continue;

		var thisbar = sprintf("splitbar%s", match[1]);

		// If this isn't us, then fix the input.
		if (thisbar != ourbar) {
			inputs[i].value = Math.min(allotment, parcel);
			allotment -= parcel;

			$(thisbar).style.width = sprintf("%spx", inputs[i].value * 3);
		}
	}
}

function campaign_split_update_winner(id, val) {
	var ourbar = sprintf("splitbar%s", id);
	var ourinput = sprintf("splitratio%s", id);
	var inputs = $$(".splitratioclass");
	var allotment = 100;

	// Fix the input if they put in some weirdo number.
	val = campaign_split_saneval(val);

	$(ourinput).value = val;
	$(ourbar).style.width = sprintf("%spx", val * 3);

	var ratio_input_ids_other = [];
	var ratio_input_value_this = 0;

	for (var i = 0; i < inputs.length; i++) {
		var match = inputs[i].id.match(/splitratio(\d+)/);

		if (!match)
			continue;

		// if this input is NOT the one we just edited, add to array
		if ( match[1] != id ) {
			ratio_input_ids_other.push(inputs[i].id);
		}
		else {
			// the value of the input box we are currently editing
			ratio_input_value_this = inputs[i].value;
		}

		var thisbar = sprintf("splitbar%s", match[1]);
		allotment -= inputs[i].value;

		$(thisbar).style.width = sprintf("%spx", inputs[i].value * 3);
	}

	if (allotment < 0) {
		var remaining = 100 - ratio_input_value_this;
		var remaining_equal = campaign_split_saneval( remaining / ratio_input_ids_other.length );
		// loop through all other ratio inputs that were NOT just edited
		for (var i = 0; i < ratio_input_ids_other.length; i++) {
			var current = ratio_input_ids_other[i];
			$(current).value = remaining_equal;
		}
		allotment = 0;
	}

	$("winnerratio").value = allotment;
	$("winnerbar").style.width = sprintf("%spx", allotment * 3);
}
*/
{/literal}
