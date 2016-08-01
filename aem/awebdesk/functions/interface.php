<?php

require_once awebdesk_functions('site.php');
require_once awebdesk_functions('http.php');
require_once awebdesk_functions('session.php');

function adesk_interface_init_basic($timeLimit = 30) {
	adesk_php_environment($timeLimit, $errorReporting = 2, $startSession = true);
}

function adesk_interface_init() {
    adesk_interface_init_basic();

    require_once(adesk_admin('functions/awebdesk.php'));
    require_once(adesk_admin('config.inc.php'));
    require_once(adesk_admin('config_ex.inc.php'));

    if (!isset($GLOBALS['db_link']) || !$GLOBALS['db_link'] || !isset($GLOBALS['db_linkdb']) || !$GLOBALS['db_linkdb']) {
        echo 'Please run <a href="install.php" rel="nofollow">install.php</a> to install this product.';
        exit;
    }

    adesk_session_drop_cache();

    if (isset($_SESSION["adesk_chart_hashes"])) {
        foreach ($_SESSION["adesk_chart_hashes"] as $hash)
            unset($_SESSION["adesk_chart_".$hash]);
        unset($_SESSION["adesk_chart_hashes"]);
    }
}

function adesk_interface_finish() {
//  @set_magic_quotes_runtime($GLOBALS['old_magic_quotes']);
#   if (isset($GLOBALS['auth_db_link']))
#       mysql_close($GLOBALS['auth_db_link']);
#   mysql_close($GLOBALS['db_link']);
}

function adesk_interface_logout($mesg = 'Timeout') {
    $go2 = 'index.php?error_mesg='.urlencode($mesg);
    if (isset($_GET['id']))
        $go2 .= '&id=' . $_GET['id'];

    adesk_interface_redirect($go2);
}

function adesk_interface_flash_header_fix() {
    session_cache_limiter('public');
    header('Expires: Mon, 20 Dec 1998 01:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-cache, must-revalidate');
    header('Pragma: no-cache');
}

function adesk_interface_init_smarty(&$smarty, $template) {
    $idt = isset($_GET['id']) ? $_GET['id'] : '';
    $actiont = isset($_GET['action']) ? $_GET['action'] : '';

    $smarty->assign('idt', $idt);
    $smarty->assign('actiont', $actiont);
    $smarty->assign('error_mesg', isset($GLOBALS['error_mesg']) ? $GLOBALS['error_mesg'] : '');
    $smarty->assign('site', isset($GLOBALS['site']) ? $GLOBALS['site'] : array());
    $smarty->assign('admin', isset($GLOBALS['admin']) ? $GLOBALS['admin'] : array());
    $smarty->assign('content_template', $template);
    $smarty->assign('languages', isset($GLOBALS['languages']) ? $GLOBALS['languages'] : array());
}

function adesk_interface_redirect($relurl) {
    if (isset($_SERVER["SERVER_SOFTWARE"]) && substr($_SERVER["SERVER_SOFTWARE"], 0, 5) == "Micro") {
        echo "<html><HEAD><TITLE>Redirect</TITLE>" .
             "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=$relurl\"></head><body>" .
             "Redirecting to ... <a href=\"" . $relurl .
             "\">here</a>.</body></html>.\n";
    } else {
        header("Location: $relurl");
        echo "<html><head><title>Redirect</title></head><body>" .
        "Redirecting to ... <a href=\"" . $relurl .
        "\">here</a>.</body></html>.\n";
    }

    exit;
}

function adesk_interface_check_version($version) {
 
}

function adesk_interface_check_logout($action, &$smarty) {
    if ($action == 'logout') {
        adesk_auth_logout();
        adesk_smarty_message($smarty, _a("You have been successfully logged out"), 1);

		# The global $admin variable should be false if you are not logged in.

		$GLOBALS["admin"] = false;

		adesk_ihook("adesk_interface_check_logout_post");
    }
}

function adesk_interface_check_account_lookup($action, &$smarty) {
    require_once awebdesk_functions('manage.php');
    if ($action == 'account_lookup') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST['user']) && isset($_POST['email'])) {
            $message = adesk_admin_lookup(adesk_sql_escape($_POST['user']), adesk_sql_escape($_POST['email']));
            $smarty->assign('message', $message);
            $template = 'account_lookup_message.tpl.htm';
        } elseif (isset($_GET['r']) && isset($_GET['r2'])) {
            $message = adesk_admin_resetpass($_GET['r'], $_GET['r2']);
            $smarty->assign('message', $message);
            $template = 'account_lookup_message.tpl.htm';
        } else {
            $template = 'account_lookup.tpl.htm';
        }

        $smarty->assign('content_template', $template);
        return true;
    }

    return false;
}

function adesk_interface_check_lang_site(&$langs, &$site) {
    if (!isset($langs[$site['lang']]))
        $site['lang'] = 'english';
}

function adesk_interface_check_lang_admin(&$langs, &$site, &$admin) {
    if (adesk_http_param('lang'))
        $admin['lang'] = adesk_http_param('lang');
    elseif (adesk_http_param('lang_ch'))
        $admin['lang'] = adesk_http_param('lang_ch');

    adesk_interface_check_lang_site($langs, $site);

    if (!isset($langs[$admin['lang']]))
        $admin['lang'] = $site['lang'];
}

function adesk_interface_debug() {
    if (isset($_GET['debug']) && isset($_GET['c'])) {
        $site = adesk_site_unsafe();
        if ($site['ac'] == $_GET['c']) {
            // Check activation field against $_GET[c]
            if ($_GET["debug"] == 'i') {
                phpinfo();
                die();
            } elseif ($_GET['debug'] == 't') {
                @mail('test@awebdesk.com', 'My Subject', "Line 1\nLine 2", 'From: test@awebdesk.com');
                print 'Test e-mail attempt to address: test@awebdesk.com';
                die();
            } else {
                print "Debug error";
                die();
            }
        }
    }
}

function adesk_interface_rss(&$smarty, $url, $title) {
	$hlines = $smarty->get_template_vars("header_lines");
	$title  = htmlspecialchars($title);
	$title  = str_replace("'", "&#039;", $title);

	if (!$hlines || !is_array($hlines))
		$hlines = array();

	$hlines[] = "<link rel='alternate' href='$url' type='application/rss+xml' title='$title' />";
	$smarty->assign("header_lines", $hlines);
}

function adesk_interface_utfconvert_gp($charset) {
	adesk_charset_convert_gp($charset, 'utf-8');
}

?>
