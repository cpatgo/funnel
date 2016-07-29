<?php

require_once awebdesk_classes("select.php");

function template_select_query(&$so) {
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		 $uid = $admin['id'];
		if ( $admin['id'] > 1 ) {
			if ( !isset($so->permsAdded) ) {
				$so->permsAdded = 1;
				//$admin['lists'][0] = 0;
				if($uid != 1 ){
	$lists = adesk_sql_select_list("SELECT distinct listid FROM #user_p WHERE userid = $uid");


	//get the lists of users
	
	@$liststr = implode("','",$lists);
	}
	else
	{
	@$liststr = implode("','", $admin["lists"]);
	}
				
				
				//$liststr = implode("','", $admin["lists"]);
				$so->push("AND (SELECT COUNT(*) FROM #template_list l WHERE l.templateid = t.id AND l.listid IN ('$liststr')) > 0");
			}
		}
	}

	return $so->query("
		SELECT
			t.id,
			t.userid,
			t.name,
			t.subject,
			t.content,
			t.categoryid,
			t.preview_mime,
			t.preview_data,
			(SELECT COUNT(*) FROM #template_list l WHERE l.templateid = t.id AND l.listid = 0) > 0 AS is_global,
			(SELECT COUNT(*) FROM #template_list l WHERE l.templateid = t.id) AS lists
		FROM
			#template t
		WHERE
			[...]
	");
}

function template_select_row($id, $campaignlists = null) {
	$id = intval($id);
	$so = new adesk_Select;
	$so->push("AND t.id = '$id'");

	$r = adesk_sql_select_row(template_select_query($so));
	if ( $r ) {
		$cond = '';
		if ( !adesk_admin_ismain() ) {
			$admin = adesk_admin_get();
			if ( $admin['id'] != 1 ) {
				//$admin['lists'][0] = 0;
				$cond = "AND l.id IN ('" . implode("', '", $admin['lists']) . "')";
			}
		}

		$r['listscnt'] = $r['lists'];
		$r['lists'] = adesk_sql_select_array("SELECT l.* FROM #template_list t, #list l WHERE t.templateid = '$id' AND t.listid = l.id $cond");
		if ( !$r['lists'] and $campaignlists ) {
			$listcond = str_replace('-', "', '", $campaignlists);
			$r['lists'] = adesk_sql_select_array("SELECT l.* FROM #list l WHERE l.id IN ('$listcond') $cond");
		}
		$lists = array();
		foreach ( $r['lists'] as $l ) {
			$lists[] = $l['id'];
		}
		$r['listslist'] = implode('-', $lists);
		$so = new adesk_Select();
		$listslist = implode(',', $lists);
		$so->push("AND (SELECT COUNT(*) FROM #template_list l WHERE l.templateid = t.id AND l.listid IN ('$listslist')) > 0");
		$r['fields'] = list_get_fields($lists, false);
		$r['personalizations'] = list_personalizations($so);
		$r['preview_image'] = ( $r['preview_mime'] ) ? 1 : 0;
		$r['preview_data'] = base64_encode($r['preview_data']);
		$tags = adesk_sql_select_list("SELECT tag FROM #template_tag tt INNER JOIN #tag t ON tt.tagid = t.id WHERE tt.templateid = '$id'");
		$r['tags'] = $tags;
	}
	return $r;
}

function template_select_array($so = null, $ids = null) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map("intval", $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND t.id IN ('$ids')");
	}
	$r = adesk_sql_select_array(template_select_query($so));
	foreach ( $r as $k => $v ) {
		if ( isset($v['preview_data']) ) {
			$r[$k]['preview_data'] = base64_encode($v['preview_data']);
		}
	}

	return $r;
}

function template_select_array_paginator($id, $sort, $offset, $limit, $filter) {
	$admin = adesk_admin_get();
	$so = new adesk_Select;

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'template'");
		$so->push($conds);
	}

	//$so->count();
	$so->count();
	$total = (int)adesk_sql_select_one($q = template_select_query($so));
	// Using template_select_query() strips out the JOIN stuff, but still passes "WHERE l.listid = ...", so total is always 0
	#$total = (int)adesk_sql_select_one("SELECT COUNT(*) as count FROM #template t");

	switch ($sort) {
		default:
		case "01":
			$so->orderby("name"); break;
		case "01D":
			$so->orderby("name DESC"); break;
		case "03":
			$so->orderby("lists"); break;
		case "03D":
			$so->orderby("lists DESC"); break;
	}

	if ( (int)$limit == 0 ) $limit = 999999999;
	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$rows = template_select_array($so);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

// api
function template_select_list($limit = 0, $ids = null) {
	$so = new adesk_Select();

	if ($ids !== null && $ids != 'all') {
		if ( !is_array($ids) ) $ids = explode(",", $ids);
		$tmp = array_diff(array_map("intval", $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND t.id IN ('$ids')");
	}

	if ( $limit = (int)$limit ) $so->limit($limit);
	$so->orderby('t.id DESC');
	$so->remove = false;
	return template_select_array($so);
}

function template_filter_post() {
	$whitelist = array(
		"name",
		"content",
	);

	$ary = array(
		"userid" => $GLOBALS['admin']['id'],
		"sectionid" => "template",
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

	if ( isset($_POST['listid']) ) {
		if ( defined('AWEBVIEW') ) {
			$_SESSION['nlp'] = $_POST['listid'];
		} else {
			$_SESSION['nla'] = $_POST['listid'];
		}
	}
	$nl = null;
	if ( isset($_SESSION['nlp']) and defined('AWEBVIEW') ) {
		$nl = $_SESSION['nlp'];
	} elseif ( isset($_SESSION['nla']) ) {
		$nl = $_SESSION['nla'];
	}
	if ( $nl ) {
		if ( is_array($nl) ) {
			if ( count($nl) > 0 ) {
				$ids = implode("', '", array_map('intval', $nl));
				$ary['conds'] .= "AND (SELECT COUNT(*) FROM #template_list l WHERE l.templateid = t.id AND l.listid IN ('$ids')) > 0 ";
			} else {
				if ( defined('AWEBVIEW') ) {
					unset($_SESSION['nlp']);
				} else {
					unset($_SESSION['nla']);
				}
			}
		} else {
			$listid = (int)$nl;
			if ( $listid > 0 ) {
				$ary['conds'] .= "AND (SELECT COUNT(*) FROM #template_list l WHERE l.templateid = t.id AND l.listid = '$listid') > 0 ";
			} else {
				if ( defined('AWEBVIEW') ) {
					unset($_SESSION['nlp']);
				} else {
					unset($_SESSION['nla']);
				}
			}
		}
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
			sectionid = 'template'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}

function template_insert_post() {

	if ( adesk_http_param('template_scope') == 'all' ) {
		$l = 0;
	}
	else {
		// find parents
		if ( isset($_POST['p']) and is_array($_POST['p']) and count($_POST['p']) > 0 ) {
			$lists = array_map('intval', $_POST['p']);
		} else {
			return adesk_ajax_api_result(false, _a("You did not select any lists."));
		}
	}

	$admin = adesk_admin_get();

	// check group/list privileges - only Admin Group users can create global templates
	if ( !adesk_admin_ismaingroup() ) {
		if (adesk_http_param('template_scope') == 'specific') {
		  foreach ($lists as $l) {
		    if ( !in_array($l, $admin['lists']) ) {
		      return adesk_ajax_api_result(false, _a("One or more lists you supplied are not accessible to you."));
		    }
		  }
		}
		elseif (adesk_http_param('template_scope') == 'all') {
		  return adesk_ajax_api_result(false, _a("You do not have privileges to create global templates."));
		}
	}

	$ary = template_post_prepare();
	$ary['id'] = 0;
	$ary['userid'] = (int)$admin['id'];

	if ( $ary['name'] == '' ) {
		return adesk_ajax_api_result(false, _a("Template Name can not be left empty. Please name this template."));
	}

	$sql = adesk_sql_insert("#template", $ary);
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Template could not be added."));
	}
	$id = adesk_sql_insert_id();

	$preview_cache_filename = (string)adesk_http_param('template_preview_cache_filename');
	$preview_cache_filename_mimetype = (string)adesk_http_param('template_preview_cache_filename_mimetype');
	$preview_process = template_preview_process($id, $preview_cache_filename, $preview_cache_filename_mimetype);
	if (!$preview_process['succeeded']) {
		return adesk_ajax_api_result(false, $preview_process['message']);
	}

	if ( adesk_http_param('template_scope') == 'specific' ) {
		foreach ( $lists as $l ) {
			adesk_sql_insert('#template_list', array('id' => 0, 'templateid' => $id, 'listid' => $l));
		}
	}
	else {
		adesk_sql_insert('#template_list', array('id' => 0, 'templateid' => $id, 'listid' => $l));
	}
	return adesk_ajax_api_added(_a("Template"));
}

function template_update_post() {

	if ( adesk_http_param('template_scope') == 'all' ) {
		$lists = array(0);
		$l = 0;
	}
	else {
		if ( isset($_POST['p']) and is_array($_POST['p']) and count($_POST['p']) > 0 ) {
			$lists = array_map('intval', $_POST['p']);
		} else {
			return adesk_ajax_api_result(false, _a("You did not select any lists."));
		}
	}

	// check group/list privileges - only Admin Group users can create global templates
	if ( !adesk_admin_ismaingroup() ) {
		if (adesk_http_param('template_scope') == 'specific') {
		  $admin = adesk_admin_get();
		  foreach ($lists as $l) {
		    if ( !in_array($l, $admin['lists']) ) {
		      return adesk_ajax_api_result(false, _a("One or more lists you supplied are not accessible to you."));
		    }
		  }
		}
		elseif (adesk_http_param('template_scope') == 'all') {
		  return adesk_ajax_api_result(false, _a("You do not have privileges to create global templates."));
		}
	}

	$ary = template_post_prepare();

	if ( $ary['name'] == '' ) {
		return adesk_ajax_api_result(false, _a("Template Name can not be left empty. Please name this template."));
	}

	$id = intval($_POST["id"]);
	$sql = adesk_sql_update("#template", $ary, "id = '$id'");

	$preview_cache_filename = (string)adesk_http_param('template_preview_cache_filename');
	// make sure file is actually in cache folder. has to be uploaded via interface for this to happen.
	// API won't work if passing this parameter
	if ( !file_exists(adesk_cache_dir() . "/" . $preview_cache_filename) ) $preview_cache_filename = "";
	$preview_cache_filename_mimetype = (string)adesk_http_param('template_preview_cache_filename_mimetype');
	$preview_process = template_preview_process($id, $preview_cache_filename, $preview_cache_filename_mimetype);
	if (!$preview_process['succeeded']) {
		return adesk_ajax_api_result(false, $preview_process['message']);
	}

	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Template could not be updated."));
	}

	// list relations
	$cond = implode("', '", $lists);
	$admincond = '';
	if ( !adesk_admin_ismaingroup() ) {
		$admin = adesk_admin_get();
		$admincond = "AND listid IN ('" . implode("', '", $admin['lists']) . "')";
	}
	//adesk_sql_delete('#template_list', "templateid = '$id' AND listid NOT IN ('$cond') $admincond");
	adesk_sql_delete('#template_list', "templateid = '$id' $admincond");

	if (adesk_http_param('template_scope') == 'specific') {
		foreach ( $lists as $l ) {
			if ( $l > 0 ) {
				if ( !adesk_sql_select_one('=COUNT(*)', '#template_list', "templateid = '$id' AND listid = '$l'") )
					adesk_sql_insert('#template_list', array('id' => 0, 'templateid' => $id, 'listid' => $l));
			}
		}
	}
	else {
	  adesk_sql_insert('#template_list', array('id' => 0, 'templateid' => $id, 'listid' => $l));
	}

	return adesk_ajax_api_updated(_a("Template"));
}

function template_delete($id) {
	$id = intval($id);
	adesk_sql_query("DELETE FROM #template WHERE id = '$id'");
	template_delete_relations(array($id));
	return adesk_ajax_api_deleted(_a("Template"));
}

function template_delete_multi($ids, $filter = 0) {
	if ( $ids == '_all' ) $ids = null;
	$so = new adesk_Select();
	$so->slist = array('t.id');
	$so->remove = false;
	$filter = intval($filter);
	if ($filter > 0) {
		$admin = adesk_admin_get();
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'template'");
		$so->push($conds);
	}
	$tmp = template_select_array($so, $ids);
	$idarr = array();
	foreach ( $tmp as $v ) {
		$idarr[] = $v['id'];
	}
	$ids = implode("','", $idarr);
	adesk_sql_query("DELETE FROM #template WHERE id IN ('$ids')");
	template_delete_relations($ids);
	return adesk_ajax_api_deleted(_a("Template"));
}

function template_delete_relations($ids) {
	$admincond = 1;
	$admincond2 = 1;
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		$admin['lists'][0] = 0;
		$admincond = "listid IN ('" . implode("', '", $admin['lists']) . "')";
		$users_templates = adesk_sql_select_list("SELECT templateid FROM #template_list WHERE listid IN ('" . implode("','", $admin['lists']) . "')");
    $admincond2 = "templateid IN ('" . implode("','", $users_templates) . "')";
	}
	if ($ids === null) {		# delete all
		adesk_sql_delete('#template_list', $admincond);
		adesk_sql_delete('#template_tag', $admincond2);
	} else {
 		if ( !is_array($ids) ) $ids = array_map('intval', explode(',', $ids));
		$ids = implode("','", $ids);
		adesk_sql_delete('#template_list', "`templateid` IN ('$ids') AND $admincond");
		adesk_sql_delete('#template_tag', "`templateid` IN ('$ids') AND $admincond2");
	}
}

function template_post_prepare() {
	// template
	$ary = array();
	$ary['name'] = (string)adesk_http_param('name');
	$ary['subject'] = (string)adesk_http_param('subject');
	$ary['content'] = (string)adesk_http_param('html');
	$ary['content'] = str_replace('&amp;', '&', $ary['content']);
	$ary['content'] = adesk_str_fixtinymce($ary['content']);
	return $ary;
}

// the image preview that can be uploaded - rename, validate, and move it to the proper directory (or database table)
function template_preview_process($id, $preview_cache_filename, $preview_cache_mimetype) {
	// if there is a newly uploaded preview file
	if ($preview_cache_filename) {
		$preview_extensions_allowed = array('jpg', 'jpeg', 'gif', 'png');
		$filename_array = explode('.', $preview_cache_filename);
		$file_ext = strtolower($filename_array[ count($filename_array) - 1 ]);
		if ( !in_array( $file_ext, $preview_extensions_allowed ) ) {
			return array( "succeeded" => 0, "message" => _a("Template preview file can only be JPG, GIF, or PNG") );
		}
		$filepath_old = adesk_cache_dir() . '/' . $preview_cache_filename;

		$file = adesk_file_get($filepath_old);
		$size = strlen($file);
		$currentPos = 0;
		$count = 1;
		$chunkSize = 700000;
		$update['preview_mime'] = $preview_cache_mimetype;
		while ( $currentPos < $size ) {
			// Get a order number
			//$update['sequence'] = $count;
			// Get a chunk of the data
			$update['preview_data'] = substr($file, $currentPos, $chunkSize);
			// Insert it
			$retval = adesk_sql_update('#template', $update, "id = '$id'");
			if ( !$retval ) {
				// If this is ever false we should remove everything about this file from the database
				adesk_sql_query("UPDATE `#template` SET `preview_data` = '' WHERE `id` = '$id' LIMIT 1");
				return array( "succeeded" => 0, "message" => adesk_sql_error() );
			}
			// Update the current position
			$currentPos += $chunkSize;
			$count++;
		}
		// delete temporary file from cache folder
		unlink($filepath_old);

		/*
		$filepath_new0 = adesk_base('templates/message_previews') . '/template_preview_' . $id;
		$filepath_new = $filepath_new0 . '.' . $file_ext;
		// remove existing template files from actual folder
		// loop through available extensions and delete any files corresponding to this template ID
		// for example, they could upload a JPG at one point, then modify the same template, and upload a GIF - we only want one preview file per template
		foreach ($preview_extensions_allowed as $ext) {
			if ( file_exists($filepath_new0 . '.' . $ext) ) {
				unlink($filepath_new0 . '.' . $ext);
			}
		}
		// move file from cache folder to appropriate folder
		rename($filepath_old, $filepath_new);
		*/
	}
	// either it processed the uploaded file as it should, or if there wasn't a file uploaded, then nothing needs to be done anyway, so success here
	return array( "succeeded" => 1, "message" => "" );
}

// api
function template_import_list() {
  $templates = array();
  $names = adesk_http_param('names');
  $urls = adesk_http_param('urls');
  if ( !is_array($names) ) return adesk_ajax_api_result( false, _a('names needs to be an array of template names') );
  if ( !is_array($urls) ) return adesk_ajax_api_result( false, _a('urls needs to be an array of URLs') );
  $imported = 0;
  foreach ($urls as $k => $url) {
    $name = $_POST['name'] = $names[$k];
    if ( adesk_str_is_url($url) ) {
      $_POST['url'] = $url;
      $import = template_import_post();
      if ($import['succeeded']) {
        $templates[] = array('id' => $import['id'], 'name' => $name, 'url' => $url, 'result' => $import);
        $imported++;
      }
      else {
        $templates[] = array('id' => 0, 'name' => $name, 'url' => $url, 'result' => $import);
      }
    }
    else {
      $templates[] = array('id' => 0, 'name' => $name, 'url' => $url, 'result' => array('succeeded' => 0, 'message' => _a('This is not a valid URL')));
    }
  }
  if ($imported) {
    $result_code = true;
    $imported_message = 'Template(s) imported';
  }
  else {
    $result_code = false;
    $imported_message = 'No Template(s) imported';
  }
  return adesk_ajax_api_result( $result_code, _a($imported_message), array('templates' => $templates) );
}

function template_import_post() {
	$site = adesk_site_get();
	$admin = adesk_admin_get();
	$ary = template_post_prepare();
	$ary['id'] = 0;
	$ary['userid'] = (int)$admin['id'];

	if ( $ary['name'] == '' ) {
		return adesk_ajax_api_result(false, _a("Template Name can not be left empty. Please name this template."));
	}

	if ( adesk_http_param_exists('url') ) {
	  $xml = @file_get_contents( adesk_http_param('url') );
	}
	else {
  	// now read in the XML file
  	$file = adesk_http_param('import');
  	// check if file(s) uploaded properly
  	$uploaded = false;
  	$xml = '';
  	$path = adesk_http_param('path');
  	if (!$path) $path = 'cache';
  	if ($path == 'cache') {
  		$path = adesk_cache_dir() . "/";
  	}
  	elseif ($path == 'manage/sql/email-templates') {
  		$path = adesk_base('manage/sql/email-templates') . "/";
  	}
  	if ( !is_array($file) ) $file = array();
  	foreach ( $file as $filename ) {
  		if ( file_exists($path . $filename) ) {
  			$xml = @file_get_contents($path . $filename);
  			if ( $xml ) {
  				$uploaded = true;
  				break; // only one file at the time in importer
  			}
  		}
  	}
  	if ( !$uploaded ) {
  		return adesk_ajax_api_result(false, _a("You did not upload a file to import. Please do that first..."));
  	}
	}

	require_once awebdesk_pear("Unserializer.php");

	$unserializer = new XML_Unserializer();

	$unserializer->unserialize($xml);
	$data = $unserializer->getUnserializedData();
	//print_r($data); exit;
	if ( PEAR::isError($data) ) {
		return adesk_ajax_api_result(false, _a("Uploaded XML file could not be parsed. Please ensure you are uploading a valid XML file."));
	}
	if ( ( !isset($data['html']) and !isset($data['content']) ) or ( !isset($data['tag']) && !isset($data['directory']) ) ) {
		return adesk_ajax_api_result(false, _a("Your template could not be imported because important data was missing from XML file."));
	}

	//$directory = adesk_admin('images/template_' . $data['tag']);
	if ( isset($data['tag']) ) $directory_str = $data['tag'];
	if ( isset($data['directory']) ) $directory_str = $data['directory'];
	$directory = adesk_base('images/manage/template_' . $directory_str);

	if (isset($GLOBALS["_hosted_account"])) {
		$directory = "/images/" . $GLOBALS["_hosted_account"] . "/manage/template_" . $directory_str;
	}

	if ( !file_exists($directory) ) {
		@mkdir($directory);
	}
	if ( !file_exists($directory) ) {
		return adesk_ajax_api_result(false, sprintf(_a("Your template could not be imported because folder %s could not be created."), $directory));
	}

	if ( !isset($data['content']) ) $data['content'] = $data['html'];
	$html = base64_decode($data['content']);

	// Strip out <title> tag since it was getting embedded in the template body at the very beginning
	$html = adesk_str_strip_tag($html, 'title');

	$html = str_replace('&amp;', '&', $html);
	$plink = $site['p_link'];
	if ( isset($_SESSION['adesk_updater']['plink']) ) $plink = $_SESSION['adesk_updater']['plink'];
		//$html = preg_replace('/images\//', $plink . '/images/manage/template_' . $data['tag'] . '/', $html);
		$html = preg_replace('/cid:(\w{32}\.\w+)/', $plink . '/images/manage/template_' . $directory_str . '/$1', $html);

	//print $html; exit;

	// set data
	$ary['content'] = $html;
	// set template category
	$ary['categoryid'] = isset($data['categoryid']) ? (int)$data['categoryid'] : 0;
	// set preview
	if ( isset($data['preview_mime']) and isset($data['preview_data']) ) {
		$ary['preview_mime'] = $data['preview_mime'];
		$ary['preview_data'] = base64_decode($data['preview_data']);
	} else {
		$ary['preview_mime'] = '';
		$ary['=preview_data'] = 'NULL';
	}

	// if they chose "available for specific lists" radio, make sure they selected some lists
	if ( adesk_http_param('template_scope2') == 'specific' ) {
		// get selected lists
		if ( isset($_POST['p']) and is_array($_POST['p']) and count($_POST['p']) > 0 ) {
			$parents = array_map('intval', $_POST['p']);
		} else {
			return adesk_ajax_api_result(false, _a("You did not select any lists."));
		}
	}

	$sql = adesk_sql_insert("#template", $ary);
	if ( !$sql ) {
		//spit( print_r($ary), 'em');
		return adesk_ajax_api_result(false, _a("Template could not be added."));
	}
	$id = adesk_sql_insert_id();

	// handle tags
	if ( isset($data["tags"]["item"]) ) {
  	if ( is_array($data["tags"]["item"]) ) {
  	  // more then one tag
      $tags = array_map("base64_decode", $data["tags"]["item"]);
  	}
  	else {
  	  // just one tag
  	  $tags = array( base64_decode($data["tags"]["item"]) );
  	}
  	foreach ($tags as $tag) {
      $tagid = (int)adesk_sql_select_one("SELECT id FROM #tag WHERE tag = '$tag'");
  	  if (!$tagid) {
        $insert = array(
          "tag" => $tag,
        );
        $sql = adesk_sql_insert("#tag", $insert);
        $tagid = adesk_sql_insert_id();
      }
      $tag_rel_exists = (int)adesk_sql_select_one("SELECT COUNT(*) FROM #template_tag WHERE templateid = '$id' AND tagid = '$tagid'");
      if (!$tag_rel_exists) {
        $insert = array(
          "templateid" => $id,
          "tagid" => $tagid,
        );
        $sql = adesk_sql_insert("#template_tag", $insert);
        $template_tag_id = adesk_sql_insert_id();
      }
  	}
	}

	// if they chose "available for ALL lists and users" radio, put a 0 in #template_list
	if ( adesk_http_param('template_scope2') == 'all' ) {
		$parents = array(0);
		$l = 0;
		adesk_sql_insert('#template_list', array('id' => 0, 'templateid' => $id, 'listid' => $l));
	}
	else {
		// loop through each selected list
		foreach ( $parents as $l ) {
			if ( $l > 0 ) {
				if ( !adesk_sql_select_one('=COUNT(*)', '#template_list', "templateid = '$id' AND listid = '$l'") )
					adesk_sql_insert('#template_list', array('id' => 0, 'templateid' => $id, 'listid' => $l));
			}
		}
	}

	// save dependencies
	if ( isset($data['images']) && is_array($data['images']) && isset($data['images']['item']) && is_array($data['images']['item']) ) {
		/*
		if ( isset($data['images']['item']['id']) ) {
			$data['images']['item'] = array($data['images']['item']);
		}
		*/
		// just one image
		if ( isset($data['images']['item']['name']) ) {
			adesk_file_put($directory . '/' . $data['images']['item']['name'], base64_decode($data['images']['item']['contents']));
		} else {
			// many images
			foreach ( $data['images']['item'] AS $img ) {
				if ( isset($img['name']) ) {
					adesk_file_put($directory . '/' . $img['name'], base64_decode($img['contents']));
				}
			}
		}
	}

	if ( !adesk_http_param_exists('url') ) {
	  $remove_cache_file = adesk_file_upload_remove(adesk_cache_dir(), '', $file[0]);
	}

	return adesk_ajax_api_result( true, _a("Your template has been successfully imported."), array("id" => $id) );
}

function template_import_file_remove($id) {
	$r = array(
		'succeeded' => false,
		'message' => '',
		'id' => $id
	);
	$file = (string)substr($id, strlen('xmlimport-'));
	if ( !$file ) $file = 'noname.xml';
	$r['succeeded'] = adesk_file_upload_remove(adesk_cache_dir(), '', $id);
	if ( $r['succeeded'] ) {
		$r['message'] = sprintf(_a("File '%s' removed."), substr($id, strlen('xmlimport-')));
	} else {
		$r['message'] = sprintf(_a("File '%s' could not be removed."), substr($id, strlen('xmlimport-')));
	}
	return $r;
}

// api
function template_export_list($ids, $type) {
  $templates = array();
  if ( is_array($ids) && count($ids) ) {
    foreach ($ids as $id) {
      $template = template_export($id, $type, false, false);
      if ( $template ) $templates[] = array("id" => $id, "name" => $template["name"], "content" => $template["content"]);
    }
  }
  return adesk_ajax_api_result( true, _a('Template(s) exported'), array('templates' => $templates) );
}

function template_export($id, $type = 'html', $download = true, $echo = true) {
	$template = template_select_row($id);
	if ( !$template ) {
		if ($echo) {
	    echo _a('Data not found.');
		}
		else {
		  return false;
		}
		exit;
	}
	$fileName = adesk_str_urlsafe($template['name']);
	if ( $type == 'xml' ) {
		require_once awebdesk_pear("Serializer.php");
		require_once awebdesk_functions("mime.php");

		$directory = substr(md5(uniqid(rand())), 0, 10);

		// set the content body and fetch images from it
		$content_copy = $template['content'];
		$images = adesk_mail_embed_images($content_copy, true, true);

		$data = array(
			'content' => base64_encode($content_copy),
			'images' => array(),
			'tags' => array_map( 'base64_encode', $template['tags'] ),
			'categoryid' => $template['categoryid'],
			'preview_mime' => $template['preview_mime'],
			'preview_data' => $template['preview_data'],
		  'directory' => $directory,
		);

		foreach ( $images as $name => $contents ) {
			$data['images'][] = array(
				'name' => $name,
				'contents' => base64_encode($contents),
			);
		}
		$options = array(
			XML_SERIALIZER_OPTION_INDENT            => '    ',
			'defaultTagName'                        => 'item',
			//XML_UNSERIALIZER_OPTION_COMPLEXTYPE   => 'array',
			XML_SERIALIZER_OPTION_RETURN_RESULT     => true,
			XML_SERIALIZER_OPTION_LINEBREAKS        => "\r\n",
			//XML_SERIALIZER_OPTION_CDATA_SECTIONS  => true,
		);

		$serializer = new XML_Serializer($options);
	}
	// send headers
	if ( $type == 'xml' ) {
		header("Content-type: application/xml; charset=" . _i18n("utf-8"));
		if ($download) header("Content-Disposition: attachment; filename=$fileName.xml");
	} elseif ( $type == 'html' ) {
		header("Content-type: text/html; charset=" . _i18n("utf-8"));
		if ($download) header("Content-Disposition: attachment; filename=$fileName.html");
	}
	header("Pragma: no-cache");
	header("Expires: 0");
	// print
	if ($echo) {
	  echo ( $type == 'html' ? $template['content'] : $serializer->serialize($data) );
	  exit; // end the script execution here!
	}
	else {
	  return ( $type == 'html' ? $template['content'] : array( 'name' => $template['name'], 'content' => $serializer->serialize($data) ) );
	}
}

// clear out the cache folder of any remnant template preview or import files, in case they get stuck there somehow
function template_cache_clear() {
	if ( $handle = opendir( adesk_cache_dir() ) ) {
		while ( false !== ($file = readdir($handle)) ) {
			$filename = adesk_file_basename($file);
			// if the file has an extension (there are some files without extensions in the cache folder)
			$file_ext = preg_match('/\./', $filename);
			if ($filename && $file_ext) {
				if ( preg_match('/^(tplimport|template_preview)/', $filename) ) {
					$remove_cache_file = adesk_file_upload_remove(adesk_cache_dir(), '', $filename);
				}
			}
		}
	}
}

function template_selector_tdisplay($tagid, $searchkey, $offset, $length) {
	$tagid = (int)$tagid;
	$so = new adesk_Select;
	$admin = adesk_admin_get();

	if ($admin["id"] != 1) {
		$liststr = implode("','", array_merge(array(0), $admin["lists"]));
		$so->push("AND (SELECT COUNT(*) FROM #template_list tl WHERE tl.templateid = t.id AND tl.listid IN ('$liststr')) > 0");
	}

	if ($tagid > 0) {
		$so->push("AND (SELECT COUNT(*) FROM #template_tag tag WHERE tag.tagid = '$tagid' AND tag.templateid = t.id) > 0");
	}

	$searchkey = trim($searchkey);
	if ($searchkey != "" && strlen($searchkey) > 2) {
		$searchkeyesc = adesk_sql_escape($searchkey);
		$so->push("AND (name LIKE '%$searchkey%' OR content LIKE '%$searchkey%')");
	}

	# For the blank one later.
	if ($offset == 0)
		$length--;

	$query = $so->query("
		SELECT
			COUNT(*)
		FROM
			#template t
		WHERE
			[...]
	");

	$total = (int)adesk_sql_select_one($query);

	$offset = (int)$offset;
	$length = (int)$length;
	$so->limit("$offset, $length");

	$query = $so->query("
		SELECT
			id,
			name,
			content != '' AS haspreview
		FROM
			#template t
		WHERE
			[...]
	");

	$rval = adesk_sql_select_array($query);

	if ($offset == 0 && !$tagid && $searchkey == "") {
		array_unshift($rval, array("id" => 0, "name" => _a("Blank Message")));
	}

	return array("row" => $rval, "loadmore" => ($offset + $length) < $total);
}

function template_selector_cdisplay($tagid, $searchkey, $offset, $length) {
	$so = new adesk_Select;

	$admin = adesk_admin_get();

	if ($admin["id"] != 1) {
		$liststr = implode("','", $admin["lists"]);
		$so->push("AND (SELECT COUNT(*) FROM #campaign_list cl WHERE cl.campaignid = cm.campaignid AND cl.listid IN ('$liststr')) > 0");
	}

	$searchkey = trim($searchkey);
	if ($searchkey != "" && strlen($searchkey) > 2) {
		$searchkeyesc = adesk_sql_escape($searchkey);
		$so->push("AND (SELECT COUNT(*) FROM #message m WHERE m.id = cm.messageid AND (m.subject LIKE '%$searchkey%' OR m.html LIKE '%$searchkey%' OR m.text LIKE '%$searchkey%')) > 0");
	}

	$query = $so->query("
		SELECT
			COUNT(DISTINCT messageid)
		FROM
			#campaign_message cm
		WHERE
			[...]
	");

	$total = (int)adesk_sql_select_one($query);

	$offset = (int)$offset;
	$length = (int)$length;
	$so->limit("$offset, $length");

	$query = $so->query("
		SELECT
			cm.messageid AS id,
			m.subject,
			m.html != '' AS haspreview
		FROM
			#campaign_message cm,
			#message m,
			#campaign c
		WHERE
			[...]
		AND
			m.id = cm.messageid
		AND
			cm.campaignid = c.id
		AND
			c.status != 0
		GROUP BY
			cm.messageid
		ORDER BY
			m.cdate DESC
	");

	return array("row" => adesk_sql_select_array($query), "loadmore" => ($offset + $length) < $total);
}

?>
