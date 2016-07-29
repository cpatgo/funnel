<?php
/**
 *   @copyright Copyright (c) 2016 Quality Unit s.r.o.
 *
 *   Licensed under the Quality Unit, s.r.o. Standard End User License Agreement,
 *   Version 1.0 (the "License"); you may not use this file except in compliance
 *   with the License. You may obtain a copy of the License at
 *   http://www.qualityunit.com/licenses/license
 */

/**
 * Auto generated code from swagger api description. DO NOT EDIT !!!!
 * Codegen version: 1.5.0
 */
require '../../scripts/bootstrap_api.php';

$app = new \Slim\Slim();

try {
    RestApi_Make::init(new Pap_Api_Make());
} catch (Exception $ignore) {}

try {
    // You must implement this class, it will not be generated !!! It must extend RestApi_Auth
    $auth = Pap_Api_Auth::init($app->request());
} catch (Exception $e) {
    $response = new RestApi_Response($app->response());
    $response->setError($e);
    $response->send();
    exit();
}

/*
 * Update affiliate status
 */
$app->put('/affiliates/:id/_changeStatus', function ($id) use ($app, $auth) {
    $params = new RestApi_Params($app->request(), array('affiliate.write'));
    $response = new RestApi_Response($app->response());
    try { 
        $params->check('status', true, '', 'string', array('A', 'D', 'P'));
        $auth->checkScope($params->getScopes());
        $handler = new Pap_Api_V2_Affiliates($params);
        $response->setResult($handler->changeStatus($id));
    } catch (Exception $e) {
        $response->setError($e);
    }
});

/*
 * Get informations about specified affiliate user.
 */
$app->get('/affiliates/:id', function ($id) use ($app, $auth) {
    $params = new RestApi_Params($app->request(), array('affiliate.read'));
    $response = new RestApi_Response($app->response());
    try { 
        $params->check('_fields', false, '', 'string[]', null);
        $auth->checkScope($params->getScopes());
        $handler = new Pap_Api_V2_Affiliates($params);
        $response->setResult($handler->get($id));
    } catch (Exception $e) {
        $response->setError($e);
    }
});

/*
 * 
 */
$app->get('/affiliates', function () use ($app, $auth) {
    $params = new RestApi_Params($app->request(), array('affiliate.read'));
    $response = new RestApi_Response($app->response());
    try { 
        $params->check('_page', false, '1', 'int', null);
        $params->check('_perPage', false, '30', 'int', null);
        $params->check('_sortDir', false, 'ASC', 'string', array('ASC', 'DESC'));
        $params->check('_sortField', false, '', 'string', null);
        $params->check('_filters', false, '', 'string', null);
        $params->check('_fields', false, '', 'string[]', null);
        $auth->checkScope($params->getScopes());
        $handler = new Pap_Api_V2_Affiliates($params);
        $response->setResult($handler->getList());
    } catch (Exception $e) {
        $response->setError($e);
    }
});

/*
 * Get informations about campaign.
 */
$app->get('/campaigns/:id', function ($id) use ($app, $auth) {
    $params = new RestApi_Params($app->request(), array('campaign.read'));
    $response = new RestApi_Response($app->response());
    try { 
        $params->check('_fields', false, '', 'string[]', null);
        $auth->checkScope($params->getScopes());
        $handler = new Pap_Api_V2_Campaigns($params);
        $response->setResult($handler->get($id));
    } catch (Exception $e) {
        $response->setError($e);
    }
});

/*
 * 
 */
$app->get('/campaigns', function () use ($app, $auth) {
    $params = new RestApi_Params($app->request(), array('campaign.read'));
    $response = new RestApi_Response($app->response());
    try { 
        $params->check('_page', false, '1', 'int', null);
        $params->check('_perPage', false, '30', 'int', null);
        $params->check('_sortDir', false, 'ASC', 'string', array('ASC', 'DESC'));
        $params->check('_sortField', false, '', 'string', null);
        $params->check('_filters', false, '', 'string', null);
        $params->check('_fields', false, '', 'string[]', null);
        $auth->checkScope($params->getScopes());
        $handler = new Pap_Api_V2_Campaigns($params);
        $response->setResult($handler->getList());
    } catch (Exception $e) {
        $response->setError($e);
    }
});

/*
 * Update transaction status.
 */
