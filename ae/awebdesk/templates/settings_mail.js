// string vars

var msgEmailSent = '{"Email Reported as SENT. Please check your inbox."|alang|js}';
var msgEmailNotSent = '{"Email Reported as NOT SENT. Please modify the configuration options."|alang|js}';
var msgEmailSending = '{"Sending test email. Please wait..."|alang|js}';

var jsAPIfailed = '{"Call timed out. Probable cause: mail configuration not correct."|alang|js}';


var msgWizTryOther = '{"Would you like to try some other mailing method?"|alang|js}';

var jsOrderNotSaved = '{"You have not saved your order changes."|alang|js}';

var jsTitleEdit = '{"&gt; Edit"|alang|js}';
var jsTitleAdd  = '{"&gt; Add"|alang|js}';



// app vars
var myProcessingTimer = false;
var triedMethods = new Array();


{jsvar name=adesk_mailer var=$mailer}
{jsvar name=rotator var=$rotator}
{jsvar name=cfg var=$cfg}
{jsvar name=blank var=$blank}
{jsvar name=mailconns var=$mailconnections}
{jsvar name=mailconnscnt var=$mailconnCnt}
{jsvar name=plink var=$plink}



{literal}
// set unload
window.onbeforeunload = null;
window.onbeforeunload = function() {
	if ( $('save_order') && $('save_order').disabled == false ) {
		return jsOrderNotSaved;
	}
}

// functions

function my_show(msg) {
	$('testEmailMsg').innerHTML = msg;
}

function my_loading_show(msg) {
	if ( $('adesk_loading_bar') ) adesk_loader_show(msg);
	else my_show(msg);
}

function my_result_show(msg) {
	if ( $('adesk_result_bar') ) adesk_result_show(msg);
	else my_show(msg);
}

function my_error_show(msg) {
	if ( $('adesk_error_bar') ) adesk_error_show(msg);
	else my_show(msg);
}

function stopAPIcall() {
	window.clearTimeout(myProcessingTimer); // we don't need this
	// display what's going on
	my_error_show(jsAPIfailed);
	// re-enable button
	$('testEmailButton').disabled = false;
}
function startAPIcall() {
	myProcessingTimer = window.setTimeout(stopAPIcall, 15 * 1000);
	// display what's going on
	my_loading_show(msgEmailSending);
	// disable button
	$('testEmailButton').disabled = true;
}

function handleAPIcallbackTEXT(txt) {
	// reset the timer
	window.clearTimeout(myProcessingTimer);
	// disable button
	$('testEmailButton').disabled = false;
	var tmp = jsErrorMailerBarMessage;
	jsErrorMailerBarMessage = '';
	adesk_ui_error_mailer(txt);
	my_error_show(tmp);
	jsErrorMailerBarMessage = tmp;
}

function handleAPIcallback(xml) {
	// now reset the text handler
	adesk_ajax_handle_text = null;
	var ary = adesk_dom_read_node(xml, ( paginator_b64 ? ( paginator_b64 ? adesk_b64_decode : null ) : null ));
	// reset the timer
	window.clearTimeout(myProcessingTimer);
	// check if it arrived late
	var arrivedLate = $('testEmailButton').disabled == false;
	// if processing is shown, hide it, since we got our response back
	if ( !arrivedLate ) {
		// re-enable button
		$('testEmailButton').disabled = false;
	}
	// display what's going on
	if ( ary.succeeded && ary.succeeded == 1 ) {
		my_result_show(msgEmailSent);
	} else {
		my_error_show(msgEmailNotSent);
	}
	/*
		wizard
	*/
	if ( !ary.succeeded ) {
/*		triedMethods[] = ary.type;
		var tryOther = confirm(msgWizTryOther);
		if ( tryOther ) {
			if ( ary.type == 3 ) {
				// try other smtp
			} else if ( type == 1 ) {
				// try smtp
			} else if ( type == 0 ) {
				// try sendmail
			}
		}
*/	}
}


