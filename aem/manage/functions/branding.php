<?php

require_once awebdesk_classes("select.php");

function branding_select_query(&$so) {
	return $so->query("
		SELECT
			*
		FROM
			#branding
		WHERE
			[...]
	");
}

function branding_select_row($id, $fromgroup = 0) {
	$id = intval($id);
	if ( $id < 2 ) $id = 3;
	$so = new adesk_Select;
	$so->push("AND groupid = '$id'");

	$row = adesk_sql_select_row(branding_select_query($so));

	// If there is no branding row for the group, insert one, but only if that group ID exists
	if (!$row) {
		$ary = array(
			"groupid" => $id,
			"site_name" => 'Email Marketing Software',
			"site_logo" => '',
		);
		if ( isset($GLOBALS['__languageArray']) ) {
			$ary['site_name'] = _i18n('Email Marketing Software');
		}

		if ($fromgroup > 0) {
			$tmp = branding_select_row($fromgroup, 0);
			$ary["site_name"]         = $tmp["site_name"];
			$ary["site_logo"]         = $tmp["site_logo"];
			/*$ary["header_text"]       = $tmp["header_text"];
			$ary["header_text_value"] = $tmp["header_text_value"];
			$ary["header_html"]       = $tmp["header_html"];
			$ary["header_html_value"] = $tmp["header_html_value"];
			$ary["footer_text"]       = $tmp["footer_text"];
			$ary["footer_text_value"] = $tmp["footer_text_value"];
			$ary["footer_html"]       = $tmp["footer_html"];
			$ary["footer_html_value"] = $tmp["footer_html_value"];
			$ary["admin_template_htm"] = $tmp["admin_template_htm"];
			$ary["admin_template_css"] = $tmp["admin_template_css"];
			$ary["public_template_htm"] = $tmp["public_template_htm"];
			$ary["public_template_css"] = $tmp["public_template_css"];*/
			$ary["copyright"]         = $tmp["copyright"];
			$ary["version"]           = $tmp["version"];
			$ary["license"]           = $tmp["license"];
			$ary["links"]             = $tmp["links"];
			$ary["demo"]              = 0;//$tmp["demo"];
			$ary["help"]              = $tmp["help"];
		}

     $admin = adesk_admin_get();
 

if ($admin['id'] != 1) {
		return adesk_ajax_api_result(false, _a("You have no permission to do this."));
	}

		$sql = adesk_sql_insert("#branding", $ary);

		$row = adesk_sql_select_row(branding_select_query($so));
	}

	return $row;
}

function branding_update_post() {


 

     $admin = adesk_admin_get();
 

if ($admin['id'] != 1) {
		return adesk_ajax_api_result(false, _a("You have no permission to do this."));
	}


	$ary = array(
		"site_name" => $_POST["site_name"],
		"site_logo" => $_POST["branding_url"],
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
		"demo" => 0,//( isset($_POST["demo"]) ) ? 1 : 0,
	);

	$id = intval($_POST["groupid"]);
	$sql = adesk_sql_update("#branding", $ary, "groupid = '$id'");
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Branding could not be updated."));
	}

	return adesk_ajax_api_updated(_a("Branding"));
}

?>
