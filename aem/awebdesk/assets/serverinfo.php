<?php

class serverinfo_assets extends AWEBP_Page {

	function serverinfo_assets() {
		$this->pageTitle = _a("Server Info");
		//$this->sideTemplate = "side.message.htm";
		$this->AWEBP_Page();
	}

	function process(&$smarty) {
		$this->setTemplateData($smarty);
		$smarty->assign("content_template", "serverinfo.htm");

		if ( !adesk_admin_ismaingroup() ) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		if ( isset($GLOBALS['_hosted_account']) ) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		// PHP
		$phpInfo = array();
		$phpInfo['general'] = adesk_php_info(INFO_GENERAL);
		$phpInfo['configuration'] = adesk_php_info(INFO_CONFIGURATION);
		$phpInfo['configuration'] = adesk_str_strip_tag($phpInfo['configuration'], 'h1');
		$phpInfo['configuration'] = adesk_str_strip_tag($phpInfo['configuration'], 'h2');
		$phpInfo['modules'] = adesk_php_info(INFO_MODULES);
		$phpInfo['environment'] = adesk_php_info(INFO_ENVIRONMENT);
		$phpInfo['variables'] = adesk_php_info(INFO_VARIABLES);
		$smarty->assign('phpInfo', $phpInfo);

		// MYSQL
		$sqlInfo = array('status' => array(), 'variables' => array(), 'tables' => array());
		$sqlInfo['status'] = adesk_sql_select_box_array("SHOW STATUS");
		$sqlInfo['variables'] = adesk_sql_select_box_array("SHOW VARIABLES");
		$sqlInfo['tables'] = adesk_sql_select_array("SHOW TABLE STATUS");
		$smarty->assign('sqlInfo', $sqlInfo);
		//dbg($sqlInfo);

	}

}

?>
