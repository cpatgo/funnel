<?php
define('AWEBVIEW', true);
define('AWEBP_USER_NOAUTH', true);

// require main include file
require_once(dirname(__FILE__) . '/manage/awebdeskend.inc.php');

//require_once('awebdeskapi.php');

require_once(awebdesk_functions('ajax.php'));
//require_once awebdesk_includes("awebdeskapi.php");

require_once adesk_admin("functions/list.php");
require_once adesk_admin("functions/subscriber.php");
require_once adesk_admin("functions/form.php");

// Preload the language file
adesk_lang_get('public');

//Character conversions if subscription form is using a different charset
$in = adesk_http_param('_charset');
$out = _i18n("utf-8");

if(isset($in) and $in != $out)
{
	adesk_charset_convert_gp($in, $out);
}

// Redirect if "Down for Maintenance" is checked
if ($site["general_maint"] && !adesk_admin_isadmin()) {
	adesk_http_redirect($site["p_link"]);
}

// action switch
$funcml = adesk_http_param('funcml');

// campaign
$campaignid = (int)adesk_http_param('c');

// message
$messageid = (int)adesk_http_param('m');

// list array
$nlbox = adesk_http_param('nlbox');
if ( !$nlbox ) $nlbox = array();

// list string
$listid = adesk_http_param('nl');
if ( !$nlbox ) {
	// coming from (un)subscribe link
	$nlbox = array_diff(array_map('intval', explode(',', $listid)), array(0));
} else {
	$x = ( is_array($nlbox) ? $nlbox : explode(',', $nlbox));
	// coming from subscription form
	$nlbox2 = array_diff(array_map('intval', explode(',', $listid)), array(0));
	$nlbox = array_diff(array_map('intval', $x), array(0));
	$nlbox = array_unique(array_merge($nlbox, $nlbox2));
	foreach($nlbox as $y)
	{
		if(!isset($admin['lists'][$y]))
			$admin['lists'][$y] = $y;
	}
}
$listid = implode(',', $nlbox);
$listidsql = implode("','", $nlbox);

$nl = (int)$listid;
if ( $campaignid ) {
	$_SESSION['nlp'] = adesk_sql_select_list("SELECT listid FROM #campaign_list WHERE campaignid = '$campaignid'");
	if ( !$_SESSION['nlp'] ) $_SESSION['nlp'] = null;
} elseif ( $nl ) {
	$_SESSION['nlp'] = ( $nl == $listid ? $nl : $nlbox );
	require(adesk_admin('functions/inc.branding.public.php'));
}


// subscriber id
$id = trim((int)adesk_http_param('id'));
// subscriber hash
$hash = trim((string)adesk_http_param('s'));
// subscriber email
$email = trim((string)adesk_http_param('email'));
$email = str_replace(chr(0), "", $email);	# This happens from time to time.  Don't ask why--I have no idea.
if ( $email ) { // fix email
	if ( !adesk_str_is_email($email) ) {
		$email = adesk_b64_decode($email);
		if ( !adesk_str_is_email($email) ) {
			$email = '';
		}
	}
}
else {
	$email = "";
}

/* find subscriber(id) */
$subscriber = false;
// by hash
if ( !$subscriber and $hash ) {
	$subscriber = subscriber_exists($hash, $nlbox, 'hash');
}
// by email
if ( !$subscriber and $email ) {
	$subscriber = subscriber_exists($email, $nlbox, 'exact');
}
// by id
if ( !$subscriber and $id ) {
	$subscriber = subscriber_select_row($id);
}
// backwards compatibility
if ( !$subscriber and 1 ) {
	//if ( $funcml != 'subscribe' ) {
		//dbg('2do: backwards compatibility');
	//}
}
// hardcode hash and email
if ( isset($subscriber['email']) ) {
	$email = $subscriber['email'];
	//$hash = ($subscriber['subscriberid']) ? md5($subscriber['subscriberid'] . $subscriber['email']) : md5($subscriber['id'] . $subscriber['email']);
	$hash  = $subscriber['hash'];
}
$subscriberid = ( isset($subscriber['subscriberid']) ? $subscriber['subscriberid'] : 0 );



// captcha
// Check for AEM4 captcha field
$captcha_field = ( isset($_POST['captcha']) ) ? 'captcha' : 'imgverify';
$captcha = md5((string)adesk_http_param($captcha_field));

