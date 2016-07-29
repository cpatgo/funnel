<?php

require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");
require_once(awebdesk_functions('ajax.php'));
require_once adesk_admin("functions/process.php");
require_once adesk_admin("functions/subscriber.php");

class batch_assets extends AWEBP_Page {

	function batch_assets() {
		$this->pageTitle = _a("Batch Actions");
		$this->AWEBP_Page();
	}

	function process(&$smarty) {

		$this->setTemplateData($smarty);

		if ( !$this->admin['pg_subscriber_delete'] ) {
			$smarty->assign('content_template', 'nolists.htm');
			return;
		}

		if ( list_get_cnt() == 0 ) {
			$smarty->assign('content_template', 'nolists.htm');
			return;
		}

		$smarty->assign("content_template", "batch.htm");
		$smarty->assign("side_content_template", "side.subscriber.htm");

		$date_15daysago = mktime(0, 0, 0, date("m"), date("d")-15, date("Y"));
		$smarty->assign("start_date", date("m/d/Y", $date_15daysago));

		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$this->formSubmitted();
		}

		// handle form submission
		adesk_smarty_submitted($smarty, $this);
	}

	function formSubmitted() {

		if ( isset($_POST['p']) and is_array($_POST['p']) and count($_POST['p']) > 0 ) {
			$lists = array_map('intval', $_POST['p']);
		}
		else {
			return adesk_ajax_api_result(false, _a("You did not select any lists."));
		}

		$data = array('lists' => $lists);

		$total = 0;

		// "Remove a select list of addresses"
		if ( adesk_http_param("batch_action") == "batchremovepanel" ) {
			$emails = trim((string)adesk_http_param('emailBox'));
			$action = 'removebatch';
			//$total = count(preg_split('/\r\n|\r|\n/', $emails));
			$addresses = explode("\n", $emails);

			$total = 0;

			foreach ($addresses as $k => $v) {

				$data['emails'][] = $v;

				$total += 1;

				// Make sure each email is valid
				/*if ( !adesk_str_is_email($v) ) {
					return adesk_ajax_api_result(false, _a("Please make sure each address is a valid email."));
				}*/
			}
		}
		// "Remove all non-confirmed subscribers from these lists"
		elseif ( adesk_http_param("batch_action") == "batchoptimizepanel" ) {
			//$sdatetime_array = explode(" ", adesk_http_param("batchoptimizepanel_field"));
			$sdate_array = explode("/", adesk_http_param("batchoptimizepanel_field"));
			$sdate_sql = $sdate_array[2] . "-" . $sdate_array[0] . "-" . $sdate_array[1];
			$action = 'removenon';
			$lids = implode(',', $lists);
			$data['conds'] = "AND l.listid IN ($lids) AND l.sdate <= '" . $sdate_sql . "' AND l.status = 0";
			$so = new adesk_Select;
			$so->push("AND l.listid IN ($lids)");
			$so->push("AND l.sdate <= '" . $sdate_sql . "'");
			$so->push("AND l.status = 0");
			$so->count();
			$total = (int)adesk_sql_select_one(subscriber_select_query($so));
		}
		// "Remove all subscribers from these lists"
		elseif ( adesk_http_param("batch_action") == "batchoptimizepanel2" ) {
			$action = 'removeall';
			$lids = implode(',', $lists);
			$data['conds'] = "AND l.listid IN ($lids)";
			$so = new adesk_Select;
			$so->push("AND l.listid IN ($lids)");
			$so->count();
			$total = (int)adesk_sql_select_one(subscriber_select_query($so));
		}
		elseif ( adesk_http_param("batch_action") == "batchoptimizepanel3" ) {
			$action = 'removeinvalid';
			$lids = implode(',', $lists);
			$data['conds'] = "AND l.listid IN ($lids)";
			$so = new adesk_Select;
			$so->push("AND l.listid IN ($lids)");
			$so->count();
			$total = (int)adesk_sql_select_one(subscriber_select_query($so));
		}

		// init this progress
		require_once(awebdesk_functions('process.php'));

		$process = adesk_process_create($action, $total, $data, true);

		if ( !$process ) {
			return adesk_ajax_api_result(false, _a("Batch process could not be initiated."));
		}
		else {
			return adesk_ajax_api_result(true, _a("Subscribers removal process started. It should be removed shortly."));
		}
	}
}

?>
