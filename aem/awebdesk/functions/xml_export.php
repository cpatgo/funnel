<?php

# This file is not specifically named for hooks, but it is just for hooks.

require_once('./base.php');
require_once('./b64.php');
require_once('./hook.php');
require_once(adesk_admin('awebdeskend.inc.php'));

session_cache_limiter("must-revalidate");

if (isset($_GET["groupid"]))
    $xml = adesk_hook_export_plugin($_GET["groupid"]);
elseif (isset($_GET["hookid"]))
    $xml = adesk_hook_export($_GET["hookid"]);
else
    exit;

header("Content-Type: text/xml");
header("Content-Length: ".strlen($xml));

if (preg_match("/MSIE 5.5/", $_SERVER['HTTP_USER_AGENT']))
    header('Content-Disposition: filename="export.xml"');
else
    header('Content-Disposition: attachment; filename="export.xml"');


echo $xml;

?>
