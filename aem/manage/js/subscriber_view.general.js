{literal}

function subscriber_view_general_load(id) {
	$("general").className = "adesk_block";
}

function subscriber_view_general_bounces() {
	$('bounces').style.display = 'block';
	return false;
}

function subscriber_bounce_reset(what) {
	if ( !confirm(jsAreYouSure) ) return false;
	adesk_ui_api_call(jsResetting);
	adesk_ajax_call_cb("awebdeskapi.php", "subscriber.subscriber_bounce_reset", subscriber_bounce_reset_cb, subscriber_view_id, what);
	return false;
}

function subscriber_bounce_reset_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
	} else {
		adesk_error_show(ary.message);
		return;
	}
	// now clear out the scene
	if ( ary.what != 'hard' ) {
		// do soft
		$('softBounceReset').innerHTML = 0;
		if ( !$('hardBounceReset') ) {
			// we had only these, remove "both"
			$('bothBounceReset').innerHTML = '';
			$('bothBounceView').innerHTML = '';
		}
	}
	if ( ary.what != 'soft' ) {
		// do hard
		$('hardBounceReset').innerHTML = 0;
		if ( !$('softBounceReset') ) {
			// we had only these, remove "both"
			$('bothBounceReset').innerHTML = '';
			$('bothBounceView').innerHTML = '';
		}
	}
	if ( ary.what == 'both' ) {
		// do for both
		$('bothBounceReset').innerHTML = '';
		$('bothBounceView').innerHTML = '';
	}
}

{/literal}
