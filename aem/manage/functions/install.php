<?php
/**
 * This is an include file that adds all data to application tables during installation
 */

require_once(dirname(dirname(__FILE__)) . '/functions/aem.php');

// what is our upload limit?
$uploadLimit = adesk_php_inisize(ini_get('upload_max_filesize'));
// what is our post limit?
$postLimit = adesk_php_inisize(ini_get('post_max_size'));

$minimumUpload = ( $uploadLimit < $postLimit ? $uploadLimit : $postLimit );
$maxuploadfilesize = ( $minimumUpload < 10 * 1024 * 1024 ? (int)adesk_file_humansize($minimumUpload) : 10 );

$gd = (int)function_exists('gd_info');

//$uploadHumanLimit = adesk_file_humansize($uploadLimit);
//$postHumanLimit = adesk_file_humansize($postLimit);

// backend table
spit(_a('Adding application data: '), 'em');
$insert = array(
	'id' => 1,
	'emfrom' => $i['site']['emfrom'],
	//'site_name' => $i['site']['site_name'],
	'p_link' => $i['site']['murl'],
	'serial' => $i['backend']['dl_s'],
	'av' => $av,
	'avo' => $i['backend']['dl_dd'],
	'ac' => $i['backend']['dr3292'],
	'acu' => $i['backend']['dl_acu'],
	'acec' => $i['backend']['acec'],
	'acar' => $i['backend']['acar'],
	'acad' => $i['backend']['acad'],
	'acff' => $i['backend']['acff'],
	'acpow' => ( isset($i['backend']['acpow']) ? $i['backend']['acpow'] : '' ),
	'version' => $thisVersion,
	'lang' => $lang,
	'local_zoneid' => $i['site']['zoneid'],
	't_offset' => $t_offset,
	't_offset_o' => $t_offset_o,
	'updateversion' => $thisVersion,
	'=updatedate' => 'SUBDATE(NOW(), INTERVAL 31 DAY)',
	'maxuploadfilesize' => $maxuploadfilesize,
	'datetimeformat' => _d('%m/%d/%Y %H:%M'),
	'dateformat' => _d('%m/%d/%Y'),
	'timeformat' => _d('%H:%M'),
	'mail_abuse' => 0,
	//'captcha' => $gd, // if gdlib is present
	//'design_logo_admin' => $i['site']['murl'] . '/manage/images/logo.gif',
);
$done = adesk_sql_insert('#backend', $insert);
if ( !$done ) {
	spit(_a('Error'), 'strong|error', 1);
	error_save("QUERY FAILED: " . adesk_sql_lastquery() . "\n\n ERROR: " . adesk_sql_error(), true);
} else {
	spit(_a('Done'), 'strong|done', 1);
}
if ( $fatal ) return;



