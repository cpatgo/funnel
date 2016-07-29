var cron_form_str_cant_insert = '{"You do not have permission to add Cron Jobs"|alang|js}';
var cron_form_str_cant_update = '{"You do not have permission to edit Cron Jobs"|alang|js}';
var cron_form_str_cant_find   = '{"Cron Job not found."|alang|js}';
var cron_form_str_missing_name= '{"Cron Job Name not provided."|alang|js}';
var cron_form_str_missing_file= '{"Cron Job File/URL not provided."|alang|js}';

{literal}

var cron_form_id = 0;

function cron_form_defaults() {
	$("form_id").value = 0;

	// fields
	$('stringidField').value = '';
	$('nameField').value = '';
	$('descriptField').value = '';
	$('activeField').checked = true;
	$('filenameField').value = '';
	$('filenameField').readOnly = false;
	$('loglevelField').checked = true;
	$('weekdayField').value = '-1';
	$('dayField').value = '-1';
	$('hourField').value = '-1';
	$('minute1Field').value = '-1';
	$('minute2Field').value = '10';
	$('minute3Field').value = '20';
	$('minute4Field').value = '30';
	$('minute5Field').value = '40';
	$('minute6Field').value = '50';
	// panels
	$('stringidField').className = 'adesk_inline';
	$('stringidLabel').className = 'adesk_hidden';
	$('nameField').className = 'adesk_inline';
	$('nameLabel').className = 'adesk_hidden';
	$('descriptField').className = 'adesk_inline';
	$('descriptLabel').className = 'adesk_hidden';
	$('commandRow').className = 'adesk_hidden';
	$('dayofmonthRow').className = 'adesk_table_rowgroup';
	$('otherMinutes').className = 'adesk_hidden';
}

function cron_form_load(id) {
	cron_form_defaults();
	cron_form_id = id;

	if (id > 0) {
		if (adesk_js_admin.id != 1) {
			adesk_ui_anchor_set(cron_list_anchor());
			alert(cron_form_str_cant_update);
			return;
		}

		adesk_ui_api_call(jsLoading);
		$("form_submit").className = "adesk_button_update";
		$("form_submit").value = jsUpdate;
		adesk_ajax_call_cb("awebdeskapi.php", "cron!adesk_cron_select_row", cron_form_load_cb, id);
	} else {
		if (adesk_js_admin.id != 1) {
			adesk_ui_anchor_set(cron_list_anchor());
			alert(cron_form_str_cant_insert);
			return;
		}

		$("form_submit").className = "adesk_button_add";
		$("form_submit").value = jsAdd;
		$("form").className = "adesk_block";
	}
}

function cron_form_load_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();
	if ( !ary.id ) {
		adesk_error_show(cron_form_str_cant_find);
		adesk_ui_anchor_set(cron_list_anchor());
		return;
	}
	ary.id = parseInt(ary.id, 10);
	cron_form_id = ary.id;

	$("form_id").value = ary.id;

	// fields
	$('stringidField').value = ary.stringid;
	$('nameField').value = ary.name;
	$('descriptField').value = ( ary.descript ? ary.descript : jsNotAvailable );
	$('activeField').checked = ( ary.active == '1' );
	$('filenameField').value = ary.filename;
	$('filenameField').readOnly = ( ary.id <= cron_protected );
	$('loglevelField').checked = ( ary.loglevel > 0 );
	$('weekdayField').value = ary.weekday;
	$('dayField').value = ary.day;
	$('hourField').value = ( ary.hour > 100 ? ary.hour - 100 : ary.hour );
	var minute = (ary.minutelist+'').split(',');
	var at = minute.length > 1;
	minute = adesk_array_remove(-2, minute, true);
	for ( var i = 0; i < 6; i++ ) {
		if ( minute[i] ) {
			$('minute' + ( i + 1 ) + 'Field').value = minute[i];
		} else {
			$('minute' + ( i + 1 ) + 'Field').value = '-2';//i * 10;
		}
	}
