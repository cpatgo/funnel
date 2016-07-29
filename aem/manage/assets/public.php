<?php

require_once awebdesk_classes("select.php");
require_once awebdesk_functions("group.php");

class public_assets extends AWEBP_Page {

	function public_assets() {
		$this->pageTitle = _a("Public Section");
		$this->sideTemplate = "";
		$this->AWEBP_Page();
		$this->admin = $GLOBALS["admin"];
	}

	function process(&$smarty) {
		$this->setTemplateData($smarty);
		if ($this->site["general_public"] != 1)
			return adesk_smarty_noaccess($smarty, $this);

		$seo = $this->site['general_url_rewrite'];
		$smarty->assign("seo", $seo);

		$plink = adesk_site_plink();
		$links = array(
			'public' => $plink,
			'user'   => $plink . ( $seo ? '/user/'  : '/?ul=' ),
			'group'  => $plink . ( $seo ? '/group/' : '/?gl=' ),
			'list'   => $plink . ( $seo ? '/list/'  : '/?nl=' )
		);
		$smarty->assign("links", $links);

		// if not in admin group, add group id right away
		$groupid = key($this->admin['groups']);
		$smarty->assign("groupid", $groupid);
		$maingroup = adesk_admin_ismaingroup();
		$smarty->assign("maingroup", $maingroup);

		if ( !$maingroup ) $links['group'] .= $groupid;


		// fetch all groups for main admins
		$groups = array();
		if ( adesk_admin_ismaingroup() ) $groups = adesk_group_select_array();
		$smarty->assign("groups", $groups);

		$lists = list_get_all(true, true);
		$smarty->assign("lists", $lists);

		$smarty->assign("content_template", "public.htm");
	}
}

?>