function sendTestEmail() {
	var email = adesk_b64_encode($('testEmailField').value);
	/*
		get mailing type
	*/
	// assume Mail()
	var type = 0;
	if ( $('smsmtp').checked ) type = 1;
	if ( $('smsendmail').checked ) type = 3;
	// gather vars needed
	var host = adesk_b64_encode($('smhost').value);
	var port = adesk_b64_encode($('smport').value);
	var user = adesk_b64_encode($('smuser').value);
	var pass = adesk_b64_encode($('smpass').value);
	var encr = adesk_b64_encode( ( adesk_mailer == 'swift' ? $('smenc').value : 8 ) );
	var pop3 = adesk_b64_encode( ( adesk_mailer == 'swift' ? ($('smpop3b4').checked ? "1" : "0") : "0" ) );
	//var thres = adesk_b64_encode( ( rotator ? $('smthres').value : 50 ) );
	// make ajax call
	startAPIcall();
	var baseURL = ( document.location.href.match('/manage/desk.php?') ? '../' : '' );
	adesk_ajax_handle_text = handleAPIcallbackTEXT;
	adesk_ajax_call_cb(baseURL + 'awebdesk/api/manage/testmail.php', 'testmail', handleAPIcallback, email, type, host, port, user, pass, encr, pop3/*, thres*/);
}

function mailconn_show(id) {
	var src = ( mailconns[id] ? mailconns[id] : blank );
	// set id
	$('mailconnid').value = src.id;
	// set type
	if ( src.type == 0 )
		$('smmail').checked = true;
	else if ( src.type == 1 )
		$('smsmtp').checked = true;
	else /*if ( src.type == 3 )*/
		$('smsendmail').checked = true;
	// hide smtp if not used
	$('smhost').value = src.host;
	$('smport').value = src.port;
	$('smuser').value = src.user;
	$('smpass').value = src.pass;
	if ( adesk_mailer == 'swift' ) {
		$('smenc').value = src.encrypt;
		$('smpop3b4').checked = ( src.pop3b4smtp == 1 );
	}
	if ( rotator ) {
		$('smthres').value = src.threshold;
		if ( typeof src.frequency != 'undefined' && $('sdfreq') ) {
			$('sdfreq').value  = ( src.frequency > 0 ? src.frequency : '' );
			$('sdnum').value   = ( src.pause > 0 ? src.pause : '' );
			$('sdlim').value   = ( src.limit > 0 ? src.limit : '' );
			$('sdspan').value  = src.limitspan;
			//$('sd').checked = ( src.frequency + src.pause > 0 );
			$('sdbox').className  = ( src.frequency + src.pause > 0 ? 'adesk_block' : 'adesk_hidden' );
			//$('lim').checked = ( src.limit > 0 );
			$('limbox').className = ( src.limit > 0 ? 'adesk_block' : 'adesk_hidden' );

			if ( src.frequency + src.pause > 0 ) {
				$('sd').checked = true;
			} else if ( src.limit > 0 ) {
				$('lim').checked = true;
			} else {
				$('dontstop').checked = true;
			}
		}
	}
	// ihook call
	if ( typeof mailconn_load_post == 'function' ) {
		mailconn_load_post(src);
	}
	$('mailconnItem').className = 'h2_wrap';
	$('mailconnList').className = 'adesk_hidden';
	$('smtpInfo').className = ( src.type == 1 ? 'adesk_table_rowgroup' : 'adesk_hidden' );
	$('rotatorEdit').className = ( mailconnscnt > 1 ? 'adesk_table_rowgroup' : 'adesk_hidden' );
	$('action2title').innerHTML = ( src.id == 0 ? jsTitleAdd : jsTitleEdit );
	adesk_ui_anchor_set( ( src.id == 0 ? 'add' : 'edit' ) + '-' + src.id );
}

function mailconn_hide() {
	$('mailconnItem').className = 'adesk_hidden';
	$('mailconnList').className = 'h2_wrap';
	$('action2title').innerHTML = '';
	adesk_ui_anchor_set('list-01');
}