/*
	$('minute1Field').value = ary.minute[0];
	$('minute2Field').value = ary.minute[1];
	$('minute3Field').value = ary.minute[2];
	$('minute4Field').value = ary.minute[3];
	$('minute5Field').value = ary.minute[4];
	$('minute6Field').value = ary.minute[5];
*/
	// operators
	$('houroperatorField').value = ( ary.hour != -1 && ary.hour > 100 ? 'every' : 'at' );
	$('minuteoperatorField').value = ( !at && minute[0] > 1 ? 'every' : 'at' );
	// labels
	$('stringidLabel').innerHTML = ary.stringid;
	$('nameLabel').innerHTML = ary.name;
	$('descriptLabel').innerHTML = ( ary.descript ? ary.descript : jsNotAvailable );
	$('commandLabel').innerHTML = ary.command;
	// panels
	$('stringidField').className = 'adesk_hidden';
	$('stringidLabel').className = 'adesk_inline';
	$('nameField').className = 'adesk_hidden';
	$('nameLabel').className = 'adesk_inline';
	$('descriptField').className = 'adesk_hidden';
	$('descriptLabel').className = 'adesk_inline';
	$('commandRow').className = 'adesk_table_rowgroup';
	$('dayofmonthRow').className = ( ary.weekday == '-1' ? 'adesk_table_rowgroup' : 'adesk_hidden' );
	cron_form_minutes_switch($('minuteoperatorField').value, $('minute1Field').value);
	cron_form_hours_switch($('houroperatorField').value, $('hourField').value);

	$("form").className = "adesk_block";
}

function cron_form_save(id) {
	var post = adesk_form_post($("form"));

	// perform checks
	if ( id == 0 ) {
		if ( $('nameField').value == '' ) {
			alert(cron_form_str_missing_name);
			$('nameField').focus();
			return;
		}
	}
	if ( $('filenameField').value == '' ) {
		alert(cron_form_str_missing_file);
		$('filenameField').focus();
		return;
	}

	adesk_ui_api_call(jsSaving);
	if (id > 0)
		adesk_ajax_post_cb("awebdeskapi.php", "cron!adesk_cron_update_post", cron_form_save_cb, post);
	else
		adesk_ajax_post_cb("awebdeskapi.php", "cron!adesk_cron_insert_post", cron_form_save_cb, post);
}

function cron_form_save_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(cron_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}
}


function cron_form_days_switch(oper, val) {
	$('otherDaysOperator').className = ( oper == 'at' ? 'adesk_hidden' : 'adesk_inline' );
	var rel = $('dayField');
	var options = rel.getElementsByTagName('option');
	if ( options[0].selected || options[1].selected || options[2].selected ) {
		if ( oper == 'every' ) options[3].selected = true;
	}
	options[0].className = ( oper == 'every' ? 'adesk_hidden' : '' );
	options[1].className = ( oper == 'every' ? 'adesk_hidden' : '' );
	options[2].className = ( oper == 'every' ? 'adesk_hidden' : '' );
}

function cron_form_hours_switch(oper, val) {
	$('otherHoursOperator').className = ( oper == 'at' ? 'adesk_hidden' : 'adesk_inline' );
	var rel = $('hourField');
	var options = rel.getElementsByTagName('option');
	if ( options[0].selected || options[1].selected || options[2].selected ) {
		if ( oper == 'every' ) options[3].selected = true;
	}
	options[0].className = ( oper == 'every' ? 'adesk_hidden' : '' );
	options[1].className = ( oper == 'every' ? 'adesk_hidden' : '' );
	options[2].className = ( oper == 'every' ? 'adesk_hidden' : '' );
}

function cron_form_minutes_switch(oper, val) {
	$('otherMinutes').className = ( oper == 'every' || val == '-1' ? 'adesk_hidden' : 'adesk_inline' );
	$('otherMinutesOperator').className = ( oper == 'at' ? 'adesk_hidden' : 'adesk_inline' );
	var rel = $('minute1Field');
	var options = rel.getElementsByTagName('option');
	if ( options[0].selected || options[1].selected || options[2].selected ) {
		if ( oper == 'every' ) options[3].selected = true;
	}
	options[0].className = ( oper == 'every' ? 'adesk_hidden' : '' );
	options[1].className = ( oper == 'every' ? 'adesk_hidden' : '' );
	options[2].className = ( oper == 'every' ? 'adesk_hidden' : '' );
}

{/literal}
