<?php

require_once awebdesk_functions("ajax.php");

function subscriber_insert_post_web() {
	$lists = array();
	if ( isset($_POST['p']) ) {
		if (!is_array($_POST['p']))
			$_POST['p'] = array($_POST['p']);
		$lists = array_map('intval', $_POST['p']);
	} else {
		return adesk_ajax_api_result(false, _a("You did not select any lists, or you did not submit a POST request."));
	}

	$status = (int)adesk_http_param("status");
	$formid = (int)adesk_http_param('form');
	$noresponders = (int)adesk_http_param('noresponders');
	$sendoptin = (int)adesk_http_param('sendoptin');
	$instantresponders = (int)adesk_http_param('instantresponders');
	$lastmessage = (int)adesk_http_param('lastmessage');

	if (!$formid) $formid = 0;

	$ary = array(
		'id' => 0,
		'email' => trim((string)adesk_http_param('email')),
		'=cdate' => 'NOW()',
		//'=hash' => "MD5(CONCAT(id, email))",
	);

	$fullname  = trim((string)adesk_http_param('name')); // vBulletin API call
	if ($fullname) {
		$fullname = explode(" ", $fullname);
		$firstname = array_shift($fullname);
		$lastname = implode(" ", $fullname);
	}
	else {
		$firstname = trim((string)adesk_http_param('first_name'));
		$lastname = trim((string)adesk_http_param('last_name'));
	}

	// check email
	if ( !adesk_str_is_email($ary['email']) ) {
		return adesk_ajax_api_result(false, _a("Subscriber Email Address is not valid."));
	}

	// duplicates check
	$update = false;
	$addcounter = 0;
	// try to find this email in the system
	$found = subscriber_exists($ary['email']);
	// if subscriber is in the system (any list)
	if ( $found ) {
		// then loop through provided lists
		foreach ( $lists as $l ) {
			// if email is in this list
			if ( subscriber_exists($ary['email'], $l) ) {
				// get list info
				$list = list_select_row($l, false);
				// if list doesn't allow duplicates to subscribe
				if ( !$list['p_duplicate_subscribe'] ) {
					// complain
					return adesk_ajax_api_result(false, _a("You selected a list that does not allow duplicates. This email is in the system already, please edit that subscriber instead."));
				} else {
					// increase add counter here deliberately;
					// if he is found in a list to which he is already subscribed to, but it allows duplicates, we wan't to force a brand new subscriber creation here
					$addcounter++;
				}
			}
		}
	}
	// we should update if we found him, and not inserting him into all lists (then we would insert a brand new row)
	$update = ( $found and $addcounter < count($lists) );
	// if this subscriber should be updated rather than inserted, then run updater
	if ( $update ) {
		$id = (int)$found['id'];
	} else {
		/*
			INSERT NEW SUBSCRIBER
		*/
		$sql = adesk_sql_insert("#subscriber", $ary);
		if ( !$sql ) {
			return adesk_ajax_api_result(false, _a("Subscriber could not be added."));
		}
		$id = adesk_sql_insert_id();

		// update same record with hash, now that we have the ID
		adesk_sql_update_one('#subscriber', '=hash', 'MD5(CONCAT(id, email))', "`id` = '$id'");
	}

	// save custom fields
	if ( is_array(adesk_http_param('field')) ) {
		adesk_custom_fields_update_data(adesk_http_param('field'), '#list_field_value', 'fieldid', array('relid' => $id));
	}

	$r = array(
		'subscriber_id' => $id,
		'sendlast_should' => 0,
		'sendlast_did' => 0,
	);

	// save lists
	$admin = adesk_admin_get();
	foreach ( $lists as $l ) {
		$sdate_field = '=sdate';
		$sdate_value = 'NOW()';
		$responder = intval(!$noresponders);
		$ary2 = array(
			'id' => 0,
			'subscriberid' => $id,
			'listid' => $l,
			'formid' => 0,
			$sdate_field => $sdate_value,
			'=udate' => ( $status == 2 ? 'NOW()' : 'NULL' ),
			'status' => $status,
			'responder' => $responder,
			'sync' => 0,
			'=unsubreason' => 'NULL',
			'unsubcampaignid' => 0,
			'unsubmessageid' => 0,
			'=ip4' => "INET_ATON('127.0.0.1')",
			'first_name' => $firstname,
			'last_name' => $lastname,
			'sourceid' => 3,
		);
		$sql = adesk_sql_insert('#subscriber_list', $ary2);
		if ( !$sql ) {
			return adesk_ajax_api_result(false, _a("Subscriber could not be added."));
		}

		$subscriber = subscriber_select_row($id);
		if ( $status == 2 ) { // UNSUBSCRIBED actions
			// nothing here yet...
			if ( $instantresponders ) {
				// (re)send instant autoresponders
				mail_responder_send($subscriber, $l, 'unsubscribe');
			}
		} elseif ( $status == 1 ) { // SUBSCRIBED actions
			if ( $instantresponders ) {
				// (re)send instant autoresponders
				mail_responder_send($subscriber, $l, 'subscribe');
			}
			if ( $lastmessage ) {
				// (re)send last broadcast message
				$r['sendlast_should'] = 1;
				$r['sendlast_did'] += mail_campaign_send_last($subscriber, $l);
			}
		} else {//if ( $status == 0 ) { // UNCONFIRMED actions
			if ( $sendoptin ) {
				// (re)send opt in email
				mail_opt_send($subscriber, list_select_row($l), strval($l), $formid, null, 'in');
			}
		}

		subscriber_action_dispatch("subscribe", $subscriber, $l, null, null);
	}

	return adesk_ajax_api_added(_a("Subscriber"), $r);
}

