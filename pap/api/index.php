<?php
/**
 *   @copyright Copyright (c) 2011 Quality Unit s.r.o.
 *   @author Juraj Simon
 *   @since Version 4.0.0
 *
 *   Licensed under the Quality Unit, s.r.o. Standard End User License Agreement,
 *   Version 1.0 (the "License"); you may not use this file except in compliance
 *   with the License. You may obtain a copy of the License at
 *   http://www.qualityunit.com/licenses/license
 */

require_once '../scripts/bootstrap.php';

$apiKey = @$_REQUEST['apikey'];
$handler = explode("/", @$_REQUEST['handler']);

if ($apiKey != Gpf_Settings::get(Gpf_Settings_Gpf::API_KEY)) {
	Gpf_Http::setHeader('Error', 'Ivalid api key', 500);
	die();
}

if (count($handler) != 2 || $handler[0] != 'merchants') {
	Gpf_Http::setHeader('Error', 'Ivalid request, handler not found', 500);
	die();
}

$authUser = new Gpf_Db_AuthUser();
$authUser->setUsername($handler[1]);
try {
	$authUser->loadFromData();
} catch (Gpf_Exception $e) {
	Gpf_Http::setHeader('Error', 'Unknown user', 500);
	die();
}

$response = new stdClass();
$response->response = new stdClass();
$response->response->authtoken = $authUser->getAuthToken();
//TODO: is this really necessary?
$response->authtoken = $authUser->getAuthToken();

echo Gpf_Rpc_Json::encodeStatic($response);