function mailconn_added(ary) {
	var type = ( ary.type == 1 ? 'SMTP' : ( ary.type == 3 ? 'Sendmail' : 'Mail()' ) );
	var host = ( ary.type == 1 ? ary.host + ':' + ary.port : jsNotAvailable );
	var user = ( ary.type == 1 ? ary.user : jsNotAvailable );
	var cells = [
		// multicheck
		Builder.node(
			'td',
			{ align: "center" },
			[
				Builder.node(
					'input',
					{ name: "multi[]", type: "checkbox", value: ary.id, onchange: "adesk_form_check_selection_none(this, $('acSelectAllCheckbox'));" }
				)
			]
		),
		// sorter
		Builder.node(
			'td',
			{ align: "center", style: "cursor:move;" },
			[
				Builder.node(
					'img',
					{ src: plink + "/awebdesk/media/drag_icon.gif", align: "absmiddle" }
				)
			]
		),
		// options
		Builder.node(
			'td',
			{ },
			[
				Builder._text(' '),
				Builder.node('a', { href: "#", onclick: "return mailconn_delete('" + ary.id + "');" }, [ Builder._text(jsOptionDelete) ] ),
				Builder._text(' '),
				Builder.node('a', { href: "#edit-" + ary.id, onclick: "return mailconn_show('" + ary.id + "');" }, [ Builder._text(jsOptionEdit) ] )
			]
		),
		// type
		Builder.node('td', { }, [ Builder._text(type) ] ),
		// host
		Builder.node('td', { }, [ Builder._text(host) ] ),
		// user
		Builder.node('td', { }, [ Builder._text(user) ] ),
		// threshold
		Builder.node('td', { align: "center" }, [ Builder._text(ary.threshold) ] )
	];

	var newRow = Builder.node('tr', { className: "adesk_table_row" }, cells);
	$('mailconnListBody').appendChild(newRow);
	mailconnscnt++;
	// set the list buttons if adding the second one
	if ( $('listButtons').className != 'adesk_inline' ) {
		// show list buttons
		$('listButtons').className = 'adesk_inline';
		// show threshold for first mailer
		var columns = $('mailconnDefault').getElementsByTagName('td');
		var thresColumn = columns[columns.length - 1];
		thresColumn.innerHTML = mailconns[1].threshold;
	}
}

function mailconn_update(tr, ary) {
	var td = tr.getElementsByTagName('td');
	// first three are multi, sorter and options
	// type
	var fieldValue = ( ary.type == 1 ? 'SMTP' : ( ary.type == 3 ? 'Sendmail' : 'Mail()' ) );
	if ( td[3].innerHTML != fieldValue ) td[3].innerHTML = fieldValue;
	// info (host:port)
	fieldValue = ( ary.type == 1 ? ary.host + ':' + ary.port : jsNotAvailable );
	if ( td[4].innerHTML != fieldValue ) td[4].innerHTML = fieldValue;
	// auth (user)
	fieldValue = ( ary.type == 1 ? ary.user : jsNotAvailable );
	if ( td[5].innerHTML != fieldValue ) td[5].innerHTML = fieldValue;
	// threshold
	fieldValue = ( mailconnscnt > 1 ? ary.threshold : jsNotAvailable );
	if ( td[6].innerHTML != fieldValue ) td[6].innerHTML = fieldValue;
}

function mailconn_saved(ary) {
	if ( ary.id == 1 ) {
		mailconn_update($('mailconnDefault'), ary);
		return;
	}
	var daddy = $('mailconnListBody');
	var inputs = daddy.getElementsByTagName('input');
	// find the one to change
	for ( var i = 0; i < inputs.length; i++ ) {
		if ( inputs[i].type == 'checkbox' && inputs[i].name == 'multi[]' ) {
			if ( inputs[i].value == ary.id ) {
				// found the one to change
				var tr = inputs[i].parentNode.parentNode;
				mailconn_update(tr, ary);
				// done, break loop
				break;
			}
		}
	}
}

