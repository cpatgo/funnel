<?php

require_once(awebdesk_classes('page.php'));
require_once adesk_admin("functions/list.php");
require_once adesk_admin("functions/form.php");
require_once adesk_admin("functions/subscriber.php");
require_once adesk_admin("functions/personalization.php");

class unsubscribe_assets extends AWEBP_Page {
	function unsubscribe_assets() {
		$this->pageTitle = _p("Unsubscribe");
		parent::AWEBP_Page();
	}

	function process(&$smarty) {

		$this->setTemplateData($smarty);

		if (!$this->site["general_public"] && $_SERVER['REQUEST_METHOD'] != 'GET') {
				adesk_smarty_redirect($smarty, $this->site["p_link"] . "/manage/");
		}

		// get list filter
		if ( (int)adesk_http_param('nl') ) {
			$listfilter = (int)adesk_http_param('nl');
		}
		else {
			$listfilter = ( isset($_SESSION['nlp']) ? $_SESSION['nlp'] : null );
		}

		if ($listfilter) {
			$list = list_select_row($listfilter);

			// used if list filter is set (or "nl" param in URL), so captcha loads without having to click checkbox to run customFieldsObj
			$smarty->assign("show_captcha", $list["p_use_captcha"]);
		}
		else {
			$smarty->assign("show_captcha", 0);
		}

		/*
		// Multiple lists displayed
		$mlt = (adesk_http_param('mlt') && adesk_http_param('mlt') != '') ? adesk_http_param('mlt') : false;

		if ($mlt && $mlt == 'no') {
			$smarty->assign("mlt", false);
		}
		else {
			$smarty->assign("mlt", true);
		}
		*/

		// form id
		$formid = (int)adesk_http_param('p');
		if ( !$formid ) $formid = 1000;
		$form = form_select_row($formid);
		$smarty->assign("form", $form);

		// subscribe codes
		$mode = adesk_http_param('mode');
		$lists = adesk_http_param('lists');
		$codes = adesk_http_param('codes');
		$ask4reason = (bool)adesk_http_param('reason');

		// subscriber
		$hash = trim((string)adesk_http_param('s'));
		$subscriber = subscriber_exists($hash, 0, "hash");

		$smarty->assign("lists", $lists);
		$smarty->assign("codes", $codes);
		$smarty->assign("p", $formid);
		$smarty->assign("hash", $hash);
		$smarty->assign("mode", $mode);
		$smarty->assign("ask4reason", $ask4reason);

		$whitelist = array('unsubscribe_success', 'unsubscribe_confirm', 'unsubscribe_error');

		$msg = false;
		if ( $mode and in_array($mode, $whitelist) ) {
			switch ( $mode ) {
				case 'unsubscribe_error':
					$field = 'unsub4';
					break;
				case 'unsubscribe_confirm':
					$field = 'unsub2';
					break;
				case 'unsubscribe_success':
					$field = ( in_array('14', explode(',', $codes)) ? 'unsub3' : 'unsub1' );
					break;
			}
			$msg = assemble_error_codes($lists, $codes);
			if ( $form[$field . '_type'] == 'custom' ) {
				$msg = str_replace('%MESSAGE%', $msg, $form[$field . '_value']);

				$msg = ($subscriber) ? subscriber_personalize($subscriber, $lists, $formid, $msg, 'unsub') : personalization_form($msg);
			}
		}
		$smarty->assign("unsubscription_message", $msg);

		// fetch custom fields for these lists
		$custom_fields = list_get_fields($listfilter, true);
		//$custom_fields = list_get_fields($lists, true);

		$smarty->assign("listfilter", $listfilter);
		$smarty->assign("custom_fields", $custom_fields);
		$smarty->assign("rand", md5(date("H:i:s")));

		$smarty->assign("content_template", "unsubscribe.htm");
	}
}

?>