function subscriber_insert_post() {
	$lists = array();
	if ( $_GET["p"] && isset($_POST["data_json"]) ) {
	  // unbounce webhook integration
    $json = json_decode($_POST["data_json"]);
    $_POST["p"] = $_GET["p"];
    $_POST["email"] = $json->email[0];
    $_POST["name"] = $json->name[0];
    $_POST["status"] = array($_GET["p"][0] => 1);
	}
	if ( isset($_POST['p']) and is_array($_POST['p']) and count($_POST['p']) > 0 ) {
		$lists = array_map('intval', $_POST['p']);
		$sdates = (array)adesk_http_param('sdate');
		$statuses = (array)adesk_http_param('status');
		$formid = (int)adesk_http_param('form');
		$noresponders = (array)adesk_http_param('noresponders');
		$sendoptin = (array)adesk_http_param('sendoptin');
		$instantresponders = (array)adesk_http_param('instantresponders');
		$lastmessage = (array)adesk_http_param('lastmessage');
	} else {

		return adesk_ajax_api_result(false, _a("You did not select any lists, or you did not submit a POST request."));
	}

	if (!$formid) $formid = 0;

	// make sure each list exists
	foreach ($lists as $l) {
	  $list = list_select_row($l);
	  if (!$list) return adesk_ajax_api_result( false, _a("List ID") . " " . $l . " " . _a("does not exist.") );
	}

	$ary = array(
		'id' => 0,
		'email' => trim((string)adesk_http_param('email')),
		'=cdate' => 'NOW()',
		//'=hash' => "MD5(CONCAT(id, email))",
	);

	$fullname  = trim((string)adesk_http_param('name')); // vBulletin API call
	if ($fullname) {
		$fullname = explode(" ", $fullname);
		$firstname = array_shift($fullname);
		$lastname = implode(" ", $fullname);
	}
	else {
		$firstname = trim((string)adesk_http_param('first_name'));
		$lastname = trim((string)adesk_http_param('last_name'));
	}

	// check email
	if ( !adesk_str_is_email($ary['email']) ) {
		return adesk_ajax_api_result(false, _a("Subscriber Email Address is not valid."));
	}

	// duplicates check
	$update = false;
	$addcounter = 0;
	// try to find this email in the system
	$found = subscriber_exists($ary['email']);

	// if subscriber is in the system (any list)
	if ( $found ) {
		// then loop through provided lists
		foreach ( $lists as $l ) {
			// if email is in this list
			if ( subscriber_exists($ary['email'], $l) ) {
				// get list info
				$list = list_select_row($l, false);
				// if list doesn't allow duplicates to subscribe
				if ( !$list['p_duplicate_subscribe'] ) {
					// complain
					return adesk_ajax_api_result(false, _a("You selected a list that does not allow duplicates. This email is in the system already, please edit that subscriber instead."));
				} else {
					// increase add counter here deliberately;
					// if he is found in a list to which he is already subscribed to, but it allows duplicates, we wan't to force a brand new subscriber creation here
					$addcounter++;
				}
			} else {
				// found in the system, but not in this list
				// we won't be adding him to this list, so we won't update the counter
				// (so it switches to update if all good)
				//$addcounter++;
			}
		}
	}

	// we should update if we found him, and not inserting him into all lists (then we would insert a brand new row)
	$update = ( $found and $addcounter < count($lists) );
	// if this subscriber should be updated rather than inserted, then run updater
	if ( $update ) {
		$id = (int)$found['id'];
		/*
		$_POST['id'] = $found['id'];
		# Don't pass in the old lists; if we didn't pass any statuses for them, and we likely
		# didn't, we'll clobber all of the old statuses those subscribers had on those lists,
		# putting them back to Unconfirmed.
		$oldlists = subscriber_get_lists($found['id']);dbg($_POST,1);
		#$_POST['p'] = array_unique(array_merge(array_keys($oldlists), $_POST['p']));
		foreach ( $oldlists as $listid => $listarr ) {
			if ( in_array($listid, $_POST['p']) ) continue;
			$_POST['p'][] = $listid;
			$_POST['status'][$listid] = $listarr['status'];
			if ( !$listarr['responder'] ) {
				if ( !isset($_POST['noresponders']) or !is_array($_POST['noresponders']) ) $_POST['noresponders'] = array();
				$_POST['noresponders'][$listid] = 1;
			}
		}dbg($_POST);
		return subscriber_update_post();
		*/
	} else {
		/*
			INSERT NEW SUBSCRIBER
		*/
		$sql = adesk_sql_insert("#subscriber", $ary);
		if ( !$sql ) {
			return adesk_ajax_api_result(false, _a("Subscriber could not be added."));
		}
		$id = (int)adesk_sql_insert_id();

		// update same record with hash, now that we have the ID
		adesk_sql_update_one('#subscriber', '=hash', 'MD5(CONCAT(id, email))', "`id` = '$id'");
	}

	// save custom fields
	if ( is_array(adesk_http_param('field')) ) {
		adesk_custom_fields_update_data(adesk_http_param('field'), '#list_field_value', 'fieldid', array('relid' => $id));
	}

	# Add their filter cache records.
	//filter_cache_subscriber($id, (bool)$update);

	$r = array(
		'subscriber_id' => $id,
		'sendlast_should' => 0,
		'sendlast_did' => 0,
	);

	// save lists
	$admin = adesk_admin_get();
	foreach ( $lists as $l ) {
		$list = list_select_row($l);
		$sdate_field = ( isset($sdates[$l]) ? 'sdate' : '=sdate' );
		$sdate_value = ( isset($sdates[$l]) ? $sdates[$l] : 'NOW()' );
		$status = ( isset($statuses[$l]) ? (int)$statuses[$l] : 0 );
		if ( isset($GLOBALS['_hosted_account']) && $status == 0 ) {
			// can't be "Unconfirmed" for hosted users
			// allow this for now - 5/19/2011
			//return adesk_ajax_api_result(false, _a("Subscriber status can't be Unconfirmed."));
		}
		$responder = (int)!isset($noresponders[$l]);
		$notifies = array();
		$ary2 = array(
			'id' => 0,
			'subscriberid' => $id,
			'listid' => $l,
			'formid' => 0,
			$sdate_field => $sdate_value,
			'=udate' => ( $status == 2 ? 'NOW()' : 'NULL' ),
			'status' => $status,
			'responder' => $responder,
			'sync' => 0,
			'=unsubreason' => 'NULL',
			'=ip4' => "INET_ATON('127.0.0.1')",
			'unsubcampaignid' => 0,
			'unsubmessageid' => 0,
			'first_name' => $firstname,
			'last_name' => $lastname,
			'sourceid' => 4,
		);
		$sql = adesk_sql_insert('#subscriber_list', $ary2);
		if ( !$sql ) {
			return adesk_ajax_api_result(false, _a("Subscriber could not be added."));
		}

		$subscriber = subscriber_select_row($id);
		if ( $status == 2 ) { // UNSUBSCRIBED actions
			// nothing here yet...
			if ( isset($instantresponders[$l]) ) {
				// (re)send instant autoresponders
				mail_responder_send($subscriber, $l, 'unsubscribe');
			}

			// admin notifications
			if ( $list["unsubscription_notify"] ) {
				// Full list array gets passed
				$notifies[] = $list;
			}
			if ( count($notifies) > 0 ) mail_admin_send($subscriber, $notifies, 'unsubscribe');

		} elseif ( $status == 1 ) { // SUBSCRIBED actions
			if ( isset($instantresponders[$l]) ) {
				// (re)send instant autoresponders
				mail_responder_send($subscriber, $l, 'subscribe');
			}
			if ( isset($lastmessage[$l]) ) {
				// (re)send last broadcast message
				$r['sendlast_should'] = 1;
				$r['sendlast_did'] += mail_campaign_send_last($subscriber, $l);
			}

			// admin notifications
			if ( $list["subscription_notify"] ) {
				// Full list array gets passed
				$notifies[] = $list;
			}
			if ( count($notifies) > 0 ) mail_admin_send($subscriber, $notifies, 'subscribe');

		} else {//if ( $status == 0 ) { // UNCONFIRMED actions
			// only available for downloaded users - otherwise hosted users can't have status = 0 (unconfirmed)
			// allow it for now - 5/19/2011
			//if ( !isset($GLOBALS['_hosted_account']) ) {
				if ( isset($sendoptin[$l]) ) {
					// (re)send opt in email
					mail_opt_send($subscriber, list_select_row($l), strval($l), $formid, null, 'in');
				}
			//}
		}

		subscriber_action_dispatch("subscribe", $subscriber, $l, null, null);
	}

	if ( isset($GLOBALS['_hosted_account']) ) {
		require(dirname(dirname(__FILE__)) . '/manage/subscriber.add.inc.php');
	}

	return adesk_ajax_api_added(_a("Subscriber"), $r);
}