// group table (3 basic groups)
spit(_a('Adding groups: '), 'em');
$insert = array(
	array(
		'id' => 1,
		'title' => _a('Visitor'),
		'descript' => _a('This is a group for site visitors (people that are not logged in)'),
		'unsubscribelink' => 0,
		'optinconfirm' => 0,
		'p_admin' => 0,
		'pg_list_add' => 0,
		'pg_list_edit' => 0,
		'pg_list_delete' => 0,
		'pg_list_opt' => 0,
		'pg_list_headers' => 0,
		'pg_list_emailaccount' => 0,
		'pg_list_bounce' => 0,
		'pg_message_add' => 0,
		'pg_message_edit' => 0,
		'pg_message_delete' => 0,
		'pg_message_send' => 0,
		'pg_subscriber_add' => 0,
		'pg_subscriber_edit' => 0,
		'pg_subscriber_delete' => 0,
		'pg_subscriber_import' => 0,
		'pg_subscriber_approve' => 0,
		'pg_subscriber_export' => 0,
		'pg_subscriber_sync' => 0,
		'pg_subscriber_filters' => 0,
		'pg_subscriber_actions' => 0,
		'pg_subscriber_fields' => 0,
		'pg_user_add' => 0,
		'pg_user_edit' => 0,
		'pg_user_delete' => 0,
		'pg_group_add' => 0,
		'pg_group_edit' => 0,
		'pg_group_delete' => 0,
		'pg_template_add' => 0,
		'pg_template_edit' => 0,
		'pg_template_delete' => 0,
		'pg_personalization_add' => 0,
		'pg_personalization_edit' => 0,
		'pg_personalization_delete' => 0,
		'pg_form_add' => 0,
		'pg_form_edit' => 0,
		'pg_form_delete' => 0,
		'pg_reports_campaign' => 0,
		'pg_reports_list' => 0,
		'pg_reports_user' => 0,
		'pg_reports_trend' => 0,
		'pg_startup_reports' => 0,
		'pg_startup_gettingstarted' => 0,
		'=sdate' => 'NOW()',
		'req_approval' => 0,
		'req_approval_1st' => 2,
		'req_approval_notify' => '',
	),
	array(
		'id' => 2,
		'title' => _a('User'),
		'descript' => _a('This is a default user group (people that are logged in)'),
		'unsubscribelink' => 0,
		'optinconfirm' => 0,
		'p_admin' => 0,
		'pg_list_add' => 0,
		'pg_list_edit' => 0,
		'pg_list_delete' => 0,
		'pg_list_opt' => 0,
		'pg_list_headers' => 0,
		'pg_list_emailaccount' => 0,
		'pg_list_bounce' => 0,
		'pg_message_add' => 0,
		'pg_message_edit' => 0,
		'pg_message_delete' => 0,
		'pg_message_send' => 0,
		'pg_subscriber_add' => 0,
		'pg_subscriber_edit' => 0,
		'pg_subscriber_delete' => 0,
		'pg_subscriber_import' => 0,
		'pg_subscriber_approve' => 0,
		'pg_subscriber_export' => 0,
		'pg_subscriber_sync' => 0,
		'pg_subscriber_filters' => 0,
		'pg_subscriber_actions' => 0,
		'pg_subscriber_fields' => 0,
		'pg_user_add' => 0,
		'pg_user_edit' => 0,
		'pg_user_delete' => 0,
		'pg_group_add' => 0,
		'pg_group_edit' => 0,
		'pg_group_delete' => 0,
		'pg_template_add' => 0,
		'pg_template_edit' => 0,
		'pg_template_delete' => 0,
		'pg_personalization_add' => 0,
		'pg_personalization_edit' => 0,
		'pg_personalization_delete' => 0,
		'pg_form_add' => 0,
		'pg_form_edit' => 0,
		'pg_form_delete' => 0,
		'pg_reports_campaign' => 0,
		'pg_reports_list' => 0,
		'pg_reports_user' => 0,
		'pg_reports_trend' => 0,
		'pg_startup_reports' => 0,
		'pg_startup_gettingstarted' => 0,
		'=sdate' => 'NOW()',
		'req_approval' => 0,
		'req_approval_1st' => 2,
		'req_approval_notify' => '',
	),
	array(
		'id' => 3,
		'title' => _a('Admin'),
		'descript' => _a('This is a group for admin users (people that can manage content)'),
		'unsubscribelink' => 0,
		'optinconfirm' => 0,
		'p_admin' => 1,
		'pg_list_add' => 1,
		'pg_list_edit' => 1,
		'pg_list_delete' => 1,
		'pg_list_opt' => 1,
		'pg_list_headers' => 1,
		'pg_list_emailaccount' => 1,
		'pg_list_bounce' => 1,
		'pg_message_add' => 1,
		'pg_message_edit' => 1,
		'pg_message_delete' => 1,
		'pg_message_send' => 1,
		'pg_subscriber_add' => 1,
		'pg_subscriber_edit' => 1,
		'pg_subscriber_delete' => 1,
		'pg_subscriber_import' => 1,
		'pg_subscriber_approve' => 1,
		'pg_subscriber_export' => 1,
		'pg_subscriber_sync' => 1,
		'pg_subscriber_filters' => 1,
		'pg_subscriber_actions' => 1,
		'pg_subscriber_fields' => 1,
		'pg_user_add' => 1,
		'pg_user_edit' => 1,
		'pg_user_delete' => 1,
		'pg_group_add' => 1,
		'pg_group_edit' => 1,
		'pg_group_delete' => 1,
		'pg_template_add' => 1,
		'pg_template_edit' => 1,
		'pg_template_delete' => 1,
		'pg_personalization_add' => 1,
		'pg_personalization_edit' => 1,
		'pg_personalization_delete' => 1,
		'pg_form_add' => 1,
		'pg_form_edit' => 1,
		'pg_form_delete' => 1,
		'pg_reports_campaign' => 1,
		'pg_reports_list' => 1,
		'pg_reports_user' => 1,
		'pg_reports_trend' => 1,
		'pg_startup_reports' => 1,
		'pg_startup_gettingstarted' => 1,
		'=sdate' => 'NOW()',
		'req_approval' => 0,
		'req_approval_1st' => 2,
		'req_approval_notify' => '',
	),
);
$done = !$fatal && adesk_sql_insert('#group', $insert);
if ( !$done ) {
	spit(_a('Error'), 'strong|error', 1);
	error_save("QUERY FAILED: " . adesk_sql_lastquery() . "\n\n ERROR: " . adesk_sql_error(), true);
} else {
	spit(_a('Done'), 'strong|done', 1);
}
if ( $fatal ) return;



