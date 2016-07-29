<?php

require_once awebdesk_functions("group.php");
require_once adesk_admin("functions/abuse.php");
require_once awebdesk_classes("select.php");

class complaint_assets extends AWEBP_Page {

	function complaint_assets() {
		$this->pageTitle = _a("Abuse Complaints");
		$this->AWEBP_Page();
	}

	function process(&$smarty) {

		$this->setTemplateData($smarty);

		$gid    = (int)adesk_http_param('g');
		$hash   = (string)adesk_http_param('h');

		if ( !$gid or !$hash ) {
			adesk_http_redirect(adesk_site_plink() . '?err=c1');
		}

		// get group
		$group = adesk_group_select_row($gid);
		//if ( !$group or !$group['abuseratio_overlimit'] ) {
		if ( !$group ) {
			adesk_http_redirect(adesk_site_plink() . '?err=c2');
		}
		$smarty->assign('group', $group);

		$abuse = abuse_select_row($gid);
		if ( !$abuse or $abuse['hash'] != $hash ) {
			adesk_http_redirect(adesk_site_plink() . '?err=c3');
		}
		$smarty->assign('abuse', $abuse);

		// display regular page with form inside
		$smarty->assign("content_template", "complaint.htm");
	}
}

?>