function subscriber_update_post() {
	if ( isset($_POST['p']) and is_array($_POST['p']) and count($_POST['p']) > 0 ) {
		$lists = array_map('intval', $_POST['p']);
		$statuses = (array)adesk_http_param('status');
		$formid = (int)adesk_http_param('form');
		$noresponders = (array)adesk_http_param('noresponders');
		$sendoptin = (array)adesk_http_param('sendoptin');
		$sendoptout = (array)adesk_http_param('sendoptout');
		$instantresponders = (array)adesk_http_param('instantresponders');
		$lastmessage = (array)adesk_http_param('lastmessage');
		$unsubreason = (array)adesk_http_param('unsubreason');
	} else {
		return adesk_ajax_api_result(false, _a("You did not select any lists."));
	}

	if (!$formid) $formid = 0;

	$ary = array(
		'email' => trim((string)adesk_http_param('email')),
		'=hash' => "MD5(CONCAT(id, email))",
	);

	$fullname  = trim((string)adesk_http_param('name')); // vBulletin API call
	if ($fullname) {
		$fullname = explode(" ", $fullname);
		$firstname = array_shift($fullname);
		$lastname = implode(" ", $fullname);
	}
	else {
		$firstname = trim((string)adesk_http_param('first_name'));
		$firstname_list = (array)adesk_http_param('first_name_list');
		$lastname = trim((string)adesk_http_param('last_name'));
		$lastname_list = (array)adesk_http_param('last_name_list');
	}

	// check email
	if ( !adesk_str_is_email($ary['email']) ) {
		return adesk_ajax_api_result(false, _a("Subscriber Email Address is not valid."));
	}

	$id = (int)adesk_http_param("id");
	if ( !$id ) {
		return adesk_ajax_api_result(false, _a("Subscriber not provided."));
	}
	$sql = adesk_sql_update("#subscriber", $ary, "id = '$id'");
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Subscriber could not be updated."));
	}

	// save custom fields
	if ( is_array(adesk_http_param('field')) ) {
		$fields = adesk_http_param('field');
		$fields_array = array();
		// make sure the data ID (if passed) corresponds to this subscriber ID and field ID
		foreach ($fields as $fieldid_dataid => $value) {
			$tmparr = explode(",", $fieldid_dataid);
			if ( !isset($tmparr[1]) ) $tmparr[1] = 0;
			list($fieldid, $dataid) = array_map('intval', $tmparr);
			$where = array(
				"relid = '$id'",
				"fieldid = '$fieldid'",
			);
			// check for existing data row - if it's not there it will be added
			$data_row_id = (int)adesk_sql_select_one("SELECT id FROM #list_field_value WHERE " . implode(" AND ", $where));
			$fieldid_dataid = implode(",", array($fieldid, $data_row_id));
			$fields_array[$fieldid_dataid] = $value;
		}
		adesk_custom_fields_update_data($fields_array, '#list_field_value', 'fieldid', array('relid' => $id));
	}

	// delete old that are now deselected
	$s = subscriber_select_row($id);
	if ( $s ) {
		foreach ( $s['lists'] as $k => $v ) {
			if ( !in_array($k, $lists) ) {
				adesk_sql_delete('#subscriber_list', "subscriberid = '$id' AND listid = '$k'");
			}
		}
	}

	# Update their cache records.
	//filter_cache_subscriber($id, true);

	$r = array(
		'sendlast_should' => 0,
		'sendlast_did' => 0,
	);

	$admin = adesk_admin_get();
	// save lists
	foreach ( $lists as $l ) {
		$status = ( isset($statuses[$l]) ? (int)$statuses[$l] : 0 );
		if ( isset($GLOBALS['_hosted_account']) && $status == 0 ) {
			// can't be "Unconfirmed" for hosted users
			// allow it for now - 5/19/2011
			//return adesk_ajax_api_result(false, _a("Subscriber status can't be Unconfirmed."));
		}
		$sendoptoutlist =  (isset($sendoptout[$l]) ? (int)$sendoptout[$l] : 0);
		$responder = (int)!isset($noresponders[$l]);
		$firstname = ( isset($firstname_list[$l]) ? (string)$firstname_list[$l] : $firstname );
		$lastname = ( isset($lastname_list[$l]) ? (string)$lastname_list[$l] : $lastname );
		$exists = adesk_sql_select_one('=COUNT(*)', '#subscriber_list', "subscriberid = '$id' AND listid = '$l'");
		if ( $exists ) {
			$ary2 = array(
				'status' => $status,
				'responder' => $responder,
				'=udate' => ( $status == 2 ? 'NOW()' : 'NULL' ),
				'first_name' => $firstname,
				'last_name' => $lastname,
			);
			if ( $status != 2 ) {
				$ary2['=unsubreason'] = 'NULL';
				$ary2['=unsubcampaignid'] = 0;
				$ary2['=unsubmessageid'] = 0;
			}
			elseif ($status == 2) {
				$ary2['unsubreason'] = isset($unsubreason[$l]) ? $unsubreason[$l] : '';
			}
			//if unsusbcribing someone but sending them opt-out msg first, make sure to keep their status as 'active' for now
			if ($status=='2' && $sendoptoutlist) $ary2['status'] = 1;
			$sql = adesk_sql_update('#subscriber_list', $ary2, "subscriberid = '$id' AND listid = '$l'");
		} else {
			$ary2 = array(
				'id' => 0,
				'subscriberid' => $id,
				'listid' => $l,
				'formid' => 0,
				'=sdate' => 'NOW()',
				'=udate' => ( $status == 2 ? 'NOW()' : 'NULL' ),
				'status' => $status,
				'responder' => $responder,
				'sync' => 0,
				'first_name' => $firstname,
				'last_name' => $lastname,
				'sourceid' => 3,
			);
			if ( $status != 2 ) {
				$ary2['=unsubreason'] = 'NULL';
				$ary2['=unsubcampaignid'] = 0;
				$ary2['=unsubmessageid'] = 0;
			}
			//if unsusbcribing someone but sending them opt-out msg first, make sure to keep their status as 'active' for now
			if ($status=='2' && $sendoptoutlist) $ary2['status'] = 1;
			$sql = adesk_sql_insert('#subscriber_list', $ary2);
			$sid = ( $sql ? adesk_sql_insert_id() : 0 );
		}
		if ( !$sql ) {
			return adesk_ajax_api_result(false, _a("Subscriber could not be added."));
		}
		$subscriber = subscriber_select_row($id);
		$subact     = "subscribe";
		if ( $status == 2 ) { // UNSUBSCRIBED actions
			$subact = "unsubscribe";
			if ( $sendoptoutlist ) {
				// (re)send opt out email
				mail_opt_send($subscriber, list_select_row($l), $l, 0, null, 'out');
			}
			elseif ( isset($instantresponders[$l]) ) {
				// (re)send instant autoresponders
				mail_responder_send($subscriber, $l, 'unsubscribe');
			}
		} elseif ( $status == 1 ) { // SUBSCRIBED actions
			if ( isset($instantresponders[$l]) ) {
				// (re)send instant autoresponders
				mail_responder_send($subscriber, $l, 'subscribe');
			}
			if ( isset($lastmessage[$l]) ) {
				// (re)send last broadcast message
				$r['sendlast_should'] = 1;
				$r['sendlast_did'] += mail_campaign_send_last($subscriber, $l);
			}
		} else {//if ( $status == 0 ) { // UNCONFIRMED actions
			// only available for downloaded users - otherwise hosted users can't have status = 0 (unconfirmed)
			// allow it for now - 5/19/2011
			//if ( !isset($GLOBALS['_hosted_account']) ) {
				$subact = "";
				if ( isset($sendoptin[$l]) ) {
					// (re)send opt in email
					mail_opt_send($subscriber, list_select_row($l), $l, $formid, null, 'in');
				}
			//}
		}

		subscriber_action_dispatch($subact, $subscriber, $l, null, null);
	}

	return adesk_ajax_api_updated(_a("Subscriber"), $r);
}