$app->put('/directlinks/:id/_changeStatus', function ($id) use ($app, $auth) {
    $params = new RestApi_Params($app->request(), array('direct_link.write'));
    $response = new RestApi_Response($app->response());
    try { 
        $params->check('status', true, '', 'string', array('A', 'D', 'P'));
        $params->check('note', false, '', 'string', null);
        $auth->checkScope($params->getScopes());
        $handler = new Pap_Api_V2_DirectLinks($params);
        $response->setResult($handler->changeStatus($id));
    } catch (Exception $e) {
        $response->setError($e);
    }
});

/*
 * 
 */
$app->get('/directlinks', function () use ($app, $auth) {
    $params = new RestApi_Params($app->request(), array('direct_link.read_own'));
    $response = new RestApi_Response($app->response());
    try { 
        $params->check('_page', false, '1', 'int', null);
        $params->check('_perPage', false, '30', 'int', null);
        $params->check('_sortDir', false, 'ASC', 'string', array('ASC', 'DESC'));
        $params->check('_sortField', false, '', 'string', null);
        $params->check('_filters', false, '', 'string', null);
        $params->check('_fields', false, '', 'string[]', null);
        $auth->checkScope($params->getScopes());
        $handler = new Pap_Api_V2_DirectLinks($params);
        $response->setResult($handler->getList());
    } catch (Exception $e) {
        $response->setError($e);
    }
});

/*
 * Get &#x60;Message&#x60; object when request hits server API.
 */
$app->get('/ping', function () use ($app, $auth) {
    $params = new RestApi_Params($app->request());
    $response = new RestApi_Response($app->response());
    try { 
        $handler = new Pap_Api_V2_Ping($params);
        $response->setResult($handler->get());
    } catch (Exception $e) {
        $response->setError($e);
    }
});

/*
 * Subscribe to push notifications.
 */
$app->post('/pushnotifications', function () use ($app, $auth) {
    $params = new RestApi_Params($app->request());
    $response = new RestApi_Response($app->response());
    try { 
        $data = new Pap_Api_Model_PushNotificationRegistration($params->getBody());
        $data->check();
        $handler = new Pap_Api_V2_PushNotification($params);
        $response->setResult($handler->add($data));
    } catch (Exception $e) {
        $response->setError($e);
    }
});

/*
 * Unsubscribe from push notifications.
 */
$app->delete('/pushnotifications/:pushToken', function ($pushToken) use ($app, $auth) {
    $params = new RestApi_Params($app->request());
    $response = new RestApi_Response($app->response());
    try { 
        $handler = new Pap_Api_V2_PushNotification($params);
        $response->setResult($handler->delete($pushToken));
    } catch (Exception $e) {
        $response->setError($e);
    }
});

/*
 * Get push notification details per user and device.
 */
$app->GET('/pushnotifications/:pushToken', function ($pushToken) use ($app, $auth) {
    $params = new RestApi_Params($app->request());
    $response = new RestApi_Response($app->response());
    try { 
        $handler = new Pap_Api_V2_PushNotification($params);
        $response->setResult($handler->get($pushToken));
    } catch (Exception $e) {
        $response->setError($e);
    }
});

/*
 * Get server settings.
 */
$app->get('/settings', function () use ($app, $auth) {
    $params = new RestApi_Params($app->request(), array('currency.read'));
    $response = new RestApi_Response($app->response());
    try { 
        $auth->checkScope($params->getScopes());
        $handler = new Pap_Api_V2_Settings($params);
        $response->setResult($handler->get());
    } catch (Exception $e) {
        $response->setError($e);
    }
});

/*
 * Get server timezone difference in seconds.
 */
$app->get('/synctime', function () use ($app, $auth) {
    $params = new RestApi_Params($app->request());
    $response = new RestApi_Response($app->response());
    try { 
        $params->check('clientTime', true, '', 'string', null);
        $handler = new Pap_Api_V2_Settings($params);
        $response->setResult($handler->syncTime());
    } catch (Exception $e) {
        $response->setError($e);
    }
});

/*
 * Get traffic summary data of specified period.
 */
$app->get('/traffic', function () use ($app, $auth) {
    $params = new RestApi_Params($app->request());
    $response = new RestApi_Response($app->response());
    try { 
        $params->check('from', true, '', 'string', null);
        $params->check('to', false, '', 'string', null);
        $handler = new Pap_Api_V2_Traffic($params);
        $response->setResult($handler->get());
    } catch (Exception $e) {
        $response->setError($e);
    }
});

