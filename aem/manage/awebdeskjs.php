<?php

if (!@ini_get("zlib.output_compression")) @ob_start("ob_gzhandler");

require_once(dirname(__FILE__) . '/awebdeskend.inc.php');
require_once(awebdesk_functions('smarty.php'));

// Preload the language file
adesk_lang_get('admin');

$smarty = new adesk_Smarty('admin');
$smarty->assign('jsSite', adesk_array_keys_remove($site, array('serial', 'av', 'avo', 'ac', 'acu', 'acec', 'acar', 'acad', 'acff', 'smpass')));
$smarty->assign('jsAdmin', adesk_array_keys_remove($admin, array('password')));

header("Content-Type: text/javascript");
$smarty->display("mainjs.inc.js");

?>