// limits table (3 basic groups)
spit(_a('Adding group limits: '), 'em');
$insert = array(
	array(
		'id' => 1,
		'groupid' => 1,
	),
	array(
		'id' => 2,
		'groupid' => 2,
	),
	array(
		'id' => 3,
		'groupid' => 3,
	),
);
$done = !$fatal && adesk_sql_insert('#group_limit', $insert);
if ( !$done ) {
	spit(_a('Error'), 'strong|error', 1);
	error_save("QUERY FAILED: " . adesk_sql_lastquery() . "\n\n ERROR: " . adesk_sql_error(), true);
} else {
	spit(_a('Done'), 'strong|done', 1);
}
if ( $fatal ) return;

require_once awebdesk_functions("auth.php");
$auth = adesk_auth_record_id(1);

// user table
spit(_a('Adding admin user: '), 'em');
$insert = array(
	'id' => 1,
	'absid' => 1,
	'parentid' => 0,
	'=last_login' => 'NOW()',
	'=ldate' => 'NOW()',
	'=ltime' => 'NOW()',
	'local_zoneid' => $i['site']['zoneid'],
	't_offset' => $t_offset,
	't_offset_o' => $t_offset_o,
	'lang' => $lang,
	'username' => 'admin',
	'first_name' => $auth["first_name"],
	'last_name' => $auth["last_name"],
	'email' => $auth["email"],
	'=sdate' => 'NOW()',
);
$done = adesk_sql_insert('#user', $insert);
if ( !$done ) {
	spit(_a('Error'), 'strong|error', 1);
	error_save("QUERY FAILED: " . adesk_sql_lastquery() . "\n\n ERROR: " . adesk_sql_error(), true);
}
if ( $fatal ) return;



// user group
$insert = array(
	'id' => 1,
	'userid' => 1,
	'groupid' => 3,
);
$done = adesk_sql_insert('#user_group', $insert);
if ( !$done ) {
	spit(_a('Error'), 'strong|error', 1);
	error_save("QUERY FAILED: " . adesk_sql_lastquery() . "\n\n ERROR: " . adesk_sql_error(), true);
} else {
	spit(_a('Done'), 'strong|done', 1);
}
if ( $fatal ) return;



// branding settings
spit(_a('Adding branding settings: '), 'em');
$insert = array(
	'id' => 1,
	'groupid' => 3,
	'site_name' => $i['site']['site_name'],
	'site_logo' => $i['site']['murl'] . '/manage/images/logo.gif',
);
$done = adesk_sql_insert('#branding', $insert);
if ( !$done ) {
	spit(_a('Error'), 'strong|error', 1);
	error_save("QUERY FAILED: " . adesk_sql_lastquery() . "\n\n ERROR: " . adesk_sql_error());
} else {
	spit(_a('Done'), 'strong|done', 1);
}



// default subscription form (used on public side)
spit(_a('Adding default subscription form: '), 'em');
$insert = array(
	'id' => 1000,
	'name' => _a('Default Subscription Form'),
);
$done = adesk_sql_insert('#form', $insert);
if ( !$done ) {
	spit(_a('Error'), 'strong|error', 1);
	error_save("QUERY FAILED: " . adesk_sql_lastquery() . "\n\n ERROR: " . adesk_sql_error());
} else {
	spit(_a('Done'), 'strong|done', 1);
}



// email confirmation sets
spit(_a('Adding email confirmation sets: '), 'em');
$insert = array(
	'id' => 1,
	'name' => _a('Default Email Confirmation Set'),
	'optin_confirm' => 1,
	'optin_format' => 'mime',
	'optin_from_name' => $i['site']['site_name'],
	'optin_from_email' => $i['site']['emfrom'],
	'optin_subject' => "%LISTNAME%",
	'optin_text' => _a("Thank you for subscribing.") . "\n\n" . _a("Click here to confirm your subscription:") . "\n%CONFIRMLINK%",
	'optin_html' => '<body><div style="font-size: 12px; font-family: Arial, Helvetica;"><strong>'
		. _a("Thank you for subscribing to %LISTNAME%!")
		. '</strong></div> <div style="padding: 15px; font-size: 12px; background: #F2FFD8; border: 3px solid #E4F4C3; margin-bottom: 0px; margin-top: 15px; font-family: Arial, Helvetica;">'
		. _a("To confirm that you wish to be subscribed, please click the link below:")
		. '<br /><br /><a href="%CONFIRMLINK%"><strong>'
		. _a("Confirm My Subscription")
		. '</strong></a></div><p> </p></body>',
	'optout_confirm' => 0,
	'optout_format' => 'mime',
	'optout_from_name' => $i['site']['site_name'],
	'optout_from_email' => $i['site']['emfrom'],
	'optout_subject' => _a("Please confirm your unsubscription"),
	'optout_text' => _a("Please click this link to confirm your unsubscription:") . " %CONFIRMLINK%",
	'optout_html' => _a("Please click this link to confirm your unsubscription:") . "<br /><a href=\"%CONFIRMLINK%\">%CONFIRMLINK%</a>",
);
$done = adesk_sql_insert('#optinoptout', $insert);
if ( !$done ) {
	spit(_a('Error'), 'strong|error', 1);
	error_save("QUERY FAILED: " . adesk_sql_lastquery() . "\n\n ERROR: " . adesk_sql_error());
} else {
	spit(_a('Done'), 'strong|done', 1);
}



