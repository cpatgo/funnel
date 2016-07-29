<?php
// require main include file
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/manage/awebdeskend.inc.php');
require_once(dirname(dirname(dirname(__FILE__)))) . '/functions/basic.php';
require_once(dirname(dirname(dirname(__FILE__)))) . '/functions/smarty.php';


if ( !isset($site) ) $site = adesk_site_get();
if ( !isset($admin) ) $admin = adesk_admin_get();


if ( !adesk_admin_ismain() ) {
	echo 'You are not logged in.';
	exit;
}



// Preload the language file
adesk_lang_get('admin');





// Smarty Template system setup
$smarty = new adesk_Smarty('global');





// assigning smarty reserved vars
$smarty->assign('site', $site);
$smarty->assign('admin', $admin);


/*
	CHECK FOR UPDATES
*/
$newVersion = false;
$version = '';

// just main admin can see this, if it's turned ON (on=1=adminID)
if ( $site['updatecheck'] == 1 ) {
	// after more than 30 days since last check
	 
	 
}
$smarty->assign('newVersion', $newVersion);
$smarty->assign('version', $version);

// check for updates link
$settings_update_link = '../../../';
if ( in_array('settings_update', $GLOBALS['adesk_assets_whitelist_admin']) )
	$settings_update_link .= 'manage/';
$settings_update_link .= 'desk.php?action=settings_update';
$smarty->assign('settings_update_link', $settings_update_link);




// loading the main template
$smarty->display('iframe.updatecheck.htm');

?>