// If coming from AEM4 form with captcha field on it, set to true, otherwise check based on the lists selected
$usecaptcha = ( $captcha_field == 'captcha' ) ? 1 : (int)adesk_sql_select_one('=SUM(p_use_captcha)', '#list', "id IN ('$listidsql')") > 0;
if ( !$site['gd'] ) $usecaptcha = false;

// unsubscribe reason
$unsubreason = adesk_http_param('reason');

// subscription form id
$formid = $fid = (int)adesk_http_param('p');
// If subscription form, override list captcha settings with that of form
if ($formid) {
	$formcaptcha = (int)adesk_sql_select_one('captcha', '#form', "id = $formid");
	// If form ID is not found, such as using a AEM4 form, leave $usecaptcha alone, otherwise use the form captcha setting
	$usecaptcha = ( !is_null($formcaptcha) ) ? $formcaptcha : $usecaptcha;
}
if ( !$formid and $subscriber ) $formid = $fid = (int)$subscriber['formid'];
if ( !$formid ) $formid = 1000;

// get default redirection urls
$form = form_select_row($formid);
if ( !$form ) {
	$formid = 1000;
	$form = form_select_row($formid);
}

/*
if ($subscriber) {
	// Pull random formid from subscriber's lists
	$formid_rand = (int)adesk_sql_select_one('formid', '#subscriber_list', "subscriberid = $subscriber[id] AND status = 1 AND formid != 0");

	// If we find one, use that form info in situations where "p" is not provided, such as Account Update
	$form = ($formid_rand) ? form_select_row($formid_rand) : $form;
}
*/

$lists = $codes = '';
$extra = array();
/*$ask2confirm = */$ask4reason = false;