// mailer
spit(_a('Adding mailer info (mail() method by default): '), 'em');
$insert = array(
	'id' => 1,
	'name' => _a('Default'),
	'type' => 0,
	'current' => 1,
	'frequency' => 0,
	'pause' => 0,
	'name' => _a('Default'),
);
$done = adesk_sql_insert('#mailer', $insert);
if ( !$done ) {
	spit(_a('Error'), 'strong|error', 1);
	error_save("QUERY FAILED: " . adesk_sql_lastquery() . "\n\n ERROR: " . adesk_sql_error(), true);
} else {
	spit(_a('Done'), 'strong|done', 1);
}



// group mailers table
spit(_a('Adding group mailers: '), 'em');
$insert = array(
	array(
		'id' => 1,
		'groupid' => 3,
		'mailerid' => 1,
	),
);
$done = !$fatal && adesk_sql_insert('#group_mailer', $insert);
if ( !$done ) {
	spit(_a('Error'), 'strong|error', 1);
	error_save("QUERY FAILED: " . adesk_sql_lastquery() . "\n\n ERROR: " . adesk_sql_error(), true);
} else {
	spit(_a('Done'), 'strong|done', 1);
}
if ( $fatal ) return;



// bounce settings
spit(_a('Adding bounce settings (off by default): '), 'em');
$insert = array(
	'id' => 1,
	'userid' => 1,
	'method' => 'none'
);
$done = adesk_sql_insert('#bounce', $insert);
if ( !$done ) {
	spit(_a('Error'), 'strong|error', 1);
	error_save("QUERY FAILED: " . adesk_sql_lastquery() . "\n\n ERROR: " . adesk_sql_error());
} else {
	spit(_a('Done'), 'strong|done', 1);
}



