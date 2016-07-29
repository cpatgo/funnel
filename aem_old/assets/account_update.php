<?php

require_once(awebdesk_classes('page.php'));
require_once adesk_admin("functions/subscriber.php");
require_once adesk_admin("functions/form.php");
require_once adesk_admin("functions/list_field.php");
require_once adesk_admin("functions/personalization.php");

class account_update_assets extends AWEBP_Page {
	function account_update_assets() {
		$this->pageTitle = _p("Modify Account");
		parent::AWEBP_Page();
	}

	function process(&$smarty) {

		$this->setTemplateData($smarty);

		// get list filter
		//$listfilter = ( isset($_SESSION['nlp']) ? $_SESSION['nlp'] : null );
 	  if ( (int)adesk_http_param('nl') ) {
			$listfilter = (int)adesk_http_param('nl');
		}
		else {
			$listfilter = ( isset($_SESSION['nlp']) ? $_SESSION['nlp'] : null );
		}

		// form id
		$formid = (int)adesk_http_param('p');
		if ( !$formid ) $formid = 1000;
		$form = form_select_row($formid);
		$smarty->assign("form", $form);

		// subscriber
		$hash = trim((string)adesk_http_param('s'));
		$subscriber = subscriber_exists($hash, $listfilter, "hash");

		// subscribe codes
		$mode = adesk_http_param('mode');
		$lists = (int)adesk_http_param('lists');
		$codes = adesk_http_param('codes');

		$show_captcha = false;
		$msg = false;
		if ( $mode == 'update' ) {
			$field = 'up2';
			$msg = assemble_error_codes($lists = 0, $codes);
			if ( $form[$field . '_type'] == 'custom' ) {
				$msg = str_replace('%MESSAGE%', $msg, $form[$field . '_value']);

				$msg = ($subscriber) ? subscriber_personalize($subscriber, $lists, $formid, $msg, 'sub') : personalization_form($msg);
			}
		} else {

			$hash = trim((string)adesk_http_param('s'));
			//$subscriber = subscriber_exists($hash, 0, "hash");
			$smarty->assign("verified", (bool)$subscriber);

			if ( $subscriber ) {
				$smarty->assign("subscriber", $subscriber);
				$smarty->assign("hash", $hash);
				$smarty->assign("campaignid", adesk_http_param("c"));
				$smarty->assign("messageid", adesk_http_param("m"));

				$subscriber_lists = subscriber_get_lists($subscriber["id"], 1);
				$smarty->assign("subscriber_lists", $subscriber_lists);
				$listids = array_keys($subscriber_lists);

				// show captcha
				$lists_array = list_select_array(null, $listids);
				foreach ( $lists_array as $k => $v ) {
					if ( $v['p_use_captcha'] ) {
						$show_captcha = true;
						break;
					}
				}
				if ( !$this->site['gd'] ) $show_captcha = false;

				if(isset($listfilter) && $listfilter)
				{
					if ( is_array($listfilter) ) {
						$fieldslist=$listfilter;
					} else {
						$fieldslist[0]=$listfilter;
					}
				}
				elseif(isset($listids) && $listids)
				{
					if ( is_array($listids) ) {
						$fieldslist=$listids;
					} else {
						$fieldslist[0]=$listids;
					}
				}
				else
				{
					$fieldslist[0]=0;
				}

				if ( !is_array($fieldslist) ) $fieldslist = array_map('intval', explode(",", $fieldslist));

				if(!$listfilter) {
					$subscriber = subscriber_exists($hash, $fieldslist[0], "hash"); //added this here to make sure we find the names correctly
					$smarty->assign("subscriber", $subscriber);
				}

				// fetch custom fields for these lists
				$custom_fields = list_field_update($subscriber["id"], implode("-", $fieldslist));
				//$custom_fields = list_get_fields($listids, true);
				$smarty->assign("custom_fields", $custom_fields);

			}
		}

		$smarty->assign("show_captcha", $show_captcha);

		$smarty->assign("account_update_message", $msg);

		$smarty->assign("listfilter", $listfilter);

		$smarty->assign("rand", md5(date("H:i:s")));

		$smarty->assign("content_template", "account_update.htm");
	}
}

?>