function subscriber_delete_post() {
	$id = intval(adesk_http_param("id"));
	$listids = adesk_http_param("listids");

	# It can't really be null here--what must have happened is no checkboxes were selected,
	# so nothing was passed via POST.  Make it seem to be an empty array.
	if ($listids === null)
		$listids = array();

	return subscriber_delete($id, $listids);
}

function subscriber_delete($id, $listids = null, $countdelete = true) {
	$id        = intval($id);
	$admin     = $GLOBALS["admin"];
	$admincond = '';

	if (!withindeletelimits()) {
		return adesk_ajax_api_result(false, _a("You cannot delete any more subscribers in this billing period"), array("pastlimit" => 1));
	}

	if ($listids !== null && is_array($listids)) {
		# soft delete: only delete those list relations given in $listids that we have access to.
		$listids   = array_intersect(array_diff(array_map('intval', $listids), array(0)), $admin["lists"]);
		$liststr   = implode("','", $listids);
		$admincond = "AND listid IN ('$liststr')";
	} else {
		# hard delete: grab every list relation that we can.
		if ( !adesk_admin_ismain() ) {
				
$admin   = adesk_admin_get();
	        $uid = $admin['id'];
	if($uid != 1 ){
	$lists = adesk_sql_select_list("SELECT distinct listid FROM #user_p WHERE userid = $uid");


	//get the lists of users
	
	@$liststr = implode("','",$lists);
	}
	else
	{
	@$liststr = implode("','", $admin["lists"]);
	}



			
			//$liststr   = implode("','", $admin["lists"]);
			$admincond = "AND listid IN ('$liststr')";
		}
	}

	adesk_sql_delete('#subscriber_list', "subscriberid = '$id' $admincond");
	adesk_sql_delete('#subscriber_responder', "subscriberid = '$id' $admincond");
	if ( adesk_sql_select_one('=COUNT(*)', '#subscriber_list', "subscriberid = '$id'") == 0 ) {
		adesk_sql_delete('#subscriber', "id = '$id'");
		adesk_sql_delete('#list_field_value', "relid = '$id'");

		if (isset($GLOBALS["_hosted_account"]) && $countdelete) {
			adesk_sql_query("UPDATE #backend SET deletedsubs = deletedsubs + 1");
		}
	}

	if ( !function_exists('adesk_ajax_api_deleted') ) return true;

	return adesk_ajax_api_deleted(_a("Subscriber"));
}

