<?php
if(!session_id()) session_start();
require_once adesk_admin("functions/form.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");
require_once awebdesk_functions("group.php");
class form_assets extends AWEBP_Page {

	function form_assets() {
		$this->pageTitle = _a("Subscription Forms");
		$this->AWEBP_Page();
		$this->admin = $GLOBALS["admin"];
	}

	function process(&$smarty) {

		$this->setTemplateData($smarty);

		if (!$this->admin["pg_form_add"] && !$this->admin["pg_form_edit"] && !$this->admin["pg_form_delete"]) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		if ( list_get_cnt() == 0 ) {
			$smarty->assign('content_template', 'nolists.htm');
			return;
		}

		$smarty->assign("content_template", "form.htm");

		$so = new adesk_Select;

		// list filter
		$filterArray = form_filter_post();
		$filter = $filterArray['filterid'];
		if ($filter > 0) {
			$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '{$this->admin['id']}' AND sectionid = 'form'");
			$so->push($conds);
		}
		$smarty->assign("filterid", $filter);
		$smarty->assign("listfilter", ( isset($_SESSION['nla']) ? $_SESSION['nla'] : null ));

		$so->count();
		$total = (int)adesk_sql_select_one(form_select_query($so));
		$count = $total;

		$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=form');
		$paginator->allowLimitChange = true;
		$paginator->ajaxAction = 'form.form_select_array_paginator';
		$smarty->assign('paginator', $paginator);

		$sections = array(
			array("col" => "name", "label" => _a("Subscription Form Name")),
			// some other columns here?
		);
		$smarty->assign("search_sections", $sections);

		// get global custom fields
		$fields = list_get_fields(array(), true); // no list id's, but global
		$smarty->assign("fields", $fields);

		// get all confirmations
		require_once(adesk_admin('functions/optinoptout.php'));
		$optsets = optinoptout_select_array();
		//dbg($optsets);
		$smarty->assign('optsetsList', $optsets);
		$smarty->assign('optsetsListCnt', count($optsets));

		// assemble_error_codes
		$assemble_error_codes = adesk_file_get(adesk_admin('functions/subscriber.code.php'));
		$addon = "\n\nif ( isset(\$_GET['lists']) && isset(\$_GET['codes']) )\n  print assemble_error_codes(\$_GET['lists'], \$_GET['codes']);\n\n";
		$assemble_error_codes = implode($addon, explode("\n", $assemble_error_codes, 2));
		$tmp = explode('/* internal */', $assemble_error_codes);
		if ( isset($tmp[1]) ) unset($tmp[1]);
		// clean up _p() from first block
		$tmp[0] = preg_replace('/=> _p\((.*)\),/', '=> $1,', $tmp[0]);
		$assemble_error_codes = implode('', $tmp);
		$assemble_error_codes = str_replace('//$message .=', '$message .=', $assemble_error_codes);
		$smarty->assign('assemble_error_codes', $assemble_error_codes);

		// api doc file examples
		$api_example_files = array();
		if ( $handle = opendir( adesk_base('docs/api-examples') ) ) {
			while ( false !== ($file = readdir($handle)) ) {
				$file = adesk_file_basename($file);
				if ($file) {
					if ( !adesk_site_hosted_rsid() or substr($file, 0, 9) != 'branding_' ) {
						$api_example_files[] = $file;
					}
				}
			}
			closedir($handle);
		}
		sort($api_example_files);
		$smarty->assign("api_example_filenames", $api_example_files);
		// the very first filename, and content, in the array - pull its content now so we can pre-load it
		$smarty->assign("api_example_filename1", $api_example_files[0]);
		$file1 = adesk_file_get( adesk_base('docs/api-examples/' . $api_example_files[0]) );
		$file1 = str_replace('YOUR_USERNAME', $GLOBALS['admin']['username'], $file1);
		$file1 = str_replace('http://yourdomain.com/path/to/AEM', $GLOBALS['site']['p_link'], $file1);
		$file1 = str_replace('AwebDesk Email Marketing', $GLOBALS['admin']['brand_site_name'], $file1);
		$smarty->assign("api_example_content1", $file1);

		$seo = $this->site['general_url_rewrite'];
		$smarty->assign("seo", $seo);

		$plink = adesk_site_plink();
		$links = array(
			'public' => $plink,
			'user'   => $plink . ( $seo ? '/user/'  : '/?ul=' ),
			'group'  => $plink . ( $seo ? '/group/' : '/?action=archive&gl=' ),
			'list'   => $plink . ( $seo ? '/list/'  : '/?action=archive&nl=' ),
			'archive' => $plink . ( $seo ? '/archive/'  : '/?action=archive&nl=0' ),
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

		$smarty->assign("selected_list_id", $_SESSION['selected_list_id']);

	}
}

?>
