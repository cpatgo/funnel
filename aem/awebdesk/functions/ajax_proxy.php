<?php

require_once dirname(dirname(__FILE__)) . '/functions/base.php';
require_once awebdesk_functions('b64.php');
require_once awebdesk_functions('http.php');

$wlfile = adesk_admin('functions/ajax_whitelist.php');

header("Content-Type: text/xml");

if (isset($_GET["url"]) && file_exists($wlfile)) {
    $url    = adesk_b64_decode($_GET["url"]);

    if (!isset($_SESSION['adesk_ajax_whitelist'])) {
        require_once $wlfile;

        if (!isset($_SESSION['adesk_ajax_whitelist']))
            exit;
    }

    $list = $_SESSION['adesk_ajax_whitelist'];
    
    foreach ($list as $patn) {
        if (substr($url, 0, strlen($patn)) == $patn) {
			if (isset($_GET["post"]) && $_GET["post"] == 1)
				echo adesk_http_post($url, $_POST);
			else
				echo adesk_http_get($url);
            exit;
        }
    }
}

?>