function subscriber_delete_multi_post() {
	$ids      = strval(adesk_http_param("ids"));
	$listids  = adesk_http_param("listids");
	$filterid = (int)adesk_http_param("filter");

	if ($listids === null)
		$listids = array();

	$delete = subscriber_delete_multi($ids, $listids, $filterid);
	if ($delete["succeeded"] && $ids == "_all") $delete["message"] = _a("All subscribers deleted");
	return $delete;
}

function subscriber_delete_multi($ids, $listids = null, $filter = 0) {
	@set_time_limit(950 * 60);
	if ( $ids == '_all' ) {
		$tmp = array();
		$so = new adesk_Select();
		$so->slist = array('s.id');
		$so->remove = false;
		$filter = intval($filter);
		if ($filter > 0) {
			$admin = adesk_admin_get();
			$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'subscriber'");
			$so->push($conds);
		} else {
			$so->push("AND l.status = 1"); // subscribed = DEFAULT
		}
		$all = subscriber_select_array($so);
		foreach ( $all as $v ) {
			$tmp[] = $v['id'];
		}
	} else {
		$tmp = array_map("intval", explode(",", $ids));
	}
	$r = false;
	foreach ( $tmp as $id ) {
		$r = subscriber_delete($id, $listids);
		if (isset($r["pastlimit"]))
			break;
	}
	if (!$r) {
		$r = adesk_ajax_api_result(false, _a("No subscribers found"));
	}
	return $r;
}

