function install_next() {
	if ( step == 1 ) {
		// check requirements
		install_step();
	} else if ( step == 2 ) {
		// check engine database info
		db_check('engine', 0);
	} else if ( step == 3 ) {
		// check authentication database info
		if ( $('authtyperemote').checked ) {
			$('newinstalladmin').className = 'adesk_hidden';
			$('oldinstalladmin').className = 'adesk_block';
			db_check('auth', 0);
		} else {
			$('newinstalladmin').className = 'adesk_block';
			$('oldinstalladmin').className = 'adesk_hidden';
			// just increment step
			install_step();
		}
	} else if ( step == 4 ) {
		// check software settings
		admin_check($('authtyperemote').checked);
	} else if ( step == 5 ) {
		window.location = 'index.php';
	}
}

function install_step() {
	step++;
	$('checks').className = ( step == 1 ? 'adesk_block' : 'adesk_hidden' );
	$('engine').className = ( step == 2 ? 'adesk_block' : 'adesk_hidden' );
	$('auth').className = ( step == 3 ? 'adesk_block' : 'adesk_hidden' );
	$('settings').className = ( step == 4 ? 'adesk_block' : 'adesk_hidden' );
	$('installer').className = ( step == 5 ? 'adesk_block' : 'adesk_hidden' );
	if ( $('langchangerbox') ) $('langchangerbox').className = ( step != 5 ? 'adesk_block' : 'adesk_hidden' );
	var menu = $('installmenu').getElementsByTagName('li');
	for ( var i = 0; i < menu.length; i++ ) {
		if ( step == i - 1 ) {
			menu[i].className = 'currentstep';
		} else if ( step < i - 1 ) {
			menu[i].className = 'nextstep';
		} else {
			menu[i].className = 'previousstep';
		}
	}
}

function db_check(type, clear) {
	var post = {
		type: type,
		host: $(type + 'Host').value,
		user: $(type + 'User').value,
		pass: $(type + 'Pass').value,
		name: $(type + 'Name').value,
		create: (type == 'engine' && $(type + 'Create').checked) ? 1 : 0,
		clear: clear
	};
	if ( post.host == '' ) post.host = 'localhost';
	adesk_ui_api_call(jsLoading, 60);
	adesk_ajax_post_cb(apipath, 'instup!database_check', ( type == 'engine' ? db_check_engine_cb : db_check_auth_cb ), post);
}

function db_check_engine_cb(xml) {
	var ary = adesk_dom_read_node(xml, adesk_b64_decode);
	adesk_ui_api_callback();
	if ( ary.succeeded && ary.succeeded == 1 ) {
		if ( ary.found > 0 ) {
			if ( confirm(sprintf(installerFoundTables, appname) + installerFoundTablesOptions) ) {
				if ( confirm(installerRemoveTablesConfirm) ) {
					db_check('engine', 1);
				}
			}
		} else {
			adesk_result_show(ary.message);
			install_step();
		}
	} else {
		adesk_error_show(ary.message);
	}
}

function db_check_auth_cb(xml) {
	var ary = adesk_dom_read_node(xml, adesk_b64_decode);
	adesk_ui_api_callback();
	if ( ary.succeeded && ary.succeeded == 1 ) {
		if ( typeof ary.tables['aweb_globalauth'] == 'undefined' && !adesk_array_has(ary.tables, 'aweb_globalauth') ) {
			alert(installerAuthTableMissing);
		} else {
			adesk_result_show(ary.message);
			install_step();
		}
	} else {
		adesk_error_show(ary.message);
	}
}



function admin_check(remote) {
	var post = adesk_form_post('siteForm');
	if ( post.password == '' ) {
		alert(jsUserFormPasswordBlank);
		$('adminpassword').focus();
		return;
	}
	post.remoteauth = ( remote ? 1 : 0 );
	if ( remote ) {
		adesk_ui_api_call(jsChecking, 60);
	} else {
		adesk_ui_api_call(jsSaving);
	}
	adesk_ajax_post_cb(apipath, 'instup!admin_check', admin_check_cb, post);
}

function admin_check_cb(xml) {
	var ary = adesk_dom_read_node(xml, adesk_b64_decode);
	adesk_ui_api_callback();
	if ( ary.succeeded && ary.succeeded == 1 ) {
		adesk_result_show(jsInstalling);
		install_step();
		$('installeriframe').src = plink + "/awebdesk/scripts/installi.php";
	} else {
		adesk_error_show(ary.message);
	}
}


