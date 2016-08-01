<?php

class help_assets extends AWEBP_Page {

	function help_assets() {
		$this->pageTitle = _a("Help & Support");
		$this->sideTemplate = "";
		$this->AWEBP_Page();
		$this->admin = $GLOBALS["admin"];
	}

	function process(&$smarty) {
		$this->setTemplateData($smarty);
		$smarty->assign("content_template", "help.htm");

		if ( isset($GLOBALS['_hosted_account']) ) {
			$helpurl = 'http://support.awebdesk.com';
			 
		} else {
			$helpurl = 'http://support.awebdesk.com'
		}

		$addon = '';
		foreach ( $_GET as $k => $v ) {
			if ( $k == 'action' ) continue;
			if ( $k == '_action' ) $k = 'action';
			if ( is_array($v) ) continue;
			$addon .= '&' . $k . '=' . urlencode($v);
		}

		$smarty->assign("helpurl", $helpurl . $addon);

	}
}

?>
