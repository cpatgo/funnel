<?php

require_once adesk_admin("functions/settings.php");
require_once adesk_admin("functions/feedbackloop.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");
require_once awebdesk_functions('ajax.php');
require_once awebdesk_functions("group.php");

class settings_assets extends AWEBP_Page {

	function settings_assets() {
		$this->pageTitle = _a("Settings");
		$this->sideTemplate = "side.settings.htm";
		$this->AWEBP_Page();
	}

	function process(&$smarty) {

		// hosted check for cnames
		#if ( isset($GLOBALS['_hosted_account']) ) {
		#	$thisurl = adesk_http_geturl();
		#	if ( $GLOBALS["_hosted_cname"] != "" && adesk_str_instr($GLOBALS['_hosted_cname'], $thisurl) && $GLOBALS['_hosted_cname'] != $_SESSION[$GLOBALS["domain"]]["account"] ) {
		#		$thisurl = str_replace($GLOBALS['_hosted_cname'], $_SESSION[$GLOBALS["domain"]]["account"], $thisurl);
		#		adesk_http_redirect($thisurl);
		#	}
		#}

		$this->setTemplateData($smarty);

		if ( !adesk_admin_ismaingroup() ) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		//$smarty->assign("side_content_template", "side.subscriber.htm");
		$site = adesk_site_get();

		$smarty->assign("zones", tz_box());

		$smarty->assign("rwCheck", adesk_php_rewrite_check());

		$smarty->assign("__planid", "");
		if (isset($GLOBALS["domain"]) && isset($_SESSION[$GLOBALS["domain"]]["_hosted_planid"]))
			$smarty->assign("__planid", $_SESSION[$GLOBALS["domain"]]["_hosted_planid"]);

		$URI = ( isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'] );
		$URI = substr($URI, 0, strpos($URI, '/manage/desk.php'));
		$smarty->assign("URI", $URI);
		$smarty->assign("htaccess", adesk_base('.htaccess'));

		$mailconnections = array();
		$sql = adesk_sql_query("SELECT * FROM #mailer ORDER BY corder");
		while ( $row = mysql_fetch_assoc($sql) ) {
			$row['pass'] = ( $row['pass'] == '' ? '' : base64_decode($row['pass']) ); // decoding mail password
			$row['groups'] = adesk_sql_select_box_array("SELECT groupid, groupid FROM #group_mailer WHERE mailerid = '$row[id]'");
			$row['groupslist'] = implode(',', $row['groups']);
			$mailconnections[$row['id']] = $row;
		}
		$smarty->assign("mailconnections", $mailconnections);
		$smarty->assign("mailconnectionscnt", count($mailconnections));


		// what is our upload limit?
		$smarty->assign('uploadLimit', ini_get('upload_max_filesize'));
		// what is our post limit?
		$smarty->assign('postLimit', ini_get('post_max_size'));
/*sandeep */
		// addons
		$smarty->assign("emailcheck", plugin_emailcheck());
		$smarty->assign("deskrss" , plugin_deskrss());
		$smarty->assign("autoremind" , plugin_autoremind());
		$smarty->assign("flashforms" , plugin_flashforms());
/*sandeep*/
		$smarty->assign("hash" , md5($this->site['serial']));

		// hosted vars
		$hosted_cname = '';
		$hosted_domain = '';
		if ( isset($GLOBALS['_hosted_account']) ) {
			$hosted_cname = $GLOBALS['_hosted_cname'];
			$hosted_domain = isset($_SESSION[$GLOBALS['domain']]['account']) ? $_SESSION[$GLOBALS['domain']]['account'] : $GLOBALS['domain'];
		}
		$smarty->assign('hosted_cname', $hosted_cname);
		$smarty->assign('hosted_domain', $hosted_domain);

		$so = new adesk_Select;
		$so->count();
		$total = (int)adesk_sql_select_one(adesk_group_select_query($so));
		$count = $total;

		$paginator_abuse = new Pagination($total, $count, 20, 0, 'desk.php?action=group');
		$paginator_abuse->ajaxAction = 'abuse.abuse_select_array_paginator';
		$smarty->assign('paginator_abuse', $paginator_abuse);

		$so = new adesk_Select;
		$so->count();
		$total = (int)adesk_sql_select_one(feedbackloop_select_query($so));
		//$count = $total;
		$smarty->assign('fblcnt', $total);

		$so->orderby("f.tstamp DESC");
		$so->limit("0, 50");
		$feedbackloops = adesk_sql_select_array(feedbackloop_select_query($so));
		$smarty->assign('feedbackloops', $feedbackloops);

		// get groups
		$so = new adesk_Select;
		//$so->push("AND id > 1");				# Exclude the Visitors group
		$so->push("AND p_admin = 1");				# Exclude the non-admin groups
		$so->orderby("title");
		$groups = adesk_group_select_array($so);
		$smarty->assign('groupsList', $groups);

		$header_lines = array(
		  "
		  <style type=\"text/css\">
		  	#adesk_help_div5,
		  	#adesk_help_div7
		  	{
		  	  width: 100px;
		  	}
		  	#adesk_help_div8
		  	{
		  	  width: 50px;
		  	}
		  </style>
		  ",
		);
		$smarty->assign("header_lines", $header_lines);

		$smarty->assign("cnamefail", "");

		adesk_smarty_submitted($smarty, $this);
		$smarty->assign("content_template", "settings.htm");
	}

	function formProcess(&$smarty) {
		// prepare data
		$update = array();

		if ( isset($_POST["general_url_rewrite"]) ) {
			$rwCheck = adesk_php_rewrite_check();
			if ( !$rwCheck['apache'] ) {
				return adesk_ajax_api_result(0, _a("You cannot enable the search-friendly URL feature on non-Apache web servers"));
			}
			if ( !$rwCheck['configured'] ) {
				$content = (string)adesk_file_get(adesk_base('rename_to_.htaccess'));
				$appURI = ( isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'] );
				$appURI = substr($appURI, 0, strpos($appURI, '/manage/desk.php'));
				$content = str_replace('/AEMURI', $appURI, $content);
				if ( $rwCheck['htaccess'] ) {
					$content = adesk_file_get(adesk_base('.htaccess')) . "\n\n\n" . $content;
				}
				$created = @adesk_file_put(adesk_base('.htaccess'), $content);
				if ( !$created ) {
					// create
					return adesk_ajax_api_result(0, _a("The system could not create/update your .htaccess file. Please configure the .htaccess file first before saving this setting."));
				}
			}
		}

		# General

		if ( !adesk_str_is_email($_POST["emfrom"]) ) {
			// create
			return adesk_ajax_api_result(0, _a("Please provide a valid FROM email address."));
		}

		#$update["site_name"]                    = $_POST["site_name"];
		$update["p_link"]                        = $_POST["p_link"];
		$update["emfrom"]                        = $_POST["emfrom"];
		$update["general_maint"]                 = intval(isset($_POST["general_maint"]));
		$update["general_maint_message"]         = ( isset($_POST["general_maint"]) ) ? $_POST["general_maint_message"] : "";
		#$update["general_passprotect"]          = intval(isset($_POST["general_passprotect"]));
		$update["general_allow_rss"]             = intval(isset($_POST["general_allow_rss"]));

		# Admin

		if (isset($_POST["maxuploadfilesize"]))
		$update["maxuploadfilesize"]             = intval($_POST["maxuploadfilesize"]);

		# Public

		$update["general_url_rewrite"]           = intval(isset($_POST["public_url_rewrite"]) || isset($GLOBALS['_hosted_account']) );
		$update["general_public"]              	 = intval(!isset($_POST["general_public"]));

		# Local

		$update["lang"]                          = $_POST["lang"];
		$local_lang_old_check                    = intval(isset($_POST["local_lang_old_check"]));
		if ($local_lang_old_check) {
		  $local_lang_old_value = $_POST["local_lang_old_check"];
		  // update all users with the new language that currently have the old language (if checkbox was checked)
      $sql = adesk_sql_update_one("#user", "lang", $update["lang"], "lang = '$local_lang_old_value'");
		}
		$update["local_zoneid"]                  = $_POST["local_zoneid"];
		$local_zoneid_old_check                  = intval(isset($_POST["local_zoneid_old_check"]));
		if ($local_zoneid_old_check) {
			$local_zoneid_old_value = $_POST["local_zoneid_old_check"];

			$up = array();
			$offset = tz_offset($_POST["local_zoneid"]);
			$up["local_zoneid"]                  = $_POST["local_zoneid"];
			$up["t_offset_o"]                    = ($offset >= 0) ? "+" : "-";
			$up["t_offset"]                      = tz_hours($offset);
			$up["t_offset_min"]                  = tz_minutes($offset, $up["t_offset"]);

			adesk_sql_update("#user", $up, "local_zoneid = '$local_zoneid_old_value'");
		}
		$update["dateformat"]                    = $_POST["dateformat"];
		$update["timeformat"]                    = $_POST["timeformat"];
		$update["datetimeformat"]                = $_POST["datetimeformat"];
		if (isset($_POST["sdord"]))
		$update["sdord"]                         = $_POST["sdord"];
		$update["mail_abuse"]              	     = intval(isset($_POST["mail_abuse"]) || isset($GLOBALS['_hosted_account']));

		# Local -- time offsets.
		$offset = tz_offset($_POST["local_zoneid"]);
		$update["t_offset_o"]                    = ($offset >= 0) ? "+" : "-";
		$update["t_offset"]                      = tz_hours($offset);
		$update["t_offset_min"]                  = tz_minutes($offset, $update["t_offset"]);

		if ( isset($GLOBALS['_hosted_account']) ) {
			$update["onbehalfof"]                = (int)adesk_http_param_exists('onbehalfof');
		}

		$r = adesk_sql_update('#backend', $update);

		if ( isset($GLOBALS['_hosted_account']) and !adesk_site_hosted_rsid() ) {
			$hosted_cname = (string)adesk_http_param('site_cname');
			$_SESSION[$GLOBALS["domain"]]["cname"] =
				$GLOBALS['_hosted_cname'] = $hosted_cname;

			$dns = dns_get_record($hosted_cname);

			if ($hosted_cname != "") {
				if (!$dns) {
					$smarty->assign("cnamefail", _a("DNS check failed"));

					adesk_session_drop_cache();
					$GLOBALS['site'] = adesk_site_unsafe();
					$smarty->assign("site", $GLOBALS["site"]);
					return true;
				} elseif ($dns[0]["type"] != "CNAME") {
					$smarty->assign("cnamefail", sprintf(_a("This host is not a CNAME record (it's actually %s)"), $dns[0]["type"]));
					adesk_session_drop_cache();
					$GLOBALS['site'] = adesk_site_unsafe();
					$smarty->assign("site", $GLOBALS["site"]);
					return true;
				} elseif (!isset($dns[0]["target"]) || $dns[0]["target"] != $GLOBALS['_hosted_account']) {
					$smarty->assign("cnamefail", sprintf(_a("This host is a CNAME record, but it's not pointing to your account (it points to %s)"), $dns[0]["target"]));
					adesk_session_drop_cache();
					$GLOBALS['site'] = adesk_site_unsafe();
					$smarty->assign("site", $GLOBALS["site"]);
					return true;
				}
			}

			if ( file_exists(adesk_base('tools/setcname.php')) ) {
				$act = ( isset($_SESSION[$GLOBALS["domain"]]["account"]) ? $_SESSION[$GLOBALS["domain"]]["account"] : $GLOBALS['_hosted_account'] );
				adesk_http_spawn($this->site['p_link'] . '/tools/setcname.php?domain=' . $act . '&cname=' . $hosted_cname);
			}
			//dbg($GLOBALS['_hosted_account'] . "\n" . $_SESSION[$GLOBALS["domain"]]["account"] . "\n" . $hosted_cname);
			$smarty->assign('hosted_cname', $hosted_cname);
		}

		if ($r) {
			adesk_session_drop_cache();
			$GLOBALS['site'] = adesk_site_unsafe();
			$smarty->assign("site", $GLOBALS["site"]);
			return adesk_ajax_api_result(1, _a("Settings Saved."));
		}
		return adesk_ajax_api_result(0, _a("Settings could not be saved. " . adesk_sql_error()));
	}
}

?>