function subscriber_bounce_reset($id, $what) {
	$admin = adesk_admin_get();
	if ( !$admin['pg_subscriber_edit'] ) {
		return adesk_ajax_api_result(false, _a("You do not have permission to reset subscriber bounces."));
	}
	$id = (int)$id;
	$subscriber = subscriber_select_row($id);
	if ( !$subscriber ) {
		return adesk_ajax_api_result(false, _a("Subscriber not found."));
	}
	$email = adesk_sql_escape($subscriber['email']);
	$update = array();
	// reset soft bounces
	if ( $what != 'hard' ) {
		$update['bounced_soft'] = 0;
		subscriber_bounce_lowercounts($id, $email, "soft");
		adesk_sql_delete('#bounce_data', "( `subscriberid` = '$id' OR `email` = '$email' ) AND `type` = 'soft'");
	}
	// reset hard bounces
	if ( $what != 'soft' ) {
		$update['bounced_hard'] = 0;
		subscriber_bounce_lowercounts($id, $email, "hard");
		adesk_sql_delete('#bounce_data', "( `subscriberid` = '$id' OR `email` = '$email' ) AND `type` = 'hard'");
		$affected = mysql_affected_rows();
	}
	$r = adesk_sql_update('#subscriber', $update, "`id` = '$id'");
	if ( $r ) {
		return adesk_ajax_api_result(true, _a("Subscriber bounces reset."), array('what' => $what));
	}
	return adesk_ajax_api_result(false, _a("Subscriber bounces were not reset."), array('what' => $what));
}

function subscriber_remove_batch() {
	//
}

function subscriber_remove_all() {}

function subscriber_list_in($subscriber, $listid) {
	$where = "`subscriberid` = '$subscriber[id]' AND `listid` = '$listid'";
	$found = (int)adesk_sql_select_one('=COUNT(*)', '#subscriber_list', $where);
	return (bool)( $found > 0 );
}

function subscriber_list_add($subscriber, $listid) {
	if ( !subscriber_list_in($subscriber, $listid) ) {
		$insert = array(
			'id' => 0,
			'subscriberid' => $subscriber['id'],
			'listid' => $listid,
			'formid' => 0,
			'=sdate' => 'NOW()',
			'=udate' => 'NULL',
			'status' => 1,
			'responder' => 1,
			'sync' => 0,
			'=unsubreason' => 'NULL',
			'unsubcampaignid' => 0,
			'unsubmessageid' => 0,
			'first_name' => $subscriber['first_name'],
			'last_name' => $subscriber['last_name'],
			'sourceid' => 7,
		);
		adesk_sql_insert('#subscriber_list', $insert);
	}
}

