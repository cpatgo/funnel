<?php

require_once adesk_admin("functions/list.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");
require_once adesk_admin("functions/template.php");
class list_assets extends AWEBP_Page {

	function list_assets() {
		$this->pageTitle = _a("Lists");
		$this->AWEBP_Page();
	}

	function process(&$smarty) {

		$this->setTemplateData($smarty);

		$site = adesk_site_get();
		$admin = adesk_admin_get();

		if (!$this->admin["pg_list_add"] && !$this->admin["pg_list_edit"] && !$this->admin["pg_list_delete"]) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		$smarty->assign("content_template", "list.htm");
		$smarty->assign("side_content_template", "side.list.htm");
		$so = new adesk_Select;
		$so->count();
		$so->greedy = true;
		$total = (int)adesk_sql_select_one(list_select_query($so));
		$count = $total;

		$paginator = new Pagination($total, $count, $this->admin['lists_per_page'], 0, 'desk.php?action=list');
		$paginator->allowLimitChange = true;
		$paginator->ajaxAction = 'list.list_select_array_paginator';
		$smarty->assign('paginator', $paginator);

		$sections = array(
			array("col" => "name", "label" => _a("List Names")),
			//array("col" => "from_name", "label" => _a("From Emails")),
			//array("col" => "from_email", "label" => _a("From Names")),
			//array("col" => "descript", "label" => _a("Descriptions")),
		);
		$smarty->assign("search_sections", $sections);

		$fields = list_get_fields(array(), true); // no list id's, but global
		$smarty->assign("fields", $fields);

		// get all groups
		$groups = group_get_all(0, "g.p_admin = 1");
		foreach ( $groups as $k => $v ) {
			$groups[$k]['admins'] = array();
			if ( adesk_admin_ismaingroup() ) {
				$groups[$k]['admins'] = adesk_sql_select_array("SELECT u.* FROM #user u, #user_group g WHERE u.id = g.userid AND g.groupid = '$k'");
			}
		}
		//dbg($groups);
		$smarty->assign('groupsList', $groups);
		$smarty->assign('groupsListCnt', count($groups));

		// get all bounces
		require_once(adesk_admin('functions/bounce_management.php'));
		$bounces = bounce_management_select_array();
		//dbg($bounces);
		$smarty->assign('bouncesList', $bounces);
		$smarty->assign('bouncesListCnt', count($bounces));

		$so = new adesk_Select();
		$so->select(array('t.id', 't.userid', 't.name', 't.subject', 't.categoryid', 't.preview_mime'));
		$templates = template_select_array($so);
		$smarty->assign("templates", $templates);
		$smarty->assign("templatesCnt", count($templates));

		// get all confirmations
		require_once(adesk_admin('functions/optinoptout.php'));
		$optsets = optinoptout_select_array();
		//dbg($optsets);
		$smarty->assign('optsetsList', $optsets);
		$smarty->assign('optsetsListCnt', count($optsets));

		// oauth for auto-sharing campaigns
		$pass = function_exists('curl_init') && function_exists('hash_hmac') && (int)PHP_VERSION > 4;
		if ($pass) {
			require_once awebdesk_functions("json.php");
			if ( adesk_http_param_exists("oauth_token") && adesk_http_param_exists("oauth_verifier") && adesk_http_param_exists("id") ) {
        // coming back from twitter after authorizing
        $access = list_twitter_oauth_init2( adesk_http_param("id"), adesk_http_param("oauth_token"), $_SESSION["twitter_oauth_token_secret"], adesk_http_param("oauth_verifier") );
        header( "Location: " . $site["p_link"] . "/manage/desk.php?action=list#form-" . adesk_http_param("id") );
			}
			if (adesk_http_param_exists('facebook_logout') && adesk_http_param('facebook_logout') != '') {
				$facebook_oauth = list_facebook_oauth_init();
				$facebook_session = list_facebook_oauth_getsession($facebook_oauth);
				adesk_sql_query("UPDATE #list SET facebook_session = NULL WHERE id = '" . adesk_http_param('facebook_logout') . "' LIMIT 1");
				$_SESSION['facebook_oauth_session'] = '';
			}
			else {
				$_SESSION['facebook_oauth_perms'] = ( adesk_http_param_exists('perms') ) ? adesk_http_param('perms') : '';
				$_SESSION['facebook_oauth_selected_profiles'] = ( adesk_http_param_exists('selected_profiles') ) ? adesk_http_param('selected_profiles') : '';
				$_SESSION['facebook_oauth_installed'] = ( adesk_http_param_exists('installed') ) ? adesk_http_param('installed') : '';
				// if the facebook session is set, and blank, check for it.
				// or if it's not set at all, check for it
				if (
					(isset($_SESSION['facebook_oauth_session']) && $_SESSION['facebook_oauth_session'] == '') ||
					!isset($_SESSION['facebook_oauth_session'])
				) {
					$_SESSION['facebook_oauth_session'] = ( adesk_http_param_exists('session') ) ? adesk_http_param('session') : '';
				}
			}
			// when coming back from facebook (after logging in or out), we could not use #form-1 in the URL (facebook does not like it), so we use &formid=1.
			// we then properly capture that, and redirect to #form-1.
			if ( adesk_http_param_exists('formid') && adesk_http_param('formid') != '' ) {
				adesk_http_redirect($site["p_link"] . '/manage/desk.php?action=list#form-' . adesk_http_param('formid'));
			}
		}

		$smarty->assign('pass', $pass);
	}
}

?>