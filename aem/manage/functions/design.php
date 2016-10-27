<?php

require_once awebdesk_classes("select.php");
require_once awebdesk_classes("mobdetect.php");
function design_select_query(&$so) {
	return $so->query("
		SELECT
			*,
			g.id AS id
		FROM
			#group g,
			#branding b
		WHERE
		[...]
		AND
			g.id = b.groupid
		AND
			g.p_admin = 1
	");
}

function design_select_row($id) {
	$id = intval($id);
	$so = new adesk_Select;
	$so->push("AND g.id = '$id'");

	return adesk_sql_select_row(design_select_query($so));
}

function design_select_array($so = null, $ids = null) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map('intval', $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND g.id IN ('$ids')");
	}
	return adesk_sql_select_array(design_select_query($so));
}

function design_select_array_paginator($id, $sort, $offset, $limit, $filter) {
	$admin = adesk_admin_get();
	$so = new adesk_Select;

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'design'");
		$so->push($conds);
	}

	$so->count();
	$total = (int)adesk_sql_select_one(design_select_query($so));

	switch ($sort) {
		case '02':
			$so->orderby("b.site_name ASC"); break;
		case '02D':
			$so->orderby("b.site_name DESC"); break;

		case '01D':
			$so->orderby("g.title DESC"); break;
		case '01':
		default:
			$so->orderby("g.title ASC");
	}

	if ( (int)$limit == 0 ) $limit = 999999999;
	$so->limit("$offset, $limit");
	$rows = design_select_array($so);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function design_filter_post() {
	$whitelist = array("title", "descript", "site_name", "site_logo", "header_text", "header_html", "footer_text", "footer_html");

	$ary = array(
		"userid" => $GLOBALS['admin']['id'],
		"sectionid" => "design",
		"conds" => "",
		"=tstamp" => "NOW()",
	);

	if (isset($_POST["qsearch"]) && !isset($_POST["content"])) {
		$_POST["content"] = $_POST["qsearch"];
	}

	if (isset($_POST["content"]) and $_POST["content"] != "") {
		$content = adesk_sql_escape($_POST["content"], true);
		$conds = array();

		if (!isset($_POST["section"]) || !is_array($_POST["section"]))
			$_POST["section"] = $whitelist;

		foreach ($_POST["section"] as $sect) {
			if (!in_array($sect, $whitelist))
				continue;
			$conds[] = "$sect LIKE '%$content%'";
		}

		$conds = implode(" OR ", $conds);
		$ary["conds"] = "AND ($conds) ";
	}
	if ( $ary['conds'] == '' ) return array('filterid' => 0);

	$conds_esc = adesk_sql_escape($ary["conds"]);
	$filterid = adesk_sql_select_one("
		SELECT
			id
		FROM
			#section_filter
		WHERE
			userid = '$ary[userid]'
		AND
			sectionid = 'design'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	 $admin = adesk_admin_get();
 

if ($admin['id'] != 1) {
		return adesk_ajax_api_result(false, _a("You have no permission to do this."));
	} else {	
		
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
	
	}
}
 
function design_insert_post() {
	$ary = array(
	);
      $admin = adesk_admin_get();
 

if ($admin['id'] != 1) {
		return adesk_ajax_api_result(false, _a("You have no permission to do this."));
	}

	$sql = adesk_sql_insert("#design", $ary);
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Design Settings could not be added."));
	}
	$id = adesk_sql_insert_id();

	return adesk_ajax_api_added(_a("Branding"));
}
 
