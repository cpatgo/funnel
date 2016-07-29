var emailaccount_log_str_cant_find   = '{"Email Check Log not found."|alang|js}';
var emailaccount_log_str_row         = '{"Email %s parsed as (un)subscription."|alang}';
var emailaccount_log_str_structured  = '{"Email appears to be an improperly structured (un)subscription message. Error: %s"|alang}';
var emailaccount_log_str_errors      = '{"Email %s NOT parsed as (un)subscription! Error: %s"|alang}';
var emailaccount_log_str_details     = '{"Details..."|alang}';

{literal}
function emailaccount_log(id) {
	adesk_ui_api_call(jsLoading);
	adesk_ajax_call_cb("awebdeskapi.php", "emailaccount.emailaccount_log", emailaccount_log_cb, id);
}

function emailaccount_log_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();
	if ( !ary.cnt ) {
		adesk_error_show(emailaccount_log_str_cant_find);
		adesk_ui_anchor_set(emailaccount_list_anchor());
		return;
	}
	ary.cnt = parseInt(ary.cnt, 10);

	// set count
	$('log_count').innerHTML = ary.cnt;

	// set list
	adesk_dom_remove_children($('log_list'));
	$('log_list' ).className = ( ary.cnt >  0 ? 'adesk_block' : 'adesk_hidden' );
	$('log_empty').className = ( ary.cnt == 0 ? 'adesk_block' : 'adesk_hidden' );
	if ( ary.cnt > 0 ) {
		for ( var i = 0; i < ary.cnt; i++ ) {
			var row = ary.log[i];
			var txt = sql2date(row.tstamp).format(datetimeformat) + ': ';
			if ( row.error && row.error != '' ) {
				if ( row.email && row.email != '' ) {
					txt += sprintf(emailaccount_log_str_errors, row.email, row.msg);
				} else {
					txt += sprintf(emailaccount_log_str_structured, row.msg);
				}
			} else {
				txt += sprintf(emailaccount_log_str_row, row.email);
			}

			$('log_list').appendChild(
				Builder.node(
					'li',
					{ className: 'emailaccount_log_row' },
					[
						Builder.node('input', { type: 'hidden', value: row.id, id: 'log_row_' + row.id }),
						Builder._text(txt + ' '),
						Builder.node(
							'a',
							{ href: '#', onclick: 'return emailaccount_log_show(' + row.id + ');' },
							[ Builder._text(emailaccount_log_str_details) ]
						)
					]
				)
			);
		}
	}

	//adesk_dom_toggle_display("log", "block");
	adesk_dom_display_block("log");
}


function emailaccount_log_show(id) {
	adesk_ui_api_call(jsLoading);
	adesk_ajax_call_cb("awebdeskapi.php", "emailaccount.emailaccount_log_select_row", emailaccount_log_show_cb, id);
	return false;
}

function emailaccount_log_show_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();
	if ( !ary.source ) {
		adesk_error_show(emailaccount_log_str_cant_find);
		return;
	}
	ary.id = parseInt(ary.id, 10);

	// set log info
	var emlstr = '';
	if ( ary.email && ary.email != '' ) {
		emlstr = ary.email;
		var sid = parseInt(ary.subscriberid, 10);
		if ( sid > 0 ) emlstr = '<a href="desk.php?action=subscriber_view&id=' + sid + '">' + emlstr + '</a>';
	} else {
		emlstr = jsNotAvailable;
	}
	$('log_source').value       = ary.source;
	$('log_result').innerHTML   = ary.msg;
	$('log_email').innerHTML    = emlstr;
	$('log_date').innerHTML     = sql2date(ary.tstamp).format(datetimeformat);

	// show the box
	$('log_list').className = 'adesk_hidden';
	$('log_source_box').className = 'adesk_block';

	/*
	// hide other log rows
	var rel = $('log_list');
	var rows = rel.getElementsByTagName('li');
	for ( var i = 0; i < rows.length; i++ ) {
		var id = parseInt(rows[i].getElementsByTagName('input')[0].value, 10);
		if ( id != ary.id ) rows[i].className = 'adesk_hidden';
	}
	*/
}

function emailaccount_log_hide() {
	// discard the source
	$('log_source').value       = '';
	$('log_result').innerHTML   = '';
	$('log_email').innerHTML    = '';
	$('log_date').innerHTML     = '';

	// show the box
	$('log_list').className = 'adesk_block';
	$('log_source_box').className = 'adesk_hidden';

	/*
	// show other log rows
	var rel = $('log_list');
	var rows = rel.getElementsByTagName('li');
	for ( var i = 0; i < rows.length; i++ ) {
		rows[i].className = '';
	}
	*/
	return false;
}
{/literal}
