<?php
if (!@ini_get("zlib.output_compression")) @ob_start("ob_gzhandler");

// require main include file
require_once(dirname(__FILE__) . '/awebdeskend.inc.php');
require_once(awebdesk_functions('smarty.php'));

/*
	== permission checks go here! ==
*/
if ( !adesk_admin_isadmin() ) {
	echo 'You are not logged in.';
	exit;
}

// Preload the language file
adesk_lang_get('admin');

// collect input
$mid = (int)adesk_http_param('m');
$tid = (int)adesk_http_param('t');

$source = "";
$subject = "";

if ( $mid ) {
	$message = adesk_sql_select_row("SELECT * FROM #message WHERE id = '$mid'");
	$subject = $message["subject"];
	$source = $message["html"];

	if ($source == "")
		$source = $message["text"];
} elseif ($tid) {
	$template = adesk_sql_select_row("SELECT * FROM #template WHERE id = '$tid'");
	$source = $template["content"];
	$subject = $template["name"];
}

# No source?
if ($source == "") {
	$source = "<div style='font-size: 64px; color: #ccc'>" . _a("No Preview") . "</div>";
	$subject = _a("No Preview");
}

// Smarty Template system setup
$smarty = new adesk_Smarty('admin');

// assigning smarty reserved vars
$smarty->assign('site', $site);
$smarty->assign('admin', $admin);
$smarty->assign('source', $source);
$smarty->assign('subject', $subject);

$smarty->display('htmlpreview.htm');

?>