function design_update_post() {
	if ($_SERVER["REQUEST_METHOD"] != "POST")
		return;

	if ( adesk_site_hosted_rsid() ) {
		return adesk_ajax_api_result(false, _a("You have no permission to do this."));
	}
     $admin = adesk_admin_get();
 

if ($admin['id'] != 1  or AEMUSERS=='2') {
		return adesk_ajax_api_result(false, _a("You have no permission to do this."));
	}

	// templates & styles
	if ( adesk_http_param_exists('template_show') ) {
		$template_htm = (string)adesk_http_param('template');
	} else {
		$template_htm = '';
	}
	if ( adesk_http_param_exists('style_show') ) {
		$template_css = (string)adesk_http_param('style');
	} else {
		$template_css = '';
	}

	// supporting old API call
	// coming from branding_edit API
	if ( adesk_http_param_exists('branding_url') ) {
		$design_url = $_POST["branding_url"];
	}
	else {
		$design_url = $_POST["design_url"];
	}

	$logo_source = adesk_http_param("logo_source");

	$site_logo = $design_url;

	if ($logo_source == "upload") {
		list($attachment) = adesk_http_param("_attachments_");
		if ($attachment) $site_logo = $GLOBALS["site"]["p_link"] . "/images/" . $GLOBALS["admin"]["username"] . "/" . $attachment;
	}

	$ary = array(
		"site_name" => $_POST["site_name"],
		"site_logo" => $site_logo,
		"header_text" => ( isset($_POST["header_text"]) ) ? 1 : 0,
		"header_text_value" => ( isset($_POST["header_text"]) ) ? $_POST["header_text_value"] : "",
		"header_html" => ( isset($_POST["header_html"]) ) ? 1 : 0,
		"header_html_value" => ( isset($_POST["header_html"]) ) ? adesk_str_fixtinymce($_POST["header_html_valueEditor"]) : "",
		"footer_text" => ( isset($_POST["footer_text"]) ) ? 1 : 0,
		"footer_text_value" => ( isset($_POST["footer_text"]) ) ? $_POST["footer_text_value"] : "",
		"footer_html" => ( isset($_POST["footer_html"]) ) ? 1 : 0,
		"footer_html_value" => ( isset($_POST["footer_html"]) ) ? adesk_str_fixtinymce($_POST["footer_html_valueEditor"]) : "",
		"copyright" => ( isset($_POST["copyright"]) ) ? 0 : 1,
		"version" => ( isset($_POST["version"]) ) ? 0 : 1,
		"license" => ( isset($_POST["license"]) ) ? 0 : 1,
		"links" => ( isset($_POST["links"]) ) ? 0 : 1,
		"help" => ( isset($_POST["help"]) ) ? 0 : 1,
		"demo" => 0,//( isset($_POST["demo"]) && !isset($GLOBALS['_hosted_account']) ) ? 1 : 0,
		"admin_template_htm" =>NULL,// ( adesk_http_param_exists('admin_template_show') ? (string)adesk_http_param('admin_template') : '' ),
		"admin_template_css" =>NULL,// ( adesk_http_param_exists('admin_style_show') ? (string)adesk_http_param('admin_style') : '' ),
		"public_template_htm" =>NULL,// ( adesk_http_param_exists('public_template_show') ? (string)adesk_http_param('public_template') : '' ),
		"public_template_css" =>NULL,// ( adesk_http_param_exists('public_style_show') ? (string)adesk_http_param('public_style') : '' ),
	);

	$id = intval($_POST["id"]);

	// coming from branding_edit API
	if ( adesk_http_param_exists('groupid') )
		$id = $_POST["groupid"];

	if ( $id != 3 ) $ary['site_logo'] = '';
     $admin = adesk_admin_get();
 

if ($admin['id'] != 1  or AEMUSERS=='2') {
		return adesk_ajax_api_result(false, _a("You have no permission to do this."));
	}

	$sql = adesk_sql_update("#branding", $ary, "groupid = '$id'");
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Design Settings could not be updated."));
	}

	return adesk_ajax_api_updated(_a("Branding"));
}
 
function design_delete($id) {
	$id = intval($id);
	     $admin = adesk_admin_get();
 

if ($admin['id'] != 1) {
		return adesk_ajax_api_result(false, _a("You have no permission to do this."));
	}

	adesk_sql_query("DELETE FROM #design WHERE id = '$id'");
	return adesk_ajax_api_deleted(_a("Branding"));
}

function design_delete_multi($ids, $filter = 0) {
	if ( $ids == '_all' ) {
		$tmp = array();
		$so = new adesk_Select();
		$filter = intval($filter);
		if ($filter > 0) {
			$admin = adesk_admin_get();
			$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'design'");
			$so->push($conds);
		}
		$all = design_select_array($so);
		foreach ( $all as $v ) {
			$tmp[] = $v['id'];
		}
	} else {
		$tmp = array_map("intval", explode(",", $ids));
	}
	foreach ( $tmp as $id ) {
		$r = design_delete($id);
	} $admin = adesk_admin_get();
 

if ($admin['id'] != 1) {
		return adesk_ajax_api_result(false, _a("You have no permission to do this."));
	}
	return $r;
}
 