function mailconn_save() {
	var post = {};
	post.id = adesk_b64_encode($('mailconnid').value);
	/*
		get mailing type
	*/
	// assume Mail()
	post.type = 0;
	if ( $('smsmtp').checked ) post.type = 1;
	if ( $('smsendmail').checked ) post.type = 3;
	// gather vars needed
	post.host = adesk_b64_encode($('smhost').value);
	post.port = adesk_b64_encode($('smport').value);
	post.user = adesk_b64_encode($('smuser').value);
	post.pass = adesk_b64_encode($('smpass').value);
	post.enc = adesk_b64_encode( ( adesk_mailer == 'swift' ? $('smenc').value : 8 ) );
	post.pop3b4 = adesk_b64_encode( ( ( adesk_mailer == 'swift' && $('smpop3b4').checked ) ? "1" : "0" ) ) ;
	post.thres = adesk_b64_encode( ( rotator ? $('smthres').value : 50 ) );
	post.freq = adesk_b64_encode( ( rotator && $('sdfreq') ? $('sdfreq').value : 50 ) );
	post.num = adesk_b64_encode( ( rotator && $('sdnum') ? $('sdnum').value : 5 ) );
	post.lim = adesk_b64_encode( ( rotator && $('sdlim') ? $('sdlim').value : 0 ) );
	post.span = adesk_b64_encode( ( rotator && $('sdspan') ? $('sdspan').value : 'hour' ) );
	// ihook call
	if ( typeof mailconn_save_post == 'function' ) {
		post = mailconn_save_post(post);
		if ( !post ) return;
	}
	// make ajax call
	adesk_ui_api_call();
	var baseURL = ( document.location.href.match('/manage/desk.php?') ? '../' : '' );
	adesk_ajax_post_cb(baseURL + 'awebdesk/api/manage/mailconn_save.php', 'mailconn_save', mailconn_save_callback, post);
}

function mailconn_save_callback(xml, txt) {
	var ary = adesk_dom_read_node(xml, ( paginator_b64 ? adesk_b64_decode : null ));
	adesk_ui_api_callback();
	if ( !ary.succeeded ) {
		adesk_error_show();
	} else {
		adesk_result_show();
		mailconns[ary.id] = ary;
		if ( rotator ) {
			mailconn_hide();
			if ( ary.name == 'add' ) {
				mailconn_added(ary);
			} else {
				mailconn_saved(ary);
			}
			if ( typeof tryDotFix != 'undefined' ) mailconn_dotfix(ary.id);
		}
	}
}

function mailconn_removed(ids) {
	var daddy = $('mailconnListBody');
	var inputs = daddy.getElementsByTagName('input');
	// find the ones to remove
	// collect them
	var removalQ = [ ];
	for ( var i = 0; i < inputs.length; i++ ) {
		if ( inputs[i].type == 'checkbox' && inputs[i].name == 'multi[]' ) {
			if ( adesk_array_has(ids, inputs[i].value) ) {
				// found the one to change
				var tr = inputs[i].parentNode.parentNode;
				removalQ.push(tr);
			}
		}
	}
	// now remove them
	for ( var i = 0; i < removalQ.length; i++ ) {
		removalQ[i].parentNode.removeChild(removalQ[i]);
		mailconnscnt--;
	}
}


function mailconn_delete_multiple() {
	if ( !adesk_form_check_selection_check($('mailconnListBody'), 'multi[]', jsNothingSelected, jsNothingFound) ) return false;
	mailconn_delete(adesk_form_check_selection_get($('mailconnListBody'), 'multi[]').join(','));
	return false;
}

function mailconn_delete(id) {
	if ( !confirm(jsAreYouSure) ) return false;
	// make ajax call
	adesk_ui_api_call();
	var baseURL = ( document.location.href.match('/manage/desk.php?') ? '../' : '' );
	adesk_ajax_call_cb(baseURL + 'awebdesk/api/manage/mailconn_delete.php', 'mailconn_delete', mailconn_delete_callback, id);
	return false;
}

