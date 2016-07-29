<?php
if(!session_id()) session_start();

// if(file_exists(dirname(__FILE__).'/database.php')) require_once(dirname(__FILE__).'/database.php');
require_once(dirname(dirname(__FILE__)).'/mauticapi/vendor/autoload.php');
require_once(dirname(dirname(__FILE__)).'/config.php');

use Mautic\MauticApi;
use Mautic\Auth\ApiAuth;

$settings = array(
    'baseUrl'          => glc_option('mautic_base_url'),       // Base URL of the Mautic instance
    'version'          => 'OAuth2', // Version of the OAuth can be OAuth2 or OAuth1a. OAuth2 is the default value.
    'clientKey'        => glc_option('mautic_client_key'),       // Client/Consumer key from Mautic
    'clientSecret'     => glc_option('mautic_client_secret'),       // Client/Consumer secret key from Mautic
    'callback'         => sprintf('%s/glc/class/mautic.php', GLC_URL)        // Redirect URI/Callback URI for this script
);

if(!empty(glc_option('mautic_accessToken'))):
    $settings['accessToken']        = glc_option('mautic_accessToken');
    $settings['accessTokenExpires'] = glc_option('mautic_accessTokenExpires'); 
    $settings['refreshToken']       = glc_option('mautic_refreshToken');
endif;

$auth = ApiAuth::initiate($settings);

if ($auth->validateAccessToken()) {
    if ($auth->accessTokenUpdated()) {
        $accessTokenData = $auth->getAccessTokenData();
        glc_update_option('mautic_accessToken', $accessTokenData['access_token']);
        glc_update_option('mautic_accessTokenExpires', $accessTokenData['expires']);
        glc_update_option('mautic_refreshToken', $accessTokenData['refresh_token']);
        print_r($accessTokenData);
    }
}

$leadApi = MauticApi::getContext("users", $auth, 'http://mautic.local/api/');
echo "<pre>";
$params = array(
    'username'      => 'testuser1',
    'password'      => '123456',
    'first_name'    => 'Test',
    'last_name'     => 'User',
    'email'         => 'testuser1@gmail.com',
    'position'      => 1,
    'role_id'       => 1
);
$fields = $leadApi->create($params);
echo "<pre>";
print_r($fields);
