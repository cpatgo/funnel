
var syncTable = new ACTable();

// Multicheck
syncTable.setcol(0, function(row) {
	return adesk_form_multicheck_get(row.id, adesk_array_has(syncTable.selection, row.id));
});

// Options
syncTable.setcol(1, function(row) {
	return Builder.node(
		'div',
		{ className: 'adesk_table_row_options' },
		[
			// edit
			Builder.node(
				'a',
				{ href: '#edit-' + row.id },
				[ Builder._text(jsOptionEdit) ]
			),
/*			// view
			Builder.node(
				'a',
				{ href: '#view-' + row.id },
				[ Builder._text(jsOptionView) ]
			),
*/			// test
			Builder.node(
				'a',
				{ href: '#test-' + row.id, onclick: 'return sync_run(' + row.id + ', true);' },
				[ Builder._text(jsOptionTest) ]
			),
			// run
			Builder.node(
				'a',
				{ href: '#run-' + row.id, onclick: 'return sync_run(' + row.id + ', false);' },
				[ Builder._text(jsOptionRun) ]
			),
			// delete
			Builder.node(
				'a',
				{ href: '#delete-' + row.id, onclick: 'return sync_delete_show(' + row.id + ');' },
				[ Builder._text(jsOptionDelete) ]
			)
		]
	);
});

// Title
syncTable.setcol(2, function(row) {
	return Builder.node('strong', [ Builder._text(row.sync_name) ]);
});

// Database
syncTable.setcol(3, function(row) {
	return row.db_name + '@' + row.db_host + ' ('+ row.db_type + ')';
});

// Lat Ran
syncTable.setcol(4, function(row, td) {
	td.align = 'center';
	return ( row.tstamp ? sql2date(row.tstamp).format(datetimeformat) : jsNotAvailable );
});



function sync_list() {
	if ( !adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible() ) adesk_ui_api_call(jsLoading);
	// fetch new list
	adesk_ajax_call_cb('awebdeskapi.php', 'sync!adesk_sync_list', sync_tabelize, sortID);
}

function sync_tabelize(xml) {
	var ary = adesk_dom_read_node(xml, ( paginator_b64 ? adesk_b64_decode : null ));
	// system stuff
	manageID = 0;
	var total = ary.rows.length;
	// switch panels
	$('syncListPanel').className = 'adesk_block';
	$('syncFormPanel').className = 'adesk_hidden';
	//$('syncDeletePanel').className = 'adesk_hidden';
	$('syncModeTitle').innerHTML = '';
	// nothing found bar
	$('syncsNoResults').className = ( total == 0 ? 'adesk_table_row' : 'adesk_hidden' );
	// other counts, buttons and elements
	$('syncsCount').innerHTML = total;
	// tabelize the table
	adesk_paginator_tabelize(syncTable, 'syncsTable', ary.rows);
	// hide the loading bar
	$('loadingBar').className = 'adesk_hidden';
	// set anchor?
	//adesk_ui_anchor_set([ 'list', sortID ].join('-'));
}



function sync_sort(newSortId) {
	var oldSortId = ( sortID.substr(-1, 1) == 'D' ? sortID.substr(0, 2) : sortID );
	var oldSortObj = $('sorter' + oldSortId);
	var sortObj = $('sorter' + newSortId);
	// if sort column didn't change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( sortID.substr(-1, 1) == 'D' ) {
			// was DESC
			newSortId = sortID.substr(0, 2);
			sortObj.className = 'adesk_sort_asc';
		} else {
			// was ASC
			newSortId = sortID + 'D';
			sortObj.className = 'adesk_sort_desc';
		}
	} else {
		// remove old sortID
		if ( oldSortObj ) oldSortObj.className = 'adesk_sort_other';
		// set sort field
		sortObj.className = 'adesk_sort_asc';
	}
	sortID = newSortId;
	adesk_ui_api_call(jsSorting);
	sync_list();
	return false;
}


