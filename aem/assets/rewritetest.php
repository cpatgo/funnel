<?php

require_once(awebdesk_classes('page.php'));

class rewritetest_assets extends AWEBP_Page {
	function rewritetest_assets() {
		$this->pageTitle = _p("Rewrite Test");
		parent::AWEBP_Page();
		$this->getParams();
	}

	function getParams() {
	}

	function process(&$smarty) {
		$this->setTemplateData($smarty);
		$smarty->assign("content_template", "rewritetest.htm");
	}
}

?>
