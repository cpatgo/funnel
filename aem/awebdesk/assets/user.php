<?php

require_once awebdesk_functions('user.php');
require_once awebdesk_classes("pagination.php");

class user_assets extends AWEBP_Page {

	function user_assets() {
		$this->pageTitle = _a('Users');
		$this->sideTemplate = "";
		$this->AWEBP_Page();
		#$this->subject = _a('User');
		#$this->tpl_view = 'user.htm';
		if ( adesk_site_isknowledgebuilder() and !adesk_site_isstandalone() ) {
			adesk_http_redirect($this->site['p_link2'] . '/manage/desk.php?action=user');
		}
	}

	function permissions(&$smarty) {
		$smarty->assign("hasedit", true);
		$smarty->assign("hasdelete", true);
	}

	function export() {
		$ary = array(
			"user" => adesk_http_param("export_user") == "true",
			"name" => adesk_http_param("export_name") == "true",
			"email" => adesk_http_param("export_email") == "true",
			"filterid" => adesk_http_param("filterid"),
		);

		adesk_user_export($ary);
	}

	function process(&$smarty) {
		$this->setTemplateData($smarty);

		$smarty = adesk_ihook("adesk_user_assets_pre", $smarty);

		if ($this->admin["id"] != 1 && !$smarty->getvar("_user_can_add") && !$smarty->getvar("_user_can_edit") && !$smarty->getvar("_user_can_delete")) {
			adesk_smarty_noaccess($smarty);
			return;
		}

		if (adesk_http_param("export")) {
			$this->export();
			return;
		}

		$this->permissions($smarty);
		$so = new adesk_Select;
		$so->count();
		$total = adesk_sql_select_one(adesk_user_select_query_localcount($so),'',1,false);
		$count = $total;

		$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=user');
		$paginator->ajaxAction = 'user!adesk_user_select_paginator';
		$smarty->assign('paginator', $paginator);

		# Global paginator

		$so->clear();
		$so->count();
		$total = adesk_sql_select_one(adesk_user_select_query($so, true),'',1,true);
		$count = $total;

		$gpag = new Pagination($total, $count, 20, 0, 'desk.php?action=user');
		$gpag->ajaxAction = 'user!adesk_user_select_paginator_global';
		$smarty->assign('global_paginator', $gpag);

		$smarty->assign('global_count', $total);

		# Groups

		require_once awebdesk_functions("group.php");

		$so = new adesk_Select;
		$so->push("AND id > 1");				# Exclude the Visitors group

		if (!adesk_admin_ismaingroup()) {
			$groupstr = implode("','", $GLOBALS["admin"]["groups"]);
			$so->push("AND id IN ('$groupstr')");
		}

		$so->orderby("title");
		$groups = adesk_group_select_array($so);
		$smarty->assign("groups", $groups);
		$smarty->assign("fgroups", $groups);
		$smarty->assign("adesk_admin_ismaingroup", adesk_admin_ismaingroup());

		# Sections

		$sections = array(
			array("col" => "username", "label" => _a("User names")),
			array("col" => "first_name", "label" => _a("First names")),
			array("col" => "last_name", "label" => _a("Last names")),
			array("col" => "email", "label" => _a("Email addresses")),
		);

		$smarty->assign("search_sections", $sections);
		$smarty->assign("content_template", 'user.htm');

		if ( !isset($GLOBALS['adesk_app_id']) ) require(adesk_admin('functions/awebdesk.php'));
		$smarty->assign("app_id", $GLOBALS['adesk_app_id']);
	}
}

?>
