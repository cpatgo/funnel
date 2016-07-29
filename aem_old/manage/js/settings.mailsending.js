var mail_sending_rand_alert = '{"Please Note:\nUsing randomized sending order can cause your sending to take longer.\nIf you are looking for high sending speed we suggest any other sending order."|alang|js}';
var mail_sending_type_mail = '{"Mail()"|alang|js}';
var mail_sending_type_smtp = '{"SMTP"|alang|js}';
var mail_sending_notfound = '{"Mailer not found."|alang|js}';
var mail_sending_email_invalid = '{"Please enter a valid email address."|alang|js}';
var mail_sending_testsend_error = '{"There was an ERROR while sending your test email."|alang|js}';
var mail_sending_throttle_check = '{"You can not enter a value greater than 4 minutes (240 seconds)."|alang|js}';
var serverLimit = '{"Server is the limit."|alang|escape:"javascript"}';
var noGroupsSelected = '{"You did not select any groups. Do you wish to use all groups instead?"|alang|escape:"javascript"}';

{jsvar var=$mailconnections name=mailers}
{jsvar var=$mailconnectionscnt name=mailerscnt}

{literal}

var sendtimes = { };

function mailer_add() {
	mailer_defaults();

	$('mailer_form').show();
}

function mailer_edit(id) {
	if ( typeof mailers[id] == 'undefined' ) {
		alert(mail_sending_notfound);
		return;
	}
	var mailer = mailers[id];

	mailer_defaults();
	mailer_fill(mailer);

	$('mailer_form').show();
}

function mailer_defaults() {
	$('mailer_form_id').value = 0;
	$('smname').value = '';
	$('smmail').checked = true;
	$('smtpInfo').className = 'adesk_hidden';
	$('dontstop').checked = true;
	$('sdbox').className = 'adesk_hidden';
	$('limbox').className = 'adesk_hidden';
	$('smthres').value = 50;
	var mailer_rows = $('mailer_list_table').getElementsByTagName('tr');
	$('rotatorEdit').className = mailer_rows.length > 1 ? 'adesk_table_rowgroup' : 'adesk_hidden';
	$('smhost').value = '';
	$('smport').value = 25;
	$('smuser').value = '';
	$('smpass').value = '';
	$('smenc').value = 8;
	$('smpop3b4').checked = false;
	$('sdnum').value = '';
	$('sdfreq').value = '';
	$('sdlim').value = '';
	$('sdspan').value = 'hour';
	//adesk_dom_boxclear("groupfield");
	$$('.groupfield').each(function(e) { e.checked = true; });
	calculateSendingSpeed();
}

function mailer_fill(ary) {
	$('mailer_form_id').value = ary.id;
	$('smname').value = ary.name;
	$('smmail').checked = ary.type == 0;
	//$('smsendmail').checked = ary.type == 1;
	$('smsmtp').checked = ary.type != 0;
	$('smtpInfo').className = ary.type != 0 ? 'adesk_table_rowgroup' : 'adesk_hidden';
	$('dontstop').checked = ary.limit + ary.pause + ary.frequency == 0;
	$('sd').checked = ary.pause + ary.frequency > 0;
	$('lim').checked = ary.limit > 0;
	$('sdbox').className = ary.pause + ary.frequency != 0 ? 'adesk_block' : 'adesk_hidden';
	$('limbox').className = ary.limit != 0 ? 'adesk_block' : 'adesk_hidden';
	$('smthres').value = ary.threshold;
	var mailer_rows = $('mailer_list_table').getElementsByTagName('tr');
	$('rotatorEdit').className = mailer_rows.length > 1 ? 'adesk_table_rowgroup' : 'adesk_hidden';
	$('smhost').value = ary.type != 0 ? ary.host : '';
	$('smport').value = ary.type != 0 ? ary.port : '';
	$('smuser').value = ary.type != 0 ? ary.user : '';
	$('smpass').value = ary.type != 0 ? ary.pass : '';
	$('smenc').value = ary.type != 0 ? ary.encrypt : 8;
	$('smpop3b4').checked = ary.type != 0 && ary.pop3b4smtp == 1;
	$('sdnum').value = ary.pause > 0 ? ary.pause : '';
	$('sdfreq').value = ary.frequency > 0 ? ary.frequency : '';
	$('sdlim').value = ary.limit > 0 ? ary.limit : '';
	$('sdspan').value = ary.limitspan == 'day' ? 'day' : 'hour';

	if ( ary.groupslist ) {
		$$('.groupfield').each(function(e) { e.checked = false; });
		adesk_dom_boxset("groupfield", ary.groupslist.toString().split(","));
	} else {
		$$('.groupfield').each(function(e) { e.checked = true; });
	}
	calculateSendingSpeed();
}

