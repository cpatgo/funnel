

function sync_delete_multiple() {
	if ( !adesk_form_check_selection_check($('syncsTable'), 'multi[]', jsNothingSelected, jsNothingFound) ) return false;
	sync_delete_show(adesk_form_check_selection_get($('syncsTable'), 'multi[]').join(','));
	return false;
}

function sync_delete_show(id) {
	adesk_ui_api_call(jsLoading);
	// make a call, fetch sync, then show it
	adesk_ajax_call_cb('awebdeskapi.php', 'sync!adesk_sync_select', sync_delete_show_callback, id);
	return false;
}

function sync_delete_show_callback(xml, txt) {
	var ary = adesk_dom_read_node(xml, ( paginator_b64 ? adesk_b64_decode : null ));
	if ( typeof(ary.sync_name) == 'undefined' && !ary.row && !ary.rows ) {
		adesk_ui_api_callback();
		somethingChanged = false;
		//sync_delete_hide();
		adesk_error_show('Sync not found.');
		return;
	}
	sync_delete_fill(ary);
	//$('syncListPanel').className = 'adesk_hidden';
	//$('syncDeletePanel').className = 'adesk_block';
	adesk_dom_toggle_display('syncDeletePanel', 'block');
	$('syncModeTitle').innerHTML = ' ' + jsTitleDelete;
	// set anchor
	//adesk_ui_anchor_set([ 'delete', manageID ].join('-'));
	adesk_ui_api_callback();
}


function sync_delete_fill(data) {
	// mode
	manageAction = 'delete';
	$('modeField').value = manageAction;
	// id
	if ( !data.row && data.id ) { // single result
		manageID = data.id;
	} 
	else if (data.row) { // list result
		var ids = [ ];
		for ( var i = 0; i < data.row.length; i++ ) {
			ids.push(data.row[i].id);
		}
		manageID = ids.join(',');
	}
	else if (data.rows) { // sometimes we have "rows" instead of "row"
		var ids = [ ];
		for ( var i = 0; i < data.rows.length; i++ ) {
			ids.push(data.rows[i].id);
		}
		manageID = ids.join(',');		
	}
	$('syncDeleteIDfield').value = manageID;
	// titles
	var rel = $('syncDeleteBox').getElementsByTagName('ul')[0];
	adesk_dom_remove_children(rel);
	if ( !data.row && data.id ) { // single result
		rel.appendChild(Builder.node("li", { className: "blacktext" }, [ Builder._text(data.sync_name) ]));
	}
	else if (data.row) { // list result
		for ( var i = 0; i < data.row.length; i++ ) {
			rel.appendChild(Builder.node("li", { className: "blacktext" }, [ Builder._text(data.row[i].sync_name) ]));
		}
	}
	else if (data.rows) { // sometimes we have "rows" instead of "row"
		for ( var i = 0; i < data.rows.length; i++ ) {
			rel.appendChild(Builder.node("li", { className: "blacktext" }, [ Builder._text(data.rows[i].sync_name) ]));
		}
	}
}

function sync_delete() {
	if ( !sync_delete_confirm() ) return false;
	var ids = $('syncDeleteIDfield').value;
	adesk_ui_api_call(jsDeleting);
	adesk_ajax_call_cb('awebdeskapi.php', 'sync!adesk_sync_delete', sync_delete_callback, ids);
	// then return FALSE! (form will be submitted only in case of error)
	return false;
}

function sync_delete_callback(xml, txt) {
	var ary = adesk_dom_read_node(xml, ( paginator_b64 ? adesk_b64_decode : null ));
	adesk_ui_api_callback();
	if ( ary.succeeded && ary.succeeded == 1 ) {
		adesk_result_show(ary.message);
		// flip back to list
		//sync_delete_hide();
		adesk_dom_toggle_display('syncDeletePanel', 'block');
		sync_list();
	} else {
		adesk_error_show(ary.message);
	}
}

function sync_delete_confirm() {
	return confirm( manageID.match(/,/) ? syncConfDeleteMulti : syncConfDeleteSingle );
}


/* unused */
function sync_delete_hide() {
	//$('syncListPanel').className = 'adesk_block';
	//$('syncDeletePanel').className = 'adesk_hidden';
	adesk_dom_toggle_display('syncDeletePanel', 'block');
	$('syncModeTitle').innerHTML = '';
	manageAction = 'list';
	manageID = 0;
	// load list table
	//if ( !adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible() )
	adesk_ui_api_call(jsLoading);
	sync_list();
	return false;
}

