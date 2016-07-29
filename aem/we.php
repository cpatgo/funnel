<?php

define('AWEBVIEW', true);
if ( !isset($_GET['useauth']) ) define('AWEBP_USER_NOAUTH', true);

// require main include file
require_once('manage/awebdeskend.inc.php');
require_once adesk_admin("functions/message.php");
require_once adesk_admin("functions/campaign.php");
require_once adesk_admin("functions/socialsharing.php");
require_once(awebdesk_functions('ajax.php'));

// Preload the language file
adesk_lang_get( !isset($_GET['useauth']) ? 'admin' : 'public' );

if ( !adesk_http_param_exists('c') || !adesk_http_param_exists('m') || !adesk_http_param_exists('s') ) exit();

$c = (int)adesk_http_param('c');
$m = (int)adesk_http_param('m');
$hash = trim((string)adesk_http_param('s'));
// stumbleupon uses "referral"
$ref = ( adesk_http_param_exists('referral') ) ? adesk_http_param('referral') : adesk_http_param('ref');
$email = '_t.e.s.t_@example.com';
if ( $hash != '' ) {
	$subscriber = subscriber_exists($hash, 0, 'hash');
	if ( $subscriber ) {
		$email = $subscriber['email'];
	}
}

$url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

//list($url, $link, $socmedia) = socialsharing_process_link($c, $m, $subscriber['id'], $url, null);
list($url, $link, $socmedia) = socialsharing_process_link($c, $m, $hash, $url, null);

adesk_http_redirect($url, $stop = false);

//subscriber_action_dispatch("social", $subscriber, null, $campaign, null, $socmedia);

?>