function subscriber_list_remove($subscriber, $listid) {
	if ( subscriber_list_in($subscriber, $listid) ) {
		//adesk_sql_delete('#subscriber_list', "`subscriberid` = '$subscriber[id]' AND `listid` = '$listid'");
		subscriber_softdelete($subscriber['id'], $listid);
	}
}
function subscriber_invalid_remove($id) {
	 require_once awebdesk_classes("email_checker.class.php");
	 
	  $emailChecker = new emailChecker;
	 
	 //settings(in future releases we wil try to automate this from admin settings)
	 //disposable emails
	  $emailChecker->filter_dea = 0; 
      $emailChecker->filter_google_dea = 0; 
     //dont touch these two settings. Not needed as is used as backend
	  $emailChecker->fix_typos = 0; 
      $emailChecker->auto_correct_typos = 0;
	 //dont touch above two settings 
	  
	  //here comes the gamechanger 
      $emailChecker->check_mx = 1; 
      $emailChecker->smtp_test = 0;
	  
	   
      $emailChecker->supress_output = 1; 
		
		//get email
		require_once(adesk_admin('functions/subscriber.select.php'));
		$subrow = subscriber_select_row($id);
	 
	// $email="jhjghjg@jhkjhkjhkhjkhkj.com";
	// die($email);
	 $e = $emailChecker->check($subrow['email']);
	  $e = $e['result'];
	 //valid email passed= $e['success'] 
	 if($e['success'])
	 return false;//or false
	 else
	 return true;
	 
	 
}
function subscriber_softdelete($id, $listid) {
	$id     = intval($id);
	$listid = intval($listid);

	adesk_sql_query("
		DELETE FROM
			#subscriber_list
		WHERE
			subscriberid = '$id'
		AND
			listid = '$listid'
	");

	adesk_sql_query("
		DELETE FROM
			#subscriber_responder
		WHERE
			subscriberid = '$id'
		AND
			listid = '$listid'
	");

	$c = (int)adesk_sql_select_one("SELECT COUNT(*) FROM #subscriber_list WHERE subscriberid = '$id'");
	if ($c < 1)
		subscriber_delete($id);
}

function subscriber_update_info($subscriber, $field, $value) {
	$custom = preg_match('/^\d+$/', $field);
	if ( $custom ) {
		// update custom field if exists, otherwise set it
		$where = "`relid` = '$subscriber[id]' AND `fieldid` = '$field'";
		$dataid = (int)adesk_sql_select_one('=COUNT(*)', '#list_field_value', $where);
		if ( $dataid > 0 ) {
			adesk_sql_update_one('#list_field_value', 'val', $value, $where);
		} else {
			$insert = array(
				'id' => 0,
				'relid' => $subscriber['id'],
				'fieldid' => $field,
				'val' => $value,
			);
			adesk_sql_insert('#list_field_value', $insert);
		}
	} else {
		// update regular field (if exists)
		if ( in_array($field, array_keys($subscriber)) ) {
			if ( in_array($field, array('first_name', 'last_name')) ) {
				adesk_sql_update_one('#subscriber_list', $field, $value, "`subscriberid` = '$subscriber[id]'");
			} else {
				adesk_sql_update_one('#subscriber', $field, $value, "`id` = '$subscriber[id]'");
			}
			if ( $field == 'email' ) {
				adesk_sql_update_one('#subscriber', '=hash', 'MD5(CONCAT(id, email))', "`id` = '$subscriber[id]'");
			}
		}
	}
}

function subscriber_update_email($subscriberid, $email) {
	$subscriberid = (int)$subscriberid;
	if ( !$subscriberid ) {
		return adesk_ajax_api_result(false, _a("Subscriber not provided."));
	}
	$subscriber = subscriber_select_row($subscriberid);
	if ( !$subscriber ) {
		return adesk_ajax_api_result(false, _a("Subscriber not found."));
	}

	// now check if any other subscriber has this email address already
	$emailesc = adesk_sql_escape($email);
	$found = (int)adesk_sql_select_one("id", "#subscriber", "email = '$emailesc' AND id != '$subscriberid'");
	if ( $found ) {
		return adesk_ajax_api_result(false, _a("This email address is already used by another subscriber."), array('id' => $found));
	}

	$sql = adesk_sql_update_one("#subscriber", "email", $email, "id = '$subscriberid'");
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("New email address for this subscriber could not be saved."));
	}

	return adesk_ajax_api_result(true, _a("Email address has been updated for this subscriber."));
}

function subscriber_responder_log($subscriberid, $listid, $campaignid, $messageid) {
	$insert = array(
		'id' => 0,
		'subscriberid' => (int)$subscriberid,
		'listid' => (int)$listid,
		'campaignid' => (int)$campaignid,
		'messageid' => (int)$messageid,
		'=sdate' => 'NOW()',
	);
	adesk_sql_insert('#subscriber_responder', $insert);
}

function subscriber_add_valid() {
	if ( !isset($GLOBALS['admin_subscribers_count']) ) {
		$GLOBALS['admin_subscribers_count'] = limit_count($GLOBALS['admin'], 'subscriber');
	}
	$valid = withinlimits('subscriber', $GLOBALS['admin_subscribers_count'] + 1, $GLOBALS['admin']);
	return $valid;
}