function mailer_save() {
	var post = adesk_form_post($('mailer_form'));
	adesk_ajax_post_cb(
		"awebdeskapi.php",
		( post.id == 0 ? "mailer.mailer_insert" : "mailer.mailer_update" ),
		function(xml) {
			var ary = adesk_dom_read_node(xml);
			if ( ary.succeeded == 1 ) {
				adesk_result_show(ary.message);
				mailers[ary.id] = ary;
				mailer_list();
				$('mailer_form').hide();
			} else {
				adesk_error_show(ary.message);
			}
		},
		post
	);
}

function mailer_test(id) {
	if ( typeof mailers[id] == 'undefined' ) {
		alert(mail_sending_notfound);
		return;
	}
	var mailer = mailers[id];

	$('mailer_test_id').value = id;
	$('mailer_test').show();
}

function mailer_send() {
	var id = $('mailer_test_id').value;
	if ( typeof mailers[id] == 'undefined' ) {
		alert(mail_sending_notfound);
		return;
	}
	var mailer = mailers[id];

	var email = $('testEmailField').value;

	mailer.to_email = email;

	if ( !adesk_str_email(email) ) {
		alert(mail_sending_email_invalid);
		return;
	}

	$('testEmailButton').disabled = true;

	adesk_ui_api_call(jsSending);
	adesk_ajax_handle_text = mailer_error_send;
	adesk_ajax_call_cb(//adesk_ajax_post_cb(
		"awebdeskapi.php",
		"mailer.mailer_test",//"mailer.mailer_test_post",
		function(xml) {
			adesk_ui_api_callback();
			var ary = adesk_dom_read_node(xml);
			$('testEmailButton').disabled = false;
			if ( ary.succeeded == 1 ) {
				adesk_result_show(ary.message);
				$('mailer_test').hide();
				$('mailer_test_id').value = '';
				sendtimes[id] = ary.sendtime;
			} else {
				adesk_error_show(ary.message);
			}
		},
		id,//mailer,
		email
	);
}

function mailer_error_send(txt) {
	// reset the callback
	adesk_ui_api_callback();
	// disable button
	$('testEmailButton').disabled = false;
	// show the mailer error modal
	jsErrorMailerBarMessage = mail_sending_testsend_error;
	adesk_ui_error_mailer(txt, 'mailer_test');
}

function mailer_delete(id) {
	if ( typeof mailers[id] == 'undefined' || id < 2 ) {
		alert(mail_sending_notfound);
		return;
	}
	var mailer = mailers[id];

	$('mailer_delete_id').value = id;
	$('deleteEmailMsg').innerHTML = mailer.name;
	$('mailer_delete').show();
}

function mailer_remove() {
	var id = $('mailer_delete_id').value;
	var mailer = mailers[id];
	adesk_ajax_call_cb(
		"awebdeskapi.php",
		"mailer.mailer_delete",
		function(xml) {
			var ary = adesk_dom_read_node(xml);
			if ( ary.succeeded == 1 ) {
				adesk_result_show(ary.message);
				delete mailers[id];
				mailer_list();
				$('mailer_delete').hide();
				$('mailer_delete_id').value = '';
			} else {
				adesk_error_show(ary.message);
			}
		},
		id
	);
}


function mailer_list() {
	var rows = [];
	for ( var i in mailers ) {
		rows.push(mailers[i]);
	}
	adesk_paginator_tabelize(mailer_table, "mailer_list_table", rows, 0);
}

function mailer_flip(id1, id2) {
	var newobj = {};
	for ( var i in mailers ) {
		if ( i == id1 ) {
			newobj[id2] = mailers[id2];
		} else if ( i == id2 ) {
			newobj[id1] = mailers[id1];
		} else {
			// just copy it
			newobj[i] = mailers[i];
		}
	}
	mailers = newobj;
}

function mailer_up(id) {
	// find previous id
	var index = null;
	var flag = false;
	for ( var i in mailers ) {
		if ( mailers[i].id == id ) {
			flag = true;
			break;
		}
		index = i;
	}
	if ( index === null || !flag ) return;

	// flip them
	mailer_flip(id, index);

	// collect the new order
	var order = [];
	for ( var i in mailers ) order.push(i);

	// save the new order
	adesk_ui_api_call(jsSorting);
	adesk_ajax_call_cb("awebdeskapi.php", "mailer.mailer_sort", mailer_sort_cb, order.join(','));
}

