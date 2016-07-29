<?php
if (!@ini_get("zlib.output_compression")) @ob_start("ob_gzhandler");

define('AWEBVIEW', true);
define('AWEBP_USER_NOAUTH', true);

// require main include file
require_once(dirname(__FILE__) . '/manage/awebdeskend.inc.php');
require_once(awebdesk_functions('smarty.php'));

// Preload the language file
adesk_lang_get('public');

$smarty = new adesk_Smarty('public');

$action = adesk_http_param('action');
$smarty->assign("action", $action);

$smarty->assign("_", $site["p_link"]);

$smarty->assign('site', $site);
$smarty->assign('plink', adesk_site_plink());

$smarty->assign('jsSite', adesk_array_keys_remove($site, array('serial', 'av', 'avo', 'ac', 'acu', 'acec', 'acar', 'acad', 'acff', 'smpass')));
$smarty->assign('jsAdmin', adesk_array_keys_remove($admin, array('password')));

$smarty->display("mainjs.inc.js");

?>
