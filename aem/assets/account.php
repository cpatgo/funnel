<?php

require_once(awebdesk_classes('page.php'));
require_once adesk_admin("functions/subscriber.php");
require_once adesk_admin("functions/form.php");
require_once adesk_admin("functions/personalization.php");

class account_assets extends AWEBP_Page {
	function account_assets() {
		$this->pageTitle = _p("Modify Account");
		parent::AWEBP_Page();
	}

	function process(&$smarty) {

		$this->setTemplateData($smarty);

		if (!$this->site["general_public"] && $_SERVER['REQUEST_METHOD'] != 'GET') {
				adesk_smarty_redirect($smarty, $this->site["p_link"] . "/manage/");
		}

		// get list filter
		$listfilter = ( isset($_SESSION['nlp']) ? $_SESSION['nlp'] : null );

		// this page should always show all lists!
		// reassign this array to smarty with no filter then
		$lists = list_get_all(true, false, null);
		$listsCnt = count($lists);
		$smarty->assign('listsList', $lists);
		$smarty->assign('listsListCnt', $listsCnt);

		// form id
		$formid = (int)adesk_http_param('p');
		if ( !$formid ) $formid = 1000;
		$form = form_select_row($formid);
		$smarty->assign("form", $form);

		// subscriber
		$hash = trim((string)adesk_http_param('s'));
		$subscriber = subscriber_exists($hash, 0, "hash");

		// subscribe codes
		$mode = adesk_http_param('mode');
		$lists = (int)adesk_http_param('lists');
		$codes = adesk_http_param('codes');

		$msg = false;
		if ( $mode == 'confirm' ) {
			$field = 'up1';
			$msg = assemble_error_codes($lists = 0, $codes);
			if ( $form[$field . '_type'] == 'custom' ) {
				$msg = str_replace('%MESSAGE%', $msg, $form[$field . '_value']);

				$msg = ($subscriber) ? subscriber_personalize($subscriber, $lists, $formid, $msg, 'sub') : personalization_form($msg);
			}
		}
		$smarty->assign("account_message", $msg);

		$smarty->assign("rand", md5(date("H:i:s")));
		$smarty->assign("listfilter", $listfilter);

		$smarty->assign("content_template", "account.htm");
	}
}

?>