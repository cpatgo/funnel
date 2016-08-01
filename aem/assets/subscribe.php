<?php

require_once(awebdesk_classes('page.php'));
require_once adesk_admin("functions/list.php");
require_once adesk_admin("functions/form.php");
require_once adesk_admin("functions/subscriber.php");
require_once adesk_admin("functions/personalization.php");

class subscribe_assets extends AWEBP_Page {
	function subscribe_assets() {
		$this->pageTitle = _p("Subscribe");
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

		$smarty->assign("show_captcha", 0);
		if ($listfilter) {
			if ( is_array($listfilter) ) {
				$list = list_select_array(null,$listfilter);
				// used if list filter is set (or "nl" param in URL), so captcha loads without having to click checkbox to run customFieldsObj
				if ( $list and isset($list[0]) ) $smarty->assign("show_captcha", $list[0]["p_use_captcha"]);
			} else {
				$list = list_select_row($listfilter);
				// used if list filter is set (or "nl" param in URL), so captcha loads without having to click checkbox to run customFieldsObj
				if ( $list ) $smarty->assign("show_captcha", $list["p_use_captcha"]);
			}
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

		// subscriber
		$hash = trim((string)adesk_http_param('s'));
		$subscriber = subscriber_exists($hash, 0, "hash");

		// subscribe codes
		$mode = adesk_http_param('mode');
		$lists = adesk_http_param('lists');
		$codes = adesk_http_param('codes');

		$whitelist = array('subscribe_success', 'subscribe_confirm', 'subscribe_error');

		$msg = false;
		if ( $mode and in_array($mode, $whitelist) ) {
			switch ( $mode ) {
				case 'subscribe_error':
					$field = 'sub4';
					break;
				case 'subscribe_confirm':
					$field = 'sub2';
					break;
				case 'subscribe_success':
					$field = ( in_array('13', explode(',', $codes)) ? 'sub3' : 'sub1' );
					break;
			}
			$msg = assemble_error_codes($lists, $codes);
			if ( $form[$field . '_type'] == 'custom' ) {
				$msg = str_replace('%MESSAGE%', $msg, $form[$field . '_value']);

				$msg = ($subscriber) ? subscriber_personalize($subscriber, $lists, $formid, $msg, 'sub') : personalization_form($msg);
			}
		}
		$smarty->assign("subscription_message", $msg);

		// fetch custom fields for these lists
		$custom_fields = list_get_fields($listfilter, true);
		//$custom_fields = list_get_fields($lists, true);

		$smarty->assign("listfilter", $listfilter);
		$smarty->assign("custom_fields", $custom_fields);

		$smarty->assign("rand", md5(date("H:i:s")));

		$smarty->assign("content_template", "subscribe.htm");
	}
}

?>