// bounce codes
spit(_a('Adding known bounce codes: '), 'em');
$insert = array(
	array(
		'id' => '1',
		'code' => '5.1.0',
		'match' => '5.1.0',
		'type' => 'hard',
		'descript' => _a('Other address status'),
	),
	array(
		'id' => '2',
		'code' => '5.1.1',
		'match' => '5.1.1',
		'type' => 'hard',
		'descript' => _a('Bad destination mailbox address'),
	),
	array(
		'id' => '3',
		'code' => '5.1.2',
		'match' => '5.1.2',
		'type' => 'hard',
		'descript' => _a('Bad destination system address'),
	),
	array(
		'id' => '4',
		'code' => '5.1.3',
		'match' => '5.1.3',
		'type' => 'hard',
		'descript' => _a('Bad destination mailbox address syntax'),
	),
	array(
		'id' => '5',
		'code' => '5.1.4',
		'match' => '5.1.4',
		'type' => 'hard',
		'descript' => _a('Destination mailbox address ambiguous'),
	),
	array(
		'id' => '6',
		'code' => '5.1.5',
		'match' => '5.1.5',
		'type' => 'hard',
		'descript' => _a('Destination mailbox address valid'),
	),
	array(
		'id' => '7',
		'code' => '5.1.6',
		'match' => '5.1.6',
		'type' => 'hard',
		'descript' => _a('Mailbox has moved'),
	),
	array(
		'id' => '8',
		'code' => '5.1.7',
		'match' => '5.1.7',
		'type' => 'hard',
		'descript' => _a("Bad sender\'s mailbox address syntax"),
	),
	array(
		'id' => '9',
		'code' => '5.1.8',
		'match' => '5.1.8',
		'type' => 'hard',
		'descript' => _a("Bad sender's system address"),
	),
	array(
		'id' => '10',
		'code' => '5.2.0',
		'match' => '5.2.0',
		'type' => 'soft',
		'descript' => _a('Other or undefined mailbox status'),
	),
	array(
		'id' => '11',
		'code' => '5.2.1',
		'match' => '5.2.1',
		'type' => 'soft',
		'descript' => _a('Mailbox disabled, not accepting messages'),
	),
	array(
		'id' => '12',
		'code' => '5.2.2',
		'match' => '5.2.2',
		'type' => 'soft',
		'descript' => _a('Mailbox full'),
	),
	array(
		'id' => '13',
		'code' => '5.2.3',
		'match' => '5.2.3',
		'type' => 'hard',
		'descript' => _a('Message length exceeds administrative limit.'),
	),
	array(
		'id' => '14',
		'code' => '5.2.4',
		'match' => '5.2.4',
		'type' => 'hard',
		'descript' => _a('Mailing list expansion problem'),
	),
	array(
		'id' => '15',
		'code' => '5.3.0',
		'match' => '5.3.0',
		'type' => 'hard',
		'descript' => _a('Other or undefined mail system status'),
	),
	array(
		'id' => '16',
		'code' => '6.1.0',
		'match' => '6.1.0',
		'type' => 'soft',
		'descript' => _a('Mail system full'),
	),
	array(
		'id' => '17',
		'code' => '5.3.2',
		'match' => '5.3.2',
		'type' => 'hard',
		'descript' => _a('System not accepting network messages'),
	),
	array(
		'id' => '18',
		'code' => '5.3.3',
		'match' => '5.3.3',
		'type' => 'hard',
		'descript' => _a('System not capable of selected features'),
	),
	array(
		'id' => '19',
		'code' => '5.3.4',
		'match' => '5.3.4',
		'type' => 'hard',
		'descript' => _a('Message too big for system'),
	),
	array(
		'id' => '20',
		'code' => '5.4.0',
		'match' => '5.4.0',
		'type' => 'hard',
		'descript' => _a('Other or undefined network or routing status'),
	),
	array(
		'id' => '21',
		'code' => '5.4.1',
		'match' => '5.4.1',
		'type' => 'hard',
		'descript' => _a('No answer from host'),
	),
	array(
		'id' => '22',
		'code' => '5.4.2',
		'match' => '5.4.2',
		'type' => 'hard',
		'descript' => _a('Bad connection'),
	),
	array(
		'id' => '23',
		'code' => '5.4.3',
		'match' => '5.4.3',
		'type' => 'hard',
		'descript' => _a('Routing server failure'),
	),
	array(
		'id' => '24',
		'code' => '5.4.4',
		'match' => '5.4.4',
		'type' => 'hard',
		'descript' => _a('Unable to route'),
	),
	array(
		'id' => '25',
		'code' => '5.4.5',
		'match' => '5.4.5',
		'type' => 'soft',
		'descript' => _a('Network congestion'),
	),
	array(
		'id' => '26',
		'code' => '5.4.6',
		'match' => '5.4.6',
		'type' => 'hard',
		'descript' => _a('Routing loop detected'),
	),
	array(
		'id' => '27',
		'code' => '5.4.7',
		'match' => '5.4.7',
		'type' => 'hard',
		'descript' => _a('Delivery time expired'),
	),
	array(
		'id' => '28',
		'code' => '5.5.0',
		'match' => '5.5.0',
		'type' => 'hard',
		'descript' => _a('Other or undefined protocol status'),
	),
	array(
		'id' => '29',
		'code' => '5.5.1',
		'match' => '5.5.1',
		'type' => 'hard',
		'descript' => _a('Invalid command'),
	),
	array(
		'id' => '30',
		'code' => '5.5.2',
		'match' => '5.5.2',
		'type' => 'hard',
		'descript' => _a('Syntax error'),
	),
	array(
		'id' => '31',
		'code' => '5.5.3',
		'match' => '5.5.3',
		'type' => 'soft',
		'descript' => _a('Too many recipients'),
	),
	array(
		'id' => '32',
		'code' => '5.5.4',
		'match' => '5.5.4',
		'type' => 'hard',
		'descript' => _a('Invalid command arguments'),
	),
	array(
		'id' => '33',
		'code' => '5.5.5',
		'match' => '5.5.5',
		'type' => 'hard',
		'descript' => _a('Wrong protocol version'),
	),
	array(
		'id' => '34',
		'code' => '5.6.0',
		'match' => '5.6.0',
		'type' => 'hard',
		'descript' => _a('Other or undefined media error'),
	),
	array(
		'id' => '35',
		'code' => '5.6.1',
		'match' => '5.6.1',
		'type' => 'hard',
		'descript' => _a('Media not supported'),
	),
	array(
		'id' => '36',
		'code' => '5.6.2',
		'match' => '5.6.2',
		'type' => 'hard',
		'descript' => _a('Conversion required and prohibited'),
	),
	array(
		'id' => '37',
		'code' => '5.6.3',
		'match' => '5.6.3',
		'type' => 'hard',
		'descript' => _a('Conversion required but not supported'),
	),
	array(
		'id' => '38',
		'code' => '5.6.4',
		'match' => '5.6.4',
		'type' => 'hard',
		'descript' => _a('Conversion with loss performed'),
	),
	array(
		'id' => '39',
		'code' => '5.6.5',
		'match' => '5.6.5',
		'type' => 'hard',
		'descript' => _a('Conversion failed'),
	),
	array(
		'id' => '40',
		'code' => '5.7.0',
		'match' => '5.7.0',
		'type' => 'hard',
		'descript' => _a('Other or undefined security status'),
	),
	array(
		'id' => '41',
		'code' => '5.7.1',
		'match' => '5.7.1',
		'type' => 'hard',
		'descript' => _a('Delivery not authorized, message refused'),
	),
	array(
		'id' => '42',
		'code' => '5.7.2',
		'match' => '5.7.2',
		'type' => 'hard',
		'descript' => _a('Mailing list expansion prohibited'),
	),
	array(
		'id' => '43',
		'code' => '5.7.3',
		'match' => '5.7.3',
		'type' => 'hard',
		'descript' => _a('Security conversion required but not possible'),
	),
	array(
		'id' => '44',
		'code' => '5.7.4',
		'match' => '5.7.4',
		'type' => 'hard',
		'descript' => _a('Security features not supported'),
	),
	array(
		'id' => '45',
		'code' => '5.7.5',
		'match' => '5.7.5',
		'type' => 'hard',
		'descript' => _a('Cryptographic failure'),
	),
	array(
		'id' => '46',
		'code' => '5.7.6',
		'match' => '5.7.6',
		'type' => 'hard',
		'descript' => _a('Cryptographic algorithm not supported'),
	),
	array(
		'id' => '47',
		'code' => '5.7.7',
		'match' => '5.7.7',
		'type' => 'hard',
		'descript' => _a('Message integrity failure'),
	),
	array(
		'id' => '48',
		'code' => '5.0.0',
		'match' => '5.0.0',
		'type' => 'hard',
		'descript' => _a('Address does not exist'),
	),
	array(
		'id' => '49',
		'code' => '9.1.1',
		'match' => 'This is a permanent error.',
		'type' => 'hard',
		'descript' => _a('Hard bounce with no bounce code found. It could be an invalid email or rejected email from your mail server (such as from a sending limit).'),
	),
);
$done = !$fatal && adesk_sql_insert('#bounce_code', $insert);
if ( !$done ) {
	spit(_a('Error'), 'strong|error', 1);
	error_save("QUERY FAILED: " . adesk_sql_lastquery() . "\n\n ERROR: " . adesk_sql_error());
} else {
	spit(_a('Done'), 'strong|done', 1);
}