function mailconn_delete_callback(xml, txt) {
	var ary = adesk_dom_read_node(xml, ( paginator_b64 ? adesk_b64_decode : null ));
	adesk_ui_api_callback();
	if ( !ary.succeeded ) {
		adesk_error_show();
	} else {
		adesk_result_show(ary.message);
		var ids = ary.ids.toString().split(',');
		for ( var i = 0; i < ids.length; i++ ) {
			mailconns = adesk_array_remove_key(ids[i], mailconns);
		}
		mailconn_removed(ids);
	}
}

function mailconn_dotfix(id) {
	// make ajax call
	adesk_ui_api_call();
	var baseURL = ( document.location.href.match('/manage/desk.php?') ? '../' : '' );
	adesk_ajax_call_cb(baseURL + 'awebdesk/api/manage/mailconn_dotfix.php', 'mailconn_dotfix', mailconn_dotfix_callback, id);
	return false;
}

function mailconn_dotfix_callback(xml, txt) {
	var ary = adesk_dom_read_node(xml, ( paginator_b64 ? adesk_b64_decode : null ));
	adesk_ui_api_callback();
	if ( !ary.succeeded ) {
		adesk_error_show();
	} else {
		adesk_result_show(ary.message);
		var ids = ary.ids.toString().split(',');
		for ( var i = 0; i < ids.length; i++ ) {
			mailconns = adesk_array_remove_key(ids[i], mailconns);
		}
		mailconn_removed(ids);
	}
}



function mailconn_sort(sortID) {
	//
}


function handleConnectionOrder() {
	setConnectionOrder();
	$('save_order').disabled = ( sorter_ids == orig_sorter_ids && sorter_orders == orig_sorter_orders );
}



function saveConnectionOrder() {
	// make ajax call
	startAPIcall();
	var baseURL = ( document.location.href.match('/manage/desk.php?') ? '../' : '' );
	adesk_ajax_call_cb(baseURL + 'awebdesk/api/manage/mailconn_order.php', 'mailconn_order', saveConnectionOrderCallback, sorter_ids, sorter_orders);
}

function saveConnectionOrderCallback(xml, txt) {
	var ary = adesk_dom_read_node(xml, ( paginator_b64 ? adesk_b64_decode : null ));
	$('save_order').disabled = ( ary.succeeded && ary.succeeded > 0 );
	adesk_ui_api_callback();
	if ( !ary.succeeded ) {
		adesk_error_show();
	} else {
		adesk_result_show();
	}
}

function setConnectionOrder() {
	sorter_ids     = "";
	sorter_orders  = "";
	var rows = $('mailconnListBody').getElementsByTagName('tr');
	for ( var i = 0; i < rows.length; i++ ) {
		if ( rows[i].getElementsByTagName('input')[0] ) {
			sorter_ids     += rows[i].getElementsByTagName('input')[0].value.toString();
			sorter_orders  += i.toString();
			if ( i < rows.length - 1 ) {
				sorter_ids     += ",";
				sorter_orders  += ",";
			}
		}
	}
}


function runPage() {
	// get default anchor array
	var arr = [ 'list', '01' ];
	// get provided anchor string
	var anchor = adesk_ui_anchor_get();
	// break the requested one into array
	var args = anchor.split('-');
	// fix arguments array
	for ( var i = 0; i < arr.length; i++ )
		if ( args[i] === undefined )
			args[i] = arr[i];
	// nothing different/provided, stop
	if ( args == arr || args[0] == '' ) {
		adesk_ui_anchor_set(arr.join('-'));
		return;
	}
	if ( args[0] == 'list' ) {
		// list
		if ( args[1] != arr[1] ) {
			mailconn_hide();
			mailconn_sort(args[1]);
		}
	} else if ( args[0] == 'edit' ) {
		// edit
		if ( args[1] != arr[1] ) {
			mailconn_show(args[1]);
		}
	} else {
		// add
		args[0] = 'add';
		mailconn_show(0);
	}
	adesk_ui_anchor_set(args.join('-'));
}

{/literal}