switch ( $funcml ) {

	case 'account':
		// use captcha
		$usecaptcha = $site['gd']; // don't have list perms to use here
		if ( $usecaptcha and !isset($_SESSION["image_random_value"][$captcha]) ) {
			$lists = '0';$codes = '18';
		} else {
			// do update
			$account = subscriber_update_request($email);
			list($lists, $codes) = subscriber_codes($account);
		}
		break;

	case 'account_update':
		// use captcha
		if ( $usecaptcha and !isset($_SESSION["image_random_value"][$captcha]) ) {
			$lists = '0';$codes = '18';
			break;
		}
	case 'up2':
		// do update
		$account_update = subscriber_update();
		list($lists, $codes) = subscriber_codes($account_update);
		break;

	case 'add':
	case 'subscribe':
		$continue = true;
		// use captcha
		if ($usecaptcha) {
			if ( isset($_SESSION["image_random_value"]) ) {
				if ( !isset($_SESSION["image_random_value"][$captcha]) ) {
					$continue = false;
					$lists = '0';$codes = '18';
				}
			}
			else {
				$continue = false;
				$lists = '0';$codes = '25';
			}
		}

		if ($continue) {
			$fullname  = trim((string)adesk_http_param('name')); // AEM4
			if ($fullname) {
				$fullname = explode(" ", $fullname);
				$firstname = array_shift($fullname);
				$lastname = implode(" ", $fullname);
			}
			else {
				$firstname = trim((string)adesk_http_param('first_name'));
				$lastname  = trim((string)adesk_http_param('last_name' ));
			}
			if ( !isset($_POST['field']) ) {
				$xf = array();
				foreach ( $_POST as $k => $v ) {
					if ( substr($k, 0, 6) == 'field_' ) {
						$tmparr = explode('_', substr($k, 6));
						if ( count($tmparr) == 2 ) {
							$xf[(int)$tmparr[0] . ',' . (int)$tmparr[1]] = $v;
						}
					}
				}
				if ( $xf ) $_POST['field'] = $xf;
			}
			$fields = adesk_http_param_forcearray('field');
			$subscribe = subscriber_subscribe(0, $email, $firstname, $lastname, $nlbox, $fid, $fields, true);
			foreach ( $subscribe as $k => $v ) {
				//if ( $v['confirm'] ) $ask2confirm = true;
				$subscriber = subscriber_exists($email, $nlbox, 'exact');
				if ( isset($subscriber['email']) ) {
					//$hash = md5($subscriber['id'] . $subscriber['email']);
					$hash = $subscriber['hash'];
				}
			}
			list($lists, $codes) = subscriber_codes($subscribe);
		}

		break;

	case 'unsubscribe': // uses email to obtain $subscriberid
		// use captcha
		if ( $usecaptcha and !isset($_SESSION["image_random_value"][$captcha]) ) {
			$lists = '0';$codes = '18';
			break;
		}
	case 'unsub2': // used $s (hash) to obtain $subscriberid
		if ( $campaignid ) {
			if(isset($_GET['ALL'])) //Unsubscribe from all of that user group's lists that you are on
			{
				$query = "
					SELECT
						s.listid
					FROM
						#subscriber_list s,
						#user_group ug,
						#list_group lg,
						#campaign c
					WHERE
						s.subscriberid = '$subscriberid'
					AND
						s.status='1'
					AND
						c.id = '$campaignid'
					AND
						c.userid = ug.userid
					AND
						ug.groupid = lg.groupid
					AND
						lg.listid = s.listid
					";
				$cnl = adesk_sql_select_list($query);
				// if no lists are found, try joinging with deleted campaigns table instead
				if ( !$cnl ) {
					$query = "
						SELECT
							s.listid
						FROM
							#subscriber_list s,
							#user_group ug,
							#list_group lg,
							#campaign_deleted c
						WHERE
							s.subscriberid = '$subscriberid'
						AND
							s.status='1'
						AND
							c.id = '$campaignid'
						AND
							c.userid = ug.userid
						AND
							ug.groupid = lg.groupid
						AND
							lg.listid = s.listid
						";
				}
			}
			else //Unsubscribe just from that campaign's list(s)
			{
				// get campaign's lists instead
				$query = "
					SELECT
						c.listid
					FROM
						#campaign_list c,
						#subscriber_list s
					WHERE
						c.campaignid = '$campaignid'
					AND
						s.subscriberid = '$subscriberid'
					AND
						c.listid = s.listid
				";
			}
			$cnl = adesk_sql_select_list($query);
			// if no lists are found, don't cancel out the initial list array
			if ( $cnl ) $nlbox = $cnl;
		}
		$unsubscribe = subscriber_unsubscribe($subscriberid, $email, $nlbox, null, $fid, $campaignid, $messageid);
		foreach ( $unsubscribe as $k => $v ) {
			//if ( $v['confirm'] ) $ask2confirm = true;
			if ( $v['reason' ] ) $ask4reason  = true;
		}
		list($lists, $codes) = subscriber_codes($unsubscribe);
		$extra = array("c" => $campaignid, "m" => $messageid);

		if ( $campaignid ) {
			// check if campaign has read tracking turned on
			if ( adesk_sql_select_one("trackreads", "#campaign", "id = '$campaignid'") ) {
				// check if he read that campaign
				$readlinkid = (int)adesk_sql_select_one("id", "#link", "campaignid = '$campaignid' AND messageid = 0 AND link = 'open'");
				if ( $readlinkid ) {
					// check if he read that campaign
					$found = (int)adesk_sql_select_one("=COUNT(*)", "#link_data", "linkid = '$readlinkid' AND subscriberid = '$subscriberid'");
					if ( !$found ) {
						adesk_http_spawn(adesk_site_plink("lt.php?nl=$nl&c=$campaignid&m=0&s=$hash&l=open"));
					}
				}
			}
		}

		break;

	case 'unsubreason': // uses hash to obtain $subscriberid
		adesk_sql_update_one('#subscriber_list', 'unsubreason', $unsubreason, "subscriberid = '$subscriberid' AND listid IN ('$listidsql')");
		$lists = $listid;
		$codes = adesk_http_param('codes');
		adesk_sql_query("UPDATE #campaign SET unsubreasons = unsubreasons + 1 WHERE id = '$campaignid'");
		adesk_sql_query("UPDATE #campaign_deleted SET unsubreasons = unsubreasons + 1 WHERE id = '$campaignid'");
		adesk_sql_query("UPDATE #campaign_message SET unsubreasons = unsubreasons + 1 WHERE campaignid = '$campaignid' AND messageid = '$messageid'");
		//$unsubscribe_reason = subscriber_unsubscribe_reason($subscriberid, $nlbox, $reason);
		//list($lists, $codes) = subscriber_codes($unsubscribe_reason);
		break;

	case 'csub':

		if ( $subscriber ) {
			$sql = adesk_sql_update_one('#subscriber_list', 'status', 1, "subscriberid = '$subscriber[subscriberid]' AND listid IN ('$listidsql')");
			if ( $sql ) {
				// send instant autoresponders once
				mail_responder_send($subscriber, $listid, 'subscribe');
 				$lists = list_select_array(null, $listid);
				foreach ( $lists as $list ) {
					// do subscriber actions
					subscriber_action_dispatch("subscribe", $subscriber, $list, null, null);
					if ( $list["send_last_broadcast"] ) {
						// (re)send last broadcast message
						mail_campaign_send_last($subscriber, $list['id']);
					}
				}
				// send admin notifications once
				mail_admin_send($subscriber, $lists, 'subscribe');
				//$ask4reason  = true;
				$lists = $listid;
				$codes = implode(',', array_fill(0, count($nlbox), '13')); // assign codes 13
			} else {
				$lists = $listid;
				$codes = implode(',', array_fill(0, count($nlbox), '23')); // assign codes 23
			}
		} else {
			$lists = $listid;
			$codes = implode(',', array_fill(0, count($nlbox), '23')); // assign codes 23
		}
		break;

	case 'cunsub':

		if ( $subscriber ) {
			$campaignUpdated = false;

			$sql = adesk_sql_update_one('#subscriber_list', 'status', 2, "subscriberid = '$subscriber[subscriberid]' AND listid IN ('$listidsql')");
			if ( $sql ) {
				// send instant autoresponders once
				mail_responder_send($subscriber, $listid, 'unsubscribe');
				$lists = list_select_array(null, $listid);
				foreach ( $lists as $list ) {
					$l=$list['id'];
					// update campaign counts
					$campaignid = (int)adesk_sql_select_one('unsubcampaignid', '#subscriber_list', "subscriberid = '$subscriber[subscriberid]' AND listid = '$list[id]'");
					if ( $campaignid > 0 ) {
						if ( !$campaignUpdated ) {
							$countup = array('=unsubscribes' => 'unsubscribes + 1');
							if ( $unsubreason or (int)adesk_sql_select_one('=COUNT(*)', '#subscriber_list', "subscriberid = '$id' AND listid = '$l' AND unsubreason IS NOT NULL") != 0 ) {
								$countup['=unsubreasons'] = 'unsubreasons + 1';
							}
							adesk_sql_update('#campaign', $countup, "id = '$campaignid'");
							adesk_sql_update('#campaign_deleted', $countup, "id = '$campaignid'");
							adesk_sql_update("#campaign_message", $countup, "campaignid = '$campaignid' AND messageid = '$messageid'");
							$campaignUpdated = true;
						}
					}
					// do subscriber actions
					subscriber_action_dispatch("unsubscribe", $subscriber, $list, null, null);
					if ( $list["send_last_broadcast"] ) {
						// (re)send last broadcast message
						mail_campaign_send_last($subscriber, $list['id']);
					}
				}
				// send admin notifications once
				mail_admin_send($subscriber, $lists, 'unsubscribe');
				//$ask4reason  = true;
				$lists = $listid;
				$codes = implode(',', array_fill(0, count($nlbox), '14')); // assign codes 14
				if ( isset($GLOBALS['_hosted_account']) ) {
					require_once(dirname(__FILE__) . '/manage/manage/unsublog.add.inc.php');
				}
			} else {
				$lists = $listid;
				$codes = implode(',', array_fill(0, count($nlbox), '23')); // assign codes 23
			}
		} else {
			$lists = $listid;
			$codes = implode(',', array_fill(0, count($nlbox), '23')); // assign codes 23
		}

}

// subscription form redirect engine
$redirect = form_redirect($form, $funcml, $codes, $lists, $ask4reason, $extra);

// add reason/confirm wanted switch
//if ( $ask2confirm ) $redirect .= "&confirm=1";
if ( $ask4reason  ) $redirect .= "&reason=1&s=$hash";
//if ( $ask4reason  ) $redirect .= "&reason=1";

//dbg($redirect);
header("Location: " . $redirect);

?>