/*
 * Update transaction status.
 */
$app->put('/transactions/:id/_changeStatus', function ($id) use ($app, $auth) {
    $params = new RestApi_Params($app->request(), array('transaction.write'));
    $response = new RestApi_Response($app->response());
    try { 
        $params->check('status', true, '', 'string', array('A', 'D', 'P'));
        $params->check('note', false, '', 'string', null);
        $auth->checkScope($params->getScopes());
        $handler = new Pap_Api_V2_Transactions($params);
        $response->setResult($handler->changeStatus($id));
    } catch (Exception $e) {
        $response->setError($e);
    }
});

/*
 * Get informations about trasnaction.
 */
$app->get('/transactions/:id', function ($id) use ($app, $auth) {
    $params = new RestApi_Params($app->request(), array('transaction.read', 'transaction.read_own'));
    $response = new RestApi_Response($app->response());
    try { 
        $params->check('_fields', false, '', 'string[]', null);
        $auth->checkScope($params->getScopes());
        $handler = new Pap_Api_V2_Transactions($params);
        $response->setResult($handler->get($id));
    } catch (Exception $e) {
        $response->setError($e);
    }
});

/*
 * Get list of transactions.
 */
$app->get('/transactions', function () use ($app, $auth) {
    $params = new RestApi_Params($app->request(), array('transaction.read', 'transaction.read_own'));
    $response = new RestApi_Response($app->response());
    try { 
        $params->check('_page', false, '1', 'int', null);
        $params->check('_perPage', false, '30', 'int', null);
        $params->check('_sortDir', false, 'ASC', 'string', array('ASC', 'DESC'));
        $params->check('_sortField', false, '', 'string', null);
        $params->check('_filters', false, '', 'string', null);
        $params->check('_fields', false, '', 'string[]', null);
        $auth->checkScope($params->getScopes());
        $handler = new Pap_Api_V2_Transactions($params);
        $response->setResult($handler->getList());
    } catch (Exception $e) {
        $response->setError($e);
    }
});

/*
 * Get informations about specified user.
 */
$app->get('/users/:userId', function ($userId) use ($app, $auth) {
    $params = new RestApi_Params($app->request(), array('merchant.read', 'myprofile.read', 'affiliate.read'));
    $response = new RestApi_Response($app->response());
    try { 
        $params->check('_fields', false, '', 'string[]', null);
        $auth->checkScope($params->getScopes());
        $handler = new Pap_Api_V2_Users($params);
        $response->setResult($handler->get($userId));
    } catch (Exception $e) {
        $response->setError($e);
    }
});

/*
 * 
 */
$app->get('/users', function () use ($app, $auth) {
    $params = new RestApi_Params($app->request(), array('merchant.read', 'myprofile.read', 'affiliate.read'));
    $response = new RestApi_Response($app->response());
    try { 
        $params->check('_page', false, '1', 'int', null);
        $params->check('_perPage', false, '30', 'int', null);
        $params->check('_sortDir', false, 'ASC', 'string', array('ASC', 'DESC'));
        $params->check('_sortField', false, '', 'string', null);
        $params->check('_filters', false, '', 'string', null);
        $params->check('_fields', false, '', 'string[]', null);
        $params->check('users_type', false, 'A', 'string', null);
        $auth->checkScope($params->getScopes());
        $handler = new Pap_Api_V2_Users($params);
        $response->setResult($handler->getList());
    } catch (Exception $e) {
        $response->setError($e);
    }
});

/*
 * Return access token.
 */
$app->get('/token', function () use ($app, $auth) {
    $params = new RestApi_Params($app->request());
    $response = new RestApi_Response($app->response());
    try { 
        $params->check('username', true, '', 'string', null);
        $params->check('password', true, '', 'string', null);
        $handler = new RestApi_Token($params);
        $response->setResult($handler->get());
    } catch (Exception $e) {
        $response->setError($e);
    }
});

/*
 * Request password reset.
 */
$app->post('/resetpassword', function () use ($app, $auth) {
    $params = new RestApi_Params($app->request());
    $response = new RestApi_Response($app->response());
    try { 
        $params->check('username', true, '', 'string', null);
        $handler = new RestApi_Token($params);
        $response->setResult($handler->resetpassword());
    } catch (Exception $e) {
        $response->setError($e);
    }
});

$app->run();
