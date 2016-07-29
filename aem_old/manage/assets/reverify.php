<?php

require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");
require_once adesk_admin("functions/reverify.php");
require_once awebdesk_functions("ajax.php");

class reverify_assets extends AWEBP_Page {

	function reverify_assets() {
		$this->pageTitle = _a("Verify Your Subscribers");
		$this->sideTemplate = "";
		$this->AWEBP_Page();
	}

	function process(&$smarty) {
		if (!isset($GLOBALS['_hosted_account'])) {
			adesk_http_redirect(adesk_site_alink());
		}

		$this->setTemplateData($smarty);

		$lists = adesk_sql_select_array("
			SELECT
				l.name,
				(SELECT COUNT(*) FROM #subscriber_list sl WHERE sl.listid = l.id) AS subcount
			FROM
				#list l
		");

		$smarty->assign("lists", $lists);
		$smarty->assign("ongoing", 0);
		$smarty->assign("progress", 0);
		$smarty->assign("progresspx", 0);

		if (reverify_ongoing()) {
			$smarty->assign("ongoing", 1);

			$percent = reverify_percent();
			$smarty->assign("progress", $percent);
			$smarty->assign("progresspx", 250 * $percent);
		}

		adesk_smarty_submitted($smarty, $this);
		$smarty->assign("content_template", "reverify.htm");
	}

	function formProcess(&$smarty) {
		$message = (string)adesk_http_param("message");
		$message = strip_tags($message);

		if (strlen($message) > 300) {
			return adesk_ajax_api_result(0, _a("You cannot use a message that is longer than 300 characters"));
		}

		$this->registerprocess($message);
		$smarty->assign("ongoing", 1);
		adesk_process_respawn(null, false);
		return adesk_ajax_api_result(1, _a("Reverify process complete"));
	}

	function registerprocess($message) {
		$ins = array(
			"userid"     => 1,
			"rnd"        => rand(),
			"action"     => "reverify",
			"total"      => reverify_count(),
			"completed"  => 0,
			"percentage" => 0,
			"data"       => serialize(array("message" => $message)),
			"=cdate"     => "NOW()",
			"=ldate"     => "NOW()",
		);

		adesk_sql_insert("#process", $ins);
	}
}

?>
