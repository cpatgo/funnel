<?php
// public side switch
define('AWEBVIEW', true);
define('AWEBP_USER_NOAUTH', true);

// require main include file
require_once(dirname(__FILE__) . '/manage/awebdeskend.inc.php');

/*
	WHITELIST
*/
$allowed = array(
	// campaign actions
	"campaign.campaign_select_array_paginator_public" => "",
	"campaign.campaign_filter_post" => "",
	// subscription forms
	"form.form_list_change" => "",
	// list-related actions
	"list.list_field_update" => "",
	"list.list_filter_post" => "",
	"list.list_select_array_paginator_public" => "",
	// subscriber actions
	"subscriber.subscriber_subscribe" => "",
	"subscriber.subscriber_unsubscribe" => "",
	"subscriber.subscriber_update" => "",
	"subscriber.subscriber_update_request" => "",
	// approval
	'approval.approval_approve' => '',
	'approval.approval_decline' => '',
	'approval.approval_decline_only' => '',
	// abuse
	'abuse.abuse_list' => '',
	'abuse.abuse_reset' => '',
	'abuse.abuse_notify' => '',
	'abuse.abuse_update' => '',
  // sso
   // Disabled for security reasons
	"singlesignon!adesk_sso_token_generate" => "",
	"singlesignon!adesk_sso_sameserver" => "", 
);


/*
	REMOTE WHITELIST
*/
$whitelist = array(
	// singlesignon
	'singlesignon' => array(
		'action' => 'singlesignon!adesk_sso_token_generate',
		'params' => array('sso_addr', 'sso_user', 'sso_pass', 'sso_duration'),
	),
	'singlesignon_sameserver' => array(
		'action' => 'singlesignon!adesk_sso_sameserver',
		'params' => array(),
	), 
);


// require ajax functions
require_once(awebdesk_functions('ajax.php'));

// Preload the language file
adesk_lang_get('public');


/*
	SUPPORT FOR REMOTE API
*/
// check if it's remote api
$api_user   = (string)adesk_http_param('api_user');
$api_pass   = (string)adesk_http_param('api_pass');
$api_pass_md5 = (string)adesk_http_param("api_pass_h");
$api_action = (string)adesk_http_param('api_action');
$api_output = (string)adesk_http_param('api_output');
// if it is a remote api
if ( /*$api_user and*/ $api_action ) {
	// adjust defaults
	if ( !$api_output ) $api_output = 'xml';

	define('adesk_API_REMOTE', 1);
	define('adesk_API_REMOTE_OUTPUT', $api_output);

	if ( isset($whitelist[$api_action]) ) {
		// convert input into ajax-style
		$_GET = adesk_api_input($whitelist[$api_action]);

		/*
			perform login
		*/
		if ( $api_user ) {
			//die("Sorry Remote API Disabled.");
			require_once awebdesk_functions("tz.php");
			require_once awebdesk_functions("loginsource.php");
			require_once awebdesk_classes("loginsource.php");
			// do login source stuff first (setup)
			adesk_loginsource_sync();
			$source = adesk_loginsource_determine($api_user, $api_pass, 0);

			session_load($GLOBALS["site"]);

			if ($source !== false) {
				$GLOBALS["loginsource"] = new $source["_classname"]($source);
			} else {
				die("This should never happen.");
			}

			// try to authenticate
			$authenticated = adesk_auth_login_plain($api_user, $api_pass);
			if ( !$authenticated and strlen($api_pass_md5) == 32 and $api_pass_md5==md5($api_pass)) {
				$authenticated = adesk_auth_login_md5($api_user, $api_pass_md5);
			}
			if ( $authenticated ) {
				// authenticated, refetch admin user
				adesk_session_drop_cache();
				unset($admin);
				$admin = adesk_admin_get();
				tz_checkdst();
			}
		}
	}  
}


// do branding changes by list filter
require(adesk_admin('functions/inc.branding.public.php'));

// require ajax include
require_once awebdesk_includes("awebdeskapi.php");

?>
