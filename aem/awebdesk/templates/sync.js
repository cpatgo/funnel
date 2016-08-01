// set unload
window.onbeforeunload = null;
window.onbeforeunload = function () {
	if ( somethingChanged ) {
		return messageLP;
	}
}


function sync_page_changed(newLocation, historyData) {
	// hide all modals
	if ( $('syncDeletePanel').style.display == 'block' ) $('syncDeletePanel').style.display = 'none';
	if ( $('syncRunPanel').style.display == 'block' ) $('syncRunPanel').style.display = 'none';
	// get default anchor array
	if ( manageAction == 'list' ) {
		var arr = [ manageAction, sortID ];
	} else {
		var arr = [ manageAction, manageID, /*step=*/ 1 ];
	}
	// get provided anchor string
	var anchor = newLocation;
	// break the requested one into array
	var args = anchor.split('-');
	// fix arguments array
	for ( var i = 0; i < arr.length; i++ )
		if ( args[i] === undefined )
			args[i] = arr[i];
	if ( args[0] == '' ) args[0] = 'list';
	if ( newLocation == '' ) adesk_ui_rsh_save(args.join('-'));
	if ( args[0] == 'list' ) {
		// sorter
		if ( args[1] != arr[1] ) {
			//sortID = args[1];
			manageID = 0;
			manageAction = 'list';
		}
		sync_list();
	} else if ( args[0] == 'delete' && ( args[1] > 0 || args[1].match(/,/) ) ) {
		// delete
		sync_delete_show(args[1]);
	} else if ( args[0] == 'test' ) {
		// test
		sync_run(args[1], true);
	} else if ( args[0] == 'run' ) {
		// run
		sync_run(args[1], false);
	} else {
		var step = ( args[2] ? args[2] : 1 );
		// add/edit
		//var action = ( args[1] == 'edit' ? 'edit' : 'add' );
		//var id = ( action == 'add' ? 1 : ( args[1] ? args[1] : 0 ) );
		if ( args[0] != arr[0] ) {
			frmArr = blank;
		} else {
			frmArr = adesk_form_post($('addSyncForm'));
			frmArr.id = args[1];
		}
		sync_form_show(( args[1] ? args[1] : 0 ), step);
	}
}

function runPage() {
	adesk_ui_anchor_init();
	sync_page_changed(adesk_ui_anchor_get(), null);
}