function mailer_down(id) {
	// find next id
	var index = null;
	var flag = false;
	for ( var i in mailers ) {
		if ( flag ) {
			index = i;
			break;
		}
		if ( mailers[i].id == id ) flag = true;
	}
	if ( index === null || !flag ) return;

	// flip them
	mailer_flip(id, index);

	// collect the new order
	var order = [];
	for ( var i in mailers ) order.push(i);

	// save the new order
	adesk_ui_api_call(jsSorting);
	adesk_ajax_call_cb("awebdeskapi.php", "mailer.mailer_sort", mailer_sort_cb, order.join(','));
}

function mailer_sort_cb(xml) {
	adesk_ui_api_callback();
	var ary = adesk_dom_read_node(xml);

	if ( ary.succeeded == 1 ) {
		adesk_result_show(ary.message);
		mailer_list();
	} else {
		adesk_error_show(ary.message);
	}
}


var mailer_table = new ACTable();
var mailer_list_sort = "01";
var mailer_list_offset = 0;
var mailer_list_filter = 0;
var mailer_list_sort_discerned = false;

mailer_table.setcol(0, function(row) {
	var edit = Builder.node("a", { href: '#', onclick: sprintf("mailer_edit(%d);return false;", row.id) }, jsEdit);
	var dele = Builder.node("a", { href: "#", onclick: sprintf("mailer_delete(%d); return false", row.id) }, jsDelete);
	var test = Builder.node("a", { href: "#", onclick: sprintf("mailer_test(%d); return false", row.id) }, jsOptionTest);

	// Check permissions

	var ary = [];

	ary.push(edit);
	if ( row.id > 1 ) {
		ary.push(" ");
		ary.push(dele);
	}
	ary.push(" ");
	ary.push(test);

	return Builder.node("div", { className: "adesk_table_row_options" }, ary);
});

mailer_table.setcol(1, function(row) {
	return row.name;
});

mailer_table.setcol(2, function(row) {
	return ( row.type == 0 ? mail_sending_type_mail : mail_sending_type_smtp );
});

mailer_table.setcol(3, function(row) {
	if ( row.type == 0 )
		return jsNotAvailable;
	else
		return row.user + '@' + row.host + ( row.port == 25 ? '' : ':' + row.port );
});

mailer_table.setcol(4, function(row) {
	return row.threshold;
});

mailer_table.setcol(5, function(row) {
	return Builder.node(
		'span',
		[
			Builder.node('a', { href: '#', onclick: sprintf('mailer_down(%s);return false;', row.id) }, [ Builder.node('img', { src: 'images/desc.gif', border: 0 }) ]),
			Builder._text(" "),
			Builder.node('a', { href: '#', onclick: sprintf('mailer_up(%s);return false;', row.id) }, [ Builder.node('img', { src: 'images/asc.gif', border: 0 }) ])
		]
	);
});
/*
function mailer_list_tabelize(rows, offset) {
	if (rows.length < 1) {
		// We may have some trs left if we just deleted the last row.
		adesk_dom_remove_children($("mailer_list_table"));

		$("mailerlist_noresults").show();
		adesk_ui_api_callback();
		return;
	}
	$("mailerlist_noresults").hide();
	$("mailerloadingBar").hide();
	adesk_paginator_tabelize(mailer_table, "mailer_list_table", rows, offset);
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function mailer_list_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	mailer_list_offset = parseInt(offset, 10);

	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, mailer_list_sort, mailer_list_offset, this.limit, mailer_list_filter);
}
*/
function mailer_list_chsort(newSortId) {
	var sortlen = mailer_list_sort.length;
	var oldSortId = ( mailer_list_sort.substr(sortlen-1, 1) == 'D' ? mailer_list_sort.substr(0, 2) : mailer_list_sort );
	var oldSortObj = $('mailer_list_sorter' + oldSortId);
	var sortObj = $('mailer_list_sorter' + newSortId);
	// if sort column didn't change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( mailer_list_sort.substr(sortlen-1, 1) == 'D' ) {
			// was DESC
			newSortId = mailer_list_sort.substr(0, 2);
			sortObj.className = 'adesk_sort_asc';
		} else {
			// was ASC
			newSortId = mailer_list_sort + 'D';
			sortObj.className = 'adesk_sort_desc';
		}
	} else {
		// remove old mailer_list_sort
		if ( oldSortObj ) oldSortObj.className = 'adesk_sort_other';
		// set sort field
		sortObj.className = 'adesk_sort_asc';
	}
	mailer_list_sort = newSortId;
	adesk_ui_api_call(jsSorting);
	paginators[1].paginate(mailer_list_offset);
	return false;
}

