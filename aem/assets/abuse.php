<?php

require_once adesk_admin("functions/abuse.php");
require_once awebdesk_functions("ajax.php");
require_once awebdesk_classes("select.php");

class abuse_assets extends AWEBP_Page {

	function abuse_assets() {
		$this->pageTitle = _a("Report Abuse");
		$this->AWEBP_Page();
	}

	function process(&$smarty) {

		$this->setTemplateData($smarty);

		$cid    = (int)adesk_http_param('c');
		$mid    = (int)adesk_http_param('m');
		$listid = (int)adesk_http_param('nl');
		$hash   = (string)adesk_http_param('s');

		if ( !$this->site['mail_abuse'] ) {
			adesk_http_redirect(adesk_site_plink() . '?err=ao');
		}

		if ( /*!$cid or !$mid or*/ !$listid or !$hash ) {
			adesk_http_redirect(adesk_site_plink() . '?err=hm');
		}

		// get campaign
		$campaign = campaign_select_row($cid, true, true, true);
		/*
		if ( !$campaign ) {
			adesk_http_redirect(adesk_site_plink() . '?err=cm');
		}
		*/

		// get subscriber
		$subscriber = subscriber_exists($hash, 0, 'hash'); // on any list
		if ( !$subscriber ) {
			$subscriber = subscriber_dummy(_a('_t.e.s.t_@example.com'), $listid);
			//adesk_http_redirect(adesk_site_plink());
		}

		$confirmed = adesk_http_param_exists('abused');

		if ( $confirmed and $campaign ) {
			if ( !$campaign ) {
				$campaign = array(
					'id' => 0,
					'userid' => 1,
					'name' => '',
				);
			}
			abuse_complaint($subscriber, $campaign, $mid, $listid);
		}

		$smarty->assign('campaign', $campaign);
		$smarty->assign('subscriber', $subscriber);
		$smarty->assign('listid', $listid);
		$smarty->assign('messageid', $mid);
		$smarty->assign('confirmed', $confirmed);

		// display regular page with form inside
		$smarty->assign("content_template", "abuse.htm");
	}
}

?>
