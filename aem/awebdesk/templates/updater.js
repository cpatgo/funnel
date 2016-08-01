function auth_check() {
	adesk_ui_api_call(jsLoading);

	var post = { "password": $("authPassword").value };
	adesk_ajax_post_cb(apipath, 'instup!auth_check', auth_check_cb, post);
}

function auth_check_cb(xml) {
	var ary = adesk_dom_read_node(xml, adesk_b64_decode);
	adesk_ui_api_callback();
	if ( ary.succeeded && ary.succeeded == 1 ) {
		updater_step();
		adesk_result_show(ary.message);
		$('dl_s').value = ary.dl_s;
		$('dl_s').focus();
	} else {
		adesk_error_show(ary.message);
	}
}


function updater_step() {
	step++;
	if ( $('auth') ) $('auth').className = ( step == 1 ? 'adesk_block' : 'adesk_hidden' );
	if ( $('backend') ) $('backend').className = ( step == 2 ? 'adesk_block' : 'adesk_hidden' );
	if ( $('checks') ) $('checks').className = ( step == 4 ? 'adesk_block' : 'adesk_hidden' );
	if ( $('settings') ) $('settings').className = ( step == 5 ? 'adesk_block' : 'adesk_hidden' );
	if ( $('updater') ) $('updater').className = ( step == 6 ? 'adesk_block' : 'adesk_hidden' );
	if ( $('backupwarning') ) $('backupwarning').className = ( step != 6 ? 'backupwarning' : 'adesk_hidden' );
	if ( $('langchangerbox') ) $('langchangerbox').className = ( step != 6 ? 'adesk_block' : 'adesk_hidden' );
	var menu = $('updatermenu').getElementsByTagName('li');
	for ( var i = 0; i < menu.length; i++ ) {
		if ( step == i + 1 ) {
			menu[i].className = 'currentstep';
		} else if ( step < i + 1 ) {
			menu[i].className = 'nextstep';
		} else {
			menu[i].className = 'previousstep';
		}
	}
}

function updater_next() {
	if ( step == 4 ) {
		updater_step();
		if ( ask4URL ) {
			//$('murl').focus();
		} else {
			updater_next();
		}
	} else /*if ( step == 5 )*/ {
		plink_send();
	}
}


function plink_send() {
	adesk_ui_api_call(jsLoading);
	adesk_ajax_call_cb(apipath, 'instup!plink_send', plink_send_cb, $('murl').value);
}

function plink_send_cb(xml) {
	var ary = adesk_dom_read_node(xml, adesk_b64_decode);
	adesk_ui_api_callback();
	if ( ary.succeeded && ary.succeeded == 1 ) {
		adesk_result_show(ary.message);
		updater_step();
		// start it!
		$('updateriframe').src = plink + "/awebdesk/scripts/updateri.php";
	} else {
		adesk_error_show(ary.message);
	}
}