// cron manager
spit(_a('Adding scheduled tasks: '), 'em');
$insert = array(
	array(
		'id' => 1,
		'stringid' => 'process',
		'name' => _a('Stalled Processes Pickup Tool'),
		'descript' => _a('This picks up stalled processes triggered by the application.  Will ensure that your mailings continue to be sent.'),
		'active' => 1,
		'filename' => './manage/process.php',
		'loglevel' => 1,
		'minute' => 'a:1:{i:0;i:5;}',
		'hour' => -1,
		'day' => -1,
		'weekday' => -1,
		'=lastrun' => 'NULL',
	),
	array(
		'id' => '2',
		'stringid' => 'sendingengine',
		'name' => _a('Sending Engine'),
		'descript' => _a('This will initiate scheduled mailings and enables the system to check if any message in a Split is a "winner"'),
		'active' => 1,
		'filename' => './manage/functions/crons/sendingengine.php',
		'loglevel' => 1,
		'minute' => 'a:1:{i:0;i:5;}',
		'hour' => -1,
		'day' => -1,
		'weekday' => -1,
		'=lastrun' => 'NULL',
	),
	array(
		'id' => '3',
		'stringid' => 'autoresponder',
		'name' => _a('Auto-Responder'),
		'descript' => _a('This sends campaigns to subscribers scheduled against the subscription date.'),
		'active' => 1,
		'filename' => './manage/functions/crons/autoresponder.php',
		'loglevel' => 1,
		'minute' => 'a:2:{i:0;i:-2;i:1;i:15;}',
		'hour' => -1,
		'day' => -1,
		'weekday' => -1,
		'=lastrun' => 'NULL',
	),
	array(
		'id' => '4',
		'stringid' => 'autoreminder',
		'name' => _a('Auto-Reminder'),
		'descript' => _a('Auto-Reminder should run only once a day, since it uses dates only, not times. It sends campaigns scheduled against the anniversary of the date stored in subscriber\'s subscription date/custom field.'),
		'active' => 1,
		'filename' => './manage/functions/crons/autoreminder.php',
		'loglevel' => 1,
		'minute' => 'a:2:{i:0;i:-2;i:1;i:0;}',
		'hour' => 0,
		'day' => -1,
		'weekday' => -1,
		'=lastrun' => 'NULL',
	),
	array(
		'id' => '5',
		'stringid' => 'bounceparser',
		'name' => _a('Bounce Management'),
		'descript' => _a('This collects bounced emails via POP3 protocol. This cron job can be turned off if you can use email piping (preferred option).'),
		'active' => 1,
		'filename' => './manage/functions/crons/bounceparser.php',
		'loglevel' => 1,
		'minute' => 'a:1:{i:0;i:15;}',
		'hour' => -1,
		'day' => -1,
		'weekday' => -1,
		'=lastrun' => 'NULL',
	),
	array(
		'id' => '6',
		'stringid' => 'emailparser',
		'name' => _a('Email (Un)Subscriptions Parser'),
		'descript' => _a('This will read all incoming emails via POP3 protocol, parse them and adds/remove senders from/to lists.'),
		'active' => 1,
		'filename' => './manage/functions/crons/emailparser.php',
		'loglevel' => 1,
		'minute' => 'a:2:{i:0;i:-2;i:1;i:30;}',
		'hour' => -1,
		'day' => -1,
		'weekday' => -1,
		'=lastrun' => 'NULL',
	),
	array(
		'id' => '7',
		'stringid' => 'dbsync',
		'name' => _a('Database Synchronization'),
		'descript' => _a('This will start a sync for all setup synchronization jobs in the system.'),
		'active' => 1,
		'filename' => './manage/functions/crons/dbsync.php',
		'loglevel' => 1,
		'minute' => 'a:2:{i:0;i:-2;i:1;i:0;}',
		'hour' => '2',
		'day' => -1,
		'weekday' => -1,
		'=lastrun' => 'NULL',
	),
	array(
		'id' => '8',
		'stringid' => 'dbbackup',
		'name' => _a('Database Backup'),
		'descript' => _a('Database Backup (a part of Database Utilities) saves a database backup to a location specified.'),
		'active' => 0,
		'filename' => './manage/functions/crons/dbbackup.php',
		'loglevel' => 1,
		'minute' => 'a:2:{i:0;i:-2;i:1;i:0;}',
		'hour' => '3',
		'day' => -1,
		'weekday' => -1,
		'=lastrun' => 'NULL',
	),
	array(
		'id' => '9',
		'stringid' => 'utilities',
		'name' => _a('Utilities'),
		'descript' => _a('This will cleanup redundant data (such as old logs), perform timed utility actions and optimize the tables.'),
		'active' => 1,
		'filename' => './manage/functions/crons/utilities.php',
		'loglevel' => 1,
		'minute' => 'a:2:{i:0;i:-2;i:1;i:0;}',
		'hour' => '4',
		'day' => -1,
		'weekday' => -1,
		'=lastrun' => 'NULL',
	),
);
$done = !$fatal && adesk_sql_insert('#cron', $insert);
if ( !$done ) {
	spit(_a('Error'), 'strong|error', 1);
	error_save("QUERY FAILED: " . adesk_sql_lastquery() . "\n\n ERROR: " . adesk_sql_error(), true);
} else {
	spit(_a('Done'), 'strong|done', 1);
}
if ( $fatal ) return;