function subscriber_add_increment() {
	if (isset($GLOBALS["admin_subscribers_count"]))
		$GLOBALS["admin_subscribers_count"]++;
}

function subscriber_optin_post() {
	$id = intval(adesk_http_param("id"));
	$optid = intval(adesk_http_param("optid"));
	if ( !$optid ) $optid = 1;
	return subscriber_optin($id, $optid);
}

function subscriber_optin($id, $optid) {
	require_once(adesk_admin('functions/optinoptout.php'));
	require_once(adesk_admin('functions/mail.php'));
	$id        = intval($id);
	$optid     = intval($optid);
	$admin     = $GLOBALS["admin"];
	$admincond = '';

	# grab every list relation that we can.
	if ( !adesk_admin_ismain() ) {
		//$liststr   = implode("','", $admin["lists"]);
			
$admin   = adesk_admin_get();
	        $uid = $admin['id'];
	if($uid != 1 ){
	$lists = adesk_sql_select_list("SELECT distinct listid FROM #user_p WHERE userid = $uid");


	//get the lists of users
	
	@$liststr = implode("','",$lists);
	}
	else
	{
	@$liststr = implode("','", $admin["lists"]);
	}



		
		$admincond = "AND listid IN ('$liststr')";
	}

	// get subscriber
	$subscriber = subscriber_select_row($id);
	$listids = array();
	$list = null;
	$formid = 0;

	if (!isset($_SESSION["nla"])) {
		$sql = adesk_sql_query("SELECT * FROM #subscriber_list WHERE status = 0 AND subscriberid = '$id' $admincond");
		while ( $row = adesk_sql_fetch_assoc($sql) ) {
			$listids[] = $row['listid'];
			if ( !$list ) {
				$list = list_select_row($row["listid"]);
				$formid = (int)$row['formid'];
			}
		}
	} else {
		$nl      = (int)$_SESSION["nla"];
		$row     = adesk_sql_select_row("SELECT * FROM #subscriber_list WHERE status = 0 AND subscriberid = '$id' AND listid = '$nl'");
		$listids = array($row["listid"]);
		$list    = list_select_row($row["listid"]);
		$formid  = (int)$row['formid'];
	}

	if ( !$list ) return false;

	// get optin
	$optin = optinoptout_select_row($optid);

	// send optin
	mail_opt_send($subscriber, $list, implode(',', $listids), $formid, $optin, 'in');

	if ( !function_exists('adesk_ajax_api_result') ) return true;

	return adesk_ajax_api_result(true, _a("Email Reminder sent."));
}

function subscriber_optin_multi_post() {
	$ids      = strval(adesk_http_param("ids"));
	$optid    = (int)adesk_http_param("optid");
	$filterid = (int)adesk_http_param("filter");

	if ( !$optid ) $optid = 1;

	return subscriber_optin_multi($ids, $optid, $filterid);
}

function subscriber_optin_multi($ids, $optid, $filter = 0) {
	@set_time_limit(950 * 60);
	if ( $ids == '_all' ) {
		$tmp = array();
		$so = new adesk_Select();
		$so->slist = array('s.id');
		$so->remove = false;
		$filter = intval($filter);
		if ($filter > 0) {
			$admin = adesk_admin_get();
			$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'subscriber'");
			$so->push($conds);
		} else {
			$so->push("AND l.status = 0"); // unconfirmed = DEFAULT
		}
		$all = subscriber_select_array($so);
		foreach ( $all as $v ) {
			$tmp[] = $v['id'];
		}
	} else {
		$tmp = array_map("intval", explode(",", $ids));
	}
	foreach ( $tmp as $id ) {
		$r = subscriber_optin($id, $optid);
	}
	return $r;
}

$GLOBALS["subscriber_domaincodes"] = array(
	"ameritech.net" => 1,
	"awebdesk.com" => 1,
	"att.net" => 1,
	"bellsouth.net" => 1,
	"flash.net" => 1,
	"nvbell.net" => 1,
	"pacbell.net" => 1,
	"prodigy.net" => 1,
	"sbcglobal.com" => 1,
	"sbcglobal.net" => 1,
	"snet.net" => 1,
	"swbell.net" => 1,
	"wans.net" => 1,
);

function subscriber_delayed($addr) {
	global $subscriber_domaincodes;

	$expl = explode("@", $addr);

	# ?!
	if (count($expl) < 2)
		return true;

	$domain = $expl[1];

	# We don't track this domain--we're not delayed.
	if (!isset($subscriber_domaincodes[$domain]))
		return false;

	$code = (int)$subscriber_domaincodes[$domain];
	$c = (int)adesk_sql_select_one("SELECT * FROM #delay WHERE code = '$code'");

	# If we found the code in the table, then we're delayed.
	return $c > 0;
}

?>
