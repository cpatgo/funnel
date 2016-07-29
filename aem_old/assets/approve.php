<?php

require_once awebdesk_classes("select.php");

class approve_assets extends AWEBP_Page {

	function approve_assets() {
		$this->pageTitle = _p("Approve Campaign Sending");
		$this->AWEBP_Page();
	}

	function process(&$smarty) {

		$this->setTemplateData($smarty);

		$cid    = (int)adesk_http_param('c');
		$aid    = (int)adesk_http_param('a');
		$mid    = (int)adesk_http_param('m'); // not used yet, but a working filter for split messages
		$hash   = (string)adesk_http_param('h');

		if ( !$cid or !$aid or !$hash ) {
			adesk_http_redirect(adesk_site_plink());
		}

		// get approval
		$approval = approval_select_row($aid);
		// get campaign
		$campaign = campaign_select_row($cid, true, true, true);

		if ( !$campaign ) {
			adesk_http_redirect(adesk_site_plink());
		}

		// checks
		$approved = ( $approval and $approval['approved'] );
		$declined = !$approval;
		if ( !$approval or $approval['approved'] or $hash != $approval['hash'] ) {
			if ( !$declined and !$approval ) adesk_http_redirect(adesk_site_plink());
		}
		$smarty->assign('approved', $approved);
		$smarty->assign('declined', $declined);

		// get campaign's filter
		$campaign['filter'] = false;
		if ( $campaign['filterid'] ) $campaign['filter'] = filter_select_row($campaign['filterid']);

		// get campaign's user
		$origAdmin = adesk_admin_get();
		$user = adesk_admin_get_totally_unsafe($campaign['userid']);
		if ( !$user ) {
			adesk_http_redirect(adesk_site_plink());
		}
		$GLOBALS['admin'] = $origAdmin;
		$groupslist = implode("', '", $user['groups']);
		$groups = adesk_sql_select_array("SELECT `id`, `title`, `descript` FROM #group WHERE `id` IN ('$groupslist')");

		// get campaign's message
		$messagekey = 0;
		if ( $mid ) {
			foreach ( $campaign['messages'] as $k => $v ) {
				if ( $v['id'] == $mid ) {
					$messagekey = $k;
					break;
				}
			}
		}
		$message = $campaign['messages'][$messagekey];

		$smarty->assign('approval', $approval);
		$smarty->assign('campaign', $campaign);
		$smarty->assign('message', $message);
		$smarty->assign('user', $user);
		$smarty->assign('groups', $groups);

		$type_array = campaign_type();
		$smarty->assign('type_array', $type_array);

		// get sample subscriber
		$subscriber = subscriber_dummy('', $campaign['lists'][0]['id']);
		$smarty->assign('subscriber', $subscriber);
		$smarty->assign('hash', ( $subscriber ? $subscriber['hash'] : '' ));

		// display regular page with form inside
		$smarty->assign("content_template", "approve.htm");
	}
}

?>