// twitter dummy subscriber
spit(_a('Adding Twitter subscriber: '), 'em');
$insert = array(
	'id' => 0,
	'=cdate' => 'NOW()',
	'email' => 'twitter',
	//'first_name' => _a('Twitter'),
	//'last_name' => _a('Account'),
	'=ip' => "INET_ATON('127.0.0.1')",
	'hash' => 'twitter',
);
$done = adesk_sql_insert('#subscriber', $insert);
if ( !$done ) {
	spit(_a('Error'), 'strong|error', 1);
	error_save("QUERY FAILED: " . adesk_sql_lastquery() . "\n\n ERROR: " . adesk_sql_error(), true);
	return;
} else {
	spit(_a('Done'), 'strong|done', 1);
}


// adding the initial list
spit(_a('Adding a Mailing List: '), 'em');
$insert = array(
	'id' => 0,
	'stringid' => 'general-list',
	'userid' => 1,
	'name' => _a('General List'),
	'=cdate' => 'NOW()',
);
$done = adesk_sql_insert('#list', $insert);
if ( !$done ) {
	spit(_a('Error'), 'strong|error', 1);
	error_save("QUERY FAILED: " . adesk_sql_lastquery() . "\n\n ERROR: " . adesk_sql_error(), true);
	return;
}
$listid = adesk_sql_insert_id();
$insert = array(
	'id' => 0,
	'listid' => $listid,
	'emailconfid' => 1,
);
$done = adesk_sql_insert('#optinoptout_list', $insert);
if ( !$done ) {
	spit(_a('Error'), 'strong|error', 1);
	error_save("QUERY FAILED: " . adesk_sql_lastquery() . "\n\n ERROR: " . adesk_sql_error(), true);
	return;
}
$insert = array(
	'id' => 0,
	'listid' => $listid,
	'bounceid' => 1,
);
$done = adesk_sql_insert('#bounce_list', $insert);
if ( !$done ) {
	spit(_a('Error'), 'strong|error', 1);
	error_save("QUERY FAILED: " . adesk_sql_lastquery() . "\n\n ERROR: " . adesk_sql_error(), true);
	return;
}
// adding permission to access this list #1
$insert = array(
	array(
		'id' => 0,
		'listid' => $listid,
		'groupid' => 1,
		'p_list_add' => 0,
		'p_list_edit' => 0,
		'p_list_delete' => 0,
		'p_list_sync' => 0,
		'p_list_filter' => 0,
		'p_message_add' => 0,
		'p_message_edit' => 0,
		'p_message_delete' => 0,
		'p_message_send' => 0,
		'p_subscriber_add' => 0,
		'p_subscriber_edit' => 0,
		'p_subscriber_delete' => 0,
		'p_subscriber_import' => 0,
		'p_subscriber_approve' => 0,
	),
	array(
		'id' => 0,
		'listid' => $listid,
		'groupid' => 3,
		'p_list_add' => 1,
		'p_list_edit' => 1,
		'p_list_delete' => 1,
		'p_list_sync' => 1,
		'p_list_filter' => 1,
		'p_message_add' => 1,
		'p_message_edit' => 1,
		'p_message_delete' => 1,
		'p_message_send' => 1,
		'p_subscriber_add' => 1,
		'p_subscriber_edit' => 1,
		'p_subscriber_delete' => 1,
		'p_subscriber_import' => 1,
		'p_subscriber_approve' => 1,
	),
);
$done = adesk_sql_insert('#list_group', $insert);
if ( !$done ) {
	spit(_a('Error'), 'strong|error', 1);
	error_save("QUERY FAILED: " . adesk_sql_lastquery() . "\n\n ERROR: " . adesk_sql_error(), true);
	return;
}
$insert = array(
	'id' => 0,
	'listid' => $listid,
	'userid' => 1,
	'p_admin' => 1,
	'p_list_add' => 1,
	'p_list_edit' => 1,
	'p_list_delete' => 1,
	'p_list_sync' => 1,
	'p_list_filter' => 1,
	'p_message_add' => 1,
	'p_message_edit' => 1,
	'p_message_delete' => 1,
	'p_message_send' => 1,
	'p_subscriber_add' => 1,
	'p_subscriber_edit' => 1,
	'p_subscriber_delete' => 1,
	'p_subscriber_import' => 1,
	'p_subscriber_approve' => 1,
);
$done = adesk_sql_insert('#user_p', $insert);
if ( !$done ) {
	spit(_a('Error'), 'strong|error', 1);
	error_save("QUERY FAILED: " . adesk_sql_lastquery() . "\n\n ERROR: " . adesk_sql_error(), true);
	return;
} else {
	spit(_a('Done'), 'strong|done', 1);
}



