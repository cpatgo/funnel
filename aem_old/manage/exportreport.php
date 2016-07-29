<?php

require_once(dirname(__FILE__) . '/awebdeskend.inc.php');
require_once(adesk_admin('assets/report_campaign.php'));
require_once(awebdesk_classes('awdzip.php'));
//require_once(awebdesk_functions('zip.php'));


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


// get input vars
$ids = (string)adesk_http_param('ids');
$reports = (string)adesk_http_param('reports');

// break input vars
$idsarr = array_map('intval', explode(',', $ids));
$reportsarr = explode(',', $reports);

// clean them up
$idsarr = array_diff($idsarr, array(0));
$reportsarr = array_intersect($reportsarr, array('open', 'link', 'click', 'forward', 'bounce', 'unsub', 'update'));

// if not properly requested
if ( !$idsarr or !$reportsarr ) {
	echo _a('Campaign Reports Information not provided.');
	exit;
}

// initialize the report_campaign assets (used for exporting)
$assets = new report_campaign_assets();

// initialize the array that will hold generated filenames
$files = array();

// create a new temp folder
$dir = md5($admin['id'] . time());
if ( !@mkdir(adesk_cache_dir($dir), 0777) ) {
	echo _a('Campaign Reports could not be build. (error=1)');
	exit;
}
@chmod(adesk_cache_dir($dir), 0777);
// for every campaign
foreach ( $idsarr as $cid ) {
	//
	// for every report type
	foreach ( $reportsarr as $mode ) {
		// build a report
		if ( $mode == 'click' ) {
			$links = adesk_sql_select_list("SELECT `id` FROM #link WHERE `campaignid` = '$cid' AND `messageid` != 0 AND `link` NOT IN ('', 'open') AND `link` IS NOT NULL");
			foreach ( $links as $lid ) {
				$_GET['linkid'] = $lid;
				$report = $assets->export('linkinfo', $cid, 0);
				// save it as a file
				$filename = adesk_cache_dir("$dir/campaign$cid-link$lid-$mode.csv");
				adesk_file_put($filename, $report);
				if(file_exists($filename)) $files[$filename] = $filename;
			}
		} else {
			$mode = ( $mode == 'click' ? 'linkinfo' : $mode );
			$report = $assets->export($mode, $cid, 0);
			// save it as a file
			$filename = adesk_cache_dir("$dir/campaign$cid-$mode.csv");
			adesk_file_put($filename, $report);
			if(file_exists($filename)) $files[$filename] = $filename;
		}
	}
}
if(!$files) die("Export files could not be created by server and written to the cache/" . $dir . "/ directory. Please contact your server administrator for further assistance.");
// zip up the files
$mimetype = 'application/zip';
$name = 'reports.zip';
$ziploc = adesk_cache_dir("$dir/$name");
if ( file_exists($ziploc) ) @unlink($ziploc);
$zipper = new ACZIPBuilder($ziploc);
$r = $zipper->create($files, PCLZIP_OPT_REMOVE_ALL_PATH);
if ( !$r ) {
	die("Error : " . $zipper->errorInfo(true));
}

// get the ZIP file contents
$content = adesk_file_get($ziploc);

// delete the temp folder
adesk_file_rmdir_recursive(adesk_cache_dir($dir));

// push the zip file
adesk_http_header_attach($name, strlen($content), $mimetype);
echo $content;

?>