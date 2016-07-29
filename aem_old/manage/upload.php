<?php
// require main include file
require_once(dirname(__FILE__) . '/awebdeskend.inc.php');
require_once(awebdesk_functions('smarty.php'));

// turning off some php limits
@ignore_user_abort(1);
@ini_set('max_execution_time', 950 * 60);
@set_time_limit(950 * 60);
$ml = ini_get('memory_limit');
if ( $ml != -1 and (int)$ml < 128 and substr($ml, -1) == 'M') @ini_set('memory_limit', '128M');
set_include_path('.');
@set_magic_quotes_runtime(0);




/*
	== permission checks go here! ==
*/
if ( !adesk_admin_isadmin() ) {
	echo 'You are not logged in.';
	exit;
}



// Preload the language file
adesk_lang_get('admin');





// Smarty Template system setup
$smarty = new adesk_Smarty('global');


$action = adesk_http_param("action");


// assigning smarty reserved vars
$smarty->assign('site', $site);
$smarty->assign('admin', $admin);

// get page params
$smarty->assign('public', !adesk_str_instr('/manage/', $_SERVER['REQUEST_URI']));
$smarty->assign('id', adesk_http_param('id')); // field id
$smarty->assign('name', adesk_http_param('name')); // field name
$smarty->assign('action', $action); // server action


$relid = adesk_http_param('relid'); // relation id(s)
$limit = (int)adesk_http_param('limit'); // upload limit

/*
	UPLOAD A FILE
*/

// figure out state
$submitted = $_SERVER['REQUEST_METHOD'] == 'POST';
$result = array('succeeded' => false, 'message' => 'Command not provided.', 'id' => 0, 'filename' => '', 'filesize' => 0);

// figure out action
if ( $action == 'something_attach?' ) {
	// 2do
} elseif ( $action == 'design_upload' ) {
	//
	if ( $submitted ) {
		// save file
		$result = adesk_file_upload('logo_group' . $relid, adesk_base('images/admin'), '', 'groupid', (int)$relid);
	}
	$limit = 1;
} elseif ( $action == 'optinoptout_attach' ) {
	// only if submitted
	if ( $submitted ) {
		// save file
		if ( $site['message_attachments_location'] == 'db' ) {
			$result = adesk_file_upload('optinoptout', '#optinoptout_file', '#optinoptout_file_data', 'optinoptoutid', (int)$relid);
		} else {
			$result = adesk_file_upload('optinoptout', '#optinoptout_file', adesk_base('files'), 'optinoptoutid', (int)$relid);
		}
	}
} elseif ( $action == 'message_attach' ) {
	// only if submitted
	if ( $submitted ) {
		// save file
		if ( $site['message_attachments_location'] == 'db' ) {
			$result = adesk_file_upload('message', '#message_file', '#message_file_data', 'messageid', (int)$relid);
		} else {
			$result = adesk_file_upload('message', '#message_file', adesk_base('files'), 'messageid', (int)$relid);
		}
	}
} elseif ( $action == 'message_fetch' ) {
	// only if submitted
	if ( $submitted ) {
		// save file
		$result = adesk_file_upload('msgimport', adesk_cache_dir(), '', 'messageid', (int)$relid);
	}
	$limit = 1;
} elseif ( $action ==  'template_import' ) {
	// only if submitted
	if ( $submitted ) {
		// save file
		$result = adesk_file_upload('tplimport', adesk_cache_dir(), '', 'templateid', $relid);
	}
	$limit = 1;
} elseif ( $action == 'subscriber_import' ) {
	// only if submitted
	if ( $submitted ) {
		// save file
		$result = adesk_file_upload('csvimport-' . $admin['id'], adesk_cache_dir(), '', 'subscriberid', $relid);
	}
	$limit = 1;
} elseif ( $action ==  'template_preview' ) {
	// only if submitted
	if ( $submitted ) {
		$proceed = true;
		// validate image dimensions
		/*
		$imagesize = getimagesize($_FILES['adesk_uploader']['tmp_name']);
		if ($imagesize[0] < 200 || $imagesize[1] < 250) {
			$proceed = false;
			$submitted = true;
			$result = array('succeeded' => false, 'message' => _a('Your image should be 200px width by 250px height'), 'id' => 0, 'filename' => '', 'filesize' => 0);
		}
		*/
		// validate image filesize
		$file_size_kb = $_FILES['adesk_uploader']['size'] / 1000;
		if ( $file_size_kb > 500 ) {
			$proceed = false;
			$submitted = true;
			$result = array('succeeded' => false, 'message' => _a("Your image file size (" . adesk_file_humansize($_FILES['adesk_uploader']['size']) . ") is too large - please keep it under 500 KB."), 'id' => 0, 'filename' => '', 'filesize' => 0);
		}
		// validate file extension
		$preview_extensions_allowed = array('jpg', 'jpeg', 'gif', 'png');
		$filename_array = explode('.', $_FILES['adesk_uploader']['name']);
		$file_ext = strtolower($filename_array[ count($filename_array) - 1 ]);
		// verify only allowed extension is uploaded
		if ( !in_array( $file_ext, $preview_extensions_allowed ) ) {
			$proceed = false;
			$submitted = true;
			$result = array('succeeded' => false, 'message' => _a('Template preview file can only be JPG, GIF, or PNG'), 'id' => 0, 'filename' => '', 'filesize' => 0);
		}
		if ($proceed) {
			if ( isset($_POST['cache_filename']) ) {
				// POST var is a temporary placeholder for the previous image uploaded.
				// in other words, they come to page and upload a different file many times - each time we land here, and we have the previous file they uploaded,
				// so we can continue to remove any lingering cache files (until they finally hit "Update") and everything gets saved
				// this POST DOM element gets appended into the upload iframe; look in `ihook_adesk_upload_js_addon()`.
				// delete the old cache file
				$cache_filepath_previous = adesk_cache_dir() . '/' . $_POST['cache_filename'];
				@unlink($cache_filepath_previous);
			}
			// unique string
			$hash = md5($_FILES['adesk_uploader']['name'] . time() . $relid);
			// the filename that it will have in the cache folder temporarily
			$cache_filename = 'template_preview_' . $hash . '-' . $_FILES['adesk_uploader']['name'];
			// upload it
			$result = adesk_file_upload('template_preview_' . $hash, adesk_cache_dir(), '', 'templateid', $relid);
			// save the cache filename to access later
			$result['cache_filename'] = $cache_filename;
			$result['cache_filename_mimetype'] = $_FILES['adesk_uploader']['type'];
		}
	}
	$limit = 1;
} else {
	$submitted = true;
	//die('Action not supported.');
}

$result['action'] = $action;
$result['humansize'] = adesk_file_humansize($result['filesize']);



$smarty->assign('submitted', $submitted);
$smarty->assign('result', $result);
$smarty->assign('relid', $relid);
$smarty->assign('limit', $limit);

$smarty->assign('additional', (string)adesk_ihook('adesk_upload_js_addon', $action, $result));

// loading the main template
$smarty->display('iframe.upload.htm');

?>