// old stuff
// set trapperr
spit(_a('Adding error handling settings: '), 'em');
$done = (
	adesk_sql_query("INSERT INTO `#trapperr` (`id`, `value`) VALUES ('xml_date_format', 'Y-m-d H:i:s (T)')") and
	adesk_sql_query("INSERT INTO `#trapperr` (`id`, `value`) VALUES ('sql_date_format', 'Y-m-d H:i:s')") and
	adesk_sql_query("INSERT INTO `#trapperr` (`id`, `value`) VALUES ('date_format', 'G:i:s, j. n. Y. (T)')") and
	adesk_sql_query("INSERT INTO `#trapperr` (`id`, `value`) VALUES ('db', '1')") and
	adesk_sql_query("INSERT INTO `#trapperr` (`id`, `value`) VALUES ('db_table', '{$GLOBALS['adesk_prefix_use']}trapperrlogs')") and
	adesk_sql_query("INSERT INTO `#trapperr` (`id`, `value`) VALUES ('mail', '0')") and
	adesk_sql_query("INSERT INTO `#trapperr` (`id`, `value`) VALUES ('mail_to', 'bugs@awebdesk.com')") and
	adesk_sql_query("INSERT INTO `#trapperr` (`id`, `value`) VALUES ('mail_subject', '{$GLOBALS['adesk_app_name']} PHP Error')") and
	adesk_sql_query("INSERT INTO `#trapperr` (`id`, `value`) VALUES ('screen', '1')") and
	adesk_sql_query("INSERT INTO `#trapperr` (`id`, `value`) VALUES ('logfile', '0')") and
	adesk_sql_query("INSERT INTO `#trapperr` (`id`, `value`) VALUES ('user_error_is_deadly', '0')")
);
if ( !$done ) {
	spit(_a('Error'), 'strong|error', 1);
	error_save("QUERY FAILED: " . adesk_sql_lastquery() . "\n\n ERROR: " . adesk_sql_error());
} else {
	spit(_a('Done'), 'strong|done', 1);
}


// message templates import
spit(_a('Importing message templates from a directory on the server: '), 'em');
$templates_import = import_files("template", "xml");
spit(_a('Done'), 'strong|done', 1);


// awebdesk_service - "External Services" - insert default rows
$service_facebook = array("name" => "Facebook", "description" => "Configure Facebook application settings.");
$service_twitter = array("name" => "Twitter", "description" => "Configure Twitter application settings.");
adesk_sql_insert("#service", $service_facebook);
adesk_sql_insert("#service", $service_twitter);

?>