function design_template_personalize(&$smarty, $admin, $panel = 'public') {
	global $site;
//mob detect
		$detect = new Mobile_Detect;
		
	$prfx = ( $panel == 'admin' ? 'admin_' : 'public_' );

	$admin['template_htm'] =& $admin['brand_' . $prfx . 'template_htm'];
  $admin99 = adesk_admin_get();
/*if($detect->isMobile()){
            $dashtheme = $admin99['default_mobdashboard'];
			//if($panel == 'public')
			//$dashtheme = 'mobile';
}
			else {*/
			$dashtheme = $admin99['default_dashboard'];
			//if($panel == 'public')
			//$dashtheme = 'mobile';
			
			//}
	// fetch html template
	$tplpath  = ( $panel == 'admin' ? adesk_admin() : adesk_basedir() );
	if($panel == 'admin'){
	    if($detect->isMobile()) {
		$dashtheme = $admin99['default_mobdashboard'];
		// $tplpath .= '/templates/mobile/'.$dashtheme.'/main.tpl';
		$tplpath .= '/templates/'.$dashtheme.'/main.tpl';
		} else {
		$dashtheme = $admin99['default_dashboard'];
		 $tplpath .= '/templates/'.$dashtheme.'/main.tpl';
		}
	
	
	
	} else if($panel == 'public')
	$tplpath .= '/templates/main.tpl';
	else
		$tplpath .= '/templates/'.$dashtheme.'/main.tpl';
	
	if ( !$admin['template_htm'] ) $admin['template_htm'] = adesk_file_get($tplpath);
	if ( !adesk_str_instr('%PAGECONTENT%', $admin['template_htm']) ) $admin['template_htm'] = adesk_file_get($tplpath);

	// pass glc data
	$glc_fields = glc_fields();
	if(!empty($glc_fields)):
		$smarty->assign('glc_username', $glc_fields['glc_username']);
		$smarty->assign('glc_membership', $glc_fields['glc_membership']);
	endif;

	// apply basic vars
	$siteurl = $smarty->get_template_vars('__');
	$admin['template_htm'] = str_replace('%SITEURL%', $siteurl, $admin['template_htm']);
	if($site['site_name']=="") $site['site_name'] = "&nbsp;";
	$admin['template_htm'] = str_replace('%SITENAME%', $site['site_name'], $admin['template_htm']);

	// fetch includes
	if ( adesk_str_instr('%HEADERNAV%', $admin['template_htm']) ) {
		$welcomeheader = $smarty->fetch('inc.welcomeheader.htm');
		$admin['template_htm'] = str_replace('%WELCOMEHEADER%', $welcomeheader, $admin['template_htm']);
		$headernav = $smarty->fetch('inc.headernav.htm');
		$admin['template_htm'] = str_replace('%HEADERNAV%', $headernav, $admin['template_htm']);
	}
	if ( $panel != 'admin' ) {
		if ( adesk_str_instr('%FOOTERNAV%', $admin['template_htm']) ) {
			$footernav = $smarty->fetch('inc.footernav.htm');
			$admin['template_htm'] = str_replace('%FOOTERNAV%', $footernav, $admin['template_htm']);
		} elseif ( $site['branding_links'] ) {
		//} else {
			$acpowt =  'Email Marketing Software';
			$admin['template_htm'] .= '<div align="center" style="font-size:11px;color:#ddd;"><a href="http://awebdesk.com/emailmarketing-software/" style="text-decoration:none; color:#ccc;" target="_blank" title="Email Marketing Software">' . $acpowt . '</a> ' . _i18n("by AwebDesk") . '</div>';
		}
		if ( adesk_str_instr('%LANGSELECT%', $admin['template_htm']) ) {
			$langselect = $smarty->fetch('inc.langselect.htm');
			$admin['template_htm'] = str_replace('%LANGSELECT%', $langselect, $admin['template_htm']);
		}
	} else {
		if ( adesk_str_instr('%SEARCHBAR%', $admin['template_htm']) ) {
			$searchbar = $smarty->fetch('inc.searchbar.htm');
			$admin['template_htm'] = str_replace('%SEARCHBAR%', $searchbar, $admin['template_htm']);
		}
		if ( adesk_str_instr('%FOOTER%', $admin['template_htm']) ) {
			$smarty->assign('tip', awebdesk_tip_get());
			$footer = $smarty->fetch('inc.footer.htm');
			$admin['template_htm'] = str_replace('%FOOTER%', $footer, $admin['template_htm']);
		}
		if ( adesk_str_instr('%ACCOUNTNAV%', $admin['template_htm']) ) {
			$accountnav = $smarty->fetch('inc.accountnav.htm');
			$admin['template_htm'] = str_replace('%ACCOUNTNAV%', $accountnav, $admin['template_htm']);
		}
	}


	$tmp = explode('%PAGECONTENT%', $admin['template_htm'], 2);

	$site['templates'] = array(
		'precontent'  => $tmp[0],
		'postcontent' => isset($tmp[1]) ? $tmp[1] : '',
	);
	$site['template_css'] = $admin['brand_' . $prfx . 'template_css'];

	return $admin;
}

function glc_fields()
{
	include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
	$glc_data = array();

	if(is_user_logged_in()):
        $current_user = wp_get_current_user();
        $role = $wpdb->prefix . 'capabilities';
        $current_user->role = array_keys($current_user->$role);
        $role = $current_user->role[0];
        
        $r = (!empty(get_user_meta(get_current_user_id(), 'membership', true))) ? get_user_meta(get_current_user_id(), 'membership', true) : ucfirst($role);

        $glc_data = array(
        	'glc_username' 		=> $current_user->user_login,
        	'glc_membership' 	=> $r
        );
    endif;
    return $glc_data;
}

?>