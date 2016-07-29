

function sync_run(id, test) {
	if ( id == 0 ) {
		var post = adesk_form_post('addSyncForm');
		post.id = id;
	} else {
		var post = { id: id };
	}
	post.test = ( test ? 1 : 0 );
	adesk_ui_api_call(jsWorking, 10 * 60); // allow 10 minutes
	adesk_ajax_post_cb('awebdeskapi.php', 'sync!adesk_sync_run_api', sync_run_callback, post);
	// then return FALSE! (form will be submitted only in case of error)
	return false;
}

function sync_run_callback(xml, txt) {
	var ary = adesk_dom_read_node(xml, ( paginator_b64 ? adesk_b64_decode : null ));
	adesk_ui_api_callback();
	// result message
	if ( ary.succeeded && ary.succeeded == 1 ) {
		// why, oh why, do we do this
		if ( typeof ary.sync == 'undefined' ) {
			var sync = ary;
		} else if ( typeof ary.sync[0] == 'undefined' ) {
			var sync = ary.sync;
		} else {
			var sync = ary.sync[0];
		}
		// display
		$('syncRunID').value = sync.id;
		//$('syncRunIsTest').value = ( ary.is_test == 1 ? 1 : 0 );
		$('syncRunTitle').innerHTML = ( ary.is_test == 1 ? syncTitleTest : syncTitleRun );
		$('syncRunUser').innerHTML = sync.db_user + '@' + sync.db_host;
		$('syncRunDB').innerHTML = sync.db_name;
		$('syncRunTable').innerHTML = ( sync.is_custom == 1 ? syncCustomQuery : sync.db_table );
		$('syncRunTablesLink').className = ( ary.is_test == 1 ? 'adesk_inline' : 'adesk_hidden' );
		if ( ary.is_test == 1 ) {
			var rel = $('syncRunTablesList');
			adesk_dom_remove_children(rel);
			for ( var i = 0; i < ary.tables.length; i++ ) {
				rel.appendChild(Builder.node('li', [ Builder._text(ary.tables[i]) ]));
			}
		}
		$('syncRunQuery').innerHTML = nl2br(sync.query);
		/*
		$('syncRunFound').innerHTML = ary.found;
		// list of synced rows
		$('syncRunSynced').innerHTML = ary.synced;
		var rel = $('syncRunSyncedList');
		adesk_dom_remove_children(rel);
		adesk_table_create(rel, ary.syncedrows);
		// list of failed rows
		$('syncRunFailed').innerHTML = ary.failed;
		var rel = $('syncRunFailedList');
		adesk_dom_remove_children(rel);
		adesk_table_create(rel, ary.failedrows);
		*/
		// start button
		$('syncRunStart').value = ( ary.is_test == 1 ? syncStartTest : syncStartRun );
		$('syncRunStart').disabled = false;
		// details button
		$('syncRunDetails').className = 'adesk_hidden';
		// progress
		//adesk_progressbar_set("progressBar", 0);
		$('syncRunNotice').className = 'adesk_hidden';
		$('syncRunResult').className = 'adesk_hidden';
		// iframe
		$('syncRunFrame').className = 'adesk_hidden';
		$('syncRunFrame').src = 'about:blank';
		// if test, show run button
		$('test_to_run_button').className = ( ary.is_test == 1 ? 'adesk_inline' : 'adesk_hidden' );
		adesk_dom_toggle_display('syncRunPanel', 'block');
		adesk_result_show(ary.message);
	} else {
		adesk_error_show(ary.message);
	}
}

function adesk_sync_start() {
	var is_test = ( $('syncRunStart').value == syncStartTest );
	var id = $('syncRunID').value;
	var uri = ( typeof adesk_js_site.sdnum != 'undefined' ? 'functions/crons/dbsync.php' : 'cron_sync.php' );
	var url = uri + '?id=' + id + '&test=' + ( is_test ? 1 : 0 ) + '&force=1';
	// start button
	$('syncRunStart').disabled = true;
	// details button
	$('syncRunDetails').className = 'adesk_inline';
	// progress
	adesk_progressbar_set("progressBar", 0);
	$('syncRunNotice').className = 'adesk_block';
	$('syncRunResult').className = 'adesk_hidden';
	// iframe
	$('syncRunFrame').className = 'adesk_block';
	$('syncRunFrame').width = '1';
	$('syncRunFrame').height = '1';
	$('syncRunFrame').src = url;

	$("sync_before_run").hide();
	$("sync_after_run").show();
}

function adesk_sync_details() {
	var show = ( $('syncRunFrame').width == '1' );
	// details button
	//adesk_dom_toggle_class('syncRunDetails', 'adesk_inline', 'adesk_hidden');
	// iframe
	$('syncRunFrame').width = ( show ? '100%' : '1' );
	$('syncRunFrame').height = ( show ? '300' : '1' );
}

function adesk_sync_progressbar_callback(ary) {
	if ( parseInt(ary.percentage) == 100 ) {
		// stop the progressbar
		adesk_progressbar_unregister("progressBar");
		$('syncRunNotice').className = 'adesk_hidden';
		$('syncRunResult').className = 'adesk_block';
		adesk_loader_hide();
	}
}

function adesk_sync_report() {
	// fetch import logs
	adesk_ui_api_call(jsLoading, 60);
	adesk_ajax_call_cb('awebdeskapi.php', 'sync!adesk_sync_report', adesk_sync_report_cb, processID);
	return false;
}

function adesk_sync_report_cb(xml) {
	var ary = adesk_dom_read_node(xml, ( paginator_b64 ? adesk_b64_decode : null ));
	adesk_ui_api_callback();

	// hack?
	if ( ary.counts[0] ) ary.counts = ary.counts[0];
	if ( ary.lists[0]  ) ary.lists  = ary.lists[0];

	// fill the modal panel

	// set counts
	ary.total0 = parseInt(ary.total, 10);
	ary.total  = parseInt($('report_count').innerHTML, 10);
	ary.total1 = ary.total - ary.total0;
	for ( var i in ary.counts ) {
		if ( typeof ary.counts[i] != 'function' ) {
			ary.counts[i] = parseInt(ary.counts[i], 10);
		}
	}

	$('report_count0').innerHTML = ary.total0;
	$('report_count1').innerHTML = ary.total1;

	if ( typeof(ihook_adesk_sync_report) == 'function' ) ihook_adesk_sync_report(ary);

	// show it
	//adesk_dom_toggle_display('sync_report', 'block');
	adesk_dom_toggle_display('syncRunPanel', 'block');
	adesk_dom_display_block('sync_report');
}



function adesk_table_create(rel, ary) {
	for ( var i = 0; i < ary.length; i++ ) {
		var row = ary[i];
		if ( i == 0 ) {
			// first row, print header
			var header = { };
			for ( var k in row ) {
				if ( typeof row[k] != 'function' ) {
					header[k] = k;
				}
			}
			adesk_table_create_row(rel, header, 'adesk_table_header');
		}
		adesk_table_create_row(rel, row, 'adesk_table_row');
	}
}


function adesk_table_create_row(rel, row, className) {
	var tds = [ ];
	for ( var k in row ) {
		if ( typeof row[k] != 'function' ) {
			var v = row[k];
			tds.push(Builder.node(
				'td',
				{ title: row[k] },
				[ Builder._text(adesk_str_shorten(row[k], 30)) ]
			));
		}
	}
	var tr = Builder.node(
		'tr',
		{ className: className },
		tds
	);
	rel.appendChild(tr);
}
