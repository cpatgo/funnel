<?php

require_once adesk_admin("functions/design.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("mobdetect.php");
require_once awebdesk_classes("pagination.php");
class design_assets extends AWEBP_Page {

	function design_assets() {
		$this->pageTitle = _a("Branding");
		$this->sideTemplate = "side.settings.htm";
		$this->AWEBP_Page();
	}

	function process(&$smarty) {
		$this->setTemplateData($smarty);

		if (!in_array('3', $this->admin['groups'])) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		if (adesk_site_hosted_rsid()) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		$smarty->assign("content_template", "design.htm");

		$so = new adesk_Select;
		$so->count();
		$total = (int)adesk_sql_select_one(design_select_query($so));
		$count = $total;

		$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=design');
		$paginator->allowLimitChange = true;
		$paginator->ajaxAction = 'design.design_select_array_paginator';
		$smarty->assign('paginator', $paginator);

		$sections = array(
			array("col" => "title", "label" => _a("Group Name")),
			array("col" => "descript", "label" => _a("Group Description")),
			array("col" => "site_name", "label" => _a("Site Name")),
			array("col" => "header_text", "label" => _a("Text Header")),
			array("col" => "header_html", "label" => _a("HTML Header")),
			array("col" => "footer_text", "label" => _a("Text Footer")),
			array("col" => "footer_html", "label" => _a("HTML Footer")),
		);
		$smarty->assign("search_sections", $sections);
	$detect = new Mobile_Detect;
$admin99 = adesk_admin_get();
 
			if($detect->isMobile()){
			$dashtheme = $admin99['default_mobdashboard'];
			// $admin_template_htm = adesk_file_get(adesk_admin('templates/mobile/'.$dashtheme.'/main.tpl'));			 
			$admin_template_htm = adesk_file_get(adesk_admin('templates/'.$dashtheme.'/main.tpl'));
			}
			else {
			   $dashtheme = $admin99['default_dashboard'];
			   
			   $admin_template_htm = adesk_file_get(adesk_admin('templates/'.$dashtheme.'/main.tpl'));
			}
		// default html templates
		
		
		
		
		$smarty->assign("admin_template_htm", $admin_template_htm);
		$public_template_htm = adesk_file_get(adesk_base('templates/main.tpl'));
		$smarty->assign("public_template_htm", $public_template_htm);


	}
}

?>
