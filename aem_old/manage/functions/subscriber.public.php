<?php

function subscriber_codes($result) {
	$r = array(
		'', // 0=lists
		'', // 1=codes
	);
	foreach ( $result as $k => $v ) {
		$r[0] .= (int)$k . ",";
		$r[1] .= ( isset($v['error_code']) ? (int)$v['error_code'] : 0 ) . ",";
	}
	$r[0] = trim($r[0], ',');
	$r[1] = trim($r[1], ',');
	return $r;
}


function subscriber_subscribe($id = 0, $email, $first_name = '', $last_name = '', $lists = array(), $subscription_form_id = 0, $fields = array(), $checkfield = true) {
	require_once awebdesk_functions("ajax.php");
	$r = array();
	$addon = array('confirm' => false, 'error_code' => 0);
	$fetchedLists = array();

	// If no lists selected.
	if ( !$lists ) {
		$addon['error_code'] = '9';
		return array( 0 => adesk_ajax_api_result( false, _a("Subscription could not be processed since you did not select a list. Please select a list and try again."), $addon ) );
	}

	if ( !adesk_str_is_email($email) ) {
			$addon['error_code'] = '8';
			return array( 0 => adesk_ajax_api_result( false, _a("E-mail address is invalid."), $addon ) );
	}

	$listids = implode(",", $lists);

	if ( !is_null($id) && $id > 0 ) {
		$subscriber = adesk_sql_select_row("SELECT email FROM #subscriber WHERE id = '$id'");

		if ( $subscriber ) {
			$subscriber = subscriber_exists($subscriber["email"]);
		} else {
			$addon['error_code'] = '19';
			return array( 0 => adesk_ajax_api_result( false, _a("Subscriber ID is invalid."), $addon ) );
		}
	} else {
		$email = trim((string)$email);
		$subscriber = subscriber_exists($email);

		// duplicates check
		$update = false;
		$addcounter = 0;
		// if subscriber is in the system (any list)
		if ( $subscriber ) {
			// then loop through provided lists
			foreach ( $lists as $l ) {
				// if email is in this list
				if ( subscriber_exists($email, $l, 'exact', 1) ) {
					// get list info
					$fetchedLists[$l] = $list = list_select_row($l);
					if ( !$list ) continue;
					// if list doesn't allow duplicates to subscribe
					if ( !$list['p_duplicate_subscribe'] ) {
						// complain
						$addon['error_code'] = '21';
						$r[$list["id"]] = adesk_ajax_api_result( false, _a("You selected a list that does not allow duplicates. This email is in the system already, please edit that subscriber instead."), $addon );
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
		$update = ( $subscriber and $addcounter < count($lists) );
		// if this subscriber should be updated rather than inserted, then run updater
		if ( !$update ) {
			// reset the subscriber into "not found" so we can insert a new row(s)
			$subscriber = false;
		}

	}

	$user_ip = ( isset($_SERVER['REMOTE_ADDR']) ? adesk_str_noipv6($_SERVER['REMOTE_ADDR']) : '127.0.0.1' );
	$user_ua = ( isset($_SERVER['HTTP_USER_AGENT']) and $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : '';

	if ( $checkfield ) {
		// checking to see if name is entered
		if ( trim((string)$first_name) == '' && trim((string)$last_name) == '' ) {
			// then loop through provided lists
			foreach ( $lists as $l ) {
				// get list info
				$fetchedLists[$l] = $list = list_select_row($l);
				if ( !$list ) continue;
				// if list doesn't allow subscriptions with empty names
				if ( $list['require_name'] ) {
					// complain
					$addon['error_code'] = '16';
					$r[$list["id"]] = adesk_ajax_api_result( false, _a("Your subscription request for this list could not be processed as you must type your name."), $addon );
				}
			}
		}
		// checking to see if all required fields are entered.
		$list_fields = list_get_fields($lists, true);
		foreach ( $list_fields as $field ) {
			# Backwards compatibility
			$fieldkey = $field["id"] . ",0";
			if ( !isset($fields[$fieldkey]) and isset($fields[$field["id"]]) ) {
				$fields[$fieldkey] = $fields[$field["id"]];
			}
			// check for presence of required
			if ( $field["req"] && !adesk_custom_fields_required_check($field["id"], $fields) ) {
				// missing a required field
				$addon['error_code'] = '2';
				return array(0 => adesk_ajax_api_result( false, _a("Your subscription request for this list could not be processed as you are missing required fields."), $addon ) );
			}
		}
		// 2do: check if all required fields are entered
		//dbg('2do: check if all required fields are entered');
	}

	if ( count($r) == count($lists) ) {
		return $r;
	}

	// If subscriber does not exist across any lists, insert into #subscriber table
	if ( !$subscriber ) {


		//moved to top of function

		// check email validity
	/*	if ( !adesk_str_is_email($email) ) {
			$addon['error_code'] = '8';
			return array( 0 => adesk_ajax_api_result( false, _a("E-mail address is invalid."), $addon ) );
		}
*/
		$ary = array(
			'id' => 0,
			'email' => $email,
			'=cdate' => 'NOW()',
			'=ip' => "INET_ATON('$user_ip')",
			//'=hash' => "MD5(CONCAT(id, email))",
		);
		if ( $user_ua == '' ) {
			$ary['=ua'] = 'NULL';
		} else {
			$ary['ua'] = $user_ua;
		}

		$sql = adesk_sql_insert("#subscriber", $ary);

		if (!$sql) {
			$addon['error_code'] = '0';
			return array( 0 => adesk_ajax_api_result( false, _a("Unknown response code. Please resubmit the subscription form."), $addon ) );
		}

		$id = adesk_sql_insert_id();

		// update same record with hash, now that we have the ID
		adesk_sql_update_one('#subscriber', '=hash', 'MD5(CONCAT(id, email))', "`id` = '$id'");

		# We still need to add them to the filter cache.
		//filter_cache_subscriber($id, false);

	} else {

		// Subscriber already exists
		$id = $subscriber["id"];

		// update subscriber info
		$ary = array(
			'=ip' => "INET_ATON('$user_ip')",
		);
		if ( $user_ua == '' ) {
			$ary['=ua'] = 'NULL';
		} else {
			$ary['ua'] = $user_ua;
		}
		adesk_sql_update("#subscriber", $ary, "id = '$id'");

		# Update their cache records.
		//filter_cache_subscriber($id, true);

	}

	// Pull the complete subscriber info (just inserted/changed) so we have it for later use
	$subscriber = subscriber_exists($email);
	$info = adesk_sql_select_row("SELECT *, INET_NTOA(ip) AS ip FROM #subscriber WHERE id = '$id'");
	$subscriber = array_merge($subscriber, $info);

	// save custom fields
	// This needs to be processed here because functions below use this information
	if ( is_array($fields) ) {
		$fields = adesk_custom_fields_relate("#list_field_value", $fields, $id);
		adesk_custom_fields_update_data($fields, '#list_field_value', 'fieldid', array('relid' => $id));
	}

	$lists = array_map('intval', $lists);
	require_once adesk_admin("functions/form.php");
	require_once adesk_admin("functions/optinoptout.php");

	if ( $subscription_form_id = (int)$subscription_form_id ) {
		$form = form_select_row($subscription_form_id);
		if ( !$form ) $subscription_form_id = 0;
	} else {
		$form = false;
		//$subscription_form_id = 0;
	}
	if ( $form ) {
		// If the Form's optinoptout is set to send a confirmation for each list: #form.emailconfirmations = 1
		if ($form["emailconfirmations"]) {
			// Set to null so it uses the lists' optinoptout in the loop further down
			$optinoptout = null;
		}
		else {
			// Otherwise use the forms' optinoptout which is set on the Edit Form page
			$optinoptout = optinoptout_select_row($form["optinoptout"]);
		}
	} else {
		$form = form_select_row(1000);
		$optinoptout = null;
	}

	$form_id = ( $subscription_form_id != 0 ? (int)$subscription_form_id : 1000 );
	if ( $form_id and $form_id != 1000 ) $GLOBALS['admin']['lists'] = adesk_sql_select_list("SELECT id FROM #list");

/*	//$formid = ( $subscription_form_id != 0 ? (int)$subscription_form_id : 1000 );
	//$form = form_select_row($formid);

	// Form optinoptout is valid
	if ( $form && $form["optinoptout"] > 0 ) {
		$optinoptout = optinoptout_select_row($form["optinoptout"]);
	} else { // NEVER GETS HERE, SINCE $formid IS ALWAYS SET AND GREATER THAN 0, ACCORDING TO OUR LOGIC
		$optinoptout = optinoptout_select_row(1);
	}
*/
	$sent = false;

	$responders = $notifies = array();

	// Loop through selected lists
	foreach ( $lists as $l ) {/*1*/
		$addon = array('confirm' => false, 'error_code' => 0);
		$list = ( isset($fetchedLists[$l]) ? $fetchedLists[$l] : list_select_row($l) );
		if ( !$list ) continue;

		// Check subscriber limits
		if ( !list_valid($list) ) {
			$addon['error_code'] = '1';
			$r[$list["id"]] = adesk_ajax_api_result( false, _a("This list is currently not accepting subscribers. This list has met its top number of allowed subscribers."), $addon );
		} else {/*2*/

			// Check blocked settings
			//if ( list_block($email, $l) > 0 ) {
			//	$addon['error_code'] = '20';
			//	$r[$list["id"]] = adesk_ajax_api_result( false, _a("Blocked settings."), $addon );
			//} else {/*3*/

				// Check exclusion settings
				if ( exclusion_match($email, $l) > 0 ) {
					$addon['error_code'] = '17';
					$r[$list["id"]] = adesk_ajax_api_result( false, _a("This e-mail address is on the global exclusion list."), $addon );
				} else {/*4*/

					// Check if subscriber is already subscribed to this list
					$exists = adesk_sql_select_one('=COUNT(*)', '#subscriber_list', "subscriberid = '$id' AND listid = '$l' AND status = 1");

					if ( $exists ) {
						$addon['error_code'] = '3';
						$r[$list["id"]] = adesk_ajax_api_result( false, _a("This e-mail address is already subscribed to this mailing list."), $addon );
					} else {/*5*/

						//if ( $list["p_duplicate_subscribe"] ) {
							//$addon['error_code'] = '21';
							//$r[$list["id"]] = adesk_ajax_api_result( false, _a("You selected a list that does not allow duplicates. This email is in the system already, please edit that subscriber instead."), $addon );
						if ( isset($r[$list["id"]]) ) {
							// do nothing, we already dealt with this one
						} else {/*6*/

							adesk_sql_delete('#subscriber_list', "subscriberid = '$id' AND listid = '$l'"); // remove all old unconfirmed/unsubscribed rows

/*
							if ( !$thisoptin ) { // NEVER GETS HERE BECAUSE $optinoptout IS ALWAYS PULLING BASED ON $formid - EITHER $formid OR 1000
								if ( $list["optinoptout"] > 0 ) {
									$thisoptin = optinoptout_select_row($list["optinoptout"]);
								}

								// send list optin for each list
								mail_opt_send($subscriber, $list, $formid, $thisoptin, "in");
							}
							else {
								if ( !$sent ) {
									// send formid optin once
									mail_opt_send($subscriber, $list, $formid, $thisoptin, "in");
									$sent = true;
								}
							}
*/
							$thisoptin = ( $optinoptout ? $optinoptout : $list ); // null means "use list's"
							$status = (int)!$thisoptin['optin_confirm'];

							//$status = ($status) ? (int)!$list['require_optin'] : $status;

							$ary = array(
								'subscriberid' => $id,
								'listid' => $l,
								'formid' => $subscription_form_id,
								'=sdate' => 'NOW()',
								'=udate' => 'NULL',
								'status' => $status,
								'sync' => 0,
								'first_name' => trim((string)$first_name),
								'last_name' => trim((string)$last_name),
								'=ip4' => "INET_ATON('$user_ip')",
								'sourceid' => $thisoptin['optin_confirm'] ? 6 : 5,
							);
							if ( $status != 2 ) {
								$ary['=unsubreason'] = 'NULL';
								$ary['=unsubcampaignid'] = 0;
								$ary['=unsubmessageid'] = 0;
							}

							$sql = adesk_sql_insert('#subscriber_list', $ary);

							if ( !$sql ) {
								$addon['error_code'] = '0';
								$r[$list["id"]] = adesk_ajax_api_result( false, _a("Unknown response code. Please resubmit the subscription form."), $addon );
							} else {
								if ( $thisoptin['optin_confirm'] /*|| $list['require_optin']*/ ) {
									if ( !$sent ) {
										// If the subscription form is set to send an email for each list,
										// Or if one of the lists' Groups has "require optin" set,
										// send a single email for that list.
										if ($form["emailconfirmations"] /*|| $list['require_optin']*/) {
											// Use the current list ID in the loop
											$listids = $list["id"];
										}
										else {
											// Otherwise, use the comma-separated string of list IDs, which is declared further up
											// Then set $sent to true so it never gets back into here
											$sent = true;
										}

										$subscriber['first_name'] = trim((string)$first_name);
										$subscriber['last_name'] = trim((string)$last_name);
										$subscriber['name'] = trim((string)$first_name) . " " . trim((string)$last_name);
										$subscriber['sdate'] = adesk_CURRENTDATETIME;
										//dbg($subscriber);
										mail_opt_send($subscriber, $list, $listids, $subscription_form_id, $thisoptin, "in");
										//if ( $optinoptout ) $sent = true;
									}
								}

								if ( $status == 1 ) {
									// Just list ID gets passed
									$responders[] = $l;

									if ( $list["subscription_notify"] ) {
										// Full list array gets passed
										$notifies[] = $list;
									}

									if ( $list["send_last_broadcast"] ) {
										// (re)send last broadcast message
										mail_campaign_send_last($subscriber, $l);
									}

									$subscriber['first_name'] = trim((string)$first_name);
									$subscriber['last_name'] = trim((string)$last_name);
									$subscriber['name'] = trim((string)$first_name) . " " . trim((string)$last_name);
									$subscriber['sdate'] = adesk_CURRENTDATETIME;
									subscriber_action_dispatch("subscribe", $subscriber, $list, null, null);

									$addon['error_code'] = '7';
									$r[$list["id"]] = adesk_ajax_api_result( true, _a("This e-mail address has subscribed to the list."), $addon );
								} else {
									$addon['error_code'] = '6';
									$addon['confirm'] = true;
									$r[$list["id"]] = adesk_ajax_api_result( true, _a("This e-mail address has been processed. Please check your email to confirm your subscription."), $addon );
								}
							}
						}/*6*/
					}/*5*/
				}/*4*/
			//}/*3*/
		}/*2*/
	}/*1*/

	$subscriber = subscriber_select_row($id);
	$subscriber['first_name'] = trim((string)$first_name);
	$subscriber['last_name'] = trim((string)$last_name);
	$subscriber['name'] = trim((string)$first_name) . " " . trim((string)$last_name);
	$subscriber['sdate'] = adesk_CURRENTDATETIME;
	//dbg($subscriber);

	// send instant autoresponders once
	if ( count($responders) > 0 ) mail_responder_send($subscriber, $responders, 'subscribe');

	// send admin notifications once
	if ( count($notifies) > 0 ) mail_admin_send($subscriber, $notifies, 'subscribe');

	if ( isset($GLOBALS['_hosted_account']) ) {
		require(dirname(dirname(__FILE__)) . '/manage/subscriber.add.inc.php');
	}

	return $r;
}

function subscriber_unsubscribe($id = 0, $email = null, $lists = array(), $reason = null, $subscription_form_id = 0, $campaignid = 0, $messageid = 0, $force = false) {
	require_once awebdesk_functions("ajax.php");
	$r = array();
	$addon = array('reason' => false, 'confirm' => false, 'error_code' => 0);
	$campaignUpdated = false;

	// If no lists selected.
	if ( !$lists ) {
		$addon['error_code'] = '9';
		return array( 0 => adesk_ajax_api_result( false, _a("Unsubscription could not be processed since you did not select a list. Please select a list and try again."), $addon ) );
	}

	$listids = implode(",", $lists);

	if (is_string($id) && strlen($id) == 32) {
		# This is likely to BE the subscriber hash; assume that it is.
		$hash = $id;
		if ( $hash ) {
			$subscriber = subscriber_exists($hash, 0, 'hash');
		} else {
			$addon['error_code'] = '19';
			return array( 0 => adesk_ajax_api_result( false, _a("Subscriber ID is invalid."), $addon ) );
		}
	} elseif ( $id = (int)$id ) {
		$hash = adesk_sql_select_one('hash', '#subscriber', "id = '$id'");
		if ( $hash ) {
			$subscriber = subscriber_exists($hash, 0, 'hash');
		} else {
			$addon['error_code'] = '19';
			return array( 0 => adesk_ajax_api_result( false, _a("Subscriber ID is invalid."), $addon ) );
		}
	} else {
		$email = trim((string)$email);
		// check email validity
		if ( !adesk_str_is_email($email) ) {
			$addon['error_code'] = '8';
			return array( 0 => adesk_ajax_api_result( false, _a("E-mail address is invalid."), $addon ) );
		}
		$subscriber = subscriber_exists($email/*, $lists, 'exact', 1*/);
	}

	// If subscriber does not exist across any lists, insert into #subscriber table
	if ( !$subscriber ) {
		$addon['error_code'] = '12';
		return array( 0 => adesk_ajax_api_result( false, _a("This e-mail address was not subscribed to the list"), $addon ));
	}


	$id = $subscriber["id"];

	$lists = array_map('intval', $lists);
	require_once adesk_admin("functions/form.php");
	require_once adesk_admin("functions/optinoptout.php");
	if ( $subscription_form_id = (int)$subscription_form_id ) {
		$form = form_select_row($subscription_form_id);
		if ( !$form ) $subscription_form_id = 0;
	} else {
		$form = false;
		//$subscription_form_id = 0;
	}
	if ( $form ) {
		// If the Form's optinoptout is set to send a confirmation for each list: #form.emailconfirmations = 1
		if ($form["emailconfirmations"]) {
			// Set to null so it uses the lists' optinoptout in the loop further down
			$optinoptout = null;
		}
		else {
			// Otherwise use the forms' optinoptout which is set on the Edit Form page
			$optinoptout = optinoptout_select_row($form["optinoptout"]);
		}
	} else {
		$form = form_select_row(1000);
		$optinoptout = null;
	}
/*
	$formid = ( isset($subscription_form_id) && (int)$subscription_form_id != 0 ) ? (int)$subscription_form_id : 1000;
	$form = form_select_row($formid);

	// Form optinoptout is valid
	if ( $form && $form["optinoptout"] > 0 ) {
		$optinoptout = optinoptout_select_row($form["optinoptout"]);
	} else { // NEVER GETS HERE, SINCE $formid IS ALWAYS SET AND GREATER THAN 0, ACCORDING TO OUR LOGIC
		$optinoptout = null;
	}
*/
	$sent = false;
	$responders = $notifies = array();

	// Loop through selected lists
	foreach ( $lists as $l ) {/*1*/
		$addon = array('reason' => false, 'confirm' => false, 'error_code' => 0);
		$list = list_select_row($l);
		if ( !$list ) continue;

		// Check if subscriber is already subscribed to this list
		$relation = adesk_sql_select_row("SELECT * FROM #subscriber_list WHERE subscriberid = '$id' AND listid = '$l'");

		if ( !isset($relation['status']) or $relation['status'] == 2 ) {
			$addon['error_code'] = '12';
			$r[$list["id"]] = adesk_ajax_api_result( false, _a("This e-mail address was not subscribed to the list"), $addon );
		} else {/*2*/

			$thisoptin = ( $optinoptout ? $optinoptout : $list ); // null means "use list's"

			// abuses need to force unsubscription, instead of caring about optouts
			if ( $force ) $thisoptin['optout_confirm'] = 0;

			if ( $thisoptin['optout_confirm'] ) {
				if ( !$sent ) {
					// If the subscription form is set to send an email for each list
					if ($form["emailconfirmations"]) {
						// Use the current list ID in the loop
						$listids = $list["id"];
					}
					else {
						// Otherwise, use the comma-separated string of list IDs, which is declared further up
						// Then set $sent to true so it never gets back into here
						$sent = true;
					}
					$sub2mail = subscriber_exists($email, $l);
					if (!$sub2mail) $sub2mail = $subscriber;
					mail_opt_send($sub2mail, $list, $listids, $subscription_form_id, $thisoptin, "out");
					//if ( $optinoptout ) $sent = true;
				}
			}


/*
			$thisoptin = $optinoptout;
			if ( !$thisoptin ) { // NEVER GETS HERE BECAUSE $optinoptout IS ALWAYS PULLING BASED ON $formid - EITHER $formid OR 1000
				if ( $list["optinoptout"] > 0 ) {
					$thisoptin = optinoptout_select_row($list["optinoptout"]);
				}

				// send list optin for each list
				mail_opt_send($subscriber, $list, $formid, $thisoptin, "out");
			} else {
				if ( !$sent ) {
					// send formid optin once
					mail_opt_send($subscriber, $list, $formid, $thisoptin, "out");
					$sent = true;
				}
			}
*/

			$status = 1 + (int)!$thisoptin['optout_confirm']; // status=2 is unsubscribed

			$ary = array(
				'formid' => $subscription_form_id,
				'=udate' => 'NOW()',
				'status' => $status,
				'sync' => 0,
			);
			if ( $campaignid ) $ary['unsubcampaignid'] = $campaignid;
			if ( $messageid  ) $ary['unsubmessageid' ] = $messageid;
			if ( $reason ) {
				$ary['unsubreason'] = $reason;
			} else {
				// if unsubscription reason is needed
				if ( $list["get_unsubscribe_reason"] ) {
					if ( (int)adesk_sql_select_one('=COUNT(*)', '#subscriber_list', "subscriberid = '$id' AND listid = '$l' AND unsubreason IS NOT NULL") == 0 ) {
						$addon['reason'] = true;
					}
				}
				//$ary['=unsubreason'] = 'NULL';
			}

			$sql = adesk_sql_update('#subscriber_list', $ary, "subscriberid = '$id' AND listid = '$l'");

			if ( !$sql ) {
				$addon['error_code'] = '22';
				$r[$list["id"]] = adesk_ajax_api_result( false, _a("This e-mail address could not be unsubscribed."), $addon );
			} else {/*3*/
				# Really unsubscribing
				if ( $status == 2 ) {
					// update campaign counts
					if ( $campaignid > 0 ) {
						if ( !$campaignUpdated ) {
							$countup = array('=unsubscribes' => 'unsubscribes + 1');
							if ( $reason ) {
								$countup['=unsubreasons'] = 'unsubreasons + 1';
							}
							adesk_sql_update('#campaign', $countup, "id = '$campaignid'");
							adesk_sql_update('#campaign_deleted', $countup, "id = '$campaignid'");
							adesk_sql_update("#campaign_message", $countup, "campaignid = '$campaignid' AND messageid = '$messageid'");
							$campaignUpdated = true;
						}
					}
					// remove information that he received any responders/reminders from this list
					adesk_sql_delete('#subscriber_responder', "subscriberid = '$id' AND listid = '$l'");
					// Just list ID gets passed
					$responders[] = $l;
					if ( $list["unsubscription_notify"] ) {
						// Full list array gets passed
						$notifies[] = $list;
					}
					// do subscriber actions
					subscriber_action_dispatch("unsubscribe", $subscriber, $list, null, null);
					// log for hosted
					if ( isset($GLOBALS['_hosted_account']) ) {
						require_once(dirname(dirname(__FILE__)) . '/manage/unsublog.add.inc.php');
					}
					// return result
					$addon['error_code'] = '11';
					$r[$list["id"]] = adesk_ajax_api_result( false, _a("This e-mail address has been unsubscribed from the list."), $addon );
				} else {
					$addon['confirm'] = true;
					$addon['error_code'] = '10';
					$r[$list["id"]] = adesk_ajax_api_result( true, _a("This e-mail address has been processed. Please check your email to confirm your unsubscription."), $addon );
				}
			}/*3*/
		}/*2*/
	}/*1*/

	// send instant autoresponders once
	if ( count($responders) > 0 ) mail_responder_send($subscriber, $responders, 'unsubscribe');

	// send admin notifications once
	if ( count($notifies) > 0 ) mail_admin_send($subscriber, $notifies, 'unsubscribe');

	return $r;
}

function subscriber_update($checkfield = true) {
	$r = array();
	$addon = array('error_code' => 0, 'subcodes' => array());

	if ( adesk_http_param('nlbox') ) {
		$lists = array_map('intval', adesk_http_param('nlbox'));
	} else {
		$lists = array();
	}

	$hash = trim((string)adesk_http_param("s"));
	$subscriber = subscriber_exists($hash, $lists, "hash");

	$campaignid = intval(adesk_http_param("c"));
	$messageid  = intval(adesk_http_param("m"));

	if ( $subscriber ) {

		$email = trim((string)adesk_http_param('email'));
		$fname = trim((string)adesk_http_param('first_name'));
		$lname = trim((string)adesk_http_param('last_name'));
		$field = adesk_http_param('field');

		$subscriber_lists = subscriber_get_lists($subscriber["id"], 1/*, $_SESSION['nlp']*/);

		$user_ip = adesk_sql_escape($_SERVER['REMOTE_ADDR']);

		$ary = array(
			'email' => $email,
			'=ip' => "INET_ATON('$user_ip')",
			'=hash' => "MD5(CONCAT(id, email))",
		);

		// Update #subscriber table
		$sql = adesk_sql_update("#subscriber", $ary, "id = '" . $subscriber["id"] . "'");
		if ( !$sql ) {
			return array( 0 => adesk_ajax_api_result( false, _a("Unknown response code. Please resubmit the subscription form."), $addon ) );
		}

		# Also update their filter cache.
		//filter_cache_subscriber($subscriber["id"], true);

		// save custom fields
		// This needs to be processed here because functions below use this information
		if ( is_array($field) ) {
			adesk_custom_fields_update_data($field, '#list_field_value', 'fieldid', array('relid' => $subscriber["id"]));
		}

		$change_email = false;
		// If they are changing their email address
		if ($subscriber["email"] != $email) {
			$change_email = true;
		}

		$change_name = false;
		if ($subscriber["first_name"] != $fname || $subscriber["last_name"] != $lname) {
			$change_name = true;
		}

		require_once adesk_admin("functions/form.php");
		$formid = ( adesk_http_param("p") && adesk_http_param("p") != 0 ) ? (int)adesk_http_param("p") : 0;
		$form = form_select_row($formid);

		// Form optinoptout is valid
		require_once adesk_admin("functions/optinoptout.php");
		if ( $form && $form["optinoptout"] > 0 ) {
			$optinoptout = optinoptout_select_row($form["optinoptout"]);
		} else {
			$optinoptout = null;
		}

		$sent = false;
		//$subscriber_lists = $subscribe_lists = $unsubscribe_lists = array();
		$subscribe_lists = $unsubscribe_lists = array();

		// UNSUBSCRIBE
		// Loop through all lists subscriber is currently subscribed to
		foreach ( $subscriber_lists as $k => $v ) {
			// If the current subscribed-list (in loop) is not present in the selected lists from the page, unsubscribe them
			if ( !in_array($k, $lists) ) {
				$unsubscribe_lists[] = $k;
			}
		}

		// SUBSCRIBE
		// Loop through selected lists from the page
		foreach ( $lists as $l ) {
			$addon = array('error_code' => 0, 'subcodes' => array());
			$list = list_select_row($l);
			if ( !$list ) continue;

			// Check subscriber limits
			if ( !list_valid($list) ) {
				$addon['error_code'] = '1';
				$r[$list["id"]] = adesk_ajax_api_result( false, _a("This list is currently not accepting subscribers. This list has met its top number of allowed subscribers."), $addon );
			} else {
				// Check blocked settings
				//if ( list_block($email, $l) > 0 ) {
				//	$addon['error_code'] = '20';
				//	$r[$list["id"]] = adesk_ajax_api_result( false, _a("Blocked settings."), $addon );
				//} else {
					// Check exclusion settings
					if ( exclusion_match($email, $l) > 0 ) {
						$addon['error_code'] = '17';
						$r[$list["id"]] = adesk_ajax_api_result( false, _a("This e-mail address is on the global exclusion list."), $addon );
					} else {
						// Check if subscriber is already subscribed to this list
						$exists = adesk_sql_select_one('=COUNT(*)', '#subscriber_list', "subscriberid = '" . $subscriber["id"] . "' AND listid = '$l' AND status = 1");

						// If the record for this subscriber/list combo with a status of 1 does not exist (either no record at all, or a record with status other than 1),
						// or a record exists with status of 1 (subscribed confirmed) AND they are changing their email address,
						// we re-submit this subscriber/list combo
						if ( !$exists || ($exists && ($change_email || $change_name) ) ) {
							//$r[$list["id"]] = adesk_ajax_api_result( false, _a("This e-mail address is already subscribed to this mailing list."), array("error_code" => '3') );
						//}
						//else {
							if (!$list["p_duplicate_subscribe"]) {
								$addon['error_code'] = '21';
								$r[$list["id"]] = adesk_ajax_api_result( false, _a("You selected a list that does not allow duplicates. This email is in the system already, please edit that subscriber instead."), $addon );
							}

							if ( $checkfield ) {
								// 2do: check if all required fields are entered
								//dbg('2do: check if all required fields are entered');
								if ( 0 ) { // req=false
									// continue;
								}
							}

							$update = array(
								'first_name' => $fname,
								'last_name' => $lname,
							);

							if ($exists && $change_name) {

								$sql = adesk_sql_update("#subscriber_list", $update, "listid = " . $l . " AND subscriberid = '" . $subscriber["id"] . "'");
							}

							// If they are already subscribed to this list, and they are changing their email address,
							// revert status to 0 (unconfirmed), so they receive the opt-in email to the new email address
							if ($exists && $change_email) {

								// If this list requires opt-in be sent, we need to make sure the new email address gets the email confirmation,
								// so reset the existing record to unconfirmed, then subscriber_subscribe will force opt-in be sent
								if ($list["optin_confirm"]) {
									// Update to unconfirmed
									$sql = adesk_sql_update_one("#subscriber_list", "status", 0, "subscriberid = '" . $subscriber["id"] . "' AND listid = '$l' AND status = 1");
								}
							}

							$subscribe_lists[] = $l;
						}
					}
				//}
			}
		}


		if ( $checkfield ) {
		// checking to see if name is entered
			if ( trim((string)$fname) == '' && trim((string)$lname) == '' ) {
				// then loop through provided lists
				foreach ( $lists as $l ) {
					// get list info
					$fetchedLists[$l] = $list = list_select_row($l);
					if ( !$list ) continue;
					// if list doesn't allow subscriptions with empty names
					if ( $list['require_name'] ) {
						// complain
						$addon['error_code'] = '16';
						return array(0 => adesk_ajax_api_result( false, _a("Your subscription request for this list could not be processed as you must type your name."), $addon ));
					}
				}
			}

			// checking to see if all required fields are entered.
			$list_fields = list_get_fields($lists, true);
			$fields = adesk_http_param('field');
			foreach ( $list_fields as $f ) {
				# Backwards compatibility
				$fieldkey = $f["id"] . ",0";
				if ( !isset($fields[$fieldkey]) and isset($fields[$f["id"]]) ) {
					$fields[$fieldkey] = $fields[$f["id"]];
				}
				// check for presence of required
				if ( $f["req"] && !adesk_custom_fields_required_check($f["id"], $fields) ) {
					// missing a required field
					$addon['error_code'] = '2';
					return array(0 => adesk_ajax_api_result( false, _a("Your subscription request for this list could not be processed as you are missing required fields."), $addon ) );
				}
			}
		}

		$subscribe = subscriber_subscribe($subscriber['id'], $email, $fname, $lname, $subscribe_lists, $formid, $field, true);
		$unsubscribe = subscriber_unsubscribe(0, $email, $unsubscribe_lists, null, $formid, 0, 0);

		// We can do anything we want here. Right now it returns a generic message.
		// But remember that the $subscribe and $unsubscribe vars (above) return the messages for each list, or 0 for other error
		$addon['subcodes'] = array_merge($subscribe, $unsubscribe);
		$addon['error_code'] = '15';

		$shouldntupdate = (int)adesk_sql_select_one("SELECT COUNT(*) FROM #update WHERE subscriberid = '$subscriber[id]' AND campaignid = '$campaignid'");

		if (!$shouldntupdate) {
			# Save the update to awebdesk_update.
			$ins = array(
				"subscriberid" => $subscriber["id"],
				"campaignid"   => $campaignid,
				"messageid"    => $messageid,
				"=tstamp"      => "NOW()",
			);

			if (isset($_SERVER["REMOTE_ADDR"]))
				$ins["=ip"] = "INET_ATON('$_SERVER[REMOTE_ADDR]')";

			adesk_sql_insert("#update", $ins);
			adesk_sql_query("UPDATE #campaign SET updates = updates + 1 WHERE id = '$campaignid'");
			adesk_sql_query("UPDATE #campaign_deleted SET updates = updates + 1 WHERE id = '$campaignid'");
		}

		return array( 0 => adesk_ajax_api_result( true, _a("Your changes have been saved."), $addon ) );

	} else {
		$email = trim((string)adesk_http_param('email'));

		// check email validity
		if ( !adesk_str_is_email($email) ) {
			$addon['error_code'] = '8';
			return array( 0 => adesk_ajax_api_result( false, _a("E-mail address is invalid."), $addon ) );
		}

		$addon['error_code'] = '19';
		return array( 0 => adesk_ajax_api_result( false, _a("Subscriber ID is invalid."), $addon ) );
	}

	return $r;
}

function subscriber_update_request($email = null) {
	require_once adesk_admin("functions/mail.php");

	$site = adesk_site_get();
	$base = $site['p_link'];
	$from = $site['emfrom'];
	$name = $site['site_name'];

	$r = array();

	if ( !$email ) {
		$email = trim((string)adesk_http_param('email'));
	}

	// check email validity
	if ( !adesk_str_is_email($email) ) {
		return array( 0 => adesk_ajax_api_result( false, _a("E-mail address is invalid."), array("error_code" => '8') ) );
	}

	$subscriber = subscriber_exists($email);
	if ( !$subscriber ) {
		return array( 0 => adesk_ajax_api_result( false, _a("This subscriber does not exist."), array("error_code" => '23') ) );
	}
	$subscriber['lists'] = subscriber_get_lists($subscriber['id'], null);


	$options = array();
	$lists = $subscriber['lists'];

	$userid = 0;
	foreach($lists as $key => $value) {
		$listid = $key;
		break; //just get first list id
	}
	if ( isset($listid) ) $userid = adesk_sql_select_one("userid", "#list", "id='$listid'");

	if($userid)
		$options['userid'] = (int)$userid;
	else
		$options['userid'] = 1;


	// check how many accounts does he have
	$e = adesk_sql_escape($email);
	$accounts = adesk_sql_select_array("SELECT * FROM `#subscriber` WHERE `email` = '$e'");
	$accountsCnt = count($accounts);
	foreach ( $accounts as $k => $v ) {
		$accounts[$k]['first_name'] = adesk_sql_select_one("SELECT first_name FROM #subscriber_list WHERE subscriberid = '$v[id]'");
		$accounts[$k]['last_name'] = adesk_sql_select_one("SELECT last_name FROM #subscriber_list WHERE subscriberid = '$v[id]'");
		$accounts[$k]['confirmlink'] = $base . "/index.php?action=account_update&s=" . $v['hash'] /*md5($v['id'] . $email)*/;
	}

	//$hash = md5($subscriber["id"] . $email);
	$subscriber['confirmlink'] = $base . "/index.php?action=account_update&s=" . $subscriber['hash'];

	// call smarty to make an e-mail body
	require_once(awebdesk_functions('smarty.php'));
	$smarty = new adesk_Smarty('public', true);
    // assign link to template
    $smarty->assign('site', $site);
    $smarty->assign('subscriber', $subscriber);
    $smarty->assign('accounts', $accounts);
    $smarty->assign('accountsCnt', $accountsCnt);
    $text = $smarty->fetch('account_modify.txt');
    // send email
	if ( !isset($GLOBALS['demoMode']) ) { // check if demo mode is on
		adesk_mail_send("text", $name, $from, $text, _p("Update Subscription Account"), $email, $subscriber["first_name"].' '.$subscriber["last_name"], $options);
	}
	return array( 0 => adesk_ajax_api_result( true, _p("The link to modify your account has been sent. Please check your email."), array("error_code" => '24') ) );
}

?>