function mailer_list_discern_sortclass() {
	if (mailer_list_sort_discerned)
		return;

	var elems = $("mailerlist_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("mailerlist_sorter%s", mailer_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (mailer_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	mailer_list_sort_discerned = true;
}

function checkSendPause() {
	var v = $('sdnum').value;
	v = parseInt(v, 10);
	if ( isNaN(v) ) {
		$('sdnum').value = $('sdnum').value.toString().replace(/(^\d+)/, '');
		return false;
	} else if ( $('sdnum').value != v ) {
		$('sdnum').value = v;
	}
	if ( v > 240 ) {
		alert(mail_sending_throttle_check);
		$('sdnum').value = 240;
		return false;
	}
	return true;
}

function checkSel(obj) {
	var ind = obj.selectedIndex;
	if (ind == 0) { alert(mail_sending_rand_alert); }
}

// settings2limits
function calculateSendingSpeed() {
	var sdnumObj = $('sdnum');
	var sdfreqObj = $('sdfreq');
	var sdepmObj = $('sdlim');
	var ephObj = $('eph');
	var epmObj = $('epm');
	var epsObj = $('eps');
	var speObj = $('spe');
	var sdObj = $('sd');
	var limObj = $('lim');
	var spanObj = $('sdspan');
	// infinite check
	var infinite = false;
	if ( !sdObj.checked ) infinite = true;
	if ( Math.floor(sdfreqObj.value) == 0 ) infinite = true;
	if ( Math.round(sdnumObj.value) == 0 ) infinite = true;
	// infinite EPM check
	var infiniteEPM = false;
	if ( !limObj.checked ) infiniteEPM = true;
	if ( Math.floor(sdepmObj.value) == 0 ) infiniteEPM = true;

	var perMinEPM = 0;

	if ( !isNaN(parseFloat(sdepmObj.value)) ) {
		// convert to hours
		perMinEPM = sdepmObj.value / 60;
		// convert to days
		if ( spanObj.value == 'day' ) perMinEPM /= 24;
	}

	// can we fix it to decimal check
	var canFix = 10;
	canFix = canFix.toFixed;
	// if infinite
	if ( infinite ) {
		// if EPM is also infinite
		if ( infiniteEPM ) {
			// really infinite
			canFix = false;
			var perSec = serverLimit;
			var perMin = serverLimit;
			var perHour = serverLimit;
			var perEml = 0;
		} else {
			// use EPM
			var perMin = perMinEPM;
			var perSec = perMin / 60;
			var perHour = perMin * 60;
			var perEml = 1 / perSec;
		}
	} else {
		// calculate per second
		var perSec = sdfreqObj.value / sdnumObj.value;
		var perEml = 1 / perSec;
		// turn into minutes
		var perMin = perSec * 60;
		// turn into hours
		var perHour = perMin * 60;
		// check if less than EPM
		if ( perMin > perMinEPM && perMinEPM > 0 ) {
			// use EPM
			perMin = perMinEPM;
			perSec = perMin / 60;
			perHour = perMin * 60;
			perEml = 1 / perSec;
		}
	}
	// done with calculating
	// fill the destination object
	if ( canFix ) {
		ephObj.innerHTML = perHour.toFixed(2);
		epmObj.innerHTML = perMin.toFixed(2);
		epsObj.innerHTML = perSec.toFixed(2);
		speObj.innerHTML = perEml.toFixed(2);
	} else {
		ephObj.innerHTML = perHour;
		epmObj.innerHTML = perMin;
		epsObj.innerHTML = perSec;
		speObj.innerHTML = perEml;
	}
}

function mailconn_save_post(post) {
	post.p = adesk_form_select_extract($('parentsList'));
	if ( !post.p.length ) {
		if ( !confirm(noGroupsSelected) ) {
			return false;
		}
	}
	if ( rotator ) {
		if ( $('sd')  && !$('sd').checked ) {
			post.num  = adesk_b64_encode('0');
			post.freq = adesk_b64_encode('0');
		}
		if ( $('lim') && !$('lim').checked ) {
			post.lim  = adesk_b64_encode('0');
		}
		//post.dotfix = 0;
	}
	return post;
}

function mailconn_load_post(src) {
	if ( src.groupslist ) {
		adesk_form_select_multiple($('parentsList'), ( src.groupslist + '').split(','));
	} else {
		adesk_form_select_multiple_all($('parentsList'));
	}

	calculateSendingSpeed();
}

{/literal}

